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

$conditions = '';

?><style type="text/css">

<!--

.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }

.style8 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }

-->

</style>

<?php

if($action == 'updateOrderNow') {

	$conditions = " id = ".quote_smart($_REQUEST['invoice']);

	$query = mysql_query("SELECT * FROM orders WHERE $conditions");

	$count = mysql_num_rows($query);

	if($count == 0) {

		displayError("Invalid invoice number entered.");

	} else {

		$arr = mysql_fetch_array($query);

		mysql_query("UPDATE orders SET orderPaid = ".quote_smart($_REQUEST['orderStatus'])." WHERE id = ".quote_smart($_REQUEST['invoice'])."");

		if(mysql_affected_rows()) {

			if($_REQUEST['orderStatus'] == '1') {

				$custom = $_REQUEST['invoice'];
$notifyLog .= "Payment status is either Completed or Pending.\n";
				$q1 = "update orders set orderPaid = '1' where id = ".quote_smart($custom)."  ";
				mysql_query($q1) or queryError($q1, __LINE__);
				
				$notifyLog .= $q1."\n";
				
		////////////////////////////////////////
				$query = mysql_query("SELECT packageID,packageType,username FROM orders WHERE id = ".quote_smart($custom)."");
				$arr = mysql_fetch_array($query);
				extract($arr);
				
				if(getCount("SELECT COUNT(id) FROM creditadditions WHERE orderID = ".quote_smart($order_id)."", "COUNT") == 0) {
					$package = $packageID;
					if($packageType == 'advertising') {
						$qr = mysql_query("SELECT fnum,pack_credits,pack_credits_type,packSpecial FROM packages WHERE fnum = ".quote_smart($arr['packageID'])."");
						$ar = mysql_fetch_array($qr);
						extract($ar);
						if(getCount("SELECT COUNT(id) FROM creditadditions WHERE orderID = ".quote_smart($custom)."", "COUNT") == 0) {
							if($packSpecial == 0) {
								if($pack_credits_type == 'referrals') {
									$packRefs = packReferrals($fnum);
									$sql = mysql_query("UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' LIMIT $packRefs");
									$receivedRefs = mysql_affected_rows();
									if($receivedRefs < $packRefs) {
										$headers = "Content-type: text/plain\nFrom: '$ptrname' <$adminemail>\r\n\r\n";
										$message = "Dear Admin,
		Your site has tried to process a referral assignment, but there where not enough orphans to fully complete the order.
		
		Order ID ".$custom."
		Username ".$username."
		Ordered ".$packRefs."
		Received ".$receivedRefs."
		Referrals not received: ".($receivedRefs - $packRefs)."
		
		This notice is to inform you that you will need to manually process the remaining referrals.
										";
										$warning = 'Your site has tried to process a referral assignment, but there where not enough orphans to fully complete the order.\n\nOrder ID '.$order.'\nUsername '.$username.'\nOrdered '.$packRefs.'\nReceived '.$receivedRefs.'\nReferrals not received: '.($receivedRefs - $packRefs).'\n';
										$mailer=mail($adminemail,$ptrname.' : Important Referral Assignment Notice',$message,$headers);
									}
								} else {
									$q = mysql_query("INSERT INTO creditadditions
									(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
									VALUES
									(".quote_smart($username).", ".quote_smart($pack_credits).", ".quote_smart($pack_credits_type).",NOW(),".quote_smart($custom).")
									");
								}
										/*$query = mysql_query("INSERT INTO creditadditions
								(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
								VALUES
								(".quote_smart($username).", ".quote_smart($pack_credits).", ".quote_smart($pack_credits_type).",NOW(),".quote_smart($custom).")
								");*/
								$notifyLog .= "Inserted new credits addition.\n";
							} else {
								$query = mysql_query("SELECT item FROM packitems WHERE package = ".quote_smart($fnum)."");
								$count = mysql_num_rows($query);
								if($count > 0) {
									for($i = 0;$i < $count;$i++) {
										mysql_data_seek($query, $i);
										$arr = mysql_fetch_array($query);
										$item = getArray("SELECT fnum,pack_credits, pack_credits_type FROM packages WHERE fnum = ".quote_smart($arr['item'])."");
										if($item['pack_credits_type'] == 'referrals') {
											$packRefs = packReferrals($item['fnum']);
											$sql = mysql_query("UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' LIMIT $packRefs");
											$receivedRefs = mysql_affected_rows();
											if($receivedRefs < $packRefs) {
												$headers = "Content-type: text/plain\nFrom: '$ptrname' <$adminemail>\r\n\r\n";
												$message = "Dear Admin,
		Your site has tried to process a referral assignment, but there where not enough orphans to fully complete the order.
		
		Order ID ".$custom."
		Username ".$username."
		Ordered ".$packRefs."
		Received ".$receivedRefs."
		Referrals not received: ".($receivedRefs - $packRefs)."
		
		This notice is to inform you that you will need to manually process the remaining referrals.
												";
												$warning = 'Your site has tried to process a referral assignment, but there where not enough orphans to fully complete the order.\n\nOrder ID '.$order.'\nUsername '.$username.'\nOrdered '.$packRefs.'\nReceived '.$receivedRefs.'\nReferrals not received: '.($receivedRefs - $packRefs).'\n';
												$mailer=mail($adminemail,$ptrname.' : Important Referral Assignment Notice',message,$headers);
											}
										} else {
											$q = mysql_query("INSERT INTO creditadditions
											(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
											VALUES
											(".quote_smart($username).", ".quote_smart($item['pack_credits']).", ".quote_smart($item['pack_credits_type']).",NOW(),".quote_smart($custom).")
											");
										}
										$notifyLog .= "Inserted new credits addition for item ".$arr['item'].".\n";
									}
								} else {
									$notifyLog .= "There were no items found for package ".$fnum." !!!!\n ERROR FILLING CREDIT REQUESTS!!! \n";
									$notifyLog = " ERROR FILLING CREDIT REQUESTS!!! \n\n".$notifyLog;
								}
							}
	
						} else {
							$notifyLog .= "Didn't insert credits addition.\n";
						}
					} else if($packageType == 'membership') {
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
						$sql = mysql_query("SELECT * FROM membershiptypes WHERE id = ".quote_smart($package)."");
						$count = mysql_num_rows($sql);
						if($count > 0) {
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
								NOW(),
								".quote_smart($lifetime)."
							)");
						}
					} //END IF packageType == 'membership'
				} //END CREDIT ADDITIONS CHECK
				
				displaySuccess("Awarded credits to this user for this order successfull.");

			}

			if($_REQUEST['orderStatus'] == '0') $status = 'Pending';

			if($_REQUEST['orderStatus'] == '1') $status = 'Completed';

			if($_REQUEST['orderStatus'] == '2') $status = 'Refunded';

			if($_REQUEST['orderStatus'] == '3') $status = 'Void';

			if($_REQUEST['orderStatus'] == '4') $status = 'Unpaid';

			displaySuccess("Updated invoice number ".$_REQUEST['invoice']." to ".$status."");

			$act = '';

			$action = '';

		}

	}

}
?>
<h2>Order History</h2>
<?php
if($action == 'updateOrder') {
	$conditions = " id = ".quote_smart($_REQUEST['invoice']);

	$query = mysql_query("SELECT * FROM orders WHERE $conditions");

	$count = mysql_num_rows($query);

	if($count == 0) {

		displayError("Invalid invoice number entered.");

	} else {

		$arr = mysql_fetch_array($query);
		if($_REQUEST['orderStatus'] == '1') $status = 'Approved';
		if($_REQUEST['orderStatus'] == '2') $status = 'Refunded';
		if($_REQUEST['orderStatus'] == '3') $status = 'Void';
		if($_REQUEST['orderStatus'] == '4') $status = 'Declined';
		?>
        <p>&nbsp;</p>
        <p><a href="index.php?tp=orderHistory&invoice=<?php echo $_REQUEST['invoice']; ?>&action=updateOrderNow&orderStatus=<?php echo $_REQUEST['orderStatus']; ?>">YES</a> - Update this order to <?php echo $status; ?>.
        
          <BR />
          <BR />
        
          <a href="index.php?tp=orderHistory&viewIncomplete=1">No</a> - I changed my mind.
          <br />
          <br />
        </p><?php
		
	}
}
if($action == 'updateStatus') {

	$conditions = " id = ".quote_smart($_REQUEST['invoice']);

	$query = mysql_query("SELECT * FROM orders WHERE $conditions");

	$count = mysql_num_rows($query);

	if($count == 0) {

		displayError("Invalid invoice number entered.");

	} else {

		$arr = mysql_fetch_array($query);

		if($arr['orderPaid'] == '1') {

			echo "Invoice Number: ".$arr['id']."<BR>Order Total: \$".number_format($arr['orderTotal'],2)."<BR><BR><BR>"; ?>

		  <a href="index.php?tp=orderHistory&invoice=<?php echo $invoice; ?>&action=updateOrderNow&orderStatus=2">Update this order to Refunded Status</a>.<BR>
	    
        <p><BR>

			<a href="index.php?tp=orderHistory&invoice=<?php echo $invoice; ?>&action=updateOrderNow&orderStatus=3">Update this order to Void Status</a>.<BR>
			<?php

		} else {

			echo "Invoice Number: ".$arr['id']."<BR>Order Total: \$".number_format($arr['orderTotal'],2)."<BR><BR><BR>"; ?>

			<a href="index.php?tp=orderHistory&invoice=<?php echo $invoice; ?>&action=updateOrderNow&orderStatus=2">Update this order to Refunded Status</a>.<BR>
			<BR>

			<a href="index.php?tp=orderHistory&invoice=<?php echo $invoice; ?>&action=updateOrderNow&orderStatus=3">Update this order to Void Status</a>.<BR>
			<BR>

			<a href="index.php?tp=orderHistory&invoice=<?php echo $invoice; ?>&action=updateOrderNow&orderStatus=4">Update this order to Payment Not Received Status</a>.<BR>
			<BR>

			<a href="index.php?tp=orderHistory&invoice=<?php echo $invoice; ?>&action=updateOrderNow&orderStatus=1">Update this order to Completed Status</a>. <strong>(NOTE: This will award the benefits of this order to this user, and send the order emails.) </strong><BR>

			<?php

		}

	}

}

if($act == 'viewInvoice') {

	$conditions = " id = ".quote_smart($_REQUEST['invoice']);

	$query = mysql_query("SELECT * FROM orders WHERE $conditions");

	$count = mysql_num_rows($query);

	if($count == 0) {

		displayError("Invalid invoice number entered.");

	} else {

		$arr = mysql_fetch_array($query);

		

?>

	            </p>
        <p>View Invoice <?php echo $_REQUEST['invoice']; ?></p>

	
				<div class="hastable_disabled"><table class="fullwidth" border="0" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE">

      <tr bgcolor="#FFFFFF">

        <td><strong>Invoice Number </strong></td>

        <td><?php echo $arr['id']; ?></td>

      </tr>

      <tr bgcolor="#FFFFFF">

        <td><strong>Date of Order </strong></td>

        <td><?php echo $arr['orderDate']; ?></td>

      </tr>

      <tr bgcolor="#FFFFFF">

        <td><strong>Username</strong></td>

        <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>

      </tr>

      <tr bgcolor="#FFFFFF">

        <td><strong>Name</strong></td>

        <td><?php echo $arr['firstname']." ".$arr['lastname']; ?></td>

      </tr>

      <tr bgcolor="#FFFFFF">

        <td><strong>Order Total </strong></td>

        <td><?php echo $setupinfo['currency']; ?><?php echo number_format($arr['orderTotal'],2); ?></td>

      </tr>

      <tr bgcolor="#FFFFFF">

        <td><strong>Order Status </strong></td>

        <td><?php if($arr['orderPaid'] == '1') { echo "Completed (<a href=\"index.php?tp=orderHistory&invoice=$invoice&action=updateStatus\">Update Order Status</a>)"; } else if($arr['orderPaid'] == '2') { echo "Refunded (<a href=\"index.php?tp=orderHistory&invoice=$invoice&action=updateStatus\">Update Order Status</a>)"; } else if($arr['orderPaid'] == '3') { echo "Void (<a href=\"index.php?tp=orderHistory&invoice=$invoice&action=updateStatus\">Update Order Status</a>)"; } else if($arr['orderPaid'] == '4') { echo "Unpaid (<a href=\"index.php?tp=orderHistory&invoice=$invoice&action=updateStatus\">Update Order Status</a>)"; } else { echo "Pending (<a href=\"index.php?tp=orderHistory&invoice=$invoice&action=updateStatus\">Update Order Status</a>)"; } ?></td>

      </tr>

      <tr bgcolor="#FFFFFF">

        <td><strong>Package Purchased</strong></td>

        <td><?php

		$sql=mysql_query("SELECT fnum, pack_price, pack_name, packSpecial FROM packages WHERE fnum = ".quote_smart($arr['packageID'])."");

							   $count = mysql_num_rows($sql);

							   if($count>0) {

									//PACK SPECIALS

									?>

									<?php

									$ar = mysql_fetch_array($sql);

									

									?>

									<?php echo $ar['pack_name']; ?> <?php echo $setupinfo['currency']; ?><?php echo number_format($ar['pack_price'],2); ?></td>

	  </tr><?php	

									if($ar['packSpecial'] == 1) {

										$query = mysql_query("SELECT b.pack_name, b.pack_price FROM packitems a, packages b WHERE a.item = b.fnum AND a.package = ".quote_smart($ar['fnum'])."");

										$c = mysql_num_rows($query);

										if($c > 0) {  ?>

									     <tr bgcolor="#FFFFFF"><td width="226" colspan="2">

											 <?php

											for($k=0; $k<$c; $k++){

												mysql_data_seek($query,$k);

												extract(mysql_fetch_array($query));

												if(isset($pack_name)) echo"$pack_name  (".$setupinfo['currency'].number_format($pack_price, 2)." Value) <BR>";

											}

											?>

											   

										 </td></tr>

										<?php

										} //if($c > 0) 

									}//END packSpecial == 1

									?>

										   <?php

										

								} else {//END IF SPECIALS COUNT > 0

									displayError("Package is either invalid, has been deleted or is currently un-available.");

								}

								?>

    </table>
    </div>

	<?php

	

	}

	?>

	<p><a href="index.php?tp=orderHistory">Back to order history </a></p>

	<BR>

      <?php

}

if($act == '' && $action == '') {

	?>

      <br>

      <br>      

      <form name="form1" method="post" action="index.php">

	    <div align="center">

          <input type="hidden" name="tp" value="orderHistory">

          <input type="hidden" name="act" value="viewInvoice">

          <input name="invoice" type="number" size="10" maxlength="15">&nbsp;&nbsp;

          <input type="submit" name="Submit" value="Search for Invoice #">

        </div>

      </form>

				<div class="hastable_disabled">
      <table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
<thead>
  <tr>

	<td width="63">Invoice</td>

	<td width="47">Date</td>

	<td width="71">Username</td>

	<td width="92">Name</td>

	<td width="73">Amount</td>

	<td width="60">Status</td>

	<td width="110">Package</td>

	<td width="170"><div align="right">Payment Information</div></td>
	<?php
    if($_REQUEST['viewIncomplete'] == '1') {
	?><td width="100"><div align="right">Approve</div></td>
	<td width="100"><div align="right">Void</div></td>
	<td width="100"><div align="right">Deny</div></td>
	<?php
	}
	?>
  </tr>
</thead><tbody>
	<?php

	if(isset($_REQUEST['viewIncomplete'])) {

		$conditions = " orderPaid = '0'";

	} else {

		$conditions = " orderPaid != '0'";

	}

	$query = mysql_query("SELECT * FROM orders WHERE $conditions ORDER BY id DESC LIMIT 0, 500");

	$count = mysql_num_rows($query);

	if($count > 0) {

	$total = 0;

	  for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query, $i);

		$arr = mysql_fetch_array($query);

	  ?><tr bgcolor="#FFFFFF">

	  <td bgcolor="#FFFFFF"><a href="index.php?tp=orderHistory&act=viewInvoice&invoice=<?php echo $arr['id']; ?>"><?php echo $arr['id']; ?> (Details)</a> </td>

	  <td bgcolor="#FFFFFF"><?php echo $arr['orderDate']; ?></td>

	  <td bgcolor="#FFFFFF"><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>

	  <td bgcolor="#FFFFFF"><?php echo $arr['firstname']; ?> <?php echo $arr['lastname']; ?></td>

	  <td bgcolor="#FFFFFF"><?php echo $setupinfo['currency']; ?><?php echo $arr['orderTotal']; $total += $arr['orderTotal']; ?></td>

	  <td bgcolor="#FFFFFF"><?php if($arr['orderPaid'] == '1') { echo "Completed"; } else if($arr['orderPaid'] == '2') { echo "Refunded"; } else if($arr['orderPaid'] == '3') { echo "Void"; } else if($arr['orderPaid'] == '4') { echo "Unpaid"; } else { echo "Pending"; } ?></td>

	  <td bgcolor="#FFFFFF"><?php echo getValue("SELECT pack_name FROM packages WHERE fnum = ".quote_Smart($arr['packageID']).""); ?></td>

	  <td><div align="right">

	    <?php 

		if($arr['paymentType'] == 'paypal') { echo "Paypal: ".$arr['paymentAccount']; }

		if($arr['paymentType'] == 'payza') { echo "Payza: ".$arr['paymentAccount']; }

		if($arr['paymentType'] == 'moneybookers') { echo "MoneyBookers: ".$arr['paymentAccount']; }

		if($arr['paymentType'] == 'account') { echo "Account Funds"; }

		if($arr['paymentType'] == 'netpay') { echo "Netpay: ".$arr['paymentAccount']; }

		if($arr['paymentType'] == 'stormpay') { echo "Stormpay: ".$arr['paymentAccount']; }

		if($arr['paymentType'] == 'egold') { echo "E-Gold: ".$arr['paymentAccount']; }

		 ?>

	    </div></td>


	 
		<?php
        if($_REQUEST['viewIncomplete'] == '1') {
        ?>
	    <td><div align="right"><a href="index.php?tp=orderHistory&invoice=<?php echo $arr['id']; ?>&action=updateOrder&orderStatus=1"><img src="images/icons/btnApprove.png" width="50" height="15" border="0" /></a></div></td>
        <td><div align="right"><a href="index.php?tp=orderHistory&invoice=<?php echo $arr['id']; ?>&action=updateOrder&orderStatus=3"><img src="images/icons/btnVoid.png" width="50" height="15" border="0" /></a></div></td>
        <td><div align="right"><a href="index.php?tp=orderHistory&invoice=<?php echo $arr['id']; ?>&action=updateOrder&orderStatus=4"><img src="images/icons/btnDecline.png" width="50" height="15" border="0" /></a></div></td>
		<?php
		}
		?>
	    

		</tr>

	  <?php

	  }

	  ?><tr bgcolor="#FFFFFF">

		<td colspan="8"><div align="right"><span class="style8">

		Total: <?php echo $setupinfo['currency']; ?><?php echo number_format($total,2); ?>

	    </span></div></td>

		</tr>

	  <?php

	} else {

		echo "<tr><td colspan=\"8\">There are currently no orders.</td></tr>";

	}

	?>
</tbody>
</table>
</div>

<p>&nbsp;</p>

	<?php if(!isset($_REQUEST['viewIncomplete'])) {

	?>

	<p><a href="index.php?tp=orderHistory&viewIncomplete=1">View un-paid invoices.</a> </p>

	<?php } else { ?>

	<p><a href="index.php?tp=orderHistory">View paid invoices.</a> </p>

	<?php } ?>

<?php }//END IF $act == ''

?>