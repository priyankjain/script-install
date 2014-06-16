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

$uid = $_REQUEST['uid'];

$username = $uid;



if($_REQUEST['act']=='debit') {
	if(!is_numeric($amount)) {
		echo "ERROR: This debit couldn't be processed. It must be a number format with no comma's.";
	} else {
		$username = $_REQUEST['uid'];

		$sql = mysql_query("INSERT INTO debit (fid, famount, fdate, fnum) VALUES(".quote_smart($username).", ".quote_smart($amount).", 'now()', '')");
		if(mysql_affected_rows()) { echo"<br><br><b>DEBIT account ID $username to amount USD $amount has been processed!</b><br><br>";
			$sql = mysql_query("select name, comments, value, subject from design where name ='emailDebit'");
			$array = mysql_fetch_array($sql);
			$from="From: $adminemail";
			$arr = getArray("SELECT username, fpassword,email FROM users WHERE username = ".quote_smart($username)."");
			
			if($setupinfo['enableMd5Passwords']) $arr['fpassword'] = '*encrypted*';
			$message=$array['value']."\n\n\n Your username is: ".$arr['username']." \n Your password is: ".$arr['fpassword']." \n Use this link to enter in your account: \n $ptrurl?tp=member";
			$subject = $ptrname.": ".$array['subject'];
			@mail($arr['femail'],$subject,$message,$from);
		}
	}
}

if($_REQUEST['act']=='Add') {
	if(!is_numeric($amount)) {
		echo "ERROR: This credit couldn't be processed. It must be a number format with no comma's.";
	} else {
		$username = $_REQUEST['uid'];
		$sql = mysql_query("INSERT INTO debit (fid, famount, fdate, fnum) VALUES(".quote_smart($username).", ".quote_smart("-".$amount).", 'now()', '')");
		if(mysql_affected_rows()) { echo"<br><br><b>DEBIT account ID $username to amount USD $amount has been processed!</b><br><br>";
			$sql = mysql_query("select name, comments, value, subject from design where name ='emailCredit'");
			$array = mysql_fetch_array($sql);
			$from="From: $adminemail";
			$arr = getArray("SELECT * FROM users WHERE username = ".quote_smart($username)."");
			
			if($setupinfo['enableMd5Passwords']) $arr['fpassword'] = '*encrypted*';
			$message=$array['value']."\n\n\n Your username is: ".$arr['username']." \n Your password is: ".$arr['fpassword']." \n Use this link to enter in your account: \n $ptrurl?tp=member";
			$subject = $ptrname.": ".$array['subject'];
			@mail($arr['femail'],$subject,$message,$from);
		}
	}
}

if($_REQUEST['act'] == 'chaccstatus') {
	$user = getArray("SELECT accstatus FROM users WHERE username = ".quote_smart($username)."");
	if($user['accstatus'] == 'active') {
		mysql_query("UPDATE users SET accstatus = 'inactive' WHERE username = ".quote_smart($username)."");
		displaySuccess("This user has been de-activated.");
	} else {
		mysql_query("UPDATE users SET accstatus = 'active' WHERE username = ".quote_smart($username).""); 
		displaySuccess("This user has been activated.");
	}
}

if($username == '') displayError("There has been no username entered. Please check your link and try again.");



$sql=mysql_query("SELECT * FROM users WHERE username=".quote_smart($username)."");
$count = mysql_num_rows($sql);
if($count == 0) exit("The username could not be found in the database that you entered. (".$username.")");
$arr=mysql_fetch_array($sql);
extract($arr);

