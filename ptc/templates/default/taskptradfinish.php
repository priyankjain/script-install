<?php

if(!function_exists('__')) {function __($var=''){return $var;}}
?><html>
<head>
<title><?php echo __('Thank you'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#999999" text="#000000">
<?php
$t=$_REQUEST['t'];
$id=$_REQUEST['id'];

$sqs=mysql_query("SELECT * FROM ptrads WHERE fn=".quote_smart($id)."") or die(mysql_error());
$count = mysql_num_rows($sqs);
if($count > 0) {
	$arr=mysql_fetch_array($sqs);
	extract($arr);
	$sq=mysql_query("SELECT * FROM ptradsactivity WHERE task=".quote_smart($fn)." AND username=".quote_smart($_SESSION['login'])." AND fdate=DATE(now())");
	if(mysql_num_rows($sq)){
	echo"<font face=verdana color=ffffff>".__('Error! You have already visited this site today!')."</font>";
	exit;
	}
	
	$fpaytype = $setupinfo['ptrad_pay_type'];
	$fprise = $setupinfo['ptrad_pay_amount'];
	$fprise = getCommPrice($_SESSION['login'],'ptrad',$id);
	$fpaytype = getCommPayType('ptrad',$id);
	if($fpaytype=='points'){
		//echo "Points...";
		$sql=mysql_query("SELECT ftotalptrad FROM users WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
		$count = mysql_num_rows($sql);
		if($count == 0) exit("ERROR!");
		
		$arr=mysql_fetch_array($sql);
		extract($arr);
		//$fprise = $price;
		$tot=$ftotalptrad + $fprise;
		$sq = "UPDATE users SET ftotalptrad=".quote_smart($tot)." WHERE username=".quote_smart($_SESSION['login'])."";
		$sqQuery=mysql_query($sq) or die(mysql_error());
		$type = 'ptreadads';
		payCommissions($_SESSION['login'], $price, $type,'points');
	} else if($fpaytype=='usd'){
		$sql=mysql_query("SELECT ftmptrad FROM users WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
		$count = mysql_num_rows($sql);
		if($count == 0) exit("ERROR!!");
		$arr=mysql_fetch_array($sql);
		extract($arr);
		//$fprise = $price;
		$tot=$ftmptrad + $fprise;
		$stat = __("Current Balance").": ".$ftmptrad.". ".__('New Balance').": ".$tot."";
		echo $stat;
		$sq = "UPDATE users SET ftmptrad=".quote_smart($tot)." WHERE username=".quote_smart($_SESSION['login'])."";
		$sqQuery=mysql_query($sq) or die(mysql_error());
		$type = 'ptreadads';
		payCommissions($_SESSION['login'], $price, $type,'usd');
	}
	
		$newvisit=$fvisits+1;
		mysql_query("UPDATE ptrads SET fvisits=fvisits+1 WHERE fn=".quote_smart($fn)."");
		mysql_query("INSERT INTO ptradsactivity (task, fip, fdate, ftime, username) VALUES(".quote_smart($fn).",".quote_smart($_SERVER['REMOTE_ADDR']).", now(), now(), ".quote_smart($_SESSION['login']).")") or die(mysql_error());
		mysql_query("INSERT INTO activity (fid, fdate, ftask,username) VALUES (".quote_smart($uid).", now(), 'ptrad',".quote_smart($_SESSION['login']).")");
	
	mysql_query("UPDATE users SET lastActivity = NOW() WHERE username = ".quote_smart($_SESSION['login'])."");
	?><form name='redirectFrm' id='redirectFrm' action='<?php echo $furl; ?>' target='_top'></form>
	<script type="text/javascript" language="javascript">
	alert("<?php echo __('Thank you for visiting our sponsor! Your account has been credited!'); ?>\n<?php echo $stat; ?>");
	document.redirectFrm.submit();
	</script>
<?php

} else {
	?><?php echo __('There has been an error looking up your advertisement.'); ?><BR><?php
}?>
	</body>
	</html>
