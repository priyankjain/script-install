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
include_once("manageadspre.php");
?><script language="javascript" type="text/javascript">
<!--
function showAdvertiserDetails() {
	divBlock = document.getElementById('advertiserDetails');
	divBlock.style.display = 'block';
}
function hideAdvertiserDetails() {
	divBlock = document.getElementById('advertiserDetails');
	divBlock.style.display = 'none';
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function verifyAddCredits(maxCredits) {
	credits = document.addCredits.creditsToAdd.value;
	if(is_numeric(credits)) {
		if(credits > 0) {
			if(maxCredits >= document.addCredits.creditsToAdd.value) {
				document.addCredits.submit();
				return true;
			} else {
				alert("You do not have suffecient credits to add to this campaign.");
				return false;
			}
		} else {
			alert("You have entered an invalid selection of credits to add.");
			return false;
		}
	} else {
		alert("You have entered an invalid selection of credits to add.");
		return false;
	}
}
function is_numeric(variable)
{
   var numericCheck = variable;
   if ((isNaN(numericCheck)) || (numericCheck.length == 0)) {
	  return false;
   } else {
	  return true;
   }
}
//-->
</script>

<h2>Manage Advertisements</h2>
<hr />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
<?php
if($action == 'viewAd') {
?>
        <table width="500" border="0" align="center" cellpadding="15" cellspacing="0" bgcolor="#EEEEEE">
          <tr>
            <td><?php 
	$valid = FALSE;
	if($adType == '' || $adType == 'banner') {
		$adType = 'banner';
		$table = 'banners';
		$idType='fnum';
		$valid = TRUE;
	} else if($adType == 'fbanner') {
		$table = 'fbanners';
		$idType='fnum';
		$valid = TRUE;
	} else if($adType == 'fad') {
		$table = 'featuredads';
		$idType='fnum';
		$valid = TRUE;
	} else if($adType == 'links') {
		$table = 'tasks';
		$idType='fn';
		$valid = TRUE;
	} else if($adType == 'ptrad') {
		$table = 'ptrads';
		$idType='fn';
		$valid = TRUE;
	} else if($adType == 'signup') {
		$table = 'signups';
		$idType='fnum';
		$valid = TRUE;
	} else if($adType == 'email') {
		$table = 'reads';
		$idType='fnum';
		$valid = TRUE;
	} else if($adType == 'survey') {
		$table = 'surveys';
		$idType='id';
		$valid = TRUE;
	} else {
		echo "Invalid type !";
	}
	if($valid == TRUE) {
		$sql = "SELECT * FROM `$table` WHERE `$idType` = ".quote_smart($_REQUEST['id'])."";
		//echo "Running SQL: ".$sql."<BR><BR>";
		$query = mysql_query($sql);
		$count = mysql_num_rows($query);
		if($count == 0) {
			echo "There are currently no campaigns to display.<BR>";
		} else {
			$arr = mysql_fetch_array($query);
			if($action == 'viewAd' && $adType == 'banner') {
		?>
                  <a href="../index.php?tp=out&t=b&id=<?php echo $arr['fnum']; ?>" target="_blank"><img src="<?php echo $arr['furl']; ?>" border="0" width="468" height="60"></a>
                  <br>
                  <br>
                  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><BR>
                  Credits: <?php echo $arr['fsize']; ?><br>
				  Views: <?php echo $arr['fshows']; ?><br>
				  Clicks: <?php echo $arr['fclicks']; ?><br><br>
				  
			  
                <?php
			} else if($action == 'viewAd' && $adType == 'fbanner') {
		?>
                  <a href="../index.php?tp=out&t=fb&id=<?php echo $arr['fnum']; ?>" target="_blank"><img src="<?php echo $arr['furl']; ?>" border="0" width="180" height="100"></a>
                  <br>
                  <br>
                  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><br>
                  Credits: <?php echo $arr['fsize']; ?><br>
				  Views: <?php echo $arr['fshows']; ?><br>
				  Clicks: <?php echo $arr['fclicks']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'fad') {
		?><a href="../index.php?tp=out&t=fb&id=<?php echo $arr['fnum']; ?>" target="_blank"><?php echo $arr['ftitle']; ?></a><BR><?php echo $arr['description']; ?>
                  <br>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><br><?php } ?>
                  Credits: <?php echo $arr['fsize']; ?><br>
				  Views: <?php echo $arr['fshows']; ?><br>
				  Clicks: <?php echo $arr['fclicks']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'flinks') {
		?><a href="../index.php?tp=out&t=fb&id=<?php echo $arr['fnum']; ?>" target="_blank"><?php echo $arr['ftitle']; ?></a><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><br><?php } ?>
                  Credits: <?php echo $arr['fsize']; ?><br>
				  Views: <?php echo $arr['fshows']; ?><br>
				  Clicks: <?php echo $arr['fclicks']; ?><br>
                  <BR>
                <?php
			} else if($action == 'viewAd' && $adType == 'links') {
		?><a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsitename']; ?></a><BR><?php echo $arr['furl']; ?><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><br><?php } ?>
                  Credits: <?php echo $arr['fsize']; ?><br>
				  Views: <?php echo $arr['fvisits']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'ptrad') {
		?><a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsitename']; ?></a><BR><?php echo $arr['furl']; ?><BR><?php echo $arr['ptrad']; ?><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><br><?php } ?>
                  Credits: <?php echo $arr['fsize']; ?><br>
				  Views: <?php echo $arr['fvisits']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'signup') {
		?>
                <a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsitename']; ?></a><BR><?php echo $arr['furl']; ?><br>
                Secret Question: <?php echo $arr['squest']; ?><BR>
                Secret Answer: <?php echo $arr['sansw']; ?><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><br><?php } ?>
                  Credits: <?php echo $arr['fsize']; ?><br>
Views: <?php echo $arr['fsignups']; ?>
<?php 
			} else if($action == 'viewAd' && $adType == 'survey') {
		?>
                <a href="<?php echo $arr['siteurl']; ?>" target="_blank"><?php echo $arr['surveyname']; ?></a><BR><?php echo $arr['siteurl']; ?><br>
                  <br>
				  <table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
                    <tr>
                      <td bgcolor="#CCFFFF"><strong>Preview of Your Survey:</strong><br><br>
                        
                        <?php
				$q = mysql_query("SELECT * FROM surveyquestions WHERE surveyID = ".quote_smart($arr['id'])." ORDER BY `question` DESC");
				$c = mysql_num_rows($q);
				if($c > 0 ) { 
					?>
						<table width="85%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
                    <tr>
                      <td bgcolor="#CCFFFF">
					  <?php
					  $endDropdown = FALSE;
					  $shownDropdown = FALSE;
					  $prevName = '';
					  for($i = 0; $i < $c;$i++) {
						mysql_data_seek($q, $i);
						$array = mysql_fetch_array($q);
						if($array['question'] != $prevName) {
							if($prevType=='dropdown') { echo "</select><BR>"; }
							if($i != 0) { echo "<BR>"; }
							echo "<strong>Q:</strong> ".$array['question']."\n\n<BR>";
							if($array['optionType'] == 'dropdown') echo "<select name=\"".$array['option']."\">\n";
						}
						
						if($array['optionType'] == 'radio') {
							echo "<input type=\"radio\" name=\"".$array['option']."\" value=\"".$array['optionValue']."\"> ".$array['optionName']."<BR>\n\n"; 
						} else if($array['optionType'] == 'dropdown') {
							echo "<option value=\"".$array['optionValue']."\">".$array['optionName']."</option>\n";
						} else if($array['optionType'] == 'checkbox') {
							echo "<input type=\"checkbox\" name=\"".$array['option']." value=\"".$array['optionValue']."\"> ".$array['optionName']."<BR>\n\n"; 
						} else if($array['optionType'] == 'text') {
							echo "<input type=\"text\" name=\"".$array['option']."\" value=\"\">\n\n<BR>"; 
						}
						$prevName = $array['question'];
						$prevType = $array['optionType'];
					}
					?></td></tr></table><BR><BR><?php
					
				}
				
				?></td>
                    </tr>
                  </table>
                  
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  Advertisers Login: <?php echo $arr['flogin']; ?><BR>
                  Advertisers Password: <?php echo $arr['fpassword']; ?><br><?php } ?>
                  Credits: <?php echo $arr['fsize']; ?><br>
				  Survey's Taken: <?php echo $arr['fviews']; 
if($arr['fviews'] > 0) { ?>
<br>
Survey's are listed below by username.<HR>
<br>
<table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#666666">
  <tr bgcolor="#EEEEEE">
    <td>Username</td>
    <td>Date Taken </td>
    <td>Status</td>
    <td>Read / View</td>
  </tr>
  <?php
  $sq = mysql_query("SELECT dateTaken, username,id FROM surveyactivity WHERE surveyID = ".quote_smart($arr['id']));
  $cnt = mysql_num_rows($sq);
  if($cnt > 0) {
  for($i = 0;$i < $cnt;$i++) {
  	mysql_data_seek($sq, $i);
	$ar = mysql_fetch_array($sq);
  ?>
  <tr bgcolor="#FFFFFF">
    <td><?php echo $ar['username']; ?></td>
    <td><?php echo $ar['dateTaken']; ?></td>
    <td>Completed</td>
    <td><a href="index.php?tp=viewSurveyResults&surveyID=<?php echo $arr['id']; ?>&aid=<?php echo $ar['id']; ?>" target="_blank">View Survey</a></td>
  </tr>
  <?php
  }//END FOR LOOP
 } //END COUNT > 0
  ?>
</table><?php 
} //END SURVEY COUNT

			} else if($action == 'viewAd' && $adType == 'email') {
		?>
              <a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsubject']; ?></a><BR>
                Promotion URL: <?php echo $arr['furl']; ?><br>
Subject: <?php echo $arr['fsubject']; ?><BR>
E-Mail Message:<br><?php echo $arr['ftext']; ?><BR>
<br>
<?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
Advertisers Login: <?php echo $arr['flogin']; ?><BR>
Advertisers Password: <?php echo $arr['fpassword']; ?><br>
<?php } ?>
Credits: <?php echo $arr['fsize']; ?><br>
Views: <?php echo $arr['freads']; ?>
<?php
			}
		}
	}
	?></td>
          </tr>
      </table><?php
}
?>
        <br>
        <br>
        <div align="center">
        <form name="jumpForm">
          <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
            <option value="index.php?tp=manageads&adType=banner" <?php if($adType == 'banner' || $adType == '') { echo "selected"; } ?>>Banners (480x60)</option>
            <option value="index.php?tp=manageads&adType=fbanner" <?php if($adType == 'fbanner') { echo "selected"; } ?>>Featured Banners (180x100)</option>
            <option value="index.php?tp=manageads&adType=fad" <?php if($adType == 'fad') { echo "selected"; } ?>>Featured Ad</option>
            <option value="index.php?tp=manageads&adType=flinks" <?php if($adType == 'flinks') { echo "selected"; } ?>>Featured Link</option>
            <option value="index.php?tp=manageads&adType=links" <?php if($adType == 'links') { echo "selected"; } ?>>Link / Paid to Click</option>
            <option value="index.php?tp=manageads&adType=signup" <?php if($adType == 'signup') { echo "selected"; } ?>>Paid To Sign-Up</option>
            <option value="index.php?tp=manageads&adType=email" <?php if($adType == 'email') { echo "selected"; } ?>>Paid To Read E-Mail</option>
            <option value="index.php?tp=manageads&adType=survey" <?php if($adType == 'survey') { echo "selected"; } ?>>Paid To Read Surveys</option>
            <option value="index.php?tp=manageads&adType=ptrad" <?php if($adType == 'ptrad') { echo "selected"; } ?>>Paid To Read Ad's</option>
		  </select>
        </form>
    </div></td></tr>
