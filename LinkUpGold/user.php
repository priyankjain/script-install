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
$s[selected_menu] = 6;
get_messages('user.php');
include($s[phppath].'/data/data_forms.php');

switch ($_GET[action]) {
case 'logged_in'			: logged_in($_GET);
case 'user_confirmed'		: user_confirmed($_GET);
case 'user_log_off'			: user_log_off();
case 'user_remind'			: user_remind();
case 'user_edit'			: user_edit();
case 'user_favorites'		: user_favorites();
case 'user_home'			: user_home($_GET);
case 'user_friends'			: user_friends();
case 'user_friend_request'	: user_friend_request($_GET[n]);
case 'user_friend_accept'	: user_friend_accept($_GET[n]);
case 'user_friend_delete'	: user_friend_delete($_GET[n]);
case 'user_wall'			: user_wall();
case 'user_wall_delete'		: user_wall_delete($_GET[n]);
case 'user_orders'			: user_orders($_GET[n]);
case 'user_adlinks'			: user_adlinks($_GET[n]);
case 'user_wall_posted'		: user_wall_posted($_GET);// new ajax
}
switch ($_POST[action]) {
case 'logged_in'			: logged_in($_POST);
case 'user_joined'			: user_joined($_POST);
case 'user_edited'			: user_edited($_POST);
case 'user_reminded'		: user_reminded($_POST);
case 'user_wall_posted'		: user_wall_posted($_POST);
}
user_join();

#########################################################################
#########################################################################
#########################################################################

