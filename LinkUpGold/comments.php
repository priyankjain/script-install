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
get_messages('comment.php');
include($s[phppath].'/data/data_forms.php');
if (!$_POST) $_POST = $_GET;//new ajax
//foreach ($_POST as $k=>$v) echo "$k - $v<br>";
if ($_POST[action]=='comment_entered') comment_entered($_POST);
elseif ($_GET[n]) comments_show($_GET);
exit;

############################################################################
############################################################################
############################################################################

function comments_show($in) {
global $s,$m;
$in = replace_array_text($in);
echo comments_get($in[what],$in[n],0);
exit;
}

############################################################################

function comment_problem($what,$n,$info) {
global $s,$m;
echo '<br>'.info_line($info,'<br><a href="#a_comments_show" onclick="javascript:parse_ajax_request(document.getElementById(\'comments_show_form'.$what.$n.'\'),\''.$s[site_url].'/comments.php?what='.$what.'&n='.$n.'\',\'comments_show_box'.$what.$n.'\'); check_show_hide_div(\'comments_show_box'.$what.$n.'\'); show_hide_div_id(0,\'enter_comment_box'.$what.$n.'\');">'.$m[show_comments].'</a>');
exit;
}

############################################################################

function comment_entered($in) {
global $s,$m;
$x = comment_form_control($in);
$in = $x[1];
if ($x[0])
{ echo stripslashes(enter_comment_box($in[what],$in[n],info_line($m[errorsfound],implode('<br />',$x[0]))));
  exit;
}
$in[comment_n] = write_to_db($in);
$in = replace_array_text($in);
if ($s[l_i_new])
{ $in[ip] = $s[ip];
  $in[to] = $in[from] = $s[mail];
  mail_from_template('comment_added.txt',$in);
}
echo '<br>'.info_line($m[comment_entered],'<br><a href="#a_comments_show" onclick="javascript:parse_ajax_request(document.getElementById(\'comments_show_form'.$in[what].$in[n].'\'),\''.$s[site_url].'/comments.php?what='.$in[what].'&n='.$in[n].'\',\'comments_show_box'.$in[what].$in[n].'\'); check_show_hide_div(\'comments_show_box'.$in[what].$in[n].'\'); show_hide_div_id(0,\'enter_comment_box'.$in[what].$in[n].'\');">'.$m[show_comments].'</a>');
exit;
}

############################################################################

function comment_form_control($in) {
global $s,$m;
$a = get_item_variables($in[what],$in[n],0);
if ($in[what]!='l') $a[url] = get_detail_page_url($in[what],$in[n],$a[rewrite_url],0,1);
$in = array_merge((array)$a,(array)$in);

if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[nick]; $in[email] = $user[email];
}
elseif ($s[register_com]) comment_problem($in[what],$in[n],$m[no_logged]);

if ($s[comm_v_captcha]) { $x = check_entered_captcha($in[image_control]); if ($x) $problem[] = $x; }
 
if (!trim($in[comment])) $problem[] = $m[m_comment];
elseif (strlen($in[comment])>$s[m_comment]) $problem[] = "$m[l_comment] $s[m_comment] $m[characters].";
$black = try_blacklist($in[comment],'word'); if ($black) $problem[] = $black;

if (($s[comm_r_name]) AND (!trim($in[name]))) $problem[] = "$m[mis_field] $m[name]";
elseif (strlen($in[name])>255) $problem[] = $m[l_name];

if (($s[comm_r_email]) AND (!trim($in[email]))) $problem[] = "$m[mis_field] $m[email]";
elseif (strlen($in[email])>255) $problem[] = $m[l_email];
elseif (($in[email]) AND (!check_email($in[email]))) $problem[] = $m[w_email];
if (try_blacklist($in[email],'email')) $problem[] = $black;

$in = replace_array_text($in);
if (($s[com_duplicate]) AND (!$problem))
{ $x = check_duplicate($in[email],$in[n],$in[what]);
  if ($x) $problem[] = $x;
}
return array($problem,$in);
}

############################################################################

function check_duplicate($email,$n,$what) {
global $s,$m;
if ($s[LUG_u_n])
{ $q = dq("select count(*) from $s[pr]comments where user = '$s[LUG_u_username]' AND item_no = '$n' AND what = '$what'",1);
  $x = mysql_fetch_row($q);
}
else
{ $q = dq("select count(*) from $s[pr]comments where (email = '$email' OR ip = '$s[ip]') AND item_no = '$n' AND what = '$what'",1);
  $x = mysql_fetch_row($q);
}
if ($x[0]) comment_problem($what,$n,$m[com_dupl]);
if ($_COOKIE[comment_c][$what][$n]) problem ($m[com_dupl]);
setcookie ("comment_c[$what][$n]",$s[cas],$s[cas]+31536000);
return false;
}

############################################################################

function write_to_db($form) {
global $s,$m;
$table = $s[item_types_tables][$form[what]];
dq("insert into $s[pr]comments values (NULL,'$form[comtitle]','$form[comment]','$form[what]','$form[n]','$form[name]','$form[email]','$s[cas]','$s[ip]','$s[com_autoapr]','$s[LUG_u_username]')",0);
$cislo = mysql_insert_id();
if ($s[com_autoapr])
{ $q = dq("select count(*) from $s[pr]comments where item_no = '$form[n]' AND what = '$form[what]' AND approved = '1'",0);
  $x = mysql_fetch_row($q);
  dq("update $table set comments = '$x[0]' where n = '$form[n]'",1);
}
if ($s[LUG_u_n])
{ $q = dq("select count(*) from $s[pr]comments where user = '$s[LUG_u_username]'",1);
  $data = mysql_fetch_row($q);
  for ($x=0;$x<=4;$x++)
  if (($data[0]>=$s['u_rank_f_'.$x]) AND ($data[0]<=$s['u_rank_t_'.$x])) { $rank = $x; break; }
  dq("update $s[pr]users set rank = '$rank', reviews = '$data[0]' where n = '$s[LUG_u_n]'",1);
}

return $cislo;
}

############################################################################

function send_emails($form) {
global $s;
$form[ip] = $s[ip];
$form[to] = $form[from] = $s[mail];
if ($s[l_i_new]) mail_from_template('email_comment.txt',$form);
}

############################################################################
############################################################################
############################################################################

?>