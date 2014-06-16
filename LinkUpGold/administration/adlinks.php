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
check_admin('adlinks');

switch ($_GET[action]) {
case 'adlink_create'			: adlink_create_edit(0);
case 'adlink_edit'				: adlink_create_edit($_GET[n]);
case 'adlink_delete'			: adlink_delete($_GET[n]);
case 'adlinks_searched'			: adlinks_searched($_GET);
case 'adlinks_updated_multiple'	: adlinks_updated_multiple($_GET);
case 'adlinks_unapproved_show'	: adlinks_unapproved_show($_GET);
}
switch ($_POST[action]) {
case 'adlink_created'			: adlink_created($_POST);
case 'adlink_edited'			: adlink_edited($_POST);
case 'adlinks_edited_multiple'	: adlinks_edited_multiple($_POST);
case 'adlinks_approved'			: adlinks_approved($_POST);
}
adlinks_home();

##################################################################################
##################################################################################
##################################################################################

function adlink_create_edit($n) {
global $s;
if ($_GET[action]) $current_action = $_GET[action]; else $current_action = $_POST[action];
if ($current_action != 'adlink_create') $adlink = get_adlink_variables($n);
else $adlink[n] = 0;

switch ($current_action) {
case 'adlink_create'	: $action = 'adlink_created'; $info = 'Create a new AdLink'; break;
case 'adlink_created'	: $action = 'adlink_edited'; $info = 'Edit Selected AdLink'; break;
case 'adlink_edit'		: $action = 'adlink_edited'; $info = 'Edit Selected AdLink'; break;
case 'adlink_edited'	: $action = 'adlink_edited'; $info = 'Edit Selected AdLink'; break;
default					: $action = 'adlink_created'; $info = 'Create a new AdLink';
}
ih();
echo $s[info];
echo page_title($info);
echo '<form action="adlinks.php" method="post">'.check_field_create('admin').'<input type="hidden" name="action" value="'.$action.'">';
adlink_create_edit_form($adlink);
echo '<input type="submit" name="co" value="Save" class="button10"></form>';
ift();
}

##################################################################################

