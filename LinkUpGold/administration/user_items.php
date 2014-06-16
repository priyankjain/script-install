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
check_admin('configuration');

switch ($_GET[action]) {
case 'user_items_home'		: user_items_home($_GET);
case 'user_item_edit'		: user_item_edit($_GET);
case 'user_item_delete'		: user_item_delete($_GET);
}
switch ($_POST[action]) {
case 'user_item_created'	: user_item_created($_POST);
case 'user_item_edited'		: user_item_edited($_POST);
case 'user_item_deleted'	: user_item_deleted($_POST);
}

###################################################################################
###################################################################################
###################################################################################

function user_items_home($in) {
global $s;
ih();
$s[action] = 'user_item_created';
echo $s[info].info_line('User Defined Items','These items can be used for additional items (which are not available by default) for links, articles and categories.<br />These items are available in submit forms as well as on public and admin pages.');
$s[table_title] = 'Create a New User Defined Item';
user_item_create_edit_form($in);
user_items_list();
ift();
}

###################################################################################

function user_items_list() {
global $s;
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Existing User defined Items</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" valign="top" nowrap><span class="text10"><b>#</b></span></td>
<td align="left" valign="top" nowrap><span class="text10"><b>Use&nbsp;for</b></span></td>
<td align="left" valign="top" nowrap><span class="text10"><b>Rank</b></span></td>
<td align="left" valign="top" nowrap><span class="text10"><b>Description</b></span></td>
<td align="left" valign="top" nowrap><span class="text10"><b>Type</b></span></td>
<td align="center" valign="top" nowrap><span class="text10"><b>Visible<br />in forms</b></span></td>
<td align="center" valign="top" nowrap><span class="text10"><b>Required</b></span></td>
<td align="center" valign="top" nowrap><span class="text10"><b>Visible<br />on pages</b></span></td>
<td align="center" valign="top" nowrap><span class="text10">&nbsp;</span></td>
</tr>';
$q = dq("select * from $s[pr]usit_list order by use_for,rank",1);
while ($x = mysql_fetch_assoc($q))
{ if ($x[required]) $required = 'Yes'; else $required = 'No';
  if ($x[visible_forms]) $visible_forms = 'Yes'; else $visible_forms = 'No';
  if ($x[visible_pages]) $visible_pages = 'Yes'; else $visible_pages = 'No';
  if (strstr($x[use_for],'c_')) { $x1 = explode('_',$x[use_for]); $use_for = 'Categories for '.$s[items_types_words][$x1[1]]; }
  else $use_for = $s[items_types_Words][$x[use_for]];
  echo '<tr>
  <td align="left" valign="top" nowrap><span class="text10">'.$x[item_n].'</span></td>
  <td align="left" valign="top" nowrap><span class="text10">'.$use_for.'</span></td>
  <td align="left" valign="top" nowrap><span class="text10">'.$x[rank].'</span></td>
  <td align="left" valign="top" nowrap><a class="link10" href="user_items.php?action=user_item_edit&item_n='.$x[item_n].'">'.$x[description].'</a></td>
  <td align="left" valign="top" nowrap><span class="text10">'.$x[kind].'</span></td>
  <td align="center" valign="top" nowrap><span class="text10">'.$visible_forms.'</span></td>
  <td align="center" valign="top" nowrap><span class="text10">'.$required.'</span></td>
  <td align="center" valign="top" nowrap><span class="text10">'.$visible_pages.'</span></td>
  <td align="center" valign="top" nowrap><a class="link10" href="user_items.php?action=user_item_delete&item_n='.$x[item_n].'" title="Delete this user item">X</a></td>
  </tr>';
}
echo '</table></td></tr></table>';
}

###################################################################################

