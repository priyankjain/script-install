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
if(!$globalIncluded) {
	$globalIncluded = TRUE;
	//GLOBAL SCRIPTS INCLUDED FOR BOTH ADMIN AND WEBSITE
	
	//////////////////////////////////////////////////////////////////////////////////////
	// AD TYPES //////////////////////////////////////////////////////////////////////////
	
	$adTypes = array();
	
	$adTypes['banner'] = array('type'=>'banner','name'=>'Banner','table'=>'banners','idType'=>'fnum','credits'=>'fsize','usedCredits'=>'fshows','disable'=>'','hasTypes'=>FALSE);
	
	$adTypes['fbanner'] = array('type'=>'fbanner','name'=>'Featured Banner','table'=>'fbanners','idType'=>'fnum','credits'=>'fsize','usedCredits'=>'fshows','disable'=>'','hasTypes'=>FALSE);
	
	$adTypes['fad'] = array('type'=>'fad','name'=>'Featured Ad','table'=>'featuredads','idType'=>'fnum','credits'=>'fsize','usedCredits'=>'fshows','disable'=>'','hasTypes'=>FALSE);
	
	$adTypes['flinks'] = array('type'=>'flinks','name'=>'Featured Link','table'=>'featuredlinks','idType'=>'fnum','credits'=>'fsize','usedCredits'=>'fshows','disable'=>'','hasTypes'=>FALSE);
	
	$adTypes['links'] = array('type'=>'links','name'=>'Paid to Click','table'=>'tasks','idType'=>'fn','credits'=>'fsize','usedCredits'=>'fvisits','disable'=>'disablePTC', 'prefix'=>'ptc','hasTypes'=>TRUE,'membershipTimer'=>'clickTimer','adBonus'=>'clickBonus');
	
	$adTypes['ptrad'] = array('type'=>'ptrad','name'=>'Paid to Read Ad','table'=>'ptrads','idType'=>'fn','credits'=>'fsize','usedCredits'=>'fvisits','disable'=>'disablePTR','prefix'=>'ptrad','hasTypes'=>TRUE,'membershipTimer'=>'readadTimer','adBonus'=>'readadBonus');
	
	$adTypes['signup'] = array('type'=>'signup','name'=>'Paid to Signup Ad','table'=>'signups','idType'=>'fnum','credits'=>'fsize','usedCredits'=>'fsignups','disable'=>'disablePTSURVEY','prefix'=>'pts','hasTypes'=>TRUE,'adBonus'=>'signupBonus');
	
	$adTypes['email'] = array('type'=>'email','name'=>'Paid to Read Email','table'=>'reads','idType'=>'fnum','credits'=>'fsize','usedCredits'=>'freads','disable'=>'disablePTEMAIL','prefix'=>'ptr','hasTypes'=>TRUE,'membershipTimer'=>'reademailTimer','adBonus'=>'reademailBonus');
	
	$adTypes['survey'] = array('type'=>'surveys','name'=>'Paid to Take Survey','table'=>'surveys','idType'=>'id','credits'=>'fsize','usedCredits'=>'fviews','prefix'=>'ptsurvey','disable'=>'disablePTSURVEY','prefix'=>'ptsurvey','hasTypes'=>TRUE,'membershipTimer'=>'takesurveyTimer','adBonus'=>'takesurveyBonus');
	
	// END AD TYPES //////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////
	
	
}
?>