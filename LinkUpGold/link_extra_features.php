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

check_post_rights('l');
switch ($_GET[action]) {
case 'link_adv_home'						: link_adv_home($_GET[n]);
case 'link_funds_away'						: link_funds_away($_GET[n]);
case 'invoice_pay_now'						: invoice_pay_now($_GET[n]);
case 'funds_add'							: funds_add();
}
switch ($_POST[action]) {
case 'link_flexible_fixed_resources_added'	: link_flexible_fixed_resources_added($_POST);
case 'link_dynamic_clicks_added'			: link_dynamic_clicks_added($_POST);
case 'link_dynamic_click_set_price'			: link_dynamic_click_set_price($_POST);
case 'link_simple_days_added'				: link_simple_days_added($_POST);
case 'link_simple_days_reviewed'			: link_simple_days_reviewed($_POST);
case 'funds_added'							: funds_added($_POST);
}
link_login_form($_GET[n]);

##################################################################################
##################################################################################
##################################################################################

function funds_add($in) {
global $s;
$a[packages] = adv_pack_select();
page_from_template('link_extra_funds_order.html',$a);
}

##################################################################################

function funds_added($in) {
global $s,$m;
if (!is_numeric($in[package])) funds_add();
if (!$s[LUG_u_n]) problem ($m[users_only]);
list($price,$order_n) = get_payment_price(0,1,'package',$in[package]);
order_email_admin($order_n,$price,'package',$in[package]);
go_to_pay($order_n,$price,'package');
}


##################################################################################

function adv_pack_select() {
global $s;
$q = dq("select * from $s[pr]adv_packs order by price",1);
while ($x = mysql_fetch_assoc($q))
{ if (!$a) $checked = ' checked'; else $checked = '';
  $a .= '<input type="radio" name="package" value="'.$x[n].'"'.$checked.'>'.$x[title].'<br />';
}
return $a;
}

##################################################################################
##################################################################################
##################################################################################

function link_adv_home($n) {
global $s,$m;
$a = check_link_access_rights($n,'','');
if ($a[sponsored]) $a[is_advertising] = $m[link_is_adv];
else $a[is_advertising] = $m[link_isnot_adv];
if ($a[status]=='enabled') $a[status] = $m[Enabled]; else $a[status] = $m[Disabled];

if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]); 
  $a[funds_balance] = $user[funds_now];
}

$q = dq("select * from $s[pr]links_adv where n = '$n'",1);
$link_adv = mysql_fetch_assoc($q);
if (!$link_adv[n]) $link_adv[c_order] = $link_adv[c_now] = $link_adv[i_order] = $link_adv[i_now] = $link_adv[d_order] = $link_adv[c_dynamic_price] = $link_adv[c_dynamic_order] = $link_adv[c_dynamic_now] = $link_adv[c_order_simple] = $link_adv[c_now_simple] = $link_adv[i_order_simple] = $link_adv[i_now_simple] = $link_adv[d_order_simple] = 0;
if ($link_adv[d_validby]) $link_adv[d_validby] = datum($link_adv[d_validby],0); else $link_adv[d_validby] = $m[na];
if ($link_adv[d_validby_simple]) $link_adv[d_validby_simple] = datum($link_adv[d_validby_simple],0); else $link_adv[d_validby_simple] = $m[na];
$link_adv[c_dynamic_price] = number_format($link_adv[c_dynamic_price],2);
if (!$s[price_dynamic_min]) $s[price_dynamic_min] = 0; if (!$s[price_dynamic_max]) $s[price_dynamic_max] = 0; 
$a[price_dynamic_min] = number_format($s[price_dynamic_min],2); $a[price_dynamic_max] = number_format($s[price_dynamic_max],2);
$a[i_static_price] = number_format($s[i_static_price],2); $a[c_static_price] = number_format($s[c_static_price],2); $a[d_static_price] = number_format($s[d_static_price],2); 

$q = dq("select * from $s[pr]links_adv_prices order by days",1);
while ($x=mysql_fetch_assoc($q)) $a[days_simple_options] .= '<option value="'.$x[days].'">'.$x[days].' days for '.$s[currency].$x[price].'</option>';

$a = array_merge((array)$a,(array)$link_adv);
$a[info] = $s[info];
page_from_template('link_extra_features.html',$a);
}

