<?php echo $pageHeader; ?><?php
$orphanCount = orphanCount();
?><?php
if(!isset($_SESSION)) session_start();
$_SESSION['randomVerification'] = rand(1000,9999);
?>
<script language="javascript" type="text/javascript">
<!--
function buyAdvertising(catNumber, packID) {
	if(catNumber == undefined && 
	(
		(
			packID == undefined && document.adForm.packID.value != '' && document.adForm.packID.value != undefined
		) || (
			packID == undefined && document.adForm.membershipID.value != '' && document.adForm.membershipID.value != undefined && document.adForm.adType.value != '' && document.adForm.adType.value != 'special'
		)
	)
	) {
		return true;
	}
	if(catNumber == undefined || catNumber == '') {
		if(catNumber == undefined) alert('Undefined...');
		if(catNumber == undefined) alert('Blank...');
		return false;
	} else {
		if(packID == 'dropDown') {
			document.adForm.packID.value = document.adForm['type'+catNumber].value;
			document.adForm.adType.value = 'special';
			return true;
		} else {
			if(catNumber == 'Memberships') {
				document.adForm.membershipID.value = packID;
				document.adForm.adType.value = 'membership';
				return true;
			} else {
				document.adForm.packID.value = packID;
				document.adForm.adType.value = 'special';
				return true;
			}
		}
	}
}
-->
</script>
<style type="text/css">
<!--
.white14pxBoldArial {font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #FFFFFF;
	font-size: 14px;
}
.arial12pxFontgreenFont {font-size: 12px; font-family: Arial, Helvetica, sans-serif;}
.greenFont {color: #009900}
-->
</style>
    <?php
					$loginPassed = FALSE;
					if(isset($_SESSION['login'])) {
						if(getValue("SELECT COUNT(fid) FROM users WHERE username = ".quote_smart($_SESSION['login'])."") > 0) {
							$loginPassed = TRUE;
						}
					}
					if($loginPassed == FALSE && $td == 'ordernow') {  
						?>
                          <p><?php echo __('You must be logged in to view our specials and advertising prices. Our accounts are free, and take less than 2 minutes to sign-up! Simply '); ?><a href="index.php?tp=signup"><?php echo __('Click Here'); ?></a> <?php echo __('to register for your free account.'); ?> </p>
                          <form name="enter" method="post" action="index.php"><table width="429" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr><td colspan="2">
                        <?php echo __('To login enter your username, usually your website extension, and your password below.'); ?>
                        </td></tr>
                        <tr>
                          <td width="95">
                            &nbsp;<?php echo __('Username'); ?>:</td>
                          <td width="324">
                            <input type="text" name="username" value="">                          </td>
                        </tr>
                        <tr>
                          <td>
                            &nbsp;<?php echo __('Password'); ?>:</td>
                          <td>
                            <input type="password" name="password" value="">                          </td>
                        </tr>
                        
                        <?php if($setupinfo['enableSecondaryPassword'] == '1') { ?>
                        <tr>
                          <td>
                            &nbsp;<?php echo __('Secondary Password'); ?>:</td>
                          <td>
                            <input type="password" name="secondaryPassword" value="">  (<?php echo __("Only if you have one set, otherwise leave blank"); ?>)                        </td>
                        </tr>
                        <?php } ?>
                        <tr>
                          <td>                            <img src="<?php echo $templateFolder; ?>captcha_img.php?np=1" width="90" height="40" border="2"/></td>
                          <td>
                            <input name="randomValidationCode" type="text" value="" size="8">
                            <br />
 <?php echo __('Enter the numbers from the image.'); ?>                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <div align="center">
                              <p>
                                <input type="image" src="<?php echo $templateFolder; ?>images/login_now.gif" name="submit" value="<?php echo __('Login Now'); ?>">
                              </p>
                              <p><?php echo __('Forgotten your login or password?'); ?><a href="index.php?tp=forgotpass"><?php echo __('Click Here'); ?></a> </p>
                            </div></td>
                        </tr>
            </table>
                      
                      
                      
            <table width="315" height="44" align="center">
            <tr>
            <td>
            <p align="center"><br />
             <br />
             <br />
             <?php echo __('Not already a FREE member?'); ?><br />
             <?php echo __('Take 3 Minutes to Sign Up and get started today!'); ?><br />
            <a href="index.php?tp=signup">
            <img src="<?php echo $templateFolder; ?>images/joinButton.png" alt="picture" width="314" height="43" border="0"/>            </a>            </p>
            </td></tr></table>
                            <input type="hidden" name="action" value="Login to my account" />
                            <input type="hidden" name="tp" value="advertise" />
                            <input type="hidden" name="td" value="ordernow" />
                            <input type="hidden" name="packID" value="<?php echo $_REQUEST['packID']; ?>" />
                            <input type="hidden" name="membershipID" value="<?php echo $_REQUEST['membershipID']; ?>" />
                            <input type="hidden" name="adType" value="<?php echo $_REQUEST['adType']; ?>" />
                          </form>
                          <p>&nbsp;</p>
                          <?php
					}
					
					if($td == 'ordernow' && $loginPassed == TRUE && !$ordered) {
						?>
                          <strong><br>
                          <br>
                    <h2><?php echo __('Please select your payment type below.'); ?></h2>
                          </strong>
                          <?php
						  if($_REQUEST['adType'] == 'membership') {
						  	$array = getArray("SELECT * FROM membershiptypes WHERE id = ".quote_smart($_REQUEST['membershipID'])."");
						  } else {
							$array = getArray("SELECT * FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."");
						  }
							?>
						  <script type="text/javascript" language="javascript">
							function updatePaymentId() {
								
								paymentType = document.paymentIDForm.paymentType.value;
								divObj = document.getElementById('paymentAccountDiv');
								if(paymentType == 'account') {
									var content = '<br><?php echo __('This will come from your current account balance of:',false); ?> <?php echo $setupinfo['currency']; ?><?php echo totalEarnings($_SESSION['login']); ?>';
								} else {
									var accountID = '';
									if(paymentType == 'paypal') {
										accountID = '<?php echo __('Your Paypal Email Address'); ?>';
									} else if(paymentType == 'payza') {
										accountID = '<?php echo __('Your Payza Email Address'); ?>';
									} else if(paymentType == 'egold') {
										accountID = '<?php echo __('Your E-Gold Account Number'); ?>';
									} else if(paymentType == 'stormpay') {
										accountID = '<?php echo __('Your Stormpay Email Address'); ?>';
									} else if(paymentType == 'moneybookers') {
										accountID = '<?php echo __('Your Moneybookers Email Address'); ?>';
									} else if(paymentType == 'netpay') {
										accountID = '<?php echo __('Your NetPay Account Number'); ?>';
									} else {
										accountID = '<?php echo __('Account ID'); ?>';
									}
                          			var content = '<br>' + accountID + ': <input type="text" name="paymentAccount"><br><?php echo __('This is so we can match your payment from your account to this order for verification.'); ?>';
								}
								divObj.innerHTML = content;
							}
							
							</script>
                          <form name="paymentIDForm" method="post" action="index.php">
                            <input type="hidden" name="packID" value="<?php echo $_REQUEST['packID']; ?>">
                            <input type="hidden" name="membershipID" value="<?php echo $_REQUEST['membershipID']; ?>">
                            <input type="hidden" name="tp" value="advertise">
                            <input type="hidden" name="td" value="ordernow">
                            <input type="hidden" name="adType" value="<?php echo $_REQUEST['adType']; ?>">
                            <select name="paymentType" onChange="updatePaymentId()">
                              <?php
								$sql = "SELECT confirmegold,confirmstormpay,confirmnetpay,confirmmoneybookers,confirmpaypal,confirmpayza FROM payoptions LIMIT 1";
								$arr = getArray($sql);
								if($arr['confirmpaypal'] == 'yes') echo "<option value='paypal' selected>Paypal (Almost Instant)</option>";
								if($arr['confirmpayza'] == 'yes') echo "<option value='payza' selected>Payza</option>";
								if($arr['confirmmoneybookers'] == 'yes') echo "<option value='moneybookers'>Money Bookers</option>";
								if($arr['confirmnetpay'] == 'yes') echo "<option value='netpay'>Netpay</option>";
								if($arr['confirmstormpay'] == 'yes') echo "<option value='stormpay'>Stormpay</option>";
								if($arr['confirmegold'] == 'yes') echo "<option value='egold'>E-Gold</option>";
								
								$sq = mysql_query("SELECT * FROM customPayments WHERE active = '1' ORDER BY id DESC");
								$cn = mysql_num_rows($sq);
								if($cn > 0) {
									for($i = 0;$i < $cn;$i++) {
										mysql_data_seek($sq,$i);
										$ar = mysql_fetch_array($sq);
										echo "<option value='custom".$ar['id']."'>".$ar['paymentName']."</option>";
									}
								}
								//if($arr['confirmegold'] == 'yes') echo "<option value='egold'>E-Gold</option>";
								
								if($array['pack_price'] <= totalEarnings($_SESSION['login'])) {
									$accCredits = __('Account Credits (Instant)');
									if($array['pack_credits_type'] == 'banner' && $accBanner==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'fbanner' && $accFBanner==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'fad' && $accFAd==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'flinks' && $accFLink==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'signup' && $accSignup==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'email' && $accEmail==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'links' && $accLinks==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'referrals' && $accReferral==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'survey' && $accSurvey==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['pack_credits_type'] == 'ptrad' && $accPtrAd==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($array['packSpecial'] == '1' && $accPacks==1) {
										echo "<option value='account'>$accCredits</option>";
									} else if($_REQUEST['adType'] == 'membership' && $accMemberships==1) {
										echo "<option value='account'>$accCredits</option>";
									}
								}
							?>
                            </select>
                            <div name="paymentAccountDiv" id="paymentAccountDiv"> <br>
                              <?php echo __('Payment Account ID'); ?>:
                                <input type="text" name="paymentAccount">
                                <br>
                                <?php echo __('This is so we can match your payment from your account to this order for verification.'); ?> </div>
                            <br>
								Payment Total: <?php echo $setupinfo['currency'];
								if($_REQUEST['adType'] == 'membership') {
									echo $array['membershipPrice'];
								} else {
									echo $array['pack_price'];
								}?>
								<br />
								Purchase for : <?php 
								if($_REQUEST['adType'] == 'membership') {
									echo $array['membershipName'];
								} else {
									echo $array['pack_name'];
								}
								?><br>
                            <br>
                            <input type="submit" name="Submit" value="<?php echo __('Continue to payment'); ?>">
                          </form>
                          <BR>
                          <?php
					} else if($td == 'ordernow' && $loginPassed == TRUE && $ordered == TRUE) {
						echo __("Thank you for your order.<BR>Your order will process soon. You can check your \"Orders\" section of the back office to view the status of your order at any time.<BR><BR><BR>");
					}
					
					if($td == '' && $act == 'viewInfo' && $_REQUEST['adType'] != 'membership') {
					   ?>
                          <table width="100%" align="center">
                            <tr>
                              <td valign="top">
                                <?php
									 $sql=mysql_query("SELECT fnum, pack_price, pack_name FROM packages WHERE pack_price>0 AND packSpecial>0");
							   $count = mysql_num_rows($sql);
							   
							   for($i = 1; $i  < $count+1;$i++) {
						   		
									mysql_data_seek($sql, $i-1);
									extract(mysql_fetch_array($sql));
									if(visibleAd($fnum)) {
									?>
                                <?php	
									$query = mysql_query("SELECT b.pack_name, b.pack_price FROM packitems a, packages b WHERE a.item = b.fnum AND a.package = ".quote_smart($fnum)."");
									$c = mysql_num_rows($query);
										if($c > 0) {  ?>
                                <table width="100%" cellpadding="10" cellspacing="1" bgcolor="<?php echo $headerColor; ?>">
                                  <tr valign="top" <?php if($_REQUEST['id'] == $fnum) { echo "bgcolor=\"#FFFFCC\""; } else { ?>bgcolor="#FFFFFF"<?php } ?>>
                                    <td width="359" height="261" valign="top"><span style="size: 12px;">
                                  <form name="form1" method="post" action="index.php" style="margin: 0">
                                        <h2><?php echo __($pack_name); ?> <span class="greenFont"><?php echo $setupinfo['currency'].number_format($pack_price,2); ?></span></h2>
                                <br>
                                        <?php
										$value = 0;
												for($k=0; $k<$c; $k++){
													mysql_data_seek($query,$k);
													extract(mysql_fetch_array($query));
													$value += $pack_price;
													if(isset($pack_name)) { ?>
      &nbsp;&nbsp;&bull;&nbsp; <?php echo __($pack_name); ?><BR>
                                        <?php }
												}
												
												?>&nbsp;&nbsp;&bull;&nbsp; <?php echo __('Estimated Value'); ?>: <?php echo $setupinfo['currency'].number_format($value,2); ?>
                                        <br><br>
                                        <input type="hidden" name="packID" value="<?php echo $fnum; ?>">
                                        <input type="hidden" name="tp" value="advertise">
                                        <input type="hidden" name="td" value="ordernow">
                                    <input type="image" name="Submit" value="Buy Now" src="<?php echo $templateFolder; ?>images/order_now.gif" />
                                        <br><br>
                                    </form>
                                    </span></td>
                                  </tr>
                                </table>
                                <?php
										} //if($c > 0) 
										if($i % 2) { echo "</td><td>"; } else { echo "</td></tr><tr><td>"; }
										}
									} //for($i = 0; $i  < $count;$i++) {
						?></td>
                            </tr>
                          </table>
                          <p>&nbsp;</p>
                          <p>
                            <?php
					   }
					   
					    if($td == '' && $act == 'viewInfo' && $_REQUEST['adType'] == 'membership') {
					   ?>
                          <table width="100%" align="center">
                            <tr>
                              <td>
                                <?php
									 $sql=mysql_query("SELECT * FROM membershiptypes WHERE membershipPrice>0 AND active=1");
						   $count = mysql_num_rows($sql);
						   
						   for($i = 1; $i  < $count+1;$i++) {
						   		
									mysql_data_seek($sql, $i-1);
									$membership = mysql_fetch_array($sql);
									extract($membership);

									
									$query = mysql_query("SELECT * FROM membershipitems WHERE membershipID = ".quote_smart($id)."");
									$c = mysql_num_rows($query);
										//if($c > 0) {  ?>
                                <table width="100%" cellpadding="10" cellspacing="1" bgcolor="<?php echo $headerColor; ?>" height="190">
                                  <tr valign="top" <?php if($_REQUEST['id'] == $id) { echo "bgcolor=\"#FFFFCC\""; } else { ?>bgcolor="#FFFFFF"<?php } ?>>
                                    <td width="359" height="215" valign="top"><span style="size: 12px;">
                                      <form name="form1" method="post" action="index.php">
                                        <h2><?php echo $membershipName; ?> <span class="greenFont"><?php echo $setupinfo['currency'].number_format($membershipPrice,2); ?></span></h2><br>
                                        
										<?php
										if($lengthType == 'lifetime') {
											echo "<STRONG>".__('LIFETIME MEMBERSHIP')."</STRONG><BR>";
										} else {
											if($lengthType == 'd') { echo "<STRONG>".__($length." Day Membership.")."</STRONG><BR>"; }
											if($lengthType == 'w') { echo "<STRONG>".__($length." Week Membership.")."</STRONG><BR>"; }
											if($lengthType == 'm') { echo "<STRONG>".__($length." Month Membership.")."</STRONG><BR>"; }
											if($lengthType == 'y') { echo "<STRONG>".__($length." Year Membership.")."</STRONG><BR>"; }
											if($lengthType == '') { echo "<STRONG>".__('1 Month Membership.',false)."</STRONG><BR>"; }
										}
										if($clickBonus > 1) {
											?>&nbsp;&nbsp;&bull;&nbsp; <?php echo number_format(($clickBonus*100) - 100,0); ?>% <?php echo __('Bonus on Paid to Click Earnings'); ?><BR><?php
										}
										if($readadBonus > 1) {
											?>&nbsp;&nbsp;&bull;&nbsp; <?php echo number_format(($readadBonus*100) - 100,0); ?>% <?php echo __('Bonus on Paid to Read Ads Earnings'); ?><BR><?php
										}
										if($signupBonus > 1) {
											?>&nbsp;&nbsp;&bull;&nbsp; <?php echo number_format(($signupBonus*100) - 100,0); ?>% <?php echo __('Bonus on Paid to Sign Up Earnings'); ?><BR><?php
										}
										if($reademailBonus > 1) {
											?>&nbsp;&nbsp;&bull;&nbsp; <?php echo number_format(($reademailBonus*100) - 100,0); ?>% <?php echo __('Bonus on Paid to Read Email Earnings'); ?><BR><?php
										}
										if($takesurveyBonus > 1) {
											?>&nbsp;&nbsp;&bull;&nbsp; <?php echo number_format(($takesurveyBonus*100) - 100,0); ?>% <?php echo __('Bonus on Paid to Take Survey\'s Earnings'); ?><BR><?php
										}
										
										if($setupinfo['ptClickTimer'] != $clickTimer) 
											echo '&nbsp;&nbsp;&bull;&nbsp; -'.__($clickTimer." second Paid to Click Timer!<BR>");
										if($setupinfo['ptReadAdTimer'] != $readadTimer) 
											echo '&nbsp;&nbsp;&bull;&nbsp; -'.__($readadTimer." second Paid to Read Ad's Timer!<BR>");
										if($setupinfo['ptSurveyTimer'] != $takesurveyTimer) 
											echo '&nbsp;&nbsp;&bull;&nbsp; -'.__($takesurveyTimer." second Paid to Take Survey's Timer!<BR>");
										if($setupinfo['ptReadEmailTimer'] != $reademailTimer) 
											echo '&nbsp;&nbsp;&bull;&nbsp; -'.__($reademailTimer." second Paid to Read Email Timer!<BR>");
										
												if($c > 0) {
												for($k=0; $k<$c; $k++){
													mysql_data_seek($query,$k);
													extract(mysql_fetch_array($query));
													$item = $itemID;
													$sq = mysql_query("SELECT pack_name,pack_price FROM packages WHERE fnum = ".quote_smart($item)."");
													$cnt = mysql_num_rows($sq);
													if($cnt > 0) { 
														extract(mysql_fetch_array($sq));
													if(isset($pack_name)) { ?>
      &nbsp;&nbsp;&bull;&nbsp; <?php echo __($pack_name);
	  if($itemLengthType == 'd') echo __(" <strong>Daily</strong>");
	  if($itemLengthType == 'w') echo __(" <strong>Weekly</strong>");
	  if($itemLengthType == 'm') echo __(" <strong>Monthly</strong>");
	  if($itemLengthType == 'y') echo __(" <strong>Yearly</strong>");
	  
	  
	  ?> (<?php echo $setupinfo['currency'].number_format($pack_price, 2); ?> Value)<BR>
                                        <?php } //END ISSET PACKNAME
										} //END IF cnt > 0
												} //END FOR LOOP
												}//END c > 0
												
												
												?>
                                        <br>
                                        <input type="hidden" name="membershipID" value="<?php echo $membership['id']; ?>">
                                        <input type="hidden" name="tp" value="advertise">
                                        <input type="hidden" name="adType" value="membership">
                                        <input type="hidden" name="td" value="ordernow">
                                        <input type="image" src="<?php echo $templateFolder; ?>images/order_now.gif" name="Submit" value="Buy Now">
                                      </form>
                                    </span></td>
                                  </tr>
                                </table>
                                <?php
										//} //if($c > 0) 
										if($i % 2) { echo "</td><td>"; } else { echo "</td></tr><tr><td>"; }
									} //for($i = 0; $i  < $count;$i++) {
						?></td>
                            </tr>
                          </table>
                          <p>&nbsp;</p>
                            <?php
					   }
					   
					   if($td == '' && $act != 'viewInfo') {
						   $sql=mysql_query("SELECT DISTINCT pack_category_name FROM packages WHERE pack_price>0 AND packSpecial<1");
						   $count = mysql_num_rows($sql);
						   if($count>0) {
								?>
                          <form name="adForm" method="post" action="index.php" onsubmit="return buyAdvertising();">
                            <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr bgcolor="#FFFFFF">
                                <td height="44" colspan="3"><h1><?php echo __('Purchase Advertising!');?> </h1>
                                  <p><?php echo __('Advertising with us is easy, simply become a free member, purchase some credits for the ad type you wish to use, create your ad in our easy to use ad manager, and apply some credits to it! It\'s truly that simple to get started.'); ?></p>
                                </td>
                              </tr>
                              <?php
							  
								for($i = 1; $i  < $count+1;$i++) {
									mysql_data_seek($sql, $i-1);
									extract(mysql_fetch_array($sql));
									$dispPack = TRUE;
									$query = mysql_query("SELECT fnum, pack_price,pack_name,pack_credits_type,pack_credits,pack_category_name  FROM packages WHERE pack_category_name = ".quote_smart($pack_category_name)." AND packSpecial<1 ORDER BY pack_price ASC");
									$c = mysql_num_rows($query);
									
									if($c > 0) {
										$dispPack = TRUE;
										//RUN CHECK FOR REFERRALS, IF NOT, DONT DISPLAY SECTION
										if($pack_category_name == 'Referrals') {
											$dispPack = FALSE;
											for($k=0; $k<$c; $k++){
												mysql_data_seek($query,$k);
												extract(mysql_fetch_array($query));
												if(packReferrals($fnum) <= $orphanCount) {
													$dispPack = TRUE;
												}
											}
										}
										if($dispPack == TRUE) {  ?>
											  <tr onMouseOver="this.bgColor='#EFEFEF'" onMouseout="this.bgColor='#FFFFFF'">
												<td width="194" class="arial12pxFontgreenFont"><?php echo __($pack_category_name); ?></td>
												<td width="226"><select name="type<?php echo $i; ?>" class="arial12pxFontgreenFont">
													<?php
													for($k=0; $k<$c; $k++){
														mysql_data_seek($query,$k);
														extract(mysql_fetch_array($query));
														if(isset($pack_name) && visibleAd($fnum)){ 
															echo "<option value=\"$fnum\">".__($pack_name)." ".$setupinfo['currency'].number_format($pack_price, 2)."</option>";
														}
													}
															?>
												</select></td>
												<td width="66"><input <?php if($_SESSION['lang'] == 'en') { ?>type="image" src="<?php echo $templateFolder; ?>images/order_now.gif"<?php } else echo 'type="submit" '; ?> name="Submit" value="<?php echo __('Buy Now'); ?>" onClick="buyAdvertising(<?php echo $i; ?>,'dropDown')"></td>
											  </tr>
											  <?php
							  			}//END if($dispPack == TRUE)
									} //if($c > 0) 
								} //for($i = 0; $i  < $count;$i++) {
								
							   $sql=mysql_query("SELECT fnum, pack_price, pack_name FROM packages WHERE pack_price>0 AND packSpecial>0");
							   $count = mysql_num_rows($sql);
							   if($count>0) {
									//PACK SPECIALS
									?>
                              <tr bgcolor="#FFFFFF">
                                <td height="44" colspan="3"><h2><?php echo __('Advertising Packages'); ?></h2>
                                </td>
                              </tr>
                              <?php
									for($i = 1; $i  < $count+1;$i++) {
									mysql_data_seek($sql, $i-1);
									extract(mysql_fetch_array($sql));
									if(visibleAd($fnum)) {
										$query = mysql_query("SELECT b.pack_name, b.pack_price FROM packitems a, packages b WHERE a.item = b.fnum AND a.package = ".quote_smart($fnum)."");
										$c = mysql_num_rows($query);
										if($c > 0) {  ?>
											
									  <tr valign="top" onMouseOver="this.bgColor='#EFEFEF'" onMouseout="this.bgColor='#FFFFFF'">
										<td width="194" class="arial12pxFontgreenFont" colspan="2"><?php echo __($pack_name); ?> <?php echo $setupinfo['currency'].number_format($pack_price,2); ?> <a href="index.php?tp=advertise&act=viewInfo&adType=Specials&id=<?php echo $fnum; ?>">(<?php echo __('Info'); ?>)</a></td>
										
											<td width="66"><input  <?php if($_SESSION['lang'] == 'en') { ?>type="image" src="<?php echo $templateFolder; ?>images/order_now.gif"<?php } else echo 'type="submit" ';  ?> name="submit" value="<?php echo __('Buy Now'); ?>" onClick="buyAdvertising('Specials','<?php echo $fnum; ?>')"></td>
										  </tr>
										  <?php
										} //if($c > 0) 
									}
									} //for($i = 0; $i  < $count;$i++) {
								}//END IF SPECIALS COUNT > 0
								
								
								
							   $sql=mysql_query("SELECT id,membershipName,membershipPrice,length,lengthType FROM membershiptypes WHERE active = 1 AND membershipPrice>0");
							   $count = mysql_num_rows($sql);
							   if($count>0) {
									//PACK SPECIALS
									?>
                              <tr bgcolor="#FFFFFF">
                                <td height="44" colspan="3"><h2><?php echo __('Memberships'); ?></h2>
                                <?php echo __('Want to earn more or have weekly or daily credits added to your account? Upgrade today and reap the rewards!'); ?></td>
                              </tr>
                              <?php
									for($i = 1; $i  < $count+1;$i++) {
									mysql_data_seek($sql, $i-1);
									extract(mysql_fetch_array($sql));
									if($lengthType == 'd') { 
										$friendlyLength = $length.__(" Day ");
									} else if($lengthType == 'm') { 
										$friendlyLength = $length.__(" Month ");
									} else if($lengthType == 'w') { 
										$friendlyLength = $length.__(" Week ");
									} else if($lengthType == 'y') { 
										$friendlyLength = $length.__(" Year ");
									} else if($lengthType == 'lifetime') { 
										$friendlyLength = __(" <strong>Lifetime</strong> ");
									}
									?>
                              <tr valign="top" onMouseOver="this.bgColor='#EFEFEF'" onMouseout="this.bgColor='#FFFFFF'">
								<td width="194" class="arial12pxFontgreenFont" colspan="2"><?php echo __($membershipName); ?> <?php echo $setupinfo['currency'].number_format($membershipPrice,2); ?> (<?php echo $friendlyLength; ?>) <a href="index.php?tp=advertise&act=viewInfo&adType=membership&id=<?php echo $fnum; ?>">(<?php echo __('Info'); ?>)</a></td>
                                
                                <td width="66">
                                <input <?php if($_SESSION['lang'] == 'en') { ?>type="image" src="<?php echo $templateFolder; ?>images/order_now.gif"<?php } else echo 'type="submit" '; ?> name="submit" value="<?php echo __('Buy Now'); ?>" onClick="buyAdvertising('Memberships',<?php echo $id; ?>)"></td>
                              </tr>
                              <?php
									} //for($i = 0; $i  < $count;$i++) {
								}//END IF MEMBERSHIPS COUNT > 0
									
								
								?>
                              <input type="hidden" name="tp" value="advertise">
                              <input type="hidden" name="td" value="ordernow">
                              <input type="hidden" name="packID" value="<?php echo $_REQUEST['packID']; ?>" />
                              <input type="hidden" name="membershipID" value="<?php echo $_REQUEST['membershipID']; ?>" />
                              <input type="hidden" name="adType" value="<?php echo $_REQUEST['adType']; ?>" />
                            </table>
          </form>
                          <?php
							}//if($count>0) {
							
						} //if($td == '' && $loginPassed == TRUE) {
						
						?>
                          <p>&nbsp;</p>
                          <form name="advertlogin" method="post" action="index.php">
                            <div align="center"><?php echo __('Have an advertisers login to view statistics? Fill out the form below.');?> </div>
                            <table width="338" border="0" align="center" cellpadding="5" cellspacing="2" bgcolor="#FF9900">
                              <tr>
                                <td bgcolor="#FFFFCC"><table width="100%" border="0" align="center" cellpadding="5">
                                    <tr>
                                      <td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Advertisers Login');?>:</font></td>
                                      <td width="50%">
                                        <input type="text" name="adlogin">
                                      </td>
                                    </tr>
                                    <tr>
                                      <td width="50%" height="31"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Advertisers Password');?>:</font></td>
                                      <td width="50%" height="31">
                                        <input type="password" name="adpassword">
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="2">
                                        <div align="center">
                                          <input <?php if($_SESSION['lang'] == 'en') { ?>type="image" src="<?php echo $templateFolder; ?>images/login_now.gif"<?php } else echo 'type="submit" '; ?> name="Submit" value="<?php echo __('Login to advertiser\'s area',false); ?>">
                                          <input type="hidden" name="tp" value="adstats">
                                      </div></td>
                                    </tr>
                                </table></td>
                              </tr>
                            </table>
                          </form>
                          <p>&nbsp;</p>
</p>
<br />
<br />
<table width="440" border="0" align="center" cellpadding="5" cellspacing="2">
<tr><td>
	<?php
	$payMethods = getArray("SELECT * FROM payoptions LIMIT 1");
	if($payMethods['confirmpaypal'] == 'yes') {
		?><img src="./common/images/merchants/paypalSmall.png" border="0" style="margin-left: 15px;" /><?php
	}
	if($payMethods['confirmpayza'] == 'yes') {
		?><img src="./common/images/merchants/payzaSmall.png" border="0" style="margin-left: 15px;" /><?php
	}
	if($payMethods['confirmmoneybookers'] == 'yes') {
		?><img src="./common/images/merchants/skrillSmall.png" border="0" style="margin-left: 15px;" /><?php
	}
	if($payMethods['confirmegold'] == 'yes') {
		?><img src="./common/images/merchants/egoldSmall.png" border="0" style="margin-left: 15px;" /><?php
	}
	?>
</td></tr>
</table>
<?php echo $pageFooter; ?>