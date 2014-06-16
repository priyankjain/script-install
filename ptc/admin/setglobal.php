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

include("mod_ini.php");

$adTypes = 3;
$ads = array();
$ads['pts'] = array('name'=>'Paid to Sign-Up','disable'=>'disablePTS');
$ads['ptr'] = array('name'=>'Paid to Read Email','disable'=>'disablePTEMAIL');
$ads['ptrad'] = array('name'=>'Paid to Read Ads','disable'=>'disablePTR');
$ads['ptsurvey'] = array('name'=>'Paid to Take Surveys','disable'=>'disablePTSURVEY');
$ads['ptc'] = array('name'=>'Paid to Click','disable'=>'disablePTC');


if($act == 'addNewTemplate') {
	if($_REQUEST['templateName'] == '') {
		displayError("MUST ENTER A TEMPLATE DISPLAY NAME");
	} else if($_REQUEST['templateIdentifier'] == '') {
		displayError("MUST ENTER A TEMPLATE FOLDER NAME");
	} else if(getValue("SELECT COUNT(id) FROM `templates` WHERE templateName = ".quote_smart($_REQUEST['templateName'])."") > 0) {
		displayError("A TEMPLATE WITH THIS NAME ALREADY EXISTS");
	} else if(getValue("SELECT COUNT(id) FROM `templates` WHERE templateIdentifier = ".quote_smart($_REQUEST['templateIdentifier'])."") > 0) {
		displayError("A TEMPLATE WITH THIS FOLDER NAME ALREADY EXISTS");
	} else if(!is_dir("../templates/".$_REQUEST['templateIdentifier'])) {
		displayError("ERROR: FOLDER NOT FOUND! Please make sure /templates/".$_REQUEST['templateIdentifier']." IS THE LOCATION OF YOUR TEMPLATE.");
	} else {
		if($demoMode === TRUE) {
			echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
		} else {
			mysql_query("INSERT INTO `templates` (templateName, templateIdentifier, active) VALUES (".quote_smart($_REQUEST['templateName']).", ".quote_smart($_REQUEST['templateIdentifier']).", ".quote_smart($_REQUEST['active']).")");
		}
		displaySuccess("Your template has been added.");
	}
}
if($act=='chglobal') {
	if($demoMode === TRUE) {
		echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
	} else {
		if(getValue("SELECT COUNT(id) FROM `templates` WHERE templateIdentifier = ".quote_smart($_REQUEST['siteTemplate'])."") > 0 && $_REQUEST['siteTemplate'] != getDefaultTemplate()) {
			mysql_query("UPDATE `templates` SET `active` = '0'");//DE ACTIVATE ALL TEMPLATES
			mysql_query("UPDATE `templates` SET `active` = '1' WHERE templateIdentifier = ".quote_smart($_REQUEST['siteTemplate'])."");//ACTIVATE ONLY THE ONE WE WANT
		}
		
		$query = "UPDATE setupinfo SET
		paidEmails=".quote_smart($_REQUEST['paidEmails']).",
		refContestActive=".quote_smart($_REQUEST['refContestActive']).",
		refContestRecurring=".quote_smart($_REQUEST['refContestRecurring']).",
		refContestLength=".quote_smart($_REQUEST['refContestLength']).",
		pts_pay_type=".quote_smart($_REQUEST['pts_pay_type']).",
		pts_pay_amount=".quote_smart($_REQUEST['pts_pay_amount']).",
		ptr_pay_type=".quote_smart($_REQUEST['ptr_pay_type']).",
		ptr_pay_amount=".quote_smart($_REQUEST['ptr_pay_amount']).",
		ptc_pay_type=".quote_smart($_REQUEST['ptc_pay_type']).",
		ptc_pay_amount=".quote_smart($_REQUEST['ptc_pay_amount']).",
		ptrad_pay_type=".quote_smart($_REQUEST['ptrad_pay_type']).",
		ptrad_pay_amount=".quote_smart($_REQUEST['ptrad_pay_amount']).",
		ptsurvey_pay_type=".quote_smart($_REQUEST['ptsurvey_pay_type']).",
		ptsurvey_pay_amount=".quote_smart($_REQUEST['ptsurvey_pay_amount']).",
		pointToFAds=".quote_smart($_REQUEST['pointToFAds']).",
		pointToBanners=".quote_smart($_REQUEST['pointToBanners']).",
		pointToCash=".quote_smart($_REQUEST['pointToCash']).",
		pointToLinks=".quote_smart($_REQUEST['pointToLinks']).",
		pointToFBanners=".quote_smart($_REQUEST['pointToFBanners']).",
		gmprice=".quote_smart($_REQUEST['newgmprice']).",
		ptrname=".quote_smart($_REQUEST['newptrname']).",
		ptrurl=".quote_smart($_REQUEST['newptrurl']).",
		adminemail=".quote_smart($_REQUEST['newadminemail']).",
		subonus=".quote_smart($_REQUEST['newsubonus']).",
		minpay=".quote_smart($_REQUEST['newminpay']).",
		accLinks=".quote_smart($_REQUEST['accLinks']).",
		accBanner=".quote_smart($_REQUEST['accBanner']).",
		accFBanner=".quote_smart($_REQUEST['accFBanner']).",
		accFAd=".quote_smart($_REQUEST['accFAd']).",
		accFLink=".quote_smart($_REQUEST['accFLink']).",
		accSignup=".quote_smart($_REQUEST['accSignup']).",
		accEmail=".quote_smart($_REQUEST['accEmail']).",
		accReferral=".quote_smart($_REQUEST['accReferral']).",
		accSurvey=".quote_smart($_REQUEST['accSurvey']).",
		accPacks=".quote_smart($_REQUEST['accPacks']).",
		accPtrAd=".quote_smart($_REQUEST['accPtrAd']).",
		accMemberships=".quote_smart($_REQUEST['accMemberships']).",
		signupAddress=".quote_smart($_REQUEST['signupAddress']).",
		signupCity=".quote_smart($_REQUEST['signupCity']).",
		signupState=".quote_smart($_REQUEST['signupState']).",
		signupZip=".quote_smart($_REQUEST['signupZip']).",
		signupCountry=".quote_smart($_REQUEST['signupCountry']).",
		signupGender=".quote_smart($_REQUEST['signupGender']).",
		signupAge=".quote_smart($_REQUEST['signupAge']).",
		signupAnualIncome=".quote_smart($_REQUEST['signupAnualIncome']).",
		signupLanguage=".quote_smart($_REQUEST['signupLanguage']).",
		signupInterests=".quote_smart($_REQUEST['signupInterests']).",
		ptClickTimer=".quote_smart($_REQUEST['ptClickTimer']).",
		ptReadAdTimer=".quote_smart($_REQUEST['ptReadAdTimer']).",
		ptSurveyTimer=".quote_smart($_REQUEST['ptSurveyTimer']).",
		ptReadEmailTimer=".quote_smart($_REQUEST['ptReadEmailTimer']).",
		currency=".quote_smart($_REQUEST['currency']).",
		siteStyle=".quote_smart($_REQUEST['siteStyle']).",
		siteTemplate=".quote_smart($_REQUEST['siteTemplate']).",
		autoPayzaBalance=".quote_smart($_REQUEST['autoPayzaBalance']).",
		requireEmailValidation=".quote_smart($_REQUEST['requireEmailValidation']).",
		payzaBalance=".quote_smart($_REQUEST['payzaBalance']).",
		poweredby=".quote_smart($_REQUEST['poweredby']).",
		currencyName=".quote_smart($_REQUEST['currencyName']).",
		pointsName=".quote_smart($_REQUEST['pointsName']).",
		enableCashoutPin = ".quote_smart($_REQUEST['enableCashoutPin']).",
		enableSecondaryPassword = ".quote_smart($_REQUEST['enableSecondaryPassword']).",
		enableEmailDownline = ".quote_smart($_REQUEST['enableEmailDownline']).",
		emailDownlineDailyLimit = ".quote_smart($_REQUEST['emailDownlineDailyLimit']).",
		emailDownlineMonthlyLimit = ".quote_smart($_REQUEST['emailDownlineMonthlyLimit']).",
		enableMd5Passwords = ".quote_smart($_REQUEST['enableMd5Passwords'])."";
		
		//LOOP THROUGH AD TYPES
		foreach($ads as $k => $v) {
			//BUILD FIELDS FOR THIS AD TYPE
			
			$disable = $v['disable'];
			
			$fields = array($k.'PayTypes'=>'',$k.'PayAmounts'=>'',$k.'PayTimers'=>'',$k.'PayCredits'=>'',$k.'PayNames'=>'');
			foreach($fields as $field => $v) {
				$values = '';
				if(count($_REQUEST[$field]) > 0) foreach($_REQUEST[$field] as $k => $v) $values .= $v.',';
				else $values = ',';
				$values = substr($values,0,strlen($values)-1);
				$query .= ',`'.$field.'` = '.quote_smart($values);
			}
			$query .= ',`'.$disable.'` = '.quote_smart($_REQUEST[$disable]);
		}
		
		//exit('"'.$query.'" Built. <hr><h1>REQUEST</h1><pre>'.print_r($_REQUEST,true).'</pre><BR>');
		
		$sq=mysql_query($query) or die(mysql_error());
	
		if(mysql_affected_rows()) {
	
			displaySuccess("GLOBAL SETTINGS CHANGED!");
	
		}
	} //END DEMO MODE
}



