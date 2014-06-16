<?php
//
// COPYRIGHT 2010 PTCSHOP.COM - WRITTEN BY ZACK MYERS ocnod1234@yahoo.com
// RESALE OF THIS WEB SCRIPT IS STRICTLY FORBIDDEN
// I DID NOT ENCRYPT IT FOR YOUR PERSONAL GAIN,
// SO PLEASE DON'T SELL OR GIVE AWAY MY WORK :-)
//
// THIS FILE IS ONLY FOR ADVANCED USERS TO MODIFY
//
// FOR BASIC CONFIGURATION, PLEASE MODIFY include/cfg.php
//
//
// --------------------------------------------------------------
// DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------
// unless you know what your doing :)
//
 loginCheck(); ?><?php
?> <h2><?php echo __('Referral\'s'); ?></h2>
  <?php
$sponsor = $_REQUEST['id'];
if($tier==1)
@$sql=mysql_query("SELECT * FROM users WHERE frefer=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==2)
@$sql=mysql_query("SELECT * FROM users WHERE frefer2=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==3)
@$sql=mysql_query("SELECT * FROM users WHERE frefer3=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==4)
@$sql=mysql_query("SELECT * FROM users WHERE frefer4=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==5)
@$sql=mysql_query("SELECT * FROM users WHERE frefer5=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==6)
@$sql=mysql_query("SELECT * FROM users WHERE frefer6=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==7)
@$sql=mysql_query("SELECT * FROM users WHERE frefer7=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==8)
@$sql=mysql_query("SELECT * FROM users WHERE frefer8=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==9)
@$sql=mysql_query("SELECT * FROM users WHERE frefer9=".quote_smart($sponsor)." ORDER BY fid DESC");
if($tier==10)
@$sql=mysql_query("SELECT * FROM users WHERE frefer10=".quote_smart($sponsor)." ORDER BY fid DESC");

$rows=mysql_num_rows($sql);
?>
<p align="center"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
  <br>
  <?php echo __('YOUR TIER '); ?> <?php echo  $tier?>
  <?php echo __('REFERRAL LIST'); ?></font></b></p>
<table width="400" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr bgcolor="<?php echo $headerColor; ?>"> 
    <td>
	<table width="400" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr bgcolor="<?php echo $headerColor; ?>"> 
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
  echo"
  <tr bgcolor=\"#FFFFFF\">
  <td><div style=\"font-color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">$regdate</div></td>
  <td><div style=\"font-color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><a href=\"index.php?tp=userview&uid=".$username."\">$username</a></div></td>
  <td><div style=\"font-color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">$fname1 $fname2</div></td></tr>
  ";
  }
  ?>
</table>
</td></tr></table>
<a href="index.php?tp=userview&uid=<?php echo $sponsor; ?>">Go Back to User View</a>