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
get_messages('rss.php');
$s[time_plus] = $s[cas]-gmmktime();
if ((!$_GET[action]) AND (is_numeric($_GET[c]))) { $c = get_category_variables($_GET[c]); $_GET[action] = $c[use_for]; }

switch ($_GET[action]) {
case 'links_new'		: show_items('l','new');
case 'links_popular'	: show_items('l','popular');
case 'links_pick'		: show_items('l','pick');
case 'links'			: show_items('l',$_GET[c]);
case 'l'				: show_items('l',$_GET[c]);
case 'articles_new'		: show_items('a','new');
case 'articles_popular'	: show_items('a','popular');
case 'articles_pick'		: show_items('a','pick');
case 'articles'			: show_items('a',$_GET[c]);
case 'a'				: show_items('a',$_GET[c]);
case 'blogs_new'		: show_items('b','new');
case 'blogs_popular'	: show_items('b','popular');
case 'blogs_pick'		: show_items('b','pick');
case 'blogs'			: show_items('b',$_GET[c]);
case 'b'				: show_items('b',$_GET[c]);
case 'news_new'			: show_items('n','new');
case 'news_popular'		: show_items('n','popular');
case 'news_pick'		: show_items('n','pick');
case 'news'				: show_items('n',$_GET[c]);
case 'n'				: show_items('n',$_GET[c]);
case 'videos_new'		: show_items('v','new');
case 'videos_popular'	: show_items('v','popular');
case 'videos_pick'		: show_items('v','pick');
case 'videos'			: show_items('v',$_GET[c]);
case 'v'				: show_items('v',$_GET[c]);
}

##################################################################################
##################################################################################
##################################################################################

function show_items($what,$what_show) {
global $s,$m;
$table = $s[item_types_tables][$what];
if (is_numeric($what_show))
{ $w = get_where_fixed_part('',$what_show,'',$s[cas]).' order by created desc'; 
  $q = dq("select name from $s[pr]cats where n = '$what_show'",1);
  $x = mysql_fetch_row($q);
  $title = str_replace('&','&amp;',$m[$what.'_category'].' '.$x[0]);
}
elseif ($what_show=='new')
{ $query = get_new_items($what,$s[$what.'_rss_per_page']);
  if (!$query) $query = "n = '0'";
  $q = dq("select * from $table where $query",1);
  while ($x = mysql_fetch_assoc($q))
  { if ($x[created]>$x[t1]) $items["$x[created]-$x[n]"] = $x; else $items["$x[t1]-$x[n]"] = $x;$numbers[] = $x[n]; }
  //$usit = user_defined_items_display($what,$all_user_items_list,$all_user_items_values,$numbers,'user_item_listing.txt',0,1,0,1);
  ksort($items); $items = array_reverse($items);
  list($images,$files) = pictures_files_display_public($what,$numbers,0);
  foreach ($items as $k=>$item)
  { //foreach ($usit['individual_'.$item[n]] as $k1=>$v1) $item[$k1] = $v1;
    $item[created] = gmdate('D, j M Y H:i:s',$item[created]+$s[time_plus]);
	$item[url] = get_detail_page_url($what,$item[n],$item[rewrite_url],0,1);
	if (!$item[description]) $item[description] = strip_tags($item[text]);
	unset($item[image]);
	foreach ($images[$item[n]] as $k1=>$v1)
    { if (!trim($v1[url])) continue;
	  $item[image] = $v1[url];
      break;
    }
    if ($item[youtube_thumbnail]) $item[description] = "<![CDATA[ <img src=\"$item[youtube_thumbnail]\" />  $item[description] ]]>";
    elseif ($item[image]) $item[description] = "<![CDATA[ <img src=\"$item[image]\" />  $item[description] ]]>";
    foreach ($item as $k1=>$v1) $item[$k1] = str_replace('&','&amp;',unreplace_once_html($v1));
    $a[individual_items] .= parse_part('rss_output.txt',$item);
  }
  $title = $m[$what.'_new'];
}
elseif ($what_show=='popular') { $w = get_where_fixed_part('',0,'',$s[cas]).' order by hits_m desc'; $title = $m[$what.'_popular']; }
elseif ($what_show=='pick') { $w = get_where_fixed_part('',0,'',$s[cas]).' order by pick desc'; $title = $m[$what.'_picks']; }
else exit;

if (!$a[individual_items])
{ $q = dq("select * from $table where $w limit ".$s[$what.'_rss_per_page'],0);
  while ($x = mysql_fetch_assoc($q)) { $items[] = $x; $numbers[] = $x[n]; }
  //$usit = user_defined_items_display($what,$all_user_items_list,$all_user_items_values,$numbers,'user_item_listing.txt',0,1,0,1);
  list($images,$files) = pictures_files_display_public($what,$numbers,0);
  foreach ($items as $k=>$item)
  { //foreach ($usit['individual_'.$item[n]] as $k1=>$v1) $item[$k1] = $v1;
    $item[created] = gmdate('D, j M Y H:i:s',$item[created]+$s[time_plus]);
	$item[url] = get_detail_page_url($what,$item[n],$item[rewrite_url],0,1);
	if (!$item[description]) $item[description] = strip_tags($item[text]);
	unset($item[image]);
	foreach ($images[$item[n]] as $k1=>$v1)
    { if (!trim($v1[url])) continue;
	  $item[image] = $v1[url];
      break;
    }
    if ($item[youtube_thumbnail]) $item[description] = "<![CDATA[ <img src=\"$item[youtube_thumbnail]\" />  $item[description] ]]>";
    elseif ($item[image]) $item[description] = "<![CDATA[ <img src=\"$item[image]\" />  $item[description] ]]>";
    foreach ($item as $k=>$v) $item[$k] = str_replace('&','&amp;',unreplace_once_html($item[$k]));
    $a[individual_items] .= parse_part('rss_output.txt',$item);
  }
}
$a[title] = $title;
header('Content-type: text/xml');
echo stripslashes(parse_part('rss_output.html',$a));
exit;
}

##################################################################################
##################################################################################
##################################################################################

?>