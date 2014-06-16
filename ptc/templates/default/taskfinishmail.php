<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<html>
<head>
<title><?php echo __('Thank you'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#999999" text="#000000">
<table width="100%" border="0" cellpadding="0">
  <tr> 
    <td>
      <?php
$mailcode=$_REQUEST['mailcode'];
$username=$_REQUEST['id'];

$sq=mysql_query("SELECT * FROM `reads` WHERE fnum=".quote_smart($mailcode)."") or die("ERROR!");
if(mysql_num_rows($sq)==0) {
	exit('We are sorry, but this ad does not exist in our system any longer.');
}


$arr=mysql_fetch_array($sq);
extract($arr);
$sq=mysql_query("SELECT * FROM `mailreads` WHERE `fourid`=".quote_smart($_REQUEST['id'])." AND `fmailnum`=".quote_smart($fnum)."");
if(mysql_num_rows($sq) > 0)
{
echo"
<table width='100%' border='0' cellpadding='0'>
  <tr> 
    <td height='18'>You have already visited this task today.</td>
    <td width='65%' align='center'>";
	  displayBanner();
  echo "
      <div align='center'></div></td>
  </tr>
</table>
";
exit;
}
$sql=mysql_query("SELECT * FROM `users` WHERE `username`=".quote_smart($_REQUEST['id'])."");
$count = mysql_num_rows($sql);
if($count == 0) {
	exit(__("Invalid username. Account missing or not found.<BR>"));
}
$arr=mysql_fetch_array($sql);
extract($arr);
$fpaytype = $ptr_pay_type;
$fprise = $ptr_pay_amount;
$fprise = getCommPrice($_SESSION['login'],'ptr',$fnum);
$fpaytype = getCommPayType('ptr',$fnum);
if($fpaytype=='usd') {
	$tot=$ftmreads + $fprise;
	$sq=mysql_query("UPDATE `users` SET `ftmreads`=".quote_smart($tot)." WHERE `username`=".quote_smart($_REQUEST['id'])."");
	$type = 'ptremail';
	payCommissions($_SESSION['login'], $price, $type,'usd');
} else if($fpaytype=='points') {
	$tot=$ftotalreads + $fprise;
	$sql = "UPDATE `users` SET `ftotalreads`=".quote_smart($tot)." WHERE `username`=".quote_smart($_REQUEST['id'])."";
	$sq=mysql_query($sql);
	$type = 'ptremail';
	payCommissions($_SESSION['login'], $price, $type,'points');
}
mysql_query("UPDATE users SET lastActivity = NOW() WHERE username = ".quote_smart($_SESSION['login'])."");

echo"<font face=verdana color=ffffff>".__('Thank you for visiting 
our sponsor! Your account has been credited!')."</font>";
$newread=$freads+1;
mysql_query("UPDATE `reads` SET `freads`=".quote_smart($newread)." WHERE `fnum`=".quote_smart($mailcode)."") or die(mysql_error());
$sql=mysql_query("SELECT `fnum` AS `num` FROM `reads` WHERE `fnum`=".quote_smart($mailcode)."") or die(mysql_error());
$arr=mysql_fetch_array($sql);
extract($arr);
mysql_query("INSERT INTO `mailreads` (`fmailnum`, `fip`, `fourid`) VALUES (".quote_smart($num).", ".quote_smart($_SERVER['REMOTE_ADDR']).", ".quote_smart($_REQUEST['id']).")") or die(mysql_error());
mysql_query("INSERT INTO `activity` (`username`, `fdate`, `ftask`) VALUES (".quote_smart($_REQUEST['id']).", now(), 'ptr')");

mysql_free_result($sql);
?>
    </td>
    <td width="65%" align="center"> 
      <?php
	 displayBanner();
	  ?>
      <div align="center"></div></td>
  </tr>
</table>
</body>
</html>

