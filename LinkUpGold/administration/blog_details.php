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

include('./blogs_functions.php');

switch ($_GET[action]) {
case 'blog_create'			: blog_create_edit(0);
case 'blog_edit'			: blog_create_edit($_GET[n]);
case 'blog_copy'			: blog_create_edit($_GET[n]);
case 'blog_delete'			: blog_delete($_GET[n]);
case 'blog_manage'			: blog_manage($_GET);
case 'delete_image'			: delete_image('b',$_GET);
}
switch ($_POST[action]) {
case 'blog_created'			: blog_created($_POST);
case 'blog_edited'			: blog_edited($_POST);
case 'blog_delete'			: blog_delete($_POST[n]);
}

#################################################################################
#################################################################################
#################################################################################

function blog_create_edit($n) {
global $s;

if ($_GET[action]) $current_action = $_GET[action]; else $current_action = $_POST[action];
if ($current_action != 'blog_create')
{ $q = dq("select * from $s[pr]blogs where n = '$n'",1);
  $blog = mysql_fetch_assoc($q);
  if (!$blog[n]) problem ("Blog #$n does not exist.");
  $q = dq("select * from $s[pr]usit_values where use_for = 'b' AND n = '$n'",1);
  while ($x = mysql_fetch_assoc($q))
  { $blog['user_item_'.$x[item_n]][code] = $x[value_code];
    $blog['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
else $blog[n] = 0;
$blog[current_action] = $current_action;
switch ($current_action) {
case 'blog_create'	: $action = 'blog_created'; $info = 'Create a New Blog'; break;
case 'blog_edit'		: $action = 'blog_edited'; $info = 'Edit Selected Blog'; break;
case 'blog_edited'	: $action = 'blog_edited'; $info = 'Edit Selected Blog'; break;
case 'blog_copy'		: $action = 'blog_created'; $info = 'Copy Selected Blog'; $blog[old_number] = $blog[n]; $blog[n] = 0; break;
}

ih();
echo $s[info];
echo page_title($info);
echo '<form ENCTYPE="multipart/form-data" action="blog_details.php" method="post">'.check_field_create('admin').'<input type="hidden" name="action" value="'.$action.'">';
if ($current_action=='blog_copy') echo '<input type="hidden" name="old_n" value="'.$n.'">';
blog_create_edit_form($blog);
echo '<input type="submit" name="co" value="Save" class="button10"></form>';
ift();
}


######################################################################################
######################################################################################
######################################################################################

function blog_created($in) {
global $s;
$old_n = $in[old_n];
$in = $in[blog][0];
if (!check_admin_categories('b',$in[categories])) problem('You do not have permission to add a blog to the selected category/categories.');
$usit = item_updated_get_usit('b',$in);
$rating = rating_update_get_average('b',$in[n],$in[rates]);
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$c = categories_edited($in[categories]);
$in = replace_array_for_blogs($in);
$en_cats = has_some_enabled_categories('b',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'b');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';
if ($in[username]) $user = get_user_variables(0,$in[username]);
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
dq("insert into $s[pr]blogs values(NULL,'$in[title]','$in[description]','$in[text]','$in[keywords]','','$in[map]$map_test','$c[categories]','$c[categories_path]','$user[n]','$in[name]','$in[email]','$created',0,'$in[password]','$in[hits]',0,'$rating[average]','$rating[total_votes]','$in[pick]','0','0','$t1','$t2','$status','$en_cats','$in[rewrite_url]')",1);
$n = mysql_insert_id();
if ($old_n) copy_files('b',$old_n,$n);
else upload_files('b',$n,0,0,'');
add_update_user_items('b',$n,$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('b',$in[categories],'');
update_item_index('b',$n);
dq("insert into $s[pr]u_to_email values('b','$n')",1);
if ($in[email_owner])
{ $email[to] = $in[email];
  $email[title] = $in[title]; $email[description] = $in[description]; $email[n] = $n; 
  $email[detail_url] = get_detail_page_url('b',$n,'',0,1);
  mail_from_template('blog_added_by_admin.txt',$email);
}
ih();
echo info_line('Blog Created');
show_one_blog($n);
ift();
}

######################################################################################

function blog_edited($in) {
global $s;
foreach ($in[blog] as $k=>$v)
{ $blog = $v; 
  if (!check_admin_categories('b',$blog[categories])) problem('You do not have permission to add/edit a blog in the selected category/categories.');
  $blog[n] = $k;
  blog_edited_process($blog);
}
$s[info] = info_line('Blog Updated');
blog_create_edit($blog[n]);
exit;
}

######################################################################################
######################################################################################
######################################################################################

function blog_manage($in) {
global $s;
$blog = get_item_variables('b',$in[n]);
if (!check_admin_categories('b',$blog[c])) problem('You do not have permission to manage blog in the category/categories where this blog is listed.');
if ($in[what]) { $status = 'enabled'; $Status = 'Enabled'; }
else { $status = 'disabled'; $Status = 'Disabled'; }
dq("update $s[pr]blogs set status = '$status' where n = '$in[n]' and status != 'queue'",1);
recount_items_cats('b',$blog[c]);
ih();
echo info_line('Selected Blog Has Been '.$Status);
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}

#################################################################################
#################################################################################
#################################################################################

function blog_delete($n) {
global $s;
$blog = get_item_variables('b',$n);
if (!check_admin_categories('b',$blog[c])) problem('You do not have permission to manage blogs in the category/categories where this blog is listed.');
delete_items('b',$n);
recount_items_cats('b',$blog[c]);
ih(); 
echo info_line('Selected Blog Has Been Deleted');
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}


##################################################################################
##################################################################################
##################################################################################

?>