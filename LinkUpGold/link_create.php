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
$s[selected_menu] = 1;
get_messages('link_create_edit.php');
include($s[phppath].'/data/data_forms.php');
$s[l_max_cats] = $s[l_max_cats_users];
if ($_GET[action]=='confirm') confirm($_GET);
check_post_rights('l');

if ($_POST[action]=='link_submitted_meta') link_submitted_meta($_POST);
elseif ($_POST) link_submitted($_POST);
if ($_GET[action]=='link_submit_meta') link_submit_meta($_GET[c]);
link_submit_form($_GET[c],$_POST);

#############################################################################
#############################################################################
#############################################################################

function link_submit_meta($selected_category) {
global $s;
$a[selected_category] = $selected_category;
page_from_template('link_submit_meta.html',$a);
}

#############################################################################

function link_submitted_meta($in) {
global $s,$m;
$form[url] = $in[url];
$metatags = get_metatags($form[url]);
$s[subm_all] = 1;
link_submit_form($in[selected_category],$metatags);
}

#############################################################################

function link_submit_form($c,$in) {
global $s,$m;
if (is_numeric($c)) $s[selected_category] = $c;
unset($in[image_control]);
$a = link_create_edit_form_public($in,0);
page_from_template('link_submit.html',$a);
}

#############################################################################
#############################################################################
#############################################################################

function link_submitted($in) {
global $s,$m;
$in = link_form_control($in); $a = $in[1];
if ($in[0])
{ $a[info] = info_line($m[errorsfound],implode('<br />',$in[0]));
  link_submit_form (0,$a);
}
$usit = item_updated_get_usit('l',$a); $a = array_merge((array)$a,(array)$usit);
$a = link_created_to_database($a);
link_submitted_send_emails($a);
link_created_edited_thankyou($a,'link_submitted.html');
}

#############################################################################

