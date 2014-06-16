
<?php echo $pageHeader; ?>
<h2><?php echo __('Withdraw Requests'); ?></h2>
<p><?php
$payoptions = getArray("SELECT * FROM payoptions WHERE 1 LIMIT 1");
$user = getArray("SELECT * FROM users WHERE username = ".quote_smart($_SESSION['login'])."");

$message = '';

if($act=='send'){
 	if(!isset($_SESSION['login'])) {
		exit("<b><font size=4 color=red>&nbsp &nbsp ".__('INCORRECT LOGIN!')."</b></font><p>&nbsp &nbsp ".__("Please click 'BACK' button and try again."));
	} else {
		@extract(mysql_fetch_array(mysql_query("SELECT * FROM payoptions")));
		
		
		$canUsePayza = canUsePayzaAutopay($_SESSION['login'],$_REQUEST['payact'],$_REQUEST['amount']);
		
		if(
		is_numeric($_REQUEST['amount']) && 
		$_REQUEST['amount'] > 0 && 
		$_REQUEST['amount'] >= $minpay && 
		$_REQUEST['amount'] <= totalEarnings($_SESSION['login'])
		) {
		
			if($setupinfo['enableCashoutPin'] == '1' && $_REQUEST['cashoutPin'] == getValue("SELECT cashoutPin FROM users WHERE username = ".quote_smart($_SESSION['login'])."")) {
				if($_REQUEST['paymethod'] == 'payzaAutopay' && !$canUsePayza[0]) {
					mysql_query("INSERT INTO payrequest (username,balance, famount,payout_method,payout_account, fdate) VALUES(".quote_smart($_SESSION['login']).", ".quote_smart(totalEarnings($_SESSION['login'])).",".quote_smart($_REQUEST['amount']).", ".quote_smart($_REQUEST['paymethod']).", ".quote_smart($_REQUEST['payact']).", now())") or die(mysql_error());
					$message = __("<font color=red>Your payment could not be processed <b>automatically</b> due to account restrictions and/or the amount is too large, or you haven't completed the mandatory 1 manually verified withdraw request. If you have not completed 1 manual payout, please select only \"Payza\" and complete at least 1 manual payza withdraw.<BR>The error reported was : ".$canUsePayza[1]."<BR></font><BR><FONT COLOR=GREEN><STRONG>Your request HAS been submitted for manual withdraw. You do not need to submit another request.</STRONG></FONT>");
				} else { 
					if($_REQUEST['paymethod'] == 'payzaAutopay') {
						$amount = number_format($_REQUEST['amount'],2,".","");
						$payzaBalance = getValue("SELECT payzaBalance FROM setupinfo LIMIT 1") or die(mysql_error());
						if($payzaBalance > $_REQUEST['amount']) {
							$newBalance = $payzaBalance - $amount;
							mysql_query("UPDATE setupinfo SET payzaBalance = ".quote_smart($newBalance)." WHERE 1") or die(mysql_error());
							$payzaEmail = getValue("SELECT adminpayza FROM payoptions LIMIT 1");
							$payzaPass = getValue("SELECT payzaAPIPass FROM setupinfo LIMIT 1");
							
							$testMode = '1'; //0 = disabled... 1 = enabled...
							
							$payment = payzaPayment($payzaEmail,$payzaPass,$amount,$_REQUEST['payact'],$payzaEmail,3,$ptrname.' Automatic Payout Request','USD',$testMode);
							if($payment[0] == TRUE) {
								mysql_query("INSERT INTO payrequest (username,balance,famount,payout_method,payout_account, fdate,paidOut) VALUES(".quote_smart($_SESSION['login']).", ".quote_smart(totalEarnings($_SESSION['login'])).",".quote_smart($_REQUEST['amount']).", ".quote_smart($_REQUEST['paymethod']).", ".quote_smart($_REQUEST['payact']).", now(),'1')") or die(mysql_error());
								$message = "<b><FONT COLOR=GREEN>CONGRATULATIONS!</FONT> Your withdraw request has been successfully processed.</b>";
								debitAccountBalance($_SESSION['login'], 'debit', $amount, 'usd','payout');
							} else {
								$description = $payment[2];
								mysql_query("INSERT INTO payrequest (username,balance, famount,payout_method,payout_account, fdate) VALUES(".quote_smart($_SESSION['login']).", ".quote_smart(totalEarnings($_SESSION['login'])).",".quote_smart($_REQUEST['amount']).", ".quote_smart($_REQUEST['paymethod']).", ".quote_smart($_REQUEST['payact']).", now())") or die(mysql_error());
								$message = __("<b>Your request has failed during payment. Your request must be processed manually and has been submitted for approval. We appologize for the inconvenience. Your request will be processed as soon as possible.</b><BR>The error returned was : ".$description);
							}
						} else {
							mysql_query("INSERT INTO payrequest (username,balance, famount,payout_method,payout_account, fdate) VALUES(".quote_smart($_SESSION['login']).", ".quote_smart(totalEarnings($_SESSION['login'])).",".quote_smart($_REQUEST['amount']).", ".quote_smart($_REQUEST['paymethod']).", ".quote_smart($_REQUEST['payact']).", now())") or die(mysql_error());
							$message = __("<b>Your request has been submitted, currently our automated system is down until we update the available balance. Your request must be processed manually and has been submitted for approval. We appologize for the inconvenience. Your request will be processed as soon as possible.</b>");
						}
					} else {
						mysql_query("INSERT INTO payrequest (username,balance, famount,payout_method,payout_account, fdate) VALUES(".quote_smart($_SESSION['login']).", ".quote_smart(totalEarnings($_SESSION['login'])).",".quote_smart($_REQUEST['amount']).", ".quote_smart($_REQUEST['paymethod']).", ".quote_smart($_REQUEST['payact']).", now())") or die(mysql_error());
						$message = "<b><FONT COLOR=GREEN>".__('YOUR REQUEST WAS SUBMITTED SUCCESFULLY!')."</FONT></b>";
					}
				}
				mysql_query("UPDATE users SET fpayacc = ".quote_smart($_REQUEST['payact']).", fpaymethod = ".quote_smart($_REQUEST['paymethod'])." WHERE username = ".quote_smart($_SESSION['login'])."");
			} else {
				$message = "<b><font color=red>".__('ERROR: Your cash out pin was not valid. Please check your account and try again.')."</font></b>";
			}
		} else {
			$message = "<b><font color=red>".__('ERROR: Your requested amount was entered incorrectly or it exceed\'s your available account balance..')."</font></b>";
			
			
		}
	}
}

