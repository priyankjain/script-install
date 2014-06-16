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

//LOGIN AND GLOBAL SITE ROUTINES

$useDraconCaptcha = FALSE;
//REGISTER GLOBALS SMALL HACK FOR THIS SCRIPT, WILL BE MERGED OUT IN LATER VERSIONS
foreach($_REQUEST as $k => $v) $GLOBALS[$k] = $v;

//GRAB SITE SETUP INFO
$setupinfo = getArray("SELECT * FROM setupinfo");
//extract($setupinfo);
if(is_array($setupinfo)) foreach($setupinfo as $k => $v) $GLOBALS[$k] = $v;

//SETUP WEBSITE TEMPLATE
$templateLocation = getDefaultTemplate();
if($templateLocation == '') $templateLocation = 'default';
$templateFolder = 'templates/'.$templateLocation."/";

//SETUP SESSION
session_start();

//REFERRED WEBSITE TRACKING PER USER
if(!isset($_SESSION['referredBy'])) {
	if($_SERVER['HTTP_REFERER'] != '') {
		$_SESSION['referredBy'] = $_SERVER['HTTP_REFERER'];
	} else {
		$_SESSION['referredBy'] = 'none';
	}
}

//REFERER CHECK AND UPDATE
if($_REQUEST['ref'] != '') {
	$refer = $_REQUEST['ref'];
	$_SESSION['refer'] = $refer;
} else if($_REQUEST['refer'] != '') {
	$refer = $_REQUEST['refer'];
	$_SESSION['refer'] = $refer;
}

//UNIQUE WEBSITE HITS TRACKING
if(!isset($_SESSION['visits'])) {
	if(getCount("SELECT COUNT(id) FROM websitevisits WHERE DATE(visitDate) = DATE(NOW()) AND `type` = 'unique'", "COUNT") == 0) {
		mysql_query("INSERT INTO websitevisits (`id`, `visitDate`, `visits`,`type`) VALUES ('',DATE(NOW()),'1','unique')");
	} else {
		mysql_query("UPDATE websitevisits SET visits = visits + 1 WHERE visitDate = DATE(NOW()) AND type = 'unique'");
	}
	$_SESSION['visits'] = TRUE;
}

//WEBSITE HITS TRACKING
if(getCount("SELECT COUNT(id) FROM websitevisits WHERE DATE(visitDate) = DATE(NOW()) AND `type` = 'nonunique'", "COUNT") == 0) {
	mysql_query("INSERT INTO websitevisits (`id`, `visitDate`, `visits`,`type`) VALUES ('',DATE(NOW()),'1','nonunique')");
} else {
	mysql_query("UPDATE websitevisits SET visits = visits + 1 WHERE DATE(visitDate) = DATE(NOW()) AND type = 'nonunique'");
}

//REFERRAL URL TRACKING
if((isset($_REQUEST['refer']) || isset($_REQUEST['ref']) || isset($_REQUEST['referer'])) && !isset($_SESSION['referralURL'])) {
	if(isset($_REQUEST['refer'])) {
		$ref = $_REQUEST['refer'];
	} else if(isset($_REQUEST['ref'])) {
		$ref = $_REQUEST['ref'];
	} else if(isset($_REQUEST['referer'])) {
		$ref = $_REQUEST['referer'];
	}
	$url = substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'],"?"));
	if(getCount("SELECT COUNT(id) FROM referrals WHERE username = ".quote_smart($ref)." AND url = ".quote_smart($url)." AND visitDate = DATE(NOW())", "COUNT") == 0) {
		mysql_query("INSERT INTO referrals (`username`, `url`, `visits`,visitDate) VALUES (".quote_smart($ref).",".quote_smart($url).",'1',DATE(NOW()))");
	} else {
		mysql_query("UPDATE referrals SET visits = visits + 1 WHERE username = ".quote_smart($ref)." AND url = ".quote_smart($url)." AND visitDate = DATE(NOW())");
	}
	$_SESSION['referralURL'] = TRUE;
}

