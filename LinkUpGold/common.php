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

error_reporting (E_ERROR | E_PARSE);
include('./data/data.php');
include_once($s[phppath].'/administration/functions.php');
redirect_www();
get_messages('common.php');
$s[pages_public] = 1;
try_ip_blacklist($s[ip]);
if ($s[A_option]=='static') unset($_SESSION[LUG_style]);
$s[no_increase_print] = 1;

if ($_SESSION[LUG_u_username])
{ $s[LUG_u_username] = $_SESSION[LUG_u_username];
  $s[LUG_u_password] = $_SESSION[LUG_u_password];
  $s[LUG_u_n] = $_SESSION[LUG_u_n];
  $s[LUG_u_style] = $_SESSION[LUG_u_style];
  $s[LUG_u_name] = $_SESSION[LUG_u_name];
  $s[LUG_u_email] = $_SESSION[LUG_u_email];
}
elseif ($_COOKIE[LUG_u_username])
{ $s[LUG_u_username] = $_COOKIE[LUG_u_username];
  $s[LUG_u_password] = $_COOKIE[LUG_u_password];
  $s[LUG_u_n] = $_COOKIE[LUG_u_n];
  $s[LUG_u_style] = $_COOKIE[LUG_u_style];
  $s[LUG_u_name] = $_COOKIE[LUG_u_name];
  $s[LUG_u_email] = $_COOKIE[LUG_u_email];
}

if ($s[LUG_u_n]) $s[LUG_style] = $s[LUG_u_style];
elseif ($_SESSION[LUG_style]) $s[LUG_style] = $_SESSION[LUG_style];
else $s[LUG_style] = $s[def_style];
if (!is_dir("$s[phppath]/styles/$s[LUG_style]")) $s[LUG_style] = $s[def_style];
if ($_SESSION[visit]) $s[visit] = 1; $_SESSION[visit] = 1;
log_country();

##################################################################################
##################################################################################
##################################################################################

function redirect_www() {
global $s;
$http_host = getenv('HTTP_HOST');
if (!$http_host) return false;
if ((strstr($http_host,'www.')) AND (!strstr($s[site_url],'//www.'))) $new_host = str_replace('www.','',$http_host);
elseif ((!strstr($http_host,'www.')) AND (strstr($s[site_url],'//www.'))) $new_host = "www.$http_host";
if (!$new_host) return false;
$request = getenv('REQUEST_URI');
if (!$request) $request = '/';
header("Location: http://$new_host$request");
exit;
}

##################################################################################