function user_join($in) {
global $s,$m;
if ($in) $s = array_merge($s,(array)$in);
$in[n] = 0;
$in = user_form_get_variables($in);
if ($s[u_v_captcha]) $in[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
//$in[field_terms] = parse_part('form_field_terms.txt',$x);
page_from_template('user_join.html',$in);
}

#########################################################################

function user_form_get_variables($in) {
global $s,$m;

$x[item_name] = $m[username]; $x[item_name] .= " *"; $x[field_name] = 'username'; $x[field_value] = $in[username]; $x[field_maxlength] = 15; $in[field_username] = parse_part('form_username.txt',$x);
$x[item_name] = $m[password]; if (!$in[n]) $x[item_name] .= " *"; $x[field_name] = 'password'; if (!$in[n]) $x[password] = $in[password]; $x[field_maxlength] = 15; if (!$in[n]) { $x[hide_create_begin] = '<!--'; $x[hide_create_end] = '-->'; } $in[field_password] = parse_part('form_password.txt',$x);
$x[item_name] = $m[email]; $x[item_name] .= " *"; $x[field_name] = 'email'; $x[field_value] = $in[email]; $x[field_maxlength] = 255; $in[field_email] = parse_part('form_field.txt',$x);
if ($s[u_v_name]) { $x[item_name] = $m[name]; if ($s[u_r_name]) $x[item_name] .= " *"; $x[field_name] = 'name'; $x[field_value] = $in[name]; $x[field_maxlength] = 255; $in[field_name] = parse_part('form_field.txt',$x); }
if ($s[u_v_company]) { $x[item_name] = $m[company]; if ($s[u_r_company]) $x[item_name] .= " *"; $x[field_name] = 'company'; $x[field_value] = $in[company]; $x[field_maxlength] = 255; $in[field_company] = parse_part('form_field.txt',$x); }
if ($s[u_v_detail]) { $x[item_name] = $m[public_article]; $x[field_name] = 'detail'; $x[field_value] = $in[detail]; $x[field_maxlength] = $s[u_max_detail]; $x[field_maxlength_now] = $s[u_max_detail] - strlen($in[detail]); if ($s[u_details_html_editor]) { $x[html_editor] = get_fckeditor('detail',$in[detail],'PublicToolbar'); $in[field_detail] = parse_part('form_detail_html.txt',$x); } else $in[field_detail] = parse_part('form_detail_textarea.txt',$x); }
$x[item_name] = $m[nick]; $x[item_name] .= " *"; $x[field_name] = 'nick'; $x[field_value] = $in[nick]; $x[field_maxlength] = 255; $in[field_nick] = parse_part('form_field.txt',$x);
if ($s[u_v_address1]) { $x[item_name] = $m[address1]; if ($s[u_r_address1]) $x[item_name] .= " *"; $x[field_name] = 'address1'; $x[field_value] = $in[address1]; $x[field_maxlength] = 255; $in[field_address1] = parse_part('form_field.txt',$x); }
if ($s[u_v_address2]) { $x[item_name] = $m[address2]; if ($s[u_r_address2]) $x[item_name] .= " *"; $x[field_name] = 'address2'; $x[field_value] = $in[address2]; $x[field_maxlength] = 255; $in[field_address2] = parse_part('form_field.txt',$x); }
if ($s[u_v_address3]) { $x[item_name] = $m[address3]; if ($s[u_r_address3]) $x[item_name] .= " *"; $x[field_name] = 'address3'; $x[field_value] = $in[address3]; $x[field_maxlength] = 255; $in[field_address3] = parse_part('form_field.txt',$x); }
if ($s[u_v_country]) { $x[item_name] = $m[country]; if ($s[u_r_country]) $x[item_name] .= " *"; $x[field_name] = 'country'; $x[field_value] = $in[country]; $x[field_maxlength] = 255; $in[field_country] = parse_part('form_field.txt',$x); }
if ($s[u_v_phone1]) { $x[item_name] = $m[phone1]; if ($s[u_r_phone1]) $x[item_name] .= " *"; $x[field_name] = 'phone1'; $x[field_value] = $in[phone1]; $x[field_maxlength] = 255; $in[field_phone1] = parse_part('form_field.txt',$x); }
if ($s[u_v_phone2]) { $x[item_name] = $m[phone2]; if ($s[u_r_phone2]) $x[item_name] .= " *"; $x[field_name] = 'phone2'; $x[field_value] = $in[phone2]; $x[field_maxlength] = 255; $in[field_phone2] = parse_part('form_field.txt',$x); }
if ($s[u_v_site_info])
{ $x[item_name] = $m[url]; if ($s[u_r_site_info]) $x[item_name] .= " *"; $x[field_name] = 'url'; $x[field_value] = $in[url]; $x[field_maxlength] = 255; $in[field_site_info] = parse_part('form_field.txt',$x);
  $x[item_name] = $m[site_title]; if ($s[u_r_site_info]) $x[item_name] .= " *"; $x[field_name] = 'site_title'; $x[field_value] = $in[site_title]; $x[field_maxlength] = 255; $in[field_site_info] .= parse_part('form_field.txt',$x);
}

//if ((!$_GET) AND (!$_POST)) $in[news1] = $in[news2] = $in[news3] = 1;

if ($s[u_v_newsletters])
{ $x[content] = '';
  for ($y=1;$y<=5;$y++)
  { if ($in["news$y"]) $checked = ' checked'; else $checked = '';
    if ($s['news_'.$y]) $x[content] .= '<input type="checkbox" name="news'.$y.'" value="1"'.$checked.'> '.$s['news_'.$y].'<br />';
  }
  $x[item_name] = $m[newsletters];
  $in[field_newsletters] = parse_part('form_no_field.txt',$x);
}
if ($s[u_v_styles])
{ $styles = get_styles_list(0);
  if (!$in[style]) $in[style] = $s[def_style];
  foreach ($styles as $k=>$v)
  { if (is_dir("$s[phppath]/styles/$v"))
    { if ($v==$in[style]) $x = ' selected'; else $x = '';
      $styles .= '<option value="'.$v.'"'.$x.'>'.str_replace('_',' ',$v).'</option>';
    }
  }
  $x[content] = '<select class="select10" name="style">'.$styles.'</select>';
  $x[item_name] = $m[style];
  $in[field_styles] = parse_part('form_no_field.txt',$x);
}
else $in[field_styles] = '<input type="hidden" name="style" value="'.$s[def_style].'">';

$files_pictures = get_item_files_pictures('u',$in[n],0);
if (($s[u_image_small_w_users]) AND ($s[u_image_small_h_users])) { $x[hide_max_size_begin] = '<!--'; $x[hide_max_size_end] = '-->'; }
else { $x[max_image_w] = $s[u_image_max_w_users]; $x[max_image_h] = $s[u_image_max_h_users]; $x[max_image_bytes] = $s[u_image_max_bytes_users]; }
for ($y=1;$y<=$s[u_max_pictures_users];$y++)
{ $x[field_name] = 'image_upload['.$in[n].']['.$y.']';
  $x[image_n] = $y;
  $in[field_pictures] .= parse_part('form_upload_user.txt',$x);
  if (($in[n]) AND ($files_pictures[image_url][$in[n]][$y]))
  { $big_file = preg_replace("/\/$in[n]-/","/$in[n]-big-",$files_pictures[image_url][$in[n]][$y]);
    $x[current_picture] = image_preview_code($files_pictures[image_n][$in[n]][$y],$files_pictures[image_url][$in[n]][$y],$big_file);
    $in[field_pictures] .= parse_part('form_picture_current.txt',$x);
  }
}

return $in;
}

#########################################################################

function user_joined($in) {
global $s,$m;
$x = user_form_control($in); $a = $x[1];
if ($x[0])
{ $a[info] = info_line($m[errorsfound],implode('<br />',$x[0]));
  user_join($a);
}
$a[code] = md5(user_write_to_db($a));
if ($s[user_conf_sub])
{ send_confirmation_email($a);
  $a[hide_continue_begin] = '<!--'; $a[hide_continue_end] = '-->'; 
}
else { $a[hide_confirmation_begin] = '<!--'; $a[hide_confirmation_end] = '-->'; }
$a[days] = $s[user_unconfirmed_delete_after];
user_joined_or_edited($a);
}

############################################################################

function send_confirmation_email($in) {
global $s;
$in[to] = $in[email];
$in[confirm_url] = "$s[site_url]/user.php?action=user_confirmed&amp;user=$in[username]&amp;password=".md5($in[password])."&amp;code=$in[code]";
$in[days] = $s[user_unconfirmed_delete_after];
mail_from_template('user_confirm.txt',$in);
}

############################################################################

function user_confirmed($in) {
global $s,$m;
if (!$s[ip]) $s[ip] = 1;
$q = dq("select * from $s[pr]users where username = '$in[user]' AND confirmed = '0'",1);
$user = mysql_fetch_assoc($q);
if ((!$user[n]) OR (md5($user[password])!=$in[password])) problem($m[no_account]);
$code = md5($user[joined]); if ($code!=$in[code]) problem($m[w_confirm]);
dq("update $s[pr]users set ip = '$s[ip]', confirmed = '1' where n = '$user[n]'",1);
$in[username] = $in[user];
if ($s[i_admin_user_joined])
{ $user[action] = 'joined';
  for ($x=1;$x<=3;$x++) $address[] = $user["address$x"]; $address[] = $user[country]; $user[address] = implode(", ",$address);
  for ($x=1;$x<=3;$x++) $phone[] = $user["phone$x"]; $user[phones] = implode(", ",$phone);
  mail_from_template('user_joined_edited_admin.txt',$user);
}
page_from_template('user_confirmed.html',$in);
}

############################################################################
############################################################################
############################################################################

function user_edit($in) {
global $s,$m;
check_logged_user();
$user = get_user_variables($s[LUG_u_n]);
$user = array_merge((array)$user,(array)$in);
$user = user_form_get_variables($user);
if ($in[action]=='user_edited') $user[username] = $user[password] = '';
if (!$user[username]) $user[username] = $s[LUG_u_username]; if (!$user[password]) $user[password] = $s[LUG_u_password];
if (($user[username]!=$s[LUG_u_username]) OR ($user[password]!=$s[LUG_u_password])) problem($m[login_error]);
$user[info] = $s[info];
page_from_template('user_edit.html',$user);
}

############################################################################

function user_edited($in) {
global $s,$m;
check_logged_user();
check_field();
$old_user_data = get_user_variables($s[LUG_u_n]);
$x = user_form_control($in); $form = $x[1];
if ($x[0])
{ $s[info] = info_line($m[errorsfound],implode('<br />',$x[0]));
  user_edit($form);
}
user_write_to_db($form);
user_edited_send_emails($form,$old_user_data);
user_joined_or_edited($form);
}

############################################################################

function user_edited_send_emails($in,$old_user_data) {
global $s;
for ($x=1;$x<=3;$x++) $address[] = $in["address$x"]; $address[] = $in[country]; $in[address] = implode(", ",$address);
for ($x=1;$x<=3;$x++) $phone[] = $in["phone$x"]; $in[phones] = implode(", ",$phone);
$in[n] = $s[LUG_u_n];
if ($s[i_admin_user_joined])
{ $in[action] = 'edited';
  mail_from_template('user_joined_edited_admin.txt',$in);
}
if ($s[i_edited_user])
{ $in[to] = $old_user_data[email];
  mail_from_template('user_edited.txt',$in);
}
}

############################################################################
############################################################################
############################################################################

function user_write_to_db($in) {
global $s;


if ($s[LUG_u_n])
{ $old = get_user_variables($s[LUG_u_n],'');
  dq("update $s[pr]users set username = '$in[username]', password = '$in[password]', email = '$in[email]', name = '$in[name]', nick = '$in[nick]', company = '$in[company]', detail = '$in[detail]', address1 = '$in[address1]', address2 = '$in[address2]', address3 = '$in[address3]', country = '$in[country]', phone1 = '$in[phone1]', phone2 = '$in[phone2]', url = '$in[url]', site_title = '$in[site_title]', showemail = '$in[showemail]', news1 = '$in[news1]', news2 = '$in[news2]', news3 = '$in[news3]', news4 = '$in[news4]', news5 = '$in[news5]', style = '$in[style]' where n = '$s[LUG_u_n]'",1);
  dq("update $s[pr]articles set email = '$in[email]', name = '$in[name]' where owner = '$s[LUG_u_n]'",1);
  dq("update $s[pr]links set email = '$in[email]', name = '$in[name]' where owner = '$s[LUG_u_n]'",1);
  dq("update $s[pr]comments set email = '$in[email]', name = '$in[name]', user = '$in[username]' where user = '$old[username]'",1);
  upload_files('u',$s[LUG_u_n],0,1,$in[delete_image]);
  update_items_for_user($s[LUG_u_n]);
  if ($_COOKIE[LUG_u_n])
  {setcookie('LUG_u_password',false); setcookie('LUG_u_password',$in[password],$s[cas]+31536000); 
    setcookie('LUG_u_username',false); setcookie('LUG_u_username',$in[username],$s[cas]+31536000); 
    setcookie('LUG_u_email',false); setcookie('LUG_u_email',$in[email],$s[cas]+31536000); 
    setcookie('LUG_u_style',false); setcookie('LUG_u_style',$in[style],$s[cas]+31536000);
  }
  else
  { $_SESSION[LUG_u_username] = $in[username];
    $_SESSION[LUG_u_email] = $in[email];
    $_SESSION[LUG_u_password] = $in[password];
    $_SESSION[LUG_u_style] = $in[style];
  }
  $s[LUG_u_username] = $in[username];
  $s[LUG_u_password] = $in[password];
  $s[LUG_u_email] = $in[email];
  $s[LUG_style] = $s[LUG_u_style] = $in[style];  
}
else
{ if (!$s[user_no_auto]) $approved = 1;
  $joined = $s[cas];
  if (!$s[user_conf_sub]) { $confirmed = 1; $ip = $s[ip]; }
  dq("insert into $s[pr]users values (NULL,'$in[username]','$in[password]','$in[email]','$in[name]','$in[nick]','$in[company]','$in[address1]','$in[address2]','$in[address3]','$in[country]','$in[phone1]','$in[phone2]','$in[url]','$in[site_title]','','$in[detail]','$in[user1]','$in[user2]','$in[user3]','$in[showemail]','$in[news1]','$in[news2]','$in[news3]','$in[news4]','$in[news5]','$ip','$joined','$confirmed','$approved','$in[style]','0','0','0','0','0','0','0','0','0','0','0')",1);
  $n = mysql_insert_id();
  if ((!$s[user_conf_sub]) AND ($s[i_admin_user_joined]))
  { $in[action] = 'joined';
    $in[n] = mysql_insert_id();
    for ($x=1;$x<=3;$x++) $address[] = $in["address$x"]; $address[] = $in[country]; $in[address] = implode(", ",$address);
    for ($x=1;$x<=3;$x++) $phone[] = $in["phone$x"]; $in[phones] = implode(", ",$phone);
    mail_from_template('user_joined_edited_admin.txt',$in);
  }
  upload_files('u',$n,0,1,'');
  if ($s[user_conf_sub]) return $joined;
}
}

############################################################################

function user_joined_or_edited($form) {
global $s,$m;
for ($x=1;$x<=5;$x++) if (($s['news_'.$x]) AND ($form['news'.$x])) $form[newsletters] .= $s['news_'.$x].'<br />';
if ($s[LUG_u_n])
{ $s[info] = info_line($m[data_saved]);
  user_edit();
}
else page_from_template('user_joined.html',$form);
}

############################################################################

function user_form_control($in) {
global $s,$m;
if ($in[action]=='user_edited')
{ $user = get_user_variables($s[LUG_u_n]);
  if (($s[LUG_u_n]!=$user[n]) OR ($s[LUG_u_password]!=$user[password])) problem($m[no_auth]);
  if (!$in[password]) $in[password] = $user[password];
  /*if (!$in[username]) */$in[username] = $user[username];
  //if ($in[username]!=$s[LUG_u_username]) { $test_user = get_user_variables(0,$in[username]); if ($test_user[n]) $problem[] = $m[use_username]; }
}
//elseif (!$in[terms]) $problem[] = 'Please read and agree to the Terms and Conditions';

if (!trim($in[email])) $problem[] = "$m[missing_field] $m[email]";
elseif (strlen($in[email])>255) { $problem[] = $m[l_email]; $email_not_ok = 1; }
elseif (!check_email($in[email])) { $problem[] = $m[w_email]; $email_not_ok = 1; }
$black = try_blacklist($in[email],'email'); if ($black) $problem[] = $black;

if (($in[action]!='user_edited') AND ($s[user_one_acc]) AND (!$email_not_ok))
{ $q = dq("select count(*) from $s[pr]users where email = '$in[email]'",0);
  $x = mysql_fetch_row($q); if ($x[0]) $problem[] = $m[already_member];
}
if (!$s[LUG_u_n])
{ $user = get_user_variables(0,$in[username]); if ($user[n]) $problem[] = $m[use_username];
  if ($s[u_v_captcha]) { $x = check_entered_captcha($in[image_control]); if ($x) $problem[] = $x; }
}

if (!trim($in[nick])) $problem[] = "$m[missing_field] $m[nick]";
//elseif (!preg_match("/^[a-z0-9]{5,15}$/i",$in[nick])) $problem[] = $m[w_nick];

if (!trim($in[username])) $problem[] = "$m[missing_field] $m[username]";
elseif (($in[username]!=$user[username]) AND (!preg_match("/^[a-z0-9\.]{6,255}$/i",$in[username]))) $problem[] = $m[w_username];

if (!(trim($in[password]))) $problem[] = "$m[missing_field] $m[password]";
elseif (!preg_match("/^[a-z0-9]{6,15}$/i",$in[password])) $problem[] = $m[w_pass];

if ($in[name]) $in[name] = ucwords(my_strtolower($in[name]));
elseif (($s[u_r_name]) AND (!$in[name])) $problem[] = "$m[missing_field] $m[name]";

$s[u_min_public_article] = 1; $s[u_max_detail] = 100000;
if ( (!trim($in[detail])) AND ($s[u_r_detail]) ) $problem[] = "$m[missing_field] $m[public_article]";
if (trim($in[detail]))
{ $y = strlen(trim(strip_tags($in[detail])));
  if (($y<$s[u_min_detail]) OR ($y>$s[u_max_detail])) $problem[] = "$m[public_article_ls] $s[u_min_public_article] $m[a] $s[u_max_detail] $m[characters].";
  if (!$s[u_details_html_editor]) $in[detail] = strip_tags($in[detail]);
  $black = try_blacklist($in[detail],'word'); if ($black) $problem[] = $black;
}

if (($s[u_r_address1]) AND (!$in[address1])) $problem[] = "$m[missing_field] $m[address1]";
if (($s[u_r_address2]) AND (!$in[address2])) $problem[] = "$m[missing_field] $m[address2]";
if (($s[u_r_address3]) AND (!$in[address3])) $problem[] = "$m[missing_field] $m[address3]";
if (($s[u_r_country]) AND (!$in[country])) $problem[] = "$m[missing_field] $m[country]";
if (($s[u_r_phone1]) AND (!$in[phone1])) $problem[] = "$m[missing_field] $m[phone1]";
if (($s[u_r_phone2]) AND (!$in[phone2])) $problem[] = "$m[missing_field] $m[phone2]";

if ($s[u_r_site_info])
{ if (!trim($in[url])) $problem[] = "$m[missing_field] $m[url]";
  if (!trim($in[site_title])) $problem[] = "$m[missing_field] $m[site_title]";
}
if ($in[url])
{ if ( ($s[add_http]) AND (!preg_match("/^(http:\/\/*+)/i",$in[url])) ) $in[url] = 'http://'.$in[url];
  $checked_url = check_url($in[url],0); if ($checked_url[1]) $problem[] = $checked_url[1];
  $black = try_blacklist($in[url],'url'); if ($black) $problem[] = $black;
}
if ($in[site_title])
{ if ($s[r_title]) $in[site_title] = ucwords(my_strtolower($in[site_title]));
  $black = try_blacklist($in[site_title],'word'); if ($black) $problem[] = $black;
}

$in = replace_array_text($in);
$in[detail] = refund_html($in[detail]);

return array ($problem,$in);
}

#########################################################################
#########################################################################
#########################################################################

function logged_in($in) {
global $s,$m;

if (!$in[username]) die(stripslashes(user_login_form('')));
elseif (!$in[password]) die(stripslashes(user_login_form('')));
if ($s[user_login_captcha]) { $x = check_entered_captcha($in[image_control]); if ($x) { $s[info] = $x; die(stripslashes(user_login_form(info_line($x)))); } }
$q = dq("select * from $s[pr]users where username='$in[username]' AND password='$in[password]' AND approved = 1",1);
$user_vars = mysql_fetch_assoc($q);
if ((!$user_vars[n]) OR (!$user_vars[ip]))
{ $s[info] = info_line($m[w_user]);
  //$s[username] = $in[username];
  check_if_too_many_logins('users',"$s[pr]users",$in[username],$in[password]);
  echo die(stripslashes(user_login_form($s[info])));
  exit;
}
if ($in[remember_me])
{ setcookie(LUG_u_username,$user_vars[username],$s[cas]+31536000); 
  setcookie(LUG_u_password,$user_vars[password],$s[cas]+31536000); 
  setcookie(LUG_u_n,$user_vars[n],$s[cas]+31536000);
  setcookie(LUG_u_email,$user_vars[email],$s[cas]+31536000);
  setcookie(LUG_u_style,$user_vars[style],$s[cas]+31536000);
}
else
{ $_SESSION[LUG_u_username] = $user_vars[username];
  $_SESSION[LUG_u_password] = $user_vars[password];
  $_SESSION[LUG_u_n] = $user_vars[n];
  $_SESSION[LUG_u_email] = $user_vars[email];
  $_SESSION[LUG_u_style] = $user_vars[style];
}
$s[LUG_u_username] = $user_vars[username];
$s[LUG_u_password] = $user_vars[password];
$s[LUG_u_email] = $user_vars[email];
$s[LUG_u_n] = $user_vars[n];
$s[LUG_u_style] = $s[LUG_style] = $user_vars[style];

echo '<br>'.info_line($m[logged_in],'<a href="'.$s[site_url].'/user.php?action=user_home">'.$m[click_continue].'</a>');
exit;
}

#########################################################################

function user_home() {
global $s,$m;
check_logged_user();
$user_vars = get_user_variables($s[LUG_u_n]);
$a[user_url] = get_detail_page_url('u',$user_vars[n],$user_vars[nick]);
page_from_template('user_home.html',$a);
}

#########################################################################

function user_log_off() {
global $s;
unset($_SESSION[LUG_u_username],$_SESSION[LUG_u_password],$_SESSION[LUG_u_n],$_SESSION[LUG_u_email],$_SESSION[LUG_u_style]);
setcookie(LUG_u_username,false);
setcookie(LUG_u_password,false);
setcookie(LUG_u_n,false);
setcookie(LUG_u_email,false);
setcookie(LUG_u_style,false);
unset($s[LUG_u_username],$s[LUG_u_password],$s[LUG_u_n],$s[LUG_u_email],$s[LUG_u_style]);
$_SESSION[LUG_style] = $s[LUG_style];
page_from_template('user_logoff.html',$s);
}

#########################################################################
#########################################################################
#########################################################################

function user_remind($data) {
global $s,$m;
if ($data[email]) 
{ $q = dq("select username,password from $s[pr]users where email = '$data[email]'",1);
  $a = mysql_fetch_assoc($q);
  if (!$a[username]) problem($m[no_account]);
  else
  { $a[site_url] = $s[site_url]; $a[to] = $data[email];
    mail_from_template('user_password_remind.txt',$a);
    $a[info] = info_line($m[mail_sent]);
  }
}
page_from_template('user_remind.html',$a);
}

#########################################################################

function user_reminded($in) {
global $s,$m;
if (!check_email($in[email])) { $in[info] = $m[no_account]; page_from_template('user_remind.html',$in); }
$q = dq("select username,password from $s[pr]users where email = '$in[email]'",1);
$a = mysql_fetch_assoc($q);
if (!$a[username]) { $in[info] = $m[no_account]; page_from_template('user_remind.html',$in); }
$a[to] = $in[email];
mail_from_template('user_password_remind.txt',$a);
$a[info] = info_line($m[mail_sent]);
page_from_template('user_remind.html',$a);
}

#########################################################################
#########################################################################
#########################################################################

function check_logged_user() {
global $s;
if (($s[LUG_u_username]) AND ($s[LUG_u_password]) AND ($s[LUG_u_n])) { check_field_create("$s[LUG_u_username]$s[LUG_u_password]$s[LUG_u_n]"); return false; } // user_edited
if ((!$_SESSION[LUG_u_username]) AND (!$_COOKIE[LUG_u_username])) user_join();
$user_vars = get_user_variables(round($s[LUG_u_n])); if (!$user_vars) user_join();
if ($_SESSION[LUG_u_username])
{ $username = $_SESSION[LUG_u_username];
  $password = $_SESSION[LUG_u_password];
}
else
{ $username = $_SESSION[LUG_u_username];
  $password = $_COOKIE[LUG_u_password];
}
if (($user_vars[username]!=$username) OR ($user_vars[password]!=$password)) user_join();

$s[LUG_u_username] = $user_vars[username];
$s[LUG_u_password] = $user_vars[password];
$s[LUG_u_email] = $user_vars[email];
$s[LUG_u_n] = $user_vars[n];
$s[LUG_u_style] = $user_vars[style];

check_field_create("$user_vars[username]$user_vars[password]$user_vars[n]");
}

###############################################################################
###############################################################################
###############################################################################

function user_adlinks() {
global $s,$m;
$q = dq("select * from $s[pr]adlinks where owner = '$s[LUG_u_n]' order by n desc",1);
while ($adlink = mysql_fetch_assoc($q)) 
{ if ($adlink[enabled]) $adlink[enabled] = $m[yes]; else $adlink[enabled] = $m[no];
  if ($adlink[approved]) $adlink[approved] = $m[yes]; else $adlink[approved] = $m[no];
  $adlink[preview] = get_complete_adlink($adlink,1);
  if (!$s[users_can_delete_adlinks]) { $adlink[hide_delete_begin] = '<!--'; $adlink[hide_delete_end] = '-->'; }
  $a[adlinks] .= parse_part('user_adlinks.txt',$adlink);
}
$a[info] = $s[info];
page_from_template('user_adlinks.html',$a);
}

###############################################################################

function user_orders() {
global $s,$m;
$q = dq("select * from $s[pr]links_extra_orders where user = '$s[LUG_u_n]' order by n desc",1);
while ($order = mysql_fetch_assoc($q)) 
{ $order[order_date] = datum($order[order_time],0);
  if ($order[paid]) { $order[status] = $m[paid]; $order[payment_link] = $m[na]; }
  else { $order[status] = $m[unpaid]; $order[payment_link] = '<a href="'.$s[site_url].'/link_extra_features.php?action=invoice_pay_now&amp;n='.$order[n].'">'.$m[click_to_pay].'</a>'; }
  if ($order[payment_type]=='link') $order[type] = $m[Link].' #'.$order[link_or_pack].' - '.round($order[days_clicks_or_value]).' days';
  elseif ($order[payment_type]=='adlink') $order[type] = $m[AdLink].' #'.$order[link_or_pack].' - '.round($order[days_clicks_or_value]).' clicks';
  elseif ($order[payment_type]=='package') $order[type] = $m[package_funds];
  $a[orders] .= parse_part('user_orders.txt',$order);
}
$a[info] = $s[info];
page_from_template('user_orders.html',$a);
}

###############################################################################
###############################################################################
###############################################################################

function user_favorites() {
global $s,$m;


foreach ($s[item_types_words] as $what=>$word)
{ $q = dq("select $s[pr]cats.* from $s[pr]u_favorites,$s[pr]cats where $s[pr]u_favorites.user = '$s[LUG_u_n]' and $s[pr]u_favorites.what = 'c_$what' and $s[pr]u_favorites.n = $s[pr]cats.n order by $s[pr]cats.name",1);
  while ($item = mysql_fetch_assoc($q))
  $a['favorite_'.$word.'s_categories'] .= '<tr><td align="center"><a href="'.category_url($what,$item[n],$item[alias_of],$item[name],1,$item[pagename],$item[rewrite_url],'','').'">'.$item[name].'</a>&nbsp;&nbsp;&nbsp;<a href="favorites.php?action=remove&amp;what=c_'.$what.'&amp;n='.$item[n].'" title="'.$m[fav_remove].'">x</a></td></tr>';
  if (!$a['favorite_'.$word.'s_categories']) $a['favorite_'.$word.'s_categories'] = '<tr><td align="center">'.$m[fav_none].'</td></tr>';
  if (!$s["bookmarks_cats_email_$what"]) { $a['hide_email_'.$what.'_info_begin'] = '<!--'; $a['hide_email_'.$what.'_info_end'] = '-->'; }
  $table = $s[item_types_tables][$what];
  $q = dq("select $table.* from $s[pr]u_favorites,$table where $s[pr]u_favorites.user = '$s[LUG_u_n]' and $s[pr]u_favorites.what = '$what' and $s[pr]u_favorites.n = $table.n order by $table.title",1);
  while ($item = mysql_fetch_assoc($q))
  $a['favorite_'.$word.'s'] .= '<tr><td align="center"><a href="'.get_detail_page_url($what,$item[n],$item[rewrite_url],'',1).'">'.$item[title].'</a>&nbsp;&nbsp;&nbsp;<a href="favorites.php?action=remove&amp;what='.$what.'&amp;n='.$item[n].'" title="'.$m[fav_remove].'">x</a></td></tr>';
  if (!$a['favorite_'.$word.'s']) $a['favorite_'.$word.'s'] = '<tr><td align="center">'.$m[fav_none].'</td></tr>';
}

page_from_template('user_favorites.html',$a);
}

###############################################################################

function user_friends() {
global $s,$m;
check_logged_user();
$q = dq("select $s[pr]u_friends.*,$s[pr]users.username,$s[pr]users.name,$s[pr]users.nick,$s[pr]users.n from $s[pr]users,$s[pr]u_friends where (($s[pr]u_friends.user2 = '$s[LUG_u_n]' and $s[pr]u_friends.user1=$s[pr]users.n) OR ($s[pr]u_friends.user1 = '$s[LUG_u_n]' and $s[pr]u_friends.user2=$s[pr]users.n)) order by accepted,time desc",1);
while ($x = mysql_fetch_assoc($q))
{ if ($x[accepted]) $accepted = $m[yes];
  else { $accepted = $m[no]; if ($x[user2]==$s[LUG_u_n]) $accepted .= '&nbsp;&nbsp;&nbsp;<a href="'.$s[site_url].'/user.php?action=user_friend_accept&n='.$x[user1].'">'.$m[accept_it].'</a>'; }
  if (!$x[nick]) $x[nick] = $x[name];
  $a[friends_list] .= '<tr>
  <td align="left" nowrap>'.$x[nick].' </td>
  <td align="left" nowrap>'.datum($x[time],0).' </td>
  <td align="center" nowrap>'.$accepted.'</td>
  <td align="center" nowrap><a href="'.get_detail_page_url('u',$x[n],$x[nick]).'">Show user</a></td>
  <td align="center" nowrap><a href="user.php?action=user_friend_delete&n='.$x[n].'">Delete</a></td>
  </tr>';
}
$a[info] = $s[info];
page_from_template('user_friends.html',$a);
}

###############################################################################

function user_friend_request($n) {
global $s,$m;
check_logged_user();
$n = round($n);
$q = dq("select * from $s[pr]u_friends where (user2 = '$s[LUG_u_n]' and user1 = '$n') OR (user1 = '$s[LUG_u_n]' and user2 = '$n') limit 1",1);
if (mysql_num_rows($q)) $s[info] = info_line('This user already exists in your friends list');
elseif ($s[LUG_u_n]!=$n)
{ dq("insert into $s[pr]u_friends values ('$s[LUG_u_n]','$n','0','$s[cas]')",1);
  $other_user_vars = get_user_variables($n);
  $user_vars = get_user_variables($s[LUG_u_n]);
  $b[to] = $other_user_vars[email];
  $b[user_url] = get_detail_page_url('u',$user_vars[n],$user_vars[nick]);
  $b[nick] = $user_vars[nick];
  mail_from_template('user_friendship_request.txt',$b);
  $s[info] = info_line($m[sent_friend_request]);
}
user_friends();
}

###############################################################################

function user_friend_accept($n) {
global $s,$m;
check_logged_user();
$n = round($n);
$q = dq("select * from $s[pr]u_friends where user2 = '$s[LUG_u_n]' and user1 = '$n' and accepted = 0 limit 1",1);
$u_friends = mysql_fetch_assoc($q);
if ($u_friends[user1])
{ dq("update $s[pr]u_friends set accepted = 1 where user2 = '$s[LUG_u_n]' and user1 = '$n' and accepted = 0 limit 1",1);
  $other_user_vars = get_user_variables($u_friends[user1]);
  $user_vars = get_user_variables($s[LUG_u_n]);
  $b[to] = $other_user_vars[email];
  $b[user_url] = get_detail_page_url('u',$user_vars[n],$user_vars[nick]);
  $b[nick] = $user_vars[nick];
  mail_from_template('user_friendship_accepted.txt',$b);
  $s[info] = info_line($m[request_accepted]);
}
user_friends();
}

###############################################################################

function user_friend_delete($n) {
global $s,$m;
check_logged_user();
$n = round($n);
$q = dq("select * from $s[pr]u_friends where user2 = '$s[LUG_u_n]' and user1 = '$n' limit 1",1);
$u_friends = mysql_fetch_assoc($q);
if ($u_friends[user1])
{ dq("delete from $s[pr]u_friends where user2 = '$s[LUG_u_n]' and user1 = '$n' limit 1",1);
  $other_user_vars = get_user_variables($u_friends[user1]);
  $user_vars = get_user_variables($s[LUG_u_n]);
  $b[to] = $other_user_vars[email];
  $b[user_url] = get_detail_page_url('u',$user_vars[n],$user_vars[nick]);
  $b[nick] = $user_vars[nick];
  mail_from_template('user_friendship_deleted.txt',$b);
  $s[info] = info_line($m[friend_deleted]);
}
user_friends();
}

###############################################################################

function user_wall() {
global $s,$m;
check_logged_user();
$q = dq("select * from $s[pr]u_wall where user = '$s[LUG_u_n]'",1);
while ($x = mysql_fetch_assoc($q))
{ $a[posts_list] .= '<tr>
  <td align="left" style="vertical-align:top;" width="20%" nowrap>'.$x[title].' </td>
  <td align="left" style="vertical-align:top;" width="10%" nowrap>'.datum($x[time],0).' </td>
  <td align="left" style="vertical-align:top;" width="60%"><a name="post'.$x[n].'"></a><div id="div_show_post'.$x[n].'" style="display:block;"><a href="#post'.$x[n].'" onclick="check_show_hide_div(\'div_show_post'.$x[n].'\');check_show_hide_div(\'div_post'.$x[n].'\');">Show</a></div><div id="div_post'.$x[n].'" style="display:none;">'.nl2br($x[text]).'<br><a href="#post'.$x[n].'" onclick="check_show_hide_div(\'div_show_post'.$x[n].'\');check_show_hide_div(\'div_post'.$x[n].'\');">Hide</a></div></td>
  <td align="center" style="vertical-align:top;" width="10%" nowrap><a href="user.php?action=user_wall_delete&n='.$x[n].'">Delete</a></td>
  </tr>';
}
$a[info] = $s[info];
$a[post_box] = parse_part('user_wall_post.txt',$a1);
page_from_template('user_wall.html',$a);
}


###############################################################################

function user_wall_posted($in) {
global $s,$m;
check_logged_user();
check_field();
$in = replace_array_text($in);
if ((!trim($in[title])) OR (!trim($in[text]))) $problem[] = $m[both_required];
$black = try_blacklist($in[title],'word'); if ($black) $problem[] = $black;
$black = try_blacklist($in[text],'word'); if ($black) $problem[] = $black;
if ($problem)
{ $in[info] = info_line($m[errorsfound],implode('<br>',$problem));
  $check_field = check_field_create("$s[LUG_u_username]$s[LUG_u_password]$s[LUG_u_n]");
  echo str_replace('</form>',$check_field.'</form>',stripslashes(parse_part('user_wall_post.txt',$in)));
  exit;
}
dq("insert into $s[pr]u_wall values (NULL,'$s[LUG_u_n]','$in[title]','$in[text]','$s[cas]')",1);

$q = dq("select count(*) from $s[pr]u_wall where user = '$s[LUG_u_n]'",1);
$x = mysql_fetch_row($q);
$over_posts = $x[0] - $s[u_max_wall_posts];
if ($over_posts>0) dq("delete from $s[pr]u_wall where user = '$s[LUG_u_n]' order by time limit $over_posts",1);
  
$in[post_n] = mysql_insert_id();
$user_vars = get_user_variables($s[LUG_u_n]);
$in[user_n] = $user_vars[n];
$in[name] = $user_vars[name];
mail_from_template('user_wall_posted.txt',$in);
echo info_line($m[wall_posted]);
exit;
}

###############################################################################

function user_wall_delete($n) {
global $s,$m;
check_logged_user();
$n = round($n);
$q = dq("select * from $s[pr]u_wall where user = '$s[LUG_u_n]' and n = '$n'",1);
$u_wall = mysql_fetch_assoc($q);
if ($u_wall[n])
{ dq("delete from $s[pr]u_wall where user = '$s[LUG_u_n]' and n = '$n'",1);
  $user_vars = get_user_variables($s[LUG_u_n]);
  $u_wall[n] = $user_vars[n];
  $u_wall[name] = $user_vars[name];
  mail_from_template('user_wall_deleted.txt',$u_wall);
  $s[info] = info_line($m[post_deleted]);
}
user_wall();
}

###############################################################################
###############################################################################
###############################################################################

?>