function user_item_created($in) {
global $s;
$in = form_user_item_control($in);
$q = dq("select search_n from $s[pr]usit_list where use_for = '$in[use_for]' order by search_n");
while ($x=mysql_fetch_assoc($q)) $taken_search_n[] = $x[search_n];
for ($x=1;$x<=60;$x++) if (!in_array($x,$taken_search_n)) { $search_n = $x; break; }
dq("insert into $s[pr]usit_list values(NULL,'$in[use_for]','$search_n','$in[kind]','$in[show_na]','$in[description]','','','$in[rank]','$in[maxlength]','$in[required]','$in[visible_forms]','$in[visible_pages]')",1);
$in[item_n] = mysql_insert_id();
//foreach ($in as $k=>$v) echo "$k - $v<br />";
if (($in[kind]=='text') OR ($in[kind]=='textarea') OR ($in[kind]=='htmlarea')) dq("update $s[pr]usit_list set def_value_text = '$in[def_value]' where item_n = '$in[item_n]'",1);
elseif (($in[kind]=='checkbox') AND ($in[def_value]=='checked')) dq("update $s[pr]usit_list set def_value_code = '1' where item_n = '$in[item_n]'",1);
elseif (($in[kind]=='radio') OR ($in[kind]=='select'))
{ $unknown_ranks = 1000;
  foreach ($in[values_new] as $k=>$v)
  { if ((!$v) AND (!is_numeric($v))) continue;
    if ($in[ranks_new][$k]) $rank = $in[ranks_new][$k]; else { $unknown_ranks++; $rank = $unknown_ranks; }
    dq("insert into $s[pr]usit_avail_val values('$in[item_n]','$in[use_for]',NULL,'$v','$rank')",1);
    if ($v==$in[def_value]) $default_value_number = mysql_insert_id();
  }
  dq("update $s[pr]usit_list set def_value_code = '$default_value_number' where item_n = '$in[item_n]'",1);
}
$s[info] = info_line('New user item has been created','You can view/edit it below.');
$s[back] = ahref('user_items.php?action=user_items_home','Back');
user_item_edit($in);
}

###################################################################################
###################################################################################
###################################################################################

function user_item_edit($in) {
global $s;
$q = dq("select * from $s[pr]usit_list where item_n = '$in[item_n]'",1);
$data = mysql_fetch_assoc($q);
if ($in[action]=='user_item_edit')
{ if (($data[kind]=='checkbox') AND ($data[def_value_code]==1)) $data[def_value] = 'checked';
  elseif (($data[kind]=='text') OR ($data[kind]=='textarea') OR ($data[kind]=='htmlarea')) $data[def_value] = $data[def_value_text];
  elseif (($data[kind]=='radio') OR ($data[kind]=='select'))
  { $q = dq("select * from $s[pr]usit_avail_val where item_n = '$in[item_n]' order by description",1);
    while ($x = mysql_fetch_assoc($q))
    { $data[values] .= "$x[description]\n";
      if ($x[value_code]==$data[def_value_code]) $data[def_value] = $x[description];
    }
  }
}

$in = array_merge((array)$data,(array)$in);
$s[action] = 'user_item_edited';
$in = strip_slashes_array($in);
ih();
echo $s[info];
$s[table_title] = 'Edit Selected User Defined Item';
user_item_create_edit_form($in);

if (!strstr($in[use_for],'c_'))
{ echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td class="common_table_top_cell">HTML code to place this user item to the Advanced Search form (template search.html)</td></tr>
  <tr><td align="center" width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
  <tr><td align="center"><br />';
  
  if ($data[kind]!='checkbox')
  echo 'Code to create a simple text field to enter a value to search.<br />
  <textarea class="field10" style="width:700px;height:150px;">
  <input class="field10" name="usit['.$data[search_n].']" style="width:650px;" maxlength="50">
  </textarea>
  <br /><br />';
  if (($data[kind]=='text') OR ($data[kind]=='textarea'))
  echo 'Code to create fields to search between numeric values.<br />This can be used only for fields that contain exclusively numeric values.<br />
  <textarea class="field10" style="width:700px;height:150px;">From <input class="field10" name="usit_from['.$data[search_n].']" style="width:100px" maxlength="15">
  To <input class="field10" name="usit_to['.$data[search_n].']" style="width:100px" maxlength="15"></textarea>
  <br /><br />';
  
  if (($data[kind]=='radio') OR ($data[kind]=='select'))
  { $q = dq("select * from $s[pr]usit_avail_val where item_n = '$in[item_n]' order by rank",1);
    while ($x = mysql_fetch_assoc($q)) $search_form[$x[value_code]] = $x[description];
    echo 'Code to create a list to choose one of available options.<br />
    <textarea class="field10" style="width:700px;height:150px;">';
    if ($data[kind]=='radio')
    { echo '<input type="radio" name="usit_options['.$data[search_n].']" value="0">Any</option><br />'."\n";
      foreach ($search_form as $k=>$v) echo '<input type="radio" name="usit_options['.$data[search_n].']" value="'.$k.'">'.$v.'</option><br />'."\n";
    }
    elseif ($data[kind]=='select')
    { echo '<select class="select10" name="usit_options['.$data[search_n].']"><option value="0">Any</option>'."\n";
      foreach ($search_form as $k=>$v) echo "<option value=\"$k\">$v</option>\n";
      echo '</select>';
    }
    echo '</textarea><br /><br />';
  }
  if ($data[kind]=='checkbox')
  { echo 'Code to create a list to choose one of available options.<br />
    <textarea class="field10" style="width:700px;height:150px;">';
    echo '<input type="radio" name="usit_options['.$data[search_n].']" value="0">Any</option><br />
<input type="radio" name="usit_options['.$data[search_n].']" value="yes">Yes</option><br />
<input type="radio" name="usit_options['.$data[search_n].']" value="no">No</option><br />';
    echo '</textarea><br /><br />';
  }
}
echo '</td></tr>
</table>
</td></tr></table>
';
echo '<br /><a href="user_items.php?action=user_items_home">Back to list of user items</a>';
ift();
}

