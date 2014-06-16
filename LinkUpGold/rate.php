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
get_messages('rate.php');
include_once("$s[phppath]/data/data_forms.php");
$s[rating_height] = 150;

//if ($_GET[rated_now]) foreach ($_GET as $k => $v) echo "$k - $v<br>\n";

if (($_GET[what]) AND ($_GET[n]) AND ($_GET[rated_now])) rated($_GET);
if (($_POST[what]) AND ($_POST[n])) rated_remote($_POST);
rating_table($_GET[what],$_GET[n]);

###############################################################################

function rated($in) {
global $s,$m;
$in = replace_array_text($in);
if ($s[rate_duplicate]) check_duplicate($in);
if ((!$s[LUG_u_n]) AND ($s[rate_reg_only]))
{ if ($in[remote]) problem($m[no_logged]);
  else { $s[problem] = $m[no_logged]; rating_table($in[what],$in[n]); } 
}
$s[rated_now] = 1;
$data = get_item_variables($in[what],$in[n]);
$data[url] = get_detail_page_url($in[what],$in[n],$data[rewrite_url],0,1);
$table = $s[item_types_tables][$in[what]];
dq("insert into $s[pr]rates values ('$in[what]','$in[n]','$in[rating]','$s[ip]','$s[cas]')",1);
$q = dq("select rating from $s[pr]rates where what = '$in[what]' and n = '$in[n]' order by rating",1);
$rows = mysql_num_rows($q); $vyhodit = floor($rows*($s[rate_exclude]/100));
while ($x = mysql_fetch_assoc($q)) $rates[] = $x[rating]; $votes = count($rates);
array_splice($rates,0,$vyhodit); array_splice($rates,-$vyhodit,$vyhodit);
$average = (array_sum($rates))/(count($rates));
dq("update $table set rating = '$average', votes = '$votes' where n = '$in[n]'",1);
$data[rating] = $in[rating];
rating_table($in[what],$in[n]);
}

###############################################################################

function check_duplicate($in) {
global $s,$m;
$q = dq("select count(*) from $s[pr]rates where ip = '$s[ip]' AND n = '$in[n]' AND what = '$in[what]'",1);
$x = mysql_fetch_row($q);
if (!$x[0]) $x[0] = $_COOKIE["vote$in[what]"][$in[n]];
if ($x[0])
{ if ($in[remote]) problem($m[rate_dupl]);
  else { $s[problem] = $m[rate_dupl]; rating_table($in[what],$in[n]); }
}
setcookie("vote$in[what][$in[n]]",1,$s[cas]+2592000);
}

###############################################################################

function rating_table($what,$n) {
global $s,$m;
$item_vars = get_item_variables($what,$n);

$q = dq("select * from $s[pr]rates where ip = '$s[ip]' AND n = '$n' AND what = '$what' order by time desc",1);
$rated = mysql_fetch_assoc($q);
$q = dq("select rating,count(*) from $s[pr]rates where what = '$what' and n = '$n' group by rating",1);
while ($x=mysql_fetch_row($q)) { $rating[$x[0]] = $x[1]; if ($x[1]>$big) $big = $x[1]; }
for ($x=1;$x<=5;$x++)
{ if (!$rating[$x]) { $a["size$x"] = 1; $rating[$x] = 0; }
  else $a["size$x"] = floor(($rating[$x]/$big)*$s[rating_height]);
  $a["rates$x"] = $rating[$x];
}
if (($s[rate_duplicate]) and ( (($rated[n]) and ($rated[rating])) or ($_COOKIE["vote$what"][$n])) ) { $a[disabled] = ' disabled'; $a["checked$rated[rating]"] = ' checked'; $have_rating = 1; }
elseif ($rated[rating]) { $a["checked$rated[rating]"] = ' checked'; $have_rating = 1; }
if (!$have_rating) $a[checked3] = ' checked';
$a[n] = $n; $a[what] = $what;
$a[rating_form_display] = 'block';
$a[rating] = $item_vars[rating]; $a[votes] = $item_vars[votes]; $a[n] = $item_vars[n]; $a[rateicon] = addslashes(get_rateicon($a[rating]));
//foreach ($a as $k => $v) echo "$k - $v<br>\n";
if ($s[problem])
{ $a[info] = '<br>'.info_line($s[problem]);
  $a[rating_form_display] = 'none';
  echo iconv($s[charset],'UTF-8',stripslashes(parse_part('ratings_table.txt',$a)));
}
elseif ($s[rated_now])
{ $a[info] = '<br>'.info_line($m[rating_recorded]);
  $a[rating_form_display] = 'none';
  echo iconv($s[charset],'UTF-8',stripslashes(parse_part('ratings_table.txt',$a)));
}
else parsejava('ratings_table.txt',$a);
exit;
}

###############################################################################
###############################################################################
###############################################################################

function rated_remote($in) {
global $s,$m;
$in = replace_array_text($in);
if ($s[rate_duplicate]) check_duplicate($in);
$data = get_item_variables($in[what],$in[n]);
$data[url] = get_detail_page_url($in[what],$in[n],$data[rewrite_url],0,1);
$table = $s[item_types_tables][$in[what]];
dq("insert into $s[pr]rates values ('$in[what]','$in[n]','$in[rating]','$s[ip]','$s[cas]')",1);
$q = dq("select rating from $s[pr]rates where what = '$in[what]' and n = '$in[n]' order by rating",1);
$rows = mysql_num_rows($q); $vyhodit = floor($rows*($s[rate_exclude]/100));
while ($x = mysql_fetch_assoc($q)) $rates[] = $x[rating]; $votes = count($rates);
array_splice($rates,0,$vyhodit); array_splice($rates,-$vyhodit,$vyhodit);
$average = (array_sum($rates))/(count($rates));
dq("update $table set rating = '$average', votes = '$votes' where n = '$in[n]'",1);
$data[rating] = $in[rating];
page_from_template('rated_remote.html',$data);
}

###############################################################################
###############################################################################
###############################################################################

?>