<?php
session_start();
?><?php echo $pageHeader; ?><?php
if(!isset($_SESSION['login'])) { exit(__("You must be logged in to view this page. Perhaps your session timed out?")); }

?> <h2><?php echo __('Referral\'s'); ?></h2>
<p align="center"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
  <?php
$id = $_SESSION['login'];
if($tier==1)
@$sql=mysql_query("SELECT * FROM users WHERE frefer=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==2)
@$sql=mysql_query("SELECT * FROM users WHERE frefer2=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==3)
@$sql=mysql_query("SELECT * FROM users WHERE frefer3=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==4)
@$sql=mysql_query("SELECT * FROM users WHERE frefer4=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==5)
@$sql=mysql_query("SELECT * FROM users WHERE frefer5=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==6)
@$sql=mysql_query("SELECT * FROM users WHERE frefer6=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==7)
@$sql=mysql_query("SELECT * FROM users WHERE frefer7=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==8)
@$sql=mysql_query("SELECT * FROM users WHERE frefer8=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==9)
@$sql=mysql_query("SELECT * FROM users WHERE frefer9=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");
if($tier==10)
@$sql=mysql_query("SELECT * FROM users WHERE frefer10=".quote_smart($_SESSION['login'])." ORDER BY fid DESC");

$rows=mysql_num_rows($sql);
?>
  <br>
  <?php echo __('YOUR TIER '); ?> <?php echo  $tier?>
  <?php echo __('REFERRAL LIST'); ?></font></b></p>
<table width="400" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr bgcolor="<?php echo $headerColor; ?>"> 
    <td>
	<table width="400" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr bgcolor="<?php echo $headerColor; ?>"> 
  	<td width="59"> <div align="center"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b><?php echo __('Earned'); ?></b></font></div></td>
    
    <td width="105"> <div align="center"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b><?php echo __('Reg. date'); ?></b></font></div></td>
    <td width="122"> <div align="center"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b><?php echo __('Username'); ?></b></font></div></td>
    <td width="59"> <div align="center"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b><?php echo __('Name'); ?></b></font></div></td>
  </tr>
  <?php
  for($i=0; $i<$rows; $i++)
  {
@mysql_data_seek($sql,$i);
 @$arr=mysql_fetch_array($sql);
  @extract($arr);
  $earned=$ftmclicks+$ftmreads+$ftmregs+$ftmsurveys+$ftmptrad;
  $earned = abs(getValue("SELECT SUM(famount) FROM debit WHERE fid = ".quote_smart($username)." AND type = ".quote_smart('usd')." AND famount < 0"));
  echo"
  <tr bgcolor=\"#FFFFFF\">
  <td><div style=\"font-color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">$earned</div></td>
  <td><div style=\"font-color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">$regdate</div></td>
  <td><div style=\"font-color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">$username</div></td>
  <td><div style=\"font-color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">$fname1 $fname2</div></td></tr>
  ";
  }
  ?>
</table>
</td></tr></table>
<?php echo $pageFooter; ?>