###################################################################################

function user_item_edited($in) {
global $s;
$in = form_user_item_control($in);
dq("update $s[pr]usit_list set kind = '$in[kind]', description = '$in[description]', show_na = '$in[show_na]', rank = '$in[rank]', maxlength = '$in[maxlength]', required = '$in[required]', def_value_code = '0', def_value_text = '', visible_forms = '$in[visible_forms]', visible_pages = '$in[visible_pages]' where item_n = '$in[item_n]'",1);
if (($in[kind]!='radio') AND ($in[kind]!='select')) dq("delete from $s[pr]usit_avail_val where item_n = '$in[item_n]'",1);
if (($in[kind]=='text') OR ($in[kind]=='textarea') OR ($in[kind]=='htmlarea')) dq("update $s[pr]usit_list set def_value_text = '$in[def_value]' where item_n = '$in[item_n]'",1);
elseif (($in[kind]=='checkbox') AND ($in[def_value]=='checked')) dq("update $s[pr]usit_list set def_value_code = '1' where item_n = '$in[item_n]'",1);
elseif (($in[kind]=='radio') OR ($in[kind]=='select'))
{ dq("delete from $s[pr]usit_avail_val where item_n = '$in[item_n]'",1);
  $unknown_ranks = 1000;
  foreach ($in[values] as $k=>$v)
  { if ((!$v) AND (!is_numeric($v))) continue;
    if ($in[ranks][$k]) $rank = $in[ranks][$k]; else { $unknown_ranks++; $rank = $unknown_ranks; }
    dq("insert into $s[pr]usit_avail_val values('$in[item_n]','$in[use_for]','$k','$v','$rank')",1);
    $dont_delete_a[] = $k;
  }
  foreach ($in[values_new] as $k=>$v)
  { if ((!$v) AND (!is_numeric($v))) continue;
    if ($in[ranks_new][$k]) $rank = $in[ranks_new][$k]; else { $unknown_ranks++; $rank = $unknown_ranks; }
    dq("insert into $s[pr]usit_avail_val values('$in[item_n]','$in[use_for]',NULL,'$v','$rank')",1);
  }
  $q = dq("select value_code from $s[pr]usit_avail_val where item_n = '$in[item_n]' AND description = '$in[def_value]'",1);
  $x = mysql_fetch_row($q);
  dq("update $s[pr]usit_list set def_value_code = '$x[0]' where item_n = '$in[item_n]'",1);
}
$s[info] = info_line('Your changes have been saved');
user_item_edit($in);
}

###################################################################################
###################################################################################
###################################################################################

