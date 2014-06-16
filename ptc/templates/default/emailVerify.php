<h1><?php echo __('Email Validation'); ?></h1>
<p><?php echo __('We require all new members to verify they own their email address prior to accessing their earnings center.'); ?></p><?php
if($_REQUEST['vc'] != '' && $_REQUEST['em'] != '') {
	if(getValue("SELECT COUNT(username) FROM users WHERE femail = ".quote_smart($_REQUEST['em'])." AND emailValidationCode = ".quote_smart($_REQUEST['vc'])."") > 0) {
		mysql_query("UPDATE users SET accstatus = 'active' WHERE femail = ".quote_smart($_REQUEST['em'])." AND emailValidationCode = ".quote_smart($_REQUEST['vc'])."");
		$_SESSION['randomVerification'] = rand(1000,9999);
		$success = __('Thank you for your verification. Your account has been activated.').'</strong></font><form name="enter" method="post" action="index.php">
                      <br>
                      <br>
                      <table width="429" border="0" align="center">
                        <tr>
                          <td width="189">
                            <div align="right">'.__('Username').':</div></td>
                          <td width="309">
                            <input type="text" name="username" value="">
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div align="right">'.__('Password').':</div></td>
                          <td>
                            <input type="password" name="password" value="">
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div align="right">'.__('Secondary Password').':</div></td>
                          <td>
                            <input type="password" name="secondaryPassword" value="">('.__("Only if you have one set, otherwise leave blank").')
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div align="right"><img src="'.$templateFolder.'captcha_img.php?np=1" width="90" height="40" /></div></td>
                          <td>
                            <input name="randomValidationCode" type="text" value="" size="8">
                            <br />
 '.__('Enter the numbers from the image.').'                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <div align="center">
                              <input name="Submit" type="submit" class="box" value=" Login ">
                          </div></td>
                        </tr>
                      </table>
                      <p class="arial12pxReg">'.__('Not a member?').' <a href="index.php?tp=signup">Register Here</a></p>
                      <input type="hidden" name="action" value="'.('Login to my account').'">
                      <input type="hidden" name="tp" value="member">
                    </form><font><strong>';
		$sql = mysql_query("select name, comments, value, subject from design where name ='emailWelcome'");
		$count = mysql_num_rows($sql);
		if($count > 0) {
			$arr = mysql_fetch_array($sql);
			$from="From: $adminemail";
			$user = getArray("SELECT username, fpassword, femail FROM users WHERE femail = ".quote_smart($_REQUEST['em'])." AND emailValidationCode = ".quote_smart($_REQUEST['vc'])."");
			$email = $user['femail'];
			if($setupinfo['enableMd5Passwords'] == '1') $user['fpassword'] = '*encrypted*';
			$message=$arr['value']."\n\n\n ".__('Your username is').": ".$user['username']." \n ".__('Your password is').": ".$user['fpassword']." \n ".__('Use this link to enter in your account').": \n $ptrurl?tp=member";
			$subject = $ptrname.": ".$arr['subject'];
			@mail($email,$subject,$message,$from);
		}
	} else {
		$error = __('Invalid Validation Code Entered...<BR><BR>');
	}
}
if($_REQUEST['toDo'] == 'resendNow') {
	if(getValue("SELECT COUNT(username) FROM users WHERE femail = ".quote_smart($_REQUEST['em'])." AND accstatus = 'email'") > 0) {
		$user = getArray("SELECT * FROM users WHERE femail = ".quote_smart($_REQUEST['em'])." AND accstatus = 'email'");
		$message = __('Your account has been created but in order to activate your account you must verify the email address on file. If you did not create a '.$ptrname.' account, then please disregard this email and no further action is required, the account will be suspended and removed.'."\n\nIf you are the one who requested this account be activated, Please follow the link below to continue your activation process.\n\nYour verification code is",false).": ".$user['emailValidationCode']."\n\n\n";
		$url = prepURL($ptrurl);
		
		$email = $user['femail'];
		$vcode = $user['emailValidationCode'];
		$url = 'http://'.$ptrurl."?tp=emailVerify&vc=".urlencode($vcode)."&em=".urlencode($email)."";
		$message .= $url;
		$subject = $ptrname.__(" Account Validation Required");
		$fr="From: $adminemail\r\n";
		mail($email,$subject,$message,$fr);
		
		$success = __('A New verification email has been sent to your email address on file. Please follow the link provided in that email to activate your account.<BR><BR>');
	} else {
		$error = __('An account pending validation with this email address could not be found.<BR><BR>');
	}
}
?><?php echo $pageHeader; ?><?php
if($success != '') {
	echo "<FONT COLOR='GREEN'><STRONG>".$success."</STRONG></FONT>";
} else {
if($error != '') echo "<FONT COLOR=RED><STRONG>".$error."</STRONG></FONT>";
	
	if($_REQUEST['toDo'] == 'resend') {
		?>
	<strong><?php echo __('To resend your verification email,').'</strong>'.__(' please enter your email address below.'); ?><BR>
		
	<form name="enter" method="post" action="index.php">
	<table border="0" align="center">
				<tr> 
				  <td width="134"> <div align="right"><?php echo __('Email Address');?>:</div></td>
				  <td width="165"> <input type="text" name="em" value="<?php echo $_REQUEST['em']; ?>"> </td>
				</tr>
				<tr> 
				  <td height="26" colspan="2"> <div align="center"> 
					  <input type="submit" name="Submit" value="   GO!   "><input type="hidden" name="tp" value="emailVerify"><input type="hidden" name="toDo" value="resendNow">
					</div></td>
				</tr>
	  </table>
	</form>
		<?php
	} else {
	?>
		<strong><?php echo __('You must verify your email address in order to activate your account.');?></strong><br />
	<br />
	<?php echo __('Please enter the verification code below or follow the link sent to your email address to verify your account.'); ?><br />
	<br />
	<form name="enter" method="post" action="index.php">
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
					  <input type="submit" name="Submit" value="   <?php echo __('GO!'); ?>   "><input type="hidden" name="tp" value="emailVerify"><input type="hidden" name="v" value="1">
					</div></td>
				</tr>
	  </table>
	<p><?php echo __('If you would like to re-send the verification email because you have not received it, please').' <a href="index.php?tp=emailVerify&toDo=resend">'.__('Click Here').'</a> '.__('to re-send it'); ?></p>
	</form>
	<?php
	}
} //END IF success != '' && error != ''
?><?php echo $pageFooter; ?>