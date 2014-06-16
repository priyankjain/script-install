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

include('./common.php');

//echo "(($s[detect_language]) AND (!$s[LUG_u_n]) AND (!$_SESSION[LUG_style]))";
if (($s[detect_language]) AND (!$s[LUG_u_n]) AND (!$_SESSION[LUG_style]))
{ include("$s[phppath]/language_detection.php");
  $language = get_languages('data');
  //foreach ($language[0] as $k=>$v) echo "$k - $v<br>";
  for ($x=1;$x<=25;$x++) if (($s["language$x"]==$language[0][0]) OR ($s["language$x"]==$language[0][1])) $s[LUG_style] = $_SESSION[LUG_style] = $s["language_style$x"];
}
get_messages('index.php');
set_time_limit(600);
//if (!$_SESSION[log_country]) $_SESSION[log_country] = 'GB';

if ($s[rebuild_auto])
{ include('./administration/rebuild_functions.php');
  load_times();
  if (($s[times_d]+86400) < $s[cas]) daily_job(0);
  if ((($s[times_m]+2592000)<$s[cas]) AND (date('j',$s[cas])==1)) reset_month(0);
}

$q = dq("select * from $s[pr]static where (style = '$s[LUG_style]' or style = '0') and page = 'home'",1);
while ($x = mysql_fetch_assoc($q)) $a[$x[what].'_'.$x[mark]] = $x[html];

foreach ($s[item_types_short] as $k=>$what)
{ $a["s_categories_$what"] = $s["s_categories_$what"];
  $a["s_hits_$what"] = $s["s_hits_$what"];
  $a["s_hits_m_$what"] = $s["s_hits_m_$what"];
  $a["s_rating_$what"] = $s["s_rating_$what"];
}
for ($x=1;$x<=5;$x++) $a["icon_folder_t$x"] = $s["icon_folder_t$x"];
$a[categories_colspan] = $s[ind_column];
$a[site_news] = index_site_news(0); if (!$a[site_news]) { $a[hide_site_news_begin] = '<!--'; $a[hide_site_news_end] = '-->'; }
if ($s[rss_home_page_url]) $a[rss_content] = show_rss_content('c',0,$s[rss_home_page_url],$s[rss_home_page_items]);
if (!trim($a[rss_content])) { $a[hide_rss_content_begin] = '<!--'; $a[hide_rss_content_end] = '-->'; }
$a[hide_home_begin] = '<!--'; $a[hide_home_end] = '-->';
$a = array_merge((array)$m,(array)$a);

$a[this_url] = "$s[site_url]/";
$a[title] = $s[site_name];
$a[share_it] = parse_part('share_it.txt',$a);

if ($_SESSION[log_country])
{ $q = dq("select flag,name from $s[pr]countries where code = '$_SESSION[log_country]'",1);
  $x = mysql_fetch_assoc($q);
  if ($x[name]) { $a[this_country] = $x[name]; $a[this_flag] = "$s[site_url]/images/flags/small/$x[flag]"; $a[this_flag_large] = "$s[site_url]/images/flags/large/$x[flag]"; }
}
if (!$a[this_country]) { $a[hide_country_begin] = '<!--'; $a[hide_country_end] = '-->'; }

foreach ($s[items_types_words] as $what=>$word) if ($s["section_$what"]) $a[search_options] .= '<option value="'.$what.'_0">'.$m[$word].'</option>';

if ($s[visit]) { $a[show_simple] = 'none'; $a[show_complete] = 'block'; }
else { $a[show_complete] = 'none'; $a[show_simple] = 'block'; }
$a[home_link] = "javascript:show_top_submenu('1');";
page_from_template('index.html',$a);

#############################################################################

?>