function adlink_create_edit_form($link) {
global $s;
$n = $link[n]; if (!$n) $n = 0;
$link = stripslashes_array($link);
$user = get_user_variables($link[owner]);
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td>
<table border=0 width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
if (($n) AND (!$link[approved]))
{ $queue = 1;
  echo '<tr><td class="common_table_top_cell" colspan="2">Queue Options</td></tr>
  <tr><td align="left" colspan="2" nowrap>
  Approve it <input type="radio" name="link['.$n.'][approve]" value="yes" id="approve_'.$n.'">
  <a class="link10" href="#" onClick="uncheck_both('.$n.'); return false;">Uncheck these boxes</a><br />
  Delete it <input type="radio" name="link['.$n.'][approve]" value="no" id="reject_'.$n.'">
  and send email: <select class="select10" name="link['.$n.'][reject_email]">'.$link[reject_emails].'
  </select>
  or <input type="checkbox" name="link['.$n.'][reject_email_custom]" value="1" id="fullcust'.$n.'" onclick="show_hide_div(document.getElementById(\'fullcust'.$n.'\').checked,document.getElementById(\'test'.$n.'\'));" value="1"> Individual Message
  <tr><td align="left" colspan="2">
  <div id="test'.$n.'" style="display:none;">
  <table border=0 width=100% cellspacing=2 cellpadding=0>
  <tr>
  <td align="left">Subject</td>
  <td><input class="field10" name="link['.$n.'][email_subject]" style="width:650px;"></td>
  </tr>
  <tr>
  <td align="left" valign="top">Text<br /><span class="text10">Available variables:<br />#%title%# - Link Title<br />#%url%# - Link URL<br /></span></td>
  <td><textarea class="field10" name="link['.$n.'][email_text]" style="width:650px;height:250px;"></textarea></td>
  </tr>
  </table></DIV>
  </td></tr>';
  $x = get_item_variables('l',$n);
  if ($x[n]) { $link_old[rating] = $x[rating]; $link_old[votes] = $x[votes]; $link_old[clicks_in] = $x[clicks_in]; $link_old[clicks_in_m] = $x[clicks_in_m]; $link_old[hits] = $x[hits]; $link_old[hits_m] = $x[hits_m]; $link_old[pick] = $x[pick];  }
  $link = array_merge((array)$link,(array)$link_old);
}
echo '<tr><td class="common_table_top_cell" colspan="2">Public Items</td></tr>
<tr>
<td align="left"><a target="_blank" href="'.$link[url].'" title="Click here to visit this URL">URL</a></td>
<td align="left"><input class="field10" name="link['.$n.'][url]" style="width:650px;" maxlength=255 value="'.$link[url].'"></td></tr>
<tr>
<td align="left">Title</td>
<td align="left"><input class="field10" name="link['.$n.'][title]" style="width:650px;" maxlength=255 value="'.$link[title].'"></td>
</tr>';
for ($x=1;$x<=10;$x++)
echo '<tr>
<td align="left">Text line '.$x.' </td>
<td align="left"><input class="field10" name="link['.$n.'][text'.$x.']" style="width:650px;" maxlength=255 value="'.$link["text$x"].'"></td>
</tr>';
echo '<tr>
<td align="left" valign="top" colspan="2">HTML ad<span class="text10"><br />If you enter HTML ad, the fields above (Title, URL, Texts) will not be used</span></td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">'.get_fckeditor('link['.$n.'][html]',$link[html],'AdminToolbar').'</td>
</tr>';
echo '<tr><td class="common_table_top_cell" colspan="2">Link Features</td></tr>';
if ($n) echo '<tr>
<td align="left">Number</td>
<td align="left">'.$n.'</td>
</tr>';
echo categories_rows_form('adlink',$link);
echo '<tr>
<td align="left" nowrap>Keywords</td>
<td align="left" nowrap><input class="field10" name="link['.$n.'][keywords]" style="width:650px;" maxlength=255 value="'.$link[keywords].'"></td>
</tr>
<tr>
<td align="left" nowrap>Owner\'s username</td>
<td align="left" nowrap><input class="field10" name="link['.$n.'][username]" style="width:100px" maxlength=255 value="'.$user[username].'"></td>
</tr>
<tr>
<td align="left" nowrap>Clicks total</td>
<td align="left" nowrap><input class="field10" name="link['.$n.'][c_total]" style="width:100px" maxlength=10 value="'.$link[c_total].'"></td>
</tr>
<tr>
<td align="left" nowrap>Clicks available</td>
<td align="left" nowrap><input class="field10" name="link['.$n.'][c_now]" style="width:100px" maxlength=10 value="'.$link[c_now].'"></td>
</tr>
<tr>
<td align="left" nowrap>Price for 1 click</td>
<td align="left" nowrap>'.$s[currency].'<input class="field10" name="link['.$n.'][price]" style="width:100px" maxlength=10 value="'.$link[price].'"></td>
</tr>
<tr>
<td align="left" nowrap>Enabled </td>
<td align="left" nowrap><input type="checkbox" value="1" name="link['.$n.'][enabled]"'; if ($link[enabled]) echo ' checked'; echo '></td>
</tr>
</table>
</td></tr></table>
<br />';
}

######################################################################################
######################################################################################
######################################################################################

function adlink_created($in) {
global $s;
$in = replace_array_text($in[link][0]);
$in[html] = refund_html($in[html]);
$c = categories_edited($in[categories]);
if ($in[username]) $user = get_user_variables(0,$in[username]);
dq("insert into $s[pr]adlinks values (NULL,'$user[n]','1','$in[enabled]','$in[price]','$in[c_now]','$in[c_total]','$c[categories]','$in[keywords]','$in[url]','$in[title]','$in[text1]','$in[text2]','$in[text3]','$in[text4]','$in[text5]','$in[text6]','$in[text7]','$in[text8]','$in[text9]','$in[text10]','$in[html]')",1);
$n = mysql_insert_id();
$s[info] = info_line('AdLink Created');
adlink_create_edit($n);
}

