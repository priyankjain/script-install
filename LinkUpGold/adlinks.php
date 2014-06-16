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
get_messages('adlinks.php');
include($s[phppath].'/data/data_forms.php');
if (!$s[LUG_u_n]) exit;

switch ($_GET[action]) {
case 'adlink_create'				: adlink_create();
case 'adlink_edit'					: adlink_edit($_GET);
case 'adlink_delete'				: adlink_delete($_GET[n]);
}
switch ($_POST[action]) {
case 'adlink_created'				: adlink_created($_POST);
case 'adlink_edited'				: adlink_edited($_POST);
case 'adlink_clicks_added'			: adlink_clicks_added($_POST);
case 'adlink_order_reviewed'		: adlink_order_reviewed($_POST);
}

#############################################################################
#############################################################################
#############################################################################

function adlink_clicks_added($in) {
global $s,$m;
$adlink = check_adlink_access_rights($in[n]);
$user = get_user_variables($s[LUG_u_n]);
if ($adlink[c_now]) $in[price] = $adlink[price];
if ((!is_numeric($in[price])) OR (!is_numeric($in[clicks])) OR ($in[clicks]<=0)) adlink_edit($in);
if ($in[price]<$s[adlinks_price_min]) $in[price] = $s[adlinks_price_min];
elseif ($in[price]>$s[adlinks_price_max]) $in[price] = $s[adlinks_price_max];
$in[clicks] = round($in[clicks]);
$price = $in[price]*$in[clicks];
if (($in[option]=='use_funds') AND ($price>$user[funds_now]))
{ $s[info] = info_line($m[errorsfound],$m[no_funds]);
  adlink_edit($in);
}
elseif ($in[option]=='use_funds')
{ dq("update $s[pr]adlinks set c_now = c_now + '$in[clicks]', c_total = c_total + '$in[clicks]', price = '$in[price]' where n = '$in[n]'",1);
  dq("update $s[pr]users set funds_now = funds_now - '$price' where n = '$s[LUG_u_n]'",1);
  $s[info] = info_line("$m[clicks_added1] $in[clicks].<br />$s[currency]$price $m[clicks_added2]");
  adlink_edit($in);
}
elseif ($in[option]=='pay_now')
{ dq("update $s[pr]adlinks set price = '$in[price]' where n = '$in[n]'",1);
  $a[price] = $price; $a[clicks] = $in[clicks]; $a[n] = $in[n];
  page_from_template('adlink_clicks_order.html',$a);
}
}

##################################################################################

function adlink_order_reviewed($in) {
global $s,$m;
$adlink = check_adlink_access_rights($in[n]);
list($price,$order_n) = get_payment_price($in[clicks],1,'adlink',$in[n]);
order_email_admin($order_n,$price,'adlink',$in[n]);
go_to_pay($order_n,$price,'adlink');
}

#############################################################################
#############################################################################
#############################################################################

function adlink_create() {
global $s,$m;
$in[action] = 'adlink_created';
$a[form] = adlink_create_edit_form_public($in);
page_from_template('adlink_create.html',$a);
}

#############################################################################

function adlink_created($in) {
global $s,$m;
$c = categories_edited($in[c]);
$in = replace_array_text($in);
if (!$s[adlink_v_enabled]) $in[enabled] = 0;
dq("insert into $s[pr]adlinks values (NULL,'$s[LUG_u_n]','$s[adlink_autoapr]','$in[enabled]','0','0','0','$c[categories]','$in[words]','$in[url]','$in[title]','$in[text1]','$in[text2]','$in[text3]','$in[text4]','$in[text5]','$in[text6]','$in[text7]','$in[text8]','$in[text9]','$in[text10]','$in[html]')",1);
$in[n] = mysql_insert_id();
if ($s[adlink_i_admin]) { $in[action] = 'created'; $in[to] = $s[mail]; mail_from_template('adlink_admin.txt',$in); }
$s[info] = info_line($m[adlink_created]);
adlink_edit($in);
}

#############################################################################
#############################################################################
#############################################################################