if($_REQUEST['act']=='change') {
	// Checking form-data
	if(!$_REQUEST['email']) {
		displayError("INCORRECT e-mail! Please click 'BACK' button and try again...");
	} else if(!$_REQUEST['name1']) {
		displayError("INCORRECT first name! Please click 'BACK' button and try again...");
	/*} else if(!$_REQUEST['name2']) {
		displayError("INCORRECT last name! Please click 'BACK' button and try again...");
	} else if(!$_REQUEST['address']) {

		displayError("INCORRECT address! Please click 'BACK' button and try again...");
	} else if(!$_REQUEST['city']) {
		displayError("INCORRECT city! Please click 'BACK' button and try again...");
	} else if(!$_REQUEST['state']){
		displayError("INCORRECT state! Please click 'BACK' button and try again...");
	} else if(!$_REQUEST['zip']) {
		displayError("INCORRECT zip or postal code! Please click 'BACK' button and try again...");*/
	} else if(!$_REQUEST['country']) {
		displayError("INCORRECT country! Please click 'BACK' button and try again...");
	} else {
		$noerrors=1;
	}
	
	if($noerrors == 1){
		
		
		
		if(!$nlang1)$nlang1=0; if(!$nlang2)$nlang2=0; if(!$nlang3)$nlang3=0; if(!$nlang4)$nlang4=0; if(!$nlang5)$nlang5=0; if(!$nlang6)$nlang6=0; if(!$nlang7)$nlang7=0; if(!$nlang8)$nlang8=0; if(!$nlang9)$nlang9=0; if(!$nlang10)$nlang10=0;if(!$incoming)$incoming=0;

if($demoMode === TRUE && $_SERVER['REMOTE_ADDR'] != '76.185.41.118')  {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {

		$ch_base=mysql_query("

		UPDATE users SET

		paidEmails=".quote_smart($_REQUEST['paidEmails']).",
		
		femail=".quote_smart($_REQUEST['email']).",

		fname1=".quote_smart($_REQUEST['name1']).",

		fname2=".quote_smart($_REQUEST['name2']).",

		faddress=".quote_smart($_REQUEST['address']).",

		fcity=".quote_smart($_REQUEST['city']).",

		fstate=".quote_smart($_REQUEST['state']).",

		fzip=".quote_smart($_REQUEST['zip']).",

		fcountry=".quote_smart($_REQUEST['country']).",

		fgender=".quote_smart($_REQUEST['gender']).",

		fage=".quote_smart($_REQUEST['age']).",

		fincoming=".quote_smart($_REQUEST['incoming']).",

		fintarts=".quote_smart($_REQUEST['int1']).",

		fintauto=".quote_smart($_REQUEST['int2']).",

		fintbusiness=".quote_smart($_REQUEST['int3']).",

		fintcomputers=".quote_smart($_REQUEST['int4']).",

		finteducation=".quote_smart($_REQUEST['int5']).",

		fintentertainment=".quote_smart($_REQUEST['int6']).",

		fintfinancial=".quote_smart($_REQUEST['int7']).",

		fintgames=".quote_smart($_REQUEST['int8']).",

		finthealth=".quote_smart($_REQUEST['int9']).",

		finthome=".quote_smart($_REQUEST['int10']).",

		fintinternet=".quote_smart($_REQUEST['int11']).",

		fintintnews=".quote_smart($_REQUEST['int12']).",

		fintmedia=".quote_smart($_REQUEST['int13']).",

		fintrecreation=".quote_smart($_REQUEST['int14']).",

		fintreference=".quote_smart($_REQUEST['int15']).",

		fintsearch=".quote_smart($_REQUEST['int16']).",

		finttechnology=".quote_smart($_REQUEST['int17']).",

		fintsocial=".quote_smart($_REQUEST['int18']).",

		fintsports=".quote_smart($_REQUEST['int19']).",

		finttravel=".quote_smart($_REQUEST['int20']).",

		userip=".quote_smart($_REQUEST['userip']).",

		lang1=".quote_smart($_REQUEST['nlang1']).",

		lang2=".quote_smart($_REQUEST['nlang2']).",

		lang3=".quote_smart($_REQUEST['nlang3']).",

		lang4=".quote_smart($_REQUEST['nlang4']).",

		lang5=".quote_smart($_REQUEST['nlang5']).",

		lang6=".quote_smart($_REQUEST['nlang6']).",

		lang7=".quote_smart($_REQUEST['nlang7']).",

		lang8=".quote_smart($_REQUEST['nlang8']).",

		lang9=".quote_smart($_REQUEST['nlang9']).",

		lang10=".quote_smart($_REQUEST['nlang10']).",

		fpaymethod=".quote_smart($_REQUEST['paymethod']).",

		fpayacc=".quote_smart($_REQUEST['payacc']).",
		
		secondaryPassword=".quote_smart($secondaryPassword)."

		WHERE

		username=".quote_smart($_REQUEST['username'])."") or die(mysql_error());

		
		
		if($_REQUEST['password'] != '') {
			if($_REQUEST['password'] != $_REQUEST['confirmpassword']) {
				displayError("INCORRECT password confirmation! Please click 'BACK' button and try again...");
			} else {
				if($setupinfo['enableMd5Passwords'] == '1') $password = md5($_REQUEST['password']);
				else $password = $_REQUEST['password'];
				$ch_base=mysql_query("UPDATE users SET fpassword=".quote_smart($password)." WHERE username=".quote_smart($_REQUEST['username'])."") or die(mysql_error());
			}


		}
		
		if(mysql_affected_rows()) displaySuccess("Your data has been changed successfully...");

		$sql=mysql_query("SELECT * FROM users WHERE femail=".quote_smart($_REQUEST['email'])." AND fpassword=".quote_smart($_REQUEST['password'])."");
		$arr=mysql_fetch_array($sql);

		extract($arr);
} //END DEMO MODE
	}

}

if($_REQUEST['remove'] && $_REQUEST['confirm'] == 'yes') {
	$username = $_REQUEST['uid'];
	//DELETE ACTIVITY
	mysql_query("DELETE FROM activity WHERE username=".quote_smart($username)."");
	//DELETE BANNERS
	mysql_query("DELETE FROM banners WHERE username=".quote_smart($username)."");
	//DELETE CREDIT ADDITIONS
	mysql_query("DELETE FROM creditadditions WHERE username=".quote_smart($username)."");
	//DELETE DEBITS
	mysql_query("DELETE FROM `debit` WHERE fid=".quote_smart($username)."");
	//DELETE FEATURED BANNERS
	mysql_query("DELETE FROM fbanners WHERE username=".quote_smart($username)."");
	//DELETE FEATURED ADS
	mysql_query("DELETE FROM featuredads WHERE username=".quote_smart($username)."");
	//DELETE FEATURED LINKS
	mysql_query("DELETE FROM featuredlinks WHERE username=".quote_smart($username)."");
	//DELETE EMAIL READS
	mysql_query("DELETE FROM mailreads WHERE fourid=".quote_smart($username)."");
	//DELETE ORDERS
	mysql_query("DELETE FROM orders WHERE username=".quote_smart($username)."");
	//DELETE PTR ADS
	mysql_query("DELETE FROM ptrads WHERE username=".quote_smart($username)."");
	//DELETE PTR ADS ACTIVITY
	mysql_query("DELETE FROM ptradsactivity WHERE username=".quote_smart($username)."");
	//DELETE READS
	mysql_query("DELETE FROM `reads` WHERE username=".quote_smart($username)."");
	//DELETE SIGNUP TASKS
	mysql_query("DELETE FROM signtask WHERE flogsponsor=".quote_smart($username)."");
	//DELETE SIGNUP ADS
	mysql_query("DELETE FROM signups WHERE username=".quote_smart($username)."");
	//DELETE SURVEY ACTIVITY
	mysql_query("DELETE FROM surveyactivity WHERE username=".quote_smart($username)."");
	//DELETE SURVEY ACTIVITY
	mysql_query("DELETE FROM surveyclickactivity WHERE username=".quote_smart($username)."");
	//DELETE SURVEYS
	mysql_query("DELETE FROM surveys WHERE username=".quote_smart($username)."");
	//DELETE PTC CLICK HISTORY
	mysql_query("DELETE FROM taskactivity WHERE username=".quote_smart($username)."");
	//DELETE PTC ADS
	mysql_query("DELETE FROM tasks WHERE username=".quote_smart($username)."");
	
	//UPDATE REFERRALS
	$user = getArray("SELECT * FROM users WHERE username = ".quote_smart($username)."");
	
	//MOVE UP ONE LEVEL
	$fields = array('frefer','frefer2','frefer3','frefer4','frefer5','frefer6','frefer7','frefer8','frefer9','frefer10');
	foreach($fields as $k => $v) mysql_query("UPDATE users SET `".$k."`=".quote_smart('')." WHERE `".$k."`=".quote_smart($username)."");
	
	if(mysql_query("DELETE from users WHERE username=".quote_smart($username)."")) {
		displaySuccess("Member $username deleted successfully!");
		exit;
	} else {
		displayError("Members account could not be found.");
		exit;
	}
}



if($_REQUEST['remove'] && $_REQUEST['confirm'] != 'yes') {
	echo "<BR><BR>You are about to PERMANENTLY remove this members account. Are you sure you wish to proceed ?<BR><BR><a href=\"index.php?tp=userview&remove=1&confirm=yes&uid=".urlencode($_REQUEST['uid'])."\">Yes - Delete this account (".$_REQUEST['id'].").</a><BR><BR><BR><a href=\"index.php?tp=userview&id=".urlencode($_REQUEST['id'])."&uid=".urlencode($_REQUEST['id'])."\">No - Go back</a><BR><BR>";
}



if($_REQUEST['addRemoveCreditsNow'] == '1') {

	if($_REQUEST['uid'] != '') {

		if(is_numeric($_REQUEST['credits'])) {

			if($_REQUEST['creditType'] == 'banner') { $adType = 'Banner'; }

			if($_REQUEST['creditType'] == 'fbanner') { $adType = 'Featured Banner'; }

			if($_REQUEST['creditType'] == 'fad') { $adType = 'Featured Ad'; }

			if($_REQUEST['creditType'] == 'flinks') { $adType = 'Featured Link'; }

			if($_REQUEST['creditType'] == 'links') { $adType = 'PTC / Link'; }

			if($_REQUEST['creditType'] == 'signup') { $adType = 'Guaranteed Signup'; }

			if($_REQUEST['creditType'] == 'email') { $adType = 'E-Mail'; }

			if($_REQUEST['creditType'] == 'survey') { $adType = 'Paid Survey'; }

			if($_REQUEST['creditType'] == 'ptrad') { $adType = 'Paid to Read Ads'; }

			

			if(getValue("SELECT COUNT(username) FROM users WHERE username = ".quote_smart($_REQUEST['uid'])."") > 0) {

				if($_REQUEST['Submit'] == 'Add credits') {

					mysql_query("INSERT INTO creditadditions (username, credits, creditsFor, additionDate, orderId) VALUES (

					".quote_smart($_REQUEST['uid']).",".quote_smart($_REQUEST['credits']).",".quote_smart($_REQUEST['creditType']).",'NOW()','0')");

					$message = 'Added '.$_REQUEST['credits'].' '.$adType.' credits to '.$_REQUEST['uid']."'s account successfully.";

					?><script type="text/javascript" language="javascript">alert("<?php echo $message; ?>");</script><?php

				} else if($_REQUEST['Submit'] == 'Remove credits') {

					mysql_query("INSERT INTO creditdebits (username, credits, creditsFor, debitDate) VALUES (

					".quote_smart($_REQUEST['id']).",".quote_smart($_REQUEST['credits']).",".quote_smart($_REQUEST['creditType']).",'NOW()')") or die(mysql_error());

					$message = 'Removed '.$_REQUEST['credits'].' '.$adType.' credits from '.$_REQUEST['uid']."'s account successfully.";
					displaySuccess($message);

				} else {

					displayError("Could not determine if you wanted to add or remove credits. Perhaps you pressed enter, but did not click on a button?");

				}

			} else {

				displayError("The members account could not be found (".$_REQUEST['uid'].").");

			}

		} else {

			displayError("Credits was not a valid number, please enter only numbers.");

		}

	} else {

		displayError("The members username was left blank.");

	}

}




if($_REQUEST['action'] == 'updateMembershipNow') {
	$package = $_REQUEST['membershipID'];
	$username = $_REQUEST['uid'];
	$sql = mysql_query("SELECT * FROM membershiptypes WHERE id = ".quote_smart($package)."");
	$count = mysql_num_rows($sql);
	if($count > 0) {
		$query = mysql_query("SELECT itemID as item FROM membershipitems WHERE membershipID = ".quote_smart($package)."");
		$count = mysql_num_rows($query);
		if($count > 0) {
			for($i = 0;$i < $count;$i++) {
				mysql_data_seek($query, $i);
				$arr = mysql_fetch_array($query);
				$item = getArray("SELECT pack_credits, pack_credits_type FROM packages WHERE fnum = ".quote_smart($arr['item'])."");
				$q = mysql_query("INSERT INTO creditadditions
				(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
				VALUES
				(".quote_smart($username).", ".quote_smart($item['pack_credits']).", ".quote_smart($item['pack_credits_type']).",NOW(),".quote_smart($custom).")
				");
				$notifyLog .= "Inserted new credits addition for item ".$arr['item'].".\n";
			}
		} else {
			$notifyLog .= "There were no items found for membership. ".$fnum." !!!!\n ERROR FILLING CREDIT REQUESTS!!! \n";
			$notifyLog = " ERROR FILLING CREDIT REQUESTS!!! \n\n".$notifyLog;
		}
		$membership = mysql_fetch_array($sql);
		if($membership['lengthType'] == 'lifetime') {
			$endDate = '0000-00-00';
			$lifetime = '1';
		} else {
			if($membership['lengthType'] == 'd') {
				$lengthTypeMySQL = 'DAY';
			} else if($membership['lengthType'] == 'w') {
				$lengthTypeMySQL = 'WEEK';
			} else if($membership['lengthType'] == 'm') {
				$lengthTypeMySQL = 'MONTH';
			} else if($membership['lengthType'] == 'y') {
				$lengthTypeMySQL = 'YEAR';
			}
			$endDate = 'NOW() + INTERVAL '.$membership['length'].' '.$lengthTypeMySQL;
			$lifetime = '0';
		}
		mysql_query("UPDATE memberships SET active = '0' WHERE username = ".quote_smart($username)."");
		mysql_query("INSERT INTO memberships (
			username,
			membershipName,
			membershipType,
			orderID,
			price,
			active,
			endDate,
			startDate,
			lifetime
		) VALUES (
			".quote_smart($username).",
			".quote_smart($membership['membershipName']).",
			".quote_smart($package).",
			".quote_smart($custom).",
			".quote_smart($membership['membershipPrice']).",
			'1',
			".$endDate.",
			".quote_smart($_REQUEST['startDate']).",
			".quote_smart($lifetime)."
		)");
		displaySuccess("Successfully updated ".$_REQUEST['uid']."'s account to ".$membership['membershipName']."");
	} else {
		displayError("This membership could not be found.");
	}
}

?><!-- 1 --><?php

if($_REQUEST['act'] == 'updateMembership') {
$membershipDetails = getArray("SELECT CONCAT(membershipName,' (Ends ', DATE(endDate), ')') AS membershipName, membershipID FROM memberships WHERE active = '1' AND (( startDate < NOW() AND endDate > NOW() ) OR ( lifetime = '1' )) AND username = ".quote_smart($_REQUEST['uid'])."");
$membership = $membershipDetails['membershipName'];

if($membership == '') $membership = "Default member";
?><h3>Update <?php echo $_REQUEST['username']; ?>'s membership.</h3>
<form name="form" method="post" action="index.php">
    Current Membership: <?php echo $membership; ?><br>
    New Membership Details<BR>
    Choose the membership: <select name="membershipID">
    <?php
    $sql = mysql_query("SELECT id, membershipName, length, lengthType FROM membershiptypes WHERE active = '1'");
	$count = mysql_num_rows($sql);
	?><option value="" <?php if($membership == 'Default member') echo 'selected="selected"'; ?>>SET NO MEMBERSHIP</option><?php
    if($count > 0) {
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($sql, $i);
			$arr = mysql_fetch_array($sql);
			$lengthType = $arr['lengthType'];
			$length = $arr['length'];
			?><option value="<?php echo $arr['id']; ?>" <?php if($membershipDetails['membershipID'] == $arr['id']) echo 'selected="selected"'; ?>><?php
			echo $arr['membershipName']." (";
			if($lengthType == 'lifetime') echo "Lifetime";
			else if($lengthType == 'd') echo $length." Day(s)";
			else if($lengthType == 'w') echo $length." Week(s)";
			else if($lengthType == 'm') echo $length." Month(s)";
			else if($lengthType == 'y') echo $length." Year(s)";
			echo ")";
			?></option><?php
		} //END FOR LOOP
	} //END COUNT > 0
	?>
    </select><br>
    Start Date: <input type="text" name="startDate" value="<?php echo date("Y-m-d"); ?>"><br>
    <br>
    <input type="submit" name="submit" value="Update Membership">
    <input type="hidden" name="tp" value="userview">
    <input type="hidden" name="action" value="updateMembershipNow">
    <input type="hidden" name="username" value="<?php echo $_REQUEST['username']; ?>">
    <input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']; ?>">
</form>
<?php

} else if($_REQUEST['addRemoveCredits'] == '1') {

	if($_REQUEST['uid'] != '') {

		if(getValue("SELECT COUNT(username) FROM users WHERE username = ".quote_smart($_REQUEST['uid'])."") > 0) {

			?>



  <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>

      <td>Add / Remove credits to <?php echo $_REQUEST['uid']; ?>'s account. </td>

    </tr>
</thead><tbody>
    <tr>

      <td height="22" valign="top"><form name="form" method="post" action="index.php">
      <input type="hidden" name="tp" value="userview">

          

          <div align="center">Credit Type: 

              <select name="creditType">

                <option value="banner" <?php $creditType = $_REQUEST['creditType']; if($creditType == 'banner' || $creditType == '') { echo "selected"; } ?>>Banners (480x60)</option>

                <option value="fbanner" <?php if($creditType == 'fbanner') { echo "selected"; } ?>>Featured Banners (180x100)</option>

                <option value="fad" <?php if($creditType == 'fad') { echo "selected"; } ?>>Featured Ad</option>

                <option value="flinks" <?php if($creditType == 'flinks') { echo "selected"; } ?>>Featured Link</option>

                <option value="links" <?php if($creditType == 'links') { echo "selected"; } ?>>Link / Paid to Click</option>

                <option value="signup" <?php if($creditType == 'signup') { echo "selected"; } ?>>Paid To Sign-Up</option>

                <option value="email" <?php if($creditType == 'email') { echo "selected"; } ?>>Paid To Read E-Mail</option>

				<option value="survey" <?php if($creditType == 'survey') { echo "selected"; } ?>>Paid To Take Survey's</option>

				<option value="ptrad" <?php if($creditType == 'ptrad') { echo "selected"; } ?>>Paid To Read Ad's</option>

	        </select>

            <br>

            Credits: 

            <input name="credits" type="text" size="8">

            <br>

            <br>

            <br>

            <br>

            <input type="hidden" name="id" value="<?php echo $_REQUEST['uid']; ?>">

			<input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']; ?>">

            <input type="hidden" name="addRemoveCreditsNow" value="1">

            <input type="submit" name="Submit" value="Add credits">

            <br>

            <br>

            <strong>Or</strong>            <br>

            <br>



            <input type="submit" name="Submit" value="Remove credits">

          </div>

      </form></td>

    </tr>
</tbody>
  </table>

  <p><BR></p>

    <?php

		} else {

			displayError("The members account could not be found (".$_REQUEST['uid'].")");		

		}

	} else {

		displayError("The members username was left blank.");

	}

}

?>

<br>

<table class="fullwidth" border="0" cellpadding="0" cellspacing="0">

  <tr>

    <th>Username</th>

    <td><?php echo $username; ?></td>
  </tr>

  

  <tr valign="top" bgcolor="#FFFFFF">

    <th>Membership</th>

    <td><?php
    $membership = getValue("SELECT CONCAT(membershipName,' (Ends ', DATE(endDate), ')') AS membershipName FROM memberships WHERE active = '1' AND (( startDate < NOW() AND endDate > NOW() ) OR ( lifetime = '1' )) AND username = ".quote_smart($username)."");
	if($membership == '') echo "Default member"; else echo $membership;
	?> (<a href="index.php?tp=userview&act=updateMembership&username=<?php echo $username; ?>&uid=<?php echo $username; ?>">Modify Membership</a>)</td>
  </tr>

  <tr valign="top">

    <th><b>Actions</b></th>

    <td>Status: <?php echo  $accstatus?><br />
<form name="form" method="post" action="index.php"><input type="hidden" name="tp" value="userview" style="margin: 0 0 0 0;">

        <input type="hidden" name="act" value="chaccstatus">

        <input type="hidden" name="uid" value="<?php echo "$username";?>">

        <input type="submit" name="Submit" value="<?if($accstatus=='active')echo"Suspend Temporarily"; else echo"Activate"?>">
      </form><form name="form" method="post" action="index.php" style="margin: 0 0 0 0;"><input type="hidden" name="tp" value="userview">

          <input type="hidden" name="uid" value="<?php echo "$username"; ?>">

          <input type="hidden" name="id" value="<?php echo "$username"; ?>">

          <input type="hidden" name="addRemoveCredits" value="1">
          <input type="submit" name="Submit" value="Add / Remove Advertising Credits to This Account" />
      </form><form name="form1" method="post" action="index.php" style="margin: 0 0 0 0;">

        
        <input type="hidden" name="emailMember" value="<?php echo "$username"; ?>">

        <input type="hidden" name="tp" value="emailMembers">

        <input type="submit" name="sendEmail" value="Send an Email to This Member">
        </form>


    </td>
  </tr>

  <tr valign="top">

    <td colspan="2"></td>
  </tr>

  <tr valign="top" bgcolor="#FFFFFF">

    <td colspan="2"></td>
  </tr>
</table>

<form name="form" method="post" action="index.php"><input type="hidden" name="tp" value="userview">

  <div align="right">

    <input type="hidden" name="id" value="<?php echo "$username";?>">

    <input type="hidden" name="uid" value="<?php echo "$username";?>">

    <input type="hidden" name="remove" value="1">

    <input type="submit" name="Submit" value="Remove This Member">

  </div>

</form>

<table class="fullwidth" border="0" cellspacing="0">



  <tr> 



    <td colspan="2"><h3>Personal earnings</h3></td>



  </tr>



  <tr> 



    <td><h3>Sign-up bonus:</h3></td>



    <td><?php echo $setupinfo['currency']; ?><?



	  extract(mysql_fetch_array(mysql_query("SELECT * FROM setupinfo")));

echo number_format(abs(getValue("SELECT famount FROM debit WHERE debitFor = 'signupBonus' AND fid = ".quote_smart($username)."")),7);




	?>



    </td>



  </tr>



  <tr> 



    <td><h3>Paid Email Clickthrus:</h3></td>



    <td width="26%"> <?php echo $setupinfo['currency']; ?><?php echo "$ftmreads";?>



    </td>



  </tr>



  <tr> 



    <td><h3>Paid clicks:</h3></td>



    <td> <?php echo $setupinfo['currency']; ?><?php echo "$ftmclicks";?>



    </td>



  </tr>



  <tr> 



    <td><h3>Paid signups:</h3></td>



    <td> <?php echo $setupinfo['currency']; ?><?php echo "$ftmregs";?>



    </td>



  </tr>



  <tr> 



    <td><h3>Payment Method:</h3></td>



    <td> <?



	  echo $fpaymethod;



	  ?>



    </td>



  </tr>



  <tr>

    <td><h3>Payment Account:</h3></td>

    <td> <?



	  echo $fpayacc;



	  ?>

    </td>

  </tr>



  

  <tr>

    <td><h3>Total Balance:</h3></td>

    <td> <?php echo $setupinfo['currency']; ?><?



	  $total=$ftmclicks+$ftmreads+$ftmregs+$$refBonus;



	  $total=totalEarnings($username);



	  echo number_format($total,7);



	  ?>

    </td>

  </tr>

  <tr>

    <td colspan="2"><hr></td>

  </tr>

  <tr>

    <td colspan="2">

      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

        <tr>

          <td width="33%" valign="top">

            <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
				<thead>
              <tr>

                <td colspan="2">Referrals count</td>

              </tr>
				</thead><tbody>
              <?php



	   if($levels>0) 



	   {



	   echo" <tr> 



          <td>Tier 1 referrals count:</td>



          <td> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=1 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>1) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 2 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer2='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=2 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>2) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 3 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer3='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=3 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>3) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 4 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer4='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=4 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>4) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 5 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer5='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=5 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>5) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 6 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer6='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=6 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>6) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 7 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer7='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=7 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>7) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 8 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer8='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=8 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>8) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 9 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer9='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=9 target=blank>view</a>] </font></td></tr>";



	}



	?>

              <?



	   if($levels>9) 



	   {



	   echo" <tr> 



          <td width='63%'>Tier 10 referrals count:</td>



          <td width='37%'> <font size=1> ";



            



	$sq=mysql_query("SELECT * FROM users WHERE frefer10='$uid'");



	$rows=mysql_num_rows($sq);



	echo"$rows &nbsp;[<a href=index.php?tp=referrals&id=$username&tier=10 target=blank>view</a>] </font></td></tr>";



	}



	?>
