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



$id = $_REQUEST['id'];

$adType = $_REQUEST['adType'];

$action = $_REQUEST['action'];



if($adType == 'survey') {

	if($_REQUEST['addAnswer'] == 'Add Another Answer') {

		if($_REQUEST['questionType'] == 'radio' || ($_REQUEST['questionType'] == '' && $_SESSION['answerType'] == 'radio')) {

			if($_REQUEST['radioAnswer'] != '') {

				$_SESSION['answers'][] = $_REQUEST['radioAnswer']; 

				/*echo "<script type=\"text/javascript\">alert('Add this radio answer to your survey question.');</script>";*/

			} else {

				displayError("You cannot enter a blank radio button answer");

			}

			if($_REQUEST['questionType'] == 'radio') $_SESSION['answerType'] = $_REQUEST['questionType'];

		} else if($_REQUEST['questionType'] == 'dropdown' || ($_REQUEST['questionType'] == '' && $_SESSION['answerType'] == 'dropdown')) {

			if($_REQUEST['dropdownAnswer'] != '') {

				$_SESSION['answers'][] = $_REQUEST['dropdownAnswer']; 

			} else {

				displayError("You cannot enter a blank drop down menu answer");

			}

			if($_REQUEST['questionType'] == 'dropdown') $_SESSION['answerType'] = $_REQUEST['questionType'];

		} else if($_REQUEST['questionType'] == 'checkbox' || ($_REQUEST['questionType'] == '' && $_SESSION['answerType'] == 'checkbox')) {

			if($_REQUEST['checkboxAnswer'] != '') {

				$_SESSION['answers'][] = $_REQUEST['checkboxAnswer']; 

			} else {

				displayError("You cannot enter a blank check box answer");

			}

			if($_REQUEST['questionType'] == 'checkbox') $_SESSION['answerType'] = $_REQUEST['questionType'];

		}

		$continueEdit = TRUE;

	} else if($_REQUEST['addQuestion'] == 'Add This Question to my Survey') {

		

		if($_SESSION['answerType'] == 'radio' || $_REQUEST['answerType'] == 'dropdown' || $_REQUEST['answerType'] == 'checkbox') {

			if(count($_SESSION['answers']) > 1) {

				$continueScript = TRUE;

			} else {

				displayError("You must have at least 2 answers for your question answer type.");

				$continueScript = FALSE;

			}

		} else {

			$continueScript = TRUE;

		}

		if($continueScript == TRUE) {

			if(is_array($_SESSION['surveyquestions'])) {

				foreach($_SESSION['surveyquestions'] as $k => $v) {

					if($_REQUEST['question'] == $v['question']) {

						$continueScript = FALSE;

						displayError("Error: Duplication Question\\n\\nThis question is already in this survey.");

						break;

					}

				}

			}

		}

		if($continueScript == TRUE) {

			

			/*echo "<script type=\"text/javascript\">alert('Adding new survey.');</script>";*/

			if(is_array($_SESSION['surveyquestions'])) {

				$counter = count($_SESSION['surveyquestions']);

			} else {

				$counter = 0;

			}

			if($_REQUEST['questionType'] == '') {

				$questionType = $_SESSION['answerType'];

			} else {

				$questionType = $_REQUEST['questionType'];

			}

			

			/*echo "<script type=\"text/javascript\">alert('Counter = " . $counter.".');</script>";*/

			$_SESSION['surveyquestions'][$counter]['question'] = $_REQUEST['question'];

			$_SESSION['surveyquestions'][$counter]['optionType'] = $questionType;

			/*echo "<script type=\"text/javascript\">alert('Question = " . $_REQUEST['question'].".');</script>";*/

			/*echo "<script type=\"text/javascript\">alert('Question Type = " . $questionType.".');</script>";*/

			$i = 0;

			if($questionType != 'text') {

				foreach($_SESSION['answers'] as $k => $v) {

					$_SESSION['surveyquestions'][$counter]['options'][$i]['optionId'] = $i;

					$_SESSION['surveyquestions'][$counter]['options'][$i]['optionName'] = $i;

					$_SESSION['surveyquestions'][$counter]['options'][$i]['optionValue'] = $v;

					/*echo "<script type=\"text/javascript\">alert('New Option = " . $v.".');</script>";*/

					$i++;

				}

			}

			

			unset($_SESSION['answerType']);

			unset($_SESSION['answers']);

			unset($_REQUEST['question']);

			//echo "<pre>".print_r($_SESSION,1)."</pre>";

		}

		

		$continueEdit = TRUE;

	} else if($_REQUEST['Submit'] == 'Add This Survey') {

		/*

		$_SESSION['surveyquestions'][$counter]['question'] = $_REQUEST['question'];

		$_SESSION['surveyquestions'][$counter]['optionType'] = $questionType;

		$i = 0;

		if($questionType != 'text') {

			foreach($_SESSION['answers'] as $k => $v) {

				$_SESSION['surveyquestions'][$counter]['options'][$i]['optionId'] = $i;

				$_SESSION['surveyquestions'][$counter]['options'][$i]['optionName'] = $i;

				$_SESSION['surveyquestions'][$counter]['options'][$i]['optionValue'] = $v;

				$i++;

			}

		}

		*/
if($demoMode === TRUE) {
			echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		$sql = "INSERT INTO surveys (
			username,
			fsize,
			fviews,
			siteurl,
			flogin,
			fpassword,
			surveyname
		) VALUES (
			".quote_smart($_REQUEST['adLogin']).",
			'0',
			'0',
			".quote_smart($_REQUEST['siteurl']).",
			".quote_smart($_REQUEST['adLogin']).",
			".quote_smart($_REQUEST['adPassword']).",
			".quote_smart($_REQUEST['surveyname'])."
		)";

		$query = mysql_query($sql) or die(mysql_error());

		$surveyID = mysql_insert_id();

		

		$optionID = 0;

		foreach($_SESSION['surveyquestions'] as $k => $v) {

		  $optionID = $optionID + 1;

		  if(is_array($v['options'])) { //MULTIPLE CHOICE, RADIO BUTTON, CHECKBOX OR DROP DOWN

				$question = $v['question'];

				if($v['optionType'] == 'radio') {

					foreach($v['options'] as $key => $value) {

						//echo "<input type=\"radio\" name=\"".$value['optionId']." value=\"".$value['optionName']."\"> ".$value['optionValue']."<BR>"; 

						mysql_query("INSERT INTO surveyquestions (

							surveyID,

							question,

							optionName,

							`option`,

							optionValue,

							optionType

						) VALUES (

							".quote_smart($surveyID).",

							".quote_smart($question).",

							".quote_smart($value['optionValue']).",

							".quote_smart($optionID).",

							".quote_smart($value['optionId']).",

							".quote_smart($v['optionType'])."

						)") or die("LINE: ".__LINE__." ERROR: ".mysql_error());

					}

				} else if($v['optionType'] == 'dropdown') {

					//echo "<select name=\"".$value['options'][0]['optionId'].">\n";

					foreach($v['options'] as $key => $value) {

						mysql_query("INSERT INTO surveyquestions (

							surveyID,

							question,

							optionName,

							`option`,

							optionValue,

							optionType

						) VALUES (

							".quote_smart($surveyID).",

							".quote_smart($question).",

							".quote_smart($value['optionValue']).",

							".quote_smart($optionID).",

							".quote_smart($value['optionId']).",

							".quote_smart($v['optionType'])."

						)") or die("LINE: ".__LINE__." ERROR: ".mysql_error());

					}

					//echo "</select>";

				} else if($v['optionType'] == 'checkbox') {

					foreach($v['options'] as $key => $value) {

						mysql_query("INSERT INTO surveyquestions (

							surveyID,

							question,

							optionName,

							`option`,

							optionValue,

							optionType

						) VALUES (

							".quote_smart($surveyID).",

							".quote_smart($question).",

							".quote_smart($value['optionValue']).",

							".quote_smart($optionID).",

							".quote_smart($value['optionId']).",

							".quote_smart($v['optionType'])."

						)") or die("LINE: ".__LINE__." ERROR: ".mysql_error());

					}

				} else if($v['optionType'] == 'text') {

					//foreach($v['options'] as $key => $value) {

						//echo $value['optionValue'].": <input type=\"text\" name=\"".$value['optionId']." value=\"".$value['optionName']."\"><BR>"; 

						mysql_query("INSERT INTO surveyquestions (

							surveyID,

							question,

							optionName,

							`option`,

							optionValue,

							optionType

						) VALUES (

							".quote_smart($surveyID).",

							".quote_smart($question).",

							".quote_smart('').",

							".quote_smart($optionID).",

							".quote_smart('').",,

							".quote_smart($v['optionType'])."

						)") or die("LINE: ".__LINE__." ERROR: ".mysql_error());

					//}

				}

			} else { //NOT MULTIPLE CHOICE, ANSWER THE QUESTION TYPE

				$question = $v['question'];

				mysql_query("INSERT INTO surveyquestions (

					surveyID,

					question,

					optionName,

					`option`,

					optionValue,

					optionType

				) VALUES (

					".quote_smart($surveyID).",

					".quote_smart($question).",

					".quote_smart('').",

					".quote_smart($optionID).",

					".quote_smart('').",

					".quote_smart('text')."

				)") or die("LINE: ".__LINE__." ERROR: ".mysql_error());

				//echo "<strong>Q:</strong> ".$v['question']."<BR>A: (User Input Form)<BR>";

			}

		} //END FOR LOOP THROUGH QUESTIONS

		unset($_SESSION['surveyquestions']);

		unset($_SESSION['answerType']);

		unset($_SESSION['answers']);

		unset($_REQUEST['question']);

		unset($_REQUEST['surveyname']);

		unset($_REQUEST['siteurl']);

		unset($_REQUEST['questionType']);

		unset($_REQUEST['radioAnswer']);

		unset($_REQUEST['checkboxAnswer']);

		unset($_REQUEST['dropdownAnswer']);
} //END DEMO MODE CHECK
	} else if($_REQUEST['Submit'] == 'Start Over from Scratch') {

		unset($_SESSION['surveyquestions']);

		unset($_SESSION['answerType']);

		unset($_SESSION['answers']);

		unset($_REQUEST['question']);

		unset($_REQUEST['surveyname']);

		unset($_REQUEST['siteurl']);

		unset($_REQUEST['questionType']);

		unset($_REQUEST['radioAnswer']);

		unset($_REQUEST['checkboxAnswer']);

		unset($_REQUEST['dropdownAnswer']);

	}

}