if($act=='chbonuses')

{

if(!$newref1bonus) $newref1bonus=0;

if(!$newref2bonus) $newref2bonus=0;

if(!$newref3bonus) $newref3bonus=0;

if(!$newref4bonus) $newref4bonus=0;

if(!$newref5bonus) $newref5bonus=0;

if(!$newref6bonus) $newref6bonus=0;

if(!$newref7bonus) $newref7bonus=0;

if(!$newref8bonus) $newref8bonus=0;

if(!$newref9bonus) $newref9bonus=0;

if(!$newref10bonus) $newref10bonus=0;
if($bnum > 10) $bnum = 10;
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
	$sq=mysql_query("UPDATE setupinfo SET levels=".quote_smart($bnum).", ref1bonus=".quote_smart($newref1bonus).", ref2bonus=".quote_smart($newref2bonus).", ref3bonus=".quote_smart($newref3bonus).", ref4bonus=".quote_smart($newref4bonus).", ref5bonus=".quote_smart($newref5bonus).", ref6bonus=".quote_smart($newref6bonus).", ref7bonus=".quote_smart($newref7bonus).", ref8bonus=".quote_smart($newref8bonus).", ref9bonus=".quote_smart($newref9bonus).", ref10bonus=".quote_smart($newref10bonus)."") or die(mysql_error());
}
if(mysql_affected_rows())displaySuccess("REFERRAL BONUSES CHANGED!");

}