@extract(mysql_fetch_array(mysql_query("SELECT * FROM payoptions")));
?>
<style type="text/css">
<!--
.style1 {
	font-size: 12px;
	font-weight: bold;
}
.style2 {font-size: 12px}
-->
</style>

<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="box">
  <tr>
    <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
      <tr>
        <td colspan="3" class="headerStyle"><?php echo __('WITHDRAW REQUEST FORM'); ?> </td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td><p><span class="style1"><font face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('*You can request payout only if your current account balance is minimum');?> <?php echo $setupinfo['currency_symbol']; ?><?php echo number_format($minpay,2); ?> <br>
            </font></span><span class="style2"><font face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Payouts will be made to your payment account once your account reaches the minimum balance, and you request a payout.'); ?></font></span></p>   
                   </td>
        </tr>
        <?php
		if($minpay <= totalEarnings($_SESSION['login']) && $message == '') { ?><tr>
          <td>
            <p class="style2"><?php echo __('We will transfer your earnings to your <b> account </b> within next 30 days unless using an &quot;Automated\' method. <br />
            NOTE: Some automated methods require at least 1 manual validation.'); ?></p>
            
            <p align="center" class="style2"><font face="Verdana, Arial, Helvetica, sans-serif"><b><?php echo __('Please complete the form below to process your withdraw request.'); ?></b></font></p>
            <form action="index.php" method="post" name="form1" class="style2">
              <table width="80%" border="0" align="center" cellpadding="5" cellspacing="0" background="<?php echo $templateFolder; ?>images/fon.gif.gif">
                <tr>
                  <td width="37%"><span class="style1"><font face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Request amount'); ?>:</font></span></td>
                  <td width="63%"> <span class="style2"><font face="Verdana, Arial, Helvetica, sans-serif"><?php echo $setupinfo['currency']; ?> </font>
                      <input type="text" name="amount" size="3" value="<?php echo number_format($minpay,2,".",""); ?>"> (<?php $earnings = totalEarnings($_SESSION['login']);
	  echo $setupinfo['currency'].number_format($earnings,5,".",",");
	  ?> <?php echo __('Available Balance'); ?>)
                  </span></td>
                </tr>
                <tr>
                  <td width="37%"><span class="style2"><b><font face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Payment Method'); ?>:</font></b></span></td>
                  <td width="63%">
                    <select name="paymethod">
                    	<?php
						// PAYPAL
						if($payoptions['memberpaypal'] == 'yes') { ?><option value="paypal" <?php if($user['fpaymethod'] == 'paypal') echo 'selected="selected"'; ?>>Paypal</option><?php } ?>
                        
						<?php
						// E-GOLD
						if($payoptions['memberegold'] == 'yes') { ?><option value="egold" <?php if($user['fpaymethod'] == 'egold') echo 'selected="selected"'; ?>>E-Gold</option><?php } ?>
                        
						<?php
						// SKRILL / MONEYBOOKERS
						if($payoptions['membermoneybookers'] == 'yes') { ?><option value="moneybookers" <?php if($user['fpaymethod'] == 'moneybookers') echo 'selected="selected"'; ?>>Skrill / Moneybookers</option><?php } ?>
                        
						<?php
						// PAYZA / ALERTPAY
						if($payoptions['memberpayza'] == 'yes') { ?><option value="payza" <?php if($user['fpaymethod'] == 'payza') echo 'selected="selected"'; ?>>Payza</option><?php } ?>
                        
						<?php
						// PAYZA AUTO PAY
						if($setupinfo['usePayzaAutopay'] == '1') { ?><option value="payzaAutopay" <?php if($user['fpaymethod'] == 'payzaAutopay') echo 'selected="selected"'; ?>>Payza <?php echo __('Automated Withdraw'); ?></option><?php } ?>
                        
                        <?php
                        //NETPAY AND STORMPAY DEPRICATED
						/*
						<?php if($memberstormpay) { ?><option value="stormpay" <?php if($user['fpaymethod'] == 'stormpay') echo 'selected="selected"'; ?>>StormPay</option><?php } ?>
						<?php if($membernetpay) { ?><option value="netpay" <?php if($user['fpaymethod'] == 'netpay') echo 'selected="selected"'; ?>>Netpay</option><?php } ?>*/ ?>
					</select>
                  </td>
                </tr>
                <tr>
                  <td width="37%"><span class="style2"><b><font face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Your  E-Mail / Payment ID'); ?>:</font></b></span></td>
                  <td width="63%">
                    <input name="payact" type="text" value="<?php echo $user['fpayacc']; ?>"> 
                    (The email or account id to make the payment to.)
                  </td>
                </tr>
                <?php if($setupinfo['enableCashoutPin'] == '1') { ?>
                <tr>
                  <td width="37%"><span class="style2"><b><font face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Cashout Pin'); ?>:</font></b></span></td>
                  <td width="63%">
                    <input name="cashoutPin" type="text" value="<?php echo $user['cashoutPin']; ?>"> 
                    (Pin Number for cashing out, leave blank if you have none.)
                  </td>
                </tr>
                <?php } //END CASHOUT PIN ENABLED CHECK
				?>
                <tr>
                  <td colspan="2">
                    <div align="center" class="style2">
                      <input type="hidden" name="act" value="send">
                      <input type="hidden" name="tp" value="redemption">
                      <input type="hidden" name="s" value="2">
                      <input type="submit" name="Submit" value="<?php echo __('Withdraw Now'); ?>">
                  </div></td>
                </tr>
              </table>
            </form>
            <p class="style2">&nbsp; </p>
            <?php
            $sql=mysql_query("SELECT * FROM payrequest WHERE username = ".quote_smart($_SESSION['login'])."");
			$rows = mysql_num_rows($sql);
			if($rows == 0) {
				echo __("You have no past withdraw request history.<BR>");
			} else {
			?><table class="fullwidth" border="0" cellspacing="0" cellpadding="5" width="100%">
              <thead>
                <tr>
                  <td><strong><?php echo __('Username'); ?></strong></td>
                  <td><strong><?php echo __('Date'); ?></strong></td>
                  <td><strong><?php echo __('Request amount'); ?></strong></td>
                  <td><strong><?php echo __('Payout Method'); ?></strong></td>
                  <td><strong><?php echo __('Payout Account'); ?></strong></td>
                  <td><strong><?php echo __('Status'); ?></strong></td>
                </tr>
              </thead>
              <tbody>
                <?php



		for($i=0;$i<$rows;$i++) {



			mysql_data_seek($sql,$i);

			$arr=mysql_fetch_array($sql); extract($arr);

			$accountnum = $fnum;

			$balancetotal = totalEarnings($username);

			?>
                <tr>
                  <td><?php echo $username; ?></td>
                  <td><?php echo $fdate; ?></td>
                  <td><?php echo $setupinfo['currency']; ?><?php echo $famount; ?></td>
                  <td><?php echo $payout_method; ?></td>
                  <td><?php echo $payout_account; ?></td>
                  <td><?php 
				  if($paidOut == '1') echo 'Completed';
				  else if($paidOut == '0') echo "Pending";
				  else if($paidOut == '-1') echo "Denied";
				  
				   ?></td>
                </tr>
                <?php

	

		} //END LOOP THROUGH RESULTS

		}

		?>
              </tbody>
            </table>
          </td></tr>
            <?php
			} else {
				?><tr><td><?php
                if($message != '') {
					?><p class="style2"><?php echo $message; ?></p><?php
				} else { 
                	echo __('You do not currently meet the minimum requirements in order to request a withdraw. Please wait until your available account balance meets the minimum requirements.'); 
				}
				?>
                </td></tr>
          
        <?php
			}
			?>
      </table></td>
  </tr>
  
</table>
</p>
<?php echo $pageFooter; ?>