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
check_admin('ads');

switch ($_GET[action]) {
case 'ads_home'				: ads_home(0);
case 'ad_copy'				: ads_home($_GET);
case 'ad_delete'			: ad_delete($_GET[n]);
case 'ad_edit'				: ad_edit($_GET[n]);
}

switch ($_POST[action]) {
case 'ad_created'			: ad_created($_POST);
case 'ad_edited'			: ad_edited($_POST);
}
ads_home();

#################################################################################
#################################################################################
#################################################################################

function ads_home($form) {
global $s;
$s[ads] = select_ads(0);
if (!$form) $form=array('');
if ($form[n])
{ $q = dq("select * from $s[pr]ads where n='$form[n]'",1);
  $form = mysql_fetch_array($q);
  $form[html] = htmlspecialchars(unreplace_once_html($form[html]));
}
ih();
echo $s[info];
echo info_line('Ads','Create/edit your ads here. All these ads will be available in settings of categories,<br />you can select 1-3 of them to display on pages of each category.').
'<form action="ads.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="ad_created">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan=2>Create a new ad</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" nowrap>Name<br /><span class="text10">(for your reference)</span></td>
<td><input class="field10" style="width:650px;" name="title" maxlength=100 value="'.$form[title].'"></td></tr>
<tr><td align="left" nowrap>HTML</td>
<td><textarea class="field10" name="html" style="width:650px;height:250px;">'.$form[html].'</textarea></td></tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Save" class="button10"></td></tr>
</table></td></tr></table></form>

<br />
<form action="ads.php" method="get" name="form1">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan=2>Edit/delete an existing ad</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center"><select class="select10" name="n">'.$s[ads].'</select></td></tr>
<tr><td align="center">Action: 
Edit<input type="radio" name="action" value="ad_edit" checked>
Copy<input type="radio" name="action" value="ad_copy">
Delete<input type="radio" name="action" value="ad_delete">
</td></tr>
<tr><td align="center"><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form>
';
ift();
}

#################################################################################

function ad_created($in) {
global $s;
$user = $_SESSION[admuser];
$in[title] = replace_once_text($in[title]);
if ((!$in[title]) OR (!$in[html]))
{ $in[html] = stripslashes($in[html]);
  $s[info] = info_line('Both fields are required');
  ads_home($in);
}
$in[html] = replace_once_html($in[html]);
dq("insert into $s[pr]ads values (NULL,'$in[title]','$in[html]','$s[cas]','$user')",1);
$s[info] = info_line('New ad "'.$in[title].'" has been created.');
ads_home(0);
exit;
}

#################################################################################

function ad_edit($n) {
global $s;
ih();
$q = dq("select * from $s[pr]ads where n='$n'",1);
$data = mysql_fetch_array($q);
$data[html] = htmlspecialchars(unreplace_once_html($data[html]));
echo $s[info].
'<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan=2>Edit Selected Ad</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">
<form action="ads.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="ad_edited">
<input type="hidden" name="n" value="'.$data[n].'">
<tr><td align="left" nowrap>Name<br /><span class="text10">(for your reference)</span></td>
<td><input class="field10" style="width:650px;" name="title" maxlength=100 value="'.$data[title].'"></td></tr>
<tr><td align="left" nowrap>HTML</td>
<td><textarea class="field10" name="html" style="width:650px;height:250px;">'.$data[html].'</textarea></td></tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Save" class="button10"></td></tr>
</table></td></tr></table></form>
<br />
<a href="ads.php">Back to ads home</a>';
ift();
}

#################################################################################

function ad_edited($in) {
global $s;
$user = $_SESSION[admuser];
$in[title] = replace_once_text($in[title]);
if ((!$in[title]) OR (!$in[html]))
{ $s[info] = info_line('Both fields are required'); ad_edit($in[n]); }
$q = dq("select * from $s[pr]ads where n = $in[n]",1);
$result = mysql_fetch_array($q);
if (!$result[title]) problem('Ad which you want to edit does not exist.');
$in[html] = replace_once_html($in[html]);
dq("update $s[pr]ads set title = '$in[title]', html = '$in[html]', edited = '$s[cas]', edited_by = '$user' where n = $in[n]",1);
dq("update $s[pr]cats set ad1 = '$in[html]' where ad1n = '$in[n]'",1);
dq("update $s[pr]cats set ad2 = '$in[html]' where ad2n = '$in[n]'",1);
dq("update $s[pr]cats set ad3 = '$in[html]' where ad3n = '$in[n]'",1);
$s[info] = info_line('Ad "'.$in[title].'" Has Been Updated.');
ad_edit($in[n]);
}

#################################################################################

function ad_delete($n) {
global $s;
dq("delete from $s[pr]ads where n = '$n'",1);
dq("update $s[pr]cats set ad1 = '' where ad1n = '$n'",1);
dq("update $s[pr]cats set ad2 = '' where ad2n = '$n'",1);
dq("update $s[pr]cats set ad3 = '' where ad3n = '$n'",1);
$s[info] = info_line('Selected Ad Has Been Deleted.');
ads_home(0);
}

#################################################################################
#################################################################################
#################################################################################

?>