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
$dbCon = @mysql_connect($dbHost, $dbUsername, $dbPass);
if(!$dbCon) exit("Cannot make a connection to the database server.<BR>");
$dbSelect = @mysql_select_db($dbName);
if(!$dbSelect) exit("Cannot select a database.<BR>");
?>