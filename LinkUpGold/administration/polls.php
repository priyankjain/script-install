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
check_admin('polls');

switch ($_GET[action]) {
case 'poll_edit'			: poll_edit($_GET[n]);
case 'poll_reset'			: poll_reset($_GET[n]);
case 'poll_delete'			: poll_delete($_GET[n]);
case 'poll_manage'			: poll_manage($_GET);
}
switch ($_POST[action]) {
case 'poll_edited'			: poll_edited($_POST);
case 'poll_added'			: poll_added($_POST);
}
polls_home();

#################################################################################
#################################################################################
#################################################################################

function polls_home() {
global $s;
$q = dq("select n,question,active from $s[pr]polls order by question",1);
while ($a = mysql_fetch_row($q))
{ if ($a[2]) $x1 = '<font color="green">';
  else $x1 = '<font color="red">';
  $polls .= "<input type=\"radio\" name=\"n\" value=\"$a[0]\">$x1 $a[1] (#$a[0])</font><br />";
}
$polls = stripslashes($polls);
ih();
echo $s[info];
echo page_title('Polls');
?>
<form action="polls.php" method="post" name="form1"><?PHP echo check_field_create('admin') ?>
<input type="hidden" name="action" value="poll_added">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Create A New Poll</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" nowrap>Question</td>
<td align="left"><input class="field10" name="question" style="width:650px;" maxlength=255></td></tr>
<tr><td align="left" nowrap>Answer 1&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a1" style="width:650px;" maxlength=255></td></tr>
<tr><td align="left" nowrap>Answer 2&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a2" style="width:650px;" maxlength=255></td></tr>
<tr><td align="left" nowrap>Answer 3&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a3" style="width:650px;" maxlength=255></td></tr>
<tr><td align="left" nowrap>Answer 4&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a4" style="width:650px;" maxlength=255></td></tr>
<tr><td align="left" nowrap>Answer 5&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a5" style="width:650px;" maxlength=255></td></tr>
<tr><td align="left" nowrap>Active&nbsp;&nbsp;</td>
<td align="left"><input type="checkbox" name="enabled"></td></tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table>
</td></tr></table></form>

<br />

<form action="polls.php" method="get" name="form1">
<input type="hidden" name="action" value="poll_edit">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">View & Edit & Delete An Existing Poll</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center" nowrap><font color="green">Green polls are enabled</font> <font color="red">Red polls are disabled</font><br /><br /></td></tr>
<tr><td align="left"><?PHP echo $polls; ?><br /></td></tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table>
</td></tr></table></form><br />
<?PHP
ift();
}

#################################################################################

function poll_added($form) {
global $s;
$form = replace_array_text($form);
$form[question] = refund_html($form[question]);
if ((!$form[question]) OR (!$form[a1]) OR (!$form[a2]))
problem ("Question and the first 2 answers are required. Please try again.");
if ($form[enabled]) $enabled = 1; else $enabled = 0;
foreach ($form as $k => $v) $form[$k] = replace_once_html($v);
dq("insert into $s[pr]polls values(NULL,'$form[question]','$form[a1]','$form[a2]','$form[a3]','$form[a4]','$form[a5]',0,0,0,0,0,0,0,0,0,0,0,'$enabled','$s[cas]')",1);
$s[info] = info_line('A new poll has been created');
polls_home();
exit;
}

#################################################################################

