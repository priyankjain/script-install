<?php echo $pageHeader; ?>
<h1><?php echo __('Edit Account Information'); ?></h1>
<p><?php
if(!isset($_SESSION)) exit("Invalid Login Details..."); 

if($_SESSION['login'] == '') exit("Invalid Login Details...");

if($act=='change')
{
	// Checking form-data
	if(!$_REQUEST['email']) {
		echo __("<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT e-mail! Please click 'BACK' button and try again...");
	} else if(!$_REQUEST['name1']) {
		echo __("<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT first name! Please click 'BACK' button and try again...");
	} else {
		$noerrors=1;
	}
if($noerrors) {
if(!$_REQUEST['nlang1']) $_REQUEST['nlang1']=0;
if(!$_REQUEST['nlang2'])$_REQUEST['nlang2']=0;
if(!$_REQUEST['nlang3'])$_REQUEST['nlang3']=0;
if(!$_REQUEST['nlang4'])$_REQUEST['nlang4']=0;
if(!$_REQUEST['nlang5'])$_REQUEST['nlang5']=0;
if(!$_REQUEST['nlang6'])$_REQUEST['nlang6']=0;
if(!$_REQUEST['nlang7'])$_REQUEST['nlang7']=0;
if(!$_REQUEST['nlang8'])$_REQUEST['nlang8']=0;
if(!$_REQUEST['nlang9'])$_REQUEST['nlang9']=0;
if(!$_REQUEST['nlang10'])$_REQUEST['nlang10']=0;
if(!$_REQUEST['incoming'])$_REQUEST['incoming']=0;
$ch_base="
UPDATE
	users
SET
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
	paidEmails=".quote_smart($_REQUEST['paidEmails'])."
	";
	if($_REQUEST['secondaryPassword'] != '**********') {
		$ch_base .= ",
		secondaryPassword=".quote_smart($_REQUEST['secondaryPassword'])."";
	}
	if($_REQUEST['cashoutPin'] != '****') {
		$ch_base .= ",
		cashoutPin=".quote_smart($_REQUEST['cashoutPin'])."";
	}
	$ch_base .= "
	WHERE
	username=".quote_smart($_SESSION['login'])."";
	mysql_query($ch_base) or die(mysql_error());
	if(mysql_affected_rows()) echo __("<b>Your data has been changed successfully...</b>");
	if($_REQUEST['password'] != '') {
		if($_REQUEST['confirmpassword'] != $_REQUEST['password']) {
			echo __("ERROR - The passwords you entered do not match and could not be updated for your account.");
		} else {
			
			if($setupinfo['enableMd5Passwords'] == '1') {
				$pass = md5($_REQUEST['password']);
			} else {
				$pass = $_REQUEST['password']; 
			}
			mysql_query("UPDATE users SET `fpassword` = ".quote_smart($pass)." WHERE username=".quote_smart($_SESSION['login'])."");
			if(mysql_affected_rows()) echo __("<BR><b>Your password has been changed successfully...</b>");
		}
	}
}

}
	$sql=mysql_query("SELECT * FROM users WHERE username=".quote_smart($_SESSION['login'])."");
	$arr=mysql_fetch_array($sql);
	extract($arr);
?>
<form name="changeprofile" method="post" action="index.php">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="box">
    <tr>
      <td height="44" bgcolor="#FFFFFF">
            <table width="100%" border="0" cellspacing="3" cellpadding="3">
              <tr valign="top">
                <td width="61%"><h2><?php echo __('Account Info'); ?></h2>
                  <table border=0  width=100% cellspacing="0" cellpadding="5" align="center">
                    <tr>
                     <td width="424" valign="top"><?php echo __('Sponsor'); ?>:</td>
                      <td width="743" valign="top">
                      <?php if ($frefer==NULL)
{
echo __('None (Admin)');
}
else
{
echo $frefer;
}
?>
                     </td>
                    </tr>
                    <tr>
                      <td width="424" valign="top">
