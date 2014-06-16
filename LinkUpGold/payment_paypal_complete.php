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
$s[my_test] = 1;
get_messages('payment_process.php');
set_time_limit(60);
if (!$_POST[item_number]) $_POST = $_GET;
if ($_POST[item_number]) finish_payment_paypal();
header("Status: 404 Not Found"); exit;

##################################################################################
##################################################################################
##################################################################################

function finish_payment_paypal() {
global $s,$m;
if (!is_numeric($_POST[item_number])) exit;
auto_payment_done($_POST[item_number]);
}

##################################################################################
##################################################################################
##################################################################################

?>