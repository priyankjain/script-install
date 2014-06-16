<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
$username = $_SESSION['login'];
?><html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
  <table width="100%" border="0" cellpadding="0">
    <tr>
      <td width="17%">
     <div id="timer"> <?php if($invalidTurning === TRUE) { 
	 	?><FONT COLOR=RED><strong>Invalid Turning Number Entered!</strong></font><BR><?php
		echo '<script type="text/javascript" language="javascript"> alert("'.str_replace('"', "'", $custMsg).'");</script>';
	 }?>
      <font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please wait</font> 
       <form name="ss" method="post" action="index.php" style="display:inline; margin:0px; padding:0px;"><input type="text" name="tt" size="2" disabled="disabled"></form> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">sec.
        </font>
     </div>
     <div id="ssVerify" style="display: none;">
     	<?php
		$_SESSION['randPlace'] = rand(0,4); 
		$_SESSION['randomVerification'] = rand(0,9);
		$displayed = array(); //TRACK NUMBERS DISPALYED TO PREVENT DUPLICATES
		$verifyNumbers = 5; //HOW MANY NUMBERS TO DISPLAY?
		for($i = 0;$i < $verifyNumbers;$i++) {
			if($_SESSION['randPlace'] == $i) {
				?><form name="tn" method="post" action="index.php" style="display:inline; margin:0px; padding:0px;">
<input type="hidden" name="tp" value="taskfinish">
<input type="hidden" name="t" value="<?php echo $t; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="username" value="<?php echo $username; ?>">
<input type="hidden" name="submit" value="<?php echo $_SESSION['randomVerification']; ?>">
<?php echo "<input type=\"image\" value=\"".$_SESSION['randomVerification']."\" name=\"submitImage\" src=\"".$templateFolder."images/verify".$_SESSION['randomVerification'].".png\">"; ?>
</form><?php
				
			} else {
				$rand = rand(0,9);
				while($rand == $_SESSION['randomVerification'] || in_array($rand, $displayed)) $rand = rand(0,9);
				?><form name="tn" method="post" action="index.php" style="display:inline; margin:0px; padding:0px;">
<input type="hidden" name="tp" value="taskfinish">
<input type="hidden" name="t" value="<?php echo $t; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="username" value="<?php echo $username; ?>">
<input type="hidden" name="submit" value="<?php echo $rand; ?>">
<?php echo "<input type=\"image\" value=\"".$rand."\" name=\"submitImage\" src=\"".$templateFolder."images/verify".$rand.".png\">"; ?>
</form><?php
				
				$displayed[] = $rand;
			}
		}
		?><br><img src="<?php echo $templateFolder; ?>captcha_img.php">
        <?php /*
		echo "<input type=\"Submit\" value=\"Continue for Credit\" name=\"submit\">"; ?>*/ ?>
     </div>
        </td>
      <td width="83%"><div align="center">
          <?php
	  displayBanner();
	  $setupinfo = getArray("SELECT * FROM setupinfo");
	  ?>
        </div></td>
    </tr>
  </table>

<form name="finishtask" action="index.php" method="post">
<input type="hidden" name="tp" value="taskfinish">
<input type="hidden" name=t value=<?php echo  $t?>>
<input type="hidden" name=id value=<?php echo  $id?>>
<input type="hidden" name=username value=<?php echo  $username?>>
</form>
  <script>
var sec=<?php if($invalidTurning === TRUE) echo 3; else {
echo getAdTimer($id, 'links', $_SESSION['login']);
}?>;

function showForm(formID,timerID) {
	var formTag;
	var timerTag;
	formTag = document.getElementById(formID);
	timerTag = document.getElementById(timerID);
	formTag.style.display = "block";
	timerTag.style.display = "none";
}
secund();
function secund()
{
sec--;
document.ss.tt.value=sec;
if(sec==0) showForm('ssVerify','timer');
setTimeout('secund()',1000);
}
</script>
    
</body>
</html>