function poll_edit($n) {
global $s;
ih();
$q = dq("select * from $s[pr]polls where n='$n'",1);
$data = mysql_fetch_array($q);
$data = stripslashes_array($data);
if ($data[active]) { $manage = 'Disable'; $enable = 0; $active = 'active'; }
else { $manage = 'Enable'; $enable = 1; $active = 'not active'; }
for ($x=1;$x<=5;$x++)
{ if ($data["a$x"]) $results .= $data["a$x"].' - '.$data["n$x"].' votes<br />'; }

echo $s[info];
echo '<form action="polls.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="poll_edited">
<input type="hidden" name="n" value="'.$n.'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Edit Selected Poll</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center" colspan=2>This poll is '.$active.'</td></tr>
<tr><td align="left" nowrap>Question</td>
<td align="left"><input class="field10" name="question" style="width:650px;" maxlength=255 value="'.$data[question].'"></td></tr>
<tr><td align="left" nowrap>Answer 1&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a1" style="width:650px;" maxlength=255 value="'.$data[a1].'"></td></tr>
<tr><td align="left" nowrap>Answer 2&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a2" style="width:650px;" maxlength=255 value="'.$data[a2].'"></td></tr>
<tr><td align="left" nowrap>Answer 3&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a3" style="width:650px;" maxlength=255 value="'.$data[a3].'"></td></tr>
<tr><td align="left" nowrap>Answer 4&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a4" style="width:650px;" maxlength=255 value="'.$data[a4].'"></td></tr>
<tr><td align="left" nowrap>Answer 5&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="a5" style="width:650px;" maxlength=255 value="'.$data[a5].'"></td></tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></form><br />
[<a target="_self" href="javascript: go_to_delete(\'Are you sure?\',\'polls.php?action=poll_delete&n='.$n.'\')">Delete this poll</a>]&nbsp;&nbsp;
[<a href="polls.php?action=poll_manage&enable='.$enable.'&n='.$n.'">'.$manage.' this poll</a>]
</td></tr></table><br />
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Results</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center"><span class="text13a_bold">'.$data[question].'</span></td></tr>
<tr><td align="center">'.$results.'</td></tr>
</table><br />
<a href="polls.php?action=poll_reset&n='.$n.'">Reset all results of this poll to zero</a>
</td></tr></table><br />';
ift();
}

#################################################################################

function poll_edited($form) {
global $s;
$form = replace_array_text($form);
$form[question] = refund_html($form[question]);
if ((!$form[question]) OR (!$form[a1]) OR (!$form[a2]))
{ $s[info] = "<font color=\"red\"><b>Question and the first 2 answers are required. Please try again.</b></font><br /><br />";
  poll_edit($form[n]); exit; }
$q = dq("select n from $s[pr]polls where n = '$form[n]'",1);
$result = mysql_fetch_row($q);
if (!$result[0]) problem("Poll which you want to edit does not exist.");
foreach ($form as $k => $v) $form[$k] = replace_once_html($v);
dq("update $s[pr]polls set question = '$form[question]', a1 = '$form[a1]', a2 = '$form[a2]', a3 = '$form[a3]', a4 = '$form[a4]', a5 = '$form[a5]' where n = '$form[n]'",1);
$s[info] = info_line('Selected poll has been updated');
poll_edit($form[n]);
exit;
}

#################################################################################

function poll_manage($data) {
global $s;
$q = dq("select n from $s[pr]polls where n = '$data[n]'",1);
$result = mysql_fetch_row($q);
if (!$result[0]) problem('Selected poll does not exist.');
dq("update $s[pr]polls set active = '$data[enable]' where n = '$data[n]'",1);
poll_edit($data[n]);
exit;
}

#################################################################################

function poll_reset($n) {
global $s;

$q = dq("select n from $s[pr]polls where n = '$n'",1);
$result = mysql_fetch_row($q);
if (!$result[0]) problem("Selected poll does not exist.");
dq("update $s[pr]polls set n1=0,n2=0,n3=0,n4=0,n5=0,p1=0,p2=0,p3=0,p4=0,p5=0,votes=0 where n = '$n'",1);
$s[info] = info_line('All results of the selected poll have been reseted');
poll_edit($n);
exit;
}

#################################################################################

function poll_delete($n) {
global $s;
$q = dq("select n from $s[pr]polls where n = '$n'",1);
$result = mysql_fetch_row($q);
if (!$result[0]) problem('Selected poll does not exist');
dq("delete from $s[pr]polls where n = '$n'",1);
$s[info] = info_line('Selected poll has been deleted');
polls_home();
exit;
}

#################################################################################
#################################################################################
#################################################################################

?>