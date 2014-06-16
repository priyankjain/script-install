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
$s[my_test] = 0;
get_messages('payment_process.php');
set_time_limit(60);
if ($_POST['sid']) process_payment_2checkout();
header("Status: 404 Not Found"); exit;

##################################################################################
##################################################################################
##################################################################################

function process_payment_2checkout() {
global $s,$m;

foreach ($_POST as $k => $v)
{ $v = stripslashes($v);
  if (trim($k)) { $from_2co[$k] = $v; $out .= '&amp;'.$k.'='.urlencode($v); }
}
$from_2co = replace_array_text($from_2co);
unset ($_POST);

$order_data = get_order_variables($from_2co[cart_id]);
if (!$order_data[n]) links_list('');
if (!$order_data) $problem[] = $m[na_order];
else
{ if (!$from_2co['order_number']) $problem[] = $m[missing_2co_number];
  if ($from_2co['sid']!=$s[co_number]) $problem[] = $m[twoco_id_wrong];
  if ($from_2co[total]!=$order_data[price]) $problem[] = "$m[wrong_co_price_1] $s[currency]$order_data[price]. $m[wrong_co_price_2]$s[currency]$from_2co[total]";
  if ($from_2co[credit_card_processed]!='Y') $problem[] = $m[card_error];
  if (($from_2co[demo]=='Y') AND (!$s[co_test])) $problem[] = $m[was_demo];
  if (!$from_2co[quantity]) $from_2co[quantity] = 1;
  if ($from_2co[quantity]!=1) $problem[] = "$m[wrong_quantity] $from_2co[quantity].";
  $valid_key = strtoupper(md5($s[co_secret_word].$s[co_number].$from_2co[order_number].$from_2co[total]));
  $demo_key = strtoupper(md5("$s[co_secret_word]$s[co_number]1$from_2co[total]"));
  if (($from_2co['key']==$demo_key) AND (!$s[co_test])) $problem[] = $m[was_demo];
  elseif (($from_2co['key']==$demo_key) AND ($s[co_test]) AND (!$problem)) $success = 1;
  elseif (($from_2co['key']==$valid_key) AND (!$s[co_test]) AND (!$problem)) $success = 1;
  if ((!$success) AND (!$problem)) $problem[] = $m[na_2co_error];
}
process_order_2checkout($order_data,$from_2co,$success,$problem);
auto_payment_done($order_data[n]);
}

##################################################################################

function process_order_2checkout($order_data,$from_2co,$success,$problem) {
global $s,$m;
if ($success) { $paid = 1; $info = $m[order_success]; $payment_status = 'Payment successful'; }
else { $info = $m[failed].'<br />'.implode('<br />',$problem); $payment_status = 'Payment WAS NOT successful'; }

$notes = "RAW DATA RECEIVED FROM 2CHECKOUT\n"; foreach ($from_2co as $k=>$v) $notes .= "$k: $v\n";
$mysql = order_update_payment_info($order_data[n],$paid,'2CheckOut',$info,$notes);

if ($paid) $admin_info = 'Order WAS MARKED AS PAID.'; else $admin_info = 'Order WAS NOT MARKED AS PAID. You should go to admin area and manually review this order.';
$email_admin = "A new order has been sent by 2CheckOut. You can see its details below.\n$admin_info\nOrder number: $order_data[n]\nAmount: $order_data[price]\nPayment status: $payment_status\nOrder number at 2CheckOut: $from_2co[order_number]\n\n";
$email_admin .= "USER RECEIVED THE FOLLOWING MESSAGE:\n".str_replace('<br />',"\n",$info)."\n\n";
$email_admin .= $notes."\n\n";
if ($s[my_test]) $email_admin .= $mysql."\n\n";
//echo "$s[mail]<br /><br />2CheckOut payment<br /><br />$email_admin<br /><br />From: $s[mail]";
my_send_mail('','',$s[mail],0,'2CheckOut payment',$email_admin,1);
}

##################################################################################
##################################################################################
##################################################################################

?>