function check_email($email) {
if (preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is',$email)) return 1;
return 0;
}

##################################################################################

function check_recip($retezec) {
global $s;
$x = strstr($retezec,$s[reciplink]);
return $x;
}

##################################################################################
##################################################################################
##################################################################################

function who_is_online() {
global $s;
if (!$s[ip]) return 'This function is not supported by this server.';
$t = $s[cas]-600;
dq("delete from $s[pr]online where time < '$t'",0);
dq("insert into $s[pr]online values ('$s[cas]','$s[ip]')",0);
$q = dq("select count(*) from $s[pr]online",0); $x = mysql_fetch_row($q);
return $x[0];
}

##################################################################################

function check_url($url,$check) {
global $s,$m;
if (!preg_match("/^(https?:\/\/+[\w\-]+\.[\w\-]+)/i",$url)) return array (0,"$m[w_url] $url.");
if (strlen($url) > 255) return array (0,"$m[l_url] $url.");
$parsedurl = parse_url($url);
if ( (!$parsedurl[scheme]) OR (!$parsedurl[host]) ) return array (0,"$m[w_url] $url");
if ($check)
{ $lines = file($url);
  if (!$lines) $lines = file($url.'/');
  if (!$lines) return array (0,"$m[noconnect_url] $url.");
  foreach ($lines as $k=>$v) $obsah .= $v."\n";
  if (!(strlen($obsah))) return array (0,"$m[noconnect_url] $url.");
  return array ($obsah,0);
}
return array (1,0);
}

##################################################################################

function item_details_page($what) {
global $s,$m;
$word = $s[item_types_words][$what];
$words = $s[items_types_words][$what];

if ($s[A_option]=='rewrite')
{ if ($_GET[vars]) { $s[table] = $s[item_types_tables][$what]; $_GET = rewrite_item($_GET[vars]); }
  elseif (is_numeric($_GET[n])) { $url = rewrite_item_url($what,$_GET[n],'',1,$_GET[c]); header("HTTP/1.1 301 Moved Permanently"); header ("Location: $url"); exit; }
}
elseif (($s[A_option]=='static') AND ($s[A_one_item_l]))
{ $url = A_get_detail_url($what,$_GET[n],'',1);
  header("HTTP/1.1 301 Moved Permanently"); header ("Location: $url"); exit;
}

if (is_numeric($_GET[n])) $in = $_GET;
else exit;
$in = replace_array_text($in);

$a = get_item_variables($what,$in[n]);
if (!$a[n])
{ if (strstr(getenv('HTTP_REFERER'),$s[site_url].'/administration/')) $a = get_item_variables($what,$in[n],1);
  if (!$a[n]) problem($m[item_not_exists]);
}
check_access_rights($what,$a[c],'');
if (check_admin_rights('all_'.$words)) $a[edit_link] = '<a target="_blank" href="'.$s[site_url].'/administration/'.$word.'_details.php?action='.$word.'_edit&amp;n='.$a[n].'">Edit this '.$word.'</a>';

$usit = user_defined_items_display($what,$all_user_items_list,$all_user_items_values,$a[n],'user_item_listing.txt',0,1,0,1);
$a[user_defined] = $usit[$in[n]]; if (!$a[user_defined]) { $a[hide_usit_begin] = '<!--'; $a[hide_usit_end] = '-->'; }
foreach ($usit['individual_'.$in[n]] as $k1=>$v1) $a[$k1] = $v1;

list($images,$files) = pictures_files_display_public($what,$a[n],0);
$images = detail_page_images($what,$images[$a[n]],$a[n],0,$a);
if ($images[full_size_image]) $a[pictures_gallery] = $images[full_size_image];
if ($images[pictures_gallery]) { $a[pictures_gallery] = $images[pictures_gallery]; $a[previews_width] = $images[previews_width]; }

$a[title_no_tag] = strip_tags($a[title]);
$a[this_url] = get_detail_page_url($what,$a[n],$a[rewrite_url],$a[category],1);
$a[share_it] = parse_part('share_it.txt',$a);
$a[icons] = get_icons_for_item($what,$a,$bookmarks[$a[n]],'&nbsp;');
$a[created] = datum($a[created],0);
if ($a[updated]) $a[updated] = datum($a[updated],0); else { $a[hide_updated_begin] = '<!--'; $a[hide_updated_end] = '-->'; }
$a[rateicon] = get_rateicon($a[rating]);
$x = list_of_categories_for_item($what,0,$a[c],'<br />',0); $a = array_merge((array)$a,(array)$x);
$a[tags] = tags_for_item($what,$a[c],$a[keywords]); if (!$a[tags]) { $a[hide_tags_begin] = '<!--'; $a[hide_tags_end] = '-->'; }
$a[show_comments] = comments_get($what,$in[n],0);// if (!$a[show_comments]) { $a[hide_comments_begin] = '<!--'; $a[hide_comments_end] = '-->'; }
if ((!$a[email]) OR ($a[email]==$s[mail])) { $a[hide_contact_form_begin] = '<!--'; $a[hide_contact_form_end] = '-->'; }
if ($s[message_owner_captcha]) $a[contact_form_field_captcha] = parse_part('form_captcha_test.txt',$b);
$x = previous_next_links($what,$in[c],$a[c],$a[n]); $a = array_merge((array)$a,(array)$x);
if ($s[det_br]) $a[detail] = str_replace("\n",'<br />',$a[detail]);
$a[detail] = str_replace('&#039;',"'",$a[detail]);
/*
if ((!strstr($a[map],'_gmok_')) AND ($a[map]))
{ $map_test = test_google_map($a[map]);
  if ($map_test)
  { $table = $s[item_types_tables][$what];
    dq("update $table set map = '$a[map]$map_test' where n = '$a[n]'",1);
    $a[map] .= "_gmok_";
  }
}
*/
if (strstr($a[map],'_gmok_')) $a[div_display_map] = 'block'; else $a[div_display_map] = 'none'; 
if ($a[rss_url]) $a[rss_content] = show_rss_content($what,$a[n],$a[rss_url],10); if (!trim($a[rss_content])) { $a[hide_rss_content_begin] = '<!--'; $a[hide_rss_content_end] = '-->'; }
if (($s[allow_claim_l]) AND ($what=='l') AND (!$a[owner])) $a[claim_listing_box] = claim_listing_box($what,$a[n]);
else { $a[hide_claim_listing_begin] = '<!--'; $a[hide_claim_listing_end] = '-->'; }

$a[tell_friend_box] = tell_friend_box($what,$a[n],'',1);
$a[enter_comment_box] = enter_comment_box($what,$a[n]);
$a[notes_edit_box] = notes_edit_box($what,$a[n]);
$a[contact_box] = contact_box($what,$a[n],'',1);
$s[search_display] = $what;

if (($a[email]) AND ($a[email]!=$s[mail])) $a[more_items] = more_items_of_owner($what,$a[email],0); if (!$a[more_items]) { $a[hide_more_items_begin] = '<!--'; $a[hide_more_items_end] = '-->'; $a[hide_more_items1_begin] = '<!--'; $a[hide_more_items1_end] = '-->'; }
if ($a[owner])
{ $user_vars = get_user_variables($a[owner]);
  $files_pictures = get_item_files_pictures('u',$user_vars[n],0);
  if ($files_pictures[image_url][$user_vars[n]][1]) 
  { $big_file = preg_replace("/\/$user_vars[n]-/","/$user_vars[n]-big-",$files_pictures[image_url][$user_vars[n]][1]);
    $a[owner_image] = image_preview_code(1,$files_pictures[image_url][$user_vars[n]][1],$big_file);
  }
  else $a[owner_image] = '<img border="0" src="'.$s[site_url].'/images/user_image.png">';
  $a[owner_nick] = $user_vars[nick];
  if ($user_vars[url]) { if (!$user_vars[site_title]) $user_vars[site_title] = $user_vars[url]; $a[owner_website] = '<a target="_blank" href="'.$user_vars[url].'">'.$user_vars[site_title].'</a>'; }
  else $a[owner_website] = $m[none];
  $a[owner_link] = get_detail_page_url('u',$user_vars[n],$user_vars[nick]);
}
if (!$user_vars[n]) { $a[hide_owner_begin] = '<!--'; $a[hide_owner_end] = '-->'; unset($a[hide_more_items1_begin],$a[hide_more_items1_end]); }
$x = explode(' ',str_replace('_','',$a[c])); $b = get_category_variables($x[0]); $x = preparse_ads_in_category($b); $a = array_merge((array)$a,(array)$x);

if ($s[LUG_u_n])
{ $bookmarks = get_favorites_status($what,$a[n]);
  $a[add_delete_favorites] = get_favorite_line($what,$a[n],$bookmarks[$a[n]]);
  $notes = get_private_notes_for_items($what,$a[n]);
  if ($notes[$a[n]]) $a[notes] = $notes[$a[n]];
}
else { $a[hide_favorites_begin] = '<!--'; $a[hide_favorites_end] = '-->'; $a[hide_edit_notes_begin] = '<!--'; $a[hide_edit_notes_end] = '-->'; $a[hide_notes_begin] = '<!--'; $a[hide_notes_end] = '-->'; }
if (!$a[notes]) { $a[hide_notes_begin] = '<!--'; $a[hide_notes_end] = '-->'; }
if ($in[category_name]) { $a[category_name] = $in[category_name]; $a[category] = $in[c]; }

$template = get_detail_template_name($what,$a[c]);

if ($what=='l')
{ if (file_exists("$s[phppath]/images/thumbnails/$a[n].gif")) $a[thumbnail] = '<a target="_blank" href="'.$a[url].'"  OnClick="track_image_'.$a[n].'.src=\''.$s[site_url].'/track_click.php?free_link='.$a[n].'\';"><img border="0" src="'."$s[site_url]/images/thumbnails/$a[n].gif".'" alt="'.$a[title_no_tag].'" style="float: left; margin: 0px 5px 0px 0px;"></a>';
  elseif ($s[l_thumbnail_url]) { $x = parse_url($a[url]); $a[thumbnail] = '<a target="_blank" href="'.$a[url].'"  OnClick="track_image_'.$a[n].'.src=\''.$s[site_url].'/track_click.php?free_link='.$a[n].'\';"><img border="0" src="'.str_replace('#%domain%#',$x[host],$s[l_thumbnail_url]).'" alt="'.$a[title_no_tag].'" style="float: left; margin: 0px 5px 0px 0px;"></a>'; }
  if ($a[sponsored])
  { $x = get_link_adv_variables($a[n]);
    if ($x[i_now]) dq("update $s[pr]links_adv set i_now = i_now - 1 where n = '$a[n]'",1);
    $is_advertising = get_link_advertising_status($x);
    if (($x[c_dynamic_now]) AND ($x[c_dynamic_price]!=0)) $a[price] = $x[c_dynamic_price];
  }
  if (!$a[price]) { $a[hide_click_price_begin] = '<!--'; $a[hide_click_price_end] = '-->'; }
  if ($is_advertising) $template = 'link_details_advertising.html';
  if (strstr($a[detail],'<complete_link>')) $a[link] = $a[detail];
  elseif ($a[url]) $a[link] = '<a target="'.$s[rl_open_window].'" href="'.$a[url].'"  OnClick="track_image_'.$a[n].'.src=\''.$s[site_url].'/track_click.php?free_link='.$a[n].'\';"><h2>'.$a[title].'</h2></a>';
  else $a[link] = '<h2>'.$a[title].'</h2>';
  $a[report_box] = report_box($what,$a[n],'',1);
}
else track_item_hit($what,$_GET[n]);

$a[video_code] = youtube_player($a[youtube_id],$a[video_code]);
if (($what=='a') OR ($what=='b') OR ($what=='n'))
{ $a[text] = str_replace('<a','<a target="_blank"',str_replace('target=\"_top\"','',$a[text]));
  $t = explode('<new_page>',$a[text]);
  if ($t[1])
  { if (!is_numeric($in[page])) { $p = 0; $in[page] = 1; } else $p = $in[page] - 1;
    $a[text] = $t[$p];
    unset($y);
    for ($x=1;$x<=count($t);$x++)
    { if ($x==$in[page]) $y .= "&nbsp;$x";  
      else $y .= '&nbsp;<a href="'.get_detail_page_url('n',$in[n],$a[rewrite_url],$a[category],$x).'">'.$x.'</a>';
    }
    $a[pages_list] = "<b>$m[pages_list]</b>&nbsp;$y<br />";
  }
}

$a[meta_title] = $a[title];
$a[meta_description] = $a[description];
$a[meta_keywords] = str_replace("\n",', ',$a[keywords]);
page_from_template($template,$a);
}

##################################################################################

function count_click($direction,$what,$n) {
global $s;

if ((!is_numeric($n)) OR (!$n)) return false;
if ($what=='link')
{ if ($direction=='in') $check = $s[l_checkip_in];
  elseif ($direction=='out') $check = $s[l_checkip_out];
}
elseif ($what=='adlink') $check = $s[adlink_checkip];
if ($check) 
{ $q = dq("SELECT COUNT(*) FROM $s[pr]links_ip WHERE direction = '$direction' AND what = '$what' AND n = '$n' AND ip = '$s[ip]'",0);
  $u = mysql_fetch_row($q);
  if ($u[0]) $not_count = 1;
  else dq("insert into $s[pr]links_ip VALUES ('$n','$s[ip]','$what','$direction')",0);
}


if (!$not_count)
{ if ($what=='link')
  { if ($direction=='in') dq("update $s[pr]links set clicks_in = clicks_in + 1, clicks_in_m = clicks_in_m + 1 WHERE n = '$n'",0);
    elseif ($direction=='out')
    { dq("update $s[pr]links set hits = hits + 1,hits_m = hits_m + 1 WHERE n = '$n'",1);
      dq("update $s[pr]links_stat set c = c + 1, r = c/i*100, c_month = c_month + 1, r_month = c_month/i_month*100, c_reset = c_reset + 1, r_reset = c_reset/i_reset*100 WHERE n = '$n'",1);
      dq("update $s[pr]links_days set c = c + 1, r = c/i*100 WHERE n = '$n' and y = '$s[year]' and m = '$s[month]' and d = '$s[day]'",1);
      if (mysql_affected_rows()<=0) dq("insert into $s[pr]links_days values ('$n','0','1','0','$s[year]','$s[month]','$s[day]')",1);
      $link_adv_vars = get_link_adv_variables($n);
      if ($link_adv_vars[c_now]) $link_adv_vars_q = "c_now = c_now - 1";
      elseif ($link_adv_vars[c_dynamic_now]) $link_adv_vars_q = "c_dynamic_now = c_dynamic_now - 1";
      if ($link_adv_vars_q)
      { dq("update $s[pr]links_adv set $link_adv_vars_q where n = '$n'",1);
        if ((($link_adv_vars[c_now]==1) OR ($link_adv_vars[c_dynamic_now]==1)) AND ($s[inform_sponsors])) inform_advertiser($n);
      }
    }
  }
  elseif ($what=='new') dq("update $s[pr]news set hits = hits + 1, hits_m = hits_m + 1 WHERE n = '$n'",1);
  elseif ($what=='adlink')
  { dq("update $s[pr]adlinks set c_now = c_now - 1 where n = '$n' and c_now >= 1",1);
    if ($s[inform_adlink_owners]) { $adlink_vars = get_adlink_variables($n); if (!$adlink_vars[c_now]) inform_adlink_owner($adlink_vars); }
  }
}
}

##################################################################################

function track_item_hit($what,$n) {
global $s;
if ($_COOKIE["read_$what"][$n]) return false;
setcookie ('read_'.$what.'['.$n.']',$s[cas],$s[cas]+31536000);
$table = $s[item_types_tables][$what];
dq("update $table set hits = hits + 1, hits_m = hits_m + 1 where n = '$n'",0);
}

##################################################################################

function inform_adlink_owner($adlink_vars) {
global $s;
$user_vars = get_user_variables($adlink_vars[owner]);
if (!$user_vars[email]) return false;
$adlink_vars[to] = $user_vars[email];
$adlink_vars[login_url] = $s[site_url].'/user.php';
mail_from_template('adlink_end.txt',$adlink_vars);
}

##################################################################################

function count_impressions($numbers) {
global $s;
if (!$numbers) return false;
if (is_array($numbers)) $query = my_implode('n','or',$numbers);
else $query = "n = '$numbers'";
dq("update $s[pr]links_stat set i = i + 1, r = c/i*100, i_month = i_month + 1, r_month = c_month/i_month*100, i_reset = i_reset + 1, r_reset = c_reset/i_reset*100 where $query",1);
}

##################################################################################

function count_adv_impression($link_adv) {
global $s;
if ($link_adv[i_now]) dq("update $s[pr]links_adv set i_now = i_now - 1 where n = '$link_adv[n]'",1);
if ($link_adv[i_now]==1) inform_advertiser($link_adv[n]);
//dq("update $s[pr]links_days set i = i + 1, r = c/i*100 WHERE n = '$link_adv[n]' and y = '$s[year]' and m = '$s[month]' and d = '$s[day]'",1);
//if (!mysql_affected_rows()) dq("insert into $s[pr]links_days values ('$link_adv[n]','1','0','0','$s[year]','$s[month]','$s[day]')",1);
}

##################################################################################

function get_favorites_status($what,$in_n) {
global $s,$m;
if (is_array($in_n)) $n = $in_n; else $n[0] = $in_n;
$query = my_implode('n','or',$n);
if (!$query) return false;
$q = dq("select * from $s[pr]u_favorites where user = '$s[LUG_u_n]' AND what = '$what' AND $query",1);
while ($x=mysql_fetch_assoc($q)) $bookmarks[$x[n]] = 1;
return $bookmarks;
}

##################################################################################

function get_favorite_line($what,$n,$bookmark) {
global $s,$m;
if ($s[item_types_Words][$what]) $what_word = $m[bookmark_it]; else $what_word = $m[bookmark_this_category];
if ($bookmark) return '<a href="'.$s[site_url].'/favorites.php?action=remove&amp;what='.$what.'&amp;n='.$n.'">'.$m[remove_from_bookmarks].'</a>';
else return '<a href="'.$s[site_url].'/favorites.php?action=add&amp;what='.$what.'&amp;n='.$n.'">'.$what_word.'</a>';
}

##################################################################################

function get_private_notes_for_items($what,$in_n) {
global $s,$m;
if (is_array($in_n)) $n = $in_n; else $n[0] = $in_n;
$query = my_implode('n','or',$n);
if (!$query) return false;
$q = dq("select * from $s[pr]u_private_notes where user = '$s[LUG_u_n]' AND what = '$what' AND $query",1);
while ($x=mysql_fetch_assoc($q)) $notes[$x[n]] = nl2br($x[notes]);
return $notes;
}

##################################################################################
##################################################################################
##################################################################################

function get_detail_template_name($what,$categories) {
global $s;
$query = my_implode('n','OR',explode(' ',str_replace('_','',$categories)));
$q = dq("select tmpl_det from $s[pr]cats where use_for = '$what' AND visible = '1' AND $query limit 1",0);
$y = mysql_fetch_row($q);
if (!$y[0]) $y[0] = $s[item_types_words][$what].'_details.html';
return $y[0];
}

##################################################################################
##################################################################################
##################################################################################

function inform_advertiser($n) {
global $s;
$link = get_item_variables('l',$n);
if (!$link[email]) return false;
$link[to] = $link[email];
$link[login_url] = $s[site_url].'/user.php';
mail_from_template('advertising_link_end.txt',$link);
}

##################################################################################
##################################################################################
##################################################################################

function get_complete_links($links,$numbers,$in_template) {
global $s;
/*
#%user_item_value_28%#
$usit['individual_'.$a[n]][user_item_value_28]
*/

$width = floor(100/$s[l_columns]);
if ($s[usit_in_cats]) $usit = user_defined_items_display('l',$all_user_items_list,$all_user_items_values,$numbers,'user_item_listing.txt',0,1,0,1);
//list($images,$files) = pictures_files_display_public('l',$numbers,0);
count_impressions($numbers);
if ($s[LUG_u_n])
{ $notes = get_private_notes_for_items('l',$numbers);
  $bookmarks = get_favorites_status('l',$numbers);
}
foreach ($links as $k => $a)
{ if ($s[usit_in_cats]) 
  { $a[user_defined] = $usit[$a[n]]; if (!$a[user_defined]) { $a[hide_usit_begin] = '<!--'; $a[hide_usit_end] = '-->'; }
    foreach ($usit['individual_'.$a[n]] as $k1=>$v1) $a[$k1] = $v1;
  }
  $a[title_no_tag] = strip_tags($a[title]);
  //if ($a[user_item_5]) $a[url] = "$s[site_url]/view.php?n=$a[n]";// redirect if usit 5 checked
  //if ($a["user_item_value_1"]) $a[rss_url] = "$s[site_url]/link_rss.php?n=$a[n]";
  //else { $a[hide_rss_url_begin] = '<!--'; $a[hide_rss_url_end] = '-->'; }
  if ($a[picture])
  { $picture1_path = str_replace($s[site_url],$s[phppath],$a[picture]);
	if (file_exists($picture1_path))
	{ $a[image_1] = $a[picture];
	  if (file_exists(preg_replace("/\/$a[n]-/","/$a[n]-big-",$picture1_path))) $a[image_1_big] = preg_replace("/\/$a[n]-/","/$a[n]-big-",$a[picture]);
      else $a[image_1_big] = $a[picture];
      $a[pictures]++;
    }
  }
  if (!$a[pictures])
  { if (file_exists("$s[phppath]/images/thumbnails/$a[n].gif")) { $a[pictures] = 1; $a[image_1] = $a[image_1_big] = "$s[site_url]/images/thumbnails/$a[n].gif"; }
    elseif ($s[l_thumbnail_url]) { $x = parse_url($a[url]); $a[pictures] = 1; $a[image_1] = $a[image_1_big] = str_replace('#%domain%#',$x[host],$s[l_thumbnail_url]); }
  }
  if (!$a[pictures]) { $a[hide_pictures_begin] = '<!--'; $a[hide_pictures_end] = '-->'; }
  $a[icons] = get_icons_for_item('l',$a,$bookmarks[$a[n]]);
  $a[item_details_url] = get_detail_page_url('l',$a[n],$a[rewrite_url],$a[category],1);
  if ($a[t1]>$a[created]) $a[created] = datum($a[t1],0); else $a[created] = datum($a[created],0);
  if ($a[updated]) $a[updated] = datum($a[updated],0); else { $a[hide_updated_begin] = '<!--'; $a[hide_updated_end] = '-->'; }
  $a[rateicon] = get_rateicon($a[rating]);
  if (strstr($a[detail],'<complete_link>')) $a[link] = $a[detail];
  elseif ($a[url]) $a[link] = '<a target="'.$s[rl_open_window].'" href="'.$a[url].'" OnClick="track_image_'.$a[n].'.src=\''.$s[site_url].'/track_click.php?free_link='.$a[n].'\';"><h2>'.$a[title].'</h2></a>';
  else $a[link] = '<h2>'.$a[title].'</h2>';
  if ($s[det_br]) $a[detail] = str_replace("\n",'<br />',$a[detail]);
  //$a[detail] = str_replace('&#039;',"'",$a[detail]);
  if (($s[search_highlight]) AND ($s[highlight])) { $a[title] = highlight_words('',$a[title]); $a[description] = highlight_words('',$a[description]); $a[detail] = highlight_words('',$a[detail]); $a[display_url] = highlight_words('',$a[url]); }
  //$x = list_of_categories_for_item('l',0,$a[c],'<br />',0); $a[categories] = $x[categories]; $a[categories_incl] = $x[categories_incl]; $a[categories_names] = $x[categories_names];
  if (trim($a[keywords])) $a[tags] = tags_for_item('l',0,$a[keywords]); else { $a[hide_tags_begin] = '<!--'; $a[hide_tags_end] = '-->'; }
  $a[report_box] = report_box('l',$a[n]);
  $a[tell_friend_box] = tell_friend_box('l',$a[n]);
  $a[enter_comment_box] = enter_comment_box('l',$a[n]);
  //echo $report_box; exit;
  if ($s[LUG_u_n])
  { $a[add_delete_favorites] = get_favorite_line('l',$a[n],$bookmarks[$a[n]]);
    if ($notes[$a[n]]) { $a[notes] = $notes[$a[n]]; $a[notes_style_display] = 'block'; } else $a[notes_style_display] = 'none';
    $s[current_notes] = $a[notes]; $a[notes_edit_box] = notes_edit_box('l',$a[n],'');
  }
  $template = $in_template;
  $is_advertising = 0;
  if ($a[sponsored])
  { $link_adv = get_link_adv_variables($a[n]);
    $is_advertising = get_link_advertising_status($link_adv);
    if (($link_adv[c_dynamic_now]) AND ($link_adv[c_dynamic_price]!=0)) $a[price] = $link_adv[c_dynamic_price];
    count_adv_impression($link_adv);
  }
  if (!$a[price]) { $a[hide_click_price_begin] = '<!--'; $a[hide_click_price_end] = '-->'; }
  if (check_admin_rights('all_links')) $a[edit_link] = '<p><a target="_blank" href="'.$s[site_url].'/administration/link_details.php?action=link_edit&amp;n='.$a[n].'">Edit this link</a></p>';
  if (($is_advertising) AND ($in_template!='link_c.txt')) $template = 'link_advertising.txt';
  $complete_array[] = '<td valign="top" width="'.$width.'%" style="padding:0px;">'.parse_part($template,$a).'</td>';
  $pocet++;
}
$rows = ceil($pocet/$s[l_columns]);
for ($x=$pocet+1;$x<=($rows*$s[l_columns]);$x++)
{ $complete_array[] = '<td>&nbsp;</td>';
  $pocet++;
}
for ($x=1;$x<=$rows;$x++)
{ $complete .= '<tr>';
  for ($y=($x-1)*$s[l_columns];$y<=$x*$s[l_columns]-1;$y++)
  $complete .= $complete_array[$y];
  $complete .= '</tr>';
}
return $complete;
}

##################################################################################

function get_complete_articles($article,$numbers,$template) {
global $s;
$width = floor(100/$s[a_columns]);
if ($s[usit_in_cats]) $usit = user_defined_items_display('a',$all_user_items_list,$all_user_items_values,$numbers,'user_item_listing.txt',0,1,0,1);
//list($images,$files) = pictures_files_display_public('a',$numbers,0);
if ($s[LUG_u_n])
{ $notes = get_private_notes_for_items('a',$numbers);
  $bookmarks = get_favorites_status('a',$numbers);
}
foreach ($article as $k => $a)
{ if ($s[usit_in_cats]) 
  { $a[user_defined] = $usit[$a[n]]; if (!$a[user_defined]) { $a[hide_usit_begin] = '<!--'; $a[hide_usit_end] = '-->'; }
    foreach ($usit['individual_'.$a[n]] as $k1=>$v1) $a[$k1] = $v1;
  }
  if ($a[picture])
  { $picture1_path = str_replace($s[site_url],$s[phppath],$a[picture]);
	if (file_exists($picture1_path))
	{ $a[image_1] = $a[picture];
	  if (file_exists(preg_replace("/\/$a[n]-/","/$a[n]-big-",$picture1_path))) $a[image_1_big] = preg_replace("/\/$a[n]-/","/$a[n]-big-",$a[picture]);
      else $a[image_1_big] = $a[picture];
      $a[pictures]++;
    }
  }
  if (!$a[pictures]) { $a[hide_pictures_begin] = '<!--'; $a[hide_pictures_end] = '-->'; }
  $a[title_no_tag] = strip_tags($a[title]);
  $a[icons] = get_icons_for_item('a',$a,$bookmarks[$a[n]]);
  $a[item_details_url] = get_detail_page_url('a',$a[n],$a[rewrite_url],0,1);
  if ($a[t1]>$a[created]) $a[created] = datum($a[t1],0);
  else $a[created] = datum($a[created],0);
  if ($a[updated]) $a[updated] = datum($a[updated],0); else { $a[hide_updated_begin] = '<!--'; $a[hide_updated_end] = '-->'; }
  $a[rateicon] = get_rateicon($a[rating]);
  //$x = list_of_categories_for_item('a',0,$a[c],'<br />',0); $a[categories] = $x[categories]; $a[categories_incl] = $x[categories_incl]; $a[categories_names] = $x[categories_names];
  if (trim($a[keywords])) $a[tags] = tags_for_item('a',0,$a[keywords]); else { $a[hide_tags_begin] = '<!--'; $a[hide_tags_end] = '-->'; }
  if (($s[search_highlight]) AND ($s[highlight])) { $a[title] = highlight_words('',$a[title]); $a[description] = highlight_words('',$a[description]); $a[text] = highlight_words('',$a[text]); }
  $a[tell_friend_box] = tell_friend_box('a',$a[n]);
  $a[enter_comment_box] = enter_comment_box('a',$a[n]);
  if ($s[LUG_u_n])
  { $a[add_delete_favorites] = get_favorite_line('a',$a[n],$bookmarks[$a[n]]);
    if ($notes[$a[n]]) { $a[notes] = $notes[$a[n]]; $a[notes_style_display] = 'block'; } else $a[notes_style_display] = 'none';
    $s[current_notes] = $a[notes]; $a[notes_edit_box] = notes_edit_box('a',$a[n],'');
  }
  if (check_admin_rights('all_articles')) $a[edit_link] = '<p><a target="_blank" href="'.$s[site_url].'/administration/article_details.php?action=article_edit&amp;n='.$a[n].'">Edit this article</a></p>';
  $complete_array[] = '<td valign="top" width="'.$width.'%">'.parse_part($template,$a).'</td>';
  $pocet++;
}
$rows = ceil($pocet/$s[a_columns]);
for ($x=$pocet+1;$x<=($rows*$s[a_columns]);$x++)
{ $complete_array[] = '<td>&nbsp;</td>';
  $pocet++;
}
for ($x=1;$x<=$rows;$x++)
{ $complete .= '<tr>';
  for ($y=($x-1)*$s[a_columns];$y<=$x*$s[a_columns]-1;$y++)
  $complete .= $complete_array[$y];
  $complete .= '</tr>';
}
return $complete;
}


##################################################################################

function get_complete_blogs($blog,$numbers,$template) {
global $s;
$width = floor(100/$s[b_columns]);
if ($s[usit_in_cats]) $usit = user_defined_items_display('b',$all_user_items_list,$all_user_items_values,$numbers,'user_item_listing.txt',0,1,0,1);
//list($images,$files) = pictures_files_display_public('b',$numbers,0);
if ($s[LUG_u_n])
{ $notes = get_private_notes_for_items('b',$numbers);
  $bookmarks = get_favorites_status('b',$numbers);
}
foreach ($blog as $k => $a)
{ if ($s[usit_in_cats]) 
  { $a[user_defined] = $usit[$a[n]]; if (!$a[user_defined]) { $a[hide_usit_begin] = '<!--'; $a[hide_usit_end] = '-->'; }
    foreach ($usit['individual_'.$a[n]] as $k1=>$v1) $a[$k1] = $v1;
  }
  if ($a[picture])
  { $picture1_path = str_replace($s[site_url],$s[phppath],$a[picture]);
	if (file_exists($picture1_path))
	{ $a[image_1] = $a[picture];
	  if (file_exists(preg_replace("/\/$a[n]-/","/$a[n]-big-",$picture1_path))) $a[image_1_big] = preg_replace("/\/$a[n]-/","/$a[n]-big-",$a[picture]);
      else $a[image_1_big] = $a[picture];
      $a[pictures]++;
    }
  }
  if (!$a[pictures]) { $a[hide_pictures_begin] = '<!--'; $a[hide_pictures_end] = '-->'; }
  $a[title_no_tag] = strip_tags($a[title]);
  $a[icons] = get_icons_for_item('b',$a,$bookmarks[$a[n]]);
  $a[item_details_url] = get_detail_page_url('b',$a[n],$a[rewrite_url],0,1);
  if ($a[t1]>$a[created]) $a[created] = datum($a[t1],0);
  else $a[created] = datum($a[created],0);
  if ($a[updated]) $a[updated] = datum($a[updated],0); else { $a[hide_updated_begin] = '<!--'; $a[hide_updated_end] = '-->'; }
  $a[rateicon] = get_rateicon($a[rating]);
  //$x = list_of_categories_for_item('b',0,$a[c],'<br />',0); $a[categories] = $x[categories]; $a[categories_incl] = $x[categories_incl]; $a[categories_names] = $x[categories_names];
  if (trim($a[keywords])) $a[tags] = tags_for_item('b',0,$a[keywords]); else { $a[hide_tags_begin] = '<!--'; $a[hide_tags_end] = '-->'; }
  if (($s[search_highlight]) AND ($s[highlight])) { $a[title] = highlight_words('',$a[title]); $a[description] = highlight_words('',$a[description]); $a[text] = highlight_words('',$a[text]); }
  $a[tell_friend_box] = tell_friend_box('b',$a[n]);
  $a[enter_comment_box] = enter_comment_box('b',$a[n]);
  if ($s[LUG_u_n])
  { $a[add_delete_favorites] = get_favorite_line('b',$a[n],$bookmarks[$a[n]]);
    if ($notes[$a[n]]) { $a[notes] = $notes[$a[n]]; $a[notes_style_display] = 'block'; } else $a[notes_style_display] = 'none';
    $s[current_notes] = $a[notes]; $a[notes_edit_box] = notes_edit_box('b',$a[n],'');
  }
  if (check_admin_rights('all_blogs')) $a[edit_link] = '<p><a target="_blank" href="'.$s[site_url].'/administration/blog_details.php?action=blog_edit&amp;n='.$a[n].'">Edit this blog</a></p>';
  $complete_array[] = '<td valign="top" width="'.$width.'%">'.parse_part($template,$a).'</td>';
  $pocet++;
}
$rows = ceil($pocet/$s[b_columns]);
for ($x=$pocet+1;$x<=($rows*$s[b_columns]);$x++)
{ $complete_array[] = '<td>&nbsp;</td>';
  $pocet++;
}
for ($x=1;$x<=$rows;$x++)
{ $complete .= '<tr>';
  for ($y=($x-1)*$s[b_columns];$y<=$x*$s[b_columns]-1;$y++)
  $complete .= $complete_array[$y];
  $complete .= '</tr>';
}
return $complete;
}

##################################################################################

function get_complete_news($new,$numbers,$template) {
global $s;
$width = floor(100/$s[n_columns]);
if ($s[usit_in_cats]) $usit = user_defined_items_display('n',$all_user_items_list,$all_user_items_values,$numbers,'user_item_listing.txt',0,1,0,1);
//list($images,$files) = pictures_files_display_public('n',$numbers,0);
if ($s[LUG_u_n])
{ $notes = get_private_notes_for_items('n',$numbers);
  $bookmarks = get_favorites_status('n',$numbers);
}
foreach ($new as $k => $a)
{ if ($s[usit_in_cats]) 
  { $a[user_defined] = $usit[$a[n]]; if (!$a[user_defined]) { $a[hide_usit_begin] = '<!--'; $a[hide_usit_end] = '-->'; }
    foreach ($usit['individual_'.$a[n]] as $k1=>$v1) $a[$k1] = $v1;
  }
  if ($a[picture])
  { $picture1_path = str_replace($s[site_url],$s[phppath],$a[picture]);
	if (file_exists($picture1_path))
	{ $a[image_1] = $a[picture];
	  if (file_exists(preg_replace("/\/$a[n]-/","/$a[n]-big-",$picture1_path))) $a[image_1_big] = preg_replace("/\/$a[n]-/","/$a[n]-big-",$a[picture]);
      else $a[image_1_big] = $a[picture];
      $a[pictures]++;
    }
  }
  if (!$a[pictures]) { $a[hide_pictures_begin] = '<!--'; $a[hide_pictures_end] = '-->'; }
  $a[title_no_tag] = strip_tags($a[title]);
  $a[icons] = get_icons_for_item('n',$a,$bookmarks[$a[n]]);
  $a[item_details_url] = get_detail_page_url('n',$a[n],$a[rewrite_url],0,1);
  if ($a[t1]>$a[created]) $a[created] = datum($a[t1],0);
  else $a[created] = datum($a[created],0);
  if ($a[updated]) $a[updated] = datum($a[updated],0); else { $a[hide_updated_begin] = '<!--'; $a[hide_updated_end] = '-->'; }
  $a[rateicon] = get_rateicon($a[rating]);
  //$x = list_of_categories_for_item('n',0,$a[c],'<br />',0); $a[categories] = $x[categories]; $a[categories_incl] = $x[categories_incl]; $a[categories_names] = $x[categories_names];
  if (trim($a[keywords])) $a[tags] = tags_for_item('n',0,$a[keywords]); else { $a[hide_tags_begin] = '<!--'; $a[hide_tags_end] = '-->'; }
  if (!$a[description]) { $a[description] = strip_tags($a[text],'<img>'); $a[text] = ''; }
  if (($s[search_highlight]) AND ($s[highlight])) { $a[title] = highlight_words('',$a[title]); $a[description] = highlight_words('',$a[description]); $a[text] = highlight_words('',$a[text]); }
  $a[tell_friend_box] = tell_friend_box('n',$a[n]);
  $a[enter_comment_box] = enter_comment_box('n',$a[n]);
  if ($s[LUG_u_n])
  { $a[add_delete_favorites] = get_favorite_line('n',$a[n],$bookmarks[$a[n]]);
    if ($notes[$a[n]]) { $a[notes] = $notes[$a[n]]; $a[notes_style_display] = 'block'; } else $a[notes_style_display] = 'none';
    $s[current_notes] = $a[notes]; $a[notes_edit_box] = notes_edit_box('n',$a[n],'');
  }
  if (check_admin_rights('all_news')) $a[edit_link] = '<p><a target="_blank" href="'.$s[site_url].'/administration/new_details.php?action=new_edit&amp;n='.$a[n].'">Edit this news item</a></p>';
  $complete_array[] = '<td valign="top" width="'.$width.'%">'.parse_part($template,$a).'</td>';
  $pocet++;
}
$rows = ceil($pocet/$s[n_columns]);
for ($x=$pocet+1;$x<=($rows*$s[n_columns]);$x++)
{ $complete_array[] = '<td>&nbsp;</td>';
  $pocet++;
}
for ($x=1;$x<=$rows;$x++)
{ $complete .= '<tr>';
  for ($y=($x-1)*$s[n_columns];$y<=$x*$s[n_columns]-1;$y++)
  $complete .= $complete_array[$y];
  $complete .= '</tr>';
}
return $complete;
}
##################################################################################

function get_complete_videos($video,$numbers,$template) {
global $s;
$width = floor(100/$s[v_columns]);
if ($s[usit_in_cats]) $usit = user_defined_items_display('v',$all_user_items_list,$all_user_items_values,$numbers,'user_item_listing.txt',0,1,0,1);
//list($images,$files) = pictures_files_display_public('v',$numbers,0);
if ($s[LUG_u_n])
{ $notes = get_private_notes_for_items('v',$numbers);
  $bookmarks = get_favorites_status('v',$numbers);
}
foreach ($video as $k => $a)
{ if ($s[usit_in_cats]) 
  { $a[user_defined] = $usit[$a[n]]; if (!$a[user_defined]) { $a[hide_usit_begin] = '<!--'; $a[hide_usit_end] = '-->'; }
    foreach ($usit['individual_'.$a[n]] as $k1=>$v1) $a[$k1] = $v1;
  }
  if ($a[youtube_thumbnail]) { $a[pictures] = 1; $a[image_1] = $a[youtube_thumbnail]; }
  elseif ($a[picture]) 
  { $a[image_1] = $a[picture];
	$big_file = preg_replace("/\/$a[n]-/","/$a[n]-big-",$a[picture]);
    if (file_exists(str_replace("$s[site_url]/","$s[phppath]/",$big_file))) $a[image_1_big] = $big_file;
    else $a[image_1_big] = $a[picture];
    $a[pictures]++;
  }
  if (!$a[pictures]) { $a[hide_pictures_begin] = '<!--'; $a[hide_pictures_end] = '-->'; }
  $a[title_no_tag] = strip_tags($a[title]);
  $a[icons] = get_icons_for_item('v',$a,$bookmarks[$a[n]]);
  $a[item_details_url] = get_detail_page_url('v',$a[n],$a[rewrite_url],0,1);
  if ($a[t1]>$a[created]) $a[created] = datum($a[t1],0);
  else $a[created] = datum($a[created],0);
  if ($a[updated]) $a[updated] = datum($a[updated],0); else { $a[hide_updated_begin] = '<!--'; $a[hide_updated_end] = '-->'; }
  $a[rateicon] = get_rateicon($a[rating]);
  //$x = list_of_categories_for_item('v',0,$a[c],'<br />',0); $a[categories] = $x[categories]; $a[categories_incl] = $x[categories_incl]; $a[categories_names] = $x[categories_names];
  if (trim($a[keywords])) $a[tags] = tags_for_item('v',0,$a[keywords]); else { $a[hide_tags_begin] = '<!--'; $a[hide_tags_end] = '-->'; }
  if (!$a[description]) { $a[description] = $a[text]; $a[text] = ''; }
  $a[video_code] = youtube_player($a[youtube_id],$a[video_code]);
  if (($s[search_highlight]) AND ($s[highlight])) { $a[title] = highlight_words('',$a[title]); $a[description] = highlight_words('',$a[description]); $a[text] = highlight_words('',$a[text]); }
  $a[tell_friend_box] = tell_friend_box('v',$a[n]);
  $a[enter_comment_box] = enter_comment_box('v',$a[n]);
  if ($s[LUG_u_n])
  { $a[add_delete_favorites] = get_favorite_line('v',$a[n],$bookmarks[$a[n]]);
    if ($notes[$a[n]]) { $a[notes] = $notes[$a[n]]; $a[notes_style_display] = 'block'; } else $a[notes_style_display] = 'none';
    $s[current_notes] = $a[notes]; $a[notes_edit_box] = notes_edit_box('v',$a[n],'');
  }
  if (check_admin_rights('all_videos')) $a[edit_link] = '<p><a target="_blank" href="'.$s[site_url].'/administration/video_details.php?action=video_edit&amp;n='.$a[n].'">Edit this video</a></p>';
  $complete_array[] = '<td valign="top" width="'.$width.'%">'.parse_part($template,$a).'</td>';
  $pocet++;
}
$rows = ceil($pocet/$s[v_columns]);
for ($x=$pocet+1;$x<=($rows*$s[v_columns]);$x++)
{ $complete_array[] = '<td>&nbsp;</td>';
  $pocet++;
}
for ($x=1;$x<=$rows;$x++)
{ $complete .= '<tr>';
  for ($y=($x-1)*$s[v_columns];$y<=$x*$s[v_columns]-1;$y++)
  $complete .= $complete_array[$y];
  $complete .= '</tr>';
}
return $complete;
}

##################################################################################

function highlight_words($word,$in) {
global $s;
if ($word) $highlight[] = trim($word); else $highlight = $s[highlight];
foreach ($highlight as $k=>$v) { $highlight[] = ucfirst($v); $highligh[] = strtolower($v); }
foreach ($highlight as $k=>$v) $in = str_replace($v,'<span class="text_highlight">'.$v.'</span>',$in);
return $in;
}

##################################################################################
##################################################################################
##################################################################################

function get_messages($script) {
global $s,$m;
if ($s[LUG_style]) $st = $s[LUG_style]; elseif ($_SESSION[LUG_style]) $st = $_SESSION[LUG_style]; else $st = $s[def_style];
if (file_exists($s[phppath].'/styles/'.$st.'/messages/'.$script)) $x = $st; else $x = '_common';
include($s[phppath].'/styles/'.$x.'/messages/'.$script);
}

##################################################################################

function page_from_template($t,$vl) {
global $s,$m;
//$t1 = getmicrotime();
if (!is_array($vl)) $vl = array();
$vl = array_merge($vl,get_common_variables());
$vl[online] = who_is_online();
foreach ($s[item_types_short] as $k=>$what) { $vl["s_active_$what"] = $s["s_active_$what"]; $vl["s_categories_$what"] = $s["s_categories_$what"]; }
if ($s[selected_menu]) $vl[selected_menu] = $s[selected_menu]; else $vl[selected_menu] = 0;
$vl[tell_friend_site_box] = tell_friend_box('',0);
$vl[contact_site_box] = contact_box('',0);
$vl[user_login_form] = user_login_form(0);
if (!$vl[home_link]) { $vl[home_link] = "$s[site_url]/"; $vl[hide_index_links_begin] = '<!--'; $vl[hide_index_links_end] = '-->'; }

if (!$vl[meta_title]) $vl[meta_title] = $s[site_name];
if ($vl[meta_description]) $vl[meta_description] = substr(str_replace("\r",'',str_replace("\n",' ',str_replace('&#039;',"'",strip_tags($vl[meta_description])))),0,200); else $vl[meta_description] = $s[site_description];
if ($vl[meta_keywords]) $vl[meta_keywords] = substr(str_replace("\r",'',str_replace("\n",' ',str_replace('&#039;',"'",strip_tags($vl[meta_keywords])))),0,200); else $vl[meta_keywords] = $s[site_keywords];

if ($s[LUG_u_n])
{ $vl[hide_for_user_begin] = '<!--'; $vl[hide_for_user_end] = '-->';
  $vl[LUG_u_username] = $s[LUG_u_username];
  $vl[hide_div_user] = ' style="display:none;"';
  $check_field = check_field_create("$s[LUG_u_username]$s[LUG_u_password]$s[LUG_u_n]");
}
else
{ $vl[user_login_form] = user_login_form();
  $vl[hide_for_no_user_begin] = '<!--'; $vl[hide_for_no_user_end] = '-->';
  $vl[hide_div_no_user] = ' style="display:none;"';
}

foreach ($s[items_types_words] as $k=>$v)
{ if (!$vl['rss_'.$v.'_category_url']) { $vl['hide_rss_'.$v.'_category_begin'] = '<!--'; $vl['hide_rss_'.$v.'_category_end'] = '-->'; }
  $vl["search_display_$k"] = 'none';
}
$vl["search_display_all"] = $vl["search_display_google"] = 'none';
if (!$s[search_display]) $s[search_display] = 'all';
$vl["search_display_$s[search_display]"] = 'block';
if ($s[head_pagination]) $vl[head_pagination] = $s[head_pagination];
$q = dq("select * from $s[pr]static where (style = '$s[LUG_style]' or style = '0') and page = '0'",1);
while ($x = mysql_fetch_assoc($q)) $vl[$x[mark]] = $x[html];
include("$s[phppath]/data/info.php");
if ($s[show_qr])
{ if ($vl[this_url]) $qrurl = $vl[this_url]; else $qrurl = "$s[site_url]/";
  $vl[qrimage] = '<img border="0" src="'.$s[site_url].'/qrimage.php?url='.urlencode($qrurl).'">';
}
$q = dq("select * from $s[pr]users order by n desc limit 1",1);
$last_user = mysql_fetch_assoc($q);
if ($last_user[nick]) $vl[last_user_name] = $last_user[nick]; else $vl[last_user_name] = $last_user[name]; 
$vl[last_user_url] = get_detail_page_url('u',$last_user[n],$last_user[nick]);
$vl[total_users] = $s[s_users];

$vl[styles_options] = get_styles_options($s[styles],$s[LUG_style]);
$styles = explode(',',$s[styles]);
foreach ($styles as $k=>$v)
{ $style_n++;
  if ($v==$s[LUG_style]) $style_image = 'style_current.png'; else $style_image = "style_$v.png";
  if ($v==$s[LUG_style]) $vl[menu_styles] .= "<li><a href=\"$s[site_url]/styles.php?style=$v\" style=\"TEXT-TRANSFORM: uppercase;\"><b>".str_replace('_',' ',$v)."</b></a></li>";
  else $vl[menu_styles] .= "<li><a href=\"$s[site_url]/styles.php?style=$v\">".str_replace('_',' ',$v)."</a></li>";
}

if ($vl[original_phrase]) $words = explode(' ',$vl[original_phrase]);
elseif ($vl[current_title]) $words = explode(' ',$vl[current_title]);
elseif ($vl[title]) $words = explode(' ',$vl[title]);
if ((!$words) OR (!$words[0])) $words = explode(' ',$s[site_name]);
foreach ($words as $k=>$v) $words1[] = '"'.trim($v).'"';
$vl[current_words] = implode(',',$words1);

for ($x=1;$x<=$s[in_templates];$x++)
{ $vl["in$x"] = str_replace('&#039;',"'",parse_variables($vl["in$x"],$vl));
  if ($_GET[bigboss]) $vl["in$x"] = str_replace('value="'.$_GET[bigboss].'"','value="'.$_GET[bigboss].'" selected',$vl["in$x"]); // searched
  if ($_GET[search_kind]) $vl["in$x"] = str_replace('value="'.$_GET[search_kind].'"','value="'.$_GET[search_kind].'" selected',$vl["in$x"]); // searched
  //if ($_GET[search_kind]) $vl["in$x"] = str_replace('value="'.$_GET[search_kind].'"','value="'.$_GET[search_kind].'" selected',$vl["in$x"]); // searched
}

//q=referral+google
if ($s[search_engine_titles])
{ $rererer = parse_url(getenv('HTTP_REFERER')); 
  if ((strstr($rererer[host],'google')) AND (trim($rererer[query])))
  { $x = explode('&',$rererer[query]);
    foreach ($x as $k=>$v) { if (strstr($v,'q=')) { $x = explode('=',$v); $referred_title = urldecode($x[1]); break; } }	
  }
  //echo $referred_title;
  if (trim($referred_title)) $vl[title] = "$referred_title";
}

$vl[head] = parse_variables_in_template(template_select('_head2.txt',0,$s[LUG_style]),$vl);
$line = parse_variables_in_template(template_select('_head1.txt',0,$s[LUG_style]),$vl);
$line .= parse_variables_in_template(template_select($t,0,$s[LUG_style]),$vl);
$line .= str_replace('</body',$info.'</body',parse_variables_in_template(template_select('_footer.txt',0,$s[LUG_style]),$vl));

if ($s[A_option]=='static') $line = A_replace_dynamic_urls1($line); elseif ($s[A_option]=='rewrite') $line = rewrite_replace_dynamic_urls($line);

foreach ($s[item_types_short] as $k=>$v) if (!$s["section_$v"]) $line = preg_replace('/#%begin_'.$v.'%#(.*)#%end_'.$v.'%#/eisU','',$line);

$line = str_replace('--BACKSLASH--','\\',$line);
$line = str_replace('</form>',$check_field.'</form>',$line);
if ($s[A_option]=='static') $line = str_replace("$s[site_url]/index.php","$s[site_url]/$s[Aindexhtml]",$line);
$https_url = str_replace('http://','https://',$s[site_url]); if (getenv('HTTPS')) $line = str_replace($s[site_url],$https_url,$line);
echo str_replace('&#039;',"'",$line);
exit;
}

##################################################################################
/*
function search_engines($what,$n) {
global $s;
$rererer = parse_url(getenv('HTTP_REFERER'));
if (strstr($rererer[host],'google'))
{ $x = explode('&',$rererer[query]);
  foreach ($x as $k=>$v) { if (strstr($v,'q=')) { $x = explode('=',$v); $keywords = $x[1]; break; } }	
}
//echo $keywords;
if (trim($keywords))
{ if ($what=='ad') $table = "$s[pr]se_ads"; elseif ($what=='c') $table = "$s[pr]se_cats";
  $q = dq("select * from $table where n = '$n'",1);
  $old = mysql_fetch_assoc($q);
  if (strstr($old,"#$keywords#")) $new_words = str_replace("#$keywords#","",$old)."#$keywords#";
  else $new_words = "$old#$keywords#";
  dq("update $table set words = '$new_words' where n = '$n'",0);
}
else dq("INSERT INTO $table VALUES ('$n','$s[cas]','#$keywords#')",0);
}*/

##################################################################################

function page_from_template_no_headers($t,$vl) {
global $s,$m;
if (!is_array($vl)) $vl = array();
$vl = array_merge($vl,get_common_variables());
$vl[selected_menu] = 0;
if (!$vl[meta_title]) $vl[meta_title] = $s[site_name];

$x = $s[phppath].'/styles/'.$s[LUG_style].'/templates/';
if (file_exists($x.$t)) $template = $x.$t;
if (file_exists($x.'_head1.txt')) $head1 = $x.'_head1.txt';

$x = $s[phppath].'/styles/_common/templates/';
if (!$template) $template = $x.$t;
if (!$head1) $head1 = $x.'_head1.txt';
$head1 = parse_variables_in_template($head1,$vl);
$line = implode('',file($template));

foreach ($vl as $k=>$v) $line = str_replace("#%$k%#",$v,$line);
$line = preg_replace("/#%[a-z0-9_]*%#/i",'',stripslashes($line));
$line = str_replace('--BACKSLASH--','\\',$line);
$line = preg_replace("/<\/head>/i",'<LINK href="'.$s[site_url].'/styles/'.$s[LUG_style].'/styles.css" rel="StyleSheet"></head>',$line);
echo $line;
exit;
}

##################################################################################

function get_styles_options($all,$selected) {
global $s;
if ($s[A_option]=='static') return false;
$styles = explode(',',$all);
foreach ($styles as $k=>$v)
{ if ($v==$selected) $x = ' selected'; else $x = '';
  $a .= '<option value="'.$v.'"'.$x.'>'.str_replace('_',' ',$v).'</option>';
}
return $a;
}

##################################################################################

function A_replace_dynamic_urls1($x) {
global $s;
foreach ($s[item_types_short] as $k=>$what)
{ $script = $s[item_types_scripts][$what];
  $x = str_replace("$s[site_url]/$script?action=popular",A_category_url($what,'popular','','',1),$x);
  $x = str_replace("$s[site_url]/$script?php?action=new",A_category_url($what,'new','','',1),$x);
  $x = str_replace("$s[site_url]/$script?php?action=pick",A_category_url($what,'pick','','',1),$x);
  $x = str_replace("$s[site_url]/$script?php?action=top_rated",A_category_url($what,'toprated','','',1),$x);
}
return $x;
}

##################################################################################
##################################################################################
##################################################################################

function getmicrotime() { 
list($usec,$sec) = explode(" ",microtime()); 
return ((float) $usec + (float)$sec); 
} 

##################################################################################
##################################################################################
##################################################################################

function problem($error) {
global $s;
$a[info] = info_line($error);
include_once("$s[phppath]/data/data_forms.php");
if ($s[message_to_us_captcha]) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
page_from_template('error.html',$a);
}

##################################################################################

function my_strtolower($a) {
$a = strtolower($a);
$a = strtr($a,'ABCDEFGHIJKLMNOPQRSTUVWXYZÌŠÈØŽÝÁÍÉÚAÂAÄÇÉËÍÎÓÔÖÚÜÝ','abcdefghijklmnopqrstuvwxyzìšèøžýáíéúaâaäçéëíîóôöúüý');
return $a;
}

##################################################################################
##################################################################################
##################################################################################

function try_blacklist($phrase,$what) {
global $s,$m;
$q = dq("select phrase from $s[pr]blacklist where what like '$what'",1);
while ($pole = mysql_fetch_row($q))
{ if (strstr ($phrase, $pole[0])) return "$m[black1] $pole[0] $m[black2]";}
}

##################################################################################

function try_ip_blacklist($ip) {
global $s,$m;
$q = dq("select phrase from $s[pr]blacklist where what = 'ip' AND phrase = '$ip'",1);
$x = mysql_fetch_row($q);
if ($x[0]) problem($m[ban_ip]);
}

##################################################################################
##################################################################################
##################################################################################

function get_ipnum() {
global $s;
if ($s[have_ip]) $x = explode('.',$s[have_ip]);
else $x = explode('.',trim(getenv('REMOTE_ADDR')));
//$x = explode('.','61.88.255.255'); // AU
$s[ipnum] = 16777216*$x[0] + 65536*$x[1] + 256*$x[2] + $x[3];
}

##################################################################################

function log_country() {
global $s;
if ($_SESSION[log_country]) return false;
get_ipnum();
if (!$s[ipnum]) return '';
dq("delete from $s[pr]ip_country_temp where time < ($s[cas]-900)",1);
$q = dq("select cc from $s[pr]ip_country_temp where n = '$s[ipnum]'",1);
$x = mysql_fetch_row($q); if ($x[0]) $country = $x[0];
if (!$country)
{ $q = dq("select cc from $s[pr]ip_country where start <= '$s[ipnum]' and end >= '$s[ipnum]'",1);
  $x = mysql_fetch_row($q);
  if ($x[0])
  { dq("insert into $s[pr]ip_country_temp values ('$s[ipnum]','$x[0]','$s[cas]')",1);
    $country = $x[0];
  }
}
if ($country) $q = dq("update $s[pr]countries set i = i + 1 where code = '$country'",1);
$_SESSION[log_country] = $country;
}

##################################################################################
##################################################################################
##################################################################################

function user_defined_items_display($use_for,$all_user_items_list,$all_user_items_values,$n,$template,$email,$only_with_value,$only_forms,$only_pages) {
global $s,$m;
if (($use_for=='l') OR ($use_for=='l_q') OR ($use_for=='l_w') OR ($use_for=='l_a')) $use_for1 = 'l';
elseif (($use_for=='a') OR ($use_for=='a_q') OR ($use_for=='a_w')) $use_for1 = 'a';
elseif (($use_for=='b') OR ($use_for=='b_q') OR ($use_for=='b_w')) $use_for1 = 'b';
elseif (($use_for=='n') OR ($use_for=='n_q') OR ($use_for=='n_w')) $use_for1 = 'n';
elseif (($use_for=='v') OR ($use_for=='v_q') OR ($use_for=='v_w')) $use_for1 = 'v';
elseif (strstr($use_for,'c_')) $use_for1 = $use_for;
else return false;
if (!$s[filter_usit]) $only_with_value = 0;
if (is_array($n)) $numbers = $n; else $numbers[0] = $n;
if (!$all_user_items_list)
{ $q = dq("select * from $s[pr]usit_list where use_for = '$use_for1' order by rank",1);
  while ($x = mysql_fetch_assoc($q)) $all_user_items_list[] = $x;
}
if (!$all_user_items_values) $all_user_items_values = get_all_user_items_values($use_for1);

$query = 'AND '.my_implode('n','OR',$numbers);
$q = dq("select * from $s[pr]usit_values where use_for = '$use_for' $query",1);
while ($x = mysql_fetch_assoc($q))
{ $b[$x[n]][$x[item_n]][code] = $x[value_code];
  $b[$x[n]][$x[item_n]][text] = $x[value_text];
}
foreach ($numbers as $k1=>$v1)
{ foreach ($all_user_items_list as $k=>$v)
  { $filter_now = $only_with_value;
	if ($v[kind]=='checkbox')
    { if ($only_forms) { if ($b[$v1][$v[item_n]][code]) $data[value] = $m[yes]; else $data[value] = $m[no]; }
      else
	  { if ($b[$v1][$v[item_n]][code]) $data[value] = $v[description]; else $data[value] = '';
        $v[description] = '';
        $filter_now = 1;
      }
    }
    else $data[value] = $b[$v1][$v[item_n]][text];
    if ((!$data[value]) AND (!is_numeric($data[value]))) { if ($filter_now) continue; else $data[value] = $m[na]; }
    $data[name] = $v[description];
    if ($email)
    { $a[$v1] .= parse_part($template,$data,1);
      $a['individual_'.$v1]['user_item_'.$v[item_n]] = parse_part($template,$data,1);
    }
    else
    { if (($s[search_highlight]) AND ($s[highlight])) $data[value] = highlight_words('',$data[value]);
      if ($s[highlight_usit][$v[item_n]]) $data[value] = highlight_words($s[highlight_usit][$v[item_n]],$data[value]);
      $c[$v[item_n]][$v1] = parse_part($template,$data);
      $a['individual_'.$v1]['user_item_'.$v[item_n]] = parse_part($template,$data);
      $a['individual_'.$v1]['user_item_value_'.$v[item_n]] = $data[value];
      $a['individual_'.$v1]['user_item_name_'.$v[item_n]] = $data[name];
    }
  }
}
foreach ($all_user_items_list as $k=>$v)
{ if (($only_forms) AND (!$v[visible_forms])) unset($c[$v[item_n]]);
  if (($only_pages) AND (!$v[visible_pages])) unset($c[$v[item_n]]);
}
foreach ($c as $k=>$v) foreach ($v as $k1=>$v1) $a[$k1] .= $v1;
return $a;
}

##################################################################################
##################################################################################
##################################################################################

function category_pages_form($what,$category,$total,$page,$sort,$direction) {
global $s,$m;
if (!$sort) { $sort = $s[$what.'_sortby']; $direction = $s[$what.'_sortby_direct']; }
$sorts = explode(',',$s[$what.'_sort']);
$perpage = $s[$what.'_per_page'];
$a[script_name] = $s[item_types_scripts][$what];

if ($total<=1)
{ if (!$total) $total = 0;
  $a[total] = $total;
  $a[hide_pages_list_begin] = '<!--'; $a[hide_pages_list_end] = '-->';
  $a[hide_sortby_begin] = '<!--'; $a[hide_sortby_end] = '-->';
  $a[hide_button_begin] = '<!--'; $a[hide_button_end] = '-->';
  return parse_part('pages_form.txt',$a);
}
$pages = ceil($total/$perpage); 
if ($pages>1)
{ for ($x=1;$x<=$pages;$x++)
  { if ($x==$page) $selected = ' selected'; else $selected = '';
    $a[pages] .= '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
  }
  $a[pages_list] = $a[pages];
}
else { $a[hide_pages_list_begin] = '<!--'; $a[hide_pages_list_end] = '-->'; }

foreach ($sorts as $k=>$v)
{ if ($sort==$v) $selected = ' selected'; else $selected = '';
  $a[sortby_options] .= '<option value="'.$v.'"'.$selected.'>'.$m[$v].'</option>';
}

$a[hidden_fields] = '<input type="hidden" name="n" value="'.$category.'">';
$a[$direction.'_selected'] = ' selected';
$a[total] = $total;
return parse_part('pages_form.txt',$a);
}

##################################################################################

function category_pages_list($what,$category,$total,$page,$rewrite_url,$sort,$direction) {
global $s,$m;
if ($total<=1)
{ if (!$total) $total = 0;
  $a[total] = $total;
  $a[hide_pages_list_begin] = '<!--'; $a[hide_pages_list_end] = '-->';
  $a[hide_sortby_begin] = '<!--'; $a[hide_sortby_end] = '-->';
  return parse_part('pages_list.txt',$a);
}
$a[pages_list] = category_pages_list_numbers($what,$category,'','',$total,$page,$rewrite_url,$sort,$direction);
if (!$a[pages_list]) { $a[hide_pages_list_begin] = '<!--'; $a[hide_pages_list_end] = '-->'; }

if (!$sort) $sort = $s[$what.'_sortby'];
$sorts = explode(',',$s[$what.'_sort']);
foreach ($sorts as $k=>$v)
{ if ($sort==$v) $sort_options[] = "$m[$v]";
  elseif ($s[category_use_ajax]) $sort_options[] = "<a href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$v&direction=$direction&page=1','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$v&direction=$direction&page=1&total=$total&rewrite=$rewrite_url','pages_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$v&direction=$direction&page=1&total=$total&rewrite=$rewrite_url','pages_div_box1');\">$m[$v]</a>";
  else $sort_options[] = '<a href="'.category_url($what,$category,0,'',1,'','',$v,$direction).'">'.$m[$v].'</a>';
}
$a[sortby_options] = implode(' ',$sort_options);
$a[total] = $total;

if ($s[category_use_ajax])
{ $a[link_asc] = "href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$sort&direction=asc&page=1','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$sort&direction=asc&page=1&total=$total&rewrite=$rewrite_url','pages_div_box');parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$sort&direction=asc&page=1&total=$total&rewrite=$rewrite_url','pages_div_box1');\"";
  $a[link_desc] = "href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$sort&direction=desc&page=1','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$sort&direction=desc&page=1&total=$total&rewrite=$rewrite_url','pages_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$sort&direction=desc&page=1&total=$total&rewrite=$rewrite_url','pages_div_box1');\"";
}
else
{ $a[link_asc] = 'href="'.category_url($what,$category,0,'',1,'','',$sort,'asc').'"';
  $a[link_desc] = 'href="'.category_url($what,$category,0,'',1,'','',$sort,'desc').'"';
}
return parse_part('pages_list.txt',$a);
}

##################################################################################
##################################################################################
##################################################################################

function find_order_by($what,$sort_by,$direction) {
global $s;
$y = explode(',',$s[$what.'_sort']); foreach ($y as $k => $v) $allowed_sortby[] = $v;
$allowed_sortby[] = 'pick';
foreach ($allowed_sortby as $k=>$v) if (!$v) unset($allowed_sortby[$k]);
if(in_array($sort_by,$allowed_sortby))
{ if ($direction=='desc') $a = "$sort_by desc";
  else $a = $sort_by;
  $s[this_sort] = $sort_by;
}
else { $a = $s[$what.'_sortby'].' '.$s[$what.'_sortby_direct']; $s[this_sort] = $s[$what.'_sortby']; }
if ($what=='l') { if ($s[sort_pick]) return "pick desc,sponsored desc,dynamic_price desc,$a"; return "sponsored desc,dynamic_price desc,$a"; }
if ($s[$what.'_sort_pick']) $a = "pick desc,$a";
return $a;
}

##################################################################################
##################################################################################
##################################################################################

function check_admin_rights($action) {
global $s;
if (($_SESSION[LUG_admin_user]) AND ($_SESSION[LUG_admin_password]))
{ $username = $_SESSION[LUG_admin_user]; $password = $_SESSION[LUG_admin_password]; }
else { $username = $_COOKIE[LUG_admin_user]; $password = $_COOKIE[LUG_admin_password]; }
$username = str_replace("'",'',$username); $password = str_replace("'",'',$password);
if ($action) $q = dq("select count(*) from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$username' and $s[pr]admins.password = '$password' and $s[pr]admins_rights.n = $s[pr]admins.n and $s[pr]admins_rights.action = '$action'",1);
$data = mysql_fetch_row($q);
if ($data[0]) return 1;
return 0;
}

###################################################################################
###################################################################################
###################################################################################

function select_days($a) {
global $s;
if ($a==0) $y = ' selected'; else $y = '';
$b .= '<option value="0"'.$y.'>N/A</option>';
for ($x=1;$x<=31;$x++)
{ if ($x==$a) $y = ' selected'; else $y = '';
  $b .= '<option value="'.$x.'"'.$y.'>'.$x.'</option>';
}
return $b;
}

##################################################################################

function select_months($a) {
global $s;
if ($a==0) $y = ' selected'; else $y = '';
$b .= '<option value="0"'.$y.'>N/A</option>';
for ($x=1;$x<=12;$x++)
{ if ($x==$a) $y = ' selected'; else $y = '';
  $b .= '<option value="'.$x.'"'.$y.'>'.$x.'</option>';
}
return $b;
}

##################################################################################

function select_years($a) {
global $s;
if (!$a) $y = ' selected'; else $y = '';
$b .= '<option value="0"'.$y.'>N/A</option>';
for ($x=2004;$x<=2035;$x++)
{ if ($x==$a) $y = ' selected'; else $y = '';
  $b .= '<option value="'.$x.'"'.$y.'>'.$x.'</option>';
}
return $b;
}

##################################################################################

function submitted_show_dates($what,$t1,$t2) {
global $s,$m;
$allowed = $s[$what.'_v_start_end'];
if ((!$allowed) OR ((!$t1) AND (!$t2))) return false;
if ($t1) $x[date_1] = datum($t1,0); else $x[date_1] = $m[na];
if ($t2) $x[date_2] = datum($t2,0); else $x[date_2] = $m[na];
return parse_part('form_submitted_dates.txt',$x);
}

##################################################################################
##################################################################################
##################################################################################

function categories_selected($what,$vybrana,$incl_invisible,$incl_disabled_submissions,$incl_aliases,$no_info) {
global $s,$m;
if (!$incl_invisible) $where = 'AND visible = 1';
if (!$incl_disabled_submissions) $where .= ' AND submithere = 1';
if (!$incl_aliases) $where .= ' AND alias_of = 0';
if (strstr($what,'_first'))
{ $x1 = explode('_',$what); $what = $x1[0];
  $q = dq("select * from $s[pr]cats where use_for = '$what' AND level = '1' $where order by path_text",1);
  while ($a=mysql_fetch_assoc($q))
  { if (!$no_info)
    { unset($i,$info);
	  if (!$a[visible]) $i[] = $m[invisible]; if (!$a[submithere]) $i[] = $m[disabled];
      if ($i) $info = '('.implode(', ',$i).')';
    }
	if ($a[n]==$vybrana) $selected = ' selected'; else $selected = '';
    $x .= '<option value="'.$a[n].'"'.$selected.'>'.$a[name].$info.'</option>';
  }
}
else
{ if ($what=='adlink') $what1 = 'l'; else $what1 = $what;
  $q = dq("select * from $s[pr]cats where use_for = '$what1' $where order by path_text",1);
  while ($a=mysql_fetch_assoc($q))
  { if (!$no_info)
    { unset($i,$info);
	  if (!$a[visible]) $i[] = $m[invisible]; if ((!$a[submithere]) AND ($what!='adlink')) $i[] = $m[disabled];
      if ($i) $info = '('.implode(', ',$i).')';
    }
    $mo = ''; for ($i=1;$i<$a[level];$i++) $mo .= '- ';
    $a[path_text] = preg_replace("/<%.+%>/",'',$a[path_text]);
    $a[path_text] = preg_replace("/<%.+$/",$a[name],$a[path_text]);
    if ($a[alias_of]) $a[path_text] = $s[alias_pref].$a[path_text].$s[alias_after];
    $a[path_text] = stripslashes($a[path_text]);
    if ($a[n]==$vybrana) $selected = ' selected'; else $selected = '';
    $x .= "<option value=\"$a[n]\"$selected>$mo $a[path_text]$info</option>\n";
  }
}

return stripslashes($x);
}

##################################################################################
##################################################################################
##################################################################################

function check_entered_captcha($entered_code) {
global $s,$m;
include("image_control.php");
$image_control = new image_control();
$image_control->get_both_codes($entered_code);
$valid_code = $image_control->valid_code;
$entered_code = $image_control->entered_code;
if ((!trim($entered_code)) OR ($valid_code!=$entered_code)) $problem = $m[w_code1].'<br />'.$m[w_code2].' <b>'.$valid_code.'</b>'.$m[w_code3].'<b> '.$entered_code.'</b>.';
if ($problem) return $problem;
}

##################################################################################

function check_post_rights($what) {
global $s,$m;
$who = $s[$what.'_who']; $field_name = 'post_'.$s[items_types_words][$what]; $reg_only = $m['reg_only_post_'.$what]; $no_right = $m['no_right_post_'.$what];
if ($field_name=='post_articles') $field_name = 'post_art';
if (!$who) return false;
if (!$s[LUG_u_n]) problem($reg_only);
if ($who==2)
{ $user = get_user_variables($s[LUG_u_n]);
  if (!$user[$field_name]) problem($no_right);
}
/*
if ( (($_POST[action]) OR ($_GET[action])) AND (!strstr($_POST[action],'_created')) ) return false;
$table = $s[item_types_tables][$what];
$q = dq("select n from $table where owner = '$s[LUG_u_n]'",2);
if (mysql_num_rows($q)) problem($no_right);
*/
}

##################################################################################
##################################################################################
##################################################################################

function check_access_rights($what,$c,$category_vars) {
global $s,$m;
if ($s[LUG_u_n]) return true;
if (strstr($what,'c_'))
{ if ((($what=='c_l') AND ($s[users_only_links_c])) OR (($what=='c_a') AND ($s[users_only_articles_c])) OR (($what=='c_b') AND ($s[users_only_blogs_c])) OR (($what=='c_v') AND ($s[users_only_videos_c])) OR (($what=='c_n') AND ($s[users_only_news_c]))) problem($m[reg_users_only]);
  if (!$category_vars[n]) $category_vars = get_category_variables($c);
  if ($category_vars[users_only_cat]) problem($m[reg_users_only]);
  return true;
}
if ($s['users_only_'.$s[items_types_words][$what]]) problem($m[reg_users_only]);
$c = explode(' ',str_replace('_','',$c));
foreach ($c as $k=>$v) { $category_vars = get_category_variables($v); if ($category_vars[users_only_items]) problem($m[reg_users_only]); }
}

##################################################################################
##################################################################################
##################################################################################

function get_payment_price($days_or_clicks,$insert,$payment_type,$link_or_pack_n) {
global $s,$m;
if ($payment_type=='link')
{ $days = $days_or_clicks;
  $a = check_link_access_rights($link_or_pack_n,'','');
  if ((!is_numeric($days)) OR ($days<=0)) link_adv_home($link_or_pack_n);
  if (($a[t1]) OR ($a[t2])) $problem[] = $m[has_t1t2];
  $q = dq("select * from $s[pr]links_adv_prices order by days",1);
  while ($x=mysql_fetch_assoc($q)) $days_options[$x[days]] = $x[price];
  $days = round($days);
  $price = $days_options[$days];
  if (!$price) $problem[] = 'Not allowed number of days';
  $link_adv = get_link_adv_variables($link_or_pack_n);
  if (($link_adv[c_dynamic_now]>0) OR ($link_adv[c_now]>0) OR ($link_adv[i_now]>0) OR ($link_adv[d_validby]>$s[cas])) $problem[] = $m[no_ad_simple];
}
elseif ($payment_type=='package')
{ $package = $link_or_pack_n;
  $q = dq("select * from $s[pr]adv_packs where n = '$package'",1);
  $package_vars = mysql_fetch_assoc($q);
  $days_clicks_value = $package_vars[price] + (($package_vars[price]*$package_vars[bonus])/100);
  $price = $package_vars[price];
	
}
elseif ($payment_type=='adlink')
{ $clicks = $days_or_clicks;
  $adlink = get_adlink_variables($link_or_pack_n);
  $price = $adlink[price]*$clicks;
  $days_clicks_value = $clicks;
}
if ($problem[0]) $s[problem] = info_line($m[errorsfound],implode('<br />',$problem));
elseif ($insert)
{ dq("insert into $s[pr]links_extra_orders values(NULL,'$s[LUG_u_n]','$s[cas]','$price','0','$control','','','$payment_type','$link_or_pack_n','$days_clicks_value')",1);
  $order_n = mysql_insert_id();
  return array(round($price,2),$order_n);
}
return round($price,2);
}

##################################################################################

function go_to_pay($n,$price,$payment_type) {
global $s;
if ($s[LUG_u_n])
{ $query = " or user = '$s[LUG_u_n]'";
  if ($_COOKIE[LUG_u_n]) $remember_me = 1;
}
elseif (($payment_type=='package') OR ($payment_type=='adlink')) user_join();
dq("delete from $s[pr]payment_process where time < ($s[cas]-600) $query",1);
dq("insert into $s[pr]payment_process values ('$s[ip]','$n','$s[LUG_u_n]','$s[cas]','$remember_me')",1);
if (($s[pp_currency]) AND ($s[pp_email])) $a[payment_links] = get_paylink_paypal($s[LUG_u_username],$s[LUG_u_password],$n,$price);
if (($s[co_number]) AND ($s[co_secret_word])) $a[payment_links] .= get_paylink_2checkout($n,$price);
if (trim($s[other_payment_com])) $a[payment_links] .= str_replace('#%order%#',$n,str_replace('#%price%#',$price,$s[other_payment_com]));
page_from_template('link_extra_payment_page.html',$a);
}

##################################################################################

function get_paylink_paypal($username,$password,$order_n,$price) {
global $s;
if ($s[pp_test]) $data[paypal_url] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
else $data[paypal_url] = 'https://www.paypal.com/cgi-bin/webscr';
$data[pp_currency] = $s[pp_currency]; $data[pp_email] = $s[pp_email];
$data[number] = $order_n; $data[price] = round($price,2);
return parse_part('order_form_paypal.txt',$data);
}

##################################################################################

function get_paylink_2checkout($order_n,$price) {
global $s;
$data[order_id] = $order_n; $data[price] = round($price,2);
$data[user_id] = $s[co_number]; if ($s[co_test]) $data[test] = '&amp;demo=Y';
return parse_part('order_form_2checkout.txt',$data);
}

##################################################################################

function get_paylink_any($order_n,$price) {
global $s;
$data[order] = $order_n; $data[price] = round($price,2);
return parse_part('order_form_any.txt',$data);
}

##################################################################################

function auto_payment_done($n) {
global $s,$m;
$q = dq("select * from $s[pr]payment_process where ip = '$s[ip]' and time >= ($s[cas]-600) and order_n = '$n'",1);
$payment_process = mysql_fetch_assoc($q);
if ($payment_process[user]) $user = get_user_variables($payment_process[user]);
dq("delete from $s[pr]payment_process where time < ($s[cas]-600) or user = '$payment_process[user]'",1);

if ($_COOKIE[LUG_u_username]) $user = get_user_variables($_COOKIE[LUG_u_n]);
elseif ($user[n])
{ if ($payment_process[remember_me])
  { setcookie(LUG_u_username,$user[username],$s[cas]+31536000); 
    setcookie(LUG_u_password,$user[password],$s[cas]+31536000); 
    setcookie(LUG_u_name,$user[name],$s[cas]+31536000); 
    setcookie(LUG_u_email,$user[email],$s[cas]+31536000); 
    setcookie(LUG_u_n,$user[n],$s[cas]+31536000);
    setcookie(LUG_u_style,$user[style],$s[cas]+31536000);
  }
  else
  { $_SESSION[LUG_u_username] = $user[username];
    $_SESSION[LUG_u_password] = $user[password];
    $_SESSION[LUG_u_name] = $user[name];
    $_SESSION[LUG_u_email] = $user[email];
    $_SESSION[LUG_u_n] = $user[n];
    $_SESSION[LUG_u_style] = $user[style];
  }
}
else { header ("Location: $s[site_url]/"); exit; }

$s[LUG_u_username] = $user[username];
$s[LUG_u_password] = $user[password];
$s[LUG_u_n] = $user[n];
$s[LUG_u_name] = $user[name];
$s[LUG_u_email] = $user[email];
$s[LUG_u_style] = $s[LUG_style] = $user[style];
$q = dq("select * from $s[pr]links_extra_orders where user = '$s[LUG_u_n]' AND n = '$payment_process[order_n]'",1);
$order = mysql_fetch_assoc($q);
//foreach ($order as $k=>$v) echo "order $k - $v<br>";
if ($order[info]) $s[info] = info_line($order[info]);
links_list('');
}


##################################################################################

function links_list($email) {
global $s,$m;
$email = replace_once_text($email);
if ($s[LUG_u_n]) $q = dq("select * from $s[pr]links where owner = '$s[LUG_u_n]' AND (status = 'enabled' or status = 'disabled')",1);
else $q = dq("select * from $s[pr]links where email = '$email' AND (status = 'enabled' or status = 'disabled')",1);
while ($link = mysql_fetch_assoc($q))
{ $link[created] = datum($link[created],0);
  $link[item_details_url] = get_detail_page_url('l',$link[n],$link[rewrite_url],0,1);
  if ($s[LUG_u_n]) { $link[hide_send_password_begin] = '<!--'; $link[hide_send_password_end] = '-->'; }
  if ((!$s[LUG_u_n]) OR (!$s[users_can_delete_l])) { $link[hide_delete_begin] = '<!--'; $link[hide_delete_end] = '-->'; }
  $a[links] .= parse_part('links_for_owner_list.txt',$link);
}
if (!$a[links]) $a[links] = $m[no_links];
if ($s[LUG_u_n])
{ $q = dq("select * from $s[pr]links_extra_orders where user = '$s[LUG_u_n]' order by n desc",1);
  while ($invoice = mysql_fetch_assoc($q)) 
  { $invoice[order_date] = datum($invoice[order_time],0);
    if ($invoice[paid]) { $invoice[status] = $m[paid]; $invoice[payment_link] = $m[na]; }
    else { $invoice[status] = $m[unpaid]; $invoice[payment_link] = '<a href="'.$s[site_url].'/link_extra_features.php?action=invoice_pay_now&amp;n='.$invoice[n].'">'.$m[click_to_pay].'</a>'; }
    if ($invoice[payment_type]=='link') $invoice[type] = $m[Link].' #'.$invoice[link_or_pack].' - '.round($invoice[days_clicks_or_value]).' days';
    elseif ($invoice[payment_type]=='adlink') $invoice[type] = $m[AdLink].' #'.$invoice[link_or_pack].' - '.round($invoice[days_clicks_or_value]).' clicks';
    elseif ($invoice[payment_type]=='package') $invoice[type] = $m[package_funds];
    $a[invoices] .= parse_part('links_for_owner_list_invoice.txt',$invoice);
  }
  $q = dq("select * from $s[pr]adlinks where owner = '$s[LUG_u_n]' order by n desc",1);
  while ($adlink = mysql_fetch_assoc($q)) 
  { if ($adlink[enabled]) $adlink[enabled] = $m[yes]; else $adlink[enabled] = $m[no];
    if ($adlink[approved]) $adlink[approved] = $m[yes]; else $adlink[approved] = $m[no];
    $adlink[preview] = get_complete_adlink($adlink,1);
    if (!$s[users_can_delete_adlinks]) { $adlink[hide_delete_begin] = '<!--'; $adlink[hide_delete_end] = '-->'; }
    $a[adlinks] .= parse_part('links_for_owner_list_adlink.txt',$adlink);
  }
}
if (!$a[invoices]) { $a[hide_invoices_begin] = '<!--'; $a[hide_invoices_end] = '-->'; }
if (!$a[adlinks]) { $a[hide_adlinks_begin] = '<!--'; $a[hide_adlinks_end] = '-->'; }
$a[info] = $s[info];
page_from_template('links_for_owner_list.html',$a);
}

##################################################################################

function order_email_admin($order_n,$price,$payment_type,$item_n) {
global $s,$m;
if ($s[LUG_u_n]) { $x = get_user_variables($s[LUG_u_n]); $a[usernumber] = $s[LUG_u_n]; $a[username] = $s[LUG_u_username]; $a[email] = $x[email]; }
else { $x = get_item_variables('l',$item_n); $a[username] = $a[usernumber] = 'N/A'; $a[email] = $x[email]; }
$a[price] = $price; $a[order_n] = $order_n;
if ($payment_type=='link') $a[item_info] = "Link #$item_n"; elseif ($payment_type=='adlink') $a[item_info] = "AdLink #$item_n"; else $a[item_info] = "Package #$item_n";
mail_from_template('link_extra_order_placed.txt',$a);
}

##################################################################################
##################################################################################
##################################################################################

function parsejava($template,$vl) {
global $s,$m;
$vl = array_merge($vl,get_common_variables());
$template = template_select($template);
$fh = fopen($template,'r') or problem ("Unable to read template $template");
while (!feof($fh))
{ $line = trim(fgets($fh,4096));
  $line = unreplace_once_html($line);
  $line = str_replace('"','\"',$line);
  $lines .= "document.write(\"$line\");\n";
}
fclose ($fh);
while (list($key,$val) = each($vl)) $lines = str_replace("#%$key%#",$val,$lines);
reset ($vl);
$lines = preg_replace("/#%[a-z0-9_]*%#/i",'',$lines);
echo "\n$lines";
}

##################################################################################
##################################################################################
##################################################################################

?>