<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
extract(mysql_fetch_array(mysql_query("SELECT * FROM setupinfo")));
session_start();
$num = $_REQUEST['num'];
$fnum = $num;
$act = $_REQUEST['act'];
$sql=mysql_query("SELECT * FROM signups WHERE fnum=".quote_smart($num)."");
$count = mysql_num_rows($sql);
if($count == 0) exit("Error: Signup task could not be located. Please check your settings and try again.");

$arr=mysql_fetch_array($sql); extract($arr);

$sql=mysql_query("SELECT * FROM signtask WHERE fourlog=".quote_smart($_SESSION['login'])." AND tasknum=".quote_smart($num)."");
if(mysql_num_rows($sql)) {
	echo"You are allready confirmed this task!"; exit;
}
if($act=='confirm'){
 if(strtolower(trim(ltrim($_REQUEST['conftext'])))==strtolower(trim(ltrim($sansw)))){
	 $sql=mysql_query("SELECT * FROM users WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
	 $count = mysql_num_rows($sql);
	 if($count == 0) {
		echo "Cannot find user (".$_SESSION['login'].")<BR>";
		exit;
	 }
	 $arr=mysql_fetch_array($sql);
	 extract($arr);
	 $fpaytype = getCommPayType('ptsignup',$fnum);
	 
	 
	 $type = 'ptsignup';
	 $price = getCommPrice($_SESSION['login'],'ptsignup',$fnum);
	
	 payCommissions($_SESSION['login'], $price, $type,$fpaytype);
	
	 if($fpaytype=='usd')$newftmregs=$ftmregs+$price;
	 if($fpaytype=='points')$newftotalregs=$ftotalregs+$price;
	  
	 if($fpaytype=='usd')mysql_query("UPDATE users SET ftmregs=".quote_smart($newftmregs)." WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
	 if($fpaytype=='points')mysql_query("UPDATE users SET ftotalregs=".quote_smart($newftotalregs)." WHERE username=".quote_smart($_SESSION['login'])."") or die(mysql_error());
	 
	 mysql_query("UPDATE users SET lastActivity = NOW() WHERE username = ".quote_smart($_SESSION['login'])."");
	 mysql_query("INSERT INTO signtask (flogsponsor, fourlog, tasknum, fdate) VALUES (".quote_smart($_REQUEST['logsponsor']).", ".quote_smart($_SESSION['login']).", ".quote_smart($fnum).", now())") or die(mysql_error());
	 mysql_query("INSERT INTO activity(fid, fdate, ftask,username) VALUES (".quote_smart($id).", now(), 'pts',".quote_smart($_SESSION['login']).")") or die(mysql_error());
	 mysql_query("UPDATE signups SET fsignups = fsignups + 1 WHERE fnum = ".quote_smart($fnum)."") or die(mysql_error());
	 echo"Thank you! Your account has been credited!";
	 exit;
 } else {
 	echo"<B>Incorrect answer! Try again!</B>";
 }
}
?>


<html>
<head>
<title><?php echo "Task #$fnum / Confirm your registration" ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<form name="form1" method="post" action="index.php">
  <table width="600" border="0" align="center" background="<?php echo $templateFolder; ?>images/fon.gif.gif">
    <tr>
      <td>
        <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Your 
          login or ID# on 
          <?php echo "$furl"?>
          : 
          <input type="text" name="logsponsor">
          </font></div>      </td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCCC"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Secret 
        question:</font></b></td>
    </tr>
    <tr> 
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <?
	echo"$squest";
	?>
        <br>
        <br>
        </font></td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCCC"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Your 
        answer:</b></font></td>
    </tr>
    <tr> 
      <td> 
        <div align="center"> 
          <input type="text" name="conftext" size="50">
          <br>
          <input type="hidden" name="id" value="<?php echo"$id";?>">
          <input type="hidden" name="num" value="<?php echo"$num";?>">
          <input type="hidden" name="act" value="confirm">
          <input type="hidden" name="tp" value="confirmreg">
          <br>
          <input type="submit" name="Submit" value="Confirm">
        </div>
        <font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
    </tr>
  </table>
</form>
</body>
</html>
