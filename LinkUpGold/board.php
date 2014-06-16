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
get_messages('board.php');
include($s[phppath].'/data/data_forms.php');

if (!$_POST) $_POST = $_GET;//new ajax
if (!$_POST) show_board();
$x = form_control($_POST);
$in = $x[1];
if ($x[0])
{ echo stripslashes(board_add_message_box(info_line($m[errorsfound],implode('<br />',$x[0]))));
  exit;
}
write_to_db($in);
echo '<br>'.info_line($m[message_created]);
exit;

###############################################################################
###############################################################################
###############################################################################

function board_add_message_box($error) {
global $s,$m;
include_once("$s[phppath]/data/data_forms.php");
$a = replace_array_text($_POST);
if (($s[board_reg_only]) AND (!$s[LUG_u_n])) { $a[info] = info_line($m[no_logged]); $a[hide_add_message_form_begin] = '<!--'; $a[hide_add_message_form_end] = '-->'; }
$in[name] = $a[name]; $in[email] = $a[email];
if ($s[board_v_name]) { $x[item_name] = $m[name]; $x[field_name] = 'name'; $x[field_value] = $a[name]; $x[field_maxlength] = 255; $a[field_name] = parse_part('form_field.txt',$x); }
if ($s[board_v_email]) { $x[item_name] = $m[email]; $x[field_name] = 'email'; $x[field_value] = $a[email]; $x[field_maxlength] = 255; $a[field_email] = parse_part('form_field.txt',$x); }
if ($s[board_v_captcha]) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
if (trim($error)) $a[info] = $error;
if ($error) { $a[board_add_message_link_display] = 'none'; $a[board_add_message_box_display] = 'block'; }
else { $a[board_add_message_link_display] = 'block'; $a[board_add_message_box_display] = 'none'; }
$q = dq("select * from $s[pr]smilies group by description order by n",1);
while ($x = mysql_fetch_assoc($q))
{ $a[smilies] .= "<img src=\"$s[site_url]/images/smilies/$x[image]\" onclick=\"insertSmiley('$x[shortcut]','board_comment')\"> ";
}

return parse_part('board_add_message.txt',$a);
}

###############################################################################

function show_board() {
global $s,$m;
$q = dq("select * from $s[pr]board order by time desc limit $s[board]",0);
while ($x = mysql_fetch_assoc($q)) 
{ $x[date] = datum ($x[time],0);
  if ($x[user])
  { $user_vars = get_user_variables($x[user]);
    $x[name] = '<a href="'.get_detail_page_url('u',$user_vars[n],$user_vars[nick]).'">'.$user_vars[nick].'</a>';
  }
  $a[messages] .= parse_part('board_message.txt',$x);
}
if ($s[LUG_u_n])
{ $user_vars = get_user_variables($s[LUG_u_n]);
  $_POST[name] = $user_vars[name]; $_POST[email] = $user_vars[email];
}
$a[add_message_form] = board_add_message_box($error);
page_from_template('board.html',$a);
}

###############################################################################
###############################################################################
###############################################################################

function form_control($in) {
global $s,$m;

if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email];
}
elseif ($s[board_reg_only]) problem ($m[no_logged]);
if ($s[board_v_captcha]) { $x = check_entered_captcha($in[image_control]); if ($x) $problem[] = $x; }

if (!trim($in[board_comment])) $problem[] = $m[m_message];
elseif (strlen($in[board_comment]) > $s[board_max]) $problem[] = "$m[l_message] $s[board_max] $m[characters].";
$black = try_blacklist($in[board_comment],"word");
if ($black) $problem[] = $black;

if (($s[board_r_name]) AND (!trim($in[name]))) $problem[] = "$m[mis_field] $m[name]";
elseif (strlen($in[name]) > 255) $problem[] = $m[l_name];

if (($s[board_r_email]) AND (!trim($in[email]))) $problem[] = "$m[mis_field] $m[email]";
elseif (strlen($in[email]) > 255) $problem[] = $m[l_email];
elseif (($s[board_r_email]) AND (!check_email($in[email]))) $problem[] = $m[w_email];
$black = try_blacklist($in[email],'email'); if ($black) $problem[] = $black;

$in = replace_array_text($in);
return array ($problem,$in);
}

###############################################################################

function write_to_db($form) {
global $s;
if ($s[LUG_u_n])
{ $q = dq("select name,email from $s[pr]users where n = '$s[LUG_u_n]'",1);
  $data = mysql_fetch_row($q);
  $form[name] = $data[0]; $form[email] = $data[1];
}
$q = dq("select * from $s[pr]smilies group by shortcut",1);
while ($x = mysql_fetch_assoc($q)) { $search[] = $x[shortcut]; $replace[] = '<img border="0" src="'.$s[site_url].'/images/smilies/'.$x[image].'">'; }
$form[board_comment] = str_replace($search,$replace,$form[board_comment]);
dq("insert into $s[pr]board values ('$form[name]','$form[email]','$s[LUG_u_n]','$s[ip]','$form[title]','$form[board_comment]','$s[cas]')",0);
}

###############################################################################
###############################################################################
###############################################################################

?>