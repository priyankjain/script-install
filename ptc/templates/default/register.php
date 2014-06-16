<?php
$refer = $_SESSION['refer'];
######################################################################################################################

$final_user = $_REQUEST['username'];
$final_username = $_REQUEST['username'];
$final_password = $_REQUEST['password'];

// Checking form-data
$setupinfo = getArray("SELECT * FROM setupinfo LIMIT 1");

$error = ''; //SET ERROR TO DEFAULT. APPEND ERRORS TO IT, IF ITS STILL = '', NO ERRORS...

// ERROR CHECKING
// TURNING VALIDATION CODE
if($_REQUEST['randomValidationCode'] != $_SESSION['randomVerification'])
	$error .= "<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT Human Verification Code!<br>";

//EMAIL ADDRESS
if(!$email || strlen($email) < 7) {
	$error .= "<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT e-mail!<br>";
	if(strlen($email) < 7) echo 'We\'re sorry but the email you submitted is under 7 characters, which we do not accept.<br>';
}

//USER'S NAME
if(!$name1 || strlen($name1) < 2) {
	$error .= "<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT first name!<br>";
	if(strlen($name1) < 2) $error .= "We're sorry but the first name you submitted is under 2 characters.";
}

//USER'S PASSWORD, MIN 5 CHARACTERS
if(!$password || strlen($password) < 6) {
	$error .= "<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT password!<br>";
	if(strlen($password) < 6) $error .= "We're sorry but the password is under 6 characters, it must be a minimum length of 6 characters.";

}
//AGREE TO TERMS?
if(!$agree)
	$error .= "<b><font size=4 color= red>ERROR!</font></b><P>You must agree with $ptrname terms and conditions!<br>";

//EMAIL EXISTS IN SYSTEM?
if(getValue("SELECT COUNT(fid) FROM users WHERE femail=".quote_smart($email)."") > 0)
	$error .= "<font color=red size=3><b>ERROR!</b><br></font><b>This e-mail is allready in use!</b><br>";

//USERNAME EXISTS IN SYSTEM?
if(getValue("SELECT COUNT(fid) FROM users WHERE username=".quote_smart($final_username)."") > 0)
	$error .= "<font color=red size=3><b>ERROR!</b><br></font><b>This username is allready in use!</b><br>";


