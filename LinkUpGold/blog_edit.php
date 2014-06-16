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
include('./f_create_edit.php');
$s[selected_menu] = 2;
get_messages('blog_create_edit.php');
include($s[phppath].'/data/data_forms.php');
$s[b_max_cats] = $s[b_max_cats_users];
check_post_rights('b');

switch ($_GET[action]) {
case 'blogs_list'			: blogs_list($_GET[email]);
case 'send_password'		: send_password($_GET[n]);
case 'blog_edit'			: blog_edit($_GET);
case 'delete_image'			: delete_image($_GET);
case 'blog_delete'			: blog_delete($_GET[n]);
}
if (!$_POST) blog_login_form($_GET[n]);
switch ($_POST[action]) {
case 'blog_edited'			: blog_edited($_POST);
}

############################################################################
############################################################################
############################################################################

function blog_login_form($n) {
global $s;
if ((!is_numeric($n)) AND ($s[LUG_u_n])) blogs_list();
$a[n] = $n;
page_from_template('blog_edit_login.html',$a);
}

############################################################################

function send_password($n) {
global $s,$m;
$a = get_item_variables('b',$n);
if (!$a[email]) problem($m[not_found]);
$a[to] = $a[email];
$a[login_url] = "$s[site_url]/blog_edit.php?n=$n";
mail_from_template('blog_password_remind.txt',$a);
$s[info] = info_line($m[pass_sent].' '.$a[email]);
blog_login_form($n);
}

############################################################################
############################################################################
############################################################################

function blogs_list($email) {
global $s,$m;
$email = replace_once_text($email);
if ($s[LUG_u_n]) $q = dq("select * from $s[pr]blogs where owner = '$s[LUG_u_n]' AND (status = 'enabled' or status = 'disabled')",1);
else $q = dq("select * from $s[pr]blogs where email = '$email' AND (status = 'enabled' or status = 'disabled')",1);
while ($blog = mysql_fetch_assoc($q))
{ $blog[created] = datum($blog[created],0);
  $blog[item_details_url] = get_detail_page_url('b',$blog[n],$blog[rewrite_url],0,1);
  if ($s[LUG_u_n]) { $blog[hide_send_password_begin] = '<!--'; $blog[hide_send_password_end] = '-->'; }
  if ((!$s[LUG_u_n]) OR (!$s[users_can_delete_b])) { $blog[hide_delete_begin] = '<!--'; $blog[hide_delete_end] = '-->'; }
  $a[blogs] .= parse_part('blog_edit_list.txt',$blog);
}
if (!$a[blogs]) $a[blogs] = $m[no_blogs];
page_from_template('blog_edit_list.html',$a);
}

############################################################################
############################################################################
############################################################################

function blog_edit($in) {
global $s,$m;
$blog = check_blog_access_rights($in[n],$in[password]);
if (!$in[action]) $in[action] = 'blog_edit';
$blog = array_merge((array)$blog,(array)$in);
$a = blog_create_edit_form_public($blog,$in[n]);
page_from_template('blog_edit.html',$a);
}

######################################################################
######################################################################
######################################################################

function blog_edited($in) {
global $s,$m;
check_blog_access_rights($in[n],'');
$in = blog_form_control($in); $a = $in[1];
if ($in[0])
{ $a[info] = info_line($m[errorsfound],join ('<br />',$in[0]));
  blog_edit($a);
}
$usit = item_updated_get_usit('b',$a,1); $a = array_merge((array)$a,(array)$usit);
if ($s[b_r_br]) $a[text] = str_replace("\n",'<br />',$a[text]);
if ($s[b_r_url]) $a[text] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a target="_blank" href="$1">$1</a>',$a[text]);
blog_edited_to_database($a);
blog_edited_send_emails($a);
blog_created_edited_thankyou($a,'blog_edited.html');
}

######################################################################

function check_blog_access_rights($n,$password) {
global $s,$m;
if ($_SESSION['blog_edit_'.$n]) return get_item_variables('b',$n);
if (($n) AND (!$password) AND (!$s[LUG_u_n])) blog_login_form($n);
//elseif (!$password) problem($m[no_auth_blog]); // edited
if ($n) $blog = get_item_variables('b',$n);
if (!$blog[n]) problem($m[not_found]);
elseif ((!$password) AND ($s[LUG_u_n]!=$blog[owner])) problem($m[no_auth_blog]);
elseif (($password) AND ($password!=$blog[password])) problem($m[wrong_pass]);
$_SESSION['blog_edit_'.$n] = 1;
return $blog;
}

######################################################################

function blog_edited_to_database($in) {
global $s,$m;
$old = get_item_variables('b',$in[n],0);
$c = categories_edited($in[c]);
$en_cats = has_some_enabled_categories('b',$in[c]);
dq("delete from $s[pr]blogs where n = '$in[n]' and (status = 'queue' or status = 'wait')",1);
$rewrite_url = discover_rewrite_url($in[title],0,'b'); 
if ($s[LUG_u_n])
{ $owner = get_user_variables($s[LUG_u_n]);
  $in[email] = $owner[email]; $in[name] = $owner[name]; $in[password] = $owner[password];
}
if (!$s[b_v_start_end]) { $in[t1] = $old[t1]; $in[t2] = $old[t2]; }
if ($s[b_autoapr])
{ dq("update $s[pr]blogs set title = '$in[title]', description = '$in[description]', text = '$in[text]', keywords = '$in[keywords]', map = '$in[map]', c = '$c[categories]', c_path = '$c[categories_path]', owner = '$s[LUG_u_n]', name = '$in[name]', email = '$in[email]', password = '$in[password]', updated = '$s[cas]', t1 = '$in[t1]', t2 = '$in[t2]', status = 'enabled', en_cats = '$en_cats', rewrite_url = '$rewrite_url' where n = '$in[n]'",1);
  add_update_user_items('b',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('b',$in[n],0,1,$in[delete_image]);
  $s[use_for] = 'b';
}
else
{ dq("insert into $s[pr]blogs values ('$in[n]','$in[title]','$in[description]','$in[text]','$in[keywords]','','$in[map]','$c[categories]','$c[categories_path]','$s[LUG_u_n]','$in[name]','$in[email]','$old[created]',0,'$in[password]',0,0,0,0,'$old[pick]','$old[popular]','$old[comments]','$in[t1]','$in[t2]','queue','$en_cats','$rewrite_url')",1);
  add_update_user_items('b_q',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('b',$in[n],1,1,$in[delete_image]);
  $s[use_for] = 'b_q';
}
update_item_index('b',$in[n]);
update_item_image1('b',$in[n]);
recount_items_cats('b',$in[c],$old[c]);
}

######################################################################

function blog_edited_send_emails($a) {
global $s,$m;
$x = user_defined_items_emails($a[all_user_items_list],$a[value_codes],$a[value_texts],'_user_item.txt',1); $a = array_merge((array)$a,(array)$x);
$y = list_of_categories_for_item('b',0,$a[c],"\n",1); $a[categories] = $y[categories_names];
$a[user] = $s[LUG_u_username];
if ($s[b_i_new]) mail_from_template('blog_admin.txt',$a);
}

############################################################################
############################################################################
############################################################################

function blog_delete($n) {
global $s,$m;
if (!$s[users_can_delete_b]) exit;
check_blog_access_rights($n,'');
delete_items('b',$n);
$s[info] = info_line($m[blog_deleted]);
blogs_list();
}

############################################################################
############################################################################
############################################################################

?>