$sql=mysql_query("SELECT * FROM setupinfo");

$setupinfo=mysql_fetch_array($sql);

@extract ($setupinfo);




if($act == 'newTemplate') {
	?>Add a new template to your website!<BR />
    Please note, your template must reside in templates/folderName, folderName being the name of the folder with your template in it.<br />
    <br />
	<form name="form1" method="post" action="./index.php">
      Template Display Name: 
        <input type="text" name="templateName" value="<?php echo $_REQUEST['templateName']; ?>" /> 
    (Only for display)<br />
        <br />
        Template Folder Name: <input type="text" name="templateIdentifier" value="<?php echo $_REQUEST['templateIdentifier']; ?>" /> (Folder name in /templates, ie: default if your template is /templates/default)<br />
        <br />
        <input type="submit" name="Submit" value="Add New Template" />
        <input type="hidden" name="tp" value="setglobal" />
	    <input type="hidden" name="act" value="addNewTemplate" />
	</form>
    <BR /><?php
}
?>



<form name="form1" method="post" action="./index.php">

  <table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
<thead>
    <tr> 

      <td colspan="3">WEBSITE SETTINGS</td>
    </tr>
</thead><tbody>
    <tr> 

      <td valign="top"> 
        <strong>Your Website's Name        </strong></td>

      <td valign="top"> 

        <div align="left">

          <input name="newptrname" type="text" value="<?php echo  $ptrname?>" size="45">
        </div></td>
    </tr>

    <tr> 

      <td valign="top"> 

        <strong>Domain Name (ONLY!)</strong></td>

      <td valign="top"> 

        <div align="left">

          <input name="newptrurl" type="text" value="<?php echo $ptrurl?>" size="45">
    MUST NOT INCLUDE http:// and NO trailing /  </div></td>
    </tr>
<tr> 

      <td valign="top"> 

        <strong>Website Color Style</strong></td>

      <td valign="top"> 

        <div align="left">
<select name="siteStyle">
	<option value="" <?php if($siteStyle == '') echo "selected=\"selected\""; ?>>Default</option>
    <option value="orange" <?php if($siteStyle == 'orange') echo "selected=\"selected\""; ?>>Orange</option>
    <option value="green" <?php if($siteStyle == 'green') echo "selected=\"selected\""; ?>>Green</option>
</select> 
(Only applicable to default template.)  
</div></td>
    </tr>
    <tr> 

      <td valign="top"> 

        <strong>Website Template</strong></td>

      <td valign="top"> 

        <div align="left">
        <?php $siteTemplate = getDefaultTemplate(); ?>
<select name="siteTemplate">
	<?php
	$sql = mysql_query("SELECT * FROM `templates` ORDER BY id ASC");
	$count = mysql_num_rows($sql);
	if($count > 0) {
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($sql, $i);
			$arr = mysql_fetch_array($sql);
			?>
			<option value="<?php echo $arr['templateIdentifier']; ?>" <?php if($siteTemplate == $arr['templateIdentifier']) echo "selected=\"selected\""; ?>><?php echo $arr['templateName']; ?></option>
			<?php
		}
	} else {
		?>
		<option value="default" selected="selected">Default</option>
		<?php
	}
	?>
</select> 
<a href="index.php?tp=setglobal&amp;act=newTemplate">Add New</a></div></td>
    </tr>
    
    <tr> 

      <td valign="top"> 

        <strong>Enable Md5 User Password Encryption</strong></td>

      <td valign="top"> 

        <div align="left">
<select name="enableMd5Passwords">
    <option value="1" <?php if($enableMd5Passwords == '1') echo "selected=\"selected\""; ?>>Yes</option>
    <option value="0" <?php if($enableMd5Passwords == '0') echo "selected=\"selected\""; ?>>No</option>
</select> 
If changed, all current users must update their password using forgotten password feature. You must send a notice to the members notifying them of this change.
</div></td>
    </tr>
    <tr> 

      <td valign="top"> 

        <strong>Enable Secondary Password</strong></td>

      <td valign="top"> 

        <div align="left">
<select name="enableSecondaryPassword">
    <option value="1" <?php if($enableSecondaryPassword == '1') echo "selected=\"selected\""; ?>>Yes</option>
    <option value="0" <?php if($enableSecondaryPassword == '0') echo "selected=\"selected\""; ?>>No</option>
</select>       </div></td>
    </tr>
    <tr> 

      <td valign="top"> 

        <strong>Enable Cashout Pin</strong></td>

      <td valign="top"> 

        <div align="left">
<select name="enableCashoutPin">
    <option value="1" <?php if($enableCashoutPin == '1') echo "selected=\"selected\""; ?>>Yes</option>
    <option value="0" <?php if($enableCashoutPin == '0') echo "selected=\"selected\""; ?>>No</option>