</tbody>
          </table>
          
          </td>

          <td valign="top" width="33%"><table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
			<thead>
              <tr>

                <td colspan=2>points bonuses:</td>

              </tr>
			 </thead><tbody>
              <?



       if($levels>0){echo" <tr> 



          <td>Tier 1</td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus1=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus1=$refbonus1+($total/100*$ref1bonus);



	$total=0;



	}



	echo"$refbonus1 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>1){echo" <tr> 



          <td>Tier 2</td>



          <td > ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer2='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus2=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus2=$refbonus2+($total/100*$ref2bonus);



	$total=0;



	}



	echo"$refbonus2 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>2){echo" <tr> 



          <td >Tier 3 </td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer3='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus3=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus3=$refbonus3+($total/100*$ref3bonus);



	$total=0;



	}



	echo"$refbonus3 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>3){echo" <tr> 



          <td >Tier 4 </td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer4='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus4=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus4=$refbonus4+($total/100*$ref4bonus);



	$total=0;



	}



	echo"$refbonus4 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>4){echo" <tr> 



          <td>Tier 5</td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer5='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus5=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus5=$refbonus5+($total/100*$ref5bonus);



	$total=0;



	}



	echo"$refbonus5 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>5){echo" <tr> 



          <td>Tier 6</td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer6='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus6=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus6=$refbonus6+($total/100*$ref6bonus);



	$total=0;



	}



	echo"$refbonus6 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>6){echo" <tr> 



          <td>Tier 7</td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer7='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus7=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus7=$refbonus7+($total/100*$ref7bonus);



	$total=0;



	}



	echo"$refbonus7 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>7){echo" <tr> 



          <td >Tier 8 </td>



          <td >  ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer8='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus8=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus8=$refbonus8+($total/100*$ref8bonus);



	$total=0;



	}



	echo"$refbonus8 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>8){echo" <tr> 



          <td>Tier 9</td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer9='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus9=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus9=$refbonus9+($total/100*$ref9bonus);



	$total=0;



	}



	echo"$refbonus9 points







          </td>



        </tr>";}



		?>

              <?



       if($levels>9){echo" <tr> 



          <td>Tier 10</td>



          <td> ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer10='$uid'");



	$rows=mysql_num_rows($sq);



	$refbonus10=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



$total=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;



	$refbonus10=$refbonus10+($total/100*$ref10bonus);



	$total=0;



	}



	echo"$refbonus10 points







          </td>



        </tr>";}



		?>