//FEATURED AD SQL CALLS

if($act=='addfad') {

	$description = substr($description, 0, 40);

	$_REQUEST['name'] = substr($_REQUEST['name'], 0, 15);

	if(getValue("SELECT COUNT(fnum) FROM featuredads WHERE fname = ".quote_smart($_REQUEST['name'])." AND description = ".quote_smart($description)." AND flink = ".quote_smart($flink)." AND ftitle = ".quote_smart($ftitle)." AND username = ".quote_smart($_REQUEST['adLogin'])."") == 0) {
if($demoMode === TRUE) {
			echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		mysql_query("INSERT INTO featuredads (fnum, fname, femail, fsize, description, flink, ftitle, flogin, fpassword,username) VALUES ('', ".quote_smart($_REQUEST['name']).", ".quote_smart($email).", ".quote_smart(0).", ".quote_smart($description).", ".quote_smart($flink).", ".quote_smart($ftitle).", ".quote_smart($_REQUEST['adLogin']).", ".quote_smart($adPassword).", ".quote_smart($_REQUEST['adLogin'])." )") or die(mysql_error());
}
	}

	displaySuccess("NEW BANNER HAS BEEN ADDED");

}



//FEATURED AD SQL CALLS

if($act=='addflink') {

	$description = substr($description, 0, 40);

	$_REQUEST['name'] = substr($_REQUEST['name'], 0, 15);

	if(getValue("SELECT COUNT(fnum) FROM featuredlinks WHERE fname = ".quote_smart($_REQUEST['name'])." AND flink = ".quote_smart($flink)." AND ftitle = ".quote_smart($ftitle)." AND username = ".quote_smart($_REQUEST['adLogin'])."") == 0) {
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		mysql_query("INSERT INTO featuredlinks (fnum, fname, femail, fsize, flink, ftitle, flogin, fpassword,username) VALUES ('', ".quote_smart($_REQUEST['name']).", ".quote_smart($email).", ".quote_smart(0).", ".quote_smart($flink).", ".quote_smart($ftitle).", ".quote_smart($_REQUEST['adLogin']).", ".quote_smart($adPassword).", ".quote_smart($_REQUEST['adLogin'])." )") or die(mysql_error());
}
	}

	displaySuccess("NEW FEATURED LINK HAS BEEN ADDED");

}





