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

if(isset($dispMessage)) unset($dispMessage);
session_start();
include_once('../include/cfg.php');
include_once("../include/dbconnect.php");
include_once("../include/global.php");
include_once('../include/functions.php');

//INCLUDE MULTI-LINGUAL LANGUAGE CLASS
include_once("../include/Language.php");

//LANGUAGE KICKSTART FOR MYSQL
mysql_query("SET NAMES utf8");

//IF LANGUAGE IS SET TO CHANGE, AND LANGUAGE IS ENABLED, CHANGE IT
if(isset($_GET['lang'])) {
	if($_SESSION['login'] != '') mysql_query("UPDATE users SET language = ".quote_smart($_GET['lang'])." WHERE username = ".quote_smart($_SESSION['login'])."");
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

$sq=mysql_query('SELECT * FROM setupinfo');
$setupinfo=mysql_fetch_array($sq); 
@extract($setupinfo);

include("adminRoutines.php");
if(!loginCheck()) include("login.php"); else include("mainTemplate.php");

?>