</tbody>
          </table></td>

          <td valign="top" width="34%"><table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <td colspan=2>cash bonuses</td>
        </tr>
      </thead>
	  <tbody>
        <?



       if($levels>0){echo" <tr> 



          <td>Tier 1</td>





          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus1=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus1=$crefbonus1+($total/100*$ref1bonus);



	$total=0;



	}



	echo number_format($crefbonus1,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>1){echo" <tr> 



          <td >Tier 2</td>



          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer2='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus2=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftmsurveys+$ftmptrad;



	$crefbonus2=$crefbonus2+($total/100*$ref2bonus);



	$total=0;



	}



	echo number_format($crefbonus2,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>2){echo" <tr> 



          <td >Tier 3 </td>



          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer3='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus3=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus3=$crefbonus3+($total/100*$ref3bonus);



	$total=0;



	}



	echo number_format($crefbonus3,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>3){echo" <tr> 



          <td >Tier 4 </td>



          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer4='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus4=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus4=$crefbonus4+($total/100*$ref4bonus);



	$total=0;



	}



	echo number_format($crefbonus4,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>4){echo" <tr> 



          <td>Tier 5</td>



          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer5='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus5=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus5=$crefbonus5+($total/100*$ref5bonus);



	$total=0;



	}



	echo number_format($crefbonus5,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>5){echo" <tr> 



          <td>Tier 6</td>



          <td > \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer6='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus6=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus6=$cefbonus6+($total/100*$ref6bonus);



	$total=0;



	}



	echo number_format($crefbonus6,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>6){echo" <tr> 



          <td>Tier 7</td>



          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer7='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus7=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus7=$crefbonus7+($total/100*$ref7bonus);



	$total=0;



	}



	echo number_format($crefbonus7,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>7){echo" <tr> 



          <td >Tier 8 </td>



          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer8='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus8=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus8=$crefbonus8+($total/100*$ref8bonus);



	$total=0;



	}



	echo number_format($crefbonus8,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>8){echo" <tr> 



          <td>Tier 9</td>



          <td> \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer9='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus9=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus9=$crefbonus9+($total/100*$ref9bonus);



	$total=0;



	}



	echo number_format($crefbonus9,7,".",",")."







          </td>



        </tr>";}



		?>
        <?



       if($levels>9){echo" <tr> 



          <td >Tier 10</td>



          <td > \$ ";



	$sq=mysql_query("SELECT * FROM users WHERE frefer10='$uid'");



	$rows=mysql_num_rows($sq);



	$crefbonus10=0;



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sq,$i);



	$arr=mysql_fetch_array($sq); extract($arr);



	$total=$ftmclicks+$ftmreads+$ftmregs+$ftotalregs+$ftotalsurveys;



	$crefbonus10=$crefbonus10+($total/100*$ref10bonus);



	$total=0;



	}



	echo number_format($crefbonus10,7,".",",")."







          </td>



        </tr>";}



		?>
      </tbody>
    </table></td></tr>
      </table>

      <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td><h3>Total cash referral bonuses:</h3></td>

          <td><?php echo $setupinfo['currency']; ?><?



			if($levels==0)$totalref=0;



			else if($levels==1)$totalref=$crefbonus1;



			else if($levels==2)$totalref=$crefbonus1+$crefbonus2;



			else if($levels==3)$totalref=$crefbonus1+$cefbonus2+$crefbonus3;



			else if($levels==4)$totalref=$crefbonus1+$crefbonus2+$crefbonus3+$crefbonus4;



			else if($levels==5)$totalref=$crefbonus1+$crefbonus2+$crefbonus3+$crefbonus4+$crefbonus5;



			else if($levels==6)$totalref=$crefbonus1+$crefbonus2+$crefbonus3+$crefbonus4+$crefbonus5+$crefbonus6;



			else if($levels==7)$totalref=$crefbonus1+$crefbonus2+$crefbonus3+$crefbonus4+$crefbonus5+$crefbonus6+$crefbonus7;



			else if($levels==8)$totalref=$crefbonus1+$crefbonus2+$crefbonus3+$crefbonus4+$crefbonus5+$crefbonus6+$crefbonus7+$crefbonus8;



			else if($levels==9)$totalref=$crefbonus1+$crefbonus2+$crefbonus3+$crefbonus4+$crefbonus5+$crefbonus6+$crefbonus7+$crefbonus8+$crefbonus9;



			else if($levels==10)$totalref=$crefbonus1+$crefbonus2+$crefbonus3+$crefbonus4+$crefbonus5+$crefbonus6+$crefbonus7+$crefbonus8+$crefbonus9+$crefbonus10;



		



	echo number_format($totalref,7,".",",");



	?>

          </td>

        </tr>

        <tr>

          <td><h3>Total points referral bonuses:</h3></td>

          <td>

            <?



			if($levels==0)$totalref2=0;



			else if($levels==1)$totalref2=$refbonus1;



			else if($levels==2)$totalref2=$refbonus1+$refbonus2;



			else if($levels==3)$totalref2=$refbonus1+$refbonus2+$refbonus3;



			else if($levels==4)$totalref2=$refbonus1+$refbonus2+$refbonus3+$refbonus4;



			else if($levels==5)$totalref2=$refbonus1+$refbonus2+$refbonus3+$refbonus4+$refbonus5;



			else if($levels==6)$totalref2=$refbonus1+$refbonus2+$refbonus3+$refbonus4+$refbonus5+$refbonus6;



			else if($levels==7)$totalref2=$refbonus1+$refbonus2+$refbonus3+$refbonus4+$refbonus5+$refbonus6+$refbonus7;



			else if($levels==8)$totalref2=$refbonus1+$refbonus2+$refbonus3+$refbonus4+$refbonus5+$refbonus6+$refbonus7+$refbonus8;



			else if($levels==9)$totalref2=$refbonus1+$refbonus2+$refbonus3+$refbonus4+$refbonus5+$refbonus6+$refbonus7+$refbonus8+$refbonus9;



			else if($levels==10)$totalref2=$refbonus1+$refbonus2+$refbonus3+$refbonus4+$refbonus5+$refbonus6+$refbonus7+$refbonus8+$refbonus9+$refbonus10;



		



	echo $totalref2;



	?>

          points </td>

        </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="2">

      <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