//BANNER SQL CALLS

if($act == 'addBanner'){

	if(getValue("SELECT COUNT(fnum) FROM banners WHERE fname = ".quote_smart($_REQUEST['name'])." AND femail = ".quote_smart($_REQUEST['email'])." AND flink = ".quote_smart($flink)." AND furl = ".quote_smart($_REQUEST['furl'])." AND username = ".quote_smart($_REQUEST['adLogin'])."") == 0) { 
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		mysql_query("INSERT INTO banners (fname, femail, fsize, fbanercode, flink, furl, flogin, fpassword, username)

		VALUES

		(".quote_smart($_REQUEST['name']).", ".quote_smart($email).", ".quote_smart('0').", ".quote_smart("<a href=\"$flink\"><img src=\"".$_REQUEST['furl']."\" width=\"482\" height=\"60\"></a>").", ".quote_smart($flink).", ".quote_smart($_REQUEST['furl']).", ".quote_smart($_REQUEST['adLogin']).", ".quote_smart($adPassword).",".quote_smart($_REQUEST['adLogin']).")") or die(mysql_error());
}
		displaySuccess("NEW BANNER HAS BEEN ADDED");

	}

}



//FEATURED BANNER SQL CALLS

if($act=='addfbanner'){

	if(getValue("SELECT COUNT(fnum) FROM fbanners WHERE fname = ".quote_smart($_REQUEST['name'])." AND femail = ".quote_smart($_REQUEST['email'])." AND flink = ".quote_smart($flink)." AND furl = ".quote_smart($_REQUEST['furl'])." AND username = ".quote_smart($_REQUEST['adLogin'])."") == 0) { 
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		mysql_query("INSERT INTO fbanners (fname, femail, fsize, fbanercode, flink, furl, flogin, fpassword,username) VALUES (".quote_smart($_REQUEST['name']).", ".quote_smart($email).", ".quote_smart(0).", ".quote_smart($bancode).", ".quote_smart($flink).", ".quote_smart($_REQUEST['furl']).", ".quote_smart($_REQUEST['adLogin']).", ".quote_smart($adPassword).",".quote_smart($_REQUEST['adLogin'])." )") or die(mysql_error());

		displaySuccess("NEW FEATURED BANNER HAS BEEN ADDED");
}
	} else {

		displayError("DUPLICATE ENTRY");

	}

}



