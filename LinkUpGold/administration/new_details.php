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

include('./news_functions.php');

switch ($_GET[action]) {
case 'new_create'			: new_create_edit(0);
case 'new_edit'				: new_create_edit($_GET[n]);
case 'new_copy'				: new_create_edit($_GET[n]);
case 'new_delete'			: new_delete($_GET[n]);
case 'new_manage'			: new_manage($_GET);
case 'delete_image'			: delete_image('n',$_GET);
}
switch ($_POST[action]) {
case 'new_created'			: new_created($_POST);
case 'new_edited'			: new_edited($_POST);
case 'new_delete'			: new_delete($_POST[n]);
}

#################################################################################
#################################################################################
#################################################################################

function new_create_edit($n) {
global $s;

if ($_GET[action]) $current_action = $_GET[action]; else $current_action = $_POST[action];
if ($current_action != 'new_create')
{ $q = dq("select * from $s[pr]news where n = '$n'",1);
  $news = mysql_fetch_assoc($q);
  if (!$news[n]) problem ("News item #$n does not exist.");
  $q = dq("select * from $s[pr]usit_values where use_for = 'n' AND n = '$n'",1);
  while ($x = mysql_fetch_assoc($q))
  { $news['user_item_'.$x[item_n]][code] = $x[value_code];
    $news['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
else $news[n] = 0;
$news[current_action] = $current_action;
switch ($current_action) {
case 'new_create'	: $action = 'new_created'; $info = 'Create a New News item'; break;
case 'new_edit'		: $action = 'new_edited'; $info = 'Edit Selected News item'; break;
case 'new_edited'	: $action = 'new_edited'; $info = 'Edit Selected News item'; break;
case 'new_copy'		: $action = 'new_created'; $info = 'Copy Selected News item'; $news[old_number] = $news[n]; $news[n] = 0; break;
}

ih();
echo $s[info];
echo page_title($info);
echo '<form ENCTYPE="multipart/form-data" action="new_details.php" method="post">'.check_field_create('admin').'<input type="hidden" name="action" value="'.$action.'">';
if ($current_action=='new_copy') echo '<input type="hidden" name="old_n" value="'.$n.'">';
new_create_edit_form($news);
echo '<input type="submit" name="co" value="Save" class="button10"></form>';
ift();
}


######################################################################################
######################################################################################
######################################################################################

function new_created($in) {
global $s;
$old_n = $in[old_n];
$in = $in['new'][0];
if (!check_admin_categories('n',$in[categories])) problem('You do not have permission to add an news to the selected category/categories.');
$usit = item_updated_get_usit('n',$in);
$rating = rating_update_get_average('n',$in[n],$in[rates]);
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$c = categories_edited($in[categories]);
$in = replace_array_for_news($in);
$en_cats = has_some_enabled_categories('n',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'n');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';
if ($in[username]) $user = get_user_variables(0,$in[username]);
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
if (!$in[password]) $in[password] = get_random_password($in[title],$in[description],$c[categories]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
dq("insert into $s[pr]news values(NULL,'$in[url]','$in[title]','$in[description]','$in[text]','$in[keywords]','','$in[map]$map_test','$c[categories]','$c[categories_path]','$user[n]','$in[name]','$in[email]','$created',0,'$in[password]','$in[hits]',0,'$rating[average]','$rating[total_votes]','$in[pick]','0','0','$t1','$t2','$status','$en_cats','$in[rewrite_url]')",1);
$n = mysql_insert_id();
if ($old_n) copy_files('n',$old_n,$n);
else upload_files('n',$n,0,0,'');
add_update_user_items('n',$n,$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('n',$in[categories],'');
update_item_index('n',$n);
dq("insert into $s[pr]u_to_email values('n','$n')",1);
if ($in[email_owner])
{ $email[to] = $in[email];
  $email[title] = $in[title]; $email[description] = $in[description]; $email[n] = $n; 
  $email[detail_url] = get_detail_page_url('n',$n,'',0,1);
  mail_from_template('news_added_by_admin.txt',$email);
}
ih();
echo info_line('News item Created');
show_one_new($n);
ift();
}

######################################################################################

function new_edited($in) {
global $s;
foreach ($in['new'] as $k=>$v)
{ $new = $v; 
  if (!check_admin_categories('n',$new[categories])) problem('You do not have permission to add/edit an news in the selected category/categories.');
  $new[n] = $k;
  new_edited_process($new);
}
$s[info] = info_line('News item Updated');
new_create_edit($new[n]);
exit;
}

######################################################################################
######################################################################################
######################################################################################

function new_manage($in) {
global $s;
$news = get_item_variables('n',$in[n]);
if (!check_admin_categories('n',$news[c])) problem('You do not have permission to manage news in the category/categories where this news is listed.');
if ($in[what]) { $status = 'enabled'; $Status = 'Enabled'; }
else { $status = 'disabled'; $Status = 'Disabled'; }
dq("update $s[pr]news set status = '$status' where n = '$in[n]' and status != 'queue'",1);
recount_items_cats('n',$news[c]);
ih();
echo info_line('Selected News item Has Been '.$Status);
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}

#################################################################################
#################################################################################
#################################################################################

function new_delete($n) {
global $s;
$news = get_item_variables('n',$n);
if (!check_admin_categories('n',$news[c])) problem('You do not have permission to manage news in the category/categories where this news is listed.');
delete_items('n',$n);
recount_items_cats('n',$news[c]);
ih(); 
echo info_line('Selected News item Has Been Deleted');
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}


##################################################################################
##################################################################################
##################################################################################

?>