<thead>
        <tr>

          <?php



		$sq=mysql_query("SELECT * FROM users WHERE username=".quote_smart($uid)."");

$count = mysql_num_rows($sq);

if($count ==  0) {

	echo "The username you entered (".$uid.") could not be found! Please try again with a new username.<BR>";

}

	extract(mysql_fetch_array($sq));



		?>

          <td colspan="2">Personal points earnings</td>

        </tr>
</thead><tbody>
        <tr>

          <td>Points Email Clickthrus:<br>

          </td>

          <td> <?php echo "$ftotalreads";?> </td>

        </tr>

        <tr>

          <td><h3>Points clicks:</h3></td>

          <td><?php echo "$ftotalclicks";?></td>

        </tr>

        <tr>

          <td><h3>Points signups:</h3></td>

          <td> <?php echo "$ftotalregs";?> </td>

        </tr>

        <tr>

          <td><h3>Total Personal <?php echo $setupinfo['pointsName']; ?></h3></td>

          <td>

            <?php $totalpoints=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad; echo "$totalpoints ".$setupinfo['pointsName']; ?>

          </td>

        </tr>

        <tr>

          <td><h3>Total <?php echo $setupinfo['pointsName']; ?></h3></td>

          <td>

            <?php $totalpoints=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad+$totalref2; echo "$totalpoints ".$setupinfo['pointsName']; ?>

          </td>

        </tr>

        <tr>

          <td><h3>Link Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'links' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'links' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

        <tr>

          <td><h3>Banner Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'banner' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'banner' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

        <tr>

          <td><h3>Featured Banner Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'fbanner' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'fbanner' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

        <tr>

          <td><h3>Featured Ad Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'fad' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'fad' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

        <tr>

          <td><h3>Featured Ad Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'flinks' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'flinks' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

        <tr>

          <td><h3>Paid Sign-up Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'signup' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'signup' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

        <tr>

          <td><h3>Paid E-Mail Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'email' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'email' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

		<tr>

          <td><h3>Paid Survey Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'survey' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'survey' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

		<tr>

          <td><h3>Paid to Read Ad Credits</h3></td>

          <td>

            <?php echo getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'ptrad' AND username = ".quote_smart($username)."") - getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'ptrad' AND username = ".quote_smart($username)."");?>

          </td>

        </tr>

        <tr>

          <td><h3>Referring Website</h3></td>

          <td>

            <?php echo $referringWebsite;?>

          </td>

        </tr>

        <tr>

          <td colspan="2">&nbsp;</td>

        </tr>

    </table></td>

  </tr>

  <tr>

    <td><h4>Account balance after all transactions:</h3></td>

    <td> <?php echo $setupinfo['currency']; ?><?php



	//$totaltotal=$total1 + $totalref - $totamount;



	echo number_format(totalEarnings($username),7);



	?>

    </td>

  </tr>