//LINKS SQL CALLS

if($act=='addlinks'){

	$qr = "SELECT COUNT(fn) FROM tasks WHERE fsitename = ".quote_smart($_REQUEST['sitename'])." AND furl = ".quote_smart($_REQUEST['url'])." AND username = ".quote_smart($_REQUEST['adLogin'])."";

	//echo "Running " . $qr."<HR>";

	$cnt = getValue($qr);

	//echo "Cnt=".$cnt."<BR>";

	if($cnt == 0) { /*
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {*/
		$query = "INSERT INTO tasks

		(fsize,furl,fsitename,fvisits,flog,fpass,startdate,enddate,prise,fpaytype,username)

		VALUES('0',".quote_smart($_REQUEST['url']).",".quote_smart($_REQUEST['sitename']).",0,".quote_smart($_REQUEST['adLogin']).",".quote_smart($adPassword).",'','','1','points',".quote_smart($_REQUEST['adLogin']).")";

		//echo "Running Query: ".$query;

		$sql = mysql_query($query) or die(mysql_error());

		if(mysql_affected_rows()) displaySuccess("NEW VISIT/PTC CAMPAIGN ADDED SUCCESSFULLY");
//} //END DEMO MODE
	}

}



//LINKS SQL CALLS

if($act=='addptrads'){

	$qr = "SELECT COUNT(fn) FROM ptrads WHERE fsitename = ".quote_smart($_REQUEST['sitename'])." AND furl = ".quote_smart($_REQUEST['url'])." AND username = ".quote_smart($_REQUEST['adLogin'])."";

	//echo "Running " . $qr."<HR>";

	$cnt = getValue($qr);

	//echo "Cnt=".$cnt."<BR>";

	if($cnt == 0) { 
/*if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {*/
		$query = "INSERT INTO ptrads

		(fsize,furl,fsitename,fvisits,flog,fpass,startdate,enddate,prise,fpaytype,ptrad,username)

		VALUES('0',".quote_smart($_REQUEST['url']).",".quote_smart($_REQUEST['sitename']).",0,".quote_smart($_REQUEST['adLogin']).",".quote_smart($adPassword).",'','','1','points',".quote_smart($_REQUEST['ptrad']).",".quote_smart($_REQUEST['adLogin']).")";

		//echo "Running Query: ".$query;

		$sql = mysql_query($query) or die(mysql_error());

		if(mysql_affected_rows()) displaySuccess("NEW PAID TO READ CAMPAIGN ADDED SUCCESSFULLY");
//} //END DEMO MODE
	}

}

if($act=='addsignup'){

	if(getValue("SELECT COUNT(fnum) FROM signups WHERE fsitename = ".quote_smart($_REQUEST['sitename'])." AND fname = ".quote_smart($_REQUEST['fname'])." AND furl = ".quote_smart($url)." AND username = ".quote_smart($_REQUEST['adLogin'])."") == 0) { 
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		$sql=mysql_query("

		INSERT INTO signups

		(fmail, fname, fsitename, fnote, fsize, furl, flogin, fpassword, fprise, fpaytype, squest, sansw,username)

		VALUES

		(".quote_smart($email).", ".quote_smart($_REQUEST['name']).", ".quote_smart($_REQUEST['sitename']).", ".quote_smart($note).", ".quote_smart('0').", ".quote_smart($url).", ".quote_smart($_REQUEST['adLogin']).", ".quote_smart($adPassword).", '1', 'points', ".quote_smart($squest).", ".quote_smart($sansw).",".quote_smart($_REQUEST['adLogin']).")")or die(mysql_error());

		if(mysql_affected_rows())displaySuccess("NEW SIGNUP CAMPAIGN ADDED SUCCESFULLY!");
} //END DEMO MODE
	}

}