//VALIDATE IP AND CHECK THAT EMAIL VALIDATION IS NOT REQUIRED FOR LOGGED IN USERS
if($_SESSION['login'] != '') {
	$sql = mysql_query("SELECT `loginIpAddress`, accstatus FROM users WHERE username = ".quote_smart($_SESSION['login'])."") or die(mysql_error());
	$count = mysql_num_rows($sql);
	if($count > 0) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$arr= mysql_fetch_array($sql);
		if($arr['loginIpAddress'] != $ip) {
			unset($_SESSION);
			session_unregister("login");
			echo __("Your login session has been removed because your ip address did not match the one on record. Please try logging in again.");
		} else if($arr['accstatus'] == 'email') {
			$_REQUEST['tp'] = 'emailVerify';
			$tp = $_REQUEST['tp'];
		}
	} else {
		unset($_SESSION);
		session_unregister("login");
		echo __("You have been logged out because some information could not be verified. Please login again.")."<BR>";
	}
}


//LOGIN TO ACCOUNT AND RESEND PASSWORD ACTIONS
if($_REQUEST['action'] =='Login to my account' || $_REQUEST['action'] =='Cancel my membership' || $_REQUEST['action'] =='Resend password') {
	$id=$_REQUEST['username'];
	$password=$_REQUEST['password'];
	if($useDraconCaptcha == TRUE) {
		# ~~~ Flash Source ~~~~~~~~~~ # 
		define('flashSrc', '/templates/default/flash/Dracon_CAPTCHA.swf');  // flash source file
		
		# ~~~ Secret Key ~~~~~~~~~~ # 
		define('aesKey', 'znwoq8fq0jf2qjve8laper9f');  // 192bit 25 chars 
		
		if (dracon_CodeEnc(strtoupper($_REQUEST['input_captcha'])) == $_SESSION['secCode']) {
			$_SESSION['secCode_try'] = false;  // reset anti-hammering
			$_SESSION['secCode_ok'] = true;  // extra for submit check
		}
	}
	if(($useDraconCaptcha == TRUE && $_SESSION['secCode_ok'] !== true) || ($useDraconCaptcha != TRUE && $_REQUEST['randomValidationCode'] != $_SESSION['randomVerification'])) {
		$error = "<b><font size=4 color= red>".__('ERROR')."!</font></b><P>".__('INCORRECT Human Verification Code! Please click \'BACK\' button and try again...');
		if($_REQUEST['action']  == 'Resend password') {
			$_REQUEST['tp'] = 'forgotpass';
			$tp = 'forgotpass';
		} else {
			$_REQUEST['tp'] = 'user';
			$tp = 'user';
		}
	} else {
		if($useDraconCaptcha === TRUE) $_SESSION['secCode_ok'] = false;  // reset old results
		
		if($_REQUEST['action'] =='Resend password') {
			$sql=mysql_query("SELECT * FROM users WHERE femail=".quote_smart($_REQUEST['emailAddress'])."")or die(mysql_error());
			if(mysql_num_rows($sql) > 0){
				mysql_query("UPDATE users SET passResetCode = ".quote_smart(GetRandomString(12))." WHERE  femail=".quote_smart($_REQUEST['emailAddress'])."")or die(mysql_error());
				$sql=mysql_query("SELECT * FROM users WHERE femail=".quote_smart($_REQUEST['emailAddress'])."")or die(mysql_error());
				$arr = mysql_fetch_array($sql);
				
				$ptrurl = prepURL($ptrurl);
				
				$message = 'Dear '.$arr['fname1'].',
The forgotten password form was used for your account, to confirm
this action, please follow the details below.

------------------------------------------------------------

-- PASSWORD RESET FORM --

To reset your password to a new password, please follow the
link below, and then follow the instructions on the link
below.

Password Reset Code: '.$arr['passResetCode'].'

'.$ptrurl.'index.php?tp=resetPassword&rpc='.$arr['passResetCode'].'&username='.$arr['username'].'

Please copy and paste the entire url above, ensuring no \"line bre
aks\" are contained in the url.

------------------------------------------------------------
If you did not request this forgotten password form,
please dis-regard this email as no information was
shared, only provided to you in this email for security.

If you have any questions, please feel free to contact us.
You are receiving this email because a "Forgotten Password"
request was submitted on '.$ptrname.' for your email address.';
				$subject = $ptrname.' Forgotten Password Form';
				$headers="From: \"".$ptrname."\" <".$adminemail.">\n";
				
				$mail = mail($arr['femail'], $subject, $message, $headers );


				?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('An email has been sent to you with your password reset code and url.'); ?>");</SCRIPT><?php
				$_REQUEST['tp'] = 'resetPassword';
				$tp = 'resetPassword';
			} else {
				?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('Your email address was not found in our system. We recommend starting a Free account or e-mailing administration.'); ?>");</SCRIPT><?php
				$_REQUEST['tp'] = 'user';
				$tp = 'user';
			}
		} else if($_REQUEST['action']  == 'Login to my account') {		
			if($setupinfo['enableMd5Passwords'] == '1') $password = md5($password);
			
			$sqQuery = "SELECT * FROM users WHERE username=".quote_smart($id)." AND fpassword=".quote_smart($password)."";
			$sql=mysql_query($sqQuery)or die(mysql_error());
			if(mysql_num_rows($sql) > 0){
				$arr = mysql_fetch_array($sql);
				if($setupinfo['enableSecondaryPass'] == '1') {
					if($setupinfo['enableMd5Passwords'] == '1') $secondaryPass = md5($_REQUEST['secondaryPassword']);
					else $secondaryPass = $_REQUEST['secondaryPassword'];
				}
				if($arr['accstatus'] == 'email') {
					$_REQUEST['tp'] = 'emailVerify';
					$tp = $_REQUEST['tp'];
				} else if($arr['accstatus'] != 'active') {
					?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('Your account has been temporarily suspended. For more information, please contact support.'); ?>");</SCRIPT><?php
					$_REQUEST['tp'] = 'user';
					$tp = 'user';
				} else if($setupinfo['enableSecondaryPass'] == '1' && ($arr['secondaryPassword'] != '' && $secondaryPass != $arr['secondaryPassword'])) {
					?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('Your login details did not match our records.. Please check your settings and try again.'); ?>");</SCRIPT><?php
					$loginError = TRUE;
					$_REQUEST['tp'] = 'user';
					$tp = 'user';
				} else {
					$id = $_REQUEST['username'];
					//session_start();
					//session_register("login");
					$login=$id;
					$_SESSION['username'] = $id;
					$_SESSION['login'] = $id;
					if(!isset($tp)) $tp = 'member';
					mysql_query("UPDATE users SET loginIpAddress = ".quote_smart($_SERVER['REMOTE_ADDR']).", lastActivity = NOW() WHERE username = ".quote_smart($_SESSION['login'])."");
					
				}
			} else {
				?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('Your login details did not match our records. Please check your settings and try again.'); ?>");</SCRIPT><?php
				$loginError = TRUE;
				$_REQUEST['tp'] = 'user';
				$tp = 'user';
			}
		} else if($action == 'Cancel my account') { //END IF action == 'Login to my account'
		
		} //END IF ACTION = CANCEL MY ACCOUNT
	} //END VERIFICATION CODE CHECK
}

