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

include_once('./common.php');
check_admin('board');

switch ($_GET[action]) {
case 'comments_view'			: comments_view($_GET);
case 'comment_edit'				: comment_edit($_GET[n]);
case 'comment_delete'			: comment_delete($_GET[n]);
case 'comments_updated_multiple': comments_updated_multiple($_GET);
}
switch ($_POST[action]) {
case 'comments_unapproved_show' : comments_unapproved_show($_POST);
case 'comment_approved'			: comment_approved($_POST);
case 'comment_edited'			: comment_edited($_POST);
}

##################################################################################
##################################################################################
##################################################################################

function comments_unapproved_show($in) {
global $s;
if (!$in[from]) $from = 0; else $from = $in[from] - 1;
$q = dq("select count(*) from $s[pr]comments where what = '$in[what]' AND approved = '0'",1);
$pocet = mysql_fetch_row($q);
if (!$pocet[0]) { ih(); echo info_line('<b>No one comment in the queue'); ift(); }

$show[0] = $from + 1;
$show[1] = $from + $in[perpage]; if ($show[1]>$pocet[0]) $show[1] = $pocet[0]; if (!$in[perpage]) $show[1] = $pocet[0];
if (($in[perpage]) AND ($pocet[0]>$in[perpage]))
{ $rozcesti = '<form action="comments.php" method="post" name="form1">'.check_field_create('admin').'
  <input type="hidden" name="action" value="comments_unapproved_show">
  <input type="hidden" name="perpage" value="'.$in[perpage].'">
  <input type="hidden" name="what" value="'.$in[what].'">
  Show comments with begin of&nbsp;&nbsp;<select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($pocet[0]/$in[perpage]);  
  for ($x=1;$x<$y;$x++)
  { $od = $x*$in[perpage]+1;
    $rozcesti .= '<option value="'.$od.'">'.$od.'</option>';
  }
  $rozcesti .= '</select>&nbsp;&nbsp;<input type="submit" value="Submit" name="B1" class="button10"></form><br />';
}
if ($in[perpage]) $limit = " limit $from,$in[perpage]";
$q = dq("select * from $s[pr]comments where what = '$in[what]' AND approved = '0' order by n $limit",1);

ih();
echo $s[info].'<span class="text13a_bold">'.$pocet[0].' comments in the queue';
if (($show[0]) AND ($show[1])) echo ", showing comments $show[0] - $show[1]";
echo '</span><br /><br />'.$rozcesti.'<form action="comments.php" method="post" name="muj">'.check_field_create('admin').'
<input type="hidden" name="action" value="comment_approved">
<input type="hidden" name="what" value="'.$in[what].'">
<input type="hidden" name="perpage" value="'.$in[perpage].'">
<input type="hidden" name="from" value="'.$from.'">';
while ($comment = mysql_fetch_assoc($q))
{ $comment = stripslashes_array($comment);
  echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td align="center" width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
  <input type="hidden" name="n[]" value="'.$comment[n].'">
  <tr><td align="left" colspan=2 nowrap>
  Approve it <input type="radio" name="approve['.$comment[n].']" value="yes" id="approve_'.$comment[n].'">&nbsp;&nbsp;&nbsp;
  Reject it <input type="radio" name="approve['.$comment[n].']" value="no" id="reject_'.$comment[n].'">&nbsp;&nbsp;&nbsp;
  <a class="link10" href="#" onClick="uncheck_both('.$comment[n].'); return false;">Uncheck these boxes</a>
  </td></tr>'.
  get_item_info($in[what],$comment[item_no])
  .'<tr><td align="left" width="100">Comment</td>
  <td align="left" width="300">'.$comment[text].'</td></tr>
  </table></td></tr></table><br />';
}
  
echo '<input type="submit" name="co" value="Save" class="button10"></form>';
ift();
}

######################################################################################

function comment_approved($in) {
global $s;
while (list($k,$v) = each($in)) $$k = $v;
foreach ($n as $key => $c)
{ if (!$approve[$c]) continue;
  $q = dq("select * from $s[pr]comments where n = '$c'",1);
  $x = mysql_fetch_assoc($q);
  if ($approve[$c]=='yes')
  { dq("update $s[pr]comments set approved = '1' where n = '$c'",1);
    recount_comments_for_item($in[what],$x[item_no]);
  }
  else dq("delete from $s[pr]comments where n = '$c'",1);
}
if ($s[info]) $s[info] .= '<br>';
comments_unapproved_show($in);
}

######################################################################################
######################################################################################
######################################################################################