</table>



<br>
<form name="changeprofile" method="post" action="/admin/index.php">



  <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">

    <tr valign="top"> 



      <td><table class="fullwidth" cellspacing="0" cellpadding="0">

			<tr> 

			<td>


          <input type=hidden name=required_keywords value=3>



          <input type=hidden name=user_form value=signup>



          <input type=hidden name=userform[code] value=48425a7f>



          <input type=hidden name=required value='username,email,first_name,last_name,address,city,state,zipcode,country,password'><h3>Change user profile</h3>

			  <table width="100%"  border="0" cellspacing="1" cellpadding="5">

                <tr bgcolor="#FFFFFF">

                  <td>E-Mail:</td>

                  <td>

                    <input type="text" name="email" value="<?php echo "$femail"?>">

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>First Name:</td>

                  <td>

                    <input type="text" name="name1" value="<?php echo "$fname1"?>">

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>Last Name:</td>

                  <td>

                    <input type="text" name="name2" value="<?php echo "$fname2"?>">

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>Address:</td>

                  <td>

                    <input type="text" name="address" value="<?php echo "$faddress"?>">

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>City:</td>

                  <td>

                    <input type="text" name="city" value="<?php echo "$fcity"?>">

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>State:</td>

                  <td>

                    <input type="text" name="state" value="<?php echo "$fstate"?>">

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>Zip Code:</td>

                  <td>

                    <input type="text" name="zip" value="<?php echo "$fzip"?>">

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td height="33">Country:</td>

                  <td height="33">

                    <select name="country">

                      <?



	$sql=mysql_query("SELECT * FROM countries ORDER BY country") or die(mysql_error());



	$rows=mysql_num_rows($sql);



	for($i=0;$i<$rows;$i++)



	{



	mysql_data_seek($sql,$i);



	$arr=mysql_fetch_array($sql);



	extract($arr);



	echo"<option value='$country' ";  if($country==$fcountry)echo'selected="selected"'; echo">$country</option>";}



			  ?>

                    </select>

                  </td>

                </tr>
                
                <tr bgcolor="#FFFFFF">

                  <td>IP Address:</td>

                  <td>

                    <input type="text" name="userip" value="<?php echo "$userip"?>">

                  </td>

                </tr>