if($act=='addemail'){

	if(getValue("SELECT COUNT(fnum) FROM `reads` WHERE furl = ".quote_smart($_REQUEST['mailurl'])." AND fsubject = ".quote_smart($_REQUEST['subject'])." AND ftext = ".quote_smart($_REQUEST['mailcontent'])." AND username = ".quote_smart($_REQUEST['adLogin'])."") == 0) { 
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
 		mysql_query("INSERT INTO `reads` (furl, fsubject, ftext, fprise, flog, fpass, fsize, fpaytype,username,mailformat) VALUES (".quote_smart($_REQUEST['mailurl']).", ".quote_smart($_REQUEST['subject']).", ".quote_smart($_REQUEST['mailcontent']).", ".quote_smart($_REQUEST['prise']).", ".quote_smart($_REQUEST['log']).", ".quote_smart($_REQUEST['pass']).", '0', ".quote_smart($_REQUEST['paytype']).",".quote_smart($_REQUEST['adLogin']).",".quote_smart($_REQUEST['mailformat']).")") or die('error');

		if(mysql_affected_rows()) {

			displaySuccess("NEW EMAIL CAMPAIGN ADDED SUCCESFULLY!");

		} else {

			displayError("COULD NOT ADD NEW EMAIL CAMPAIGN");

		}
} //END DEMO MODE
	}

}