function user_item_create_edit_form_options_part($in) {
global $s;
$a = '<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td align="left"><span class="text10">Rank</td>
<td align="center"><span class="text10">Value</td>
</tr>';

$q = dq("select * from $s[pr]usit_avail_val where item_n = '$in[item_n]' order by rank",1);
while ($x = mysql_fetch_assoc($q))
{ $a .= '<tr>
  <td align="left"><input class="field10" style="width:100px" name="ranks['.$x[value_code].']" maxlength="100" value="'.$x[rank].'"></td>
  <td align="center"><input class="field10" style="width:450px" name="values['.$x[value_code].']" maxlength="255" value="'.$x[description].'"></td>
  </tr>';
  if ($biggest_rank<$x[rank]) $biggest_rank = $x[rank];
}
for ($x=1;$x<=10;$x++)
{ $rank = $biggest_rank + $x;
  $a .= '<tr>
  <td align="left"><input class="field10" style="width:100px" name="ranks_new[]" maxlength="100" value="'.$rank.'"></td>
  <td align="center"><input class="field10" style="width:450px" name="values_new[]" maxlength="255" value=""></td>
  </tr>';
}
$a .= '</table>';
return $a;
}

###################################################################################

function user_item_create_edit_form($in) {
global $s;

$in = replace_array_text($in);
$$in[kind] = ' selected';
$where_use[$in[use_for]] = ' selected';
if ($in[required]) $required = ' checked';
if ($in[show_na]) $show_na = ' checked';
if ((!$in[item_n]) OR ($in[visible_forms])) $visible_forms = ' checked';
if ((!$in[item_n]) OR ($in[visible_pages])) $visible_pages = ' checked';
if ($s[action]=='user_item_edited')
{ if (strstr($in[use_for],'c_')) { $x1 = explode('_',$in[use_for]); $use_for = 'Categories for '.$s[items_types_words][$x1[1]]; }
  else $use_for = $s[items_types_Words][$in[use_for]];
}
else
{ $use_for = '<select class="select10" name="use_for">';
  foreach ($s[items_types_Words] as $k => $v) $use_for .= '<option value="'.$k.'"'.$where_use[$k].'>'.$v.'</option>';
  foreach ($s[items_types_Words] as $k => $v) $use_for .= '<option value="c_'.$k.'"'.$where_use[$k].'>Categories for '.strtolower($v).'</option>';
  $use_for .= '</select>';
}
echo '<form action="user_items.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="'.$s[action].'">
<input type="hidden" name="item_n" value="'.$in[item_n].'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">'.$s[table_title].'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" nowrap>Description</td>
<td align="left"><input class="field10" style="width:650px;" name="description" maxlength="255" value="'.$in[description].'"></td></tr>
<tr><td align="left" nowrap>Use for </td>
<td align="left">'.$use_for.'</td></tr>
<tr><td align="left" nowrap>Type</td>
<td align="left"><select class="select10" name="kind">'.
"<option value=\"text\"$text>Text field - one line</option>
<option value=\"textarea\"$textarea>Textarea</option>
<option value=\"htmlarea\"$htmlarea>HTML area</option>
<option value=\"checkbox\"$checkbox>Checkbox</option>
<option value=\"radio\"$radio>Radio</option>
<option value=\"select\"$select>Select</option>".'</select></td></tr>
<tr><td align="left" nowrap>Maximum length<br /><span class="text10">For fields Text and Textarea only</span></td>
<td align="left"><input class="field10" style="width:100px" name="maxlength" maxlength="10" value="'.$in[maxlength].'"><span class="text10"> Maximum allowed size for field Text is 255 characters</span></td></tr>
<tr>
<td align="left" valign="top" nowrap>Values<br /><span class="text10">If you have selected as type<br /><b>Radio</b> or <b>Select Box</b>, enter<br />its values here.</span></td>
<td align="left">'.user_item_create_edit_form_options_part($in).'
</td>
</tr>';
if (($in[kind]=='select') OR ($in[kind]=='multiselect') OR ($in[kind]=='radio')) echo '<tr>
<td align="left" nowrap>Show N/A option </td>
<td align="left"><input type="checkbox" name="show_na" value="1"'.$show_na.'></td>
</tr>';
echo '<tr>
<td align="left" nowrap>Default value</td>
<td align="left"><input class="field10" style="width:650px;" name="def_value" maxlength="255" value="'.$in[def_value].'"></td>
</tr>
<tr>
<td align="left" nowrap>Rank in form</td>
<td align="left"><input class="field10" style="width:100px" name="rank" maxlength="255" value="'.$in[rank].'"></td>
</tr>
<tr>
<td align="left" nowrap>Visible in submit/edit forms</td>
<td align="left"><input type="checkbox" name="visible_forms" value="1"'.$visible_forms.'></td>
</tr>
<tr>
<td align="left" nowrap>Required</td>
<td align="left"><input type="checkbox" name="required" value="1"'.$required.'></td>
</tr>
<tr>
<td align="left" nowrap>Visible on public pages </td>
<td align="left"><input type="checkbox" name="visible_pages" value="1"'.$visible_pages.'></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="button10"></td>
</tr>
</table></td></tr></table></form>
<br /><br />';
}

