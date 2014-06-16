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
 loginCheck();
 /* PRE INCLUDES ARE INCLUDED PRIOR TO THE HEADER */
 include("preIncludes.php");
 /* INCLUDE THE ADMIN TEMPLATES HEADER */
 include('header.php');
 
 ?>
	<div id="page-wrapper">
		<div id="main-wrapper">
			<div id="main-content">
            <?php
$tp = $_REQUEST['tp'];
if($tp=='admins') include('admins.php'); else 
if($tp=='editinfo') include('editinfo.php'); else 
if($tp=='userview') include('userview.php'); else 
if($tp=='features') include('features.php'); else 
if($tp=='news') include('news.php'); else 
if($tp=='fads') include('fads.php'); else 
if($tp=='setglobal') include('setglobal.php'); else 
if($tp=='setdesign') include('setdesign.php'); else 
if($tp=='payopt') include('payopt.php'); else 
if($tp=='prices') include('prices.php'); else 
if($tp=='country') include('country.php'); else 
if($tp=='newmail') include('newmail.php'); else 
if($tp=='newptc') include('newptc.php'); else 
if($tp=='newsignup') include('newsignup.php'); else 
if($tp=='fbanners') include('fbanners.php'); else 
if($tp=='addtask') include('addtask.php'); else 
if($tp=='orders') include('orders.php'); else 
if($tp=='members') include('members.php'); else 
if($tp=='campaigns') include('campaigns.php'); else 
if($tp=='payments') include('payments.php'); else 
if($tp=='chitcontrol') include('chitcontrol.php'); else 
if($tp=='setup') include('setup.php'); else 
if($tp=='support') include('support.php'); else 
if($tp=='banners') include('banners.php'); else 
if($tp=='convert') include('convert.php'); else 
if($tp=='activity') include('activity.php'); else 
if($tp=='goldmember') include('goldmember.php'); else 
if($tp=='refcontest') include('refcontest.php'); else 
if($tp=='activity') include('activity.php'); else 
if($tp=='helptips') include('helpTips.php'); else 
if($tp=='packSpecials') include('packSpecials.php'); else 
if($tp=='orderHistory') include('orderHistory.php'); else 
if($tp=='setemails') include('setemails.php'); else
if($tp=='manageads') include('manageads.php'); else
if($tp=='manageadspre') include('manageads.php'); else
if($tp=='membershiptypes') include('membershipTypes.php'); else
if($tp=='emailMembers') include('emailMembers.php'); else
if($tp=='cheaterReport') include('cheaterReport.php'); else
if($tp=='menuEditor') include('menuEditor.php'); else
if($tp=='viewSurveyResults') include('viewSurveyResults.php'); else
if($tp=='aboutScript') include('aboutScript.php'); else
if($tp=='faq') include('faq.php'); else
if($tp=='updates') include('updates.php'); else
if($tp=='assignreferrals') include('assignreferrals.php'); else
if($tp=='siteactions') include('siteActions.php'); else
if($tp=='translateeditor') include('translateeditor.php'); else
if($tp=='exportMembers') include('exportMembers.php'); else
if($tp=='referrals') include('referrals.php'); else
if($tp=='updateDatabase') include('updateDatabase.php'); else
include("home.php");
?>
			</div>
			<div class="clearfix"></div>
		</div>
<?php include('sidebar.php'); ?>

	</div>
	<div class="clearfix"></div>
<?php include('footer.php'); ?>
</body>
</html>