//VALIDATE USERNAME IS REGISTERED
$loginPassed = FALSE;
if(isset($_SESSION['login'])) {
	if(getValue("SELECT COUNT(fid) FROM users WHERE username = ".quote_smart($_SESSION['login'])."") > 0) {
		$loginPassed = TRUE;
	}
}

//UPDATE A RESET PASSWORD REQUEST
if($_REQUEST['action'] =='update password') {
	if(getValue("SELECT COUNT(fid) FROM users WHERE femail=".quote_smart($_REQUEST['emailAddress'])."") > 0){
		//mysql_query("UPDATE users SET passResetCode = ".quote_smart(GetRandomString(12))." WHERE  femail=".quote_smart($_REQUEST['emailAddress'])."")or die(mysql_error());
		if(getValue("SELECT COUNT(fid) FROM users WHERE passResetCode = ".quote_smart($_REQUEST['rpc'])." AND femail=".quote_smart($_REQUEST['emailAddress'])."") > 0) {
			if($_REQUEST['newPass'] == $_REQUEST['confirmNewPass'] && $_REQUEST['confirmNewSecondaryPass'] == $_REQUEST['newSecondaryPass']) {
				$sql=mysql_query("SELECT * FROM users WHERE femail=".quote_smart($_REQUEST['emailAddress'])."")or die(mysql_error());
				$arr = mysql_fetch_array($sql);
				
				
				if($setupinfo['enableMd5Passwords'] == '1') {
					$final_password = md5($_REQUEST['newPass']);
					if($setupinfo['enableSecondaryPassword'] == '1') $secondaryPassword = md5($_REQUEST['newSecondaryPass']);
				} else {
					$final_password = $_REQUEST['newPass']; 
					if($setupinfo['enableSecondaryPassword'] == '1') $secondaryPassword = $_REQUEST['newSecondaryPass'];
				}
				
				mysql_query("UPDATE users SET fpassword = ".quote_smart($final_password)." WHERE passResetCode = ".quote_smart($_REQUEST['rpc'])." AND femail=".quote_smart($_REQUEST['emailAddress'])." AND username = ".quote_smart($arr['username'])." LIMIT 1");
				
				if($setupinfo['enableSecondaryPassword'] == '1') {
					mysql_query("UPDATE users SET secondaryPassword = ".quote_smart($secondaryPassword)." WHERE passResetCode = ".quote_smart($_REQUEST['rpc'])." AND femail=".quote_smart($_REQUEST['emailAddress'])." AND username = ".quote_smart($arr['username'])." LIMIT 1");
				}
				
				$ptrurl = prepURL($ptrurl);
				
				$message = 'Dear '.$arr['fname1'].',
You have successfully updated your password. If you did not
perform this request, please manually login and change your
password, and then ensure that your email address account
has not been compromised.

------------------------------------------------------------

-- PASSWORD RESET --

Your username: '.$arr['username'].'
New Password: '.$_REQUEST['newPass'].'';
if($setupinfo['enableSecondaryPassword'] == '1') $message .= '
Secondary Password: *********';
$message .= '

'.$ptrurl.'index.php?tp=user

Use the URL above to login to your account with your new password.

------------------------------------------------------------
If you did not request this forgotten password form,
please dis-regard this email as no information was
shared, only provided to you in this email for security.

If you have any questions, please feel free to contact us.
You are receiving this email because a "Forgotten Password"
request was submitted on '.$ptrname.' for your email address.';
				$subject = $ptrname.' Password Has Been Updated';
				$headers="From: \"".$ptrname."\" <".$adminemail.">\n";
				
				$mail = mail($arr['femail'], $subject, $message, $headers );
		
		
				?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('Congratulations, your password has been updated, you may now login to your account with your new password.'); ?>");</SCRIPT><?php
				$_REQUEST['tp'] = 'user';
				$tp = 'user';
			} else {
				?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('We\'re sorry but your passwords did not match each other. Please type both the Password and Confirm Password exactly the same to continue.'); ?>");</SCRIPT><?php
				$_REQUEST['tp'] = 'resetPassword';
				$tp = 'resetPassword';
			}
		} else {
			?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('We\'re sorry but the password reset code you submitted is invalid. Please check the latest reset password email, if you submitted the form more than once please use the latest code we sent.'); ?>");</SCRIPT><?php
			$_REQUEST['tp'] = 'resetPassword';
			$tp = 'resetPassword';
		}
	} else {
		?><SCRIPT TYPE="text/javascript" LANGUAGE="javascript">alert("<?php echo __('Your email address was not found in our system.'); ?>");</SCRIPT><?php
		$_REQUEST['tp'] = 'user';
		$tp = 'user';
	}
}

//PROCESSING ORDER CHECK, INCLUDE processOrder.php
if($td == 'ordernow' && $loginPassed == TRUE && $_REQUEST['paymentType'] != '') include("processOrder.php");

//LOGOUT REQUEST
if($act=='logout') {
	$_SESSION['login'] = ''; //CLEAR OUT LOGIN...
	@session_unregister('login');
	@session_destroy();
	$logoutMessage = TRUE;
}

?>