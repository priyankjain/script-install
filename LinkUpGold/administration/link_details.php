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

include('./links_functions.php');

switch ($_GET[action]) {
case 'link_create'			: link_create_edit(0);
case 'link_edit'			: link_create_edit($_GET[n]);
case 'link_delete'			: link_delete($_GET[n]);
case 'link_copy'			: link_create_edit($_GET[n]);
case 'link_manage'			: link_manage($_GET);
case 'delete_image'			: delete_image('l',$_GET);
}
switch ($_POST[action]) {
case 'link_created'			: link_created($_POST);
case 'link_edited'			: link_edited($_POST);
}

##################################################################################
##################################################################################
##################################################################################

function link_create_edit($n) {
global $s;

if ($_GET[action]) $current_action = $_GET[action]; else $current_action = $_POST[action];
if ($current_action != 'link_create')
{ $q = dq("select * from $s[pr]links where n = '$n'",1);
  $link = mysql_fetch_assoc($q);
  if (!$link[n]) problem ("Link #$n does not exist.");
  $q = dq("select * from $s[pr]usit_values where use_for = 'l' AND n = '$n'",1);
  while ($x = mysql_fetch_assoc($q))
  { $link['user_item_'.$x[item_n]][code] = $x[value_code];
    $link['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
else $link[n] = 0;
$link[current_action] = $current_action;
switch ($current_action) {
case 'link_create'	: $action = 'link_created'; $info = 'Create a New Link'; break;
case 'link_edit'	: $action = 'link_edited'; $info = 'Edit Selected Link'; break;
case 'link_edited'	: $action = 'link_edited'; $info = 'Edit Selected Link'; break;
case 'link_copy'	: $action = 'link_created'; $info = 'Copy Selected Link'; $link[old_number] = $link[n]; $link[n] = 0; break;
}

if ($_GET[autofill]) { $link[url] = $_GET[url]; $link[title] = $_GET[title]; }
ih();
echo $s[info];
echo page_title($info);
echo '<form enctype="multipart/form-data" action="link_details.php" method="post">'.check_field_create('admin').'<input type="hidden" name="action" value="'.$action.'">';
if ($current_action=='link_copy') echo '<input type="hidden" name="old_n" value="'.$n.'">';
link_create_edit_form($link);
echo '<input type="submit" name="co" value="Save" class="button10"></form>
<br />
You can get this form with a prefilled URL and Title of a page you want to add. Bookmark this link:
<a href="javascript:location.href=\''.$s[site_url].'/administration/link_details.php?action=link_create&autofill=1&url=\'+encodeURIComponent(location.href)+\'&title=\'+encodeURIComponent(document.title)"><b>Add link to Link Up Gold</b></a>
<br />and when you found a page you want to add to your directory, click the bookmarked link. It gives you this form.
<br />This tool may work properly only if you checked the field "Remember me" in the login form.<br /><br />';
ift();
}

######################################################################################
######################################################################################
######################################################################################

function link_created($in) {
global $s;
$old_n = $in[old_n];
$in = $in[link][0];
if (!check_admin_categories('l',$in[categories])) problem('You do not have permission to add a link to the selected category/categories.');
$usit = item_updated_get_usit('l',$in);
$in = replace_array_text($in);
if ($in[enabled]) $in[status] = 'enabled'; else $in[status] = 'disabled';
if ($in[username]) { $user = get_user_variables(0,$in[username]); $in[user_n] = $user[n]; }
$n = enter_link($in);
if ($old_n) copy_files('l',$old_n,$n);
else upload_files('l',$n,0,0,'');
add_update_user_items('l',$n,$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('l',$in[categories],'');
update_item_index('l',$n);
if ($in[email_owner])
{ $email[to] = $in[email];
  $email[title] = $in[title]; $email[description] = $in[description]; $email[url] = $in[url]; $email[n] = $n; 
  $email[detail_url] = get_detail_page_url('l',$n,'',0,1);
  mail_from_template('link_added_by_admin.txt',$email);
}

ih();
echo info_line('Link Created');
show_one_link($n);
ift();
}

##################################################################################

function link_edited($in) {
global $s;
foreach ($in[link] as $k=>$v)
{ $link = $v;
  if (!check_admin_categories('l',$link[categories])) problem('You do not have permission to add/edit a link in the selected category/categories.');
  $link[n] = $k;
  link_edited_process($link);
}
$s[info] = info_line('Selected link has been updated');
link_create_edit($link[n]);
}

##################################################################################
##################################################################################
##################################################################################

function link_manage($in) {
global $s;
$link = get_item_variables('l',$in[n],0);
if (!check_admin_categories('l',$link[c])) problem('You do not have permission to manage links in the category/categories where this link is listed.');
if ($in[what]) { $status = 'enabled'; $Status = 'Enabled'; }
else { $status = 'disabled'; $Status = 'Disabled'; }
dq("update $s[pr]links set status = '$status' where n = '$in[n]' and status != 'queue'",1);
recount_items_cats('l',$link[c]);
ih();
echo info_line('Selected Link Has Been '.$Status);
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}

##################################################################################

function link_delete($n) {
global $s;
$link = get_item_variables('l',$n,0);
if (!check_admin_categories('l',$link[c])) problem('You do not have permission to delete links in the category/categories where this link is listed.');
delete_items('l',$n);
recount_items_cats('l',$link[c]);
ih();
echo info_line('Selected link has been deleted');
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}

######################################################################################
######################################################################################
######################################################################################

?>