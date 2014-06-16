<?PHP

#################################################
##                                             ##
##               Link Up Gold                  ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                                             ##
##               version:  8.0                 ##
##            copyright (c) 2012               ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

// rebuild.php?action=XXX[&key=$s[secretword]&result=1]

include('./common.php');
if (($s[secretword]) AND ($_GET[word]!=$s[secretword])) { echo 'Missing or wrong key.'; exit; }
set_time_limit(600);

if ($_GET[action]=='import_rss')
{ $category_vars = get_category_variables(round($_GET[c]));
  if (!$category_vars[n]) exit;
  rss_news_import($category_vars,20);
  exit;	
}
elseif ($_GET[action]=='import_youtube')
{ $category_vars = get_category_variables(round($_GET[c]));
  if (!$category_vars[n]) exit;
  youtube_import($category_vars);
  exit;	
}
elseif ($_GET[action]=='import_dmoz')
{ $category_vars = get_category_variables(round($_GET[c]));
  if (!$category_vars[n]) exit;
  $dmoz_data = read_dmoz_page($category_vars);
  foreach ($dmoz_data[urls] as $k1=>$current_url)
  { if (!preg_match("/^(https?:\/\/+[\w\-]+\.[\w\-]+)/i", $current_url)) continue;
    set_time_limit(30);
    $b[url] = replace_once_text($current_url);
    $q = dq("select count(*) from $s[pr]links where url = '$b[url]'",1); $x = mysql_fetch_row($q); if ($x[0]) continue;
    $b[title] = replace_once_text($dmoz_data[titles][$k1]);
    $b[description] = refund_html(replace_once_text($dmoz_data[descriptions][$k1]));
    $b[categories][0] = $category_vars[n];
    $n = enter_link($b);
    dq("insert into $s[pr]usit_search (use_for,n) values('l','$n')",1);
    update_item_index('l',$n);
  }
  recount_items_cats('l',$category_vars[n],'');
  exit;	
}
elseif ($_GET[newsletter])
{ $newsletter_n = round($_GET[newsletter]);
  $days = round($_GET[days]); if (!$days) $days = 7;
  if (file_exists("$s[phppath]/styles/_common/email_templates/newsletter$newsletter_n.txt")) $template = join ('',file("$s[phppath]/styles/_common/email_templates/newsletter$newsletter_n.txt"));
  else $template = join ('',file("$s[phppath]/styles/_common/email_templates/newsletter.txt"));
  preg_match("/Subject: +([^\n\r]+)/i",$template,$regs); $subject = $regs[1];
  $subject = str_replace('HTML_EMAIL','',$subject); if ($subject!=$regs[1]) $htmlmail = 1;
  $text = preg_replace("/Subject: +([^\n\r]+)[\r\n]+/i",'',$template);
  $cas = $s[cas] - ($days * 86400);
  $q = dq("select n,name from $s[pr]cats",1); while ($x = mysql_fetch_assoc($q)) $categories[$x[n]] = $x[name];
  foreach ($s[item_types_short] as $k=>$what)
  { $where = get_where_fixed_part('',0,'',$s[cas]);
    $table = $s[item_types_tables][$what];
    $q = dq("select * from $table where created > '$cas' and $where order by created desc",1);
    while ($item = mysql_fetch_assoc($q))
    { $item[created] = datum ($item[created],0);
      $item[url] = get_detail_page_url($what,$item[n],$item[rewrite_url],0,1);
      $cat = explode(' ',str_replace('_','',$item[c]));
      $item[category] = $categories[$cat[0]];
      $item[catlink] = category_url($what,$cat[0],0,$categories[$cat[0]],1,'','','','');
      $b[$s[item_types_words][$what]] .= parse_part('newsletter_item.txt',$item,1);
    }
  }
  $emaily = dq("select * from $s[pr]users where news$newsletter_n = '1' and approved = '1' and confirmed = '1'",0);
  while ($address = mysql_fetch_assoc($emaily))
  { $line = $text; $subject = unreplace_once_html($subject);
    foreach ($s[items_types_words] as $k=>$what) $value[$what] = $b[$s[item_types_words][$k]];
    $value[name] = $address[name]; $value[email] = $address[email];
    $value[unsubscribe] = "$s[site_url]/";
    foreach($value as $k => $v) $line = str_replace("#%$k%#",$v,$line);
    $line = unreplace_once_html($line);
    $line = unhtmlentities($line); $subject = unhtmlentities($subject);
    my_send_mail('','',$address[email],$data[htmlmail],$subject,$line,1);
    //echo "$address[email],$subject,$line<br><br><br><br>";
    set_time_limit(30);
    //echo "$address[email]<br />\n";
  }
  unset($data);
  $fp = fopen("$s[phppath]/data/newsletter.php","w");
  $s["send_newsletter_$newsletter_n"] = $s[cas];
  for ($x=1;$x<=5;$x++) { if (!$s["send_newsletter_$x"]) $s["send_newsletter_$x"] = 0; $data .= '$s[send_newsletter_'.$x.'] = '.$s["send_newsletter_$x"].';'; }
  fwrite ($fp,'<?PHP '.$data.' ?>');
  fclose($fp);
  chmod("$s[phppath]/data/newsletter.php",0666);
  exit;	
}




include("$s[phppath]/administration/rebuild_functions.php");

if ($s[day]==1)
{ load_times();
  if ($s[times_d]<$s[cas]-6000) reset_month($_GET[result]);
}

if ($s[A_option]=='static') A_daily_job($_GET[result]);
else daily_job($_GET[result]);

?>