<?php echo __('Referral URL'); ?>:</td>
                      <td valign="top">
                        <?php echo '<input type="text" name="userRefURL" value="';
						 $setupinfo['ptrurl'] = prepURL($setupinfo['ptrurl']);
						 
						 echo $setupinfo['ptrurl'];
						echo "index.php?refer=".$_SESSION['login'].'" readonly="readonly" size="45">'; ?> <br />
                        <?php echo __('Give out this link to refer others to your system'); ?>                    <input type=hidden name=required_keywords value=3>
                    <input type=hidden name=user_form value=signup>
                    <input type=hidden name=userform[code] value=48425a7f>
                    <input type=hidden name=required value='username,email,first_name,last_name,address,city,zipcode,country,password'></td>
                    </tr><tr>
                      <td width="424" valign="top">
<?php echo __('Username'); ?>:</td>
                      <td width="743" valign="top">
                        <?php echo "<b>".$_SESSION['login']."</b>"; ?>
                      (<span class="font12pxSize fontArialType" style="width: 100%; height: 200px; overflow: auto;"><?php echo __('Member since');?> <?php echo $regdate; ?></span>)</td>
                    </tr>
                    <?php if($setupinfo['enableSecondaryPassword'] == '1') { ?>
					<tr>
                      <td width="424"><?php echo __('Secondary Password'); ?>:</td>
                      <td>
                        <input name="secondaryPassword" type="text" value="**********">
                      </td>
                    </tr>
                    
                    <?php } //END ENABLE SECONDARY PASSWORD CHECK
					?>
                    <?php if($setupinfo['enableCashoutPin'] == '1') { ?>
					<tr>
                      <td width="424"><?php echo __('Cashout Pin'); ?>:</td>
                      <td>
                        <input name="cashoutPin" type="text" value="****">
                      </td>
                    </tr>
                    <?php } //END ENABLE CASHOUT PIN CHECK
					?>
					<tr>
                      <td width="424"><?php echo __('E-Mail'); ?>:</td>
                      <td>
                        <input name="email" type="text" value="<?php echo "$femail"?>">
                      </td>
                    </tr>
                    <tr>
                      <td width="424"><?php echo __('Full  Name'); ?>:</td>
                      <td>
                        <input type="text" name="name1" value="<?php echo "$fname1"?>">
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
					?><?php
				   if($signupCountry == 1) {
				   ?> <tr>
                      <td width="424" height="33"><?php echo __('Country'); ?>:</td>
                      <td height="33">
                        <select name="country">
                          <?php
	$sql=mysql_query("SELECT * FROM countries ORDER BY country") or die(mysql_error());
	$rows=mysql_num_rows($sql);
	for($i=0;$i<$rows;$i++)
	{
	mysql_data_seek($sql,$i);
	$arr=mysql_fetch_array($sql);
	extract($arr);
	echo"<option value='$country' ";  if($country==$fcountry)echo'selected'; echo">$country</option>";}
			  ?>
                        </select>
                      </td>
                    </tr>
					<?php
					}
					if($signupGender == 1) {
					?>
                    <tr>
                      <td width="424"><?php echo __('Gender'); ?>:</td>
                      <td>
                        <select name="gender">
                          <option <?php if($fgender=='Male')echo"selected"; ?>><?php echo __('Male'); ?></option>
                          <option <?php if($fgender=='Female')echo"selected"; ?>><?php echo __('Female'); ?></option>
                        </select>
                      </td>
                    </tr>
					<?php
					}
					if($signupAge == 1) {
					?>
                    <tr>
                      <td width="424"><?php echo __('Age'); ?>:</td>
                      <td>
                        <input type="text" name="age" size="2" value="<?php echo  $fage?>">
            years old</td>
                    </tr>
					<?php
					}
					if($signupAnualIncome == 1) {
					?>
                    <tr>
                      <td width="424"><?php echo __('Anual incoming'); ?>:</td>
                      <td>
                        <input type="text" name="incoming" size="5" value="<?php echo  $fincoming?>">
            <?php echo $setupinfo['currency']; ?> </td>
                    </tr>
					<?php
					}
					?>
					<tr<?php if($_REQUEST['highlightPaidEmails'] == '1') echo ' bgcolor="#FFFFCC"';?>><td<?php if($_REQUEST['highlightPaidEmails'] == '1') echo ' bgcolor="#FFFFCC"';?>>Receive paid emails</td><td<?php if($_REQUEST['highlightPaidEmails'] == '1') echo ' bgcolor="#FFFFCC"';?>><input type="radio" name="paidEmails" value="0" <?php if($paidEmails == '0') { echo "checked"; } ?>>
					No 
					    <input type="radio" name="paidEmails" value="1" <?php if($paidEmails == '1') { echo "checked"; } ?>>
					    Yes</td>
					</tr>
                    <?php
					if($signupLanguage == 1) {
					?><tr>
                      <td width="424" valign="top" height="139"><?php echo __('Language preference'); ?></td>
                      <td height="139">
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
					<?php
					}
					if($signupInterests == 1) {
					?>
                    <tr valign="top">
                      <td colspan=2 align=center><table border="0" cellspacing="0" cellpadding="5" width="100%">
                            <tr valign="top">
                              <td colspan="3"> <h2><?php echo __('Interests'); ?></h2></td>
                            </tr>
                            <tr valign="top">
                              <td>
                                <input type=checkbox name=int1 value=1 <?if($fintarts=='1') echo"checked"?>>
                  <?php echo __('Arts'); ?><br>
                  <input type=checkbox name=int2 value=1 <?if($fintauto=='1') echo"checked"?>>
                  <?php echo __('Automotive'); ?><br>
                  <input type=checkbox name=int3 value=1 <?if($fintbusiness=='1') echo"checked"?>>
                  <?php echo __('Business'); ?><br>
                  <input type=checkbox name=int4 value=1 <?if($fintcomputers=='1') echo"checked"?>>
                  <?php echo __('Computers'); ?><br>
                  <input type=checkbox name=int5 value=1 <?if($finteducation=='1') echo"checked"?>>
                  <?php echo __('Education'); ?><br>
                  <input type=checkbox name=int6 value=1 <?if($fintentertainment=='1') echo"checked"?>>
                  <?php echo __('Entertainment'); ?><br>
                  <input type=checkbox name=int7 value=1 <?if($fintfinancial=='1') echo"checked"?>>
                  <?php echo __('Financial'); ?><br></td>
                              <td>
                                <input type=checkbox name=int8 value=1 <?if($fintgames=='1') echo"checked"?>>
                  <?php echo __('Games'); ?><br>
                  <input type=checkbox name=int9 value=1 <?if($finthealth=='1') echo"checked"?>>
                  <?php echo __('Health'); ?><br>
                  <input type=checkbox name=int10 value=1 <?if($finthome=='1') echo"checked"?>>
                  <?php echo __('Home'); ?><br>
                  <input type=checkbox name=int11 value=1 <?if($fintinternet=='1') echo"checked"?>>
                  <?php echo __('Internet'); ?><br>
                  <input type=checkbox name=int12 value=1 <?if($fintnews=='1') echo"checked"?>>
                  <?php echo __('News'); ?><br>
                  <input type=checkbox name=int13 value=1 <?if($fintmedia=='1') echo"checked"?>>
                  <?php echo __('Media'); ?><br>
                  <input type=checkbox name=int14 value=1 <?if($fintrecreation=='1') echo"checked"?>>
                  <?php echo __('Recreation'); ?><br> </td>
                              <td>
                                <input type=checkbox name=int15 value=1 <?if($fintreference=='1') echo"checked"?>>
                  <?php echo __('Reference'); ?><br>
                  <input type=checkbox name=int16 value=1 <?if($fintsearch=='1') echo"checked"?>>
                  <?php echo __('Search'); ?><br>
                  <input type=checkbox name=int17 value=1 <?if($finttechnology=='1') echo"checked"?>>
                  <?php echo __('Technology'); ?><br>
                  <input type=checkbox name=int18 value=1 <?if($fintsocial=='1') echo"checked"?>>
                  <?php echo __('Social'); ?><br>
                  <input type=checkbox name=int19 value=1 <?if($fintsports=='1') echo"checked"?>>
                  <?php echo __('Sports'); ?><br>
                  <input type=checkbox name=int20 value=1 <?if($finttravel=='1') echo"checked"?>>
                  <?php echo __('Travel'); ?><br></td>
                            </tr>
                        </table></td>
                    </tr>
					<?php
					}
					?>
                    <tr valign="top">
                      <td colspan=2 align=center>
                        <table width="100%" border="0" cellpadding="5" cellspacing="0">
                          <tr>
                            <td colspan="2"> <h2><?php echo __('Payout Info'); ?></h2></td>
                          </tr>
                          <tr>
                            <td width="27%"> <?php echo __('Payout method'); ?>:</td>
                            <td width="73%">
                                <select name=paymethod>
                                  <option value="" selected><?php echo __('Select a payment method'); ?> </option>
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
                                </select>                            </td>
                          </tr>
                          <tr>
                            <td><?php echo __('Payout  account ID'); ?>:</td>
                            <td>
                                <input type="text" size="25" name="payacc" value="<?php echo $fpayacc; ?>">                            </td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellpadding="5" cellspacing="0">
                          <tr>
                            <td colspan="2"> <h2><?php echo __('Change your Password'); ?></h2></td>
                          </tr>
                          <tr>
                            <td width="27%"><?php echo __('New Password'); ?>:</td>
                            <td width="73%">
                              <input type="password" name="password" value="">
                            </td>
                          </tr>
                          <tr>
                            <td><?php echo __('Confirm New Password'); ?>:</td>
                            <td>
                              <input type="password" name="confirmpassword" value="">
                            </td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                        </table>
                        <br>
                        <input type="submit" value="         <?php echo __('Update my profile!'); ?>        " name="submit">
                        <input type="hidden" name="tp" value="edit">
                        <input type="hidden" name="s" value="2">
                        <input type="hidden" name="act" value="change">
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
</td>
    </tr>
  </table>
