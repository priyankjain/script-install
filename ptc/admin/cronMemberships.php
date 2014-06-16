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

session_start();

include('../include/cfg.php');
include('../include/dbconnect.php');
include('../include/global.php');
include('../include/functions.php');

extract(mysql_fetch_array(mysql_query('SELECT * FROM setupinfo'))); 

$sql = mysql_query("SELECT *, DAYOFWEEK(startDate) AS weekDay, DATEDIFF(startDate, NOW()) AS daysToNow, DAY(startDate) AS monthDay, MONTH(startDate) AS yearMonth FROM memberships WHERE startDate < NOW() AND (endDate > NOW() OR lifetime = '1') AND active = '1' AND DATE(lastCreditDate) != DATE(NOW())");
$count = mysql_num_rows($sql);
if($count == 0) {
	exit("No members found with an active membership.<BR>");
} else {
	echo "Found ".$count." memberships to run through.<BR>";
	for($i = 0;$i < $count;$i++) {
		mysql_data_seek($sql, $i);
		$arr = mysql_fetch_array($sql);
		//GET LIST OF ITEMS IN MEMBERSHIP
		$membershipName = $arr['membershipName'];
		$membershipType = $arr['membershipType'];
		$username = $arr['username'];
		echo "($i,$count) $username - $membershipName<BR>";
		//LOOP THROUGH ITEMS
		$sq = mysql_query("SELECT * FROM membershipitems WHERE membershipID = ".quote_smart($membershipType)."");
		$cnt = mysql_num_rows($sq);
		if($cnt > 0) {
			echo "($i,$count) Found ".$cnt." items in this membership.<BR>";
			for($k = 0; $k < $cnt;$k++) {
				mysql_data_seek($sq, $k);
				$ar = mysql_fetch_array($sq);
				$type = $ar['itemLengthType'];
				if($type == 'd') { //DAY
					echo "($i,$count) Item ($k,$cnt) Daily Credit<BR>";
					creditMembershipItem($username, $ar['itemID']);
				} else if($type == 'w') { //WEEK
					if(($arr['daysToNow']%7) == 0) {
						echo "($i,$count) Item ($k,$cnt) It's been a week...<BR>";
						creditMembershipItem($username, $ar['itemID']);
					} else {
						echo "($i,$count) Item ($k,$cnt) It hasn't been a week...<BR>";
					}
				} else if($type == 'm') { //MONTH
					if($arr['monthDay'] == date("d")) {
						echo "($i,$count) Item ($k,$cnt) Day of month is today: ".$arr['dayOfMonth']." - ".date("d")."<BR>";
						creditMembershipItem($username, $ar['itemID']);
					} else {
						echo "($i,$count) Item ($k,$cnt) Day of month is NOT today: ".$arr['dayOfMonth']." - ".date("d")."<BR>";
					}
				} else if($type == 'y') { //YEAR
					if($arr['yearMonth'] == date("m") && date("d") == $arr['dayOfMonth']) {
						echo "($i,$count) Item ($k,$cnt) It has been a year : ".$arr['yearMonth']." == ".date("m")." && ".date("d")." == ".$arr['dayOfMonth']."<BR>";//IT HAS BEEN A YEAR
						creditMembershipItem($username, $ar['itemID']);
					} else {
						echo "($i,$count) Item ($k,$cnt) It has not been a year yet... : ".$arr['yearMonth']." == ".date("m")." && ".date("d")." == ".$arr['dayOfMonth']."<BR>";//IT HASNT BEEN A YEAR YET
					}
				} else {
					echo "($i,$count) Item ($k,$cnt) Item length type unknown.".$type."<BR>";
				}
			}
		}
		mysql_query("UPDATE memberships SET lastCreditDate = NOW() WHERE id = ".quote_smart($arr['id']).""); 
	} //END LOOP THROUGH ACTIVE MEMBERSHIPS
} //END IF COUNT MEMBERSHIPS > 0


function creditMembershipItem($username, $itemID) {
	$sql = mysql_query("SELECT pack_credits, pack_credits_type FROM packages WHERE fnum = ".quote_smart($itemID)."");
	$count = mysql_num_rows($sql);
	if($count > 0) {
		$arr = mysql_fetch_array($sql);
		$q = mysql_query("INSERT INTO creditadditions
		(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
		VALUES
		(".quote_smart($username).", ".quote_smart($arr['pack_credits']).", ".quote_smart($arr['pack_credits_type']).",NOW(),'-1')
		");
		echo "Inserted new credits addition for item ".$itemID." for ".$username."<BR>";
	} else {
		return FALSE;
	}
}
?>