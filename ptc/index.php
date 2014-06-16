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
// YOUR DEFAULT WEBSITE FILES CAN BE FOUND IN templates/default/
//
//
//
// --------------------------------------------------------------
// DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------
// unless you know what your doing :)
//

//CHECK THAT INSTALL ISN'T PRESENT
if(is_dir("install")) exit("Please remove the \"install\" folder after you have installed the web script to activate your website.");
//INCLUDE SITE CONFIG
include_once("include/cfg.php");
//INCLUDE DATABASE CONNECTION
include_once("include/dbconnect.php");
//INCLUDE GLOBALS
include_once("include/global.php");
//INCLUDE FUNCTIONS
include_once("include/functions.php");
//BACKWARDS COMPATIBILITY FOR MOD_INI
include_once("include/mod_ini.php");
//INCLUDE MULTI-LINGUAL LANGUAGE CLASS
include_once("include/Language.php");
//INCLUDE SITE ROUTINES
include_once("include/routines.php");
//INCLUDE TEMPLATE CLASS
//include_once("include/template.class.php");

//LANGUAGE KICKSTART FOR MYSQL
mysql_query("SET NAMES utf8");

//IF LANGUAGE IS SET TO CHANGE, AND LANGUAGE IS ENABLED, CHANGE IT
if(isset($_GET['lang'])) {
	if($_SESSION['login'] != '') mysql_query("UPDATE users SET language = ".quote_smart($_GET['lang'])." WHERE username = ".quote_smart($_SESSION['login'])."") or die(mysql_error());
	$_SESSION['lang'] = $_GET['lang'];
}

//LOG OUT ADMINISTRATION OF LANGUAGES
if($_REQUEST['logoutTranslator'] == '1') $_SESSION['translateLogin'] = '';

//IF TRANSLATOR IS LOGGED IN, ALLOW LANGUAGE BLOCK EDITING
if($_SESSION['translateLogin'] != '') Language::SetEnableEditor(TRUE); else Language::SetEnableEditor(FALSE);

//CHECK IF LANGUAGE IS SET, OTHERWISE SET LANGUAGE
if($_SESSION['lang'] == '' &&  $_SESSION['login'] != '') {
	$_SESSION['lang'] = getValue("SELECT language FROM users WHERE username = ".quote_smart($_SESSION['login'])."");
} else if($_SESSION['lang'] == '') $_SESSION['lang'] = getValue("SELECT language FROM setupinfo LIMIT 1");

//SET THE LANGUAGE
Language::Set($_SESSION['lang']);

Language::SetAuto(true);

Language::SetPage($_REQUEST['tp']);

///VALIDATE ACTIVE TEMPLATE
if(!is_dir($templateFolder))  exit("FATAL ERROR: SITE TEMPLATE IS INCORRECT. PLEASE CONCTACT SUPPORT ADMINISTRATION TO UPDATE SITE TEMPLATE IN CFG.PHP");//HAS PRE INCLUDE TO THIS PAGE, SCRIPTS THAT RUN BEFORE THE PAGE EXECUTES
//THIS IS FOR DIRECT ACCESS TO header() WITHOUT THE PRE INCLUDE OF header.php
if(hasPreInclude($tp)) {
	$fileToInclude = getValue("SELECT incPage FROM siteactions WHERE tp = ".quote_smart($tp)." AND hasPreInclude = '1'");
	if(is_file($preIncludeFolder.$fileToInclude)) include($preIncludeFolder.$fileToInclude);
}
if(!isStandalonePage($tp)) { //IF NOT STANDALONE, INCLUDE TEMPLATE AND SITE CONTENT
	//INCLUDE WEBSITE HEADER
	include_once($templateFolder."template.php");
	include_once($templateFolder."header.php");
	if($templateFolder == 'templates/default/') {
		//INCLUDE SITE CONTENT PAGES
		include_once($includeFolder."siteContent.php");
		//INCLUDE DEFAULT SIDEBAR
		if(!isStandalonePage($tp)) include_once($templateFolder."sidebar.php");
	} else {
		//INCLUDE DEFAULT SIDEBAR
		if(!isStandalonePage($tp)) include_once($templateFolder."sidebar.php");
		//INCLUDE SITE CONTENT PAGES
		include_once($includeFolder."siteContent.php");
	}
	//INCLUDE FOOTER
	if(!isStandalonePage($tp)) include_once($templateFolder."footer.php");
} else { //IF STANDALONE, ONLY INCLUDE PAGE OR VIRTUAL PAGE CONTENT...
	//INCLUDE SITE CONTENT PAGES
	include_once($includeFolder."siteContent.php");
}
?>