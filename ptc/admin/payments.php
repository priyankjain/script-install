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

if($action == 'pay') {
	$paymentID = $_REQUEST['num'];
	$payment = getArray("SELECT * FROM payrequest WHERE `fnum` = ".quote_smart($paymentID)."");
	$username = $payment['username'];
	$payAmount = $payment['famount'];
	$payoutMethod = $payment['payout_method'];
	$payoutAccount = $payment['payout_account'];
	$balance = totalEarnings($username);
	?><div style="width: 600px; margin: 15px 15px 15px 15px; padding: 15px 15px 15px 15px; border-style: solid; border-color: #F60; border-width: thin; background-color:#FFC;"><?php
	echo 'Users current balance : '.$setupinfo['currency'].''.$balance.'<BR>';
	echo 'User wishes to be paid : '.$setupinfo['currency'].''.$payAmount.'<BR>';
	echo 'User payment method : '.$payoutMethod.'<BR>';
	echo 'User payment account : '.$payoutAccount.'<BR>';
	echo '<BR><BR>';
	if($payoutMethod == 'paypal') {
		?><img src="../common/images/merchants/paypalMedium.png" border="0" /><BR />Paypal is the preferred payment method.<BR /><BR /><a href="#">Click Here to be forwarded to Paypal to complete this payment.</a><?php
	} else if($payoutMethod == 'payza') {
		?><img src="../common/images/merchants/payzaMedium.png" border="0" /><BR />Payza is the preferred payment method.<BR /><BR /><a href="#">Click Here to be forwarded to Payza to complete this payment.</a><?php
	} else if($payoutMethod == 'moneybookers') {
		?><img src="../common/images/merchants/skrillMedium.png" border="0" /><BR />Skrill / Moneybookers is the preferred payment method.<BR /><BR /><a href="#">Click Here to be forwarded to Skrill to complete this payment.</a><?php
	} else if($payoutMethod == 'egold') {
		?><img src="../common/images/merchants/egoldMedium.png" border="0" /><BR />E-Gold is the preferred payment method.<BR /><BR /><a href="#">Click Here to be forwarded to E-Gold to complete this payment.</a><?php
	}
	
	?></div><?php
}

if($action=='delete') {

	$num = $_REQUEST['num'];

	$sql = mysql_query("SELECT * FROM `payrequest` WHERE `fnum` = ".quote_smart($num)." LIMIT 1");

	$count = mysql_num_rows($sql);

	if($count == 0) {

		displayError("Error: This pay request could not be found.");

	} else {

		$arr = mysql_fetch_array($sql);

		$amount = $arr['famount'];

		$username = $arr['username'];

		$user = getArray("SELECT * FROM users WHERE username = ".quote_smart($username)."");

		//$sql = mysql_query("INSERT INTO debit (fid, famount, fdate, fnum) VALUES(".quote_smart($username).", ".quote_smart($amount).", 'now()', '')");

		debitAccountBalance($username, 'debit', $amount, 'usd','payout');

		if(mysql_affected_rows()) { echo"<br><br><b>DEBIT account ID $username to amount USD $amount has been processed!</b><br><br>";

			$sql = mysql_query("select name, comments, value, subject from design where name ='emailCredit'");

			$array = mysql_fetch_array($sql);

			$from="From: $adminemail";

			$arr = getArray("SELECT * FROM users WHERE username = ".quote_smart($username)."");
			if($setupinfo['enableMd5Passwords'] == '1') $arr['fpassword'] = '*encrypted*';
			$message=$array['value']."\n\n\n Your username is: ".$arr['username']." \n Your password is: ".$user['fpassword']." \n Use this link to enter in your account: \n $ptrurl?tp=member";

			$subject = $ptrname.": ".$array['subject'];

			@mail($arr['femail'],$subject,$message,$from);

		}

		//mysql_query("DELETE FROM `payrequest` WHERE `fnum` = ".quote_smart($num)." LIMIT 1");

		mysql_query("UPDATE `payrequest` SET paidOut = '1' WHERE `fnum` = ".quote_smart($num)." LIMIT 1");

		displaySuccess("SUCCESS - This users account has been updated.\n\nUsername: ".$arr['username']."\nAmount debited: ".$amount."\n\nPlease make sure you properly paid this member via their requested method, this is not automated and you must actually pay them manually.");

		}

}