</select>       </div></td>
    </tr>
    <?php /*<tr> 

      <td valign="top"> 

        <strong>Enable Downline Mailer for Members</strong></td>

      <td valign="top"> 

        <div align="left">
<select name="enableEmailDownline">
    <option value="1" <?php if($enableEmailDownline == '1') echo "selected=\"selected\""; ?>>Yes</option>
    <option value="0" <?php if($enableEmailDownline == '0') echo "selected=\"selected\""; ?>>No</option>
</select>       </div></td>
    </tr><tr> 

      <td valign="top"> 

        <strong>Downline Mailer Limits</strong></td>

      <td valign="top"> 

        <div align="left"><input name="emailDownlineDailyLimit" type="text" value="<?php echo $emailDownlineDailyLimit?>" size="6" maxlength="6"> 
        Max Per Day<br />
<input name="emailDownlineMonthlyLimitPer" type="text" value="<?php echo $emailDownlineMonthlyLimitPer?>" size="6" maxlength="6"> 
Max Per Month     </div></td>
    </tr>
	*/ ?>
    <tr> 

      <td valign="top"> 

        <strong>New Accounts Require Email Verification</strong></td>

      <td valign="top"> 

        <div align="left">
<select name="requireEmailValidation">
    <option value="1" <?php if($requireEmailValidation == '1') echo "selected=\"selected\""; ?>>Yes</option>
    <option value="0" <?php if($requireEmailValidation == '0') echo "selected=\"selected\""; ?>>No</option>
</select>       </div></td>
    </tr>
    <tr> 

      <td valign="top"> 

        <strong>Admin e-mail</strong></td>

      <td valign="top"> 

        <div align="left">

          <input type="text" name="newadminemail" value="<?php echo $adminemail?>">
          </div></td>
    </tr>

    <tr> 

      <td width="28%" valign="top"> 

        <strong>Signup bonus</strong></td>

      <td width="70%" valign="top"> 

        <div align="left"><?php echo $currency; ?><input type="text" name="newsubonus" size="3" value="<?php echo  $subonus?>">
      </div></td>
    </tr>

    <tr> 

      <td width="28%" valign="top"> 

        <strong>Minimum payout</strong></td>

      <td width="70%" valign="top"> 

        <div align="left"><?php echo $currency; ?><input type="text" name="newminpay" size="3" value="<?php echo  $minpay?>">
      </div></td>
    </tr>

    <tr> 

      <td valign="top"><strong>Convert <?php echo $pointsName; ?>'s to Cash Rate </strong></td>
      <td valign="top"><div align="left"><?php echo $currency; ?><input type="text" name="pointToCash" size="3" value="<?php echo  $pointToCash?>">