<tr>
  <td>
  <div id="newAd"<?php if($continueEdit != TRUE) { echo " style=\"display: none;\""; } ?>><table border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#EEEEEE">
      <tr>
        <td><?php
	if($adType == '' || $adType == 'banner') {
		?>
          <form name="form1" method="post" action="index.php?tp=manageads&adType=<?php echo $adType; ?>">
            <table width="418" border="0" align="center">
              <tr>
                <td width="141"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad Name: </font></td>
                <td width="267"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="name" type="text" value="<?php echo  $fname?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr>
                <td width="141"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Banner link URL:</font></td>
                <td width="267">
                  <input name="flink" type="text" maxlength="255">
                  <input type="hidden" name="tp" value="manageads">
                  <input type="hidden" name="adType" value="<?php echo $adType; ?>">
                  <input type="hidden" name="act" value="addBanner">
                </td>
              </tr>
              <tr>
                <td width="141"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Banner image URL:</font></td>
                <td width="267">
                  <input name="furl" type="text" maxlength="255">
                </td>
              </tr>
			  <tr>
			  	<td colspan="2">
				
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
			  </tr>
              <tr>
                <td width="141"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input type="submit" name="Submit" value="Add banner">
                </font></td>
                <td width="267"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
              </tr>
            </table>
          </form>
          <?php
	} else if($adType == 'survey') {
	//echo "<pre>".print_r($_REQUEST,1)."</pre>";
		?><strong>Add your new Paid Survey below.</strong><br>
You will need to add at least 2 questions to create a survey. Simply fill out the question information, add as many answers as you want for that question if it is multiple choice, and click Add This Survey to finish setting up your ad.<BR><br>
<HR>
          <form name="form1" method="post" action="index.php?tp=manageads&adType=<?php echo $adType; ?>">
            <table width="418" border="0" align="center">
              <tr>
                <td width="141"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Survey Name:</font></td>
                <td width="267"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="surveyname" type="text" value="<?php if(isset($_REQUEST['siteurl'])) echo $_REQUEST['surveyname']; ?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr>
                <td width="141"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Survey link URL:</font></td>
                <td width="267">
                  <input name="siteurl" type="text" maxlength="255" value="<?php echo $_REQUEST['siteurl']; ?>">
                  <input type="hidden" name="tp" value="manageads">
                  <input type="hidden" name="adType" value="<?php echo $adType; ?>">
                  <input type="hidden" name="act" value="addSurvey">
                </td>
              </tr>
			  <tr>
			  <td colspan="2"><br>			    <table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#006633">
                  <tr>
                    <td bgcolor="#FFFFCC"><p><strong>Add a new Survey Question Below</strong></p>
                      <p align="center">Your Question:<br>
<textarea name="question" cols="45" rows="2"><?php echo $_REQUEST['question']; ?></textarea>
<br>
<?php
				  if(isset($_SESSION['answers']) && count($_SESSION['answers'] > 1)) {
				  	echo "<br><br>";
					echo "Current Answers.<BR>";
					if($_SESSION['answerType'] == 'dropdown') echo "<select name=\"currentAnswers\">\n";
					foreach($_SESSION['answers'] as $k => $v) {
						if($_SESSION['answerType'] == 'radio') {
							echo "<input type=\"radio\" value=\"\"> ".$v."<BR>";
						} else if($_SESSION['answerType'] == 'checkbox') {
							echo "<input type=\"checkbox\" value=\"\"> ".$v."<BR>";
						} else if($_SESSION['answerType'] == 'dropdown') {
							echo "<option value=\"\">".$v."</option>\n";
						}
					}
					if($_SESSION['answerType'] == 'dropdown') echo "</select><BR>";
				  }
			      ?>
<br>
<br>
Type of Answer:
<br>
<?php
				  if(!isset($_SESSION['answerType'])) { ?>
<select name="questionType" onChange="javascript: changeSurveyType(this.value);">
  <option value="text">User Input Answer</option>
  <option value="radio">Radio Button Answer</option>
  <option value="checkbox">Check Box Answer</option>
  <option value="dropdown">Drop Down Answer</option>
</select>
<?php
				  } else {
				  	if($_SESSION['answerType'] == 'radio') echo "Radio Button User Input.";
				  	if($_SESSION['answerType'] == 'dropdown') echo "Dropdown Menu User Input.";
				  	if($_SESSION['answerType'] == 'checkbox') echo "Checkbox User Input.";
				  }
				  ?>
<br>
<br>
</p>
                      <div id="text" <?php if($_SESSION['answerType'] != '' && $_SESSION['answerType'] != 'text') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong>Input form user input.</strong><br>
        A Form will be provided to the user to give their answer. You will be able to see what they wrote when a user completes the survey. </td>
                          </tr>
                        </table>
                      </div>
                      <div id="radio"  <?php if($_SESSION['answerType'] != 'radio') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong>Radio Button user input.</strong><br>
        Add at least 2 answers below. A radio button will be an either or situation. <br>
        Answer:
        <input name="radioAnswer" type="text" size="25" maxlength="25">
        <br>
        <input type="submit" name="addAnswer" value="Add Another Answer"></td>
                          </tr>
                        </table>
                      </div>
                      <div id="checkbox"  <?php if($_SESSION['answerType'] != 'checkbox') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong>Checkbox user input.</strong><br>
        Add at least 2 answers below. A checkbox will be question where someone can choose multiple answers.. <br>
        Answer:
        <input name="checkboxAnswer" type="text" size="25" maxlength="25">
        <br>
        <input type="submit" name="addAnswer" value="Add Another Answer"></td>
                          </tr>
                        </table>
                      </div>
                      <div id="dropdown" <?php if($_SESSION['answerType'] != 'dropdown') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong>Dropdown user input.</strong><br>
        Add at least 2 answers below. A dropdown will be question where someone can choose an answer from a list. <br>
        Answer:
        <input name="dropdownAnswer" type="text" size="35" maxlength="35">
        <br>
        <input type="submit" name="addAnswer" value="Add Another Answer"></td>
                          </tr>
                        </table>
                      </div>
                      <div align="center">
                        <script type="text/javascript">
			  	function changeSurveyType(divID) {
					var id=new Array()
					id[0] = 'text';
					id[1] = 'radio';
					id[2] = 'checkbox';
					id[3] = 'dropdown';
					
					for(i = 0;i < 4; i++) {
						divTag = document.getElementById(id[i]);
						if(divID == id[i]) {
							divTag.style.display = "block";
						} else {
							divTag.style.display = "none";
						}
					}
					
				}
			          </script>
                        <br>
                        <br>
                        <input type="submit" name="addQuestion" value="Add This Question to my Survey">
                      </div></td>
                  </tr>
                </table>
			    <p>&nbsp;                  </p>
			    <div align="center">
			      <table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
                    <tr>
                      <td bgcolor="#CCFFFF"><strong>Preview of Your Survey:</strong><br><br>
                        
                        <?php
				if(isset($_SESSION['surveyquestions']) && is_array($_SESSION['surveyquestions'])) {

					foreach($_SESSION['surveyquestions'] as $k => $v) {
						?>
						<table width="85%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
                    <tr>
                      <td bgcolor="#CCFFFF">
					  <?php
					  if(is_array($v['options'])) { //MULTIPLE CHOICE, RADIO BUTTON, CHECKBOX OR DROP DOWN
							echo "<strong>Q:</strong> ".$v['question']."<BR>";
							if($v['optionType'] == 'radio') {
								foreach($v['options'] as $key => $value) {
									echo "<input type=\"radio\" name=\"".$value['optionId']." value=\"".$value['optionName']."\"> ".$value['optionValue']."<BR>"; 
								}
							} else if($v['optionType'] == 'dropdown') {
								echo "<select name=\"".$value['options'][0]['optionId'].">\n";
								foreach($v['options'] as $key => $value) {
									echo "<option value=\"".$value['optionName']."\">".$value['optionValue']."</option>"; 
								}
								echo "</select>";
							} else if($v['optionType'] == 'checkbox') {
								foreach($v['options'] as $key => $value) {
									echo "<input type=\"checkbox\" name=\"".$value['optionId']." value=\"".$value['optionName']."\"> ".$value['optionValue']."<BR>"; 
								}
							} else if($v['optionType'] == 'textbox') {
								foreach($v['options'] as $key => $value) {
									echo $value['optionValue'].": <input type=\"text\" name=\"".$value['optionId']." value=\"".$value['optionName']."\"><BR>"; 
								}
							}
						} else { //NOT MULTIPLE CHOICE, ANSWER THE QUESTION TYPE
							echo "<strong>Q:</strong> ".$v['question']."<BR>A: (User Input Form)<BR>";
						}
						?></td></tr></table><BR><BR><?php
					}
				}
				
				?></td>
                    </tr>
                  </table>
                  <br>
				  </div>			  </td>
			  </tr>
			  <tr>
			  	<td colspan="2">
				
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();" <?php if($_REQUEST['flogin'] != '' && $_REQUEST['fpassword'] != '') { echo "checked"; } ?>>Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" <?php if($_REQUEST['flogin'] == '' && $_REQUEST['fpassword'] == '') { echo "checked"; } ?>>
				No
				<div id="advertiserDetails" <?php if($_REQUEST['flogin'] == '' && $_REQUEST['fpassword'] == '') { ?>style="display:none;"<?php } ?>>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
			  </tr>
              <tr>
                <td width="141"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <br>
                  <br>
                  <br>
                  <input type="submit" name="Submit" value="Add This Survey">
                </font></td>
                <td width="267"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <br>
                  <br>
                  <br>
                  <br>
                  <br>
                  <br>
                  <br>
                  <input type="submit" name="Submit" value="Start Over from Scratch">
                </font></td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </form>
          <?php
	} else if($adType == 'fbanner') {
		?>
          <form name="form1" method="post" action="index.php?tp=manageads&adType=<?php echo $adType; ?>">
            <table width="409" border="0" align="center">
              <!--DWLayoutTable-->
              <tr>
                <td width="134"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad name</font></td>
                <td width="265"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="name" type="text" id="name" value="<?if ($act=='add') echo $fname?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Banner link URL:</font></td>
                <td>
                  <input name="flink" type="text" id="flink" size="25" maxlength="255">
                  <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input name="act" type="hidden" id="act" value="addfbanner">
                  </font> </td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Banner image URL:</font></td>
                <td>
                  <input name="furl" type="text" id="furl" size="25" maxlength="255">
                </td>
              </tr>
              <tr>
                <td height="70" colspan="2" valign="top">
				
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
                </tr>
              <tr>
                <td> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input type="submit" name="Submit" value="Add banner">

                </font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
              </tr>
            </table>
          </form>
          <?php
	} else if($adType == 'fad') {
		?>
          <form name="form1" method="post" action="index.php">
            <table width="407" border="0" align="center">
              <!--DWLayoutTable-->
              <tr>
                <td width="132"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad Title / Name: </font></td>
                <td width="265"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="name" type="text" id="name" value="<?if ($act=='add') echo $fname?>" size="25" maxlength="15">
</font></td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad URL :</font></td>
                <td>
                  <input name="flink" type="text" id="flink" size="25" maxlength="255">
                </td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Your Ad :<br>
                  (40 Characters)</font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <textarea name="description" cols="40" rows="4"><?if ($act=='add') echo $description?></textarea>
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input name="act" type="hidden" id="act" value="addfad">
                </font> </td>
              </tr>
              <tr>
                <td height="73" colspan="2" valign="top">
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
                </tr>
            </table>
            <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
              <input type="submit" name="Submit" value=" Add Featured Ad ">
            </font> </div>
          </form>
          <?php
	} else if($adType == 'flinks') {
		?>
          <form name="form1" method="post" action="index.php">
            <table width="407" border="0" align="center">
              <!--DWLayoutTable-->
              <tr>
                <td width="132"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad Title / Name: </font></td>
                <td width="265"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="name" type="text" id="name" value="<?if ($act=='add') echo $fname?>" size="25" maxlength="15">
</font></td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad URL :</font></td>
                <td>
                  <input name="flink" type="text" id="flink" size="25" maxlength="255">
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input name="act" type="hidden" id="act" value="addflink">
                </td>
              </tr>
              <tr>
                <td height="73" colspan="2" valign="top">
				
				
				
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
                </tr>
            </table>
            <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
              <input type="submit" name="Submit" value=" Add Featured Ad ">
            </font> </div>
          </form>
          <?php
	} else if($adType == 'links') {
		?>
          <form name="addvisit" method="post" action="index.php">
            <table border="0" width="72%" align="center" bgcolor="f5f5f5">
              <!--DWLayoutTable-->
              <tr valign="top">
                <td width="270"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad URL:</font></td>
                <td width="230"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="url" type="text" value="<?php if($act=add) echo $_REQUEST['furl']; ?>" size="25" maxlength="255">
</font></td>
              </tr>
              <tr valign="top">
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site name:</font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="sitename" type="text" value="<?if($act=add) echo $_REQUEST['sitename']?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr valign="top">
                <td height="50" colspan="2" valign="top">
				
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
                </tr>
              <tr valign="top">
                <td colspan="2">
                  <div align="center"><font size="4">&nbsp; </font></div></td>
              </tr>
              <tr valign="top">
                <td colspan="2">
                  <div align="center">
                    <input name="tp" type="hidden" id="tp" value="manageads">
                    <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                    <input name="act" type="hidden" id="act" value="addlinks">
                    <input type="submit" name="Submit" value="Add campaign">
                </div></td>
              </tr>
            </table>
            <div align="center"></div>
          </form>
          <?php
	 } else if($adType == 'ptrad') {
		?>
          <form name="addvisit" method="post" action="index.php">
            <table border="0" width="72%" align="center" bgcolor="f5f5f5">
              <!--DWLayoutTable-->
              <tr valign="top">
                <td width="270"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ad URL:</font></td>
                <td width="230"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="url" type="text" value="<?php if($act=add) echo $_REQUEST['furl']; ?>" size="25" maxlength="255">
</font></td>
              </tr>
              <tr valign="top">
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site name:</font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="sitename" type="text" value="<?if($act=add) echo $_REQUEST['sitename']?>" size="25" maxlength="20">
                </font></td>
              </tr>
<tr valign="top">
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Paid to Read Ad:</font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <textarea name="ptrad"><?if($act=add) echo $_REQUEST['ptrad']?></textarea>
                </font></td>
              </tr>
              <tr valign="top">
                <td height="50" colspan="2" valign="top">
				
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
                </tr>
              <tr valign="top">
                <td colspan="2">
                  <div align="center"><font size="4">&nbsp; </font></div></td>
              </tr>
              <tr valign="top">
                <td colspan="2">
                  <div align="center">
                    <input name="tp" type="hidden" id="tp" value="manageads">
                    <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                    <input name="act" type="hidden" id="act" value="addptrads">
                    <input type="submit" name="Submit" value="Add campaign">
                </div></td>
              </tr>
            </table>
            <div align="center"></div>
          </form>
          <?php
	 } else if($adType == 'signup') {
		?>
         <form name="addvisit" method="post" action="index.php">
		 <table width="64%" height="312" border="0" align="center" background="../images/fon.gif.gif">
            <tr valign="top">
              <td width="32%" height="24"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Signup URL:</font></td>
              <td width="68%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input type="text" name="url" size="40" value="<?if($act=='add') echo $_REQUEST['furl']?>">
              </font></td>
            </tr>
            <tr valign="top">
              <td width="32%" height="24"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site name:</font></td>
              <td width="68%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input type="text" name="sitename" size="40" value="<?if($act=='add') echo $fsitename?>">
              </font></td>
            </tr>
            <tr valign="top">
              <td width="32%" height="88"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Site description:</font></td>
              <td width="68%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <textarea name="note" cols="40" rows="4"><?if($act=='add') echo $fnote?></textarea>
              </font></td>
            </tr>
            <tr valign="top">
              <td width="32%" height="24"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Secret question:</font></td>
              <td width="68%"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
                <input type="text" name="squest" size="40" value="<?if($act=='add') echo $_REQUEST[sq]?>">
                <br>
      Must be an obvious question that can only be answered after successfully signing up.</font></td>
            </tr>
            <tr valign="top">
              <td width="32%" height="24"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Secret answer:</font></td>
              <td width="68%"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
                <input type="text" name="sansw" size="40" value="<?if($act=='add') echo $sa?>">
                <br>
      Must be an obvious answer found after successfully signing up.</font></td>
            </tr>
			<tr valign="top">
				<td colspan="2">
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
			</tr>
            <tr valign="top">
              <td height="26" colspan="2">
                <div align="center">
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input name="act" type="hidden" id="act" value="addsignup">
                  <input type="submit" name="Submit" value="Add campaign">
              </div></td>
            </tr>
          </table>
         </form>
          <?php 
	} else if($adType == 'email') {
		?>
          <form name="addvisit" method="post" action=""><table border="0" cellspacing="0" cellpadding="0" bgcolor="f5f5f5" align="center" width="40%">
            <tr>
              <td>
                  <table border="0" align="left" bgcolor="f5f5f5">
                    <tr>
                      <td width="75"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Subject:</font></td>
                      <td width="300">
                        <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                          <input type="text" name="subject" size="50" value="<?if($act=='add') echo$fsubject?>">
                      </font></div></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif">E-mail content:</font></div></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                          <textarea name="mailcontent" rows="12" cols="50"><?if($act=='add') echo$fcontent?></textarea>
                      </font></div></td>
                    </tr>
                    <tr>
                      <td colspan="2"> <font size="2">
                        <input type="radio" name="mailformat" value="plain" <?php if($act=='add' && $fformat=='text') echo"checked"?>>
              Text format
              <input type="radio" name="mailformat" value="html" <?php if($act=='add' && $fformat=='html') echo"checked"?>>
              HTML format</font></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <table width="340">
                          <tr>
                            <td align="right" class="light" width="147"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Mail coding: </font></td>
                            <td align="left" class="light" width="181">
                              <select name="mail_coding">
                                <option value="gb2312">Chinese (Simplified)</option>
                                <option value="big5">Chinese (Traditional)</option>
                                <option value="windows-1250">Czech</option>
                                <option value="windows-1250">Dansk</option>
                                <option value="windows-1250">Deutsch</option>
                                <option value="iso-8859-1">English (GB)</option>
                                <option value="windows-1252" selected>English (US)</option>
                                <option value="windows-1250">Espa&ntilde;ol</option>
                                <option value="windows-1250">Fran&ccedil;ais</option>
                                <option value="windows-1250">Magyar</option>
                                <option value="windows-1250">Italiano</option>
                                <option value="iso-2022-jp">Japanese</option>
                                <option value="euc-kr">Korean</option>
                                <option value="windows-1250">Nederlands</option>
                                <option value="windows-1250">Norsk bokm&aring;l</option>
                                <option value="windows-1250">Polski</option>
                                <option value="windows-1250">Portugu&ecirc;s</option>
                                <option value="windows-1251">Russian (Windows)</option>
                                <option value="KOI8-R">Russian (KOI8-R)</option>
                                <option value="windows-1250">Slovak</option>
                                <option value="windows-1250">Slovenscina</option>
                            </select></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <br>
                Link for receive credit for reading this e-mail:<br>
                <input type="text" name="mailurl" size="50" value="<?if($act=='add') echo$_REQUEST['furl']?>">
                <br>
                      </font></div></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                       
				
				Provide Someone Unique Advertiser Login?<br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();">Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				No
				<div id="advertiserDetails" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Login</font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="">
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'>Unique Advertiser only Password</font>
                    </font></td>
              </tr>
                </table>
				</div>				 <div align="center">
                          <input name="tp" type="hidden" id="tp" value="manageads">
                          <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                          <input name="act" type="hidden" id="act" value="addemail">
                      </div></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <div align="center">
                          <p>&nbsp;</p>
                          <p>
                            <input type="submit" name="Submit3" value="Add campaign">
                            <br />
                            <br />
                          NOTE: After you have added this campaign, you will need to assign credits in order for the &quot;Paid to Read Email&quot; is sent. If you wish to send an email blast, please use the &quot;Members -&gt; Email Blast&quot; tool instead and this will send a non paid instant email to all members.</p>
                      </div></td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table></form>
          <?php
	}
	?></td>
      </tr>
    </table></div><?php
	
  if($action == 'addCredits') {
  ?><table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong>How many credits would you like to add?</strong> </td>
    </tr>
    <tr>
      <td><?php $maxCredits = 9999999999; ?><form name="addCredits" method="post" action="" onsubmit="return false;">
        Credits Available: <?php echo $maxCredits; ?>
        <br>
        Add Credits: 
		<input name="creditsToAdd" type="text" value="0" size="10" maxlength="15">
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<input type="hidden" name="adType" value="<?php echo $adType; ?>">
		<input type="hidden" name="tp" value="manageads">
		<input type="hidden" name="action" value="addCreditsNow">
        <br>
        <br>
        <input type="submit" name="Submit4" value="Add Credits" onClick="verifyAddCredits(<?php echo $maxCredits; ?>)">      
              </form></td>
    </tr>
  </table></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td><script language="javascript" type="text/javascript">
			function showAddCampaign() {
				divTag = document.getElementById('newAd');
				divTag.style.display = "block";
				return false;
			}
			</script>   <a href="javascript:;" onClick="javascript:showAddCampaign();">Add New Advertising Campaign</a><br><?php
	$valid = FALSE;
	if($adType == '' || $adType == 'banner') {
		$adType = 'banner';
		$table = 'banners';
		$creditType = '`fsize` - `fshows`';
		$fieldShows = 'fshows';
		$valid = TRUE;
	} else if($adType == 'fbanner') {
		$table = 'fbanners';
		$fieldShows = 'fshows';
		$creditType = '`fsize` - `fshows`';
		$valid = TRUE;
	} else if($adType == 'fad') {
		$table = 'featuredads';
		$creditType = '`fsize` - `fshows`';
		$fieldShows = 'fshows';
		$valid = TRUE;
	} else if($adType == 'flinks') {
		$table = 'featuredlinks';
		$creditType = '`fsize` - `fshows`';
		$fieldShows = 'fshows';
		$valid = TRUE;
	} else if($adType == 'links') {
		$table = 'tasks';
		$creditType = '`fsize` - `fvisits`';
		$fieldShows = 'fvisits';
		$valid = TRUE;
	} else if($adType == 'ptrad') {
		$table = 'ptrads';
		$creditType = '`fsize` - `fvisits`';
		$fieldShows = 'fvisits';
		$valid = TRUE;
	} else if($adType == 'signup') {
		$table = 'signups';
		$creditType = '`fsize` - `fsignups`';
		$fieldShows = 'fsignups';
		$valid = TRUE;
	} else if($adType == 'email') {
		$table = 'reads';
		$creditType = '`fsize` - `freads`';
		$fieldShows = 'freads';
		$valid = TRUE;
	} else if($adType == 'survey') {
		$table = 'surveys';
		$creditType = '`fsize` - `fviews`';
		$fieldShows = 'fviews';
		$valid = TRUE;
	} else {
		echo "Invalid type !";
	}
	
	if($valid == TRUE) {
		$sql = "SELECT *, $creditType AS `credits` FROM `$table` ORDER BY `credits` DESC";
		//echo "Running SQL: ".$sql."<BR><BR>";
		$query = mysql_query($sql) or die("There was an error running SQL Query: $sql. The error reported was <BR><BR>".mysql_error());
		$count = mysql_num_rows($query);
		if($count == 0) {
			echo "There are currently no campaigns to display.<BR>";
		} else {
			?>
      <div class="hastable_disabled">
      <table class="fullwidth" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
          <?php
		  if($adType == 'banner' || $adType == 'fbanner' || $adType == 'fad' || $adType == 'flinks') {
		  if($adType == 'banner' || $adType == 'fbanner') { ?>
		   <td width="100">Image</td>
		   <?php
		   }
		   ?>
		  <td width="181"><strong>Name</strong></td>
          <td width="49"><strong>Credits Left</strong></td>
		  <td width="49"><strong>Views</strong></td>
          <td width="42"><strong>Clicks</strong></td>
          <td><strong>Username</strong></td>
		  <td width="188"><div align="right"><strong>Options</strong></div></td>
		  <?php
		  }
		  if($adType == 'links') { ?>
		  <td width="181"><strong>Name</strong></td>
          <td width="49"><strong>Credits Left</strong></td>
		  <td width="42"><strong>Clicks</strong></td>
		  <td><strong>Username</strong></td>
          <td width="188"><div align="right"><strong>Options</strong></div></td>
		  <?php
		  }
		  if($adType == 'ptrad') { ?>
		  <td width="181"><strong>Name</strong></td>
          <td width="49"><strong>Credits Left</strong></td>
		  <td width="42"><strong>Clicks</strong></td>
		  <td><strong>Username</strong></td>
          <td width="188"><div align="right"><strong>Options</strong></div></td>
		  <?php
		  }
		  if($adType == 'email') { ?>
		  <td width="181"><strong>Subject</strong></td>
          <td width="49"><strong>Credits Left</strong></td>
		  <td width="49"><strong>Sent</strong></td>
          <td><strong>Username</strong></td>
		  <td width="188"><div align="right"><strong>Options</strong></div></td>
		  <?php
		  }
		  if($adType == 'signup') { 
		  ?>
		  <td width="180"><strong>Name</strong></td>
          <td width="49"><strong>Credits Left</strong></td>
		  <td width="49"><strong>Sign-ups</strong></td>
          <td><strong>Username</strong></td>
		  <td width="188"><div align="right"><strong>Options</strong></div></td>
		  <?php
		  }
		  if($adType == 'survey') { 
		  ?>
		  <td width="180"><strong>Name</strong></td>
          <td width="49"><strong>Credits Left</strong></td>
		  <td width="49"><strong>Reads / Visits</strong></td>
          <td><strong>Username</strong></td>
		  <td width="188"><div align="right"><strong>Options</strong></div></td>
		  <?php
		  }
		  ?>
		  
        </tr>
        </thead>
        <tbody>
        <?php
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($query, $i);
			$arr = mysql_fetch_array($query);
			?><tr valign="top" <?php if($i%2) echo "class=\"odd\""; ?>>
			  
			  
			   <?php
		  if($adType == 'banner' || $adType == 'fbanner' || $adType == 'fad' || $adType == 'flinks') {
		  if($adType == 'banner' || $adType == 'fbanner') { ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><img src="<?php echo $arr['furl']; ?>" <?php if($adType == 'fbanner') { ?>width="90" height="50"<?php } else { echo 'width="117" height="15"'; } ?>></a></td>
		   <?php
		   }
		   ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php if($arr['fname'] != '') { echo $arr['fname']; } else if($arr['fsitename'] != '') { echo $arr['fsitename']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fshows']; ?></td>
		 <td><?php echo $arr['fshows']; ?></td>
          <td><?php echo $arr['fclicks']; ?></td>
		  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>
			  <td><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>">Delete</a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>">Add Credits</a>		  <?php
		  }
		  if($adType == 'links') { ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php if($arr['fname'] != '') { echo $arr['fname']; } else if($arr['fsitename'] != '') { echo $arr['fsitename']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fvisits']; ?></td>
          <td><?php echo $arr['fvisits']; ?></td>
		  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>
			  <td><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>">Delete</a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>">Add Credits</a><?php
		  }
		  if($adType == 'ptrad') { ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php if($arr['fname'] != '') { echo $arr['fname']; } else if($arr['fsitename'] != '') { echo $arr['fsitename']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fvisits']; ?></td>
          <td><?php echo $arr['fvisits']; ?></td>
		  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>
			  <td><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>">Delete</a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>">Add Credits</a><?php
		  }
		  if($adType == 'email') { ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo $arr['fsubject']; ?></a></td>
          <td><?php echo $arr['fsize']-$arr['freads']; ?></td>
		  <td><?php echo $arr['freads']; ?></td>
		  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>
			  <td><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>">Delete</a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>">Add Credits</a><?php
		  }
		  if($adType == 'signup') { ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php if($arr['fname'] != '') { echo $arr['fname']; } else if($arr['fsitename'] != '') { echo $arr['fsitename']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fsignups']; ?></td>
		  <td><?php echo $arr['fsignups']; ?></td>
		  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>
			  <td><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>">Delete</a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>">Add Credits</a><?php
		  }
		  if($adType == 'survey') { ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['id']; ?>"><?php if($arr['surveyname'] != '') { echo $arr['surveyname']; } else if($arr['surveyname'] != '') { echo $arr['surveyname']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fviews']; ?></td>
		  <td><?php echo $arr['fviews']; ?></td>
		  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>
			  <td><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['id']; ?>">Delete</a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['id']; ?>">Add Credits</a><?php
		  }
		  ?>
			
			
			</tr>
		<?php
		}
		?>
        </tbody>
      </table>
      </div>      <BR>
	<?php 
		}
	}
	?>
    <br>
    <br>
    <br>	
    </td>
  </tr>
</table>