<tr><td>Receive paid emails</td><td><input type="radio" name="paidEmails" value="0" <?php if($paidEmails == '0') { echo "checked"; } ?>>
					No 
					    <input type="radio" name="paidEmails" value="1" <?php if($paidEmails == '1') { echo "checked"; } ?>>
					    Yes</td>
					</tr>
                <tr bgcolor="#FFFFFF">

                  <td>Gender:</td>

                  <td>

                    <select name="gender">

                      <option <?if($fgender=='Male')echo"selected"?>>Male</option>

                      <option <?if($fgender=='Female')echo"selected"?>>Female</option>

                    </select>

                  </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>Age:</td>

                  <td>

                    <input type="text" name="age" size="2" value="<?php echo  $fage?>">

    years old</td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td>Anual incoming:</td>

                  <td>

                    <?php echo $setupinfo['currency']; ?><input type="text" name="incoming" size="5" value="<?php echo  $fincoming?>">

     </td>

                </tr>

                <tr bgcolor="#FFFFFF">

                  <td height="126" valign="top">Language preference</td>

                  <td height="126">

                    <table border="0" cellspacing="5" cellpadding="5" width="100%">

                      <tr valign="top">

                        <td width="54%"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">

                          <input type=checkbox name=nlang1 value="1" <?if($lang1==1)echo"checked"?>>

          English<br>

          <input type=checkbox name=nlang2 value="1"  <?if($lang2==1)echo"checked"?>>

          German<br>

          <input type=checkbox name=nlang3 value="1" <?if($lang3==1)echo"checked"?>>

          France<br>

          <input type=checkbox name=nlang4 value="1" <?if($lang4==1)echo"checked"?>>

          Italian<br>

          <input type=checkbox name=nlang5 value="1" <?if($lang5==1)echo"checked"?>>

          Chinese<br>

          <br>

                        </font></td>

                        <td width="46%"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">

                          <input type=checkbox name=nlang6 value=1 <?if($lang6==1)echo"checked"?>>

          Poland<br>

          <input type=checkbox name=nlang7 value=1 <?if($lang7==1)echo"checked"?>>

          Romanian<br>

          <input type=checkbox name=nlang8 value=1 <?if($lang8==1)echo"checked"?>>

          Russian<br>

          <input type=checkbox name=nlang9 value="1" <?if($lang9==1)echo"checked"?>>

          Greece<br>

          <input type=checkbox name=nlang10 value="1" <?if($lang10==1)echo"checked"?>>

          Other</font></td>

                      </tr>

                  </table></td>

                </tr>

              </table></td>

		</tr>



          <tr valign="top" bgcolor="#FFFFFF"> 



            <td align=center> 



              <table border="0" cellspacing="1" cellpadding="5" width="100%">



                <tr valign="top"> 



                  <td colspan="3"> 



                    <h3>Change interests</h3>



                  </td>



                </tr>



                <tr valign="top"> 



                  <td> 



                    <input type=checkbox name=int1 value=1 <?if($fintarts=='1') echo"checked"?>>



                    Arts<br>



                    <input type=checkbox name=int2 value=1 <?if($fintauto=='1') echo"checked"?>>



                    Automotive<br>



                    <input type=checkbox name=int3 value=1 <?if($fintbusiness=='1') echo"checked"?>>



                    Business<br>



                    <input type=checkbox name=int4 value=1 <?if($fintcomputers=='1') echo"checked"?>>



                    Computers<br>



                    <input type=checkbox name=int5 value=1 <?if($finteducation=='1') echo"checked"?>>



                    Education<br>



                    <input type=checkbox name=int6 value=1 <?if($fintentertainment=='1') echo"checked"?>>



                    Entertainment<br>



                    <input type=checkbox name=int7 value=1 <?if($fintfinancial=='1') echo"checked"?>>



                    Financial</td>



                  <td> 



                    <input type=checkbox name=int8 value=1 <?if($fintgames=='1') echo"checked"?>>



                    Games<br>



                    <input type=checkbox name=int9 value=1 <?if($finthealth=='1') echo"checked"?>>



                    Health<br>



                    <input type=checkbox name=int10 value=1 <?if($finthome=='1') echo"checked"?>>



                    Home<br>



                    <input type=checkbox name=int11 value=1 <?if($fintinternet=='1') echo"checked"?>>



                    Internet<br>



                    <input type=checkbox name=int12 value=1 <?if($fintnews=='1') echo"checked"?>>



                    News<br>



                    <input type=checkbox name=int13 value=1 <?if($fintmedia=='1') echo"checked"?>>



                    Media<br>



                    <input type=checkbox name=int14 value=1 <?if($fintrecreation=='1') echo"checked"?>>



                    Recreation </td>



                  <td> 



                    <input type=checkbox name=int15 value=1 <?if($fintreference=='1') echo"checked"?>>



                    Reference<br>



                    <input type=checkbox name=int16 value=1 <?if($fintsearch=='1') echo"checked"?>>



                    Search<br>



                    <input type=checkbox name=int17 value=1 <?if($finttechnology=='1') echo"checked"?>>



                    Technology<br>



                    <input type=checkbox name=int18 value=1 <?if($fintsocial=='1') echo"checked"?>>



                    Social <br>



                    <input type=checkbox name=int19 value=1 <?if($fintsports=='1') echo"checked"?>>



                    Sports<br>



                    <input type=checkbox name=int20 value=1 <?if($finttravel=='1') echo"checked"?>>



                    Travel</td>



                </tr>

            </table>            </td>



          </tr>



          <tr valign="top" bgcolor="#FFFFFF"> 



            <td align=center> 



              <table width="100%" border="0" cellpadding="5" cellspacing="1">



                <tr> 



                  <td> 



                    <h3>Change Payout information:</h3>



                  </td>



                </tr>



                <tr> 



                  <td> Payment method: 



                    <select name=paymethod>



                      <option value="" selected>Select a payment method </option>



                      <?



	$sql=mysql_query("SELECT * FROM payoptions") or die(mysql_error());



	$arr=mysql_fetch_array($sql);



	extract($arr);



	if($memberegold=='yes') echo"<option"; if($fpaymethod=='E-gold')echo" selected";echo">E-gold</option>";



	if($membernetpay=='yes') echo"<option"; if($fpaymethod=='Netpay')echo" selected";echo">Netpay</option>";



	if($memberstormpay=='yes') echo"<option"; if($fpaymethod=='Stormpay')echo" selected";echo">Stormpay</option>";



	if($membermoneybookers=='yes') echo"<option"; if($fpaymethod=='Moneybookers')echo" selected";echo">Moneybookers</option>";



	if($memberpaypal=='yes')echo"<option"; if($fpaymethod=='Paypal')echo" selected";echo">Paypal</option>";



	if($memberpayza=='yes')echo"<option"; if($fpaymethod=='Payza')echo" selected";echo">Payza</option>";



				?>



                    </select>



                  </td>



                </tr>



                <tr> 



                  <td>Payment account ID: 



                    <input type=text size=25 name=payacc value=<?php echo "$fpayacc"?>>



                  </td>



                </tr>



                <tr> 



                  <td>&nbsp;</td>



                </tr>

              </table>

</tr><tr bgcolor="#FFFFFF"><td>

              <table width="100%" border="0">



                <tr> 



                  <td colspan="2"><h3>Change Password</h3>
Do not enter anything to leave the current password intact, enter a new password in both fields to update to a new password.</td>



                </tr>



                <tr> 



                  <td>Password:</td>



                  <td> 



                    <input type=text name=password value="">



                  </td>



                </tr>



                <tr> 



                  <td>Confirm Password:</td>



                  <td> 



                    <input type=text name=confirmpassword>



                  </td>



                </tr>



              </table>



              <div align="center"><br>

  

                <input type="submit" value="         Save Profile Information        " name="submit">

  

                <input type="hidden" name="act" value="change">  
                <input type="hidden" name="tp" value="userview">  
                <input type="hidden" name="username" value="<?php echo $username; ?>">   
                <input type="hidden" name="uid" value="<?php echo $username; ?>">            

              </div></td>



          </tr>

      </table>      </td></tr>
</tbody>
  </table>



</form>


