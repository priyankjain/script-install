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

include("../include/cfg.php");
include("../include/dbconnect.php");
include("../include/mod_ini.php");
include("../include/global.php");
include("../include/functions.php");

if(isset($_REQUEST)) {
	foreach($_REQUEST as $k => $v) { $notifyLog .= "_REQUEST[".$k."] = ".$v."\n"; }
} else {
	$notifyLog .= "No request variables.\n";
}
$notifyLog .= "\n";
	
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
	$req .= "&$key=$value";
}
$req = str_replace("&", "\n",$req);
$req = "\n".$req;
if ($action != "thankyou") {
	$notifyLog .= 'Action is not = "thankyou"'."\n";
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$req .= "&$key=$value";
	}
	// post back to PayPal system to validate
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
	// assign posted variables to local variables
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$custom = $_POST['custom'];
	
	if (!$fp) {
		// HTTP ERROR
		echo "<b>ERROR:</b> Could not connect to Paypal's server. Please contact support if your account has not been activated with your payment information and account username. Thank you.";
		$notifyLog .= 'ERROR: Could not connect to Paypal\'s server. Please contact support if your account has not been activated with your payment information and account username. Thank you.'."\n";
		$notifyLog .= "Sent request to paypal: ".$req."\n";
	} else {
		fputs ($fp, $header . $req);
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp ($res, "VERIFIED") == 0) {
				$notifyLog .= "VERIFIED Request to paypal as a valid IPN Request\n";
				
				// Assign posted variables to local variables
				$receiver_email = $_REQUEST['receiver_email'];
				$payer_email = $_REQUEST['payer_email'];
				$payer_status = $_REQUEST['payer_status'];
				$payment_gross = $_REQUEST['payment_gross'];
				$payment_fee = $_REQUEST['payment_fee'];
				$payment_date = $_REQUEST['payment_date'];
				$payment_type = $_REQUEST['payment_type'];
				$payment_status = $_REQUEST['payment_status'];
				$pending_reason = $_REQUEST['pending_reason'];
				$txn_id = $_REQUEST['txn_id'];
				$txn_type = $_REQUEST['txn_type'];
				$custom = $_REQUEST['custom'];
			
				if ($_REQUEST['payment_status'] == "Completed" || $_REQUEST['payment_status'] == "Pending") {
					$notifyLog .= "Payment status is either Completed or Pending.\n";
					$q1 = "update orders set orderPaid = '1' where id = ".quote_smart($custom)."  ";
					mysql_query($q1) or queryError($q1, __LINE__);
					
					$notifyLog .= $q1."\n";
					
			////////////////////////////////////////
					$sq = "SELECT packageID,packageType,username FROM orders WHERE id = ".quote_smart($custom)."";
					$query = mysql_query($sq) or queryError($sq, __LINE__);
					$arr = mysql_fetch_array($query);
					extract($arr);
					$sq = "SELECT COUNT(id) FROM creditadditions WHERE orderID = ".quote_smart($custom)."";
					mysql_query($sq) or queryError($sq, __LINE__);
					
					if(getCount($sq, "COUNT") == 0) {
						$notifyLog .= "Count of creditadditions for Order ID: ".$custom." is equal to 0, continuing.\n";
						$package = $packageID;
						if($packageType == 'advertising') {
							$notifyLog .= "Package type for order is advertising\n";
							$sq = "SELECT fnum,pack_credits,pack_credits_type,packSpecial FROM packages WHERE fnum = ".quote_smart($arr['packageID'])."";
							$qr = mysql_query($sq) or queryError($sq, __LINE__);
							$ar = mysql_fetch_array($qr);
							extract($ar);
							if(getCount("SELECT COUNT(id) FROM creditadditions WHERE orderID = ".quote_smart($custom)."", "COUNT") == 0) {
								
								if($packSpecial == 0) {
									$notifyLog .= "This is not a special package, processing single credits order.\n";
									if($pack_credits_type == 'referrals') {
										$notifyLog .= "Package type is referrals, building out referrals list and assigning to member.\n";
										$packRefs = packReferrals($fnum);
										$sq = "UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' AND username != ".quote_smart($username)." LIMIT $packRefs";
										$sql = mysql_query($sq) or queryError($sq, __LINE__);
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
										$sq = "INSERT INTO creditadditions
										(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
										VALUES
										(".quote_smart($username).", ".quote_smart($pack_credits).", ".quote_smart($pack_credits_type).",NOW(),".quote_smart($custom).")
										";
										$q = mysql_query($sq) or queryError($sq, __LINE__);
									}
									
									$notifyLog .= "Inserted new credits addition.\n";
								} else {
									$sq = "SELECT item FROM packitems WHERE package = ".quote_smart($fnum)."";
									$query = mysql_query($sq) or queryError($sq, __LINE__);
									$count = mysql_num_rows($query);
									if($count > 0) {
										for($i = 0;$i < $count;$i++) {
											mysql_data_seek($query, $i);
											$arr = mysql_fetch_array($query);
											$item = getArray("SELECT fnum,pack_credits, pack_credits_type FROM packages WHERE fnum = ".quote_smart($arr['item'])."");
											if($item['pack_credits_type'] == 'referrals') {
												$packRefs = packReferrals($item['fnum']);
												$sq = "UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' AND username != ".quote_smart($username)." LIMIT $packRefs";
												$sql = mysql_query($sq) or queryError($sq, __LINE__);
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
												$sq = "INSERT INTO creditadditions
												(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
												VALUES
												(".quote_smart($username).", ".quote_smart($item['pack_credits']).", ".quote_smart($item['pack_credits_type']).",NOW(),".quote_smart($custom).")
												";
												$q = mysql_query($sq) or queryError($sq, __LINE__);
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
									$sq = "INSERT INTO creditadditions
									(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
									VALUES
									(".quote_smart($username).", ".quote_smart($item['pack_credits']).", ".quote_smart($item['pack_credits_type']).",NOW(),".quote_smart($custom).")
									";
									$q = mysql_query($sq) or $notifyLog .= "Error while running credit query.\nError: ".mysql_error()."\nQuery: ".$sq."\n\n";
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
				} else {
					$notifyLog .= "Payment method not accepted. Not setup as completed or pending (".$_REQUEST['payment_status'].")...\n";
				}
			}
		}
	}
}

$notifyLog .= "Script Finished.\n";
mail($adminemail, "NEW Order", $notifyLog);

function queryError($query, $line) {
	$GLOBALS['notifyLog'] .= "\nQUERY Error occurred on line ".$line."\nThe error produced was:\n".$query."\n\n";
}
?>