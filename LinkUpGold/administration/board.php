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
check_admin('board');

switch ($_GET[action]) {
case 'board_updated_multiple'	: board_updated_multiple($_GET);
case 'board_del_msg'			: board_del_msg($_GET[cas]);
}
board();

#################################################################################
#################################################################################
#################################################################################

function board() {
global $s;
$q = dq("select * from $s[pr]board order by time desc limit $s[board]",0);
ih();
echo $s[info];
echo show_check_uncheck_all().'<form method="get" action="board.php" id="myform">
<input type="hidden" name="action" value="board_updated_multiple">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Messages in the Message Board</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
while ($p=mysql_fetch_array($q)) 
{ $p = stripslashes_array($p);
  $p[date] = datum ($p[time],1);
  if ($p[user]) { $p[link] = "users.php?action=user_edit&user=$p[user]"; }
  else $p[link] = "mailto:$p[email]";
  echo '<tr>
  <td align="left" colspan="2"><input class="bbb" type="checkbox" name="message[]" value="'.$p[time].'"></td>
  </tr>
  <tr>
  <td align="left">Name</td>
  <td align="left" nowrap><a href="'.$p[link].'">'.$p[name].'</a>&nbsp;</td>
  </tr>
  <tr>
  <td align="left">Message</td>
  <td align="left">'.$p[text].'&nbsp;</td>
  </tr>
  <tr>
  <td align="left">IP</td>
  <td align="left">'.$p[ip].'&nbsp;</td>
  </tr>
  <tr>
  <td align="left">Time</td>
  <td align="left">'.$p[date].'&nbsp;</td>
  </tr>
  <tr><td align="left" colspan="2"><a href="board.php?action=board_del_msg&cas='.$p[time].'">Delete this message</a></td></tr>
  <tr><td align="left" colspan="2">&nbsp;</td></tr>';
}
echo '</td></tr></table></td></tr></table>';
echo '<input type="submit" name="submit" value="Delete selected messages" class="button10"></form>';
ift();
}

#################################################################################


function board_del_msg($cas) {
global $s;
dq("delete from $s[pr]board where time = '$cas'",1);
$s[info] = info_line('Selected message has been deleted');
board();
}

##################################################################################

function board_updated_multiple($in) {
global $s;
ih();
if (count($in[message]))
{ $query = my_implode('time','OR',$in[message]);
  dq("delete from $s[pr]board where $query",1);
}
echo info_line('Messages deleted');
echo '<a href="board.php?action=board">Back</a>';
ift();
}

#################################################################################
#################################################################################
#################################################################################


?>