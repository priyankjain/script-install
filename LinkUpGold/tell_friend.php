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
$s[selected_menu] = 5;
get_messages('tell_friend.php');
include_once("$s[phppath]/data/data_forms.php");

if (!$_POST) $_POST = $_GET;//new ajax
$_POST = replace_array_text($_POST);
$x = form_control($_POST);
$in = $x[1];
if ($x[0])
{ echo stripslashes(tell_friend_box($in[what],$in[n],'<br>'.info_line($m[errorsfound],implode('<br />',$x[0]))));
  exit;
}

$in[from] = $in[email];
mail_from_template('tell_friend.txt',$in);

if (!$_POST[hide_cancel])
{ $close_it = '<br><a href="#page_top" onclick="show_hide_div_id(0,\'tell_friend_box'.$_POST[what].$_POST[n].'\')">'.$m[close_this_window].'</a>';
  echo '<div class="common_div_main"><br>'.stripslashes(tell_friend_box($in[what],$in[n],'<br>'.info_line($m[message_sent],$close_it))).'</div>';
}
else echo '<br>'.info_line($m[message_sent]);
exit;
/*
echo stripslashes(tell_friend_box($in[what],$in[n],'<br>'.info_line($m[message_sent])));
exit;
*/
###############################################################################
###############################################################################
###############################################################################

function form_control($in) {
global $s,$m;
$in = replace_array_text($in);

$in[comment] = trim($in[comment]);
if (!$in[comment]) $chyba[] = $m[m_text];
$black = try_blacklist($in[comment],"word");
if ($black) $chyba[] = $black;

$in[name] = trim($in[name]);
if (!$in[name]) $chyba[] = "$m[mis_field] $m[name]";

$in[email] = trim($in[email]);
if (!$in[email]) $chyba[] = "$m[mis_field] $m[email]";
elseif (!check_email($in[email])) $chyba[] = $m[w_email];

$in[friend_email] = trim($in[friend_email]);
if (!$in[friend_email]) $chyba[] = $m[m_friend_email];
elseif (!check_email($in[friend_email])) $chyba[] = $m[w_friend_email];

if ($s[tell_friend_captcha]) { $x = check_entered_captcha($in[image_control]); if ($x) $chyba[] = $x; }

$in[to] = $in[friend_email]; $s[email_from] = $s[mail];

return array ($chyba,$in);
}

###############################################################################
###############################################################################
###############################################################################

?>