##################################################################################

function adlink_edited($in) {
global $s;
adlink_edited_process($in);
$s[info] = info_line('Selected AdLink has been updated');
foreach ($in[link] as $k=>$v) $n = $k;
adlink_create_edit($n);
}

##################################################################################

function adlink_edited_process($in) {
global $s;
foreach ($in[link] as $k=>$v) $n = $k;
$in = replace_array_text($in[link][$n]);
$in[n] = $n;
$in[html] = refund_html($in[html]);
$c = categories_edited($in[categories]);
if ($in[username]) $user = get_user_variables(0,$in[username]);
dq("update $s[pr]adlinks set owner = '$user[n]', c = '$c[categories]', keywords = '$in[keywords]', enabled = '$in[enabled]', url = '$in[url]', title = '$in[title]', text1 = '$in[text1]', text2 = '$in[text2]', text3 = '$in[text3]', text4 = '$in[text4]', text5 = '$in[text5]', text6 = '$in[text6]', text7 = '$in[text7]', text8 = '$in[text8]', text9 = '$in[text9]', text10 = '$in[text10]', html = '$in[html]', price = '$in[price]', c_now = '$in[c_now]', c_total = '$in[c_total]' where n = '$in[n]'",1);
}

##################################################################################
##################################################################################
##################################################################################

function adlink_delete($n) {
global $s;
dq("delete from $s[pr]adlinks where n = '$n'",1);
ih();
echo info_line('Selected AdLink has been deleted');
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}

######################################################################################
######################################################################################
######################################################################################

function adlinks_home() {
global $s;
ih();
$q = dq("select count(*) from $s[pr]adlinks where approved = '0'",1); $x = mysql_fetch_row($q);
if ($x[0]) echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Queue</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center"><a href="adlinks.php?action=adlinks_unapproved_show">AdLinks in the queue: '.$x[0].'. Click here to review them.</a></td></tr>
</table></td></tr></table>
<br />';
echo '<form method="get" action="adlinks.php">
<input type="hidden" name="action" value="adlinks_searched">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Search for AdLinks</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left">Number <span class="text10">&nbsp;&nbsp;&nbsp;If you enter a number here, it will find this link, not depending if it meets any other criteria<br /></span></td>
<td align="left"><input class="field10" name="n" style="width:100px" maxlength=10></td></tr>
<tr>
<td align="left" nowrap>Category</td>
<td align="left"><select class="select10" name="category"><option value="0">Any category</option>'.categories_selected('l',0,1,1,0,0).'</select></td>
</tr>
<tr>
<td align="left" nowrap>Any field contains </td>
<td align="left"><input class="field10" name="any" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Title contains </td>
<td align="left"><input class="field10" name="title" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>URL contains </td>
<td align="left"><input class="field10" name="url" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Texts contain </td>
<td align="left"><input class="field10" name="texts" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Keywords contains </td>
<td align="left"><input class="field10" name="keywords" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Owner\'s username </td>
<td align="left"><input class="field10" name="username" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Enabled </td>
<td align="left" nowrap>N/A<input type="radio" name="enabled" value="0" checked>&nbsp; Yes<input type="radio" name="enabled" value="yes">&nbsp; No<input type="radio" name="enabled" value="no"></td>
</tr>
<tr>
<td align="left" nowrap>Available clicks </td>
<td align="left" nowrap>N/A<input type="radio" name="clicks" value="0" checked>&nbsp; Yes<input type="radio" name="clicks" value="yes">&nbsp; No<input type="radio" name="clicks" value="no"></td>
</tr>
<tr>
<td align="left" nowrap>Type of search </td>
<td align="left" nowrap>AND <input type="radio" value="and" name="boolean" checked> OR <input type="radio" value="or" name="boolean"></td>
</tr>
<tr>
<td align="left" nowrap>Sort by </td>
<td align="left"><select class="select10" name="sort"><option value="title">Title</option><option value="n">Number</option><option value="c_now">Number of available clicks</option></select><select class="select10" name="order"><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>
</tr>
<tr><td colspan=2 align="center"><input type="submit" value="Search" name="B1" class="button10"></td></tr>
</table></td></tr></table></form>';
ift();
}

