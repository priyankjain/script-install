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
extract(getArray("SELECT * FROM setupinfo LIMIT 1"));

	if(isset($_REQUEST)) {
		foreach($_REQUEST as $k => $v) { $notifyLog .= "_REQUEST[".$k."] = ".$v."\n"; }
	} else {
		$notifyLog .= "No request variables.\n";
	}
	$notifyLog .= "\n";
	
	
	
    // Security code variable
    $ap_SecurityCode;

    // Customer info variables
    $ap_CustFirstName;
    $ap_CustLastName;
	$ap_CustAddress;
	$ap_CustCity;
    $ap_CustCountry;
	$ap_CustZip;
	$ap_CustEmailAddress;

    // Common transaction variables
    $ap_ReferenceNumber;
	$ap_Status;
	$ap_PurchaseType;
	$ap_Merchant;
    $ap_ItemName;
	$ap_ItemCode;
	$ap_Description;
	$ap_Quantity;
	$ap_Amount;
	$ap_AdditionalCharges;
    $ap_ShippingCharges;
	$ap_TaxAmount;
	$ap_DiscountAmount;
	$ap_TotalAmount;
	$ap_Currency;
    $ap_Test;

    // Custom fields
    $ap_Apc_1;
	$ap_Apc_2;
	$ap_Apc_3;
	$ap_Apc_4;
	$ap_Apc_5;
	$ap_Apc_6;

    // Subscription variables
    $ap_SubscriptionReferenceNumber;
	$ap_TimeUnit;
	$ap_PeriodLength;
	$ap_PeriodCount;
	$ap_NextRunDate;
    $ap_TrialTimeUnit;
	$ap_TrialPeriodLength;
	$ap_TrialAmount;


	// Initialize variable
	setSecurityCodeVariable();

	$payzaCode = getValue("SELECT payzacode FROM payoptions");
	if ($ap_SecurityCode != $payzaCode) {
		// The Data is NOT sent by Payza.
		// Take appropriate action 
		$notifyLog .= "AP Security code mis-match, Failure to continue\n";
	} else {
		if ($ap_Test == "1") {
			 $notifyLog .= "AP Test Mode Enabled, Not processing order!\n";
			 // Your site is currently being integrated with Payza IPN for TESTING PURPOSES
			// ONLY. Don't store any information in your Production database and don't process
			// this transaction as a real order.
		} else {
			// Initialize variables
			setCustomerInfoVariables();
			setCommonTransactionVariables();

			// Initialize the custom field variables.
			setCustomFields();

			// If the transaction is subscription-based (recurring payment), initialize the
			// Subscription variables too.
			if ($ap_PurchaseType == "Subscription") {
				setSubscriptionVariables();
			}

			if (strlen($ap_ReferenceNumber) == 0 && $ap_TrialAmount != "0") {
				 $notifyLog .= "Invalid Transaction!\nReference number = 0 or Trial Amount != 0";
				 // Invalid reference number. The reference number is invalid because the ap_ReferenceNumber doesn't
				// contain a value and the ap_TrialAmount is not equal to 0.
			} else {
				if ($ap_Status == "Success") {
					$custom = $ap_Apc_1;
					$notifyLog .= "Payment status is either Completed or Pending.\nCustom order ID = ".$custom."\n";
					$q1 = "update orders set orderPaid = '1' where id = ".quote_smart($custom)."  ";
					mysql_query($q1) or queryError($q1, __LINE__);
					
					//$notifyLog .= $q1."\n";
					$sq = "SELECT packageID,packageType,username,orderTotal FROM orders WHERE id = ".quote_smart($custom)."";
					$query = mysql_query($sq) or queryError($sq, __FILE__);
					$arr = mysql_fetch_array($query);
					extract($arr);
					
					$cntQry = "SELECT COUNT(id) FROM creditadditions WHERE orderID = ".quote_smart($custom)."";
					mysql_query($cntQry) or queryError($sq, __FILE__);
					
					if(getValue($cntQry) == 0) {
						$notifyLog .= "This order has not been credited, running credit validation now.\n";
						$package = $packageID;
						if($packageType == 'advertising') {
							$notifyLog .= "Credit type is advertising, selecting pack credits and continuing.\n";
							$sq = "SELECT fnum,pack_credits,pack_credits_type,packSpecial FROM packages WHERE fnum = ".quote_smart($arr['packageID'])."";
							$qr = mysql_query($sq) or queryError($sq, __FILE__);
							$ar = mysql_fetch_array($qr);
							extract($ar);
						
							if($packSpecial == 0) {
								$notifyLog .= "This is not a package item, continuing\n";
								if($pack_credits_type == 'referrals') {
									$notifyLog .= "This purchase is for referrals, processing order\n";
									$packRefs = packReferrals($fnum);
									$sq = "UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' LIMIT $packRefs";
									$sql = mysql_query($sq) or queryError($sq, __FILE__);
									$receivedRefs = mysql_affected_rows();
									if($receivedRefs < $packRefs) {
										$notifyLog .= "Received referrals is less than ordered, sending a notice that the site has no more available referrals.\n";
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
									$notifyLog .= "Ad type is \"advertising\" and not referrals, processing credit addition (".$pack_credits_type.").\n";
									$sq = "INSERT INTO creditadditions
									(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
									VALUES
									(".quote_smart($username).", ".quote_smart($pack_credits).", ".quote_smart($pack_credits_type).",NOW(),".quote_smart($custom).")
									";
									$q = mysql_query($sq) or queryError($sq, __FILE__);
									$notifyLog .= "Inserted credits with query (".$sq.")\n";
								}
										/*$query = mysql_query("INSERT INTO creditadditions
								(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
								VALUES
								(".quote_smart($username).", ".quote_smart($pack_credits).", ".quote_smart($pack_credits_type).",NOW(),".quote_smart($custom).")
								");*/
								$notifyLog .= "Inserted new credits addition.\n";
							} else {
								$notifyLog .= "Order is a package, processing multi item package order\n";
								
								$sq = "SELECT item FROM packitems WHERE package = ".quote_smart($fnum)."";
								$query = mysql_query($sq) or queryError($sq, __FILE__);
								$count = mysql_num_rows($query);
								if($count > 0) {
									$notifyLog .= "Item count > 0, looping through each item in pack now.\n";
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
		
						} else if($packageType == 'membership') {
							$notifyLog .= "Order is for a membership, continuing to process order.\n";
							$query = mysql_query("SELECT itemID as item FROM membershipitems WHERE membershipID = ".quote_smart($package)."");
							$count = mysql_num_rows($query);
							if($count > 0) {
								$notifyLog .= "Count of items in memberships > 0\n";
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
					} else {
						$notifyLog .= "Credit additions were already found for this order ID.\n";
					}//END CREDIT ADDITIONS CHECK
					
					$q3 = "select email from orders where id = ".quote_smart($custom)."";
					$notifyLog .= $q3."\n";
					$r = mysql_query($q3) or queryError($q3, __LINE__);
					
					if($r) { 
						$notifyLog .= "E-Mail found, pulling row.\n";
						$e = mysql_fetch_array($r);
						$notifyLog .= "E-Mail pulled as ".$e['email']."\n";
					} else {
						$notifyLog .= "E-Mail row could not be pulled from $q3\n";
					}
				} else {
					$notifyLog .= "Transaction Cancelled!\n";
					// Transaction cancelled means seller explicitely cancelled the subscription or Payza 								
					// cancelled or it was cancelled since buyer didnt have enough money after resheduling after two times.
					// Take Action appropriately
				}
			}
		}
	}

	// Security code variable
	function setSecurityCodeVariable()
	{
        $GLOBALS['ap_SecurityCode'] = $_POST['ap_securitycode'];
	}
	
	// Customer info variables
    function setCustomerInfoVariables()
    {
        $GLOBALS['ap_CustFirstName'] =$_POST['ap_custfirstname'];
        $GLOBALS['ap_CustLastName'] = $_POST['ap_custlastname'];
        $GLOBALS['ap_CustAddress'] = $_POST['ap_custaddress'];
        $GLOBALS['ap_CustCity'] = $_POST['ap_custcity'];
        $GLOBALS['ap_CustCountry'] = $_POST['ap_custcountry'];
        $GLOBALS['ap_CustZip'] = $_POST['ap_custzip'];
        $GLOBALS['ap_CustEmailAddress'] = $_POST['ap_custemailaddress'];
        $GLOBALS['ap_PurchaseType'] = $_POST['ap_purchasetype'];
        $GLOBALS['ap_Merchant'] = $_POST['ap_merchant'];
    }
	
	// Common transaction variables
    function setCommonTransactionVariables()
    {
        $GLOBALS['ap_ItemName'] = $_POST['ap_itemname'];
        $GLOBALS['ap_Description'] = $_POST['ap_description'];
        $GLOBALS['ap_Quantity'] = $_POST['ap_quantity'];
        $GLOBALS['ap_Amount'] = $_POST['ap_amount'];
        $GLOBALS['ap_AdditionalCharges']=$_POST['ap_additionalcharges'];
        $GLOBALS['ap_ShippingCharges']=$_POST['ap_shippingcharges'];
        $GLOBALS['ap_TaxAmount']=$_POST['ap_taxamount'];
        $GLOBALS['ap_DiscountAmount']=$_POST['ap_discountamount'];
        $GLOBALS['ap_TotalAmount'] = $_POST['ap_totalamount'];
        $GLOBALS['ap_Currency'] = $_POST['ap_currency'];
        $GLOBALS['ap_ReferenceNumber'] = $_POST['ap_referencenumber'];
        $GLOBALS['ap_Status'] = $_POST['ap_status'];
        $GLOBALS['ap_ItemCode'] = $_POST['ap_itemcode'];
        $GLOBALS['ap_Test'] = $_POST['ap_test'];
    }
	
	// Subscription variables
    function setSubscriptionVariables()
    {
	    $GLOBALS['ap_SubscriptionReferenceNumber'] = $_POST['ap_subscriptionreferencenumber'];
	    $GLOBALS['ap_TimeUnit'] = $_POST['ap_timeunit'];
	    $GLOBALS['ap_PeriodLength']=$_POST['ap_periodlength'];
	    $GLOBALS['ap_PeriodCount']=$_POST['ap_periodcount'];
	    $GLOBALS['ap_NextRunDate']=$_POST['ap_nextrundate'];
	    $GLOBALS['ap_TrialTimeUnit']=$_POST['ap_trialtimeunit'];
	    $GLOBALS['ap_TrialPeriodLength']=$_POST['ap_trialperiodlength'];
	    $GLOBALS['ap_TrialAmount']=$_POST['ap_trialamount'];
    }

	// Custom fields
    function setCustomFields()
    {
        $GLOBALS['ap_Apc_1'] = $_POST['apc_1'];
        $GLOBALS['ap_Apc_2'] = $_POST['apc_2'];
        $GLOBALS['ap_Apc_3'] = $_POST['apc_3'];
        $GLOBALS['ap_Apc_4'] = $_POST['apc_4'];
        $GLOBALS['ap_Apc_5'] = $_POST['apc_5'];
        $GLOBALS['ap_Apc_6'] = $_POST['apc_6'];
    }



$notifyLog .= "Script Finished.\n";

$headers = "Content-type: text/plain\nFrom: '".$ptrname."' <".$adminemail.">\r\n\r\n";
$message = "Dear Admin,
Your site has processed an order.

Order ID ".$custom."
Username ".$username."
Total ".$orderTotal."


The notification LOG of this transaction process is below
---------------------------------------------------------

".$notifyLog;
$mailer=mail($adminemail,$ptrname.": New Payza Notification",$message,$headers);


function queryError($query, $line) {
	$GLOBALS['notifyLog'] .= "\nQUERY Error occurred on line ".$line."\nThe error produced was:\n".$query."\n\n";
	/*
	$headers = "Content-type: text/plain\nFrom: '".$GLOBALS['ptrname']."' <".$GLOBALS['adminemail'].">\r\n\r\n";
	$message = "Dear Admin,
	Your site has processed an order.
	
	Order ID ".$GLOBALS['custom']."
	Username ".$GLOBALS['username']."
	Total ".$GLOBALS['orderTotal']."
	
	
	The notification LOG of this transaction process is below
	---------------------------------------------------------
	
	".$GLOBALS['notifyLog'];
	$mailer=mail($GLOBALS['adminemail'],$GLOBALS['ptrname'].": New Payza Notification",$message,$headers);*/

}
?>