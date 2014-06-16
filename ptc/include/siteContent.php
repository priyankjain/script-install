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
	
	$siteRoot = "../";
	
	if(trim($tp) == '') $tp = 'home';
	$query = mysql_query("SELECT incPage FROM siteactions WHERE tp = ".quote_smart($tp)."");
	$count = mysql_num_rows($query);
	
	$pageShown = FALSE;
	if($_SESSION['login'] != '') {
		$query = mysql_query("SELECT incPage,virtualPage,pageName FROM siteactions WHERE tp = ".quote_smart($tp)." AND actionType = 'member'");
		if(mysql_num_rows($query) > 0) {
			$arr = mysql_fetch_array($query);
			if($arr['virtualPage'] == '1') {
				include_once($templateFolder.'virtualPage.php');
			} else {
				include_once($templateFolder.$arr['incPage']);
			}
			$pageShown = TRUE;
		}
	}
	if(!$pageShown) {
		$query = mysql_query("SELECT incPage,virtualPage,pageName FROM siteactions WHERE tp = ".quote_smart($tp)." AND actionType = 'website'");
		if(mysql_num_rows($query) > 0) {
			$arr = mysql_fetch_array($query);
			if($arr['virtualPage'] == '1') {
				include_once($templateFolder.'virtualPage.php');
			} else {
				include_once($templateFolder.$arr['incPage']);
			}
			$pageShown = TRUE;
		}
	}
	if(!$pageShown) echo "PAGE NOT FOUND. Please check the link and try agian. 2<BR><BR><BR>";


	
?>