function adlink_edit($in) {
global $s,$m;
$c = categories_edited($in[c]);
$adlink = check_adlink_access_rights($in[n]);
$adlink[preview] = get_complete_adlink($adlink,1);
$adlink[action] = 'adlink_edited';
$adlink[form] = adlink_create_edit_form_public($adlink);
$adlink[price_min] = $s[adlinks_price_min]; $adlink[price_max] = $s[adlinks_price_max];
$user = get_user_variables($s[LUG_u_n]); $adlink[funds_balance] = $user[funds_now];
if ($adlink[price]==0) $adlink[price] = $s[adlinks_price_max];
$adlink[price] = number_format($adlink[price],2);
if ($adlink[c_now]) $adlink[price_disabled] = ' disabled';
$adlink[info] = $s[info];
page_from_template('adlink_edit.html',$adlink);
}

##################################################################################

function adlink_edited($in) {
global $s,$m;
$old = check_adlink_access_rights($in[n]);
$c = categories_edited($in[c]);
$in = replace_array_text($in);
if ($s[adlink_v_enabled]) $enabled = "enabled = '$in[enabled]',";
dq("update $s[pr]adlinks set approved = '$s[adlink_autoapr]', $enabled c = '$c[categories]', keywords = '$in[keywords]', url = '$in[url]', title = '$in[title]', text1 = '$in[text1]', text2 = '$in[text2]', text3 = '$in[text3]', text4 = '$in[text4]', text5 = '$in[text5]', text6 = '$in[text6]', text7 = '$in[text7]', text8 = '$in[text8]', text9 = '$in[text9]', text10 = '$in[text10]', html = '$in[html]' where n = '$in[n]'",1);
if ($s[adlink_i_admin]) { $in[action] = 'edited'; $in[to] = $s[mail]; mail_from_template('adlink_admin.txt',$in); }
$s[info] = info_line($m[adlink_edited]);
adlink_edit($in);
}

##################################################################################

function check_adlink_access_rights($n) {
global $s,$m;
$a = get_adlink_variables($n);
if ($a[owner]!=$s[LUG_u_n]) problem();
return $a;
}

##################################################################################
##################################################################################
##################################################################################

function adlink_create_edit_form_public($in) {
global $s,$m;
$in[field_categories] = categories_rows_form('adlink',$in[c]);
$x[item_name] = $m[keywords]; $x[field_name] = 'keywords'; $x[field_value] = $in[keywords]; $x[field_maxlength] = $s[m_keywords]; $in[field_keywords] = parse_part('form_field.txt',$x);
$x[item_name] = $m[url]; $x[field_name] = 'url'; $x[field_value] = $in[url]; $x[field_maxlength] = 255; $in[field_url] = parse_part('form_field.txt',$x);
$x[item_name] = $m[title]; $x[field_name] = 'title'; $x[field_value] = $in[title]; $x[field_maxlength] = $s[adlink_m_title]; $in[field_title] = parse_part('form_field.txt',$x);
if ($s[adlink_v_html]) { $x[item_name] = $m[html_ad]; $x[html_editor] = get_fckeditor('html',$in[html],'PublicToolbar'); $in[field_html] = parse_part('form_detail_html.txt',$x); }
if ($s[adlink_v_enabled]) { $x[item_name] = $m[Enabled]; $x[content] = '<input type="checkbox" name="enabled" value="1"'; if ($in[enabled]) $x[content] .= ' checked'; $x[content] .= '>'; $in[field_enabled] = parse_part('form_no_field.txt',$x); }
for ($x=1;$x<=10;$x++) { if ($s["adlink_v_text$x"]) { $y[item_name] = $m["text$x"]; $y[field_name] = "text$x"; $y[field_value] = $in["text$x"]; $y[field_maxlength] = $s["adlink_m_text$x"]; $in["field_text$x"] = parse_part('form_field.txt',$y); } }
return parse_part('adlink_create_edit_form.txt',$in);
}

##################################################################################
##################################################################################
##################################################################################

function adlink_delete($n) {
global $s,$m;
if (!$s[users_can_delete_adlinks]) exit;
check_adlink_access_rights($n);
dq("delete from $s[pr]adlinks where n = '$n'",1);
$s[info] = info_line($m[adlink_deleted]);
links_list();
}

##################################################################################
##################################################################################
##################################################################################

?>