if($action=='deleteOnly') {

	$num = $_REQUEST['num'];

	$sql = mysql_query("SELECT * FROM `payrequest` WHERE `fnum` = ".quote_smart($num)." LIMIT 1");

	$count = mysql_num_rows($sql);

	if($count == 0) {

		displayError("Error: This pay request could not be found.");

	} else {

		//mysql_query("DELETE FROM `payrequest` WHERE `fnum` = ".quote_smart($num)." LIMIT 1");

		mysql_query("DELETE FROM `payrequest` WHERE `fnum` = ".quote_smart($num)." LIMIT 1");

		displaySuccess("This payout request has been denied and removed.");

		}

}



if($action=='debit') {

	$username = $id;

	$sql = mysql_query("INSERT INTO debit (fid, famount, fdate, fnum) VALUES(".quote_smart($username).", ".quote_smart($amount).", 'now()', '')");

	if(mysql_affected_rows()) { echo"<br><br><b>DEBIT account ID $username to amount USD $amount has been processed!</b><br><br>";

		$sql = mysql_query("select name, comments, value, subject from design where name ='emailDebit'");

		$array = mysql_fetch_array($sql);

		$from="From: $adminemail";

		$arr = getArray("SELECT * FROM users WHERE username = ".quote_smart($username)."");
		if($setupinfo['enableMd5Passwords'] == '1') $arr['fpassword'] = '*encrypted*';
		$message=$array['value']."\n\n\n Your username is: ".$arr['username']." \n Your password is: ".$arr['fpassword']." \n Use this link to enter in your account: \n $ptrurl?tp=member";

		$subject = $ptrname.": ".$array['subject'];

		@mail($arr['femail'],$subject,$message,$from);

	}

}

if($action=='Add') {

	$username = $id;

	$sql = mysql_query("INSERT INTO debit (fid, famount, fdate, fnum) VALUES(".quote_smart($username).", ".quote_smart("-".$amount).", 'now()', '')");

	if(mysql_affected_rows()) { echo"<br><br><b>DEBIT account ID $username to amount USD $amount has been processed!</b><br><br>";

		$sql = mysql_query("select name, comments, value, subject from design where name ='emailCredit'");

		$array = mysql_fetch_array($sql);

		$from="From: $adminemail";

		$arr = getArray("SELECT * FROM users WHERE username = ".quote_smart($username)."");
		if($setupinfo['enableMd5Passwords'] == '1') $arr['fpassword'] = '*encrypted*';
		$message=$array['value']."\n\n\n Your username is: ".$arr['username']." \n Your password is: ".$arr['fpassword']." \n Use this link to enter in your account: \n $ptrurl?tp=member";

		$subject = $ptrname.": ".$array['subject'];

		@mail($arr['femail'],$subject,$message,$from);

	}

}



