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
get_messages('article_create_edit.php');
include($s[phppath].'/data/data_forms.php');
$s[a_max_cats] = $s[a_max_cats_users];
check_post_rights('a');

switch ($_GET[action]) {
case 'articles_list'			: articles_list($_GET[email]);
case 'send_password'			: send_password($_GET[n]);
case 'article_edit'				: article_edit($_GET);
case 'delete_image'				: delete_image($_GET);
case 'article_delete'			: article_delete($_GET[n]);
}
if (!$_POST) article_login_form($_GET[n]);
switch ($_POST[action]) {
case 'article_edited'			: article_edited($_POST);
}

############################################################################
############################################################################
############################################################################

function article_login_form($n) {
global $s;
if ((!is_numeric($n)) AND ($s[LUG_u_n])) articles_list();
$a[n] = $n;
page_from_template('article_edit_login.html',$a);
}

############################################################################

function send_password($n) {
global $s,$m;
$a = get_item_variables('a',$n);
if (!$a[email]) problem($m[not_found]);
$a[to] = $a[email];
$a[login_url] = "$s[site_url]/article_edit.php?n=$n";
mail_from_template('article_password_remind.txt',$a);
$s[info] = info_line($m[pass_sent].' '.$a[email]);
article_login_form($n);
}

############################################################################
############################################################################
############################################################################

function articles_list($email) {
global $s,$m;
$email = replace_once_text($email);
if ($s[LUG_u_n]) $q = dq("select * from $s[pr]articles where owner = '$s[LUG_u_n]' AND (status = 'enabled' or status = 'disabled')",1);
else $q = dq("select * from $s[pr]articles where email = '$email' AND (status = 'enabled' or status = 'disabled')",1);
while ($article = mysql_fetch_assoc($q))
{ $article[created] = datum($article[created],0);
  $article[item_details_url] = get_detail_page_url('a',$article[n],$article[rewrite_url],0,1);
  if ($s[LUG_u_n]) { $article[hide_send_password_begin] = '<!--'; $article[hide_send_password_end] = '-->'; }
  if ((!$s[LUG_u_n]) OR (!$s[users_can_delete_a])) { $article[hide_delete_begin] = '<!--'; $article[hide_delete_end] = '-->'; }
  $a[articles] .= parse_part('article_edit_list.txt',$article);
}
if (!$a[articles]) $a[articles] = $m[no_articles];
page_from_template('article_edit_list.html',$a);
}

############################################################################
############################################################################
############################################################################

function article_edit($in) {
global $s,$m;
$article = check_article_access_rights($in[n],$in[password]);
if (!$in[action]) $in[action] = 'article_edit';
$article = array_merge((array)$article,(array)$in);
$a = article_create_edit_form_public($article,$in[n]);
page_from_template('article_edit.html',$a);
}

######################################################################
######################################################################
######################################################################

function article_edited($in) {
global $s,$m;
check_article_access_rights($in[n],'');
$in = article_form_control($in); $a = $in[1];
if ($in[0])
{ $a[info] = info_line($m[errorsfound],join ('<br />',$in[0]));
  article_edit($a);
}
$usit = item_updated_get_usit('a',$a,1); $a = array_merge((array)$a,(array)$usit);
if ($s[a_r_br]) $a[text] = str_replace("\n",'<br />',$a[text]);
if ($s[a_r_url]) $a[text] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a target="_blank" href="$1">$1</a>',$a[text]);
article_edited_to_database($a);
article_edited_send_emails($a);
article_created_edited_thankyou($a,'article_edited.html');
}

######################################################################

function check_article_access_rights($n,$password) {
global $s,$m;
if ($_SESSION['article_edit_'.$n]) return get_item_variables('a',$n);
if (($n) AND (!$password) AND (!$s[LUG_u_n])) article_login_form($n);
//elseif (!$password) problem($m[no_auth_article]); // edited
if ($n) $article = get_item_variables('a',$n);
if (!$article[n]) problem($m[not_found]);
elseif ((!$password) AND ($s[LUG_u_n]!=$article[owner])) problem($m[no_auth_article]);
elseif (($password) AND ($password!=$article[password])) problem($m[wrong_pass]);
$_SESSION['article_edit_'.$n] = 1;
return $article;
}

######################################################################

function article_edited_to_database($in) {
global $s,$m;
$old = get_item_variables('a',$in[n],0);
$c = categories_edited($in[c]);
$en_cats = has_some_enabled_categories('a',$in[c]);
dq("delete from $s[pr]articles where n = '$in[n]' and (status = 'queue' or status = 'wait')",1);
$rewrite_url = discover_rewrite_url($in[title],0,'a'); 
if ($s[LUG_u_n])
{ $owner = get_user_variables($s[LUG_u_n]);
  $in[email] = $owner[email]; $in[name] = $owner[name]; $in[password] = $owner[password];
}
if (!$s[a_v_start_end]) { $in[t1] = $old[t1]; $in[t2] = $old[t2]; }
if ($s[a_autoapr])
{ dq("update $s[pr]articles set title = '$in[title]', description = '$in[description]', text = '$in[text]', keywords = '$in[keywords]', map = '$in[map]', c = '$c[categories]', c_path = '$c[categories_path]', owner = '$s[LUG_u_n]', name = '$in[name]', email = '$in[email]', password = '$in[password]', updated = '$s[cas]', t1 = '$in[t1]', t2 = '$in[t2]', status = 'enabled', en_cats = '$en_cats', rewrite_url = '$rewrite_url' where n = '$in[n]'",1);
  add_update_user_items('a',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('a',$in[n],0,1,$in[delete_image]);
  $s[use_for] = 'a';
}
else
{ dq("insert into $s[pr]articles values ('$in[n]','$in[title]','$in[description]','$in[text]','$in[keywords]','','$in[map]','$c[categories]','$c[categories_path]','$s[LUG_u_n]','$in[name]','$in[email]','$old[created]',0,'$in[password]',0,0,0,0,'$old[pick]','$old[popular]','$old[comments]','$in[t1]','$in[t2]','queue','$en_cats','$rewrite_url')",1);
  add_update_user_items('a_q',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('a',$in[n],1,1,$in[delete_image]);
  $s[use_for] = 'a_q';
}
update_item_index('a',$in[n]);
update_item_image1('a',$in[n]);
recount_items_cats('a',$in[c],$old[c]);
}

######################################################################

function article_edited_send_emails($a) {
global $s,$m;
$x = user_defined_items_emails($a[all_user_items_list],$a[value_codes],$a[value_texts],'_user_item.txt',1); $a = array_merge((array)$a,(array)$x);
$y = list_of_categories_for_item('a',0,$a[c],"\n",1); $a[categories] = $y[categories_names];
$a[user] = $s[LUG_u_username];
if ($s[a_i_new]) mail_from_template('article_admin.txt',$a);
}

############################################################################
############################################################################
############################################################################

function article_delete($n) {
global $s,$m;
if (!$s[users_can_delete_a]) exit;
check_article_access_rights($n,'');
delete_items('a',$n);
$s[info] = info_line($m[article_deleted]);
articles_list();
}

############################################################################
############################################################################
############################################################################

?>