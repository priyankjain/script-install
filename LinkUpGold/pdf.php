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
header("Content-type: application/octet-stream\n" );
header("Content-Disposition: filename=document.pdf");
header("Content-Disposition: attachment; filename=document.pdf");
session_start();
include('./data/data.php');
$linkid = db_connect(); if (!$linkid) die($s[db_error]);

$s[item_types_short] = array('l','a','v','n','b');
$s[item_types_words] = array('l'=>'link','a'=>'article','v'=>'video','n'=>'new','b'=>'blog');
$s[item_types_Words] = array('l'=>'Link','a'=>'Article','v'=>'Video','n'=>'News Item','b'=>'Blog');
$s[items_types_words] = array('l'=>'links','a'=>'articles','v'=>'videos','n'=>'news','b'=>'blogs','u'=>'users','c'=>'categories');
$s[items_types_Words] = array('l'=>'Links','a'=>'Articles','v'=>'Videos','n'=>'News','b'=>'Blogs');
$s[item_types_tables] = array('l'=>"$s[pr]links",'a'=>"$s[pr]articles",'v'=>"$s[pr]videos",'n'=>"$s[pr]news",'b'=>"$s[pr]blogs",);
$s[item_types_scripts] = array('l'=>'links.php','a'=>'articles.php','v'=>'videos.php','n'=>'news.php','b'=>'blogs.php');

if ((!in_array($_GET[what],$s[item_types_short])) OR (!is_numeric($_GET[n]))) exit;

$table = $s[item_types_tables][$_GET[what]];
$q = mysql_query("select * from $table where n = '$_GET[n]' and status = 'enabled'");
$a = mysql_fetch_assoc($q);

$a[created] = datum($a[created],0);
if (!$a[detail]) $a[detail] = $a[text];
if ($a[url]) $a[title] = '<a target="_blank" href="'.$a[url].'">'.$a[title].'</a>';
else $a[url] = '';

foreach ($a as $k=>$v) $a[$k] = strip_tags($v);

$a[user_defined] = user_defined_items_display($_GET[what],$all_user_items_list,$all_user_items_values,$a[n],'user_item_listing.txt',0,1,0,1);
$q = dq("select * from $s[pr]files where item_n = '$_GET[n]' and queue = '0' and what = '$_GET[what]' and file_type = 'image' order by file_n limit 1",1);
$x = mysql_fetch_assoc($q);
$big_file = preg_replace("/\/$x[item_n]-/","/$x[item_n]-big-",$x[filename]);
if (file_exists(str_replace("$s[site_url]/","$s[phppath]/",$x[filename]))) $x[filename] = $big_file;
if (file_exists(str_replace("$s[site_url]/","$s[phppath]/",$x[filename]))) $a[picture] = '<img border="0" src="'.str_replace("$s[site_url]/","$s[phppath]/",$x[filename]).'" />'; else $a[picture] = '';
$a[site_url] = $s[site_url]; $a[site_name] = $s[site_name];
$a[site_logo] = str_replace("$s[site_url]/","$s[phppath]/",$s[logo_url]);

require_once('./pdf/config/lang/eng.php');
require_once('./pdf/tcpdf.php');

class MYPDF extends TCPDF { function Header() { $this->Ln(20); } }
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->Ln(20);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
$pdf->SetFont('freeserif', '', 12);// unicode

$pdf->AddPage();
$html = implode('',file('pdf/template.html'));
foreach ($a as $k=>$v) $html = str_replace("#%$k%#",$v,$html);
if ($s[charset]!="UTF-8") $html = iconv($s[charset],"UTF-8",$html);
$html = str_replace('&quot;','"',$html);
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->lastPage();
ob_end_clean();
$pdf->Output('document.pdf', 'D');

exit;

##################################################################################
##################################################################################
##################################################################################

function db_connect() {
global $s;
unset($s[db_error],$s[dben]);
if ($s[nodbpass]) $link_id = mysql_connect($s[dbhost], $s[dbusername]);
else $link_id = mysql_connect($s[dbhost],$s[dbusername],$s[dbpassword]);
if(!$link_id)
{ $s[db_error] = "Unable to connect to the database host. Check database host, username, password."; $s[dben] = mysql_errno(); return 0; }
if ( (!$s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if ( ($s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if (($s[charset]=='UTF-8') OR ($s[charset]=='utf-8')) MySQL_Query("SET NAMES utf8");
return $link_id;
}

##################################################################################

function dq($query,$check) {
global $s;
$query = str_replace('insert into','insert ignore into',$query);
$query = str_replace("update $s[pr]","update ignore $s[pr]",$query);
$q = mysql_query($query);
if (($check) AND (!$q)) die(mysql_error());
return $q;
}

##################################################################################

function datum($cas,$plustime) {
global $s;
if (is_array($cas)) $cas = mktime(6,0,0,$cas[date_m],$cas[date_d],$cas[date_y]);
elseif (!$cas) $cas = $s[cas];
for ($y=1;$y<=3;$y++) if ($s['date_form_'.$y.'a']=='Space') $date_separator[$y] = ' '; elseif ($s['date_form_'.$y.'a']=='Nothing') $date_separator[$y] = ''; else $date_separator[$y] = $s['date_form_'.$y.'a'];
$x[d] = date('d',$cas); $x[m] = date('m',$cas); $x[y] = date('Y',$cas);
$datum = $x[$s[date_form_1]].$date_separator[1].$x[$s[date_form_2]].$date_separator[2].$x[$s[date_form_3]].$date_separator[3];
if ($plustime) { if ($s[time_form]=='12') $datum .= date(', g:i a',$cas); else $datum .= date(', G:i',$cas); }
return $datum;
}

##################################################################################

function get_all_user_items_values($what) {
global $s;
$q = dq("select * from $s[pr]usit_avail_val where use_for = '$what'",1);
while ($x = mysql_fetch_assoc($q)) $all_user_items_values[$x[item_n]][$x[value_code]] = $x[description];
return $all_user_items_values;
}

#####################################################################################

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
if (!$all_user_items_list)
{ $q = dq("select * from $s[pr]usit_list where use_for = '$use_for1' order by rank",1);
  while ($x = mysql_fetch_assoc($q)) $all_user_items_list[] = $x;
}
if (!$all_user_items_values) $all_user_items_values = get_all_user_items_values($use_for1);

$q = dq("select * from $s[pr]usit_values where use_for = '$use_for' AND n = '$n'",1);
while ($x = mysql_fetch_assoc($q))
{ $b[$x[n]][$x[item_n]][code] = $x[value_code];
  $b[$x[n]][$x[item_n]][text] = $x[value_text];
}
$v1 = $n;
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
    if ($data[value]) $a .= strip_tags("$data[name]: $data[value]")."<br />";
  }
}
foreach ($all_user_items_list as $k=>$v)
{ if (($only_pages) AND (!$v[visible_pages])) unset($c[$v[item_n]]);
}
return $a;
}

##################################################################################
##################################################################################
##################################################################################

?>