?> 
<h2>Payment / Withdraw requests</h2>
<table width="100%" border="0" cellspacing="0">



  <tr> 



    <td>

		<?php

		$sql=mysql_query("SELECT * FROM payrequest WHERE paidOut = '0'");



		$rows=mysql_num_rows($sql);

		if ($rows == '0') { ?>

		

		<div align="center"></div>

		<div align="center"><br>

		  <br>

		  <strong><br>

		  There are no payment requests at this time.</strong><br>

		  <br>

		  <br>

		

		

		  <?php } else {

		?>

        </div>

		<table class="fullwidth" border="0" cellspacing="0">
		<thead>
        <tr> 

          <td width="61">Username</td>

          <td width="29">Date</td>

          <td width="97">Request amount</td>

          <td width="104">Member Balance</td>

          <td width="94">Payout Method</td>

          <td width="289">Payout Account</td>

          <td width="51">Pay</td>

          <td width="54">Approve</td>
          <td width="50">Decline</td>
        </tr>
		</thead><tbody>
        <?php



		for($i=0;$i<$rows;$i++) {



			mysql_data_seek($sql,$i);

			$arr=mysql_fetch_array($sql); extract($arr);

			$accountnum = $fnum;

			$balancetotal = totalEarnings($username);

			?>

			<tr>

			<td valign="top"> <?php echo $username; ?></td>

			<td valign="top"> <?php echo $fdate; ?></td>

			<td valign="top"> <?php echo $setupinfo['currency'].$famount; ?></td>

			<td valign="top"> <?php echo $setupinfo['currency'].($balancetotal); ?><br />
			  (after withdraws)</td>

			<td valign="top"> <?php 
			
			if($payout_method == 'paypal') {
				echo '<img src="../common/images/merchants/paypal32.png" border="0">';
			} else if($payout_method == 'payza') {
				echo '<img src="../common/images/merchants/payza32.png" border="0">';
			} else if($payout_method == 'moneybookers') {
				echo '<img src="../common/images/merchants/skrill32.png" border="0">';
			} else if($payout_method == 'egold') {
				echo '<img src="../common/images/merchants/egold32.png" border="0">';
			}
			echo '<BR>'.$payout_method;
			
			
			?></td>

			<td valign="top"> <?php echo $payout_account; ?></td>

			<td valign="top"><a href="index.php?tp=payments&num=<?php echo "$accountnum&id=$username&amount=$famount&action=pay"; ?>" target="_blank"><img src="images/icons/btnPay.gif" width="50" height="15" border="0" /></a><br /></td>

		    <td valign="top"><a href="index.php?tp=payments&num=<?php echo "$accountnum&id=$username&amount=$famount&action=delete"; ?>"><img src="images/icons/btnApprove.png" width="50" height="15" border="0" /></a></td>
		    <td valign="top"><a href="index.php?tp=payments&num=<?php echo "$accountnum&id=$username&amount=$famount&action=deleteOnly"; ?>"><img src="images/icons/btnDecline.png" width="50" height="15" border="0" /></a></td>
		  </tr>

			<?php

	

		} //END LOOP THROUGH RESULTS



		?>
		</tbody>
      </table>

	<?php

		}

		?>    </td>



  </tr>



  <tr> 



    <td>Marking a payment as made will also remove that amount from the users account.</td>



  </tr>



  <tr> 



    <td>&nbsp;</td>



  </tr>



</table>



<br>



<form name="form1" method="post" action="index.php">



  <table class="fullwidth" border="0" cellpadding="0" cellspacing="0">

<thead>

    <tr> 

      <td colspan="3">Remove cash from member account</td>



    </tr>



    <tr> 



      <td>Account Username</td>



      <td>Debit amount</td>



      <td> 



        <input type="hidden" name="action" value="debit">



        <input type="hidden" name="tp" value="payments">



        <input type="hidden" name="num" value="<?php echo $fnum?>">



      </td>






    </tr>
</thead>


    <tr> 



      <td> 



        <input type="text" name="id" value="<?php echo  $username?>">



      </td>



      <td> 



        <input type="text" name="amount">



      </td>



      <td> 



        <input type="submit" name="Submit" value="Subtract Funds / Debit this account">



      </td>



    </tr>

</tbody>
  </table>



</form>



<form name="form1" method="post" action="index.php">



  <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">


<thead>
    <tr> 

      <td colspan="4">Add cash to member account</font></b></td>



    </tr>



    <tr> 



      <td><b>Account Username </b></td>



      <td><b>Add amount</b></td>



      <td> 



        <input type="hidden" name="action" value="Add">



        <input type="hidden" name="tp" value="payments">



        <input type="hidden" name="num" value="<?php echo $fnum?>">



      </td>


    </tr>

</thead><tbody>

    <tr> 



      <td> 



        <input type="text" name="id" value="<?php echo  $username?>">



      </td>



      <td> 



        <input type="text" name="amount">



      </td>



      <td> 



        <input type="submit" name="Submit" value="Increase Funds / Add to account">



      </td>



    </tr>


</tbody>
  </table>



</form>