</form>

<h3><?php echo __('Your '.$setupinfo['currencyName'].' earnings'); ?></h3>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="box">
  <tr>
    <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="5" cellspacing="1">
      <!--DWLayoutTable-->
      <?php
      
$sql = mysql_query("SELECT SUM(famount) AS totalSignupBonus FROM `debit` WHERE `debitFor` = 'signupBonus' AND fid = ".quote_smart($username)." AND `type` = 'usd'");
$count = mysql_num_rows($sql);
if($count > 0) {
	$ar = mysql_fetch_array($sql);
	$totalSignupBonus = abs($ar['totalSignupBonus']);
} else {
	$totalSignupBonus = '0.00';
}
if($totalSignupBonus != '0.00') {
	  ?><tr>
        <td width="687" height="29" valign="top"><?php echo __('Sign-up Bonus'); ?>:</td>
        <td width="246" valign="top"><?php echo $setupinfo['currency']; ?><?php
//echo"$subonus";
echo number_format($totalSignupBonus,5);
?>        </td>
      </tr>
      <?php
	  }
	  ?>
      <tr>
        <td height="29" valign="top"><?php echo __('Paid Email Clickthrus'); ?>: <br>
        </td>
        <td valign="top"><?php echo $setupinfo['currency'];  echo number_format($ftmreads,5);?> </td>
      </tr>
	  <tr>
        <td height="29" valign="top"><?php echo __('Paid To Read Ads'); ?>: <br>
        </td>
        <td valign="top"><?php echo $setupinfo['currency']; echo number_format($ftmptrad,5);?> </td>
      </tr>
      <tr>
        <td height="29" valign="top"><?php echo __('Paid Clicks'); ?>:</td>
        <td valign="top"><?php echo $setupinfo['currency']; echo number_format($ftmclicks,5);?> </td>
      </tr>
      <tr>
        <td height="29" valign="top"><?php echo __('Paid Surveys'); ?>:</td>
        <td valign="top"><?php echo $setupinfo['currency']; echo number_format($ftmsurveys,5); ?> </td>
      </tr>
      <tr>
        <td height="29" valign="top"><?php echo __('Paid Signups'); ?>:</td>
        <td valign="top"><?php echo $setupinfo['currency'];
		 echo number_format($ftmregs,5);?> </td>
      </tr>
      <tr>
        <td height="29" valign="top"><b><?php echo __('Total'); ?>:</b></td>
        <td valign="top"><?php echo $setupinfo['currency']; ?><?php
	 $total=$ftmclicks+$ftmreads+$ftmptrad+$ftmregs+$ftmsurveys+$totalSignupBonus;
	// $total = totalEarnings($_SESSION['login']);
	  $total1=$total;
	  echo number_format($total,5);
	  ?>        </td>
      </tr>
    </table>      </td>
  </tr>