######################################################################################

function adlinks_searched($in) {
global $s;
//foreach ($in as $k=>$v) echo "$k - $v<br />";
ih();
if (!$in[n])
{ if ($in[texts]) $w[] = "(text1 like '%$in[texts]%' OR text2 like '%$in[texts]%' OR text3 like '%$in[texts]%' OR text4 like '%$in[texts]%' OR text5 like '%$in[texts]%' OR text6 like '%$in[texts]%' OR text7 like '%$in[texts]%' OR text8 like '%$in[texts]%' OR text9 like '%$in[texts]%' OR text10 like '%$in[texts]%')";
  if (strstr($in[title],'|')) $w[] = "title like '".str_replace('|','',$in[title])."%'";
  elseif ($in[title]) $w[] = "title like '%$in[title]%'";
  if ($in[url]) $w[] = "url like '%$in[url]%'";
  if ($in[keywords]) $w[] = "keywords like '%$in[keywords]%'";
  if ($in[username])
  { $user = get_user_variables(0,$in[username]);
    if ($user[n]) $w[] = "owner = '$user[n]'";
  }
  elseif ($in[owner]) $w[] = "owner = '$in[owner]'";
  if ($in[category]) $w[] = "c like '%\_$in[category]\_%'";
  if ($in[enabled]=='yes') $w[] = "enabled = '1'"; elseif ($in[enabled]=='no') $w[] = "enabled = '0'";
  if ($in[clicks]=='yes') $w[] = "c_now >= '1'"; elseif ($in[clicks]=='no') $w[] = "c_now = '0'";
  if ($in[any])
  { if (!$w[0]) $only_any = 1;
    $w[] = "(title like '%$in[any]%' OR url like '%$in[any]%' OR html like '%$in[any]%' OR text1 like '%$in[any]%' OR text2 like '%$in[any]%' OR text3 like '%$in[any]%' OR text4 like '%$in[any]%' OR text5 like '%$in[any]%' OR text6 like '%$in[any]%' OR text7 like '%$in[any]%' OR text8 like '%$in[any]%' OR text9 like '%$in[any]%' OR text10 like '%$in[any]%')";
  }
  if ($w) $where = ' where ('.join (" $in[boolean] ", $w).') and approved = 1';
}
else $where = "where n = '$in[n]' and approved = '1'";
if (!$where) $where = "where approved = '1'";

$x = dq("select count(*) from $s[pr]adlinks $where",1);
$pocet = mysql_fetch_row($x); $pocet = $pocet[0];
if (!$pocet) no_result('link');

if ($in[sort]) $orderby = "order by $in[sort]";
$q = dq("select * from $s[pr]adlinks $where $orderby $in[order]",1);
echo '<span class="text13a_bold">AdLinks Found: '.$pocet.'</span><br /><br />';
prepare_and_display_adlinks($q,$in[edit_forms]);
ift();
}

######################################################################################