= 1 <?php echo $pointsName; ?> </div></td>
    </tr>

    <tr> 

      <td valign="top"><strong>Convert <?php echo $pointsName; ?>'s to Link Credits Rate </strong></td>
      <td valign="top"><div align="left">

        <input type="text" name="pointToLinks" size="3" value="<?php echo  $pointToLinks?>"> 
 credit = 1 <?php echo $pointsName; ?></div></td>
    </tr>

    <tr> 

      <td valign="top"><strong>Convert <?php echo $pointsName; ?>'s to Featured Banner Credits Rate </strong></td>
      <td valign="top"><div align="left">

        <input type="text" name="pointToFBanners" size="3" value="<?php echo  $pointToFBanners?>">

 credit = 1 <?php echo $pointsName; ?></div></td>
    </tr>

    <tr> 

      <td valign="top"><strong>Convert <?php echo $pointsName; ?>'s to Banner Credits Rate </strong></td>
      <td valign="top"><div align="left">

        <input type="text" name="pointToBanners" size="3" value="<?php echo  $pointToBanners?>">

 credit = 1 <?php echo $pointsName; ?></div></td>
    </tr>

    <tr> 

      <td valign="top"><strong>Convert <?php echo $pointsName; ?>'s to Featured Ad Credits Rate </strong></td>
      <td valign="top"><div align="left">

        <input type="text" name="pointToFAds" size="3" value="<?php echo  $pointToFAds?>">

 credit = 1 <?php echo $pointsName; ?></div></td>
    </tr>
    
    
    <?php /*
	
	<tr>

      <td valign="top">

        <strong>Paid to Click</strong> (<input type="checkbox" name="disablePTC" value="1" <?php if($disablePTC == '1') echo ' checked="checked"'; ?>> disable)</td>

      <td valign="top">

        <div align="left">
          <input name="ptClickTimer" type="text" value="<?php echo $ptClickTimer; ?>" size="3" maxlength="10">

          Second Timer, Pays

          <input type="text" name="ptc_pay_amount" size="3" value="<?php echo $ptc_pay_amount; ?>">

          <select name="ptc_pay_type">

            <option value="points" <?php if($ptc_pay_type == 'points'||$ptc_pay_type == '') echo "selected"; ?>><?php echo $pointsName; ?></option>

            <option value="usd" <?php if($ptc_pay_type == 'usd') echo "selected"; ?>>Cash</option>
          </select> 
          ( <?php 
		  if($ptc_pay_type == 'usd') echo $setupinfo['currency'];
		  echo getTotalCommPayout($ptc_pay_amount);
		  if($ptc_pay_type == 'points') echo ' Points';
		  
		   ?> paid out including <?php echo $setupinfo['levels']; ?> level(s) of referrals)   </div></td>

    </tr>

	<tr>

      <td valign="top">

        <strong>Paid to Read Ad's </strong> (<input type="checkbox" name="disablePTR" value="1" <?php if($disablePTR == '1') echo ' checked="checked"'; ?>> disable)</td>

      <td valign="top">

        <div align="left">
          <input name="ptReadAdTimer" type="text" value="<?php echo $ptReadAdTimer; ?>" size="3" maxlength="10">

          Second Timer, Pays

          <input type="text" name="ptrad_pay_amount" size="3" value="<?php echo $ptrad_pay_amount; ?>">

          <select name="ptrad_pay_type">

            <option value="points" <?php if($ptrad_pay_type == 'points'||$ptrad_pay_type == '') echo "selected"; ?>><?php echo $pointsName; ?></option>

            <option value="usd" <?php if($ptrad_pay_type == 'usd') echo "selected"; ?>>Cash</option>
          </select>
        ( <?php
		  if($ptrad_pay_type == 'usd') echo $setupinfo['currency'];
		  echo getTotalCommPayout($ptrad_pay_amount);
		  if($ptrad_pay_type == 'points') echo ' Points';
		  ?> paid out including <?php echo $setupinfo['levels']; ?> level(s) of referrals) </div></td>
    </tr>

	 <tr>

      <td valign="top">

        <strong>Paid to Take Surveys </strong> (<input type="checkbox" name="disablePTSURVEY" value="1" <?php if($disablePTSURVEY == '1') echo ' checked="checked"'; ?>> disable)</td>

      <td valign="top">

        <div align="left">
          <input name="ptSurveyTimer" type="text" value="<?php echo $ptSurveyTimer; ?>" size="3" maxlength="10"> 

          Second Timer, Pays

          <input type="text" name="ptsurvey_pay_amount" size="3" value="<?php echo $ptsurvey_pay_amount; ?>">

          <select name="ptsurvey_pay_type">

            <option value="points" <?php if($ptsurvey_pay_type == 'points'||$ptsurvey_pay_type == '') echo "selected"; ?>><?php echo $pointsName; ?></option>

            <option value="usd" <?php if($ptsurvey_pay_type == 'usd') echo "selected"; ?>>Cash</option>
          </select>
         ( <?php
		  if($ptsurvey_pay_type == 'usd') echo $setupinfo['currency'];
		  echo getTotalCommPayout($ptsurvey_pay_amount);
		  if($ptsurvey_pay_type == 'points') echo ' Points';
		  ?> paid out including <?php echo $setupinfo['levels']; ?> level(s) of referrals) </div></td>
    </tr>

	

    <tr>

      <td valign="top">

        <strong>Paid to Read Emails </strong> (<input type="checkbox" name="disablePTEMAIL" value="1" <?php if($disablePTEMAIL == '1') echo ' checked="checked"'; ?>> disable)</td>

      <td valign="top">

        <div align="left">
          <input name="ptReadEmailTimer" type="text" value="<?php echo $ptReadEmailTimer; ?>" size="3" maxlength="10"> 

          Second Timer, Pays

          <input type="text" name="ptr_pay_amount" size="3" value="<?php echo $ptr_pay_amount; ?>">

          <select name="ptr_pay_type">

            <option value="points" <?php if($ptr_pay_type == 'points'||$ptr_pay_type == '') echo "selected"; ?>><?php echo $pointsName; ?></option>

            <option value="usd" <?php if($ptr_pay_type == 'usd') echo "selected"; ?>>Cash</option>
          </select>
        ( <?php
		  if($ptr_pay_type == 'usd') echo $setupinfo['currency'];
		  echo getTotalCommPayout($ptr_pay_amount);
		  if($ptr_pay_type == 'points') echo ' Points';
		  ?> paid out including <?php echo $setupinfo['levels']; ?> level(s) of referrals) </div></td>
    </tr>

	

	

	

	

	

    <tr>

      <td valign="top">

        <strong>Paid to Sign-Up Pays</strong> (<input type="checkbox" name="disablePTS" value="1" <?php if($disablePTS == '1') echo ' checked="checked"'; ?>> disable)</td>

      <td valign="top">

        <div align="left">

          Pays 
          <input type="text" name="pts_pay_amount" size="3" value="<?php echo $pts_pay_amount; ?>">

          <select name="pts_pay_type">

            <option value="points" <?php if($pts_pay_type == 'points'||$pts_pay_type == '') echo "selected"; ?>><?php echo $pointsName; ?></option>

            <option value="usd" <?php if($pts_pay_type == 'usd') echo "selected"; ?>>Cash</option>
          </select>
        ( <?php
		  if($pts_pay_type == 'usd') echo $setupinfo['currency'];
		  echo getTotalCommPayout($pts_pay_amount);
		  if($pts_pay_type == 'points') echo ' Points';
		  ?> paid out including <?php echo $setupinfo['levels']; ?> level(s) of referrals) </div></td>
    </tr>
	
	*/ ?>
	

    
    
    


      <td valign="top">

        <strong>Referral Contest Enabled</strong></td>

      <td valign="top">

        <div align="left">

          <input type="radio" value="1" name="refContestActive" <?php if($refContestActive == '1') { echo "checked"; } ?>>

          Yes 

          <input type="radio" value="0" name="refContestActive" <?php if($refContestActive == '0') { echo "checked"; } ?>>

        No        </div></td>

    </tr>

<tr>

      <td valign="top">

      <strong>Referral Contest is Recurring</strong></td>

      <td valign="top">

        <div align="left">

          <input type="radio" value="1" name="refContestRecurring" <?php if($refContestRecurring == '1') { echo "checked"; } ?>>

          Yes 

          <input type="radio" value="0" name="refContestRecurring" <?php if($refContestRecurring == '0') { echo "checked"; } ?>>

      No      </div></td>
    </tr>

