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



$echoDetailed = TRUE;

include('../include/cfg.php');
include('../include/dbconnect.php');
include('../include/global.php');
include('../include/functions.php');

$sq=mysql_query('SELECT * FROM setupinfo');
$ar=mysql_fetch_array($sq); 
@extract($ar);

$ptrHeader = getValue("SELECT `value` FROM `design` WHERE `name` = 'ptrHeader'");

$ptrFooter = getValue("SELECT `value` FROM `design` WHERE `name` = 'ptrFooter'");



//CLEAN OUT OLD EMAIL LOGS

$expire = time()-(7*24*60*60);

echo "Send same email after ".date("Y M d g:i:sa",$expire)."<BR><BR>";

if(getValue("SELECT COUNT(id) FROM siteactions WHERE tp = 'reademail'") == 0) 
	mysql_query("INSERT INTO `siteactions` (`id` ,`tp` ,`incPage` ,`actionType` ,`virtualPage` ,`isStandalonePage` ,`hasPreInclude` ,`pageName`) VALUES (NULL , 'reademail', 'reademail.php', 'website', '0', '1', '0', 'Read Paid Email');");
if(getValue("SELECT COUNT(id) FROM siteactions WHERE tp = 'toptaskmail'") == 0) 
	mysql_query("INSERT INTO `siteactions` (`id` ,`tp` ,`incPage` ,`actionType` ,`virtualPage` ,`isStandalonePage` ,`hasPreInclude` ,`pageName`) VALUES (NULL , 'toptaskmail', 'toptaskmail.php', 'website', '0', '1', '0', 'Read Paid Email Top Frame');");
if(getValue("SELECT COUNT(id) FROM siteactions WHERE tp = 'taskfinishmail'") == 0) 
	mysql_query("INSERT INTO `siteactions` (`id` ,`tp` ,`incPage` ,`actionType` ,`virtualPage` ,`isStandalonePage` ,`hasPreInclude` ,`pageName`) VALUES (NULL , 'taskfinishmail', 'taskfinishmail.php', 'website', '0', '1', '0', 'Read Paid Email Task Finish');");



$query = mysql_query("SELECT * FROM users WHERE paidEmails = '1'");

$count = mysql_num_rows($query);

if($count > 0) {
	
	$ptrurl = prepURL($ptrurl);
	
	for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query, $i);

		$user = mysql_fetch_array($query);

		$q = mysql_query("SELECT * FROM `reads` WHERE `fsize` > `freads` AND fnum NOT IN (SELECT fmailnum FROM mailreads WHERE fourid = ".quote_smart($user['username']).") ORDER BY RAND() LIMIT 0, 1") or die(mysql_error());

		$c = mysql_num_rows($q);

		if($c > 0) {

			$email = mysql_fetch_array($q);
			$mailformat = $email['mailformat'];
			$mailcoding = $email['mailcoding'];
			if($mailformat == 'html') $NL = "<BR>\n"; else $NL = "\n";
			
			$message=$ptrHeader.$email['fsubject']." ".$NL." ".$email['ftext']." ".$NL." Click this link to earn ".$ptr_pay_amount." ".$ptr_pay_type.": ".$NL."".$ptrurl."index.php?tp=reademail&id=".$user['username']."&username=".$user['username']."&mailcode=".$email['fnum'].$NL.$NL.$NL.$NL."---------------------------------------------".$NL."This email was sent to you as a paid email because you are signed up as a free member at $ptrname. To stop receiving these, simply log into your account, click My Account and update your email settings to not receive paid email requests.".$ptrFooter;

			$subject="Paid Email From ".$ptrname." - ".$email['fsubject'];
			
			if($mailformat != 'html') $mailformat = 'plain';
			if($mailcoding == '') $mailcoding = 'UTF-8';
			
			$headers="From: \"".$ptrname."\" <".$adminemail.">\r\nReply-To: ".$adminemail."\r\n"."Content-type: text/".$mailformat."; charset=".$mailcoding."\r\nMIME-Version: 1.0\r\n";

			if($echoDetailed) echo "Subject: ".$subject."<BR>Message<BR><pre width=\"300\">".$message."</pre><BR><BR>";

			if(getValue("SELECT COUNT(fnum) FROM `mailreads` WHERE fourid = ".quote_smart($user['username'])." AND `fmailnum`=".quote_smart($email['fnum'])."") > 0) {

				echo "This user has already received this email.<BR>";

			} else {

				$mail = mail($user['femail'], $subject, $message, $headers );

				if($mail) {

					echo "<FONT COLOR=GREEN><STRONG>SUCCESS</STRONG></FONT> - Sending email to user.<BR>";

				} else {

					echo "<FONT COLOR=RED><STRONG>FAILURE</STRONG></FONT> - Sending email to user.<BR>";

				}

			}

			//echo "EMail : ".$user['email']."<BR>Message: ".$message."<BR>Subject: ".$subject."<BR><HR>";

		} else {

			echo "Could not find a paid email to send to this user.<BR>";

		}

	}

} else {

	echo "There are no users set to receive paid emails at this time.<BR>";

}

?>