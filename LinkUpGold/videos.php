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
$s[selected_menu] = 3;
get_messages('videos.php');
if (!$s[v_per_page]) $s[v_per_page] = 25;
if (!$s[v_new_page]) $s[v_new_page] = 25;
$s[SCRname] = 'videos.php';
$_GET = replace_array_text($_GET);

if ($s[A_option]=='rewrite')
{ if ($_GET[vars]) { $s[table] = "$s[pr]videos"; $_GET = rewrite_category('v',$_GET[vars]); }
  elseif (is_numeric($_GET[n])) $url = rewrite_category_url('v',$_GET[n],$_GET[page],'',$_GET[sort],$_GET[direction]);
  elseif (($_GET[action]=='new') OR ($_GET[action]=='popular') OR ($_GET[action]=='top_rated') OR ($_GET[action]=='pick')) $url = rewrite_special_category_url('v',$_GET[action],1);
  else $url = '';
  if ($url) { header("HTTP/1.1 301 Moved Permanently"); header ("Location: $url"); exit; }
}
elseif ($s[A_option]=='static')
{ if ($_GET[action]) $url = A_category_url('v',$_GET[action],'','',1);
  else $url = A_category_url('v',$_GET[n],'','',1);
  header("HTTP/1.1 301 Moved Permanently"); header ("Location: $url"); exit;
}

switch ($_GET[action]) {
case 'new'			: new_videos();
case 'popular'		: popular_videos();
case 'top_rated'	: top_rated();
case 'pick'			: editor_picks();
}

#############################################################################
#############################################################################
#############################################################################

if (!is_numeric($_GET[n])) exit;
$data = replace_array_text($_GET);
$sortby = find_order_by('v',$_GET[sort],$_GET[direction]);
if (!$data[n]) problem($m[cat_no_exists]);

$a = get_category_variables($data[n]);
if ($a[alias_of]) $a = get_category_variables($a[alias_of]);
check_access_rights('c_v',$a[n],$a);
if (!$a[name]) problem ($m[cat_no_exists]);
if (!$a[tmpl_cat]) $a[tmpl_cat] = 'category.html';
if (!$a[tmpl_one]) $a[tmpl_one] = 'video_a.txt';
if ($a[image1]) $a[image] = '<img border="0" src="'.$a[image1].'" alt="'.$a[name].'">';
else { $a[image] = '';$a[hide_image_begin] = '<!--'; $a[hide_image_end] = '-->'; }

$a[similar] = get_more_categories('similar','v',$a); if (!$a[similar]) { $a[hide_similar_begin] = '<!--'; $a[hide_similar_end] = '-->'; }
if (!$a[description]) { $a[hide_description_begin] = '<!--'; $a[hide_description_end] = '-->'; }
$a[arrow] = category_get_arrow('v',$a[level],$a[parent]);
$a[subcategories] = get_more_categories('subcategories','v',$a[n],$_GET[sort],$_GET[direction]);
if (!$a[subcategories]) { $a[hide_subcategories_begin] = '<!--'; $a[hide_subcategories_end] = '-->'; }
$x = preparse_ads_in_category($a); $a = array_merge((array)$a,(array)$x);

if (($a[rss_read_interval]) AND ($a[last_import]<($s[cas]-($a[rss_read_interval]*60)))) youtube_import($a);

if ($a[rss_url]) $a[rss_content] = show_rss_content('c',$a[n],$a[rss_url],$a[rss_items]);
if (!trim($a[rss_content])) { $a[hide_rss_content_begin] = '<!--'; $a[hide_rss_content_end] = '-->'; }
$a[rss_videos_category_url] = "$s[site_url]/rss.php?c=$a[n]";
$a[adlinks] = get_adlinks($a[n],'');
$s[search_display] = 'v';
if (($a[latitude]!=0.0000000) AND ($a[longitude]!=0.0000000)) $a[div_display_map] = 'block'; 
else $a[div_display_map] = 'none'; 
if ($s[suggest_category]) 
{ $a[div_display_suggest] = 'block';
  $a[category_suggest_box] = suggest_category_box($a[n],'');
}
else $a[div_display_suggest] = 'none';

$usit = user_defined_items_display('c_v',$all_user_items_list,$all_user_items_values,$a[n],'user_item_listing.txt',0,1,0,1);
$a[user_defined] = $usit[$a[n]];
foreach ($usit['individual_'.$a[n]] as $k1=>$v1) $a[$k1] = $v1;
if (!$a[user_defined]) { $a[hide_user_defined_begin] = '<!--'; $a[hide_user_defined_end] = '-->'; }
/*if ($a[submithere]) { $a[item_submit_url] = $s[site_url].'/video_create.php?c='.$a[n]; $a[item_submit_text] = 'Submit a video to this category'; }
else {*/ $a[hide_submit_here_begin] = '<!--'; $a[hide_submit_here_end] = '-->'; //}
if (!$data[page]) { $from = 0; $data[page] = 1; } else $from = $s[v_per_page] * ($data[page]-1); 

