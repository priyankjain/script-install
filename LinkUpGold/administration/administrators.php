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
check_admin('admins');

switch ($_POST[action]) {
case 'admin_created'		: admin_created($_POST);
case 'admin_edited'			: admin_edited($_POST);
case 'admin_edited_cats'	: admin_edited_cats($_POST);
case 'admin_delete'			: admin_delete($_POST);
}
switch ($_GET[action]) {
case 'admin_edit'			: admin_edit($_GET);
case 'admin_delete'			: admin_delete($_GET);
}
admins_home();

##################################################################################
##################################################################################
##################################################################################

function admins_home($in) {
global $s;
ih();
echo $s[info];
echo page_title('Administrators');
$in[action] = 'admin_created';
$in[head] = 'Create A New Administrator';
admin_create_edit_form($in);
echo '<form action="administrators.php" method="get">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" colspan="2" class="common_table_top_cell">Existing Administrators</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align=center><select class="select10" name="n">'.select_admins().'</select></td></tr>
<tr><td align="center">Action: <input type="radio" name="action" value="admin_edit" checked>Edit&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="action" value="admin_delete">Delete</td></tr>
<tr><td align=center><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form>';
ift();
}

#################################################################################

function admin_created($in) {
global $s;
if ((!$in[username]) OR (!$in[password]) OR (!$in[email])) $problem[] = 'Some of required fields left blank.';
if (((strlen($in[username])) < 6) OR ((strlen($in[username])) > 15)) $problem[] = 'Username must be 6-15 characters long.';
if (((strlen($in[password])) < 6) OR ((strlen($in[password])) > 15)) $problem[] = 'Password must be 6-15 characters long.';
$in[name] = replace_once_text($in[name]);
if ($problem)
{ $s[info] = info_line('One or more errors found. Please try again.',implode('<br />',$problem));
  admins_home($in);
}
$in[password] = md5($in[password]);
dq("insert into $s[pr]admins values (NULL,'$in[username]','$in[password]','$in[email]','$in[name]','0')",1);
$n = mysql_insert_id();
foreach ($in[rights] as $k=>$v) dq("insert into $s[pr]admins_rights values ('$n','$v')",1);
$s[info] = info_line('New administrator has been created.');
admins_home();
}

#################################################################################

function admin_edit($in) {
global $s;
$in[action] = 'admin_edited';
$in[head] = 'Edit Selected Administrator';
ih();
echo $s[info];
admin_create_edit_form($in);

$q = dq("select * from $s[pr]admins where n = '$in[n]'",1); $user = mysql_fetch_assoc($q);
$q = dq("select * from $s[pr]admins_rights where n = '$in[n]'",1); while ($x = mysql_fetch_assoc($q)) $rights[] = $x[action];
$q = dq("select * from $s[pr]admins_cats where n = '$in[n]'",1); while ($x = mysql_fetch_assoc($q)) $cats_rights[] = $x[category];

foreach ($s[item_types_short] as $k=>$what)
{ $word = $s[items_types_words][$what];
  $Word = $s[items_types_Words][$what];
  if ((in_array('all_'.$word,$rights)) OR (!in_array($word,$rights))) continue;
  echo '<form action="administrators.php" method="post">'.check_field_create('admin').'
  <input type="hidden" name="action" value="admin_edited_cats">
  <input type="hidden" name="n" value="'.$user[n].'">
  <input type="hidden" name="what" value="'.$what.'">
  <table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td colspan=2 class="common_table_top_cell">Categories of '.$Word.'</td></tr>
  <tr><td align="center" width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
  <tr><td align="left" colspan=2>
  <span class="text10">Select the categories where this admin can manage '.$word.'.<br />
  Administrators of individual categories have access to those '.$word.' that are listed in a single category only, not to those '.$word.' that are listed in multiple categories. These administrators also can\'t use the feature to edit/manage multiple '.$word.' at the same time.<br /></span></td></tr>';
  $q = dq("select * from $s[pr]cats where use_for = '$what' order by name",1);
  while ($x=mysql_fetch_assoc($q))
  { if (in_array($x[n],$cats_rights)) $checked=' checked'; else $checked = '';
    echo '<tr>
    <td align="left" width="20"><input type="checkbox" name="cats[]" value="'.$x[n].'"'.$checked.'></td>
    <td align="left">'.$x[name].'</td>
    </tr>';
  }
  echo '<tr><td align="center" colspan=2><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
  </table></td></tr></table><br /></form>';
}
echo '<a href="administrators.php?action=admins_home">Back to previous page</a><br /><br />';
ift();
}

