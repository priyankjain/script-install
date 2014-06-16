<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<html>
<head>
<title>Thank you</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#999999" text="#000000">
<?php
$t=intval($_REQUEST['t']);
$id=intval($_REQUEST['id']);
//if($_REQUEST['Submit'] == 'Complete Survey') {
	$sqs=mysql_query("SELECT * FROM surveys WHERE id=".quote_smart($id)."") or die(mysql_error());
	$arr=mysql_fetch_array($sqs);
	extract($arr);
	$sq=mysql_query("SELECT * FROM surveyclickactivity WHERE task=".quote_smart($id)." AND username=".quote_smart($_SESSION['login'])."");
	if(mysql_num_rows($sq))
	{
	echo"<font face=verdana color=ffffff>Error! You have already taken this survey!</font>";
	exit;
	}
	$fpaytype = $ptsurvey_pay_type;
	$fprise = $ptsurvey_pay_amount;
	$fprise = getCommPrice($_SESSION['login'],'ptsurvey',$id);
	$fpaytype = getCommPayType('ptsurvey',$id);
	
	if($fpaytype=='points'){
		$sql=mysql_query("SELECT ftotalsurveys FROM users WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
		$count = mysql_num_rows($sql);
		if($count == 0) exit("ERROR!");
		
		$arr=mysql_fetch_array($sql);
		extract($arr);
		$tot=$ftotalsurveys + $fprise;
		$sq=mysql_query("UPDATE users SET ftotalsurveys=".quote_smart($tot)." WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
		$type = 'ptsurvey';
		payCommissions($_SESSION['login'], $price, $type,'points');
	}
	if($fpaytype=='usd') {
		$sql=mysql_query("SELECT ftmsurveys FROM users WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
		$arr=mysql_fetch_array($sql);
		extract($arr);
		$tot=$ftmsurveys + $fprise;
		$sq=mysql_query("UPDATE users SET ftmsurveys=".quote_smart($tot)." WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
		$type = 'ptsurvey';
		payCommissions($_SESSION['login'], $price, $type,'usd');
	}
	mysql_query("UPDATE users SET lastActivity = NOW() WHERE username = ".quote_smart($_SESSION['login'])."");

	$newvisit=$fvisits+1;
	mysql_query("UPDATE surveys SET fviews='$newvisit' WHERE id=".quote_smart($id)."");
	mysql_query("INSERT INTO surveyclickactivity (task, fip, fdate, ftime, username) VALUES(".quote_smart($t).",".quote_smart($_SERVER['REMOTE_ADDR']).", now(), now(), ".quote_smart($_SESSION['login']).")") or die(mysql_error());
	mysql_query("INSERT INTO activity(fid, fdate, ftask,username) VALUES (".quote_smart($uid).", now(), 'ptsurvey',".quote_smart($_SESSION['login']).")");
	echo"<font face=verdana color=ffffff>Thank you for visiting 
	our sponsor and participating in a paid survey! Your account has been credited! It is now save to close this window.</font>";

	@mysql_free_result($sql);
?>
</body>
</html>
