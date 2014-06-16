<?php
if(!isset($_SESSION)) session_start();
$_SESSION['randomVerification'] = rand(1000,9999);
?>
<?php echo $pageHeader; ?>

<style type="text/css">
<!--
.style8 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.white14pxBoldArial {	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #FFFFFF;
	font-size: 14px;
}
-->
</style>
<?php

if(isset($_SESSION['login'])) {
	?><BR /><BR /><DIV ALIGN="CENTER">
  <h1 align="left"><STRONG><FONT COLOR=RED><?php echo __('Logged in members can not use the sign-up form...'); ?></FONT></STRONG></h1>
  <p><a href="index.php?tp=user"><?php echo __('Click Here'); ?></a> <?php echo __('to view your members homepage.'); ?></p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p><?php displayBanner(); ?></p>
  <p>&nbsp;</p>
</DIV><?php
} else {
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="box">
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#666666">
      <tr>
        <td align="center" valign="top">
          <table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td bgcolor="#FFFFFF">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td><form name="regform" method="post" action="index.php" style="margin: 0;padding: 0;">
                        <input type="hidden" name="tp" value="register" />
                            
                        <h1><?php echo __('Sign-Up for your FREE Account'); ?></h1>
                        <p>
                        <?php echo __('We offer low withdraw limit\'s, reasonable advertising prices and much more!  Get started today by filling out the signup form below.'); ?><br>
                        </p><?php
						if($error != '') echo "<BR>".$error."<BR>";
						?>
                        <br>
                            <table width="100%" border="0" cellpadding="5" cellspacing="0">
                              <!--DWLayoutTable-->
                              <tr>
                                <td width="20%" valign="top">
                                    *<?php echo __('Username'); ?>:</td><td width="80%">
                                        <input type="text" name="username" value="<?php echo $_REQUEST['username']; ?>" style="width: 300px; height: 28px;">
                               </td></tr><tr><td>
                               
                                <?php echo __('Password'); ?>:</td><td>
                                <input type="password" name="password" value="<?php echo $_REQUEST['password']; ?>" style="width: 300px; height: 28px;">
                                    </td>
                              </tr><?php if($setupinfo['enableSecondaryPassword'] == '1') { ?>
                              <tr><td>
                               
                                <?php echo __('Secondary Password'); ?>:</td><td>
                                <input type="password" name="secondaryPassword" value="<?php echo $_REQUEST['secondaryPassword']; ?>" style="width: 300px; height: 28px;"> <br />
                                <?php echo __('The Secondary Password is Optional, but required at login if not left blank.'); ?>
                                    </td>
                              </tr><?php } //END SECONDARY PASSWORD ENABLE CHECK
							  ?><?php if($setupinfo['enableCashoutPin'] == '1') { ?>
                              <tr><td>
                               
                                <?php echo __('Cashout Pin'); ?>:</td><td>
                                <input type="password" name="cashoutPin" value="<?php echo $_REQUEST['cashoutPin']; ?>" style="width: 300px; height: 28px;"> <br />
                                <?php echo __('The Cashout Pin is used to request withdraw\'s from your account balance.'); ?>
                                    </td>
                              </tr><?php }//END ENABLE CASHOUT PIN CHECK
							   ?>
                            </table>
                              <table border=0  width=100% cellspacing="0" cellpadding="5">
                                <!--DWLayoutTable-->
                                <tr>
                                  <td width="20%" height="26" valign="top">
                                      <input type=hidden name="required_keywords" value="3">
                                      <input type=hidden name="user_form" value="signup">
                                      <input type=hidden name="userform[code]" value="48425a7f">
                                      <input type=hidden name="required" value="username,email,first_name,password">
                                      <?php echo __('E-Mail'); ?>:</td><td width="80%">
                                        <input type="text" name="email" value="<?php echo $_REQUEST['email']; ?>" style="width: 300px; height: 28px;">
                                        </td>
                                </tr>
                                <tr>
                                  <td height="26" valign="top">
                                   
                                      <?php echo __('Full Name'); ?></td><td>
                                        <input type="text" name="name1" value="<?php echo $_REQUEST['name1']; ?>" style="width: 300px; height: 28px;">
                                  </td>
                                </tr>
                                <?php
					
					if($signupAddress == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                   
                                      <?php echo __('Address'); ?></td><td>
                                        <input type="text" name="faddress" value="<?php echo $_REQUEST['faddress']; ?>" style="width: 300px; height: 28px;">
                                      </td>
                                </tr>
                                <?php
					}
					if($signupCity == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                    
                                      <?php echo __('City');?></td><td>
                                        <input type="text" name="fcity" value="<?php echo $_REQUEST['fcity']; ?>" style="width: 300px; height: 28px;">
                                      </td>
                                </tr>
                                <?php
					}
					if($signupState == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                    
                                      <?php echo __('State / Province (if applicable)'); ?></td><td>
                                        <input type="text" name="fstate" value="<?php echo $_REQUEST['fstate']; ?>" style="width: 300px; height: 28px;">
                                      </td>
                                </tr>
                                <?php
					}
					if($signupZip == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                    
                                      <?php echo __('Postal / Zip Code'); ?></td><td>
                                        <input type="text" name="fzip" value="<?php echo $_REQUEST['fzip']; ?>" style="width: 300px; height: 28px;">
                                      </td>
                                </tr>
                                <?php
					}
					if($signupCountry == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                   
                                      <?php echo __('Country'); ?></td><td>
                                        <select name="fcountry" style="width: 300px; height: 28px;">
                                          <?php
								$query = mysql_query("SELECT country FROM countries ORDER BY country ASC");
								$count = mysql_num_rows($query);
								for($i = 0;$i < $count;$i++) {
									mysql_data_seek($query, $i);
									$arr  = mysql_fetch_array($query);
									?>
                                          <option value="<?php echo $arr['country']; ?>"<?php
                                            if($arr['country'] == 'United States') echo " selected=\"selected\"";
											?>><?php echo $arr['country']; ?></option>
                                          <?php } ?>
                                        </select>
                                      </td>
                                </tr>
                                <?php
					}
					if($signupGender == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                    
                                      <?php echo __('Gender'); ?></td><td>
                                        <select name="fgender" style="width: 300px; height: 28px;">
                                          <option value="F" <?php if($_REQUEST['fgender'] == 'F') echo "selected"; ?>><?php echo __('Female'); ?></option>
                                          <option value="M" <?php if($_REQUEST['fgender'] == 'M') echo "selected"; ?>><?php echo __('Male'); ?></option>
                                        </select>
                                      </td>
                                </tr>
                                <?php
					}
					if($signupAge == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                   
                                      <?php echo __('Age'); ?></td><td>
                                        <input type="text" name="fage" value="<?php if($_REQUEST['fage'] != '') echo $_REQUEST['fage']; else echo "18"; ?>" style="width: 300px; height: 28px;">
                                      </td>
                                </tr>
                                <?php
					}
					if($signupAnualIncome == 1) {
						?>
                                <tr>
                                  <td height="26" valign="top">
                                                                         <?php echo __('Anual Income'); ?></td><td>
                                      <select name="fincoming" style="width: 300px; height: 28px;">
                                        <?php
								for($i = 1;$i < 25;$i++) { ?>
                                        <option value="<?php echo $i*2000; ?>" <?php if($_REQUEST['fincoming'] == ($i*2000)) echo "selected"; ?>><?php echo $setupinfo['currency']; ?><?php echo number_format($i*2000,2); ?>/yr</option>
                                        <?php } ?>
                                      </select>
                                    </td>
                                </tr>
                                <?php
					}
				
				?>
                                
                              </table>
                            <div align="left">
                              <p>&nbsp; </p>
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <?php if($signupLanguage == 1) { ?><tr valign="top">
                                  <td colspan="4"> <?php echo __('Please choose the languages you speak'); ?> </td>
                                </tr>
                                <tr valign="top">
                                  <td><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
                                      <input type="checkbox" name="nlang1" value="1" <?php if($lang1==1)echo"checked"?> />
                                    English<br />
                                    <input type="checkbox" name="nlang2" value="1"  <?php if($lang2==1)echo"checked"?> />
                                    German<br />
                                    <input type="checkbox" name="nlang3" value="1" <?php if($lang3==1)echo"checked"?> />
                                    France<br />
                                    <input type="checkbox" name="nlang4" value="1" <?php if($lang4==1)echo"checked"?> />
                                    Italian<br />
                                    <input type="checkbox" name="nlang5" value="1" <?php if($lang5==1)echo"checked"?> />
                                    Chinese<br />
                                    <br />
                                  </font></div></td>
                                  <td colspan="3"><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
                                      <input type="checkbox" name="nlang6" value="1" <?if($lang6==1)echo"checked"?> />
                                    Poland<br />
                                    <input type="checkbox" name="nlang7" value="1" <?if($lang7==1)echo"checked"?> />
                                    Romanian<br />
                                    <input type="checkbox" name="nlang8" value="1" <?if($lang8==1)echo"checked"?> />
                                    Russian<br />
                                    <input type="checkbox" name="nlang9" value="1" <?if($lang9==1)echo"checked"?> />
                                    Greece<br />
                                    <input type="checkbox" name="nlang10" value="1" <?if($lang10==1)echo"checked"?> />
                                    Other</font></div></td>
                                </tr>
                                <?php }
								if($signupInterests == 1) {
								?>
                                <tr valign="top">
                                  <td colspan="4"><div align="left"><?php echo __('Please check some of your interests'); ?> </div></td>
                                </tr>
                                <tr valign="top">
                                  <td><div align="left">
                                      <input type="checkbox" name="fintarts" value="1" <?php if($_REQUEST['fintarts'] == 1) echo 'checked="checked"'; ?>/>
                                    <?php echo __('Arts'); ?><br />
                                    <input type="checkbox" name="fintauto" value="1" <?php if($_REQUEST['fintauto'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Automotive'); ?><br />
                                    <input type="checkbox" name="fintbusiness" value="1" <?php if($_REQUEST['fintbusiness'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Business'); ?><br />
                                    <input type="checkbox" name="fintcomputers" value="1" <?php if($_REQUEST['fintcomputers'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Computers'); ?><br />
                                    <input type="checkbox" name="finteducation" value="1" <?php if($_REQUEST['finteducation'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Education'); ?><br />
                                    <input type="checkbox" name="fintentertainment" value="1" <?php if($_REQUEST['fintentertainment'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Entertainment'); ?><br />
                                    <input type="checkbox" name="fintfinancial" value="1" <?php if($_REQUEST['fintfinancial'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Financial'); ?></div></td>
                                  <td><div align="left">
                                      <input type="checkbox" name="fintgames" value="1" <?php if($_REQUEST['fintgames'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Games'); ?><br />
                                    <input type="checkbox" name="finthealth" value="1" <?php if($_REQUEST['finthealth'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Health'); ?><br />
                                    <input type="checkbox" name="finthome" value="1" <?php if($_REQUEST['finthome'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Home'); ?><br />
                                    <input type="checkbox" name="fintinternet" value="1" <?php if($_REQUEST['fintinternet'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Internet'); ?><br />
                                    <input type="checkbox" name="fintintnews" value="1" <?php if($_REQUEST['fintintnews'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('News'); ?><br />
                                    <input type="checkbox" name="fintmedia" value="1" <?php if($_REQUEST['fintmedia'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Media'); ?><br />
                                    <input type="checkbox" name="fintrecreation" value="1" <?php if($_REQUEST['fintrecreation'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Recreation'); ?> </div></td>
                                  <td colspan="2"><div align="left">
                                      <input type="checkbox" name="fintreference" value="1" <?php if($_REQUEST['fintreference'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Reference'); ?><br />
                                    <input type="checkbox" name="fintsearch" value="1" <?php if($_REQUEST['fintsearch'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Search'); ?><br />
                                    <input type="checkbox" name="finttechnology" value="1" <?php if($_REQUEST['finttechnology'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Technology'); ?><br />
                                    <input type="checkbox" name="fintsocial" value="1" <?php if($_REQUEST['fintsocial'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Social'); ?> <br />
                                    <input type="checkbox" name="fintsports" value="1" <?php if($_REQUEST['fintsports'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Sports'); ?><br />
                                    <input type="checkbox" name="finttravel" value="1" <?php if($_REQUEST['finttravel'] == 1) echo 'checked="checked"'; ?> />
                                    <?php echo __('Travel'); ?></div></td>
                                </tr>
                                <?php
					
								}
								?>
                              </table>
                              <p><img src="<?php echo $templateFolder; ?>captcha_img.php?np=1" width="90" height="40" border="2" />
                                    <input name="randomValidationCode" type="text" value="" size="8">
                                    <br />
                                <?php echo __('Enter the numbers from the image for human validation'); ?>.</p>
                              <p><span class="style8">
                                <?php if($_SESSION['refer'] != '') echo __("Referred by").": ".$_SESSION['refer']; ?>
                                  <br>
                                  <input type="checkbox" name="agree" value="1">
                                <?php echo __('I agree with'); ?> <?php echo "$ptrname";?> <a href="index.php?tp=terms" target="_blank"><?php echo __('Terms and conditions'); ?></a><br>
                                <input type="hidden" name="tp" value="register">
                                </span><br>
                                <input type="submit" value="<?php echo __('Signup Now'); ?>" name="submit">
                                <br>
                                </p>
                            </div>
                        </form></td>
                      </tr>
                    </table>
                    <br></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
}	//END LOGIN CHECK
?>
<?php echo $pageFooter; ?>