#################################################################################

function admin_create_edit_form($in) {
global $s;
if ($in[action]=='admin_created') { $user = $in; $rights = $in[rights]; }
else
{ $q = dq("select * from $s[pr]admins where n = '$in[n]'",1); $user = mysql_fetch_assoc($q);
  $q = dq("select * from $s[pr]admins_rights where n = '$in[n]'",1); while ($x = mysql_fetch_assoc($q)) $rights[] = $x[action];
}
$all_rights = array(
'links'=>'Add/view/edit/delete links',
'all_links'=>'Add/view/edit/delete links of all categories, can import links',
'categories_links'=>'Add/view/edit/delete categories',
'articles'=>'Add/view/edit/delete articles',
'all_articles'=>'Add/view/edit/delete articles of all categories',
'categories_articles'=>'Add/view/edit/delete categories for articles',
'blogs'=>'Add/view/edit/delete blogs',
'all_blogs'=>'Add/view/edit/delete blogs of all categories',
'categories_blogs'=>'Add/view/edit/delete categories for blogs',
'videos'=>'Add/view/edit/delete videos',
'all_videos'=>'Add/view/edit/delete videos of all categories',
'categories_videos'=>'Add/view/edit/delete categories for videos',
'news'=>'Add/view/edit/delete news',
'all_news'=>'Add/view/edit/delete news of all categories',
'categories_news'=>'Add/view/edit/delete categories for news',
'email_owners'=>'Can email owners of links, articles, blogs',
'blacklist'=>'View/edit blacklist',
'polls'=>'Add/view/edit/delete polls',
'users'=>'View/edit/delete registered users',
'email_users'=>'Can email users',
'newsletter'=>'Can send newsletters',
'board'=>'View/edit/delete messages on the Board',
'search_log'=>'View/delete search log',
'ads'=>'Add/view/edit/delete ads',
'adlinks'=>'Add/view/edit/delete AdLinks',
'site_news'=>'Add/edit/delete site news',
'messages'=>'Edit/translate messages',
'templates'=>'Edit/translate templates',
'adv_prices_orders'=>'Edit prices for advertising links, view/edit orders',
'admins'=>'Add/view/edit/delete admins',
'database_tools'=>'Access to Database tools',
'configuration'=>'View/edit Configuration',
'reset_rebuild'=>'Reset stats/rebuild pages');
echo '<form action="administrators.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="'.$in[action].'">
<input type="hidden" name="n" value="'.$user[n].'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" nowrap>'.$in[head].'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
if ($in[action]=='admin_created')
echo '<tr>
<td align="left" nowrap>Username&nbsp;&nbsp;</td>
<td align="left" colspan=2><input class="field10" style="width:100px" name="username" value="'.$user[username].'" maxlength=15></td>
</tr>
<tr>
<td align="left" nowrap>Password&nbsp;&nbsp;</td>
<td align="left" colspan=2><input class="field10" style="width:100px" name="password" maxlength=15></td>
</tr>';
else echo '<tr>
<td align="left" nowrap>Username&nbsp;&nbsp;</td>
<td align="left" colspan=2>'.$user[username].'</td>
</tr>
<tr>
<td align="left" nowrap>Password&nbsp;&nbsp;</td>
<td align="left" colspan=2><input class="field10" style="width:100px" name="password" maxlength=15> <span class="text10">Leave it blank if you don\'t want to change it</td>
</tr>';
echo '<tr>
<td align="left" nowrap>Email&nbsp;&nbsp;</td>
<td align="left" colspan=2><input class="field10" style="width:650px;" name="email" maxlength="255" value="'.$user[email].'"></td>
</tr>
<tr>
<td align="left" nowrap>Name&nbsp;&nbsp;</td>
<td align="left" colspan=2><input class="field10" style="width:650px;" name="name" maxlength="255" value="'.$user[name].'"></td>
</tr>
<tr>
<td align="left" valign="top">Privilegies<br /><span class="text10">This administrator<br />can view/edit<br />these items</span></td>
<td align="left" nowrap>';
foreach ($all_rights as $k=>$v)
{ echo '<input type="checkbox" name="rights[]" value="'.$k.'"';
  if (in_array($k,$rights)) echo ' checked';
  echo '>'.$v.'<br />';
}
echo '</td></tr>
<tr><td align="center" colspan=3><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form><br />';
}