function link_created_to_database($in) {
global $s,$m;

$in[created_timestamp] = $old[created];
$in[t1_timestamp] = $in[t1];
$in[t2_timestamp] = $in[t2];
$in[categories] = $in[c];
$in[user_n] = $s[LUG_u_n];

if ($s[LUG_u_n])
{ $owner = get_user_variables($s[LUG_u_n]);
  $in[email] = $owner[email]; $in[name] = $owner[name];
}

if (($s[conf_sub]) AND (!$s[LUG_u_n]))
{ $in[status] = 'wait';
  $in[n] = enter_link($in);
  $code = get_random_password($s[cas],$in[url],$in[title]);
  add_update_user_items('l_w',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  $in[to] = $in[email];
  $in[confirm_url] = "$s[site_url]/link_create.php?action=confirm&amp;n=$in[n]&amp;code=$code";
  $in[days] = $s[l_unconfirmed_delete_after];
  mail_from_template('link_confirm.txt',$in);
  dq("insert into $s[pr]unconfirmed values ('l','$in[n]','$code')",1);
  upload_files('l',$in[n],1,1,'');
  $s[use_for] = 'l_w'; 
}
elseif ($s[autoapr])
{ $in[status] = 'enabled';
  $in[n] = enter_link($in);
  add_update_user_items('l',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('l',$in[n],0,1,'');
  $s[use_for] = 'l'; 
}
else 
{ $in[status] = 'queue';
  $in[n] = enter_link($in);
  add_update_user_items('l_q',$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
  upload_files('l',$in[n],1,1,'');
  $s[use_for] = 'l_q'; 
}
dq("insert into $s[pr]links_recips_info values ('$in[n]','$in[i_recip]')",1);
update_item_index('l',$in[n]);
recount_items_cats('l',$in[c],'');
return $in;
}

#############################################################################

function link_submitted_send_emails($a) {
global $s,$m;
$x = user_defined_items_emails($a[all_user_items_list],$a[value_codes],$a[value_texts],'_user_item.txt',1); $a = array_merge((array)$a,(array)$x);
$categories = $a[c];
$y = list_of_categories_for_item('l',0,$a[c],"\n",1); $a[categories] = $y[categories_names]; $a[categories_urls] = $y[categories_urls];
if ((!$s[conf_sub]) OR ($s[LUG_u_n]))
{ if ($s[l_i_new])
  { $a[to] = $s[mail];
    mail_from_template('link_admin.txt',$a);
  }
  if (($s[l_i_new_admins]) AND (count($categories)==1))
  { $q = dq("select email from $s[pr]admins_cats,$s[pr]admins where $s[pr]admins_cats.category = '$categories[0]' AND $s[pr]admins_cats.n = $s[pr]admins.n group by $s[pr]admins.n",1);
    while ($x = mysql_fetch_row($q)) { $a[to] = $x[0]; mail_from_template('link_admin.txt',$a); }
  }
}
if ($s[l_i_owner]) 
{ $a[to] = $a[email];
  $a[detail_url] = get_detail_page_url('l',$a[n],'',0,1);
  if ((!$s[conf_sub]) OR ($s[LUG_u_n]))
  { if ($s[autoapr]) mail_from_template('link_added.txt',$a);
    else mail_from_template('link_queued.txt',$a);
  }
}
}

#############################################################################
#############################################################################
#############################################################################

function confirm($in) {
global $s,$m;
if (!is_numeric($in[n])) exit;
$q = dq("select * from $s[pr]unconfirmed where what = 'l' and n = '$in[n]'",1);
$unconfirmed = mysql_fetch_assoc($q);
if (!$unconfirmed[n]) problem($m[not_found]);
if ($unconfirmed[code]!=$in[code]) problem($m[w_code]);
$n = $unconfirmed[n];

if ($s[autoapr])
{ dq("update $s[pr]links set status = 'enabled' where n = '$n'",1);
  dq("update $s[pr]usit_values set use_for = 'l' where use_for = 'l_w' and n = '$n'",1);
  $s[use_for] = 'l';
  $link = get_item_variables('l',$n,0);
}
else
{ dq("update $s[pr]links set status = 'queue' where n = '$n'",1);
  dq("update $s[pr]usit_values set use_for = 'l_q' where use_for = 'l_w' and n = '$n'",1);
  $s[use_for] = 'l_q';
  $link = get_item_variables('l',$n,1);
}
dq("delete from $s[pr]unconfirmed where what = 'l' and n = '$n'",1);
// emails
$y = user_defined_items_display($s[use_for],'','',$n,'_user_item.txt',1,0,1,0); $link[user_defined] = $y[$n];
$y = list_of_categories_for_item('l',0,$link[c],"\n",1); $link[categories] = $y[categories_names]; $link[categories_urls] = $y[categories_urls];
if ($s[l_i_new])
{ $link[to] = $s[mail];
  mail_from_template('link_admin.txt',$link);
}
$categories = explode(' ',str_replace('_','',$link[c]));
if (($s[l_i_new_admins]) AND (count($categories)==1))
{ $q = dq("select email from $s[pr]admins_cats,$s[pr]admins where $s[pr]admins_cats.category = '$categories[0]' AND $s[pr]admins_cats.n = $s[pr]admins.n group by $s[pr]admins.n",1);
  while ($x = mysql_fetch_row($q)) { $link[to] = $x[0]; mail_from_template('link_admin.txt',$link); }
}
if ($s[l_i_owner]) 
{ $link[to] = $link[email];
  $link[detail_url] = get_detail_page_url('l',$link[n],'',0,1);  
  if ($s[autoapr]) mail_from_template('link_added.txt',$link);
  else mail_from_template('link_queued.txt',$link);
}
recount_items_cats('l',$link[c],'');
link_created_edited_thankyou($link,'link_confirmed.html');
}

#############################################################################
#############################################################################
#############################################################################

?>