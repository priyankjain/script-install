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

if (!$_POST) blog_submit_form($_GET[c],$_POST);
else blog_submitted($_POST);

###########################################################################
###########################################################################
###########################################################################

function blog_submit_form($c,$in) {
global $s,$m;
if (is_numeric($c)) $s[selected_category] = $c;
unset($in[image_control]);
$a = blog_create_edit_form_public($in,0);
page_from_template('blog_submit.html',$a);
}

###########################################################################
###########################################################################
###########################################################################

function blog_submitted($in) {
global $s,$m;
$in = blog_form_control($in); $a = $in[1];
if ($a[preview])
{ $a[preview] = blog_submitted_get_preview($a);
  blog_submit_form(0,$a);
}
if ($in[0])
{ $a[info] = info_line($m[errorsfound],implode('<br />',$in[0]));
  blog_submit_form(0,$a);
}
if ($s[b_r_br]) $a[text] = str_replace("\n",'<br />',$a[text]);
if ($s[b_r_url]) $a[text] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a target="_blank" href="$1">$1</a>',$a[text]);
if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $a = array_merge((array)$a,(array)$user);
}
$usit = item_updated_get_usit('b',$a); $a = array_merge((array)$a,(array)$usit);
$a = blog_submitted_write_to_database($a);
blog_submitted_send_emails($a);
blog_created_edited_thankyou($a,'blog_submitted.html');
}

#############################################################################

function blog_submitted_get_preview($in) {
global $s;
if ($s[b_r_br]) $in[text] = str_replace("\n",'<br />',$in[text]);
if ($s[b_r_url]) $in[text] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@','<a target="_blank" href="$1">$1</a>',$in[text]);
return parse_part('blog_submitted_preview.txt',$in);
}

#############################################################################

function blog_submitted_send_emails($a) {
global $s,$m;
$x = user_defined_items_emails($a[all_user_items_list],$a[value_codes],$a[value_texts],'_user_item.txt',1); $a = array_merge((array)$a,(array)$x);
$y = list_of_categories_for_item('b',0,$a[c],"\n",1); $a[categories] = $y[categories_names]; $a[categories_urls] = $y[categories_urls];
if ($s[b_i_new]) mail_from_template('blog_admin.txt',$a);
if ($s[b_i_owner])
{ $a[to] = $a[email];
  $a[detail_url] = get_detail_page_url('b',$a[n],'',0,1);
  mail_from_template('blog_added.txt',$a);
}
}

###########################################################################

function blog_submitted_write_to_database($in) {
global $s,$m;
$c = categories_edited($in[c]);
$en_cats = has_some_enabled_categories('b',$in[c]);
$in[rewrite_url] = discover_rewrite_url($in[title],0,'b');
if ($s[b_autoapr]) { $status = 'enabled'; $s[use_for] = 'b'; $queue = 0; }
else { $status = 'queue'; $s[use_for] = 'b_q'; $queue = 1; }
dq("insert into $s[pr]blogs values (NULL,'$in[title]','$in[description]','$in[text]','$in[keywords]','','$in[map]','$c[categories]','$c[categories_path]','$s[LUG_u_n]','$in[name]','$in[email]','$s[cas]',0,'$in[password]',0,0,0,0,0,0,0,'$in[t1]','$in[t2]','$status','$en_cats','$in[rewrite_url]')",1);
$in[n] = mysql_insert_id();
add_update_user_items($s[use_for],$in[n],$in[all_user_items_list],$in[value_codes],$in[value_texts]);
if ($s[b_autoapr])
{ dq("insert into $s[pr]u_to_email values('b','$in[n]')",1);
}
upload_files('b',$in[n],$queue,1,'');
update_item_index('b',$in[n]);
recount_items_cats('b',$in[c],'');
return $in;
}

###########################################################################
###########################################################################
###########################################################################

?>