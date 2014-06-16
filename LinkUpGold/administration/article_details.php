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

include('./articles_functions.php');

switch ($_GET[action]) {
case 'article_create'			: article_create_edit(0);
case 'article_edit'				: article_create_edit($_GET[n]);
case 'article_copy'				: article_create_edit($_GET[n]);
case 'article_delete'			: article_delete($_GET[n]);
case 'article_manage'			: article_manage($_GET);
case 'delete_image'				: delete_image('a',$_GET);
}
switch ($_POST[action]) {
case 'article_created'			: article_created($_POST);
case 'article_edited'			: article_edited($_POST);
case 'article_delete'			: article_delete($_POST[n]);
}

#################################################################################
#################################################################################
#################################################################################

function article_create_edit($n) {
global $s;

if ($_GET[action]) $current_action = $_GET[action]; else $current_action = $_POST[action];
if ($current_action != 'article_create')
{ $q = dq("select * from $s[pr]articles where n = '$n'",1);
  $article = mysql_fetch_assoc($q);
  if (!$article[n]) problem ("Article #$n does not exist.");
  $q = dq("select * from $s[pr]usit_values where use_for = 'a' AND n = '$n'",1);
  while ($x = mysql_fetch_assoc($q))
  { $article['user_item_'.$x[item_n]][code] = $x[value_code];
    $article['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
else $article[n] = 0;
$article[current_action] = $current_action;
switch ($current_action) {
case 'article_create'	: $action = 'article_created'; $info = 'Create a New Article'; break;
case 'article_edit'		: $action = 'article_edited'; $info = 'Edit Selected Article'; break;
case 'article_edited'	: $action = 'article_edited'; $info = 'Edit Selected Article'; break;
case 'article_copy'		: $action = 'article_created'; $info = 'Copy Selected Article'; $article[old_number] = $article[n]; $article[n] = 0; break;
}

ih();
echo $s[info];
echo page_title($info);
echo '<form ENCTYPE="multipart/form-data" action="article_details.php" method="post">'.check_field_create('admin').'<input type="hidden" name="action" value="'.$action.'">';
if ($current_action=='article_copy') echo '<input type="hidden" name="old_n" value="'.$n.'">';
article_create_edit_form($article);
echo '<input type="submit" name="co" value="Save" class="button10"></form>';
ift();
}


######################################################################################
######################################################################################
######################################################################################

function article_created($in) {
global $s;
$old_n = $in[old_n];
$in = $in[article][0];
if (!check_admin_categories('a',$in[categories])) problem('You do not have permission to add an article to the selected category/categories.');
$usit = item_updated_get_usit('a',$in);
$rating = rating_update_get_average('a',$in[n],$in[rates]);
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$c = categories_edited($in[categories]);
$in = replace_array_for_articles($in);
$en_cats = has_some_enabled_categories('a',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'a');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';
if ($in[username]) $user = get_user_variables(0,$in[username]);
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
dq("insert into $s[pr]articles values(NULL,'$in[title]','$in[description]','$in[text]','$in[keywords]','','$in[map]$map_test','$c[categories]','$c[categories_path]','$user[n]','$in[name]','$in[email]','$created',0,'$in[password]','$in[hits]',0,'$rating[average]','$rating[total_votes]','$in[pick]','0','0','$t1','$t2','$status','$en_cats','$in[rewrite_url]')",1);
$n = mysql_insert_id();
if ($old_n) copy_files('a',$old_n,$n);
else upload_files('a',$n,0,0,'');
add_update_user_items('a',$n,$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('a',$in[categories],'');
update_item_index('a',$n);
dq("insert into $s[pr]u_to_email values('a','$n')",1);
if ($in[email_owner])
{ $email[to] = $in[email];
  $email[title] = $in[title]; $email[description] = $in[description]; $email[n] = $n; 
  $email[detail_url] = get_detail_page_url('a',$n,'',0,1);
  mail_from_template('article_added_by_admin.txt',$email);
}
ih();
echo info_line('Article Created');
show_one_article($n);
ift();
}

######################################################################################

function article_edited($in) {
global $s;
foreach ($in[article] as $k=>$v)
{ $article = $v; 
  if (!check_admin_categories('a',$article[categories])) problem('You do not have permission to add/edit an article in the selected category/categories.');
  $article[n] = $k;
  article_edited_process($article);
}
$s[info] = info_line('Article Updated');
article_create_edit($article[n]);
exit;
}

######################################################################################
######################################################################################
######################################################################################

function article_manage($in) {
global $s;
$article = get_item_variables('a',$in[n]);
if (!check_admin_categories('a',$article[c])) problem('You do not have permission to manage article in the category/categories where this article is listed.');
if ($in[what]) { $status = 'enabled'; $Status = 'Enabled'; }
else { $status = 'disabled'; $Status = 'Disabled'; }
dq("update $s[pr]articles set status = '$status' where n = '$in[n]' and status != 'queue'",1);
recount_items_cats('a',$article[c]);
ih();
echo info_line('Selected Article Has Been '.$Status);
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}

#################################################################################
#################################################################################
#################################################################################

function article_delete($n) {
global $s;
$article = get_item_variables('a',$n);
if (!check_admin_categories('a',$article[c])) problem('You do not have permission to manage articles in the category/categories where this article is listed.');
delete_items('a',$n);
recount_items_cats('a',$article[c]);
ih(); 
echo info_line('Selected Article Has Been Deleted');
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}


##################################################################################
##################################################################################
##################################################################################

?>