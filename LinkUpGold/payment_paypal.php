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
$s['no_test'] = 1;
get_messages('payment_process.php');

$s[my_paypal_test] = 0;
$s[my_paypal_test_verified] = 0;
if ($s[my_paypal_test]) $s[mail] = '';
if ($s[my_paypal_test]) mail($s[mail],'Paypal payment 1','Start',"From: $s[mail]");
set_time_limit(60);
if (($s[my_paypal_test]) OR ($s[pp_test])) $s[paypal_domain] = 'www.sandbox.paypal.com'; else $s[paypal_domain] = 'www.paypal.com';
foreach ($_POST as $k=>$v) $email_info .= "POST: $k - $v\n";
if ($s[my_paypal_test]) mail($s[mail],'Paypal payment 2',"Paypal domain: $s[paypal_domain]\n\n$email_info","From: $s[mail]");
if ($_POST[txn_type]) paypal_main();
header("Status: 404 Not Found"); exit;

##################################################################################
##################################################################################
##################################################################################

function paypal_main() {
global $s,$m;

header("Status: 200 OK");
$out = 'cmd=_notify-validate';
foreach ($_POST as $k => $v)
{ $v = stripslashes($v);
  if ((!eregi("^[_0-9a-z-]{1,30}$",$k)) OR (!strcasecmp($k,'cmd'))) unset ($k,$v);
  if (trim($k)) { $from_pp[$k] = $v; $out .= '&'.$k.'='.urlencode($v); }
}
unset ($_POST);

if ($s[my_paypal_test])
{ foreach ($from_pp as $k=>$v) $email_info .= "from_pp $k - $v\n";
  foreach ($out as $k=>$v) $email_info .= "out $k - $v\n";
  mail($s[mail],'Paypal payment 3',$email_info,"From: $s[mail]");
}

$socket = curl_init("https://$s[paypal_domain]/cgi-bin/webscr");
curl_setopt($socket, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($socket, CURLOPT_POST, 1);
curl_setopt($socket, CURLOPT_RETURNTRANSFER,1);
curl_setopt($socket, CURLOPT_POSTFIELDS, $out);
curl_setopt($socket, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($socket, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($socket, CURLOPT_FORBID_REUSE, 1);
curl_setopt($socket, CURLOPT_HTTPHEADER, array('Connection: Close'));

// In wamp like environments that do not come bundled with root authority certificates,
// download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the path of the certificate
// curl_setopt($socket, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if(!($pp_decision = curl_exec($socket)))
{ $curl_error = curl_error($socket);
  if ($s[my_paypal_test]) mail($s[mail],'Paypal payment curl_error',$curl_error,"From: $s[mail]");
  $problem[] = $m[no_connect];
}
curl_close($socket);

$pp_decision = strtolower(trim($pp_decision));
if ($s[my_paypal_test])
{ $email_info = "pp_decision: $pp_decision\n\nSocket: $socket\nout: $out\n";
  mail($s[mail],'Paypal payment 4',$email_info,"From: $s[mail]");
  unset($email_info);
}

$from_pp = replace_array_text($from_pp);
$from_pp[item_number] = str_replace('AMP','',str_replace('LUG','',$from_pp[item_number]));
if ($s[my_paypal_test_verified]) { $pp_decision = 'verified'; $from_pp[payment_status] = 'Completed'; }
$order_data = get_order_variables($from_pp[item_number]);
if ($s[my_paypal_test])
{ foreach ($order_data as $k=>$v) $email_info .= "Order data: $k - $v\n";
  mail($s[mail],'Paypal payment 5',$email_info,"From: $s[mail]");
}
if ($pp_decision=='verified')
{ if ($from_pp[mc_gross]!=$order_data[price]) $problem[] = "$m[wrong_price_1] $order_data[price]. $m[wrong_price_2] $from_pp[mc_gross]";
  if ($from_pp[mc_currency]!=$s[pp_currency]) $problem[] = "$m[wrong_currency_1] $s[pp_currency]. $m[wrong_currency_2] $from_pp[mc_gross]";
  if (!$s[my_paypal_test_verified]) { if ($from_pp[payment_status]=='Pending') $problem[] = "$m[pending] $from_pp[pending_reason]"; }
  elseif ($from_pp[payment_status]!='Completed') $problem[] = $m[not_completed];
  if ($from_pp[business]!=$s[pp_email]) $problem[] = $m[wrong_pp_email];
  $success = 1;
}
elseif ($pp_decision=='invalid') $problem[] = $m[invalid];
else $problem[] = $m[na_error];
paypal_process_order($order_data,$from_pp,$success,$problem,$pp_decision);
exit;
}

##################################################################################

function paypal_process_order($order_data,$from_pp,$success,$problem,$pp_decision) {
global $s,$m;
if ($success)
{ if ($problem) $info = $m[success_errors].'<br />'.implode('<br />',$problem);
  else { $paid = 1; $info = $m[order_success]; }
}
else $info = $m[failed].'<br />'.implode('<br />',$problem);
$notes = "RAW DATA RECEIVED FROM PAYPAL\n"; foreach ($from_pp as $k=>$v) $notes .= "$k: $v\n";
$mysql = order_update_payment_info($order_data[n],$paid,'PayPal',$info,$notes);

if ($paid) $admin_info = 'Order WAS MARKED AS PAID.'; else $admin_info = 'Order WAS NOT MARKED AS PAID. You should go to admin area and manually review this order.';
if ($from_pp[payment_status]=='Pending') $pending_reason = " ($from_pp[pending_reason])";
$email_admin = "A new order has been sent by Paypal. You can see its details below.\n$admin_info\nResult: ".ucfirst($pp_decision)."\nCurrency and amount: $from_pp[mc_currency]$from_pp[mc_gross]\nPayment status: $from_pp[payment_status]$pending_reason\nOrder number: $from_pp[item_number]\n\n";
$email_admin .= "USER RECEIVED THE FOLLOWING MESSAGE:\n".str_replace('<br />',"\n",$info)."\n\n";
$email_admin .= $notes."\n\n";
if ($s[my_paypal_test]) mail($s[mail],'Paypal payment 6',"$email_admin\n\n$mysql\n\n","From: $s[mail]");
my_send_mail('','',$s[mail],0,'Paypal payment',$email_admin,0);
if ($s[money_mail]) { my_send_mail('','',$s[money_mail],0,'Paypal payment',$email_admin,1); }
}

##################################################################################
##################################################################################
##################################################################################

?>