<tr>

      <td valign="top">

      <strong>Referral Contest Length</strong></td>

      <td valign="top">

      <div align="left">

            <select name="refContestLength">

		      <option value="1" <?php if($refContestLength == '1') { ?>selected<?php } ?>>Daily</option>

		      <option value="7" <?php if($refContestLength == '7') { echo "selected"; } ?>>Weekly</option>

		      <option value="15" <?php if($refContestLength == '15') { echo "selected"; } ?>>Bi-Monthly</option>

		      <option value="30" <?php if($refContestLength == '30') { echo "selected"; } ?>>Every 30 Days</option>

		      <option value="60" <?php if($refContestLength == '60') { echo "selected"; } ?>>Every 60 Days</option>

		      <option value="90" <?php if($refContestLength == '90') { echo "selected"; } ?>>Every 90 Days</option>

		      <option value="120" <?php if($refContestLength == '120') { echo "selected"; } ?>>Every 120 Days</option>
	      </select>
          </div></td>
    </tr>

<tr>

      <td valign="top">

        <strong>New Members Receive Paid Emails</strong><br></td>

      <td valign="top">

      <div align="left">

            <select name="paidEmails">

		      <option value="1" <?php if($paidEmails == '1') { ?>selected<?php } ?>>Yes</option>

		      <option value="0" <?php if($paidEmails == '0') { echo "selected"; } ?>>No</option>
	      </select>
            <font size="1">If this is disabled, members will have to go into My Profile to enable receiving paid emails.</font></div></td>
    </tr>

<tr>

      <td valign="top">

      <strong>Orders Affect Payza Balance</strong></td>

      <td valign="top">

      <div align="left">

            <select name="autoPayzaBalance">

		      <option value="1" <?php if($autoPayzaBalance == '1') { ?>selected<?php } ?>>Yes</option>

		      <option value="0" <?php if($autoPayzaBalance == '0') { echo "selected"; } ?>>No</option>
	      </select>
            <font size="1">When you reveive payments for advertising or memberships for Payza, the total will be added to the available payza balance automatically for user's auto withdraw's via Payza.</font></div></td>
    </tr>
    
<tr>

      <td valign="top">

      <strong>Payza Balance</strong></td>

      <td valign="top">

      <div align="left">

            <?php echo $setupinfo['currency']; ?><input name="payzaBalance" type="text" value="<?php echo $payzaBalance; ?>" size="8" />
            <font size="1">Available balance for Payza Automated Withdraw's.</font></div></td>
    </tr>
    
<tr>

      <td valign="top">

      <strong>Display "Powered By"</strong></td>

      <td valign="top">

      <div align="left">

            <select name="poweredby">

		      <option value="1" <?php if($poweredby == '1') { ?>selected<?php } ?>>Yes</option>

		      <option value="0" <?php if($poweredby == '0') { echo "selected"; } ?>>No</option>
	      </select>
            <font size="1">Show / Hide the "Powered By PTCShop" displayed on the bottom of your website. It is not required, but it is appreciated :)</font></div></td>
    </tr>
    <tr> 

      <td valign="top"> 

        <strong><?php echo $currencyName; ?> Currency Symbol</strong></td>

      <td valign="top"> 

        <div align="left">
          <input name="currency" type="text" value="<?php echo $currency; ?>" size="8" />
        <font size="1">IE: $ or €</font></div></td>

    </tr>
<tr>

      <td valign="top">

      <strong>Currency Name</strong></td>

      <td valign="top">

      <div align="left">
<input type="text" name="currencyName" value="<?php echo $currencyName; ?>" />
            <font size="1">IE: Cash or USD or Yen</font></div></td>
    </tr>
    
<tr>

      <td valign="top">

      <strong>Points Name</strong></td>

      <td valign="top">

      <div align="left">