#################################################################################

function admin_edited($in) {
global $s;
if (!$in[email]) $problem[] = 'Email left blank';
if ($in[password])
{ if ( ((strlen($in[password])) < 6) OR ((strlen($in[password])) > 15) ) $problem[] = 'Password must be 6-15 characters long.';
  $in[password] = md5($in[password]); 
  $password = " ,password='$in[password]'";
}
$in[name] = replace_once_text($in[name]);
if ($problem)
{ $s[info] = info_line('One or more errors found. Please try again.',implode('<br />',$problem));
  $in[admin] = $in[n]; admin_edit($in);
}
dq("update $s[pr]admins set email = '$in[email]' $password, name = '$in[name]' where n = '$in[n]'",1);
dq("delete from $s[pr]admins_rights where n = '$in[n]'",1);
foreach ($in[rights] as $k=>$v) dq("insert into $s[pr]admins_rights values ('$in[n]','$v')",1);
$s[info] = info_line('Administrator has been edited');
$in[admin] = $in[n];
admin_edit($in);
}

#################################################################################

function admin_edited_cats($in) {
global $s;
dq("delete from $s[pr]admins_cats where n = '$in[n]' and what = '$in[what]'",1);
foreach ($in[cats] as $k => $v) 
{ $x = $in[cats][$k];
  dq("insert into $s[pr]admins_cats values('$in[what]','$x','$in[n]')",1);
}
$in[info] = info_line('Selected administrator has been edited');
admin_edit($in);
}

#################################################################################

function admin_delete($in) {
global $s;
$q = dq("select username from $s[pr]admins where n = '$in[n]'",1); $user = mysql_fetch_assoc($q);
if (($_SESSION[LUG_admin_user]==$user[username]) OR ($_COOKIE[LUG_admin_user]==$user[username])) problem('You can not delete your account');
if (!$in[ok])
{ ih();
  echo '<br /><table border=0 width=500 cellspacing=10 cellpadding=2 class="common_table">
  <form action="administrators.php" method="post">'.check_field_create('admin').'
  <input type="hidden" name="action" value="admin_delete">
  <input type="hidden" name="ok" value="1">
  <input type="hidden" name="n" value="'.$in[n].'">
  <tr><td align="center" nowrap><span class="text13a_bold">You are about to delete administrator '.$user[username].'. Are you sure?</span></td></tr>
  <tr><td align="center"><input type="submit" name="submit" value="Yes, delete this administrator" class="button10"></td></tr>
  </form></table>';
  ift();
}
dq("delete from $s[pr]admins where n = '$in[n]'",1);
dq("delete from $s[pr]admins_rights where n = '$in[n]'",1);
dq("delete from $s[pr]admins_cats where n = '$in[n]'",1);
$s[info] = info_line('Selected administrator has been deleted');
admins_home();
}

#################################################################################

function select_admins() {
global $s;
$q = dq("select * from $s[pr]admins order by username",1);
while ($a=mysql_fetch_assoc($q)) $x .= '<option value="'.$a[n].'">'.$a[username].'</option>';
return $x;
}

##################################################################################
##################################################################################
##################################################################################

?>