<h3>DEBIT HISTORY</h3>
            <table border="0" cellspacing="0" class="fullwidth">
              <thead><tr>
                <td><b>Date</b></td>
                <td><b>Debit For</b></td>
                <td><b>Amount</b></td>
              </tr></thead><tbody>
              <?php
		$debitForList = "
		'account',
		'signupBonus',
		'ptc',
		'ptcRefBonus',
		'ptreadads',
		'ptreadadsRefBonus',
		'ptremail',
		'ptremailRefBonus',
		'ptsurvey',
		'ptsurveyRefBonus',
		'ptsignup',
		'ptsignupRefBonus'";
		@$sq=mysql_query("SELECT * FROM debit WHERE fid=".quote_smart($username)." AND `type` = 'usd' AND `debitFor` IN (".$debitForList.") ORDER BY fdate DESC LIMIT 50");
		@$rows=mysql_num_rows($sq);
		$totalRows = getValue("SELECT COUNT(fid) FROM debit WHERE fid=".quote_smart($username)." AND `type` = 'usd' AND `debitFor` IN (".$debitForList.")");
		$totamount=0;
		for($i=0;$i<$rows;$i++)
		{
			mysql_data_seek($sq,$i);
			$ar=mysql_fetch_array($sq); extract($ar);
			if($debitFor == 'signupBonus') $debitFor = 'Signup Bonus';
			else if($debitFor == 'ptc') $debitFor = 'Paid to Click';
			else if($debitFor == 'ptcRefBonus') $debitFor = 'Referral Paid to Click Earnings';
			else if($debitFor == 'ptreadads') $debitFor = 'Paid to Read Ads';
			else if($debitFor == 'ptreadadsRefBonus') $debitFor = 'Referral Paid to Read Ads Earnings';
			else if($debitFor == 'ptremail') $debitFor = 'Paid to Read Emails';
			else if($debitFor == 'ptremailRefBonus') $debitFor = 'Referral Paid to Read Emails Earnings';
			else if($debitFor == 'ptsurvey') $debitFor = 'Paid to Take Survey\'s';
			else if($debitFor == 'ptsurveyRefBonus') $debitFor = 'Referral Paid to Take Survey\'s Earnings';
			else if($debitFor == 'ptsignup') $debitFor = 'Paid to Sign Up';
			else if($debitFor == 'ptsignupRefBonus') $debitFor = 'Referral Paid to Sign Up Earnings';
			else $debitFor = 'Account Transaction';
			if(number_format($famount,7) < 0) {
				$amount = "<FONT COLOR=GREEN><STRONG>+</STRONG></FONT>";
			} else {
				$amount = "<FONT COLOR=RED><STRONG>-</STRONG></FONT>";
			}
			echo"<tr><td>$fdate </td><td> ".$debitFor." </td><td> ".$amount.$setupinfo['currency'].number_format(abs($famount),7)."</td></tr>";
			
			$totamount=$totamount+$famount;
		}
		?>
          </tbody></table>There are currently <a href="#"><?php echo $totalRows; ?></a> transactions for your account.

<div align="center"><strong>Order History for <?php echo $username; ?></strong><br>

</div>

<table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
<thead>
  <tr>

	<td>Invoice</td>

		<td>Date</td>

	<td>Username</td>

		<td>Name</td>

		<td>Amount</td>

		<td>Status</td>

		<td>Package</td>

  </tr>
</thead><tbody>
<?php

	$query = mysql_query("SELECT * FROM orders WHERE username = ".quote_smart($username)." ORDER BY id DESC LIMIT 0, 500");

	$count = mysql_num_rows($query);

	if($count > 0) {

	  for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query, $i);

		$arr = mysql_fetch_array($query);

	  ?><tr>

	  <td><a href="index.php?tp=orderHistory&act=viewInvoice&invoice=<?php echo $arr['id']; ?>"><?php echo $arr['id']; ?></a></td>

		<td><?php echo $arr['orderDate']; ?></td>

	  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>

		<td><?php echo $arr['firstname']; ?> <?php echo $arr['lastname']; ?></td>

		<td>$<?php echo $arr['orderTotal']; ?></td>

		<td><?php if($arr['orderPaid'] == '1') { echo "Completed"; } else { echo "Pending"; } ?></td>

		<td><?php echo $arr['orderFor']; ?></td>

		</tr>

	  <?php

	  }

	} else {

		echo "<tr><td colspan=\"6\">There are currently no orders for this member.</td></tr>";

	}

	?>
</tbody>
</table>

<br>

<br>



<div align="center"><strong>Survey History for <?php echo $username; ?></strong><br>

</div>

<table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
<thead>
  <tr>

	<td width="47">Date of Survey</td>

		<td width="156">Survey Name</td>

	<td width="59">Owner of Survey</td>

		<td width="156">Read Survey Results</td>

  </tr>
</thead><tbody>
<?php

	$query = mysql_query("SELECT * FROM surveyactivity WHERE username = ".quote_smart($username)." ORDER BY id DESC LIMIT 0, 500");

	$count = mysql_num_rows($query);

	if($count > 0) {

	  for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query, $i);

		$arr = mysql_fetch_array($query);

		$details = getArray("SELECT * FROM surveys WHERE id = ".quote_smart($arr['surveyID'])."");

	  ?><tr>

	  <td><?php echo $arr['dateTaken']; ?></td>

		<td><?php echo $details['surveyname']; ?></td>

	  <td><?php echo $details['username']; ?></td>

		<td><a href="index.php?tp=viewSurveyResults&surveyID=<?php echo $arr['surveyID']; ?>&aid=<?php echo $arr['id']; ?>">View Survey</a></td>

		</tr>

	  <?php

	  }

	} else {

		echo "<tr><td colspan=\"6\">There are currently no orders for this member.</td></tr>";

	}

	?>
</tbody>
</table>

<br>

<br>

<br>



<div align="center"><strong>Recent Click History for <?php echo $username; ?></strong><br>

</div>

<table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
<thead>
  <tr>

	<td>Date of click</td>

		<td>Site Name</td>

	<td>Earned</td>

  </tr>
</thead><tbody>
<?php

	$query = mysql_query("SELECT * FROM taskactivity WHERE username = ".quote_smart($username)." ORDER BY fnum DESC LIMIT 0, 500");

	$count = mysql_num_rows($query);

	if($count > 0) {

		$fpaytype = $ptc_pay_type;

		$fprise = $ptc_pay_amount;

		if($fpaytype == 'usd') { $amount = '$'.number_format($fprise,7); } else { $amount = $fprise.' Points'; }

	  for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query, $i);

		$arr = mysql_fetch_array($query);

		$details = getArray("SELECT * FROM tasks WHERE fn = ".quote_smart($arr['task'])."");

	  ?><tr>

	  <td><?php echo $arr['fdate'].' '.$arr['ftime']; ?></td>

		<td><?php echo $details['fsitename']; ?></td>

	  <td><?php echo $amount; ?></td>

		</tr>

	  <?php

	  }

	} else {

		echo "<tr><td colspan=\"6\">There are currently no orders for this member.</td></tr>";

	}

	?>

</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="index.php" style="margin: 0 0 0 0;">



  <table class="fullwidth" border="0" cellpadding="0" cellspacing="0">

<thead>

    <tr> 

      <td colspan="3">Remove cash from member account</td>
    </tr>



    <tr> 



      <td>&nbsp;</td>



      <td>Debit amount</td>



      <td> 



        <input type="hidden" name="act" value="debit">
        <input type="hidden" name="tp" value="userview">
        <input type="hidden" name="uid" value="<?php echo $username; ?>" /></td>
    </tr>
</thead>


    <tr> 



      <td>&nbsp;</td>



      <td> 



        <input type="text" name="amount">      </td>



      <td> 



        <input type="submit" name="Submit" value="Subtract Funds / Debit this account">      </td>
    </tr>

</tbody>
  </table>



  <p>&nbsp;</p>
</form>



<form name="form1" method="post" action="index.php" style="margin: 0 0 0 0;">
  <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">


<thead>
    <tr> 

      <td colspan="4">Add <?php echo $setupinfo['currencyName']; ?> to member account</font></b></td>
    </tr>



    <tr> 



      <td>&nbsp;</td>



      <td><b>Add amount</b></td>



      <td> 
        <input type="hidden" name="act" value="Add">
        <input type="hidden" name="tp" value="userview">
        <input type="hidden" name="uid" value="<?php echo $username; ?>" /></td>
    </tr>
</thead><tbody>

    <tr> 



      <td>&nbsp;</td>



      <td> 



        <input type="text" name="amount">      </td>



      <td> 



        <input type="submit" name="Submit" value="Increase Funds / Add to account">      </td>
    </tr>


</tbody>
  </table>



  <p>&nbsp;</p>
</form>
<!-- 2 -->