$where = 'where '.get_where_fixed_part('v',$a[n],'',$s[cas]);

//
//echo "select count(*) from $s[pr]videos,$s[pr]cats_items $where";
$q = dq("select count(*) from $s[pr]videos,$s[pr]cats_items $where",1);
$total = mysql_fetch_row($q); $a[total] = $total[0];
$a[items_in_category] = $m[videos_in_cat];

$q = dq("select * from $s[pr]videos,$s[pr]cats_items $where order by $sortby limit $from,$s[v_per_page]",1);
while ($x = mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }

if ($numbers)
{ foreach ($item as $k => $d) $item[$k][category] = $a[n];
  $a[items] = get_complete_videos($item,$numbers,$a[tmpl_one]);
}
else $a[items] = '';

if ($s[drop_down]) $a[pages] = category_pages_form('v',$a[n],$a[total],$data[page],$_GET[sort],$_GET[direction]);
else $a[pages] = category_pages_list('v',$a[n],$a[total],$data[page],$a[rewrite_url],$_GET[sort],$_GET[direction]);
$a[pages1] = $s[pages_list_numbers];
if ($s[LUG_u_n])
{ $bookmarks = get_favorites_status('c_v',$a[n]);
  $a[add_delete_favorites] = get_favorite_line('c_v',$a[n],$bookmarks[$a[n]]);
  $notes = get_private_notes_for_items('c',$a[n]);
  if ($notes[$a[n]]) { $a[notes] = $notes[$a[n]]; $a[notes_style_display] = 'block'; } else $a[notes_style_display] = 'none';
  $s[current_notes] = $a[notes]; $a[notes_edit_box] = notes_edit_box('c',$a[n],'');
  if (!$a[notes]) { $a[hide_notes_begin] = '<!--'; $a[hide_notes_end] = '-->'; }
}
if (check_admin_rights('categories_videos')) $a[edit_link] = '<tr><td align="center"><a target="_blank" href="'.$s[site_url].'/administration/categories.php?action=category_edit&what=v&&n='.$a[n].'">Edit this category</a></td></tr>';


$a[this_url] = category_url($a[use_for],$a[n],$a[alias_of],$a[name],1,$a[pagename],$a[rewrite_url],'','');
$a[current_title] = $a[title] = $a[name];
$a[share_it] = parse_part('share_it.txt',$a);
$a[meta_title] = $a[name];
$a[meta_description] = $a[m_desc];
$a[meta_keywords] = $a[m_keyword];
$a[items_title] = $m[$s[items_types_words][v]];
page_from_template($a[tmpl_cat],$a);

#############################################################################
#############################################################################
#############################################################################

function top_rated() {
global $s,$m;
$where = get_where_fixed_part('',0,'',$s[cas]);
$q = dq("select * from $s[pr]videos where $where order by rating desc,votes desc limit $s[v_new_page]",0);
while ($x = mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
if ($numbers) $a[items] = get_complete_videos($item,$numbers,'video_a.txt');
$a[title] = $a[meta_title] = $m[top_rated_videos];
page_from_template('category_special.html',$a);
}

#############################################################################

function new_videos() {
global $s,$m;
if ($query = get_new_items('v',$s[v_new_page]))
{ $q = dq("select * from $s[pr]videos where $query",1);
  while ($x = mysql_fetch_assoc($q))
  { if ($x[created]>$x[t1]) $videos["$x[created]-$x[n]"] = $x;
    else $videos["$x[t1]-$x[n]"] = $x;
    $numbers[] = $x[n];
  }
  ksort($videos); $videos = array_reverse($videos);
  $a[items] = get_complete_videos($videos,$numbers,'video_a.txt');
}
$a[title] = $a[meta_title] = $m[new_videos];
page_from_template('category_special.html',$a);
}

#############################################################################

function popular_videos() {
global $s,$m;
$where = get_where_fixed_part('',0,'',$s[cas]);
$q = dq("select * from $s[pr]videos where $where order by hits_m desc limit $s[v_new_page]",1);
while ($x = mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
if ($item) $a[items] = get_complete_videos($item,$numbers,'video_a.txt');
$a[title] = $a[meta_title] = $m[popular_videos];
page_from_template('category_special.html',$a);
}

#############################################################################

function editor_picks() {
global $s,$m;
$where = get_where_fixed_part('',0,'',$s[cas]);
$q = dq("select * from $s[pr]videos where $where and pick > 0 order by pick desc limit $s[v_new_page]",0);
while ($x = mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
if ($item) $a[items] = get_complete_videos($item,$numbers,'video_a.txt');
$a[title] = $a[meta_title] = $m[pick_videos];
page_from_template('category_special.html',$a);
}

#############################################################################
#############################################################################
#############################################################################

?>