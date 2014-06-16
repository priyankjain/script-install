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
if($_REQUEST['Submit'] == 'This looks good, send this email now') {
	if(isset($_REQUEST['emailMember'])) $emailMember = $_REQUEST['emailMember'];
	if(isset($emailMember)) {
		$sql = mysql_query("SELECT femail FROM users WHERE username = ".quote_smart($emailMember)." LIMIT 1") or die(mysql_error());
		$count = mysql_num_rows($sql);
		if($count == 0) {
			displayError("Failed to find account under username: $username!");
		} else {
			$arr = mysql_fetch_array($sql);
			$email = $arr['femail'];
			if($_REQUEST['emailType'] == 'text') {
				$headers = "Content-type: text\nFrom: \"$ptrname\" <$adminemail>\r\n\r\n";
			} else {
				$headers = "Content-type: text/html\nFrom: \"$ptrname\" <$adminemail>\r\n\r\n";
			}
			$mailer=mail($email,stripslashes($_REQUEST['subject']),stripslashes($_REQUEST['message']),$headers);
			//$mailer=mail('ocnod1234@yahoo.com',$_REQUEST['subject'],$_REQUEST['message'],$headers);
			if($mailer) {
				echo "<font color=green>Email Sent Successfully!</font>";
			} else {
				echo "<font color=red><BR><BR><STRONG>Error.</STRONG> email failed to send to ".$email."!</font>";
			}
		}
		flush();
	} else {
		$query = mysql_query("SELECT fid, fname1, fname2, femail, fpassword, username FROM users WHERE accstatus = 'active'");
		$count = mysql_num_rows($query);
		echo "Found ".$count." users who are active that will receive this email. Sending the email now, please do not close your browser until it is completed.<BR>
		<BR>Each period (.) will represent an email that has been processed. Please wait until you see the completed text below all of the dots to close your browser.<BR><BR>";
		flush();
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($query, $i);
			$arr = mysql_fetch_array($query);
			if($_REQUEST['emailType'] == 'text') {
				$headers = "Content-type: text\nFrom: '$ptrname' <$adminemail>\r\n\r\n";
			} else {
				$headers = "Content-type: text/html\nFrom: '$ptrname' <$adminemail>\r\n\r\n";
			}
			$mailer=mail($arr['femail'],$_REQUEST['subject'],$_REQUEST['message'],$headers);
			//$mailer=mail('ocnod1234@yahoo.com',$_REQUEST['subject'],$_REQUEST['message'],$headers);
			if($mailer) {
				echo "<font color=green>.</font>";
			} else {
				echo "<font color=red>.</font>";
			}
			flush();
		}
		displaySuccess("COMPLETED - Email Send Completed sending to $count users. It is now safe to close your internet browser.");
	}
}
if($_REQUEST['Submit'] == 'Make changes to this email') {
	$_REQUEST['Submit'] = '';
}
if($_REQUEST['Submit'] == 'Preview and send this email now') {
	if($_REQUEST['subject'] == '') {
		?><script type="text/javascript" language="javascript">alert("Your email must have a subject entered to send.");</script><?php
	} else if($_REQUEST['message'] == '') {
		?><script type="text/javascript" language="javascript">alert("Your email must have a message entered to send.");</script><?php
	} else {
		if(isset($_REQUEST['emailMember'])) $emailMember = $_REQUEST['emailMember'];

		?>
Please review your email below. If everything looks correct, press the Send now button.<BR>
		Subject: <?php echo htmlspecialchars(stripslashes($_REQUEST['subject'])); ?><BR>
		Message:<BR>
		<table width="499" height="26" bgcolor="#FFFFCC">
		<tr><td height="20" valign="top">
		<?php 
			if($_REQUEST['emailType']=='text') {
				echo "<pre>".htmlspecialchars(stripslashes($_REQUEST['message']))."</pre>";
			} else {
				echo stripslashes($_REQUEST['message']);
			}
		?>
		</td>
		</tr>
</table>
		<p>    <strong>Note: </strong>Your email will not have a yellow background. </p>
		<form name="form2" method="post" action="index.php">
			<input type="hidden" name="message" value="<?php echo htmlspecialchars(stripslashes($_REQUEST['message'])); ?>">
			<input type="hidden" name="subject" value="<?php echo htmlspecialchars(stripslashes($_REQUEST['subject'])); ?>">
			<input type="hidden" name="tp" value="emailMembers"><?php if(isset($emailMember)) { ?><input type="hidden" name="emailMember" value="<?php echo $emailMember; ?>"><?php } ?>
			<?php if(isset($emailMember)) { ?><input type="hidden" name="emailMember" value="<?php echo $emailMember; ?>"><?php } ?>
		  <input type="submit" name="Submit" value="Make changes to this email">
                </form>
		<form name="form2" method="post" action="index.php">
			<input type="hidden" name="message" value="<?php echo htmlspecialchars(stripslashes($_REQUEST['message'])); ?>">
			<input type="hidden" name="subject" value="<?php echo htmlspecialchars(stripslashes($_REQUEST['subject'])); ?>">
			<input type="hidden" name="emailType" value="<?php echo htmlspecialchars(stripslashes($_REQUEST['emailType'])); ?>">
			<input type="hidden" name="tp" value="emailMembers"><?php if(isset($emailMember)) { ?><input type="hidden" name="emailMember" value="<?php echo $emailMember; ?>"><?php } ?>
          <input type="submit" name="Submit" value="This looks good, send this email now">
          <br>
          <strong> IMPORTANT NOTE</strong>: Press submit only once please, this will take some time depending on how many members it has to send an email to. 
		</form>
		<p>&nbsp;</p>
		<br>
            <?php
	}
}
if($_REQUEST['Submit'] == '') {
if(isset($_REQUEST['emailMember'])) $emailMember = $_REQUEST['emailMember'];
?>
<p>Send an email to <strong><?php if(!isset($emailMember)) { ?>All Members<?php } else { echo $emailMember; } ?></strong> by filling out the information below.</p>
<table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
<thead>  <tr>
    <td width="100%" height="22" valign="top">E-Mail <strong><?php if(!isset($emailMember)) { ?>All Members<?php } else { echo $emailMember; } ?></strong></td>
  </tr>
</thead><tbody>
  <tr>
    <td height="22" valign="top"><form name="form1" method="post" action="index.php">
        <div align="left">
          Subject: 
            <input name="subject" type="text" size="55" maxlength="55" value="<?php echo $_REQUEST['subject']; ?>" />            
            <br>
            Email Type: 
            <select name="emailType">
				<option value="text" <?php if($_REQUEST['emailType'] == '' || $_REQUEST['emailType'] == 'text') { echo "selected"; } ?>>Text Based</option>
				<option value="html" <?php if($_REQUEST['emailType'] == 'html') { echo "selected"; } ?>>Html Based</option>
            </select>
            <br>
            Email Message:<br>
            <textarea name="message" cols="65" rows="12" class="wysiwyg"><?php echo $_REQUEST['message']; ?></textarea>
          <br>
          <br>
          <input type="submit" name="Submit" value="Preview and send this email now"><input type="hidden" name="tp" value="emailMembers"><?php if(isset($emailMember)) { ?><input type="hidden" name="emailMember" value="<?php echo $emailMember; ?>"><?php } ?>
        </div>
    </form></td>
  </tr>
  </tbody>
</table>
<p>&nbsp; </p>
<?php
}
?>