function prepare_and_display_adlinks($q,$edit_forms) {
global $s;

while ($x = mysql_fetch_assoc($q)) { $links[$x[n]] = $x; $link_numbers[] = $x[n]; }
if (!$link_numbers[0]) return false;

if ($edit_forms) echo '<form method="post" action="adlinks.php">'.check_field_create('admin').'<input type="hidden" name="action" value="adlinks_edited_multiple">';
else echo show_check_uncheck_all().'<form method="get" action="adlinks.php" id="myform"><input type="hidden" name="action" value="adlinks_updated_multiple">';
foreach ($links as $k=>$v)
{ if ($edit_forms) { $v[current_action] = 'adlink_edit'; $v[update_no_check] = '1'; adlink_create_edit_form($v); }
  else { $v[show_checkbox] = 1; show_one_adlink($v); }
}
if (!$link_numbers) ift();
if (!$edit_forms)
{ echo 'Action to do with selected links: 
  <select class="select10" name="to_do"><option value="0">No action</option>
  <option value="enable">Enable</option>
  <option value="disable">Disable</option>
  <option value="delete">Delete</option>
  </select> ';
}
echo '<input type="submit" name="submit" value="Submit" class="button10"></form>';
}

######################################################################################

function show_one_adlink($link) {
global $s;

$link = stripslashes_array($link);
if ($link[enabled])
{ 
  $enabled = '<font color="green">Yes</font>';
}
else
{ 
  $enabled = '<font color="red">No</font>';
}
if ($link[show_checkbox]) $checkbox = '<input class="bbb" type="checkbox" name="link[]" value="'.$link[n].'">&nbsp;&nbsp;';
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" valign="top" width="200">'.$checkbox.' Title </td>
<td align="left">'.$link[title].'</td>
</tr>
<tr>
<td align="left" valign="top">URL </td>
<td align="left"><a target="_blank" href="'.$link[url].'">'.$link[url].'</a></td>
</tr>';
for ($x=1;$x<=10;$x++) echo '<tr>
<td align="left" valign="top">Text #'.$x.'</td>
<td align="left">'.$link["text$x"].'&nbsp;</td>
</tr>';
echo '<tr>
<td align="left" valign="top">Categories</td>
<td align="left">'.list_of_categories_for_item_admin('adlinks',$link[c]).'&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Keywords</td>
<td align="left">'.$link[keywords].'&nbsp;</td>
</tr>';
if ($link[owner])
{ if ($link[owner]) $user = get_user_variables($link[owner]);
  echo '<tr>
  <td align="left" nowrap>Owner </td>
  <td align="left"><a href="users.php?action=users_searched&n='.$user[n].'&sort=username&order=asc">'.$user[username].'</a>&nbsp;</td>
  </tr>';
}
echo '<tr>
<td align="left" valign="top">Clicks total </td>
<td align="left">'.$link[c_total].'</td>
</tr>
<tr>
<td align="left" valign="top">Clicks balance </td>
<td align="left">'.$link[c_now].'</td>
</tr>
<tr>
<td align="left" valign="top">Enabled </td>
<td align="left">'.$enabled.'</td>
</tr>
<tr>
<td align="left" valign="top">Number </td>
<td align="left">'.$link[n].'</td>
</tr>';
echo '<tr><td align="left" colspan=2>
[<a target="_self" href="adlinks.php?action=adlink_edit&n='.$link[n].'" title="Edit this link">Edit</a>]&nbsp;&nbsp;
[<a target="_self" href="javascript: go_to_delete(\'Are you sure?\',\'adlinks.php?action=adlink_delete&n='.$link[n].'\')" title="Delete this link">Delete</a>]&nbsp;&nbsp;
</td></tr></table></td></tr></table>
<br />';
}

######################################################################################

