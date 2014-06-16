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
//INCLUDED ONLY WHEN $td == 'ordernow' && user is Logged In
if($_REQUEST['packID'] != '') {
	$sql = mysql_query("SELECT * FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."");
	$package = $_REQUEST['packID'];
	$packageType = 'advertising';
} else if($_REQUEST['membershipID'] != '') {
	$sql = mysql_query("SELECT membershipName AS pack_name, membershipPrice AS pack_price, id AS fnum FROM membershiptypes WHERE id = ".quote_smart($_REQUEST['membershipID'])."");
	$package = $_REQUEST['membershipID'];
	$packageType = 'membership';
} else {
	exit("Invalid package selected. Cannot continue to payment.<BR>");
}
$count = mysql_num_rows($sql);
if($count > 0) {
	$arr = mysql_fetch_array($sql);
	extract($arr);
	
	if($_REQUEST['paymentType'] == 'account') {
		$orderPaid = '1'; //SET ORDER TO COMPLETED
	} else {
		$orderPaid = '0'; //MANUAL CONFIRMATION /  PENDING STATUS
	}
	
	$memberInfo = getArray("SELECT * FROM users WHERE username = ".quote_smart($_SESSION['login'])."");
	$q1 = "insert into orders 
	(`firstname`,
	`lastname`,
	`email`,
	`username`,
	`orderDate`,
	`orderTotal`,
	`orderPaid`,
	`paymentID`,
	`packageID`,
	`packageType`,
	`paymentType`,
	`paymentAccount`,
	`orderFor`) VALUES (
	".quote_smart($memberInfo['fname1']).",
	".quote_smart($memberInfo['fname2']).",
	".quote_smart($memberInfo['femail']).",
	".quote_smart($_SESSION['login']).",
	NOW(),
	".quote_smart($pack_price).",
	".quote_smart($orderPaid).",
	'',
	".quote_smart($package).",
	".quote_smart($packageType).",
	".quote_smart($_REQUEST['paymentType']).",
	".quote_smart($_REQUEST['paymentAccount']).",
	".quote_smart($pack_name)."
	)"; 
		
	mysql_query($q1) or die(mysql_error());
	$order_id = mysql_insert_id();
	$payment_option = 2;
	$q = mysql_query("SELECT ptrurl FROM setupinfo");
	$a = mysql_fetch_array($q);
	$site_url = $a['ptrurl'];
	
	if($_REQUEST['paymentType'] == 'account') {
		if(totalEarnings($_SESSION['login']) >= $pack_price) {
				$query = mysql_query("SELECT packageID,username FROM orders WHERE id = ".quote_smart($order_id)."");
				$arr = mysql_fetch_array($query);
				extract($arr);
				
				$qr = mysql_query("SELECT fnum,pack_credits,pack_credits_type,packSpecial FROM packages WHERE fnum = ".quote_smart($arr['packageID'])."");
				$cnt = mysql_num_rows($qr);
				if($cnt > 0) {
					$ar = mysql_fetch_array($qr);
					extract($ar);
				}
				
				if(getCount("SELECT COUNT(id) FROM creditadditions WHERE orderID = ".quote_smart($order_id)."", "COUNT") == 0) {
					if($packageType == 'advertising') {
						if($packSpecial == 0) {
							/*$query = mysql_query("INSERT INTO creditadditions
							(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
							VALUES
							(".quote_smart($_SESSION['login']).", ".quote_smart($pack_credits).", ".quote_smart($pack_credits_type).",NOW(),".quote_smart($custom).")
							");*/
							if($pack_credits_type == 'referrals') {
								$packRefs = packReferrals($fnum);
								$sql = mysql_query("UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' AND username != ".quote_smart($_SESSION['login'])." LIMIT $packRefs");
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
								(".quote_smart($username).", ".quote_smart($pack_credits).", ".quote_smart($pack_credits_type).",NOW(),".quote_smart($order_id).")
								");
							}
							$notifyLog .= "Inserted new credits addition.\n";
						} else {
							$query = mysql_query("SELECT item FROM packitems WHERE package = ".quote_smart($fnum)."");
							$count = mysql_num_rows($query);
							if($count > 0) {
								for($i = 0;$i < $count;$i++) {
									mysql_data_seek($query, $i);
									$arr = mysql_fetch_array($query);
									$item = getArray("SELECT pack_credits, pack_credits_type FROM packages WHERE fnum = ".quote_smart($arr['item'])."");
									if($item['pack_credits_type'] == 'referrals') {
										$packRefs = packReferrals($arr['item']);
										$sql = mysql_query("UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' AND username != ".quote_smart($_SESSION['login'])." LIMIT $packRefs");
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
											(".quote_smart($username).", ".quote_smart($item['pack_credits']).", ".quote_smart($item['pack_credits_type']).",NOW(),".quote_smart($order_id).")
											");
										}
									/*$q = mysql_query("INSERT INTO creditadditions
									(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
									VALUES
									(".quote_smart($_SESSION['login']).", ".quote_smart($item['pack_credits']).", ".quote_smart($item['pack_credits_type']).",NOW(),".quote_smart($custom).")
									");*/
									$notifyLog .= "Inserted new credits addition for item ".$arr['item'].".\n";
								}
							} else {
								$notifyLog .= "There were no items found for package ".$fnum." !!!!\n ERROR FILLING CREDIT REQUESTS!!! \n";
								$notifyLog = " ERROR FILLING CREDIT REQUESTS!!! \n\n".$notifyLog;
							}
						}
					} else if($packageType == 'membership') {
						$query = mysql_query("SELECT itemID as item FROM membershipitems WHERE membershipID = ".quote_smart($package)."");
						$count = mysql_num_rows($query);
						if($count > 0) {
							for($i = 0;$i < $count;$i++) {
								mysql_data_seek($query, $i);
								$arr = mysql_fetch_array($query);
								$item = getArray("SELECT pack_credits, pack_credits_type FROM packages WHERE fnum = ".quote_smart($arr['item'])."");
								if($item['pack_credits_type'] == 'referrals') {
									$packRefs = packReferrals($arr['item']);
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

Simply add ".($receivedRefs - $packRefs)." referral's to the account with the username ".$username." .";
										$warning = 'Your site has tried to process a referral assignment, but there where not enough orphans to fully complete the order.\n\nOrder ID '.$order.'\nUsername '.$username.'\nOrdered '.$packRefs.'\nReceived '.$receivedRefs.'\nReferrals not received: '.($receivedRefs - $packRefs).'\n';
										$mailer=mail($adminemail,$ptrname.' : Important Referral Assignment Notice',$message,$headers);
									}
								} else {
									$q = mysql_query("INSERT INTO creditadditions
									(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
									VALUES
									(".quote_smart($_SESSION['login']).", ".quote_smart($item['pack_credits']).", ".quote_smart($item['pack_credits_type']).",NOW(),".quote_smart($custom).")
									");
								} 
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
							$sq = "INSERT INTO memberships (
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
								".quote_smart($_SESSION['login']).",
								".quote_smart($membership['membershipName']).",
								".quote_smart($package).",
								".quote_smart($custom).",
								".quote_smart($membership['membershipPrice']).",
								'1',
								".$endDate.",
								NOW(),
								".quote_smart($lifetime)."
							)";
							mysql_query($sq) or die(mysql_error()."<BR>Query: ".$sq);
							
						}
						
					}
					debitAccountBalance($_SESSION['login'],'debit',$pack_price);
					$query = mysql_query("UPDATE orders SET orderPaid = '1' WHERE id = ".quote_smart($order_id)."");
					$notifyLog .= "Debited user account for ($pack_price).\n";
				} else {
					$notifyLog .= "Didn't insert credits addition.\n";
				}
				
				
				$q3 = "select email from orders where id = ".quote_smart($custom)."";
				$notifyLog .= $q3."\n";
				$r = mysql_query($q3) or queryError($q3, __LINE__);
				if($r) { 
					$notifyLog .= "E-Mail found, pulling row.\n";
					$e = mysql_fetch_row($r);
					$notifyLog .= "E-Mail pulled as ".$e."\n";
				} else {
					$notifyLog .= "E-Mail row could not be pulled from $q3\n";
				}
				
				
			mysql_query("UPDATE orders SET orderStatus = '1' WHERE fnum = ".quote_smart($order_id)."");

			//echo "Your order has been processed. Thank you.<BR>";
			
			$sql = mysql_query("select name, comments, value, subject from design where name ='emailOrderThankyou'");
			$array = mysql_fetch_array($sql);
			$from="From: $adminemail";
			$arr = getArray("SELECT * FROM users WHERE username = ".quote_smart($_SESSION['login'])."");
			$message=$array['value']."\n\n\n Your username is: ".$arr['username']." \n Your password is: ".$arr['fpassword']." \n Use this link to enter in your account: \n $ptrurl?tp=member";
			$subject = $ptrname.": ".$array['subject'];
			@mail($arr['email'],$subject,$message,$from);
			
			$ordered = TRUE;
		} else {
			echo "You do not have suffecient account funds to process this request.<BR>";
		}
	}
	
	$site_url = prepURL($site_url);
	
	if($_REQUEST['paymentType'] == 'paypal') {
		//redirect to paypal
		$payoptions = getArray("SELECT adminpaypal, paypal_currency FROM payoptions LIMIT 1");
		$adminpaypal = $payoptions['adminpaypal'];
		$paypal_currency = $payoptions['paypal_currency'];
		
		$header = "Location: https://www.paypal.com/xclick?business=$adminpaypal&item_name=$pack_name&first_name=$my_sFirstName&last_name=$my_sLastName&email=$my_clientEmail&item_number=1&custom=$order_id&amount=$pack_price&currency_code=".urlencode($paypal_currency)."&notify_url=".urlencode($site_url."payments/notify.php")."&return=".urlencode($site_url."index.php?tp=thankyou")."";
		//exit($header);
		header($header);
		exit();
	} else if($_REQUEST['paymentType'] == 'payza') {
		//redirect to payza
		$payoptions = getArray("SELECT adminpayza, payza_currency FROM payoptions LIMIT 1");
		$adminpayza = $payoptions['adminpayza'];
		$payza_currency = $payoptions['payza_currency'];
		$header = "Location: https://www.payza.com/PayProcess.aspx?ap_purchasetype=other&ap_merchant=$adminpayza&ap_itemname=$pack_name (Order: $order_id)&apc_1=$order_id&ap_currency=".urlencode($payza_currency)."&ap_returnurl=".urlencode($site_url."index.php?tp=thankyou")."&ap_itemcode=&ap_quantity=1&ap_description=$pack_name&ap_amount=$pack_price&ap_cancelurl=".$site_url."";
		//exit($header);
		header($header);
		exit();
	} else if($_REQUEST['paymentType'] == 'stormpay') {
		//redirect to stormpay
		$adminnetpay = getValue("SELECT adminstormpay FROM payoptions LIMIT 1");
		header("location:https://www.stormpay.com/stormpay/handle_gen.php?generic=1&vendor_email=$adminstormpay&payee_email=$adminemail&transaction_ref=$order_id&product_name=$pack_name&amount=$pack_price&require_IPN=1&return_URL=".urlencode($site_url."index.php?tp=thankyou"));
		exit();
	} else if($_REQUEST['paymentType'] == 'egold') {
		$adminegold = getValue("SELECT adminegold FROM payoptions LIMIT 1");
		header("location:https://www.e-gold.com/sci_asp/payments.asp?PAYEE_ACCOUNT=$adminegold&PAYEE_NAME=$ptrname&PAYMENT_AMOUNT=$pack_price&PAYMENT_UNITS=1&PAYMENT_METAL_ID=1&STATUS_URL=mailto:$adminemail&PAYMENT_URL=".urlencode($site_url."index.php?tp=thankyou")."&NOPAYMENT_URL=$ptrurl&PAYMENT_URL_METHOD=POST&BAGGAGE_FIELDS=X_NAME&X_NAME=$buyer_egold_email");
		exit;
	} else if($_REQUEST['paymentType'] == 'netpay') {
		$adminnetpay = getValue("SELECT adminnetpay FROM payoptions LIMIT 1");
		header("location:https://www.netpay.tv/cgi-bin/merchant/mpay.cgi?PAYMENT_AMOUNT=$pack_price&PAYEE_NAME=$ptrname&PAYEE_ACCOUNT=$adminnetpay&MEMO=&STATUS_URL=".$site_url."thankyou.php&RETURN_URL=".$site_url."thankyou.php&CANCEL_URL=".$site_url."thankyou.php&PRODUCT_NAME=$pack_name&EXTRA_INFO=");
		exit();
	} else if($_REQUEST['paymentType'] == 'moneybookers') {
		$adminmoneybookers = getValue("SELECT adminmoneybookers FROM payoptions LIMIT 1");
		?><BR><BR><BR><HR><table width="400" align="center" bgcolor="#FFFFCC" bordercolor="#000000" border="1">
		<tr> 
		<td bgcolor="f5f5f5"> 
		<div align="center"><b>Payment Via Moneybookers</b></div>
		</td>
		</tr>
		<tr> 
		<td>
		<div align="center">Please pay <?php echo $setupinfo['currency']; ?> 
		<?php echo $pack_price; ?>
		to our moneybookers account:<br>
		<?php echo $adminmoneybookers; ?><br>
		<br>
		When we have verified your payment, we will update your order status.
		</div>
		</td>
		</tr>
		</table><?php
		exit();
	}
	if(substr($_REQUEST['paymentType'],0,6) == 'custom') {
		$paymentID = substr($_REQUEST['paymentType'],6,strlen($_REQUEST['paymentType'])-6);
		$customPayment = getArray("SELECT * FROM customPayments WHERE id = ".quote_smart($paymentID)." LIMIT 1");
		?><BR><BR><BR><HR><table width="400" align="center" bgcolor="#FFFFCC" bordercolor="#000000" border="1">
		<tr> 
		<td bgcolor="f5f5f5"> 
		<div align="center"><b>Payment Via <?php echo $customPayment['paymentName']; ?></b></div>
		</td>
		</tr>
		<tr> 
		<td>
		<div align="center">Please pay <?php echo $setupinfo['currency']; ?><?php echo $pack_price; ?>
		to our <?php echo $customPayment['paymentName']; ?> account:<br>
		<?php echo str_replace("\n","<BR>",$customPayment['accountDetails']); ?><br>
		<br>
		When we have verified your payment, we will update your order status.
		</div>
		</td>
		</tr>
		</table><?php
		exit();
	}
} else { 
	echo "(".$_REQUEST['packID'].") Is not a valid pack ID. Please try again.<BR>";
}
?>