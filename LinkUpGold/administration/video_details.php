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

include('./videos_functions.php');

switch ($_GET[action]) {
case 'video_create'			: video_create_edit(0);
case 'video_edit'			: video_create_edit($_GET[n]);
case 'video_copy'			: video_create_edit($_GET[n]);
case 'video_delete'			: video_delete($_GET[n]);
case 'video_manage'			: video_manage($_GET);
case 'delete_image'			: delete_image('v',$_GET);
}
switch ($_POST[action]) {
case 'video_created'		: video_created($_POST);
case 'video_edited'			: video_edited($_POST);
case 'video_delete'			: video_delete($_POST[n]);
}

#################################################################################
#################################################################################
#################################################################################

function video_create_edit($n) {
global $s;

if ($_GET[action]) $current_action = $_GET[action]; else $current_action = $_POST[action];
if ($current_action != 'video_create')
{ $q = dq("select * from $s[pr]videos where n = '$n'",1);
  $videos = mysql_fetch_assoc($q);
  if (!$videos[n]) problem ("Videos #$n does not exist.");
  $q = dq("select * from $s[pr]usit_values where use_for = 'v' AND n = '$n'",1);
  while ($x = mysql_fetch_assoc($q))
  { $videos['user_item_'.$x[item_n]][code] = $x[value_code];
    $videos['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
else $videos[n] = 0;
$videos[current_action] = $current_action;
switch ($current_action) {
case 'video_create'		: $action = 'video_created'; $info = 'Create New Video'; break;
case 'video_edit'		: $action = 'video_edited'; $info = 'Edit Selected Video'; break;
case 'video_edited'		: $action = 'video_edited'; $info = 'Edit Selected Video'; break;
case 'video_copy'		: $action = 'video_created'; $info = 'Copy Selected Video'; $videos[old_number] = $videos[n]; $videos[n] = 0; break;
}

ih();
echo $s[info];
echo page_title($info);
echo '<form ENCTYPE="multipart/form-data" action="video_details.php" method="post">'.check_field_create('admin').'<input type="hidden" name="action" value="'.$action.'">';
if ($current_action=='video_copy') echo '<input type="hidden" name="old_n" value="'.$n.'">';
video_create_edit_form($videos);
echo '<input type="submit" name="co" value="Save" class="button10"></form>';
ift();
}


######################################################################################
######################################################################################
######################################################################################

function video_created($in) {
global $s;
$old_n = $in[old_n];
$in = $in[video][0];
if (!check_admin_categories('v',$in[categories])) problem('You do not have permission to add an video to the selected category/categories.');
$usit = item_updated_get_usit('v',$in);
$rating = rating_update_get_average('v',$in[n],$in[rates]);
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$c = categories_edited($in[categories]);
$in = replace_array_for_videos($in);
$in[description] = refund_html($in[description]);
$en_cats = has_some_enabled_categories('v',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'v');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';
if ($in[username]) $user = get_user_variables(0,$in[username]);
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
if (!$in[password]) $in[password] = get_random_password($in[title],$in[description],$c[categories]);
$youtube_id = get_youtube_id($in[youtube_url]);
if ((!$in[youtube_thumbnail]) AND ($youtube_id)) $in[youtube_thumbnail] = str_replace('#%id%#',$youtube_id,$s[youtube_thumbnail]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
$in[video_code] = refund_html($in[video_code]);
dq("insert into $s[pr]videos values(NULL,'$in[title]','$in[description]','$in[keywords]','','$in[map]$map_test','$c[categories]','$c[categories_path]','$in[name]','$in[email]','$user[n]','$created',0,'$in[password]','$rating[average]','$rating[total_votes]','$youtube_id','$in[youtube_length]','$in[youtube_thumbnail]','$in[video_code]','$in[hits]',0,'0','0','$in[pick]','$t1','$t2','$status','$en_cats','$in[rewrite_url]')",1);
$n = mysql_insert_id();
if ($old_n) copy_files('v',$old_n,$n);
else upload_files('v',$n,0,0,'');
add_update_user_items('v',$n,$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('v',$in[categories],'');
update_item_index('v',$n);
dq("insert into $s[pr]u_to_email values('v','$n')",1);
if ($in[email_owner])
{ $email[to] = $in[email];
  $email[title] = $in[title]; $email[description] = $in[description]; $email[n] = $n; 
  $email[detail_url] = get_detail_page_url('v',$n,'',0,1);
  mail_from_template('videos_added_by_admin.txt',$email);
}
ih();
echo info_line('Videos Created');
show_one_video($n);
ift();
}

######################################################################################

function video_edited($in) {
global $s;
foreach ($in['video'] as $k=>$v)
{ $video = $v; 
  if (!check_admin_categories('v',$video[categories])) problem('You do not have permission to add/edit an videos in the selected category/categories.');
  $video[n] = $k;
  video_edited_process($video);
}
$s[info] = info_line('Videos Updated');
video_create_edit($video[n]);
exit;
}

######################################################################################
######################################################################################
######################################################################################

function video_manage($in) {
global $s;
$videos = get_item_variables('v',$in[n]);
if (!check_admin_categories('v',$videos[c])) problem('You do not have permission to manage videos in the category/categories where this videos is listed.');
if ($in[what]) { $status = 'enabled'; $Status = 'Enabled'; }
else { $status = 'disabled'; $Status = 'Disabled'; }
dq("update $s[pr]videos set status = '$status' where n = '$in[n]' and status != 'queue'",1);
recount_items_cats('v',$videos[c]);
ih();
echo info_line('Selected Video Has Been '.$Status);
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}

#################################################################################
#################################################################################
#################################################################################

function video_delete($n) {
global $s;
$videos = get_item_variables('v',$n);
if (!check_admin_categories('v',$videos[c])) problem('You do not have permission to manage videos in the category/categories where this videos is listed.');
delete_items('v',$n);
recount_items_cats('v',$videos[c]);
ih(); 
echo info_line('Selected Video Has Been Deleted');
echo '<br /><br /><a href="javascript: history.go(-1)">Back</a>';
ift();
}


##################################################################################
##################################################################################
##################################################################################

?>