function adlinks_updated_multiple($in) {
global $s;
ih();
$query = my_implode('n','or',$in[link]);
if ($in[to_do]=='delete')
{ echo info_line('Total of '.count($in[link]).' links will be deleted. Continue?');
  echo '<form method="get" action="adlinks.php">
  <input type="hidden" name="action" value="adlinks_updated_multiple">
  <input type="hidden" name="to_do" value="deleted">';
  foreach ($in[link] as $k=>$v) echo '<input type="hidden" name="link[]" value="'.$v.'">';
  echo '<input type="submit" name="submit" value="Submit" class="button10"></form>';
}
else
{ if ($in[to_do]=='enable')
  { dq("update $s[pr]adlinks set enabled = '1' where $query",1);
    $info = info_line(mysql_affected_rows().' AdLinks have been enabled');
  }
  elseif ($in[to_do]=='disable')
  { dq("update $s[pr]adlinks set enabled = '0' where $query",1);
    $info = info_line(mysql_affected_rows().' AdLinks have been disabled');
  }
  elseif ($in[to_do]=='deleted')
  { dq("delete from $s[pr]adlinks where $query",1);
    $info = info_line(mysql_affected_rows().' AdLinks have been deleted');
  }
  echo $info;
}
if ($in[back]) $back = $in[back]; else $back = $_SERVER[HTTP_REFERER];
echo '<br /><br /><a href="javascript:history.back(-1)">Back</a>';
ift();
exit;
}

######################################################################################
######################################################################################
######################################################################################

function adlinks_unapproved_show($in) {
global $s;
if (!$in[from]) $from = 0; else $from = $in[from] - 1;

$q = dq("select count(*) from $s[pr]adlinks where approved = '0'",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet) { ih(); echo $s[info].info_line('No one AdLink in the queue'); ift(); }

$q = dq("select * from $s[pr]adlinks where approved = '0' order by n",1);
while ($x = mysql_fetch_assoc($q)) { $links[$x[n]] = $x; $numbers[] = $x[n]; }
$reject_emails = get_reject_emails_list('reject_adlink_');

ih();
echo $s[info].page_title('AdLinks in The Queue: '.$pocet);
echo '<form action="adlinks.php" method="post" name="muj">'.check_field_create('admin').'<input type="hidden" name="action" value="adlinks_approved">';
foreach ($links as $k=>$v)
{ $v[reject_emails] = $reject_emails;
  adlink_create_edit_form($v);
}
echo '<input type="submit" name="submit" value="Submit" class="button10"></form>';
ift();
}

######################################################################################

function adlinks_approved($in) {
global $s;
foreach ($in[link] as $n=>$link)
{ if (!$in[link][$n][approve]) continue;
  $link[n] = $n;
  $oznamit = 0;
  $old = get_adlink_variables($n);
  if ($link[approve]=='yes')
  { dq("update $s[pr]adlinks set approved = '1' where n = '$n'",1);
	adlink_edited_process($link);
    $s[info] .= 'Link #'.$n.' has been approved';
    $oznamit = 1;
  }
  elseif ($link[approve]=='no')  // reject
  { dq("delete from $s[pr]adlinks where n = '$n'",1);
	$s[info] .= 'Link #'.$n.' has been deleted';
    $oznamit = 1;
  }
  if ($link[username]) $user = get_user_variables(0,$link[username]);
  
  if (!$user[n]) $oznamit = 0;
  if (!$oznamit) { $s[info] .= '.<br />'; continue; }
  $link[to] = $user[email];
  if ($link[approve]=='no')
  { if ($link[reject_email]) { $email_sent = 1; mail_from_template($link[reject_email],$link); }
    elseif (($link[reject_email_custom]) AND ($link[email_subject]) AND ($link[email_text]))
    { foreach ($link as $k=>$v) $link[email_text] = str_replace("#%$k%#",$v,$link[email_text]);
      my_send_mail('','',$user[email],0,unhtmlentities($link[email_subject]),unhtmlentities($link[email_text]),1);
	  $email_sent = 1;
	}
  }
  elseif (($link[approve]=='yes') AND ($s[adlink_i_approved])) { $email_sent = 1; mail_from_template('adlink_approved.txt',$link); }
  if ($email_sent) $s[info] .= '. Email sent.<br />'; else $s[info] .= '. Email not sent.<br />';
}
adlinks_unapproved_show();
}

######################################################################################
######################################################################################
######################################################################################

?>