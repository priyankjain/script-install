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
$s[selected_menu] = 5;

//$s[short_words] = array('and','AND','or','OR');
//$s[search_words] = 0;
$s[allow_negative] = 1;
//$s[search_use_like] = 1;

get_messages('search.php');

//if ($_GET[action]=='xml') xml();

if ($_GET[bigboss]=='google')
{ $_GET[selected_google] = ' selected';
  $_GET[original_phrase] = $_GET[phrase];
  $_GET[meta_title] = $_GET[phrase];
  $_GET[meta_description] = "$_GET[phrase], $s[site_name]";
  $a = replace_array_text($_GET);
  $s[search_display] = 'google';
  if (strstr($s[site_url],'localhost')) $xml = 'http://www.scripts-demo.com/linkupgold/google_search.xml'; else $xml = "$s[site_url]/google_search.xml";
  // to connect your adsense account to the web search replace the partner id in the line below and use this line to replace the line below it
  //header("Location: $s[site_url]/search.php?cx=partner-pub-3331182269595349:nzvbi0vuf05&cof=FORID:10&ie=$s[charset]&sa=Search&q=$_GET[phrase]");
  header("Location: $s[site_url]/search.php?cref=".urlencode($xml)."&cof=FORID:10&ie=$s[charset]&sa=Search&q=$_GET[phrase]");
  exit;
}
elseif ((($_GET[cref]) OR ($_GET[cx])) AND ($_GET[q]))
{ $_GET[selected_google] = ' selected';
  $_GET[original_phrase] = $_GET[q];
  $_GET[meta_title] = $_GET[q];
  $_GET[meta_description] = "$_GET[q], $s[site_name]";
  $a = replace_array_text($_GET);
  $s[search_display] = 'google';
  page_from_template('search_result_google.html',$a);
}


//foreach ($_GET as $k=>$v) echo "$k - $v<br>";

unset($_GET[cref],$_GET[cof],$_GET[q]);
if (($s[A_option]=='rewrite') AND ($_GET))
{ if ($_GET[bigboss]=='google')
  { //$x = explode('/',$_GET[vars]);
    //$_GET[action] = 'shared_simple';
    //$_GET[phrase] = $x[0];
    //if ($x[1]) { if (($x[1]=='and') OR ($x[1]=='or') OR ($x[1]=='phrase')) $_GET[search_kind] = $x[1]; else $_GET[bigboss] = $x[1]; }
    //if ($x[2]) $_GET[bigboss] = $x[2];
    //else
    { $url = "$s[site_url]/search.php?cx=$s[google_search_id]%3A_ixry4kj8r4&cof=FORID%3A10&q=$_GET[phrase]";
      header("HTTP/1.1 301 Moved Permanently"); header ("Location: $url"); exit;
    }
    //page_from_template('search_results.html',$_GET)
  }
  if ($_GET[vars])
  { $x = explode('/',$_GET[vars]);//foreach ($x as $k=>$v) echo "$k - $v<br>";exit;
    $_GET[action] = 'shared_simple';
    $_GET[phrase] = $x[0];
    if ($x[1]) { if (($x[1]=='and') OR ($x[1]=='or') OR ($x[1]=='phrase')) $_GET[search_kind] = $x[1]; else $_GET[bigboss] = $x[1]; }
    if ($x[2]) $_GET[bigboss] = $x[2];
  }
  elseif ($_GET[rss])
  { $_GET[action] = 'rss';
    $_GET[phrase] = str_replace('/','',$_GET[rss]);
  }
  else
  { if ((!$_GET[action]) OR ($_GET[action]=='shared_simple'))
    { if (!trim($_GET[phrase])) { $url = "$s[site_url]/"; header ("Location: $url"); exit; }
	  else $url = "$s[site_url]/search/".urlencode($_GET[phrase])."/";
      if ($_GET[bigboss]) $url .= "$_GET[bigboss]/";
      if (($_GET[search_kind]=='or') OR ($_GET[search_kind]=='phrase')) $url .= "$_GET[search_kind]/";
    }
    if ($url) { header("HTTP/1.1 301 Moved Permanently"); header ("Location: $url"); exit; }
  }
}

$_GET = replace_array_text($_GET);
$_GET[phrase] = replace_array_text($_GET[phrase]);
if (!$_GET[search_kind]) $_GET[search_kind] = 'and';

switch ($_GET[action]) {
case 'shared_simple'			: shared_simple($_GET);
case 'rss'						: shared_simple($_GET);
case 'articles_simple'			: shared_simple1('a',$_GET);
case 'articles_advanced'		: shared_advanced1('a',$_GET);
case 'blogs_simple'				: shared_simple1('b',$_GET);
case 'blogs_advanced'			: shared_advanced1('b',$_GET);
case 'links_simple'				: shared_simple1('l',$_GET);
case 'links_advanced'			: shared_advanced1('l',$_GET);
case 'videos_simple'			: shared_simple1('v',$_GET);
case 'videos_advanced'			: shared_advanced1('v',$_GET);
case 'news_simple'				: shared_simple1('n',$_GET);
case 'news_advanced'			: shared_advanced1('n',$_GET);
}
if ($_GET[phrase]) search_all($_GET[phrase],'and');
search_form();