###################################################################################

function form_user_item_control($in) {
global $s;
$in = replace_array_text($in);
if (!$in[description]) user_item_error($in,'Description is missing');
if (!$in[rank]) user_item_error($in,'Rank is missing');
if ($in[item_n])
{ $q = dq("select rank,use_for from $s[pr]usit_list where item_n = '$in[item_n]'",1);
  $x = mysql_fetch_row($q);
  $in[use_for] = $x[1];
}
if ($in[rank]!=$x[0]) // splneno pokud je novy nebo pokud je editovany a ma zmeneny rank
{ $q = dq("select count(*) from $s[pr]usit_list where rank = '$in[rank]' AND use_for = '$in[use_for]'",1);
  $x = mysql_fetch_row($q); if ($x[0]) user_item_error($in,'Entered rank is already in use');
}
if (($in[kind]=='radio') OR ($in[kind]=='select'))
{ $in[values] = replace_array_text($in[values]);
  $in[values_new] = replace_array_text($in[values_new]);
  if ((count($in[values_new])+count($in[values]))<2) user_item_error($in,'Number values for field radio or select cannot be lower than 2.');
  if ((!$in[def_value]) AND (!is_numeric($in[def_value]))) user_item_error($in,'Default value is missing');
  elseif ((!in_array($in[def_value],$in[values])) AND (!in_array($in[def_value],$in[values_new]))) user_item_error($in,'Default value must be one of the values available in field "Values"');
}
else
{ unset($in[values]);
  if (($in[kind]=='checkbox') AND ($in[def_value]) AND ($in[def_value]!='checked'))
  user_item_error($in,'The only allowed default value for checkbox is "checked".<br />To have it unchecked let the field blank.');
  if (($in[kind]=='text') AND (!$in[maxlength])) $in[maxlength] = 255;
}
return $in;
}

###################################################################################

function user_item_error($in,$error) {
global $s;
$s[info] = info_line($error);
$in = strip_slashes_array($in);
if ($in[item_n]) user_item_edit($in);
else user_items_home($in);
}

###################################################################################
###################################################################################
###################################################################################

function user_item_delete($in) {
global $s;
$q = dq("select description from $s[pr]usit_list where item_n = '$in[item_n]'",1);
$x = mysql_fetch_assoc($q);
ih();
echo '<form action="user_items.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="user_item_deleted">
<input type="hidden" name="item_n" value="'.$in[item_n].'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">
<span class="text13a_bold">User Defined Item "'.stripslashes($x[description]).'" will be deleted<br />
It also deletes values of this item in all links/articles</span><br /><br />
Are you sure?<br /><br />
<input type="submit" name="submit" value="Yes, delete it now" class="button10">
</td></tr>
</table></td></tr></table></form>';
ift();
}

###################################################################################

function user_item_deleted($in) {
global $s;
dq("delete from $s[pr]usit_list where item_n = '$in[item_n]'",1);
dq("delete from $s[pr]usit_avail_val where item_n = '$in[item_n]'",1);
dq("delete from $s[pr]usit_values where item_n = '$in[item_n]'",1);
$s[info] = info_line('Selected item has been deleted');
user_items_home();
}

###################################################################################
###################################################################################
###################################################################################

function strip_slashes_array($in) {
if (!is_array($in)) return $in;
foreach ($in as $k=>$v) $in[$k] = stripslashes($v);
return $in;
}

?>