##################################################################################
##################################################################################
##################################################################################

function link_simple_days_added($in) {
global $s,$m;
$a = get_item_variables('l',$in[n]);
$a[price] = get_payment_price($in[days],0,'link',$in[n]);
$a[days] = $in[days];
page_from_template('link_extra_simple_order.html',$a);
}

##################################################################################

function link_simple_days_reviewed($in) {
global $s,$m;
$price = get_payment_price($in[days],0,'link',$in[n]);
dq("insert into $s[pr]links_extra_orders values (NULL,'$s[LUG_u_n]','$s[cas]','$price','0','','','','link','$in[n]','$in[days]')",1);
$order_n = mysql_insert_id();
order_email_admin($order_n,$price,'link',$in[n]);
go_to_pay($order_n,$price,'link');
}

##################################################################################
##################################################################################
##################################################################################

function invoice_pay_now($n) {
global $s,$m;
$n = round($n);
$q = dq("select * from $s[pr]links_extra_orders where n = '$n'",1);
$order_data = mysql_fetch_assoc($q);
if (!$n) exit;
if (($order_data[user]) AND (!$s[LUG_u_n])) problem($m[invoice]);
go_to_pay($order_data[n],$order_data[price],$order_data[payment_type]);
}

##################################################################################
##################################################################################
##################################################################################

function link_flexible_fixed_resources_added($in) {
global $s,$m;
if ((!is_numeric($in[amount])) OR ($in[amount]<=0)) link_adv_home($in[n]);
$a = check_link_access_rights($in[n],'','');

if (($in[what]=='days') AND (($link[t1]) OR ($link[t2]))) $problem[] = $m[has_t1t2];
if ($problem[0])
{ $s[info] = info_line($m[errorsfound],implode('<br />',$problem));
  link_adv_home($in[n]);
}
$user = get_user_variables($s[LUG_u_n]);
$in[amount] = round($in[amount]);
if ($in[what]=='impressions') $price = ($s[i_static_price]/100)*$in[amount];
elseif ($in[what]=='clicks') $price = ($s[c_static_price]/100)*$in[amount];
elseif ($in[what]=='days') $price = $s[d_static_price]*$in[amount];
if ($price>$user[funds_now])
{ $s[info] = info_line($m[errorsfound],$m[no_funds]);
  link_adv_home($in[n]);
}
else
{ $link_adv = get_link_adv_variables($in[n]);
  if ($link_adv[d_validby_simple]>$s[cas]) $info = $m[no_ad_flexible];
  else
  { if ($link_adv[i_now]>=1) $i = 1; else $i = 0;
    if ($link_adv[c_now]>=1) $c = 1; else $c = 0;
    if ($link_adv[d_validby]>($s[cas]+10800)) $d = 1; else $d = 0;
    if ($link_adv[c_dynamic_now]>=1) $c_dynamic = 1; else $c_dynamic = 0;
    if ($in[what]=='impressions')
    { if (($c) OR ($d) OR ($c_dynamic)) $info = $m[cant_add_impressions];
      else $query = "i_order = i_order + '$in[amount]', i_now = i_now + '$in[amount]'";
    }
    elseif ($in[what]=='clicks')
    { if (($i) OR ($d) OR ($c_dynamic)) $info = $m[cant_add_clicks];
      else $query = "c_order = c_order + '$in[amount]', c_now = c_now + '$in[amount]'";
    }
    elseif ($in[what]=='days') 
    { if (($i) OR ($c) OR ($c_dynamic)) $info = $m[cant_add_days];
      else { if (!$link_adv[d_validby]) $link_adv[d_validby] = $s[cas]; $query = "d_order = d_order + '$in[amount]', d_validby = $link_adv[d_validby] + (86400*$in[amount])"; }
    }
    else $info = '';
  }
  if ($info)
  { $s[info] = info_line($m[errorsfound],$info);
    link_adv_home($in[n]); 
  }
  if (!$link_adv[n]) dq("insert into $s[pr]links_adv values('$in[n]','0','0','0','0','0','0','0','0','0','0','0')",1);
  dq("update $s[pr]links_adv set $query where n = '$in[n]'",1);
  dq("update $s[pr]users set funds_now = funds_now - '$price' where n = '$s[LUG_u_n]'",1);
}
update_link_advertising_status($in[n]);
$s[info] = info_line($m[data_saved]);
link_adv_home($in[n]);
}