if($error != '') {
	//AN ERROR OCCURRED.... INCLUDE SIGNUP PAGE
	include("signup.php"); //INCLUDE SIGNUP PAGE AGAIN TO FIX PROBLEM...
} else { 
//IF NO ERROR FOUND, PROCESS REGISTRATION


//GET REFERRER FROM SESSION IF ITS NOT ALREADY SET
if(($refer == '') && $_SESSION['refer'] != '') $refer = $_SESSION['refer'];

//SET ALL BLANK IF BASE REFERRER IS BLANK
if($refer==''){
	$refer='';
	$refer2='';
	$refer3='';
	$refer4='';
	$refer5='';
	$refer6='';
	$refer7='';
	$refer8='';
	$refer9='';
	$refer10='';
}

//IF BASE REFERRER NOT EQUAL BLANK
if($refer!=''){
	//SET USERS REFERRAL COUNT + 1
	mysql_query("UPDATE users SET refcount=refcount+1 WHERE username=".quote_smart($refer)."");
	
	$lastRef = $refer;
	//LOOP THROUGH 10 LEVELS AND SET $refer2 -> $refer10 TO THEIR RESPECTIVE SETTINGS
	for($i = 2;$i < 11;$i++) {
		if($lastRef != '') {
			if(getValue("SELECT COUNT(username) FROM users WHERE username = ".quote_smart($lastRef)."") > 0) {
				//IF IT EXISTS, GET NEXT LEVEL UP
				$GLOBALS['refer'.$i] = getValue("SELECT frefer FROM users WHERE username = ".quote_smart($lastRef)."");
			} else {
				$GLOBALS['refer'.$i] = ''; //AT THE END OF THE LINE HERE, NOBODY ON THIS LEVEL...
			}
		} else {
			$GLOBALS['refer'.$i] = ''; //ALREADY AT END OF LINE...
		}
		$lastRef = $GLOBALS['refer'.$i];
	}
}

$userip=$_SERVER['REMOTE_ADDR'];
if($refer=='') {
	if(getCount("SELECT COUNT(id) FROM refconteststats WHERE username = ".quote_smart($refer)."", "COUNT") > 0) {
		mysql_query("UPDATE refconteststats SET referrals = referrals + 1 WHERE username = ".quote_smart($refer)."");
	} else {
		mysql_query("INSERT INTO refconteststats (referrals,username) VALUES ('1',".quote_smart($refer).")");
	}
}

if($setupinfo['enableMd5Passwords'] == '1') {
	$final_password = md5($final_password);
	$secondaryPassword = md5($_REQUEST['secondaryPassword']);
} else {
	$secondaryPassword = $_REQUEST['secondaryPassword']; 
}

$emailValidationCode = GetRandomString(15);
$sql=mysql_query("INSERT INTO users (femail, username, fname1, frefer, frefer2, frefer3, frefer4, frefer5, frefer6, frefer7, frefer8, frefer9, frefer10, fpaymethod, fpayacc, fpassword, userip, regdate, refcount,referringWebsite,paidEmails,emailValidationCode,language,secondaryPassword,cashoutPin) VALUES (".quote_smart($email).", ".quote_smart($final_username)." , ".quote_smart($name1).", ".quote_smart($refer).", ".quote_smart($refer2).", ".quote_smart($refer3).", ".quote_smart($refer4).", ".quote_smart($refer5).", ".quote_smart($refer6).", ".quote_smart($refer7).", ".quote_smart($refer8).", ".quote_smart($refer9).", ".quote_smart($refer10).", ".quote_smart($paymethod).", ".quote_smart($payacc).", ".quote_smart($final_password).", ".quote_smart($userip).", now(), 0,".quote_smart($_SESSION['referredBy']).",".quote_smart($paidEmails).",".quote_smart($emailValidationCode).",".quote_smart($_SESSION['lang']).", ".quote_smart($secondaryPassword).", ".quote_smart($_REQUEST['cashoutPin']).")") or die (mysql_error());
$userID = mysql_insert_id();

$baseQuery = "UPDATE users SET username = ".quote_smart($final_username)."";
if($signupAddress == 1) $queryAddition .= ",faddress = ".quote_smart($_REQUEST['faddress'])."";
if($signupCity == 1) $queryAddition .= ",fcity = ".quote_smart($_REQUEST['fcity'])."";
if($signupState == 1) $queryAddition .= ",fstate = ".quote_smart($_REQUEST['fstate'])."";
if($signupZip == 1) $queryAddition .= ",fzip = ".quote_smart($_REQUEST['fzip'])."";
if($signupCountry == 1) $queryAddition .= ",fcountry = ".quote_smart($_REQUEST['fcountry'])."";
if($signupGender == 1) $queryAddition .= ",fgender = ".quote_smart($_REQUEST['fgender'])."";
if($signupAge == 1) $queryAddition .= ",fage = ".quote_smart($_REQUEST['fage'])."";
if($signupAnualIncome == 1) $queryAddition .= ",fincoming = ".quote_smart($_REQUEST['fincoming'])."";
if($signupLanguage == 1) {
	$queryAddition .= ",lang1 = ".quote_smart($_REQUEST['nlang1'])."";
	$queryAddition .= ",lang2 = ".quote_smart($_REQUEST['nlang2'])."";
	$queryAddition .= ",lang3 = ".quote_smart($_REQUEST['nlang3'])."";
	$queryAddition .= ",lang4 = ".quote_smart($_REQUEST['nlang4'])."";
	$queryAddition .= ",lang5 = ".quote_smart($_REQUEST['nlang5'])."";
	$queryAddition .= ",lang6 = ".quote_smart($_REQUEST['nlang6'])."";
	$queryAddition .= ",lang7 = ".quote_smart($_REQUEST['nlang7'])."";
	$queryAddition .= ",lang8 = ".quote_smart($_REQUEST['nlang8'])."";
	$queryAddition .= ",lang9 = ".quote_smart($_REQUEST['nlang9'])."";
	$queryAddition .= ",lang10 = ".quote_smart($_REQUEST['nlang10'])."";
}
if($signupInterests == 1) {
	$queryAddition .= ",fintarts = ".quote_smart($_REQUEST['fintarts'])."";
	$queryAddition .= ",fintauto = ".quote_smart($_REQUEST['fintauto'])."";
	$queryAddition .= ",fintbusiness = ".quote_smart($_REQUEST['fintbusiness'])."";
	$queryAddition .= ",fintcomputers = ".quote_smart($_REQUEST['fintcomputers'])."";
	$queryAddition .= ",finteducation = ".quote_smart($_REQUEST['finteducation'])."";
	$queryAddition .= ",fintentertainment = ".quote_smart($_REQUEST['fintentertainment'])."";
	$queryAddition .= ",fintfinancial = ".quote_smart($_REQUEST['fintfinancial'])."";
	$queryAddition .= ",fintgames = ".quote_smart($_REQUEST['fintgames'])."";
	$queryAddition .= ",finthealth = ".quote_smart($_REQUEST['finthealth'])."";
	$queryAddition .= ",finthome = ".quote_smart($_REQUEST['finthome'])."";
	$queryAddition .= ",fintinternet = ".quote_smart($_REQUEST['fintinternet'])."";
	$queryAddition .= ",fintintnews = ".quote_smart($_REQUEST['fintintnews'])."";
	$queryAddition .= ",fintmedia = ".quote_smart($_REQUEST['fintmedia'])."";
	$queryAddition .= ",fintrecreation = ".quote_smart($_REQUEST['fintrecreation'])."";
	$queryAddition .= ",fintreference = ".quote_smart($_REQUEST['fintreference'])."";
	$queryAddition .= ",fintsearch = ".quote_smart($_REQUEST['fintsearch'])."";
	$queryAddition .= ",finttechnology = ".quote_smart($_REQUEST['finttechnology'])."";
	$queryAddition .= ",fintsocial = ".quote_smart($_REQUEST['fintsocial'])."";
	$queryAddition .= ",fintsports = ".quote_smart($_REQUEST['fintsports'])."";
	$queryAddition .= ",finttravel = ".quote_smart($_REQUEST['finttravel'])."";
}

if($queryAddition != '') {
	$query = $baseQuery.$queryAddition." WHERE fid = ".quote_smart($userID)."";
	$updateQuery = mysql_query($query) or die("Signup error running query: ".$query."<BR><BR>Error: ".mysql_error());
}

$sq=mysql_query("SELECT * FROM users WHERE username=".quote_smart($final_username)."") or die(mysql_error());
$arr=mysql_fetch_array($sq);
$user = $arr;
extract($arr);
$amount = $setupinfo['subonus'];
if($amount > 0) debitAccountBalance($final_username, 'credit', $amount,'usd','signupBonus');

if($setupinfo['requireEmailValidation'] == '1') {
	mysql_query("UPDATE users SET accstatus = ".quote_smart('email')." WHERE username = ".quote_smart($final_username)."");
	
	$message = __('Your account has been created but in order to activate your account you must verify the email address on file. If you did not create a '.$ptrname.' account, then please disregard this email and no further action is required, the account will be suspended and removed.'."\n\nIf you are the one who requested this account be activated, Please follow the link below to continue your activation process.\n\nYour verification code is: ").$user['emailValidationCode']."\n\n\n";
	$url = $ptrurl;
	
	$ptrurl = prepURL($ptrurl);
	$email = $user['femail'];
	$vcode = $user['emailValidationCode'];
	$url = $ptrurl."?tp=emailVerify&vc=".urlencode($vcode)."&em=".urlencode($email)."";
	$message .= $url;
	$subject = $ptrname." Account Validation Required";
	$fr="From: $adminemail\r\n";
	mail($email,$subject,$message,$fr);
	$emailValidation = TRUE;
} else {
	$emailValidation = FALSE;
}
?><?php echo $pageHeader; ?>
<table width="97%" border="0" align="left" height="212">
  <tr>
    <td height="21" align="center"> <?php

if(mysql_affected_rows) echo __("<center><P><P><P><b>Thank you for registering at $ptrname!</b><br>Your Username is ")."<b>$final_username</b><br>Your password is <b>*HIDDEN*</b></b><p>";

if($emailValidation === TRUE) {
	echo __("You are almost done with your signup process! In order to activate your account, we have sent you an email with your validation code. Please validate your account with the link in this email to continue.<BR><BR>");
	?><form name="enter" method="post" action="index.php">
<table border="0" align="center">
            <tr> 
              <td width="134"> <div align="right"><?php echo __('Email Address'); ?>:</div></td>
              <td width="165"> <input type="text" name="em" value="<?php echo $_REQUEST['em']; ?>"> </td>
            </tr>
            <tr> 
              <td width="134"> <div align="right"><?php echo __('Verification Code'); ?>:</div></td>
              <td width="165"> <input type="text" name="vc" value="<?php echo $_REQUEST['vc']; ?>"> </td>
            </tr>
            <tr> 
              <td height="26" colspan="2"> <div align="center"> 
                  <input type="submit" name="Submit" value="   GO!   "><input type="hidden" name="tp" value="emailVerify"><input type="hidden" name="v" value="1">
                </div></td>
            </tr>
  </table>
    <p><?php echo __('If you would like to re-send the verification email because you have not received it, please'); ?> <a href="index.php?tp=emailVerify&toDo=resend"><?php echo __('Click Here'); ?></a> <?php echo __('to re-send it.'); ?></p>
    </form><?php
} else {
	echo "<a href=index.php?tp=member>".__('Click here to login your account')."</a><p></center>";
}


if($emailValidation === FALSE) {
	$sql = mysql_query("select name, comments, value, subject from design where name ='emailWelcome'");
	$count = mysql_num_rows($sql);
	if($count > 0) {
		$arr = mysql_fetch_array($sql);
		$from="From: $adminemail";
		if($setupinfo['enableMd5Passwords'] == '1') $final_password = '*encrypted*';
		$message=$arr['value']."\n\n\n Your username is: $final_username \n Your password is: $final_password \n Use this link to enter in your account: \n $ptrurl?tp=member";
		$subject = $ptrname.": ".$arr['subject'];
		@mail($email,$subject,$message,$from);
	}
}
?>
    </td>
  </tr>
</table>

<?php echo $pageFooter; ?>
<?php } //END IF NO ERROR ?>