</table>
<br>
<h3><?php echo __('Your <strong>'.$setupinfo['pointsName'].'s</strong> earnings'); ?></h3>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
  <tr>
    <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr bordercolor="#999999" bgcolor="fafafa">
        <td width="73%"><?php echo __('Points Email Clickthrus'); ?>:<br>        </td>
        <td width="27%"> <?php
        echo number_format($ftotalreads,0);?> <?php echo $setupinfo['pointsName']; ?>s</td>
      </tr>
      <tr bordercolor="#999999" bgcolor="fafafa">
        <td width="73%"><?php echo __('Points Clicks'); ?>:</td>
        <td width="27%"> <?php
        echo  number_format($ftotalclicks,0);?> <?php echo $setupinfo['pointsName']; ?>s</td>
      </tr>
      <tr bordercolor="#999999" bgcolor="fafafa">
        <td width="73%"><?php echo __('Points Signups'); ?>:</td>
        <td width="27%"> <?php
        echo  number_format($ftotalregs,0);
		?> <?php echo $setupinfo['pointsName']; ?>s</td>
      </tr>
      <tr>
        <td><b><?php echo __('Total Personal '.$setupinfo['pointsName'].'s'); ?></b></td>
        <td>
          <?php
          $totalpoints=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad;
		  echo  number_format($totalpoints,0)." ".$setupinfo['pointsName'].'s';
		  ?>        </td>
      </tr>
      <tr bordercolor="#999999">
        <td><b><?php echo __('Total '.$setupinfo['pointsName'].'s'); ?></b></td>
        <td>
          <?php
          $totalpoints=$ftotalclicks+$ftotalreads+$ftotalregs+$ftotalsurveys+$ftotalptrad+$totalref2; echo  number_format($totalpoints,0)." ".$setupinfo['pointsName'].'s';
		  ?>        </td>
      </tr>
      <tr bordercolor="#999999">
        <td colspan="2"><font color="#FF0000" size="1"><?php echo __('*Note: All '.$setupinfo['pointsName'].'s will be converted to '.$setupinfo['currencyName'].' automatically monthly... '); ?></font></td>
      </tr>
    </table></td>
  </tr>
