<?php

$place = $_SESSION['randPlace'];
$num = $_SESSION['randomVerification'];
$clicked = $_REQUEST['submit'];
if(!isset($_REQUEST['submit']) || $_REQUEST['submit'] != $num) {
	$invalidTurning = TRUE;
	$custMsg = 'Clicked '.$clicked.' of '.$num.'<BR>';
	include("toptask.php");
	exit();
}

if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<html>
<head>
<title><?php echo __('Thank you'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#999999" text="#000000">
<?php
$t=$_REQUEST['t'];
$id=$_REQUEST['id'];


$sqs=mysql_query("SELECT * FROM tasks WHERE fn=".quote_smart($id)."") or die(mysql_error());
$count = mysql_num_rows($sqs);

if($count == 0) {
	exit("<font face=verdana color=ffffff>Error! This ad does not exist! An administration report has been filed and your ip and account have been logged.</font><BR><a href=\"".$furl."\" target=\"_top\">".__('Remove This Frame')."</a>");
}

$arr=mysql_fetch_array($sqs);
extract($arr);

if($fvisits > $fsize || $fsize == $fvisits) {
	exit("<font face=verdana color=ffffff>We're sorry, this ad is no longer available due to all credits have already been awarded.</font><BR><a href=\"".$furl."\" target=\"_top\">".__('Remove This Frame')."</a>");
}



$sq=mysql_query("SELECT * FROM taskactivity WHERE task=".quote_smart($fn)." AND username=".quote_smart($_SESSION['login'])." AND DATE(fdate)=DATE(now())");
if(mysql_num_rows($sq) > 0) {
	exit("<font face=verdana color=ffffff>".__('Error! You have already visited this site today!')."</font><BR><a href=\"".$furl."\" target=\"_top\">".__('Remove This Frame')."</a>");
}
$fpaytype = $ptc_pay_type;
$fprise = $ptc_pay_amount;
$fprise = getCommPrice($_SESSION['login'],'ptc',$id);
$fpaytype = getCommPayType('ptc',$id);
if($fpaytype=='points'){
	$sql=mysql_query("SELECT ftotalclicks FROM users WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
	$count = mysql_num_rows($sql);
	if($count == 0) exit("ERROR!");
	$arr=mysql_fetch_array($sql);
	extract($arr);
	$tot=$ftotalclicks + $fprise;
	$sq=mysql_query("UPDATE users SET ftotalclicks=".quote_smart($tot)." WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
	
	$type = 'ptc';
	payCommissions($_SESSION['login'], $fprise, $type,'points');
} else if($fpaytype=='usd') {
	$sql=mysql_query("SELECT ftmclicks FROM users WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
	$arr=mysql_fetch_array($sql);
	extract($arr);
	$tot=$ftmclicks + $fprise;
	$sq=mysql_query("UPDATE users SET ftmclicks=".quote_smart($tot)." WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
	$type = 'ptc';
	payCommissions($_SESSION['login'], $fprise, $type,'usd');
}
mysql_query("UPDATE users SET lastActivity = NOW() WHERE username = ".quote_smart($_SESSION['login'])."");

echo"<font face=verdana color=ffffff>".__('Thank you for visiting our sponsor! Your account has been credited!')."</font> <BR><a href=\"".$furl."\" target=\"_top\">".__('Remove This Frame')."</a>";

$newvisit=$fvisits+1;

mysql_query("UPDATE tasks SET fvisits=fvisits+1 WHERE fn=".quote_smart($id)."");

mysql_query("INSERT INTO taskactivity (task, fip, fdate, ftime, username) VALUES(".quote_smart($id).",".quote_smart($_SERVER['REMOTE_ADDR']).", now(), now(), ".quote_smart($_SESSION['login']).")") or die(mysql_error());

mysql_query("INSERT INTO activity(fid, fdate, ftask,username) VALUES (".quote_smart($uid).", now(), 'ptc',".quote_smart($_SESSION['login']).")");

?>
</body>
</html>