#############################################################################
#############################################################################
#############################################################################

function shared_simple($in) {
global $s;
$s[search_display] = 'all';
if (!$in[bigboss]) search_all($in[phrase],$in[search_kind]);
$x = explode('_',$in[bigboss]); $in[bigboss] = $x[1];
if ($x[0]=='l') shared_simple1('l',$in);
elseif ($x[0]=='a') shared_simple1('a',$in);
elseif ($x[0]=='b') shared_simple1('b',$in);
elseif ($x[0]=='v') shared_simple1('v',$in);
elseif ($x[0]=='n') shared_simple1('n',$in);
}

#############################################################################
#############################################################################
#############################################################################

function shared_simple1($what,$in) {
global $s;
if (!$s[search_display]) $s[search_display] = $what;
if (($what=='l') AND ($in[refer])) count_click('in','l',$in[refer]);
$in[phrase] = delete_special_chars($in[phrase]);
if ($in[search_kind]=='phrase') { $phrases[0] = $in[phrase]; $boolean = 'and'; }
else
{ $phrases = delete_some_words($in[phrase]);
  if ($in[search_kind]=='or') $boolean = 'or'; else $boolean = 'and';
}
if (!$phrases[0]) search_form($what);
if (!$s[items_types_words][$what]) exit;
shared_simple2($what,$s[items_types_words][$what].'_simple',$in[phrase],$phrases,$boolean,$in[search_kind],0,$in[bigboss],$in[page],0,$in[sort],$in[direction],$in[nolog]);
}

#############################################################################