##################################################################################

function link_funds_away($n) {
global $s,$m;
$a = check_link_access_rights($n,'','');
$link_adv = get_link_adv_variables($n);
if ($link_adv[d_validby]>$s[cas]) $price = ($s[d_static_price]/86400) * ($link_adv[d_validby]-$s[cas]);
elseif ($link_adv[i_now]) $price = ($s[i_static_price]/100) * $link_adv[i_now];
elseif ($link_adv[c_now]) $price = ($s[c_static_price]/100) * $link_adv[c_now];
elseif ($link_adv[c_dynamic_now]) $price = $link_adv[c_dynamic_price] * $link_adv[c_dynamic_now];
$price = round($price,2);
dq("update $s[pr]users set funds_now = funds_now + '$price' where n = '$s[LUG_u_n]'",1);
dq("update $s[pr]links_adv set c_now = 0, i_now = 0, d_validby = 0, c_dynamic_now = 0 where n = '$n'",1);
$s[info] = info_line($m[ret_funds].' '.$s[currency].$price);
link_adv_home($n);
}

##################################################################################

function link_dynamic_clicks_added($in) {
global $s,$m;
$a = check_link_access_rights($in[n],'','');
if ((!is_numeric($in[amount])) OR ($in[amount]<=0)) link_adv_home($in[n]);
$link_adv = get_link_adv_variables($in[n]);
$user = get_user_variables($s[LUG_u_n]);
$in[amount] = round($in[amount]);
settype($link_adv[c_dynamic_price],double); if ($link_adv[c_dynamic_price]==0) $info[] = $m[dyn_unpriced];
$in[price] = $link_adv[c_dynamic_price] * $in[amount];
if ($in[price]>$user[funds_now]) $info[] = $m[no_funds];
elseif ( ($link_adv[i_now]) OR ($link_adv[c_now]) OR ($link_adv[d_validby]>($s[cas]+10800)) OR ($link_adv[d_validby_simple]>($s[cas]+10800)) ) $info[] = $m[no_ad_dyn];
if ($info[0])
{ $s[info] = info_line($m[errorsfound],implode('<br />',$info));
  link_adv_home($in[n]);
}
dq("update $s[pr]links_adv set c_dynamic_order = c_dynamic_order + '$in[amount]', c_dynamic_now = c_dynamic_now + '$in[amount]' where n = '$in[n]'",1);
dq("update $s[pr]users set funds_now = funds_now - '$in[price]' where n = '$s[LUG_u_n]'",1);
update_link_advertising_status($in[n]);
$s[info] = info_line($m[data_saved]);
link_adv_home($in[n]);
}

##################################################################################

function link_dynamic_click_set_price($in) {
global $s,$m;
$a = check_link_access_rights($in[n],'','');
if ((!is_numeric($in[new_price])) OR ($in[new_price]<=0)) link_adv_home($in[n]);
$link_adv = get_link_adv_variables($in[n]);
$user = get_user_variables($s[LUG_u_n]);
if (($in[new_price]<$s[price_dynamic_min]) OR ($in[new_price]>$s[price_dynamic_max]))
{ $s[info] = info_line($m[errorsfound],$m[dyn_wrong_pr]); 
  link_adv_home($in[n]);
}
if ($link_adv[c_dynamic_now])
{ $price_difference = ($in[new_price]-$link_adv[c_dynamic_price])*$link_adv[c_dynamic_now];
  if ($in[new_price]>$link_adv[c_dynamic_price])
  { if ($price_difference>$user[funds_now])
    { $s[info] = info_line($m[errorsfound],$m[no_funds]); 
      link_adv_home($in[n]);
    }
  }
  dq("update $s[pr]users set funds_now = funds_now - $price_difference where n = '$s[LUG_u_n]'",1);
}
dq("update $s[pr]links_adv set c_dynamic_price = '$in[new_price]' where n = '$in[n]'",1);
$s[info] = info_line($m[data_saved]);
link_adv_home($in[n]);
}

##################################################################################
##################################################################################
##################################################################################

?>