<input type="text" name="pointsName" value="<?php echo $pointsName; ?>" />
            <font size="1">IE: Point or Coin or Ticket</font></div></td>
    </tr>
    

    <tr valign="top"> 

      <td valign="top"> 

        <strong>Account Funds can Buy</strong><br>

      <font size="1">This will enable them to use their account funds to purchase advertising credits or referrals.</font></td>

      <td valign="top"> 

        <div align="left">
          <table><tr><td>
          <input type="checkbox" name="accLinks" id="accLinks" value="1" <?php if($accLinks == 1) echo 'checked'; ?>></td><td>
          <label for="accLinks">Link Credits</label></td><td>
		  <input type="checkbox" name="accBanner" id="accBanner" value="1" <?php if($accBanner == 1) echo 'checked'; ?>></td><td>
		  <label for="accBanner">Banner Credits</label></td><td>
		  <input type="checkbox" name="accFBanner" id="accFBanner" value="1" <?php if($accFBanner == 1) echo 'checked'; ?>></td><td>
		  <label for="accFBanner">Featured Banner Credits</label></td></tr>
  <tr>
    <td><input type="checkbox" name="accFAd" id="accFAd" value="1" <?php if($accFAd == 1) echo 'checked'; ?> /></td>
    <td><label for="accFAd">Featured Ad Credits</label></td>
    <td><input type="checkbox" name="accFLink" id="accFLink" value="1" <?php if($accFLink == 1) echo 'checked'; ?> /></td>
    <td><label for="accFLink">Featured Link Credits</label></td>
    <td><input type="checkbox" name="accSignup" id="accSignup" value="1" <?php if($accSignup == 1) echo 'checked'; ?> /></td>
    <td><label for="accSignup">Signup Credits</label></td>
  </tr>
  <tr>
    <td><input type="checkbox" name="accEmail" id="accEmail" value="1" <?php if($accEmail == 1) echo 'checked'; ?> /></td>
    <td><label for="accEmail">E-Mail Credits</label></td>
    <td><input type="checkbox" name="accSurvey" id="accSurvey" value="1" <?php if($accSurvey == 1) echo 'checked'; ?> /></td>
    <td><label for="accSurvey">Paid Survey Credits</label></td>
    <td><input type="checkbox" name="accPtrAd" id="accPtrAd" value="1" <?php if($accPtrAd == 1) echo 'checked'; ?> /></td>
    <td><label for="accPtrAd">Paid to Read Ad Credits</label></td>
  </tr>
  <tr>
    <td><input type="checkbox" name="accReferral" id="accReferral" value="1" <?php if($accReferral == 1) echo 'checked'; ?> /></td>
    <td><label for="accReferral">Referrals</label></td>
    <td><input type="checkbox" name="accPacks" id="accPacks" value="1" <?php if($accPacks == 1) echo 'checked'; ?> /></td>
    <td><label for="accPacks">Advertising Packages</label></td>
    <td><input type="checkbox" name="accMemberships" id="accMemberships" value="1" <?php if($accPacks == 1) echo 'checked'; ?> /></td>
    <td><label for="accMemberships">Memberships</label></td>
  </tr>
</table>
		  
        </div></td>
    </tr>

<tr valign="top"> 

      <td valign="top">        <strong>Signup Form Options </strong><br>

      <font size="1">Check this box to enable each user attribute.</font></td>

      <td valign="top">
        <table>
          <tr>
            <td><input type="checkbox" name="signupAddress" id="signupAddress" value="1" <?php if($signupAddress == 1) echo 'checked'; ?> /></td>
            <td><label for="signupAddress">Address</label></td>
            <td><input type="checkbox" name="signupCity" id="signupCity" value="1" <?php if($signupCity == 1) echo 'checked'; ?> /></td>
            <td><label for="signupCity">City</label></td>
            <td><input type="checkbox" name="signupState" id="signupState" value="1" <?php if($signupState == 1) echo 'checked'; ?> /></td>
            <td><label for="signupState">State</label></td>
            <td><input type="checkbox" name="signupZip" id="signupZip" value="1" <?php if($signupZip == 1) echo 'checked'; ?> /></td>
            <td><label for="signupZip">Zip</label></td>
          </tr>

          <tr>
            <td><input type="checkbox" name="signupCountry" id="signupCountry" value="1" <?php if($signupCountry == 1) echo 'checked'; ?> /></td>
            <td><label for="signupCountry">Country</label></td>
            <td><input type="checkbox" name="signupGender" id="signupGender" value="1" <?php if($signupGender == 1) echo 'checked'; ?> /></td>
            <td><label for="signupGender">Gender</label></td>
            <td><input type="checkbox" name="signupAge" id="signupAge" value="1" <?php if($signupAge == 1) echo 'checked'; ?> /></td>
            <td><label for="signupAge">Age</label></td>
            <td><input type="checkbox" name="signupAnualIncome" id="signupAnualIncome" value="1" <?php if($signupAnualIncome == 1) echo 'checked'; ?> /></td>
            <td><label for="signupAnualIncome">Anual Income</label></td>
          </tr>
          <tr>
            <td><input type="checkbox" name="signupLanguage" id="signupLanguage" value="1" <?php if($signupLanguage == 1) echo 'checked'; ?> /></td>
            <td><label for="signupLanguage">Language</label></td>
            <td><input type="checkbox" name="signupInterests" id="signupInterests" value="1" <?php if($signupInterests == 1) echo 'checked'; ?> /></td>
            <td><label for="signupInterests">Interests</label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>


	
    <tr>
    	<td valign="top" colspan="2">
        <?php
echo '<table>';