function shared_simple2($what,$action,$original_phrase,$phrases,$boolean,$search_kind,$category,$bigboss,$page,$perpage,$sort_by,$sortdirection,$nolog) {
global $s,$m;

if ($s[search_highlight]) { $s[highlight] = $phrases; foreach ($usit as $k=>$v) if ($v) $s[highlight_usit][$k] = $v; }
update_search_log($phrases,$what,$nolog);
$a[searched] = get_info_what_searched($what,$phrases,$boolean,$category,$bigboss);
$standard_where = get_where_fixed_part($what,$category,$bigboss,$s[cas]);
if (!$page) $page = 1;
if ((!is_numeric($perpage)) OR ($perpage>50) OR ($perpage<=0)) $perpage = $s[$what.'_per_page'];
$from = ($page-1)*$perpage;
$order_by = find_order_by($what,$sort_by,$sortdirection);
$maximum = $s[$what.'_search_max'];

foreach ($phrases as $k=>$v)
{ $v = str_replace('(','',str_replace(')','',str_replace('[','',str_replace(']','',$v))));
  if (!trim($v)) continue;
  if ($s[search_words]) $regexp = "REGEXP '(^|[^a-zA-Z])$v($|[^a-zA-Z])'"; elseif ($s[search_use_like]) $regexp = "like '%$v%'"; else $regexp = "REGEXP '$v'";
  if ((substr($v,0,1)=='-') AND ($s[allow_negative])) { $v = substr_replace($v,'',0,1); $array_where[] = "not ($s[pr]index.all_text $regexp)"; }
  else $array_where[] = "($s[pr]index.all_text $regexp)";
}
$table = $s[item_types_tables][$what];
$w[] = '('.implode(" $boolean ",$array_where).") and $standard_where and $table.n = $s[pr]index.n and $s[pr]index.what = '$what'";
foreach ($w as $k=>$v) if (trim($v)) $w[$k] = "($v)"; else unset($w[$k]);
$where =  implode(" $boolean ",$w);
//if (($category) AND (is_numeric($category))) $where .= "and $table.c like '%\_$category\_%'";
//elseif (($bigboss) AND (is_numeric($bigboss))) $where .= "and $table.c_path like '%\_$bigboss\_%'";
if (strstr($standard_where,"$s[pr]cats_items")) { $tables_list = ",$s[pr]cats_items"; }

$ready_where = " where $where";
$q = dq("select count($table.n) from $table,$s[pr]index $tables_list $ready_where",1); $x = mysql_fetch_row($q);
$s[items_total] = $x[0];

if ((!$category) AND (!$bigboss)) $a[categories] = categories_searched($what,$phrases,$boolean); if (!$a[categories]) { $a[hide_categories_begin] = '<!--'; $a[hide_categories_end] = '-->'; }
if (is_array($original_phrase)) $a[original_phrase] = trim(implode(' ',$original_phrase)); else $a[original_phrase] = trim($original_phrase); if (!trim($a[original_phrase])) $a[original_phrase] = $m[search_result];
if (!$bigboss) $bigboss = 0; $a['selected_'.$what.'_'.$bigboss] = ' selected';
if ((!$s[items_total]) AND (!$a[categories])) { $s[info] = info_line($m[no_result]); search_form($a); }
if ($s[items_total]>$maximum) $s[items_total] = $maximum;

//echo "<br><br>select $table.* from $table,$s[pr]index $tables_list $ready_where order by $order_by limit $from,$perpage<br><br>";

$q = dq("select $table.* from $table,$s[pr]index $tables_list $ready_where order by $order_by limit $from,$perpage",0);
while ($x=mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
if ($item) 
{ $function = 'get_complete_'.$s[item_types_words][$what].'s';
  $a[items] = $function($item,$numbers,$s[item_types_words][$what].'_a.txt');
  if ($s[drop_down]) $a[pages] = search_pages_form($what,$action,$original_phrase,$boolean,$search_kind,$category,$bigboss,$sort_by,$sortdirection,$s[items_total],$perpage,$page);
  else $a[pages] = search_pages_list($what,$action,$original_phrase,$boolean,$search_kind,$category,$bigboss,$sort_by,$sortdirection,$s[items_total],$perpage,$page);
}
$a[pages1] = $s[pages_list_numbers];
$a[adlinks] = get_adlinks(0,$a[original_phrase]);
$a["selected_$search_kind"] = ' selected';
$a[meta_title] = $a[original_phrase];
$a[meta_description] = "$a[original_phrase], $s[site_name]";
$a[items_title] = $m[$s[items_types_words][$what]];
page_from_template('search_result.html',$a);
}

#############################################################################
#############################################################################
#############################################################################

function search_all($phrase,$search_kind) {
global $s,$m;
$phrase = delete_special_chars($phrase);
if ($search_kind=='phrase') { $phrases[0] = $phrase; $boolean = 'and'; }
else
{ $phrases = delete_some_words($phrase);
  if ($search_kind=='or') $boolean = 'or'; else $boolean = 'and';
}
if (!$phrases[0]) search_form();

if ($s[search_highlight]) { $s[highlight] = $phrases; foreach ($usit as $k=>$v) if ($v) $s[highlight_usit][$k] = $v; }
update_search_log($phrases,'',$nolog);
$a[searched] = get_info_what_searched('',$phrases,$boolean,0,0);
$standard_where = get_where_fixed_part('',0,'',$s[cas]);
if ((!is_numeric($perpage)) OR ($perpage>50) OR ($perpage<=0)) $perpage = $s[$what.'_per_page'];
//preg_replace('/\W/', '_', $string)
foreach ($phrases as $k=>$v)
{ $v = str_replace('(','',str_replace(')','',str_replace('[','',str_replace(']','',$v))));
  if (!trim($v)) continue;
  if ($s[search_words]) $regexp = "REGEXP '(^|[^a-zA-Z])$v($|[^a-zA-Z])'"; elseif ($s[search_use_like]) $regexp = "like '%$v%'"; else $regexp = "REGEXP '$v'";
  if ((substr($v,0,1)=='-') AND ($s[allow_negative])) { $v = substr_replace($v,'',0,1); $array_where[] = "not ($s[pr]index.all_text $regexp)"; }
  else $array_where[] = "($s[pr]index.all_text $regexp)";
}

foreach ($s[item_types_short] as $k1=>$what)
{ if (!$s["section_$what"]) { $a['hide_'.$s[item_types_words][$what].'s_begin'] = '<!--'; $a['hide_'.$s[item_types_words][$what].'s_end'] = '-->'; continue; }
  $order_by = find_order_by($what,$sort_by,$sortdirection);
  $table = $s[item_types_tables][$what];
  $w[] = '('.implode(" $boolean ",$array_where).") and $standard_where and $table.n = $s[pr]index.n";
  foreach ($w as $k=>$v) if (trim($v)) $w[$k] = "($v)"; else unset($w[$k]);
  $where =  implode(" $boolean ",$w);
  //echo "select $table.* from $table,$s[pr]index where $where and $s[pr]index.what = '$what' order by $table.$order_by limit 5<br><br>";
  $q = dq("select $table.* from $table,$s[pr]index where $where and $s[pr]index.what = '$what' order by $table.$order_by limit 5",0);
  while ($x=mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
  if ($item) 
  { if ($_GET[action]=='rss')
    { foreach ($item as $k=>$item1)
      { $item1[created] = date('D, j M Y H:i:s',$item1[created]+$s[time_plus]);
	    $item1[url] = get_detail_page_url($what,$item1[n],$item1[rewrite_url],0,1);
	    if (!$item1[description]) $item1[description] = strip_tags($item1[text]);
        foreach ($item1 as $k1=>$v1) $item1[$k1] = str_replace('&','&amp;',unreplace_once_html($v1));
        $item1 = replace_array_text($item1);
        $a[individual_items] .= parse_part('rss_output.txt',$item1);
      }
    }
    else
    { $function = 'get_complete_'.$s[item_types_words][$what].'s';
      $a[$what.'_items'] = $function($item,$numbers,$s[item_types_words][$what].'_a.txt');
      $have_results = 1;
    }
  }
  else { $a['hide_'.$s[item_types_words][$what].'s_begin'] = '<!--'; $a['hide_'.$s[item_types_words][$what].'s_end'] = '-->'; }
  unset($w,$item,$numbers);
}
if ($_GET[action]=='rss')
{ $a[title] = $phrase;
  header('Content-type: text/xml');
  echo stripslashes(parse_part('rss_output.html',$a));
  exit;
}
$a[original_phrase] = trim($phrase);
if (!$have_results) { $s[info] = info_line($m[no_result]); search_form($a); }
$a[phrase] = urlencode($phrase); $a[search_kind] = $search_kind;
$a[adlinks] = get_adlinks(0,$a[original_phrase]);
$a[meta_title] = $a[original_phrase];
$a[meta_description] = "$a[original_phrase], $s[site_name]";
page_from_template('search_result_all.html',$a);
}

#############################################################################
#############################################################################
#############################################################################

function shared_advanced1($what,$in) {
global $s;
if (($what=='l') AND ($in[refer])) count_click('in','l',$in[refer]);
if (!is_array($in[phrase])) $phrases[0] = $in[phrase];
else foreach ($in[phrase] as $k=>$v) if ($v) $phrases[$k] = delete_special_chars($v);
$phrases = delete_some_words($phrases);
if ((!$phrases[0]) AND (!$in[usit_from]) AND (!$in[usit_to]) AND (!$in[usit]) AND (!$in[usit_options])) search_form();
if ($in[boolean]=='and') $boolean = 'and'; else $boolean = 'or';

$table = $s[item_types_tables][$what]; if (!$table) exit;
foreach ($phrases as $k=>$v)
{ $v = str_replace('(','',str_replace(')','',str_replace('[','',str_replace(']','',$v))));
  if (!trim($v)) continue;
  if ($s[search_words]) $regexp = "REGEXP '(^|[^a-zA-Z])$v($|[^a-zA-Z])'"; elseif ($s[search_use_like]) $regexp = "like '%$v%'"; else $regexp = "REGEXP '$v'";
  if ((substr($v,0,1)=='-') AND ($s[allow_negative]))
  { $v = substr_replace($v,'',0,1);
    $array_where[] = "not ($s[pr]index.all_text $regexp)";
    $array_where_w_usit[] = "not ($s[pr]index.all_text $regexp OR $s[pr]usit_search.all_usit $regexp)";
  }
  else
  { $array_where[] = "($s[pr]index.all_text $regexp)";
    $array_where_w_usit[] = "($s[pr]index.all_text $regexp OR $s[pr]usit_search.all_usit $regexp)";
  }
}

shared_advanced2($what,$_GET[action],$phrases,$in[phrase],$in[boolean],$boolean,$array_where,$array_where_w_usit,$in[usit],$in[usit_from],$in[usit_to],$in[usit_options],$in[category],$in[bigboss],$in[sort],$in[direction],$in[page],$in[perpage],$in[nolog]);
}

#############################################################################

function shared_advanced2($what,$action,$phrases,$original_phrase,$search_kind,$boolean,$array_where,$array_where_w_usit,$usit,$usit_from,$usit_to,$usit_options,$category,$bigboss,$sort_by,$sortdirection,$page,$perpage,$nolog) {
global $s,$m;
if ($s[search_highlight]) { $s[highlight] = $phrases; foreach ($usit as $k=>$v) if ($v) $s[highlight_usit][$k] = $v; }
update_search_log($phrases,$what,$nolog);
$a[searched] = get_info_what_searched($what,$phrases,$boolean,$category,$bigboss,$usit,$usit_from,$usit_to,$usit_options);
$standard_where = get_where_fixed_part($what,$category,$bigboss,$s[cas]);
if (!$page) $page = 1;
if ((!is_numeric($perpage)) OR ($perpage>50) OR ($perpage<=0)) $perpage = $s[$what.'_per_page'];
$from = ($page-1)*$perpage;
$sortby = find_order_by($what,$sort_by,$sortdirection);

if (!$array_where) $array_where[] = $array_where_w_usit[] = 1;
$table_name = $s[item_types_tables][$what]; $maximum = $s[$what.'_search_max'];

$all_user_items_list = get_all_user_items_list($what);
foreach ($all_user_items_list as $k=>$current_usit)
{ unset($usit_rank,$usit_search);
  $search_n = $current_usit[search_n];
  if ($usit[$search_n])
  { $value = $usit[$search_n];
    $v = str_replace('(','',str_replace(')','',str_replace('[','',str_replace(']','',$v))));
    if ($s[search_words]) { $value = "REGEXP '(^|[^a-zA-Z])$value($|[^a-zA-Z])'"; $w[] = "$s[pr]usit_search.v$search_n $value"; }
    else $w[] = "$s[pr]usit_search.v$search_n like '%$value%'";
  }
  if (($usit_from[$search_n]) OR ($usit_to[$search_n]))
  { if ((!$usit_from[$search_n]) AND ($usit_to[$search_n]<=0)) $usit_from[$search_n] = -9999999999;
    elseif (!$usit_from[$search_n]) $usit_from[$search_n] = 0.0000001;
    if (!$usit_to[$search_n]) $usit_to[$search_n] = 9999999999;
    $w[] = "(($s[pr]usit_search.v$search_n BETWEEN $usit_from[$search_n] AND $usit_to[$search_n]) AND not($s[pr]usit_search.v$search_n = ''))";
  }

  if ($usit_options[$search_n])
  { if ($current_usit[kind]=='checkbox')
    { if ($usit_options[$search_n]=='yes') $w[] = "($s[pr]usit_search.v$search_n like '%\_\_1\_\_%')";
      elseif ($usit_options[$search_n]=='no') $w[] = "($s[pr]usit_search.v$search_n like '%\_\_\_\_%' or $s[pr]usit_search.v$search_n like '%\_\_0\_\_%' or $s[pr]usit_search.v$search_n = '')";
    }
    else $w[] = "($s[pr]usit_search.v$search_n like '%\_\_$usit_options[$search_n]\_\_%')";
  }
}
if (($usit_from) OR ($usit_to) OR ($usit_options) OR ($usit))
{ $w[] = implode(" $boolean ",$array_where_w_usit);
  $tables_list = "$table_name,$s[pr]index,$s[pr]usit_search";
  $usit_standard_where = "and $s[pr]usit_search.n = $table_name.n and $s[pr]usit_search.use_for = '$what'";
}
else
{ $w[] = implode(" $boolean ",$array_where);
  $tables_list = "$table_name,$s[pr]index";
  $usit_standard_where = '';
}
foreach ($w as $k=>$v) if (trim($v)) $w[$k] = "($v)"; else unset($w[$k]);
$where =  implode(" $boolean ",$w);
//if (($category) AND (is_numeric($category))) $where .= "and $table_name.c like '%\_$category\_%'";
//elseif (($bigboss) AND (is_numeric($bigboss))) $where .= "and $table_name.c_path like '%\_$bigboss\_%'";

if (strstr($standard_where,"$s[pr]cats_items")) { $tables_list .= ",$s[pr]cats_items"; }

$where .= " and $standard_where $usit_standard_where and $s[pr]index.n = $table_name.n and $s[pr]index.what = '$what'";
$ready_where = " where $where group by $table_name.n";

//echo "select $table_name.n from $tables_list $ready_where order by $table_name.$sortby limit $maximum";
$q = dq("select $table_name.n from $tables_list $ready_where order by $table_name.$sortby limit $maximum",0);
while ($x = mysql_fetch_row($q)) $found[] = $x[0];
$a[total] = count($found);
//$found = array_slice($found,$from,$perpage); 
//if ($found) $where = my_implode('n','or',$found);

if ((!$category) AND (!$bigboss)) $a[categories] = categories_searched($what,$phrases,$boolean); if (!$a[categories]) { $a[hide_categories_begin] = '<!--'; $a[hide_categories_end] = '-->'; }
if ((!$a[total]) AND (!$a[categories])) { $s[info] = info_line($m[no_result]); search_form(); }
if ($a[total])
{ //echo "select * from $table_name where $where order by $sortby";
  $q = dq("select $table_name.* from $tables_list $ready_where order by $table_name.$sortby limit $from,$perpage",0);
  while ($x=mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
  if ($item) 
  { $function = 'get_complete_'.$s[item_types_words][$what].'s';
    $a[items] = $function($item,$numbers,$s[item_types_words][$what].'_a.txt');
    if ($s[drop_down]) $a[pages] = search_pages_form($what,$action,$original_phrase,$boolean,$search_kind,$category,$bigboss,$sort_by,$sortdirection,$a[total],$perpage,$page,$usit,$usit_from,$usit_to,$usit_options);
    else $a[pages] = search_pages_list($what,$action,$original_phrase,$boolean,$search_kind,$category,$bigboss,$sort_by,$sortdirection,$a[total],$perpage,$page,$usit,$usit_from,$usit_to,$usit_options);
  }
}
else $a[pages] = '<br />';
if (is_array($original_phrase)) $a[original_phrase] = trim(implode(' ',$original_phrase)); else $a[original_phrase] = trim($original_phrase); if (!trim($a[original_phrase])) $a[original_phrase] = $m[search_result];
$a[adlinks] = get_adlinks(0,$a[original_phrase]);
$a["selected_$search_kind"] = ' selected';
if (!$bigboss) $bigboss = 0; $a['selected_'.$what.'_'.$bigboss] = ' selected';
$a[meta_title] = $a[original_phrase];
$a[meta_description] = "$a[original_phrase], $s[site_name]";
$a[items_title] = $m[$s[items_types_words][$what]];
page_from_template('search_result.html',$a);
}

#############################################################################
#############################################################################
#############################################################################

function search_pages_list($what,$action,$phrase,$boolean,$search_kind,$category,$bigboss,$sortby,$sortdirection,$total,$perpage,$page,$usit,$usit_from,$usit_to,$usit_options) {
global $s,$m;
//if ((!$total) OR ($total<=1)) return '<br />';
//if ($total<=$perpage) return '<br />';
if (!$total) $total = 1;
$z = array('action'=>$action,'phrase'=>$phrase,'nolog'=>1);
if ($boolean) $z[boolean] = $boolean;
if ($search_kind) $z[search_kind] = $search_kind;
if ($bigboss) $z[bigboss] = $bigboss;
if ($category) $z[category] = $category;
if ($perpage) $z[perpage] = $perpage;
if ($boolean) $z[boolean] = $boolean;
foreach ($usit as $k=>$v) if ($v) $z[usit][$k] = $v;
foreach ($usit_from as $k=>$v) if ($v) $z[usit_from][$k] = $v;
foreach ($usit_to as $k=>$v) if ($v) $z[usit_to][$k] = $v;
foreach ($usit_options as $k=>$v) if ($v) $z[usit_options][$k] = $v;

foreach ($z as $k => $v)
{ if (is_array($v)) foreach ($v as $k1=>$v1) $x[] = $k.'['.$k1.']='.$v1;
  else $x[] = $k.'='.$v;
}
if ($x) $hidden = implode('&amp;',$x);

$a[items_displayed] = $m[$s[items_types_words][$what].'_found']; $sorts = explode(',',$s[$what.'_sort']);
$base = "$s[site_url]/search.php?$hidden&amp;sort=";
foreach ($sorts as $k=>$v)
{ if ($s[this_sort]==$v) $sort_options[] = "$m[$v]";
  else $sort_options[] = '<a href="'.$base.$v.'&amp;direction='.$sortdirection.'">'.$m[$v].'</a>';
}
$a[sortby_options] = implode(' ',$sort_options);

$hidden .= "&amp;sort=title&amp;direction=";
$a[pages_list] = search_pages_list_numbers($total,$perpage,$page,$base.$sortby.'&amp;direction='.$sortdirection); if (!$a[pages_list]) { $a[hide_pages_list_begin] = '<!--'; $a[hide_pages_list_end] = '-->'; }
$a[link_asc] = 'href="'.$base.$sortby.'&amp;direction=asc"';
$a[link_desc] = 'href="'.$base.$sortby.'&amp;direction=desc"';

if (!$total) $total = 0; $a[total] = $total;
if ($total<=1)
{ $a[hide_pages_list_begin] = '<!--'; $a[hide_pages_list_end] = '-->';
  $a[hide_sortby_begin] = '<!--'; $a[hide_sortby_end] = '-->';
}
return parse_part('pages_list.txt',$a);
}

#############################################################################

function search_pages_list_numbers($total,$perpage,$page,$hidden) {
global $s,$m;
$hidden = str_replace("$s[site_url]/",'',$hidden);
$pages = ceil($total/$perpage); 
if ($pages==1) $pages_list = '';
else
{ $sort = str_replace(' desc','',str_replace('pick desc,','',$sort));
  $url = $s[site_url].'/'.$hidden.'&amp;page=';
  for ($x=1;$x<=$pages;$x++)
  { if ($x==$page) $y .= "&nbsp;$x "; 
    elseif ((!$s[pages_max_links]) OR (($x>=($page-$s[pages_max_links])) AND ($x<=($page+$s[pages_max_links])))) $y .= ' <a href="'.$url.$x.'">'.$x.'</a> ';
    if ($s[pages_max_links])
    { if ($page>1) $link_first = ' <a href="'.$url.'1">&laquo;&laquo;&laquo;</a> ';
      if ($page<$pages) $link_last = ' <a href="'.$url.$x.'">&raquo;&raquo;&raquo;</a> ';
    }
    if ($x==($page-1))
    { $link_down = ' <a href="'.$url.($page-1).'">&laquo;</a> ';
      $s[head_pagination] .= "<link rel=\"prev\" href=\"".str_replace('&amp;','&',$url.($page-1))."\">\n";
    }
    elseif ($x==($page+1))
    { $link_up = ' <a href="'.$url.($page+1).'">&raquo;</a> ';
      $s[head_pagination] .= "<link rel=\"next\" href=\"".str_replace('&amp;','&',$url.($page+1))."\">\n";
    }
  }
  $pages_list = " $link_first$link_down$y$link_up$link_last";
}
$s[pages_list_numbers] = $pages_list;
return $pages_list;
}

#############################################################################

function search_pages_form($what,$action,$phrase,$boolean,$search_kind,$category,$bigboss,$sort,$direction,$total,$perpage,$page,$usit,$usit_from,$usit_to,$usit_options) {
global $s,$m;
$z = array('action'=>$action,'phrase'=>$phrase,'nolog'=>1);
if ($boolean) $z[boolean] = $boolean;
if ($search_kind) $z[search_kind] = $search_kind;
if ($bigboss) $z[bigboss] = $bigboss;
if ($category) $z[category] = $category;
if ($perpage) $z[perpage] = $perpage;
if ($boolean) $z[boolean] = $boolean;
foreach ($usit as $k=>$v) if ($v) $z[usit][$k] = $v;
foreach ($usit_from as $k=>$v) if ($v) $z[usit_from][$k] = $v;
foreach ($usit_to as $k=>$v) if ($v) $z[usit_to][$k] = $v;
foreach ($usit_options as $k=>$v) if ($v) $z[usit_options][$k] = $v;

foreach ($z as $k => $v)
{ if (is_array($v)) foreach ($v as $k1=>$v1) $a[hidden_fields] .= '<input type="hidden" name="'.$k.'['.$k1.']" value="'.$v1.'">';
  else $a[hidden_fields] .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
}

if ($total<=1)
{ if (!$total) $total = 0;
  $a[total] = $total;
  $a[hide_pages_list_begin] = '<!--'; $a[hide_pages_list_end] = '-->';
  $a[hide_sortby_begin] = '<!--'; $a[hide_sortby_end] = '-->';
  $a[hide_button_begin] = '<!--'; $a[hide_button_end] = '-->';
  return parse_part('pages_form.txt',$a);
}

$a[items_displayed] = $m[$s[items_types_words][$what].'_found'];
if (!$sort) { $sort = $s[$what.'_sortby']; $direction = $s[$what.'_sortby_direct']; }
$sorts = explode(',',$s[$what.'_sort']);

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

$a[script_name] = 'search.php';
$a[$direction.'_selected'] = ' selected';
$a[total] = $total;
return parse_part('pages_form.txt',$a);
}

#####################################################################################
#####################################################################################
#####################################################################################

function delete_some_words($in) {
global $s;
if (!is_array($in)) $in = explode(' ',$in);
foreach ($in as $k => $v)
{ if ((in_array(trim($v),$s[short_words])) OR (strlen($v)<$s[search_min])) unset($in[$k]);
  $in[$k] = str_replace('?','',$in[$k]);
  $in[$k] = str_replace('(','',$in[$k]);
  $in[$k] = str_replace(')','',$in[$k]);
}
return $in;
}

#####################################################################################

function delete_special_chars($x) {
return str_replace("'",' ',str_replace('"',' ',str_replace(chr(92),' ',$x)));
}

#####################################################################################

function update_search_log($phrases,$what,$nolog) {
global $s;
if ($nolog) return false;
if (is_array($phrases)) $x = $phrases; else $x = explode(' ',$phrases);
foreach($x as $k => $v)
{ $v = trim($v); if ((!$v) OR ($v=='Array') OR (strstr($v,'http://')) OR (strstr($v,'<')) OR (strlen($v)>25)) continue;
  dq("update $s[pr]log_search set count = count + 1 where word = '$v'",0);
  if (mysql_affected_rows()<=0) dq("insert into $s[pr]log_search values('','$v','1',NULL)",0);
  //echo "search log: $v<br><br>";
}
}

#####################################################################################

function get_info_what_searched($what,$phrases,$boolean,$category,$bigboss,$usit,$usit_from,$usit_to,$usit_options) {
global $s,$m;
//foreach ($usit_options as $k=>$v) echo "$k - $v<br>";

if ($boolean=='and') $boolean = $m[a]; else $boolean = $m[nebo];
foreach ($phrases as $k=>$v) $phrases[$k] = "<b>$v</b>";
if (($usit) OR ($usit_from) OR ($usit_to) OR ($usit_options))
{ $all_user_items_list = get_all_user_items_list($what);
  $all_user_items_values = get_all_user_items_values($what);
  foreach ($all_user_items_list as $k=>$v)
  { if ($usit[$v[search_n]]) $phrases[] = '<b>'.$v[description].' = '.$usit[$v[search_n]].'</b>';
    if ($usit_from[$v[search_n]]) $x[] = '<b>'.$v[description].' >= '.$usit_from[$v[search_n]].'</b>';
    if ($usit_to[$v[search_n]]) $x[] = '<b>'.$v[description].' <= '.$usit_to[$v[search_n]].'</b>';
    if (($usit_from[$v[item_n]]) OR ($usit_to[$v[item_n]])) $phrases[] = implode(" $m[a] ",$x); unset($x);
    
    if ($usit_options[$v[search_n]])
    { if ($v[kind]=='checkbox')
      { if ($usit_options[$v[search_n]]=='yes') $phrases[] = '<b>'.$v[description].' = '.$m[a].'</b>';
        elseif ($usit_options[$v[search_n]]=='no') $phrases[] = '<b>'.$v[description].' = '.$m[no].'</b>';
      }
      else $phrases[] = '<b>'.$v[description].' = '.$all_user_items_values[$v[item_n]][$usit_options[$v[search_n]]].'</b>';
    }
    
    
  }
}
$a = implode(' '.$boolean.' ',$phrases);

if ($category)
{ $x = get_category_variables($category);
  $category_url = category_url($what,$x[n],$x[alias_of],$x[name],1,$x[pagename],$x[rewrite_url],'','');
  if ($x[alias_of]) $x[name] = $s[alias_pref].$x[name].$s[alias_after];
  $a .= " $m[in_cat] <a href=\"$category_url\">$x[name]</a>";
}
if ($bigboss)
{ $x = get_category_variables($bigboss);
  $category_url = category_url($what,$x[n],$x[alias_of],$x[name],1,$x[pagename],$x[rewrite_url],'','');
  if ($x[alias_of]) $x[name] = $s[alias_pref].$x[name].$s[alias_after];
  $a .= " $m[in_cat] <a href=\"$category_url\">$x[name]</a> $m[incl_subc]";
}
return $a;
}

#############################################################################
#############################################################################
#############################################################################

function search_form($in) {
global $s,$m;
foreach ($s[item_types_short] as $k=>$what)
{ $sorts = explode(',',$s[$what.'_sort']);
  $sort = $s[$what.'_sortby'];
  foreach ($sorts as $k1=>$v1)
  { if ($sort==$v1) $selected = ' selected'; else $selected = '';
    $a['sort_options_'.$what] .= '<option value="'.$v1.'"'.$selected.'>'.$m[$v1].'</option>';
  }
  $a[$what.'_all_cats'] = categories_selected($what,0,0,1,0,1);
  $a[$what.'_first_cats'] = categories_selected($what.'_first',0,0,1,0,1);
}
//$a[top_searches] = top_searches();
$a[top_tags] = top_tags();
$a[info] = $s[info];
$a = array_merge((array)$in,(array)$a);
page_from_template('search.html',$a);
}

#############################################################################

function top_searches() {
global $s,$m;
$q = dq("select * from $s[pr]log_search order by count desc limit $s[top_search]",0);
while ($x = mysql_fetch_assoc($q))
{ $font_size = round(35 - ($pocet));
  $words_array[] = '<a style="font-size:'.$font_size.'px"; href="'.$s[site_url].'/search.php?phrase='.urlencode($x[word]).'">'.$x[word].'</a>';
  $pocet++;
}
shuffle($words_array);
$a = implode("\n",$words_array);
return $a;
}

#############################################################################
/*
function xml() {
global $s,$m;
$q = dq("select * from $s[pr]log_search order by count desc limit $s[top_search]",0);
while ($x = mysql_fetch_assoc($q))
{ $item1[created] = date('D, j M Y H:i:s',$s[cas]);
  $item1[url] = $s[site_url].'/search.php?phrase='.urlencode($x[word]);
  $item1[description] = $x[word];
  foreach ($item1 as $k1=>$v1) $item1[$k1] = str_replace('&','&amp;',unreplace_once_html($v1));
  $item1 = replace_array_text($item1);
  $a[individual_items] .= parse_part('xml_output.txt',$item1);
}
header('Content-type: text/xml');
echo stripslashes(parse_part('xml_output.html',$a));
exit;
}*/

#############################################################################

function top_tags() {
global $s,$m;

$search = array("[\]",'&amp;',"&#039;",'"','(',')','-');
$replace = array('&#92;','&',"'",'','','','');

include("$s[phppath]/data/top_tags.php");
foreach ($top_tags as $k=>$v)
{ $font_size = round(35 - ($pocet/2));
  $x1 = trim(str_replace($search,$replace,unhtmlentities($v)));
  if ($x1) { $pocet++; if ($pocet<=50) $words_array[] = '<a style="font-size:'.$font_size.'px"; href="'.$s[site_url].'/search.php?phrase='.urlencode($x1).'">'.$x1.'</a>'; }
}
shuffle($words_array);
$a = implode("\n",$words_array);

return $a;
}

#####################################################################################
#####################################################################################
#####################################################################################

function categories_searched($what,$phrases,$boolean) {
global $s,$m;
$columns = $s[subc_column];
foreach ($phrases as $k=>$v) if ($v) $w[] = "(name like '%$v%' OR description like '%$v%')";
if (!$w[0]) return false;
$where = "where use_for = '$what' AND visible = 1 AND (".implode(" $boolean ",$w).')';

$q = dq("select * from $s[pr]cats $where order by name",1);
$a[title] = $m[categories];
if (!mysql_num_rows($q)) return false;

while ($c = mysql_fetch_assoc($q))
{ $c[url] = category_url($what,$c[n],$c[alias_of],$c[name],1,$c[pagename],$c[rewrite_url],$sort,$direction);
  $c[folder_icon] = folder_icon($c[item_created],$c[image2]);
  if ($c[alias_of]) $c[name] = $s[alias_pref].$c[name].$s[alias_after];
  $c[items] = "$c[items] ".$m[$s[items_types_words][$what].'1'];
  $c[width] = floor(100/$columns);
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

$a[colspan] = $columns;
return parse_part('categories.txt',$a);
}

#####################################################################################
#####################################################################################
#####################################################################################

?>