function comments_view($in) {
global $s;

$q = dq("select * from $s[pr]comments where item_no = '$in[n]' AND what = '$in[what]' AND approved = 1 order by time desc",0);
while ($comment=mysql_fetch_assoc($q)) 
{ $comment = stripslashes_array($comment);
  $comment[date] = datum ($comment[time],1);
  $comments .= '<tr><td align="left" colspan=2><input class="bbb" type="checkbox" name="comment[]" value="'.$comment[n].'">
  '.$comment[text].'<br />
  Written by: <a href="mailto:'.$comment[email].'">'.$comment[name].'</a>, created: '.$comment[date].'&nbsp;&nbsp;
  <a target="_self" href="comments.php?action=comment_edit&n='.$comment[n].'" title="Edit this comment">Edit</a>&nbsp;&nbsp;
  <a target="_self" href="comments.php?action=comment_delete&n='.$comment[n].'" title="Delete this comment">Delete</a>&nbsp;&nbsp;
  </td></tr>';
}
if (!$comments) $comments = '<tr><td align="left" nowrap colspan="2">No one comment found</td></tr>';

ih();
echo page_title('Comments');
echo show_check_uncheck_all().'<form method="get" action="comments.php" id="myform">
<input type="hidden" name="action" value="comments_updated_multiple">
<input type="hidden" name="what" value="'.$in[what].'">
<input type="hidden" name="n" value="'.$in[n].'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
'.get_item_info($in[what],$in[n])
.get_options_for_item($in[what],$in[n]).$comments.'</table></td></tr></table><br />';
echo '<input type="submit" name="submit" value="Delete selected comments" class="button10"></form>';
ift();
}

######################################################################################

function comment_edit($n) {
global $s;

$q = dq("select * from $s[pr]comments where n = '$n' limit 1",1);
$comment = mysql_fetch_assoc($q); if (!$comment[n]) problem ("Comment # $comment does not exist.");
$comment = stripslashes_array($comment);

ih();
echo $s[info];
$created = datum ($comment[time],1);

echo '<form method="post" action="comments.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="comment_edited">
<input type="hidden" name="what" value="'.$comment[what].'">
<input type="hidden" name="n" value="'.$comment[n].'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan=2>Comment #'.$comment[n].', Created: '.$created.' by <a href="mailto:'.$comment[email].'">'.$comment[name].'</a>, IP: '.$comment[ip].'</td></tr>'.
get_item_info($comment[what],$comment[item_no]).get_options_for_item($comment[what],$comment[item_no]).'
<tr><td align="left" valign="top">Text</td>
<td align="left"><textarea class="field10" name="text" style="width:650px;height:250px;">'.$comment[text].'</textarea></td></tr>
<tr><td align="left" nowrap>Owner </td>
<td align="left"><input class="field10" name="name" style="width:650px;" maxlength=150 value="'.$comment[name].'"></td></tr>
<tr><td align="left">Email</td>
<td align="left"><input class="field10" name="email" style="width:650px;" maxlength=150 value="'.$comment[email].'"></td></tr>
<tr><td align="center" colspan=2><input type="submit" name="co" value="Save changes" class="button10">
</td></tr>
</table>
</td></tr></table></form><br />';
ift();
}

##################################################################################

function comment_edited($in) {
global $s;
$in = replace_array_text($in);
dq("update $s[pr]comments set text = '$in[text]', name = '$in[name]', email = '$in[email]' where n = '$in[n]'",1);
$s[info] = info_line('Selected comment has been updated');
comment_edit($in[n]);
}

##################################################################################

function comment_delete($n) {
global $s;
$q = dq("select what,item_no from $s[pr]comments where n = '$n'",1);
$comment = mysql_fetch_assoc($q);
dq("delete from $s[pr]comments where n = '$n'",1);
recount_comments_for_item($comment[what],$comment[item_no]);
ih(); 
echo info_line('Selected comment has been deleted');
echo '<br /><br /><a href="'.$_SERVER[HTTP_REFERER].'">Back</a>';
ift();
}

##################################################################################

function comments_updated_multiple($in) {
global $s;
ih();
if (count($in[comment]))
{ $query = my_implode('n','OR',$in[comment]);
  dq("delete from $s[pr]comments where $query",1);
}
echo info_line('Comments deleted');
echo '<a href="comments.php?action=comments_view&what='.$in[what].'&n='.$in[n].'">Back</a>';
ift();
}

##################################################################################
##################################################################################
##################################################################################

function recount_comments_for_item($what,$n) {
global $s;
$table = $s[item_types_tables][$what];
$q = dq("select count(*) from $s[pr]comments where item_no = '$n' AND what = '$what' AND approved = '1'",1);
$x = mysql_fetch_row($q);
dq("update $table set comments = '$x[0]' where n = '$n'",1);
}

######################################################################################

function get_item_info($what,$n) {
global $s;
if ($what=='l')
{ $q = dq("select url,title from $s[pr]links where n = '$n'",1);
  $x = mysql_fetch_assoc($q);
  return '<tr><td align="left">Link</td><td align="left">#'.$n.' - <a target="_blank" href="'.$x[url].'">'.$x[title].'</a></td></tr>';
}
else
{ $table = $s[item_types_tables][$what];
  $q = dq("select title from $table where n = '$n'",1);
  $x = mysql_fetch_assoc($q);
  return '<tr><td align="left">'.$s[item_types_Words][$what].'</td><td align="left">#'.$n.' - '.$x[title].'</td></tr>';
}
}

######################################################################################

function get_options_for_item($what,$n) {
global $s;
$nazev = $action = $s[item_types_words][$what];
$script = $nazev.'_details.php';
return '<tr><td align="left" colspan=2><span class="text13a_bold">
<a href="'.$script.'?action='.$nazev.'_edit&n='.$n.'">Edit this item</a>&nbsp;&nbsp;
<a href="'.$script.'?action='.$nazev.'_delete&n='.$n.'">Delete this item</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="'.$script.'?action='.$nazev.'_copy&n='.$n.'">Copy this item</a>
</td></tr>';
}

######################################################################################
######################################################################################
######################################################################################


?>