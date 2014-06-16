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

$s[where_fixed_part] = get_where_fixed_part('',0,0,$s[cas]);
$s[styles_list] = get_styles_list(0);
include_once("$s[phppath]/data/data_forms.php");

function daily_job($showresult) {
global $s;
set_time_limit(600);
new_time('times_d');
count_stats($showresult);
delete_old($showresult);
//update_countries_map();
update_popular($showresult);
update_sponsored_status($showresult);
if ($s[daily_recount]) foreach ($s[item_types_short] as $k=>$v) { $function = 'recount_all_'.$s[items_types_words][$v]; $function(); }
rebuild_static_files($showresult,0);
//email_sponsored_links($showresult);
email_users_new_items($showresult);
if ($s[sitemap_location]) create_sitemap($showresult);
}

################################################################################
################################################################################
################################################################################

function update_index_suggest($showresult) {
global $s;
$search = array('&amp;',"&#039;",'"','\(','\)','-');
$replace = array('&',"'",'','','');
$x = explode(',',$s[ignored_tags]);
foreach ($x as $k=>$v) { $v = trim($v); if ($v) $search_replace1[] = $v; }
$q = dq("SELECT DISTINCT `word`,COUNT(`word`) AS num_logs FROM `$s[pr]index_suggest` GROUP BY `word` ORDER BY num_logs DESC LIMIT 100",1);
while ($x = mysql_fetch_assoc($q))
{ //$x1 = trim(str_replace('"','',str_replace('"','',str_replace('"','',unhtmlentities(str_replace("&#039;","'",str_replace('&amp;','&',str_replace(chr(92),'&#92;',$x[word]))))))));
  //$x1 = trim(unhtmlentities($x[word]));
  $x1 = unhtmlentities($x[word]);
  $x1 = str_replace(chr(92),'&#92;',$x1);
  foreach ($search as $k=>$v) $x1 = preg_replace("/$v/i",$replace[$k],$x1);
  $x1 = trim($x1);
  foreach ($search_replace1 as $k=>$v) if (!strcasecmp ($x1,$v)) /*($x1==$v)*/ continue 2;
  if (strlen($x1)<=2) continue;
  if ($x1) { /*if ($pocet<=100) */$top_tags_for_search[] = '"'.str_replace("'",'',$x1).'"'; /*$words[] = '"'.$x1.'"';*/ $pocet++; if ($pocet<=25) $s[top_tags_words][] = $x1; }
}
/*
$data = "var search_suggestions = [".implode(',',$words)."];";
if (!$sb = fopen("$s[phppath]/cache/jquery.js",'w')) die("Cannot write to file 'jquery.js' in your cache directory. Please make sure that your cache directory exists and has 777 permission. Cannot continue.");
fwrite($sb,$data);
chmod("$s[phppath]/cache/jquery.js",0666);
*/
$data = '<?PHP $top_tags = array('.implode(',',$top_tags_for_search).'); ?>';
$sb = fopen("$s[phppath]/data/top_tags.php",'w');
fwrite($sb,$data);
chmod("$s[phppath]/data/top_tags.php",0666);

$info = 'List of top searches updated.<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################

function email_sponsored_links($showresult) {
global $s;
$cas1 = $s[cas] + 259200; $cas2 = $s[cas] + 345600; 
$q = dq("select $s[pr]links.* from $s[pr]links,$s[pr]links_adv where ((d_validby > '$cas1' and d_validby < '$cas2') OR (d_validby_simple > '$cas1' and d_validby_simple < '$cas2')) and $s[pr]links.n = $s[pr]links_adv.n",1);
while ($link = mysql_fetch_assoc($q))
{ if (!$link[email]) return false;
  $link[to] = $link[email];
  $link[login_url] = $s[site_url].'/user.php';
  mail_from_template('advertising_link_end1.txt',$link);
}
$info = 'Sent emails to owners of sponsored links which expire after 72 - 96 hours.<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################

function email_users_new_items($showresult) {
global $s;
foreach ($s[item_types_short] as $k=>$what) if (!$s["bookmarks_cats_email_$what"]) dq("delete from $s[pr]u_to_email where what = '$what'",1);
$q = dq("select * from $s[pr]u_to_email",1);
while ($x=mysql_fetch_assoc($q))
{ $item = get_item_variables($x[what],$x[n]);
  if ($item[status]!='enabled') continue;
  $email[what] = $s[item_types_words][$x[what]];
  $email[title] = $item[title]; $email[url] = get_detail_page_url($x[what],$item[n],$item[rewrite_url],0,1);
  if (!$item[c]) continue;
  $c_array = explode(' ',str_replace('_','',$item[c])); $query = my_implode("$s[pr]u_favorites.n",'or',$c_array);
  $q1 = dq("select $s[pr]users.*,$s[pr]u_favorites.n as category from $s[pr]u_favorites,$s[pr]users where $s[pr]u_favorites.what = 'c_$x[what]' and ($query) and $s[pr]u_favorites.user = $s[pr]users.n",1);
  while ($user = mysql_fetch_assoc($q1))
  { if (!$categories[$user[category]]) { $c = get_category_variables($user[category]); $categories[$user[category]] = $c[name]; }
    $email[to] = $user[email]; $email[category] = $categories[$user[category]];
    mail_from_template('user_new_items.txt',$email);
    unset($email);
    set_time_limit(30);
  }
  dq("delete from $s[pr]u_to_email where what = '$x[what]' and n = '$x[n]'",1);
}
$info = 'Sent emails to users who watch individual categories<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################

function reset_month($showresult) {
global $s;
new_time('times_m');
dq("update $s[pr]links set clicks_in_m = 0, hits_m = 0",1);
dq("update $s[pr]links_stat set i_month = 0, c_month = 0, r_month = 0",1);
dq("update $s[pr]articles set hits_m = 0",1);
dq("update $s[pr]videos set hits_m = 0",1);
dq("update $s[pr]news set hits_m = 0",1);
dq("update $s[pr]blogs set hits_m = 0",1);
$info = 'Monthly statistic reseted<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################
################################################################################
################################################################################

function delete_l_days($in) {
global $s;
dq("delete from $s[pr]links_days where y < '$in[stat_delete_year]'",1);
dq("delete from $s[pr]links_days where y = '$in[stat_delete_year]' AND m <= '$in[stat_delete_month]'",1);
$s[info] = info_line('Selected records have been deleted');
if ($s[A_option]=='static') { header ("Location: html_rebuild.php"); exit; }
else reset_rebuild_home();
}

################################################################################

function delete_old($showresult) {
global $s;
$cas = $s[cas] - 86400; $cas1 = $s[cas] - 604800; //$cas2 = $s[cas] - 86400;
if ($s[news_delete_after]) dq("delete from $s[pr]site_news where time < ($s[cas] - ($s[news_delete_after]*86400))",1);
if ($s[user_unconfirmed_delete_after])
{ dq("delete from $s[pr]users where confirmed = '0' AND joined < ($s[cas]-($s[user_unconfirmed_delete_after]*86400))",1);
  $info .= 'Unconfirmed users joined more than '.$s[user_unconfirmed_delete_after].' days ago deleted<br />';
}
dq("delete from $s[pr]links_ip",1);
$q = dq("select time from $s[pr]board order by time desc",0);
if (mysql_data_seek($q,$s[board])) { $r = mysql_fetch_row($q); dq("delete from $s[pr]board where time <= '$r[0]'",1); }
if ($s[daily_delete_expired]) delete_expired_items(0);
if ($s[l_unconfirmed_delete_after])
{ $q = dq("select n from $s[pr]links where status = 'wait' and created < ($s[cas]-($s[l_unconfirmed_delete_after]*86400))",1);
  while ($x=mysql_fetch_row($q)) $unconfirmed[] = $x[0];
  if ($unconfirmed[0])
  { delete_items('l',$unconfirmed);
    $query = my_implode('n','or',$unconfirmed);
    dq("delete from $s[pr]unconfirmed where what = 'l' and $query",1);
  }
  $info .= 'Unconfirmed links older than '.$s[l_unconfirmed_delete_after].' days deleted<br />';
}
$info .= 'IP records deleted<br />
In the message board table is now no more than '.$s[board].' newest messages<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################
################################################################################
################################################################################

function delete_expired_items($showresult) {
global $s;
foreach ($s[item_types_short] as $k=>$what)
{ $table = $s[item_types_tables][$what];
  $q = dq("select n from $table where t2 > '0' AND t2 < '$s[cas]'",1);
  while ($x = mysql_fetch_row($q)) $numbers[] = $x[0];
  delete_items($what,$numbers);
  unset($numbers);
}
$info = 'Expired links and articles deleted<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################
################################################################################
################################################################################

function update_popular($showresult) {
global $s;
foreach ($s[item_types_tables] as $what=>$table)
{ if ($s[$what.'_popular']) $limit = $s[$what.'_popular']; else $limit = 25;
  dq("update $table set popular = 0",1);
  $q = dq("select n from $table order by hits_m desc limit $limit",1);
  while ($x = mysql_fetch_row($q)) dq("update $table set popular = 1 where n = '$x[0]'",1);
}
$info = 'Popular items updated<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################
################################################################################
################################################################################

function update_sponsored_status($showresult) {
global $s;

$q = dq("select $s[pr]links.n from $s[pr]links,$s[pr]links_adv where $s[pr]links.sponsored = 1 and $s[pr]links.n = $s[pr]links_adv.n and $s[pr]links_adv.c_now = 0 and $s[pr]links_adv.i_now = 0 and $s[pr]links_adv.c_dynamic_now = 0 and $s[pr]links_adv.d_validby < $s[cas] and $s[pr]links_adv.d_validby_simple < $s[cas]",1);
while ($x=mysql_fetch_row($q)) $a[] = $x[0];
if ($a[0])
{ $query = my_implode('n','or',$a);
  dq("update $s[pr]links set sponsored = 0 where $query",1);
}
unset($a);

$q = dq("select $s[pr]links.n from $s[pr]links,$s[pr]links_adv where $s[pr]links.sponsored = 0 and $s[pr]links.n = $s[pr]links_adv.n and ($s[pr]links_adv.c_now >= 1 or $s[pr]links_adv.i_now >= 1 or $s[pr]links_adv.c_dynamic_now >= 1 or $s[pr]links_adv.d_validby > $s[cas] or $s[pr]links_adv.d_validby_simple > $s[cas])",1);
while ($x=mysql_fetch_row($q)) $a[] = $x[0];
if ($a[0])
{ $query = my_implode('n','or',$a);
  dq("update $s[pr]links set sponsored = 1 where $query",1);
}
unset($a);

dq("update $s[pr]links set dynamic_price = 0",1);
$q = dq("select n,c_dynamic_price from $s[pr]links_adv where c_dynamic_now >= 1",1);
while ($x=mysql_fetch_assoc($q)) dq("update $s[pr]links set dynamic_price = '$x[c_dynamic_price]' where n = '$x[n]'",1);

$info = 'Status of sponsored links updated<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

/*
dq("update ($s[pr]links,$s[pr]links_adv) set $s[pr]links.sponsored = 0 where ($s[pr]links.sponsored = 1 and $s[pr]links.n = $s[pr]links_adv.n and $s[pr]links_adv.c_now = 0 and $s[pr]links_adv.i_now = 0 and $s[pr]links_adv.c_dynamic_now = 0 and $s[pr]links_adv.d_validby < $s[cas] and $s[pr]links_adv.d_validby_simple < $s[cas])",1);
dq("update ($s[pr]links,$s[pr]links_adv) set $s[pr]links.sponsored = 1 where ($s[pr]links.sponsored = 0 and $s[pr]links.n = $s[pr]links_adv.n and ($s[pr]links_adv.c_now >= 1 or $s[pr]links_adv.i_now >= 1 or $s[pr]links_adv.c_dynamic_now >= 1 or $s[pr]links_adv.d_validby > $s[cas] or $s[pr]links_adv.d_validby_simple > $s[cas]))",1);
dq("update $s[pr]links set dynamic_price = 0",1);
dq("update ($s[pr]links,$s[pr]links_adv) set ($s[pr]links.dynamic_price = $s[pr]links_adv.c_dynamic_price where $s[pr]links.sponsored = 1 and $s[pr]links_adv.c_dynamic_now >= 1 and $s[pr]links.n = $s[pr]links_adv.n)",1);
*/

################################################################################
################################################################################
################################################################################

function rebuild_static_files($showresult) {
global $s;
dq("truncate table $s[pr]static",1);
update_index_suggest($showresult);
create_in_files($showresult,0);
rebuild_index_categories($showresult);
rebuild_index_categories_groups($showresult);
}

################################################################################

function create_in_files($showresult,$html) {
global $s;
//$q = dq("select * from $s[pr]comments where approved = '1' order by time desc",0);
//$data[comments] = create_in_files_one_item('com',$q);
foreach ($s[item_types_short] as $k=>$what) 
{ $table = $s[item_types_tables][$what];
  $data[$what][new_items] = create_in_files_get_new_items($what);
  $q = dq("select * from $s[pr]cats where use_for = '$what' AND visible = '1' AND level = '1' order by name",1);
  $data[$what][first_cats] = create_in_files_one_item('c_'.$what,$q);
  $q = dq("select * from $table WHERE $s[where_fixed_part] order by hits_m desc limit $s[right_column_items]",1);
  $data[$what][popular] = create_in_files_one_item($what,$q);
  $q = dq("select * from $table WHERE $s[where_fixed_part] order by rating desc,votes desc limit $s[right_column_items]",1);
  $data[$what][toprated] = create_in_files_one_item($what,$q);
  for ($x=1;$x<=10;$x++)
  { $q = dq("select * from $s[pr]cats where cat_group = '$x' AND visible = '1' order by name",1);
    $data[group_left][$x] = create_in_files_one_item('c_'.$x,$q);
  }
  // #%group_left_2%#
}

$q = dq("select word,count from $s[pr]log_search order by count desc limit 25",1);
while ($x=mysql_fetch_assoc($q))
{ $font_size = round(20 - ($pocet/2));
  $words_array[] = '<a style="font-size:'.$font_size.'px"; href="'.$s[site_url].'/search.php?phrase='.rawurlencode(html_entity_decode($x[word])).'">'.htmlentities($x[word]).'</a>';
  $pocet++;
}
shuffle($words_array);
$b[topsearch] = implode("\n",$words_array);

unset($pocet,$words_array);
foreach ($s[top_tags_words] as $k=>$word) 
{ $font_size = round(20 - ($pocet/2));
  $words_array[] = '<a style="font-size:'.$font_size.'px"; href="'.$s[site_url].'/search.php?phrase='.rawurlencode(html_entity_decode($word)).'">'.htmlentities($word).'</a>';
  $pocet++;
}
shuffle($words_array);
$b[top_tags] = implode("\n",$words_array);
$b[last_rebuild] = datum($s[times_d],1);
if (trim($s[banner_code])) $b[banner_code] = '<div align="center" style="padding-bottom:15px;">'.$s[banner_code].'</div>';
$static[0][0][x][recently_added_items] = $s[recently_added_items];;

foreach ($data as $what=>$array)
{ foreach ($array as $mark=>$items_array)
  { foreach ($items_array as $k=>$item_array)
    { if ($html)
      { if (strstr($what,'_cats')) $out[$what] .= A_parse_part('_in_one_category.txt',$data[$what][$k]);
        else $out[$what] .= A_parse_part('_in_one_item.txt',$data[$what][$k]);
      }
      else
  	  { foreach ($s[styles_list] as $stlk=>$st)
	    { if ($mark=='first_cats') $out[$st][$what.'_first_cats'] .= php_rebuild_parse_part('_in_one_category.txt',$st,$item_array);
          else $out[$st][$what.'_'.$mark] .= php_rebuild_parse_part('_in_one_item.txt',$st,$item_array);
          if ($mark=='new_items') 
          { //foreach ($item_array as $k5=>$v5) echo "$k5 - $v5<br>";
            //$new_items[$what][$st][] = php_rebuild_parse_part('index_new_item.txt',$st,$item_array); // two columns of news items on home page
            $static[home][$st][$what][new_items] .= php_rebuild_parse_part('index_new_item.txt',$st,$item_array);
          }
        }
      }
    }
  }
}

/*
two columns of news items on home page
index_new_item.txt: <td class="table_item_top_cell"><a href="#%url%#">#%title%#</a><br>#%picture%##%description%#</td>
foreach ($new_items as $what=>$v)
{ foreach ($s[styles_list] as $stlk=>$st)
  $static[home][$st][$what][new_items] = multicolumns_table($new_items[$what][$st],2,1,'php_rebuild_parse_part','index_new_item.txt',$st);
}
*/

if ($html)
{ //$file = $s[phppath].'/data/static_parts/'.$s[def_style].'_in_';
  $out[first_cats_select] = select_list_first_categories_all('');
  for ($x=1;$x<=$s[in_templates];$x++)
  { if (!file_exists("$s[phppath]/styles/_common/templates/_in$x.txt")) continue;
    $content = str_replace('#_','#%',str_replace('_#','%#',A_parse_part("_in$x.txt",$out)));
    $fp = fopen ($file.$x,'w') or problem ('Unable to write to file '.$file.$x); fwrite ($fp,$content); fclose($fp); chmod($file.$x,0666);
  }
}
else
{ foreach ($s[styles_list] as $stlk=>$st) 
  { //$file = $s[phppath].'/data/static_parts/'.$st.'_in_';
    $a = array_merge((array)$out[$st],(array)$b);
    $a[first_cats_select] = select_list_first_categories_all($st);
    for ($x=1;$x<=$s[in_templates];$x++)
    { if (!file_exists("$s[phppath]/styles/_common/templates/_in$x.txt")) continue;
	  $static[0][$st][x]["in$x"] = php_rebuild_parse_part('_in'.$path.$x.'.txt',$st,$a);
    }
  }
}

foreach ($s[item_types_short] as $k=>$what)
{ unset($menu_categories);
  $q = dq("select * from $s[pr]cats where in_menu = '1' and use_for = '$what' order by name",1);
  while ($c = mysql_fetch_assoc($q))
  { $number++;
    $static[0][0][x]["menu_categories_$what"] .= "<li><a href=\"".category_url($c[use_for],$c[n],$c[alias_of],$c[name],1,$c[pagename],$c[rewrite_url],'','')."\">$c[name] </a></li>";
  }
}

foreach ($static as $page=>$array1)
{ foreach ($array1 as $style=>$array2) 
  { foreach ($array2 as $what=>$array3)
    { foreach ($array3 as $mark=>$content)
      { $content = replace_once_html($content);
        dq("insert into $s[pr]static values ('$page','$style','$what','$mark','$content')",1);
      }
    }
  }
}

$info = 'Included files updated<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################

function create_in_files_get_new_items($what) {
global $s;
if ($query = get_new_items($what,$s[right_column_items]))
{ unset($x,$numbers,$items);
  $table = $s[item_types_tables][$what];
  $q = dq("select * from $table where $query",1);
  while ($x = mysql_fetch_assoc($q))
  {	if ($x[created]>$x[t1]) $items["$x[created]-$x[n]"] = $x;
    else $items["$x[t1]-$x[n]"] = $x;
    $numbers[] = $x[n];
  }
  ksort($items); $items = array_reverse($items);
  return create_in_files_one_item($what,$items);
}
}

################################################################################

function create_in_files_one_item($what,$q) {
global $s;
if (is_array($q)) // new items
{ $search = array('&amp;',"&#039;",'"','\(','\)','-');
  $replace = array('&',"'",'','','');
  foreach ($q as $k => $x)
  { $cislo++;
    $a[$cislo][url] = get_detail_page_url($what,$x[n],$x[rewrite_url],0,1);
//    if ($what=='n') $a[$cislo][url] = "$s[site_url]/new_in_frame.php?n=$x[n]";
//    else $a[$cislo][url] = get_detail_page_url($what,$x[n],$x[rewrite_url],0,1);
    $a[$cislo][title] = $x[title];
    if ($x[description]) $a[$cislo][description] = $x[description]; elseif ($what=='n') $a[$cislo][description] = strip_tags($x[text],'<img>'); else $a[$cislo][description] = my_substr(strip_tags($x[text],'<img>'),255);
    if ($x[youtube_thumbnail]) $a[$cislo][picture] = '<a href="'.$a[$cislo][url].'"><img border="0" src="'.$x[youtube_thumbnail].'" alt="'.$x[title].'" style="float: left; margin: 0px 5px 0px 0px;" width="130" height="80"></a>';
    else
    { $q = dq("select filename from $s[pr]files where item_n = '$x[n]' and what = '$what' and file_type = 'image' and queue = '0' order by file_n limit 1",1);
      $y = mysql_fetch_assoc($q);
      if (trim($y[filename])) $a[$cislo][picture] = '<a href="'.$a[$cislo][url].'"><img border="0" src="'.$y[filename].'" alt="'.$x[title].'" style="float: left; margin: 0px 5px 0px 0px;" width="130" height="80"></a>';
      elseif (($what=='l') AND ($s[l_thumbnail_url])) { $x1 = parse_url($x[url]); $a[$cislo][picture] = '<a href="'.$a[$cislo][url].'"><img border="0" src="'.str_replace('#%domain%#',$x1[host],$s[l_thumbnail_url]).'" alt="'.$x[title].'" style="float: left; margin: 0px 5px 0px 0px;" width="130" height="80"></a>'; }
    }
    
    $title = unhtmlentities($x[title]);
    $title = str_replace(chr(92),'&#92;',$title);
    $title = str_replace(chr(92),'&#92;',$title);
    $title = str_replace("\n","",$title);
    foreach ($search as $k=>$v) $title = preg_replace("/$v/i",$replace[$k],$title);
    if (strlen($title)>30)
    { $s[recently_added_items_cislo]++;
      $s[recently_added_items] .= "line[$s[recently_added_items_cislo]] = '<a target=\"_top\" href=\"".$a[$cislo][url]."\">".str_replace("'",'',$title)."</a>';\n";
    }
    
  }
}
else  // mysql result
{ while ($x = mysql_fetch_assoc($q))
  { $cislo++;
    if (strstr($what,'c_'))
    { $a[$cislo][url] = category_url($x[use_for],$x[n],$x[alias_of],$x[name],1,$x[pagename],$x[rewrite_url],'','');
      $a[$cislo][title] = $x[name];
      $a[$cislo][items] = $x[items];
      $a[$cislo][folder_icon] = folder_icon($x[item_created],$x[image2]);
    }
    else
    { $a[$cislo][url] = get_detail_page_url($what,$x[n],$x[rewrite_url],0,1);
      $a[$cislo][title] = $x[title];
      $a[$cislo][description] = $x[description];
      //foreach ($x as $k=>$v) echo "$k - $v<br>";//exit;
      if ($x[youtube_thumbnail]) $a[$cislo][picture] = '<a href="'.$a[$cislo][url].'"><img border="0" src="'.$x[youtube_thumbnail].'" alt="'.$x[title].'" style="float: left; margin: 0px 5px 0px 0px;" width="130" height="80"></a>';
      else
      { $q1 = dq("select filename from $s[pr]files where item_n = '$x[n]' and what = '$what' and file_type = 'image' and queue = '0' order by file_n limit 1",1);
        $y = mysql_fetch_assoc($q1);
        if (trim($y[filename])) $a[$cislo][picture] = '<a href="'.$a[$cislo][url].'"><img border="0" src="'.$y[filename].'" alt="'.$x[title].'" style="float: left; margin: 0px 5px 0px 0px;" width="130" height="80"></a>';
      }
    }
  }
}
return $a;
}

################################################################################

function rebuild_index_categories($showresult) {
global $s;
foreach ($s[item_types_short] as $k=>$what)
{ unset($a,$pocet,$categories);
  $q = dq("select * from $s[pr]cats where use_for = '$what' AND level = '1' AND visible = '1' AND hide_home = '0' order by name",1);
  while ($c = mysql_fetch_assoc($q))
  { $a[total] = $a[total] + $c[items]; unset($subcats);
    $c[folder_icon] = folder_icon($c[item_created],$c[image2]);
    if ($c[alias_of]) $c[name] = $s[alias_pref].$c[name].$s[alias_after];
    $c[catlink] = category_url($what,$c[n],$c[alias_of],$c[name],1,$c[pagename],$c[rewrite_url],'','');
    if ($s[max_subc])
    { $r = dq("select * from $s[pr]cats where use_for = '$what' AND level = '2' AND visible = '1' AND hide_home = '0' AND parent = '$c[n]' order by name limit $s[max_subc]",1);
      while ($x = mysql_fetch_assoc($r))
      { if ($x[alias_of]) $x[name] = $s[alias_pref].$x[name].$s[alias_after];
        $subcats[] = '<a class="link10" href="'.category_url($what,$x[n],$x[alias_of],$x[name],1,$x[pagename],$x[rewrite_url],'','').'">'.$x[name].'</a>';
      }
      if ($subcats) $c[subcats] = implode($s[ind_sep_subc],$subcats);
    }
    $c[width] = floor(100/$s[ind_column]);
    foreach ($s[styles_list] as $k => $style) $categories[$style][] = php_rebuild_parse_part('index_category.txt',$style,$c);
    $pocet++;
  }
  foreach ($s[styles_list] as $k => $style)
  { $a[categories][$style] = multicolumns_table($categories[$style],$s[ind_column],$s[in_sort_rows],'php_rebuild_parse_part','index_category_empty.txt',$style);
    $content = replace_once_html($a[categories][$style]);
    $mark = 'categories';
    dq("insert into $s[pr]static values ('home','$style','$what','$mark','$content')",1);
  }
}

$info = 'Categories on home page updated<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################

function rebuild_index_categories_groups($showresult) {
global $s;
$q = dq("select * from $s[pr]cats where visible = '1' and cat_group > '0' order by cat_group,name",1);
while ($c = mysql_fetch_assoc($q))
{ if ($c[alias_of]) $c[name] = $s[alias_pref].$c[name].$s[alias_after];
  $c[catlink] = category_url($c[use_for],$c[n],$c[alias_of],$c[name],1,$c[pagename],$c[rewrite_url],'','');
  $c[folder_icon] = folder_icon($c[item_created],$c[image2]);
  $c[width] = floor(100/$s[ind_column_group]);
  
  unset($subcats);
  if ($s[max_subc])
  { $r = dq("select * from $s[pr]cats where visible = '1' AND hide_home = '0' AND parent = '$c[n]' order by name limit $s[max_subc]",1);
    while ($x = mysql_fetch_assoc($r))
    { if ($x[alias_of]) $x[name] = $s[alias_pref].$x[name].$s[alias_after];
      $subcats[] = '<a class="link10" href="'.category_url($x[use_for],$x[n],$x[alias_of],$x[name],1,$x[pagename],$x[rewrite_url],'','').'">'.$x[name].'</a>';
    }
    if ($subcats) $c[subcats] = implode($s[ind_sep_subc],$subcats);
  }

  foreach ($s[styles_list] as $k => $style) $cats[$c[cat_group]][$style][] = php_rebuild_parse_part('index_category_in_group.txt',$style,$c);
}

foreach ($cats as $group=>$v)
{ foreach ($s[styles_list] as $k1 => $style)
  { $a = multicolumns_table($v[$style],$s[ind_column_group],$s[in_sort_rows],'php_rebuild_parse_part','index_category_empty.txt',$style);
    $content = replace_once_html($a);
    $mark = 'categories_group_'.$group;
    dq("insert into $s[pr]static values ('home','$style','x','$mark','$content')",1);
    unset($a,$categories);
  }
}
$info = 'Groups of categories on home page updated<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

################################################################################
################################################################################
################################################################################

function new_time($what) {
global $s;
load_times();
$s[$what] = $s[cas];
save_times();
}

###################################################################################

function php_rebuild_parse_part($t,$style,$vl) {
global $s;$t1 = $t;
if (file_exists($s[phppath].'/styles/'.$style.'/templates/'.$t))
$t = $s[phppath].'/styles/'.$style.'/templates/'.$t;
else $t = $s[phppath].'/styles/_common/templates/'.$t;
if (!is_array($vl)) $vl = array(); $vl[site_url] = $s[site_url];
$fh = fopen ($t,'r') or problem ("Unable to read file $t");
while (!feof($fh)) $line .= fgets ($fh,4096); fclose($fh);
foreach ($s[item_types_short] as $k=>$v) if (!$s["section_$v"]) $line = preg_replace('/#%begin_'.$v.'%#(.*)#%end_'.$v.'%#/eisU','',$line);
foreach ($vl as $k=>$v) $line = str_replace("#%$k%#",$v,$line);
$line = preg_replace("/#%[a-z0-9_]*%#/i",'',stripslashes($line));
$line = str_replace('#_','#%',str_replace('_#','%#',$line));
return $line;
}

###################################################################################

function select_list_first_categories_all($style) {
global $s,$m;
if (!file_exists($s[phppath].'/styles/'.$style.'/messages/common.php')) $style = '_common';
include($s[phppath].'/styles/'.$style.'/messages/common.php');
$q = dq("select use_for from $s[pr]cats where level = 1 and visible = '1' and alias_of = '0' group by use_for",0);
while ($x=mysql_fetch_assoc($q)) $have[$x[use_for]] = 1;

foreach ($s[item_types_short] as $k=>$what)
{ if (!$s["section_$what"]) continue;
  $word = $s[items_types_words][$what];
  if ($have[$what]) $a .= "<optgroup label=\"$m[$word]\"><option value=\"".$what."_0\"#_selected_".$what."_0_#>$m[all_cats]</option>\n";
  $q = dq("select n,name from $s[pr]cats where use_for = '$what' AND level = 1 and visible = '1' and alias_of = '0' order by name",0);
  while ($x=mysql_fetch_assoc($q)) $a .= "<option value=\"".$what."_$x[n]\"#_selected_".$what."_$x[n]_#>$x[name]</option>\n";
  if ($have[$what]) $a .= "</optgroup>";
}
return stripslashes($a);
}

###################################################################################
###################################################################################
###################################################################################

function recount_all_links($show_result,$category) {
global $s;
$s[dont_count_max] = 1;
if ($show_result) echo '<span class="text10">';
if ($category) $category = " and (n = '$category' or bigboss = '$category')"; else $category = '';
$q = dq("select n,name from $s[pr]cats where use_for = 'l' and alias_of = '0' $category order by name",1);
while ($c = mysql_fetch_assoc($q))
{ set_time_limit(300);
  recount_items_cat('l',$c[n]);
  if ($show_result) echo 'Links category '.$c[name].' updated<br />';
}
if ($show_result) echo '</span>'.info_line('Links have been recounted');
}

########################################################################################

function recount_all_articles($show_result,$category) {
global $s;
$s[dont_count_max] = 1;
if ($show_result) echo '<span class="text10">';
if ($category) $category = " and (n = '$category' or bigboss = '$category')"; else $category = '';
$q = dq("select n,name from $s[pr]cats where use_for = 'a' and alias_of = '0' $category order by name",1);
while ($c = mysql_fetch_assoc($q))
{ set_time_limit(300);
  recount_items_cat('a',$c[n]);
  if ($show_result) echo 'Articles category '.$c[name].' updated<br />';
}
if ($show_result) echo '</span>'.info_line('Articles have been recounted');
}

########################################################################################

function recount_all_blogs($show_result,$category) {
global $s;
$s[dont_count_max] = 1;
if ($show_result) echo '<span class="text10">';
if ($category) $category = " and (n = '$category' or bigboss = '$category')"; else $category = '';
$q = dq("select n,name from $s[pr]cats where use_for = 'b' and alias_of = '0' $category order by name",1);
while ($c = mysql_fetch_assoc($q))
{ set_time_limit(300);
  recount_items_cat('b',$c[n]);
  if ($show_result) echo 'Blogs category '.$c[name].' updated<br />';
}
if ($show_result) echo '</span>'.info_line('Blogs have been recounted');
}

########################################################################################

function recount_all_videos($show_result,$category) {
global $s;
$s[dont_count_max] = 1;
if ($show_result) echo '<span class="text10">';
if ($category) $category = " and (n = '$category' or bigboss = '$category')"; else $category = '';
$q = dq("select n,name from $s[pr]cats where use_for = 'v' and alias_of = '0' $category order by name",1);
while ($c = mysql_fetch_assoc($q))
{ set_time_limit(300);
  recount_items_cat('v',$c[n]);
  if ($show_result) echo 'Videos category '.$c[name].' updated<br />';
}
if ($show_result) echo '</span>'.info_line('Videos have been recounted');
}

########################################################################################

function recount_all_news($show_result,$category) {
global $s;
$s[dont_count_max] = 1;
if ($show_result) echo '<span class="text10">';
if ($category) $category = " and (n = '$category' or bigboss = '$category')"; else $category = '';
$q = dq("select n,name from $s[pr]cats where use_for = 'n' and alias_of = '0' $category order by name",1);
while ($c = mysql_fetch_assoc($q))
{ set_time_limit(300);
  recount_items_cat('n',$c[n]);
  if ($show_result) echo 'News category '.$c[name].' updated<br />';
}
if ($show_result) echo '</span>'.info_line('News have been recounted');
}

########################################################################################
########################################################################################
########################################################################################

function create_sitemap($showresult) {
global $s;
foreach ($s[item_types_short] as $k=>$what)
{ if (!$s['sitemap_'.$what.'_cats']) continue;
  $q = dq("select * from $s[pr]cats where use_for = '$what' and visible = '1' order by path_text",1);
  while ($b=mysql_fetch_assoc($q))
  { set_time_limit(300);
    if (time()>($time1+10)) { $time1=time(); echo str_repeat (' ',4000); flush(); }
    $mo = ''; for ($i=1;$i<$b[level];$i++) $mo .= '-&nbsp;';
    $b[path_text] = preg_replace("/<%.+%>/",'',$b[path_text]);
    $b[path_text] = preg_replace("/<%.+$/",$b[name],$b[path_text]);
    if (!$b[path_text]) $b[path_text] = $b[name];
    if ($b[alias_of]) $b[path_text] = $s[alias_pref].$b[path_text].$s[alias_after];
    $x .= $mo.' <a href="'.category_url($what,$b[n],$b[alias_of],$b[name],1,$b[pagename],$b[rewrite_url],'','').'">'.$b[path_text].'</a><br />'."\n";
  }
  $a["categories_$what"] = stripslashes($x); unset($x);
  if ($s["sitemap_$what"])
  { $table = $s[item_types_tables][$what];
    $q = dq("select * from $table where $s[where_fixed_part] order by n",1);
    while ($b = mysql_fetch_assoc($q))
    { $x .= '<a href="'.get_detail_page_url($what,$b[n],$b[rewrite_url],$b[category],1).'">'.$b[title].'</a><br />';
      if ($s['sitemap_'.$what.'_description']) $x .= $b[description].'<br />';
      $x .= "\n";
    }
  }
  $a[$what] = stripslashes($x); unset($x);
}
$a[charset] = $s[charset]; $a[user_options] = $s[user_options]; $a[site_url] = $s[site_url]; $a[site_name] = $s[site_name]; $a[currency] = $s[currency];
$f = fopen($s[phppath].'/styles/_common/templates/_head1.txt','r'); while (!feof($f)) $line .= fgets($f,4096); fclose ($f);
$line .= '<LINK href="'.$s[site_url].'/styles/'.$s[def_style].'/styles.css" rel="StyleSheet">';
$f = fopen($s[phppath].'/styles/_common/templates/sitemap.html','r'); while (!feof($f)) $line .= fgets($f,4096); fclose ($f);
foreach ($a as $k=>$v) $line = str_replace("#%$k%#",$v,$line); 
$line = preg_replace("/#%[a-z0-9_]*%#/i",'',stripslashes($line));
$file = fopen($s[sitemap_location],'w'); fwrite ($file,$line); fclose($file);
chmod ($s[sitemap_location],0666);
}

###################################################################################
###################################################################################
###################################################################################

function reset_all_question($ok) {
global $s;
ih();
echo '<br />
<form method="post" action="rebuild.php" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="reset_all">';
echo info_line('It resets numbers of incoming and outgoing hits of all links (except advertising links) and numbers of reads for all articles to zero. Are you sure?','<input type="submit" name="submit" value="Yes, reset it" class="button10">');
echo '</form>';
ift();
}

###################################################################################

function reset_all() {
global $s;
dq("update $s[pr]links set clicks_in = 0, hits = 0, clicks_in_m = 0, hits_m = 0",1);
dq("update $s[pr]links_stat set i = 0, c = 0, r = 0, i_month = 0, c_month = 0, r_month = 0, i_reset = 0, c_reset = 0, r_reset = 0, reseted = '$s[cas]'",1);
dq("update $s[pr]articles set hits = 0, hits_m = 0",1);
dq("update $s[pr]videos set hits = 0, hits_m = 0",1);
dq("update $s[pr]news set hits = 0, hits_m = 0",1);
dq("update $s[pr]blogs set hits = 0, hits_m = 0",1);
$s[info] = 'Statistic has been reseted<br />';
if ($s[A_option]=='static') { header ("Location: html_rebuild.php"); exit; }
else reset_rebuild_home();
}

###################################################################################
###################################################################################
###################################################################################

function create_google_sitemap($showresult) {
global $s;
$s[google_map_n] = 0;
$file = fopen($s[g_sitemap_location],'w');
$line = '<?xml version="1.0" encoding="UTF-8" ?> 
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n";
fwrite($file,$line);
foreach ($s[item_types_short] as $k=>$what)
{ if ($s['g_sitemap_'.$what.'_cats'])
  { $q = dq("select * from $s[pr]cats where use_for = '$what' and visible = '1' order by level",1);
    while ($b=mysql_fetch_assoc($q))
    { set_time_limit(300);
      if (time()>($time1+10)) { $time1=time(); echo str_repeat (' ',4000); flush(); }
      $b[url] = category_url($what,$b[n],$b[alias_of],$b[name],1,$b[pagename],$b[rewrite_url],'','');
      $b[date] = str_replace('---','T',date("Y-m-d---H:i:s+00:00",$s[cas]));
      fwrite($file,php_rebuild_parse_part('google_sitemap.txt','_common',$b)."\n");
      $total_items ++; if ($total_items>=49500) { $total_items = 0; $file = create_new_google_sitemap($file); }
    }
  }
  if ($s["g_sitemap_$what"])
  { $table = $s[item_types_tables][$what];
    $q = dq("select * from $table where $s[where_fixed_part] order by n",1);
    while ($b = mysql_fetch_assoc($q))
    { $b[url] = get_detail_page_url($what,$b[n],$b[rewrite_url],0,1);
      if ($b[edited]) $date = $b[edited]; else $date = $b[created];
      $b[date] = str_replace('---','T',date("Y-m-d---H:i:s+00:00",$date));
      fwrite($file,php_rebuild_parse_part('google_sitemap.txt','_common',$b)."\n");
      $total_items ++; if ($total_items>=49500) { $total_items = 0; $file = create_new_google_sitemap($file); }
    }
  }
}
if ($s[g_sitemap_search])
{ $table = $s[item_types_tables][$what];
  $q = dq("select word from $s[pr]log_search order by n desc",1);
  while ($b = mysql_fetch_assoc($q))
  { if ($s[A_option]=='rewrite') $b[url] = "$s[site_url]/search/".rawurlencode(html_entity_decode($b[word]))."/";
    else $b[url] = $s[site_url].'/search.php?phrase='.rawurlencode(html_entity_decode($b[word]));
    $b[date] = str_replace('---','T',date("Y-m-d---H:i:s+00:00",$s[cas]));
    fwrite($file,php_rebuild_parse_part('google_sitemap.txt','_common',$b)."\n");
    $total_items ++; if ($total_items>=49500) { $total_items = 0; $file = create_new_google_sitemap($file); }
  }
}

fwrite ($file,'</urlset>');
fclose($file); chmod ($file,0666);
$info = 'Google sitemap created<br />';
if ($showresult) echo info_line($info); else $s[info] .= $info;
}

function create_new_google_sitemap($file) {
global $s;
fwrite ($file,'</urlset>'); fclose($file); chmod ($file,0666);
$s[google_map_n]++;
$file = fopen(str_replace('.xml',"-$s[google_map_n].xml",$s[g_sitemap_location]),'w');
$line = '<?xml version="1.0" encoding="UTF-8" ?>'."\n".'<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n";
fwrite($file,$line);
return $file;
}

###################################################################################

function create_yahoo_sitemap($showresult) {
global $s;
if (($s[A_option]=='static') AND ($s[Aindexhtml])) $home_page = $s[Aindexhtml]; else $home_page = 'index.php';
$file = fopen($s[y_sitemap_location],'w');

foreach ($s[item_types_tables] as $what=>$table)
{ if ($s['y_sitemap_'.$what.'_cats'])
  { $q = dq("select * from $s[pr]cats where use_for = '$what' and visible = '1' order by level",1);
    while ($b=mysql_fetch_assoc($q)) $list .= category_url($what,$b[n],$b[alias_of],$b[name],1,$b[pagename],$b[rewrite_url],'','')."\n";
    fwrite($file,$list); unset($list);
  }
  if ($s["y_sitemap_$what"])
  { $q = dq("select * from $table where $s[where_fixed_part] order by n",1);
    while ($b=mysql_fetch_assoc($q)) $list .= get_detail_page_url($what,$b[n],$b[rewrite_url],0,1)."\n";
    fwrite($file,$list); unset($list);
  }
}
if ($s[y_sitemap_search])
{ $q = dq("select word from $s[pr]log_search order by n desc",1);
  while ($b=mysql_fetch_assoc($q))
  { if ($s[A_option]=='rewrite') $url = "$s[site_url]/search/".rawurlencode(html_entity_decode($b[word]))."/";
    else $url = $s[site_url].'/search.php?phrase='.rawurlencode(html_entity_decode($b[word]));
    $list .= "$url\n";
  }
  fwrite($file,$list); unset($list);
}
fclose($file); chmod ($s[y_sitemap_location],0666);
$info = 'Yahoo URL list created<br />';
if ($showresult) echo info_line($info); else $s[info] .= $info;
}


###################################################################################

function create_index($showresult) {
global $s;
//dq("delete from $s[pr]index",1);
$s[no_twitter] = 1;
dq("delete from $s[pr]index_suggest",1);
foreach ($s[item_types_short] as $k=>$what)
{ dq("delete from $s[pr]index where what = '$what'",1);
  $table = $s[item_types_tables][$what];
  $q = dq("select n from $table where $s[where_fixed_part]",1);
  while ($x = mysql_fetch_assoc($q)) { update_item_index($what,$x[n]); increase_print_time(2,1); }
}
increase_print_time(2,'end');
$info = 'Index for searching created<br />';
$s[info] .= $info;
}

###################################################################################
###################################################################################
###################################################################################

function repair_path_cats($category) {
global $s;
if ($category)
{ $x = explode('_',$category);
  $query = "and use_for = '$x[0]'";
  if ($x[1]) $query = "and (bigboss = '$x[1]' or n = '$x[1]')";
}
$q = dq("select n from $s[pr]cats where alias_of = '0' $query order by level",1);
while ($c=mysql_fetch_assoc($q)) update_category_paths($c[n]);
$info = 'Paths for selected categories have been updated<br />';
$s[info] .= $info;
}

###################################################################################

function repair_image_sizes($in) {
global $s;
//foreach ($in as $k=>$v) echo "$k - $v<br>";//exit;
/*
resize_what - l
resize_thumbnails - 1
resize_w - 500
resize_h - 500
*/
$q = dq("select * from $s[pr]files where file_type = 'image' and what = '$in[resize_what]' and filename like '$s[site_url]%'",1);
while ($image=mysql_fetch_assoc($q))
{ if ($in[resize_thumbnails]) $file_path = str_replace($s[site_url],$s[phppath],$image[filename]);
  else $file_path = str_replace($s[site_url],$s[phppath],preg_replace("/\/$image[item_n]-/","/$image[item_n]-big-",$image[filename]));
  $size = getimagesize($file_path);
  if (($size[0]<=$in[resize_w]) AND ($size[1]<=$in[resize_h])) continue;
  $x1 = $size[0]/$in[resize_w]; $x2 = $size[1]/$in[resize_h];
  //$thumb=new thumbnail($file_path);
  //if ($x1>$x2) $thumb->size_width($in[resize_w]); else $thumb->size_height($in[resize_h]);
  //$file_path = str_replace("/images/","/images1/",$file_path);
  //$thumb->save($file_path);
  resize_image($file_path,$file_path,$in[resize_w],$in[resize_h]);
  if (!$in[resize_thumbnails]) { $size = filesize($file_path); dq("update $s[pr]files set size = '$size' where n = '$image[n]'",1); }
  increase_print_time(2,1);
}
increase_print_time(2,'end');
$info = 'Selected images have been resized<br />';
$s[info] .= $info;
}

###################################################################################
###################################################################################
###################################################################################

?>