if($action == 'addCreditsNow') {
	$error = '';
	if($adTypes[$adType]['hasTypes'] == TRUE) {
			
			$creditsUsed = getValue("SELECT `".$adTypes[$adType]['credits']."` - `".$adTypes[$adType]['usedCredits']."` FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
			
			if($creditsUsed < 1) $editable = TRUE; else $editable = FALSE;
			
			$credits = $adTypes[$adType]['prefix'].'PayCredits';
			$credits = $setupinfo[$credits];
			$credits = explode(',',$credits);
			
			$names = $adTypes[$adType]['prefix'].'PayNames';
			$names = $setupinfo[$names];
			$names = explode(',',$names);
			
			if($editable == FALSE) {
				$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
			} else {
				$creditID = $_REQUEST['creditID'];
			}
			
			$multiplier = $credits[$creditID];
	} else {
		$multiplier = 1;	
	}
	//$maxCredits = totalBannerCredits($_SESSION['login'], $adType);
	$cost = $_REQUEST['creditsToAdd'] * $multiplier; 
	/*if($cost > $maxCredits) {
		$error = 'An error has occurred - Your ad will cost '.$cost.' credits, and you only have '.$maxCredits.' available.';
		$action = 'addCredits';
	}*/
	if($error != '') {
		echo "<script> alert('".$error."'); </script>";
	} else if($_REQUEST['id'] == '') {
		echo "<script> alert('Invalid selection!'); </script>";
	} else {
		$valid = FALSE;
		$table = '';
		if($adType == '' || $adType == 'banner') {
			$adType = 'banner';
			$table = 'banners';
			$valid = TRUE;
		} else if($adType == 'fbanner' || $adType == 'fbanners') {
			$adType = 'fbanner';
			$table = 'fbanners';
			$valid = TRUE;
		} else if($adType == 'fad') {
			$table = 'featuredads';
			$valid = TRUE;
		} else if($adType == 'flinks') {
			$table = 'featuredlinks';
			$valid = TRUE;
		} else if($adType == 'links' || $adType == 'link') {
			$adType = 'links';
			$table = 'tasks';
			$valid = TRUE;
		} else if($adType == 'signup') {
			$table = 'signups';
			$valid = TRUE;
		} else if($adType == 'survey') {
			$table = 'surveys';
			$valid = TRUE;
		} else if($adType == 'email') {
			$table = 'reads';
			$valid = TRUE;
		} else if($adType == 'ptrad') {
			$table = 'ptrads';
			$valid = TRUE;
		}
		
		if($valid != TRUE) {
			echo "<script> alert('Your campaign could not be updated...'); </script>";
		} else {
			if($adType == 'links' || $adType == 'ptrad') { $idName = 'fn'; } else if($adType == 'survey') { $idName = 'id'; } else { $idName = 'fnum'; }
			$query = mysql_query("SELECT fsize, `$idName` AS campaignID FROM `$table` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($_REQUEST['id'])."") or die(mysql_error());
			
			$count = mysql_num_rows($query);
			if($count > 0) {
				$arr = mysql_fetch_array($query);
				
				$creditID = $arr['creditID'];
				$campaignID = $arr['campaignID'];
				$fsize = $arr['fsize'];
				
				if($adTypes[$adType]['hasTypes'] == TRUE) $creditID = getValue("SELECT creditID FROM `".$table."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($_REQUEST['id']).""); else $creditID = '';
				if($creditID != $_REQUEST['creditID'] && $fsize == 0 && is_numeric($_REQUEST['creditID']) && $adTypes[$adType]['hasTypes'] == TRUE) {
					mysql_query("UPDATE `".$table."` SET creditID = ".quote_smart($_REQUEST['creditID'])." WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($_REQUEST['id'])."");
					//echo 'Updated creditID to '.$_REQUEST['creditID'].'<BR>';
				}
				if($adTypes[$adType]['hasTypes'] == TRUE) $creditID = getValue("SELECT creditID FROM `".$table."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($_REQUEST['id']).""); else $creditID = '';
				
				
				if($adTypes[$adType]['hasTypes'] == TRUE) {
					$creditsUsed = getValue("SELECT `".$adTypes[$adType]['credits']."` - `".$adTypes[$adType]['usedCredits']."` FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
					
					if($creditsUsed < 1) $editable = TRUE; else $editable = FALSE;
					
					mysql_query("UPDATE `$table` SET `creditID` = ".quote_smart($_REQUEST['creditID'])." WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($_REQUEST['id'])."");
					
					$multipliers = $adTypes[$adType]['prefix'].'PayCredits';
					$multipliers = $setupinfo[$multipliers];
					$multipliers = explode(',',$multipliers);
					
					$multiplier = $multipliers[$creditID];
					//echo 'Set multiplier to $multipliers['.$creditID.']; ('.$multiplier.');<BR>';
					if($multiplier == '' || $multiplier == 0) $multiplier = 1;
				} else {
					$multiplier = 1;	
				}
				
				//$maxCredits = totalBannerCredits($_SESSION['login'], $adType);
				$cost = $_REQUEST['creditsToAdd'] * $multiplier; 
				
				if($_REQUEST['creditsToAdd'] > 0) {
					
					$credits = ($_REQUEST['creditsToAdd']*$multiplier);
					
					//$maxCredits = totalBannerCredits($_SESSION['login'], $adType);
					/*
					echo 'Multiplier = '.$multiplier.'<BR>CreditsToAdd = '.$_REQUEST['creditsToAdd'].'<BR>Cost = '.$cost.'<BR>Credits = '.$credits.'<BR>MaxCredits = '.$maxCredits.'<BR>FSize = '.$fsize.'<BR>';
					*/
					
					//if($cost <= $maxCredits) {
						//echo "Setting the current ".$fsize." to ".($fsize+$_REQUEST['creditsToAdd']).".<BR>Added ".$_REQUEST['creditsToAdd']."...<BR>";
						//echo 'Debit = '.$credits.'<BR>';
						//$debit = debitAccount($_SESSION['login'], $adType, $credits,$_REQUEST['id']);
						//if($debit) {
							$fsize = $fsize + ($credits / $multiplier);
							//echo 'FSize Now = '.$fsize.'<BR>';
							mysql_query("UPDATE `$table` SET `fsize` = ".quote_smart($fsize)." WHERE `username` = ".quote_smart($_SESSION['login'])." AND `$idName` = ".quote_smart($_REQUEST['id'])."");
							echo "<script> alert('Credits added!'); </script>";
						/*} else {
							echo "<script> alert('Could not update campaign. Debit of credits failure'); </script>";
						}*/
						
					//} else {
						/*echo "<script> alert('Insuffecient Credits to add.'); </script>";*/
					//}
				} else {
					$NL = "<BR>\n";
					$message=" ".$NL."A Possible Hack attempt has been performed to undermine the advertising system by ".$_SESSION['login']." ".$NL.$NL."
					We recommend suspending this user, but first we recommend finding all accounts for their IP Address. ".$NL.$NL."
					Their IP address is : ".$_SERVER['REMOTE_ADDR']." ".$NL."";
					$subject="".$setupinfo['ptrname']." Possible Hack Attempt on Manage Ads (Negative Integer Detected)";
					$mailformat = 'html';
					$headers="From: \"".$setupinfo['ptrname']."\" <".$setupinfo['adminemail'].">\r\nReply-To: ".$setupinfo['adminemail']."\r\n"."Content-type: text/".$mailformat."; charset=us-ASCII\r\nMIME-Version: 1.0\r\n";
					$mail = mail($setupinfo['adminemail'], $subject, $message, $headers );
					echo "<script> alert('Negative integer detected. Possible Hack attempt notice has been sent to administration.'); </script>";
				}
			} else {
				echo "<script> alert('Could not update campaign.'); </script>";
			}
		}
	}

}



if($action == 'retractCreditsNow') {
	if($_REQUEST['id'] == '') {
		echo "<script> alert('Invalid selection!'); </script>";
	} else {
		$valid = FALSE;
		if($adType == '' || $adType == 'banner') {
			$adType = 'banner';
			$table = 'banners';
			$valid = TRUE;
		} else if($adType == 'fbanner' || $adType == 'fbanners') {
			$adType = 'fbanner';
			$table = 'fbanners';
			$valid = TRUE;
		} else if($adType == 'fad') {
			$table = 'featuredads';
			$valid = TRUE;
		} else if($adType == 'flinks') {
			$table = 'featuredlinks';
			$valid = TRUE;
		} else if($adType == 'links' || $adType == 'link') {
			$adType = 'links';
			$table = 'tasks';
			$valid = TRUE;
		} else if($adType == 'ptrad') {
			$table = 'ptrads';
			$valid = TRUE;
		} else if($adType == 'signup') {
			$table = 'signups';
			$valid = TRUE;
		} else if($adType == 'email') {
			$table = 'reads';
			$valid = TRUE;
		} else if($adType == 'survey') {
			$table = 'surveys';
			$valid = TRUE;
		}
		
		$activeCredits = creditsLeft($_REQUEST['id'],$_REQUEST['adType']);
		if($activeCredits <= $_REQUEST['creditsToRetract']) { $_REQUEST['creditsToRetract'] = $activeCredits; }
		if(is_numeric($_REQUEST['creditsToRetract']) && $activeCredits >= $_REQUEST['creditsToRetract']) {
			if($valid != TRUE) {
				echo "<script> alert('Your campaign could not be updated...'); </script>";
			} else {
				if($adType == 'links' || $adType == 'ptrad') { $idName = 'fn'; } else if($adType == 'survey') { $idName = 'id'; } else { $idName = 'fnum'; }
				$query = mysql_query("SELECT fsize, `$idName` AS campaignID, creditID FROM `$table` WHERE `$idName` = ".quote_smart($_REQUEST['id'])."") or die(mysql_error());
				$count = mysql_num_rows($query);
				if($count > 0) {
					$arr = mysql_fetch_array($query);
					
					$creditID = $arr['creditID'];
					$campaignID = $arr['campaignID'];
					$fsize = $arr['fsize'];
					
					if($creditID != $_REQUEST['creditID'] && $fsize == 0 && is_numeric($_REQUEST['creditID'])) mysql_query("UPDATE `".$table."` SET creditID = ".quote_smart($_REQUEST['creditID'])." WHERE `".$idName."=".quote_smart($_REQUEST['id'])."");
					
					if($adTypes[$adType]['hasTypes'] == TRUE) {
						$creditsUsed = getValue("SELECT `".$adTypes[$adType]['credits']."` - `".$adTypes[$adType]['usedCredits']."` FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
						
						if($creditsUsed < 1) $editable = TRUE; else $editable = FALSE;
						
						mysql_query("UPDATE `$table` SET `creditID` = ".quote_smart($_REQUEST['creditID'])." WHERE `$idName` = ".quote_smart($_REQUEST['id'])."");
						
						$credits = $adTypes[$adType]['prefix'].'PayCredits';
						$credits = $setupinfo[$credits];
						$credits = explode(',',$credits);
						
						$multiplier = $credits[$creditID];
					} else {
						$multiplier = 1;	
					}
					
					$arr = getValue("SELECT * FROM `".$table."` WHERE `".$idName."`=".quote_smart($_REQUEST['id'])."");
					$creditID = $arr['creditID'];
					$username = $arr['username'];
					
					$campaignID = $arr['campaignID'];
					$fsize = $arr['fsize'];
					if($_REQUEST['creditsToRetract'] > 0) {
						if($_REQUEST['creditsToRetract'] <= $activeCredits) {
							//echo "Setting the current ".$fsize." to ".($fsize+$_REQUEST['creditsToAdd']).".<BR>Added ".$_REQUEST['creditsToAdd']."...<BR>";
							$credit = creditAccount($username, $adType, $_REQUEST['creditsToRetract']*$multiplier,$_REQUEST['id']);
							if($credit) { 
								mysql_query("UPDATE `$table` SET fsize = ".quote_smart(($fsize-$_REQUEST['creditsToRetract']))." WHERE `$idName` = ".quote_smart($_REQUEST['id'])."");
								echo "<script> alert('Credits retracted!'); </script>";
							} else {
								echo "<script> alert('Could not update campaign. Debit of credits failure'); </script>";
							}
							
						} else {
							echo "<script> alert('Insuffecient Credits to retract.'); </script>";
						}
					} else {
					
						$NL = "<BR>\n";
						$message=" ".$NL."A Hack attempt has been performed to undermine the advertising system by ".$_SESSION['login']." ".$NL.$NL."
						We recommend suspending this user, but first we recommend finding all accounts for their IP Address. ".$NL.$NL."
						Their IP address is : ".$_SERVER['REMOTE_ADDR']." ".$NL."";
						$subject="".$setupinfo['ptrname']." Hack Attempt on Manage Ads (Negative Integer Detected)";
						$mailformat = 'html';
						$headers="From: \"".$setupinfo['ptrname']."\" <".$setupinfo['adminemail'].">\r\nReply-To: ".$setupinfo['adminemail']."\r\n"."Content-type: text/".$mailformat."; charset=us-ASCII\r\nMIME-Version: 1.0\r\n";
						$mail = mail($setupinfo['adminemail'], $subject, $message, $headers );
	
						echo "<script> alert('A hack attempt has been detected. Emailing Administration with your details.'); </script>";
					}
				} else {
					echo "<script> alert('Could not update campaign.'); </script>";
				}
			}
		} else {
			echo "<script> alert('Could not update campaign.'); </script>";
		}
	}

}

if($action == 'remove' && $adType != '' && $id != '' && is_numeric($id)) {
	if($adType == 'banner') {
		$array = getArray("SELECT fclicks, fshows, fsize,username FROM banners WHERE fnum = ".quote_smart($id)."") or die(mysql_error());
		$credits = $array['fsize']-$array['fshows'];
		if($credits > 0) refundCredits($array['username'], $adType, $credits);
		if($demoMode === TRUE) {
			echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
		} else {
				mysql_query("DELETE FROM banners WHERE fnum=".quote_smart($id)." ") or die(mysql_error());
		}
		displaySuccess("BANNER REMOVED! Refunded ".$credits." credits.");

	} else if($adType == 'fbanner') {

		$array = getArray("SELECT fclicks, fshows, fsize,username FROM fbanners WHERE fnum = ".quote_smart($id)."") or die(mysql_error());

		$credits = $array['fsize'] - $array['fshows'];
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		if($credits > 0) refundCredits($array['username'], $adType, $credits);

		$sq = "DELETE FROM fbanners WHERE fnum=".quote_smart($id)." AND username = ".quote_smart($array['username'])."";

		mysql_query($sq) or die(mysql_error());
}
		displaySuccess("FEATURED BANNER REMOVED! Refunded ".$credits." credits.");

	} else if($adType == 'fad') {

		$array = getArray("SELECT fclicks, fshows, fsize,username FROM featuredads WHERE fnum = ".quote_smart($id)."") or die(mysql_error());

		$credits = $array['fsize']-$array['fshows'];
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		if($credits > 0) refundCredits($array['username'], $adType, $credits);

		mysql_query("DELETE FROM featuredads WHERE fnum=".quote_smart($id)."");
}
		displaySuccess("FEATURED AD REMOVED! Refunded ".$credits." credits.");
	} else if($adType == 'flinks') {

		$array = getArray("SELECT fclicks, fshows, fsize,username FROM featuredlinks WHERE fnum = ".quote_smart($id)."") or die(mysql_error());

		$credits = $array['fsize']-$array['fshows'];
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		if($credits > 0) refundCredits($array['username'], $adType, $credits);

		mysql_query("DELETE FROM featuredlinks WHERE fnum=".quote_smart($id)."") or die(mysql_error());
}
		displaySuccess("FEATURED LINK REMOVED! Refunded ".$credits." credits.");

	} else if($adType == 'links') {

		$array = getArray("SELECT fvisits, fsize,username FROM tasks WHERE fn = ".quote_smart($id)."") or die(mysql_error());

		$credits = $array['fsize']-$array['fvisits'];
/*if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {*/
		if($credits > 0) refundCredits($array['username'], $adType, $credits);

		mysql_query("DELETE FROM tasks WHERE fn=".quote_smart($id)."") or die(mysql_error());
//}
		displaySuccess("VISIT/PTC TASK REMOVED! Refunded ".$credits." credits.");

	} else if($adType == 'ptrad') {

		$array = getArray("SELECT fvisits, fsize,username FROM ptrads WHERE fn = ".quote_smart($id)."") or die(mysql_error());

		$credits = $array['fsize']-$array['freads'];
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		if($credits > 0) refundCredits($array['username'], $adType, $credits);

		mysql_query("DELETE FROM ptrads WHERE fn=".quote_smart($id)."") or die(mysql_error());
}
		displaySuccess("PAID TO READ ADS TASK REMOVED! Refunded ".$credits." credits.");

	} else if($adType == 'email') {

		$array = getArray("SELECT freads, fsize, username FROM `reads` WHERE fnum = ".quote_smart($id)."") or die(mysql_error());

		$credits = $array['fsize']-$array['freads'];
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		if($credits > 0) refundCredits($array['username'], $adType, $credits);

		mysql_query("DELETE FROM `reads` WHERE fnum=".quote_smart($id)."") or die(mysql_error());
}
		displaySuccess("EMAIL REMOVED! Refunded ".$credits." credits.");

	} else if($adType == 'signup') {

		$array = getArray("SELECT fsignups, fsize, username FROM signups WHERE fnum = ".quote_smart($id)."") or die(mysql_error());

		$credits = $array['fsize']-$array['fsignups'];
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		if($credits > 0) refundCredits($array['username'], $adType, $credits);

		mysql_query("DELETE FROM signups WHERE fnum=".quote_smart($id)."") or die(mysql_error());
}
		displaySuccess("SIGNUP CAMPAIGN REMOVED! Refunded ".$credits." credits.");

	} else if($adType == 'survey') {

		$array = getArray("SELECT fviews, fsize FROM surveys WHERE id = ".quote_smart($id)."");

		$credits = $array['fsize'] - $array['fviews'];
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		if($credits > 0) refundCredits($_SESSION['login'], $adType, $credits);

		mysql_query("DELETE FROM surveys WHERE id=".quote_smart($id)."");

		mysql_query("DELETE FROM surveyquestions WHERE surveyID=".quote_smart($id)."");

		mysql_query("DELETE FROM surveyactivity WHERE surveyID=".quote_smart($id)."");
}
		displaySuccess("PAID SURVEY REMOVED! Refunded ".$credits." credits.");

	} else {

		displayError("Invalid Ad type !");

	}

}

?>