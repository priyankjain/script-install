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
$script_name = $s[item_types_scripts][$_GET[what]]; if (!$script_name) exit;
get_messages($script_name);
include($s[phppath].'/data/data_forms.php');

switch ($_GET[action]) {
case 'category'					: category_ajax($_GET);
case 'category_pages_list'		: category_pages_list_ajax($_GET);
case 'category_pages_list1'		: category_pages_list_ajax1($_GET);
}

#############################################################################
#############################################################################
#############################################################################

function category_ajax($in) {
global $s,$m;
if (!is_numeric($_GET[n])) exit;
$table = $s[item_types_tables][$in[what]];
$perpage = $s[$in[what].'_per_page'];
$a = get_category_variables($in[n]);
if ($a[alias_of]) $a = get_category_variables($a[alias_of]);
check_access_rights("c_$in[what]",$a[n],$a);
if (!$a[name]) exit;
if (!$a[tmpl_one]) $a[tmpl_one] = $s[item_types_words][$in[what]].'_a.txt';
$_GET[page] = round($_GET[page]);
if ((!$_GET[page]) OR (!is_numeric($_GET[page]))) { $from = 0; $_GET[page] = 1; } else $from = $perpage * ($_GET[page]-1); 
$sortby = find_order_by($in[what],$_GET[sort],$_GET[direction]);

$where = 'where '.get_where_fixed_part($in[what],$a[n],'',$s[cas]);
$q = dq("select count(*) from $table,$s[pr]cats_items $where",1);

$total = mysql_fetch_row($q); $a[total] = $total[0];
$q = dq("select * from $table,$s[pr]cats_items $where order by $sortby limit $from,$perpage",1);
while ($x = mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }

if ($numbers)
{ foreach ($item as $k => $d) $item[$k][category] = $a[n];
  $function = 'get_complete_'.$s[items_types_words][$in[what]];
  $a[items] = $function($item,$numbers,$a[tmpl_one]);
}
else exit;
//if ($in[what]=='v') page_from_template_no_headers('category_ajax_videos.txt',$a);
page_from_template_no_headers('category_ajax.txt',$a);
}

##################################################################################

function category_pages_list_ajax($in) {
global $s,$m;
//foreach ($in as $k=>$v) echo "$k - $v<br>";
if (!is_numeric($in[n])) exit;
$perpage = $s[$in[what].'_per_page'];
echo stripslashes(category_pages_list($in[what],$in[n],$in[total],$in[page],$in[rewrite],$in[sort],$in[direction]));
exit;
}

##################################################################################

function category_pages_list_ajax1($in) {
global $s,$m;
//foreach ($in as $k=>$v) echo "$k - $v<br>";
if (!is_numeric($in[n])) exit;
$perpage = $s[$in[what].'_per_page'];
echo stripslashes(category_pages_list_numbers($in[what],$in[n],$name,'',$in[total],$in[page],$in[rewrite],$in[sort],$in[direction]));
exit;
}

##################################################################################
##################################################################################
##################################################################################

?>