foreach($ads as $adType => $adDetails) {
	$types = explode(',',$setupinfo[$adType.'PayTypes']);
	$amounts = explode(',',$setupinfo[$adType.'PayAmounts']);
	$credits = explode(',',$setupinfo[$adType.'PayCredits']);
	$timers = explode(',',$setupinfo[$adType.'PayTimers']);
	$names = explode(',',$setupinfo[$adType.'PayNames']);
	$disable = $adDetails['disable'];
	echo '<tr><td colspan="2">';
	
	echo '<h2 style="font-size: 14px; font-weight: bold;">'.$adDetails['name'].' Ad Status: <select name="'.$disable.'">';
	echo '<option value="1" '; if($setupinfo[$disable] == '1') echo ' selected="selected"'; echo '>Disabled</option>';
	echo '<option value="0" '; if($setupinfo[$disable] == '0') echo ' selected="selected"'; echo '>Enabled</option>';
	echo '</select></h2>';
	
	echo '<table>';
	for($i = 0; $i < $adTypes;$i++) {
		$type = $types[$i];
		$amount = $amounts[$i];
		$timer = $timers[$i];
		$name = $names[$i];
		$credit = $credits[$i];
		
		echo '<tr><td colspan="3"><h2 style="font-size: 14px; font-weight: bold;">'.$adDetails['name'].' Ad Tier #'.($i+1).'</h2></td></tr>';
		
		echo '<tr><td><strong>Name:</strong></td><td><input type="input" name="'.$adType.'PayNames[]" value="'.$name.'"> 
		</td><td><span style="font-size: 10px;">Keep it short and simple.</span></td></tr>';
		
		echo '<tr><td><strong>Payout:</strong></td><td><input type="input" name="'.$adType.'PayAmounts[]" value="'.$amount.'">
		&nbsp;&nbsp;&nbsp;&nbsp;<select name="'.$adType.'PayTypes'.'[]">
		<option value="points"'; if($type == 'point') echo ' selected="selected"'; echo '>Points</option>
		<option value="usd"'; if($type == 'usd') echo ' selected="selected"'; echo '>Cash</option>
		</select></td><td><span style="font-size: 10px;">(Enter only a numeric value such as 1.0 or 0.005)</span></td></tr>
		
		<tr><td><strong>Credits Per Ad:</strong></td><td><input type="input" name="'.$adType.'PayCredits[]" value="'.$credit.'"></td><td><span style="font-size: 10px;">(Enter only a numeric value such as 1.0 or 0.005. A value of 1 will require 1 credit per ad completion. A value of 10 will require 10 crdits per ad completion.)</span></td></tr>
		
		';
		
		echo '<tr><td><strong>Timer:</strong></td><td><input type="input" name="'.$adType.'PayTimers[]" value="'.$timer.'"> Seconds
		</td><td><span style="font-size: 10px;">Use only whole numbers such as 1 5 or 30.</span></td></tr>';
	}
	echo '</table>';
	echo '<hr>';
	echo '</td></tr>';
}

echo '</table>';
?></td>
    </tr>
<?php
?>


    <tr> 

      <td> 

        <input type="hidden" name="tp" value="setglobal">      </td>

      <td> 

        <input type="hidden" name="act" value="chglobal">      </td>
    </tr>

    <tr> 

      <td colspan="3"> 

        <div align="center"> 

          <input type="submit" name="Submit" value="Save Changes">
        </div>      </td>
    </tr>
    </tbody>
  </table>

</form>

<p><br>

</p>

<form name="bonuses" method="post" action="">

  <table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
<thead>
    <tr> 

      <td colspan="2">REFERRAL BONUS SETTINGS</td>

    </tr>
</thead><tbody>
    <tr> 

      <td width="367"> 

        <div align="right">Number of referral levels</div>

      </td>

      <td width="354"> 

        <div align="right"> 

          <input type="text" name="bnum" size="3" value="<?php echo  $levels?>">

          <input type="button" name="setreferralbonuses" value="Set referral bonus levels." onclick="ch()">Set referral bonuses </button><font size="1" color="#FF0000">(max.:10) 

          </font></div>

      </td>

    </tr>

    <tr> 

      <td colspan="2"> 

        <div align="center" ID='s1' style="position:relative; visibility:hidden"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 1 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref1bonus" size="3" value="<?php echo  $ref1bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

        <div align="center" ID='s2' style="position:relative; visibility:hidden"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 2 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref2bonus" size="3" value="<?php echo  $ref2bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s3' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 3 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref3bonus" size="3" value="<?php echo  $ref3bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s4' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 4 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref4bonus" size="3" value="<?php echo  $ref4bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s5' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 5 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref5bonus" size="3" value="<?php echo  $ref5bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s6' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 6 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref6bonus" size="3" value="<?php echo  $ref6bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s7' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 7 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref7bonus" size="3" value="<?php echo  $ref7bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s8' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 8 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref8bonus" size="3" value="<?php echo  $ref8bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s9' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 9 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref9bonus" size="3" value="<?php echo  $ref9bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

		  <div align="center" ID='s10' style="position:relative; visibility:hidden; display:none;"> 

          <table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr> 

              <td width="51%"> 

                <div align="right">Tier 10 bonus:</div>

              </td>

              <td width="49%"> 

                <div align="right">% 

                  <input type="text" name="newref10bonus" size="3" value="<?php echo  $ref10bonus?>">

                </div>

              </td>

            </tr>

          </table>

        </div>

      </td>

    </tr>

    <tr> 

      <td width="367"> 

        <input type="hidden" name="tp" value="setglobal">

        <input type="hidden" name="act" value="chbonuses">

      </td>

      <td width="354"> 

        <div align="right"></div>

      </td>

    </tr>

    <tr> 

      <td colspan="2"> 

        <div align="center"> 

          <input type="submit" name="Submit" value="Save referral bonus settings">

        </div>

      </td>

    	</tr>
    </tbody>
  </table>

</form>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<script language="javascript" type="text/javascript">

function ch() {
	var i; var blockID; var divBlock;
	for(i = 0;i < 10;i++) {
		blockID = i + 1;
		divBlock = document.getElementById('s' + blockID);
		if(document.bonuses.bnum.value>i) {
			divBlock.style.visibility='visible';
			divBlock.style.display='block';
		} else {
			divBlock.style.visibility='hidden';
			divBlock.style.display='none';
		}
	}
	return false;

}

ch();

</script>
