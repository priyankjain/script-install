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

$sq=mysql_query('SELECT * FROM setupinfo');

$ar=mysql_fetch_array($sq); 

@extract($ar);

if($refContestActive == '1') {

	if($refContestStart == '0' || $refContestEnd == '0') {

		echo "New Contest Setup.<BR>";

		$today = date("M d, Y"); //GET TIME AT 12AM

		$timestamp = strtotime($today);

		if($refContestRecurring == '1') {

			echo "Contest is set to last for ".$refContestLength." days.<BR>";

			echo "Setting end date to ".date("M d, Y",$timestamp+($refContestLength*24*60*60))."<BR>";

			mysql_query("UPDATE setupinfo SET refContestStart = ".quote_smart($timestamp).", refcontestEnd=".quote_smart($timestamp+($refContestLength*24*60*60))."");

			echo "Setup contest. Contest starts now.<BR>Not proceeding with rest of script since the contest just started, and no previous contest was set.<BR>";

			mysql_query("DELETE FROM refconteststats");

			exit;

		} else {

			echo "Contest has ended and is not set to recurring.<BR>";

		}

	} else {

		if($refContestEnd < time()) {

			

			echo "Referral contest has ended! Awarding prizes now, and setting up new contest if its recurring!<BR>Ended: ".date("M d Y g:ia",$refContestEnd)." which is > ".date("M d Y g:ia",time())."<BR><BR>";

			

			$query = mysql_query("SELECT * FROM refconteststats ORDER BY referrals DESC LIMIT 10");

			$count = mysql_num_rows($query);

			if($count > 0) {

				for($i = 0;$i < $count;$i++) {

					$place = $i+1;

					mysql_data_seek($query,$i);

					$arr = mysql_fetch_array($query);

					$q = mysql_query("SELECT * FROM referralbonus WHERE place = ".quote_smart($place)." ORDER BY place DESC") or die(mysql_error());

					$c = mysql_num_rows($q);

					if($c >0){ 

						for($k = 0;$k < $c;$k++) {

							mysql_data_seek($q,$k);

							$winnings = mysql_fetch_array($q);

							if($winnings['bonusType'] == 'cash') {

								echo "Awarding ".$arr['username']." with ".$setupinfo['currency'].$winnings['bonus']."<BR>";

								//mysql_query("UPDATE users SET refContestCash = refContestCash + ".quote_smart($winnings['bonus'])."");

								debitAccountBalance($arr['username'], 'credit', $winnings['bonus'], 'usd','referralContestWinnings');

							} else if($winnings['bonusType'] == 'points') {

								echo "Awarding ".$arr['username']." with ".$winnings['bonus']." Points.<BR>";

								//mysql_query("UPDATE users SET refContestPoints = refContestPoints + ".quote_smart($winnings['bonus'])."");

								debitAccountBalance($arr['username'], 'credit', $winnings['bonus'], 'points','referralContestWinnings');

							} else if($winnings['bonusType'] == 'advertising') {

								$pack_name=getValue("SELECT pack_name FROM packages WHERE fnum = ".quote_smart($winnings['bonus'])."");

								echo "Awarding ".$arr['username']." with ".$pack_name.".<BR>";

								$pQuery = mysql_query("SELECT * FROM packages WHERE fnum = ".quote_smart($winnings['bonus'])."");

								$pCount = mysql_num_rows($pQuery);

								if($pCount > 0) {

									$pArray = mysql_fetch_array($pQuery);

									if($pArray['packSpecial'] == '0') {

										mysql_query("INSERT INTO creditadditions (username, credits, creditsFor, additionDate,orderID) VALUES (".quote_smart($arr['username']).",".quote_smart($pArray['pack_credits']).",".quote_smart($pArray['pack_credits_type']).",NOW(),'0')");

										echo "Inserted credit additions.<BR>";

									} else {

										$sQuery = mysql_query("SELECT item FROM packitems WHERE package = ".quote_smart($winnings['bonus'])."");

										$sCount = mysql_num_rows($sQuery);

										if($sCount > 0) {

											for($sI = 0;$sI > $sCount;$sI++) {

												mysql_data_seek($sQuery,$sI);

												$sArray = mysql_fetch_array($sQuery);

												$pack = mysql_fetch_array(mysql_query("SELECT pack_credits, pack_credits_type FROM packages WHERE fnum = ".quote_smart($sArray['item']).""));

												/*

												mysql_query("INSERT INTO creditadditions (username, credits, creditsFor, additionDate,orderID)

												VALUES

												(".quote_smart($arr['username']).",".quote_smart($pack['pack_credits']).",".quote_smart($pack['pack_credits_type']).",NOW(),'0')");

												*/

												refundCredits($_SESSION['login'], $pack['pack_credits_type'], $pack['pack_credits']);

												echo "Inserted credit additions.<BR>";

											}

										}

									}

								} else {

									echo "Invalid package for this bonus type!<BR>";

								}

							} else if($winnings['bonusType'] == 'referrals') {

								echo "Awarding ".$arr['username']." with ".$winnings['bonus']." Referrals<BR>";

								$query = mysql_query("SELECT COUNT(id) AS orphans FROM users WHERE frefer = ''");

								$arr = mysql_fetch_array($query);

								if($arr['orphans'] >= $winnings['bonus']) {

									$sql = "UPDATE users SET frefer = ".quote_smart($arr['username'])." WHERE frefer = '' LIMIT ".$winnings['bonus'];

									$query = mysql_query($sql) or die(mysql_error());

									echo "Assigned ".$winnings['bonus']." referrals to ".$arr['username'];

								} else {

									echo "There are not enough referral orphans to assign! Assigning all.";

									$sql = "UPDATE users SET frefer = ".quote_smart($arr['username'])." WHERE frefer = '' LIMIT ".$winnings['bonus'];

									$query = mysql_query($sql) or die(mysql_error());

								}

							}

							

							$sql = mysql_query("select name, comments, value, subject from design where name ='emailRefContest'");

							$count = mysql_num_rows($sql);

							if($count > 0) {

								$arr = mysql_fetch_array($sql);

								$from="From: $adminemail";

								$message=$arr['value'];

								$subject = $ptrname.": ".$arr['subject'];

								@mail($email,$subject,$message,$from);

							}

							

						}

					} else {

						echo "No bonus found for Place ".$place."<BR>";

					}

					echo "<HR>";

				}

			} else {

				echo "There was no winner this round!<BR>";

			}

			echo "Resetting contest now.<BR>";

			if($refContestRecurring == '1') {

				echo "This contest is recurring. Resetting contest!<BR>";

				$timestamp = strtotime(date("M d, Y")); //GET TIME AT MIDNIGHT TODAY

				$start = $timestamp;

				$end = $timestamp + ($refContestLength * 24 * 60 * 60);

				mysql_query("UPDATE setupinfo SET refContestActive = '1', refContestStart = ".quote_smart($start).", refContestEnd = ".quote_smart($end)."") or die(mysql_error());

				echo "Set contest to active. Contest starts ".date("M d, Y", $start)." and Ends ".date("M d, Y",$end).".<BR>";

				mysql_query("DELETE FROM refconteststats");

				echo "Reset member statistics for the referral contest.<BR>";

			} else {

				echo "This contest is not recurring. Setting to default now.<BR>";

				mysql_query("UPDATE setupinfo SET refContestActive = '0', refContestStart = '0', refContestEnd = '0'");

				echo "Set contest to inactive and start / end to 0 to disable contest.<BR>";

				mysql_query("DELETE FROM refconteststats");

				echo "Reset member statistics for the referral contest.<BR>";

			}

		} else {

			$days = ($refContestEnd-time())/(24*60*60);

			$daysLeft = number_format($days,0,'.',',');

			echo "Exiting now, the contest has not ended yet. There are/is ".$daysLeft." day(s) left.<BR>";

		}

	}

}









?>