</table>
<br>
<h3><?php echo __('Your <strong>referrals</strong> earnings'); ?></h3>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="box">
  <tr>
    <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="5" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="19" colspan="3" valign="top"><div align="center">
            <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#666666">
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>

                      <td width="50%" valign="top" colspan="2">
                        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" bordercolor="#999999">
                          <tr>
                            <td colspan="2"><div align="left"><?php echo __('Your Referrals Count'); ?>:</div></td>
                          </tr>
                          <?php
						  $totalRefs = 0;
						  for($i = 1;$i <= $levels;$i++) {
						  	$tier = $i;
							if($i == 1) $field = 'frefer'; else $field = 'frefer'.$tier;
							$count = getValue("SELECT COUNT(fid) FROM users WHERE ".$field."=".quote_smart($_SESSION['login'])."");
							?> <tr><td width='73%'>Tier <?php echo $tier; ?> Referrals Count:</td>
                          <td width='27%'> <font size=1> <?php
							echo number_format($count,0);
							if($count > 0) echo "&nbsp;(<a href='index.php?tp=referrals&tier=".$tier."' target='blank'>".__('view')."</a>)";
							$totalRefs = $count + $totalRefs;
							?> </font></td>
                          </tr><tr><td colspan="2"> <hr color="#EFEFEF" width="95%" /> </td></tr><?php
						  }
	?>
                      </table></td>
                    </tr>
                  </table>
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" bordercolor="#999999">
                      <tr>
                        <td width="73%" height="24"><b><?php echo __('Total <font color="#993300">cash</font> referral bonuses:'); ?></b></td>
                        <td width="27%" height="24"><?php echo $setupinfo['currency']; ?><?php
			$totalref = abs(number_format(getValue("SELECT SUM(famount) FROM `debit` WHERE fid = ".quote_smart($_SESSION['login'])." AND debitFor LIKE '%RefBonus' AND `type` = 'usd'"),5,".",""));
	echo  number_format($totalref,5);
	?>                        </td>
                      </tr>
                      <tr>
                        <td width="73%" height="24"><b><?php echo __('Total <font color="#993300">'.$setupinfo['pointsName'].'s</font> referral bonuses');?>:</b></td>
                        <td width="27%" height="24">
   <?php
			$totalref = abs(number_format(getValue("SELECT SUM(famount) FROM `debit` WHERE fid = ".quote_smart($_SESSION['login'])." AND debitFor LIKE '%RefBonus' AND `type` = 'points'"),5,".",""));
	echo  number_format($totalref,5);
	?>
            <?php echo $setupinfo['pointsName']; ?>s </td>
                      </tr>
                  </table></td>
              </tr>
            </table>
          </div></td>
        </tr>
        <tr>
          <td width="26%" height="1"></td>
          <td width="47%"></td>
          <td width="27%"></td>
        </tr>
        <tr>
          <td height="21" colspan="3" valign="top">
            <h3><?php echo __('DEBIT HISTORY'); ?></h3>
            <table width="100%" border="0" cellspacing="0">
              <tr>
                <td width="17%"><b><?php echo __('Date'); ?></b></td>
                <td width="56%"><b><?php echo __('Debit For'); ?></b></td>
                <td width="27%"><b><?php echo __('Amount'); ?></b></td>
              </tr>
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
		@$sq=mysql_query("SELECT * FROM debit WHERE fid=".quote_smart($_SESSION['login'])." AND `type` = 'usd' AND `debitFor` IN (".$debitForList.") ORDER BY fdate DESC LIMIT 15");
		@$rows=mysql_num_rows($sq);
		$totalRows = getValue("SELECT COUNT(fid) FROM debit WHERE fid=".quote_smart($_SESSION['login'])." AND `type` = 'usd' AND `debitFor` IN (".$debitForList.")");

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
			$debitFor = __($debitFor);
			if(number_format($famount,7) < 0) {
				$amount = "<FONT COLOR=GREEN><STRONG>+</STRONG></FONT>";
			} else {
				$amount = "<FONT COLOR=RED><STRONG>-</STRONG></FONT>";
			}
			echo"<tr><td>$fdate </td><td> ".$debitFor." </td><td> ".$amount.$setupinfo['currency'].number_format(abs($famount),7)."</td></tr>";
			
			$totamount=$totamount+$famount;
		}
		?>
          </table><?php echo __('There are currently '.$totalRows.' transactions for your account.'); ?></td>
        </tr>
        <tr>
          <td height="21" colspan="3" valign="top">
            <h3><?php echo __('WITHDRAW HISTORY'); ?></h3>
            <table width="100%" border="0" cellspacing="0">
              <tr>
                <td width="17%"><b><?php echo __('Date'); ?></b></td>
                <td width="56%"><b><?php echo __('Payment To'); ?></b></td>
                <td width="27%"><b><?php echo __('Amount'); ?></b></td>
              </tr>
              <?php
			  
		@$sq=mysql_query("SELECT * FROM payrequest WHERE username=".quote_smart($_SESSION['login'])." AND `paidOut` = '1' ORDER BY fdate DESC LIMIT 10");
		@$rows=mysql_num_rows($sq);
		for($i=0;$i<$rows;$i++)
		{
			mysql_data_seek($sq,$i);
			$ar=mysql_fetch_array($sq); extract($ar);
			echo"<tr><td>$fdate </td><td> ".$payout_account." (".$payout_method.") </td><td> ".$setupinfo['currency'].number_format(abs($famount),7)."</td></tr>";
			
		}
		?>
          </table></td>
        </tr>
        <tr>
          <td height="1"></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td valign="top" colspan="2"><h3><?php echo __('EARNINGS SUMMARY TOTALS'); ?></h3></td>
          
          <td valign="top"> <?php echo __('Total Referrals'); ?>: <?php echo  number_format($totalRefs,0).""; ?><br />
            <?php echo __('Total '.$setupinfo['currencyName'].''); ?>: <?php echo $setupinfo['currency']; ?>
  <?
	//$totaltotal=$total1 + $totalref - $totamount;
	echo  number_format(totalEarnings($_SESSION['login']),5);
	?>
            <br>
        <?php echo __('Total '.$setupinfo['pointsName'].'s'); ?>: <?php echo  number_format(userPoints($_SESSION['login']),5).""; ?> <br /></td></tr>
    </table></td>
  </tr>
</table>
</p>
<?php echo $pageFooter; ?>