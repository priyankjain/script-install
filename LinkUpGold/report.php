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
get_messages('report.php');
include_once("$s[phppath]/data/data_forms.php");

if (!$_POST) $_POST = $_GET;//new ajax
if (!$_POST) problem($m[item_not_exists]);

$x = form_control($_POST);
$in = $x[1];
if ($x[0])
{ echo stripslashes(report_box('l',$in[n],'<br>'.info_line($m[errorsfound],implode('<br />',$x[0]))));
  exit;
}

$data = get_item_variables('l',$in[n]);
$in = array_merge((array)$data,(array)$in);
$in[c_number] = write_to_db($in);
send_emails($in);
echo '<br>'.info_line($m[reported]);exit;

######################################################################
######################################################################
######################################################################

function send_emails($in) {
global $s;
$in[ip] = $s[ip];
$in[to] = $in[from] = $s[mail];
if ($s[i_error]) mail_from_template('report.txt',$in);
}

######################################################################

function write_to_db($in) {
global $s;
dq("insert into $s[pr]er_reports values (NULL,'$in[comment]','$in[n]','$in[name]','$in[email]','$s[cas]','$s[ip]')",1);
$cislo = mysql_insert_id();
return $cislo;
}

######################################################################

function form_control($in) {
global $s,$m;

if ($s[report_captcha]) { $x = check_entered_captcha($in[image_control]); if ($x) $chyba[] = $x; }

if (strlen($in[name]) > 255) $chyba[] = $m[l_name];
if ($in[email])
{ if (strlen($in[email]) > 255) $chyba[] = $m[l_email];
  elseif (!check_email($in[email])) $chyba[] = $m[w_email];
}

$in = replace_array_text($in);
return array ($chyba,$in);
}

######################################################################
######################################################################
######################################################################


?>