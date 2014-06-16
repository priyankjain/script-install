<?php

?><?php echo $pageHeader; ?>
<h2><?php echo __('Manage Advertisements'); ?></h2>
<p><?php
$orphanCount = orphanCount();
?><script language="javascript" type="text/javascript">
<!--
function showAdvertiserDetails() {
	var browserName=navigator.appName; 
	if (browserName=="Netscape"){ 
	 document.getElementById('adDetailDiv').style.display = 'block';
	} else { 
	 if (browserName=="Microsoft Internet Explorer"){
		document.getElementById('adDetailDiv').style.display = 'block';
	 } else {
		var divBlock = document.getElementById('adDetailDiv');
		//divBlock.style.display = 'inline';
		divBlock.style.display = 'block';
	   }
	}
	return false;
}
function hideAdvertiserDetails() {
	var browserName=navigator.appName; 
	if (browserName=="Netscape"){ 
	 document.getElementById('adDetailDiv').style.display = 'none';
	} else { 
	 if (browserName=="Microsoft Internet Explorer") {
		document.getElementById('adDetailDiv').style.display = 'none';
	 } else {
		var divBlock = document.getElementById('adDetailDiv');
		divBlock.style.display = 'none';
	   }
	}
	return false;
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
<?php
if($action == 'addCredits' || $action == 'addCreditsConfirm') { ?>
function verifyAddCredits(maxCredits) {
	credits = document.addCredits.creditsToAdd.value;
	if(is_numeric(credits)) {
		if(credits > 0) {
			if(maxCredits >= document.addCredits.creditsToAdd.value) {
				document.addCredits.submit();
				return true;
			} else {
				alert("<?php echo __('You do not have suffecient credits to add to this campaign.'); ?>");
				return false;
			}
		} else {
			alert("<?php echo __('You have entered an invalid selection of credits to add.'); ?>");
			return false;
		}
	} else {
		alert("<?php echo __('You have entered an invalid selection of credits to add.'); ?>");
		return false;
	}
}
<?php
}
if($action == 'retractCredits') { ?>
function verifyRetractCredits(maxCredits) {
	credits = document.retractCredits.creditsToRetract.value;
	if(is_numeric(credits)) {
		if(credits > 0) {
			if(maxCredits >= document.retractCredits.creditsToRetract.value) {
				document.retractCredits.submit();
				return true;
			} else {
				alert("<?php echo __('You do not have suffecient credits to retract from this campaign.'); ?>");
				return false;
			}
		} else {
			alert("<?php echo __('You have entered an invalid selection of credits to retract.'); ?>");
			return false;
		}
	} else {
		alert("<?php echo __('You have entered an invalid selection of credits to retract.'); ?>");
		return false;
	}
}
<?php
}
?>
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
<style type="text/css">
<!--
.style1 {font-size: 10px}
-->
</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
<?php
if($action == 'viewAd') {
?><p class="test">
        <table width="500" border="0" align="center" cellpadding="15" cellspacing="0">
          <tr>
            <td bgcolor="#EFEFEF"><?php 
	$valid = FALSE; $idType = 'id'; $table = '';
	if($adType == '') $adType = 'banner';
	
	foreach($adTypes as $k => $v) {
		if($adType == $k) {
			$adType = $k;
			$table = $adType[$k]['table'];
			$idType=$adType[$k]['idType'];
			$valid = TRUE;
		}
	}
	if($valid == FALSE) echo __("Invalid type !");
	
	if($valid == TRUE) {
		$sql = "SELECT * FROM `$table` WHERE `$idType` = ".quote_smart($_REQUEST['id'])." AND username = ".quote_smart($_SESSION['login'])."";
		//echo "Running SQL: ".$sql."<BR><BR>";
		$query = mysql_query($sql);
		$count = mysql_num_rows($query);
		if($count == 0) {
			echo __("There are currently no campaigns to display.<BR>");
		} else {
			$arr = mysql_fetch_array($query);
			if($action == 'viewAd' && $adType == 'banner') {
		?>
                  <a href="index.php?tp=out?t=b&id=<?php echo $arr['fnum']; ?>" target="_blank"><img src="<?php echo $arr['furl']; ?>" border="0" width="486" height="80"></a>
      <br>
                  <br>
                  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to.'); ?> <strong>                  <?php echo __('Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fshows']; ?><br>
				  <?php echo __('Clicks'); ?>: <?php echo $arr['fclicks']; ?><br><br>
				  
			  
                <?php
			} else if($action == 'viewAd' && $adType == 'fbanner') {
		?>
                  <a href="index.php?tp=out&t=fb&id=<?php echo $arr['fnum']; ?>" target="_blank"><img src="<?php echo $arr['furl']; ?>" border="0" width="180" height="100"></a>
                  <br>
                  <br>
                  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="1"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fshows']; ?><br>
				  <?php echo __('Clicks'); ?>: <?php echo $arr['fclicks']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'fad') {
		?><a href="index.php?tp=out&t=fb&id=<?php echo $arr['fnum']; ?>" target="_blank"><?php echo $arr['ftitle']; ?></a><BR><?php echo $arr['description']; ?>
                  <br>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php } ?>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fshows']; ?><br>
				  <?php echo __('Clicks'); ?>: <?php echo $arr['fclicks']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'flinks') {
		?><a href="index.php?tp=out&t=fb&id=<?php echo $arr['fnum']; ?>" target="_blank"><?php echo $arr['ftitle']; ?></a><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php } ?>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fshows']; ?><br>
				  <?php echo __('Clicks'); ?>: <?php echo $arr['fclicks']; ?><br>
                  <BR>
                <?php
			} else if($action == 'viewAd' && $adType == 'links') {
		?><a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsitename']; ?></a><BR><?php echo $arr['furl']; ?><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>				  
              	  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php } ?>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fvisits']; ?><br>
                  <BR>
				  
                <?php
			 } else if($action == 'viewAd' && $adType == 'links') {
		?><a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsitename']; ?></a><BR><?php echo $arr['furl']; ?><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>				  
              	  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php } ?>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fvisits']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'ptrad') {
		?><a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsitename']; ?></a><BR><?php echo $arr['furl']; ?><BR><?php echo $arr['ptrad']; ?><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>				  
               	  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php } ?>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fvisits']; ?><br>
                  <BR>
				  
                <?php
			} else if($action == 'viewAd' && $adType == 'signup') {
		?>
                <a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsitename']; ?></a><BR><?php echo $arr['furl']; ?><br>
                <?php echo __('Secret Question'); ?>: <?php echo $arr['squest']; ?><BR>
                <?php echo __('Secret Answer'); ?>: <?php echo $arr['sansw']; ?><BR>
                  <br>
                  <?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
				  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
                  <?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
                  <font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
                  <BR>
                  <?php } ?>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Views'); ?>: <?php echo $arr['fsignups']; ?>
<?php 
			} else if($action == 'viewAd' && $adType == 'survey') {
		?>
                <a href="<?php echo $arr['siteurl']; ?>" target="_blank"><?php echo $arr['surveyname']; ?></a><BR><?php echo $arr['siteurl']; ?><br>
                  <br>
				  <table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
                    <tr>
                      <td bgcolor="#CCFFFF"><strong><?php echo __('Preview of Your Survey'); ?>:</strong><br><br>
                        
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
				  <?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
                  <?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br><?php } ?>
                  <?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
				  <?php echo __('Survey\'s Taken'); ?>: <?php echo $arr['fviews']; ?><br>
<?php
if($arr['fviews'] > 0) { ?>
<br>
<?php echo __('Survey\'s are listed below by username'); ?>.<HR>
<br>
<table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#666666">
  <tr bgcolor="#EEEEEE">
    <td><?php echo __('Username'); ?></td>
    <td><?php echo __('Date Taken'); ?> </td>
    <td><?php echo __('Status'); ?></td>
    <td><?php echo __('Read / View'); ?></td>
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
    <td><?php echo __('Completed'); ?></td>
    <td><a href="index.php?tp=viewSurveyResults&surveyID=<?php echo $arr['id']; ?>&aid=<?php echo $ar['id']; ?>" target="_blank"><?php echo __('View Survey'); ?></a></td>
  </tr>
  <?php
  }//END FOR LOOP
 } //END COUNT > 0
  ?>
</table><?php 
} //END SURVEY COUNT
			}else if($action == 'viewAd' && $adType == 'email') {
		?>
              <a href="<?php echo $arr['furl']; ?>" target="_blank"><?php echo $arr['fsubject']; ?></a><BR>
                <?php echo __('Promotion URL'); ?>: <?php echo $arr['furl']; ?><br>
<?php echo __('Subject'); ?>: <?php echo $arr['fsubject']; ?><BR>
<?php echo __('E-Mail Message'); ?>:<br><?php echo $arr['ftext']; ?><BR>
<br>
<?php if($arr['flogin'] != '' && $arr['fpassword'] != '') { ?>
<?php echo __('Advertisers Login'); ?>: <?php echo $arr['flogin']; ?><BR>
<?php echo __('Advertisers Password'); ?>: <?php echo $arr['fpassword']; ?><br>
<?php echo __('Ad Statistics Login URL'); ?>: <a href="<?php echo $ptrurl; ?>index.php?tp=adstats"><?php echo $ptrurl; ?>index.php?tp=adstats</a><br>
<font size="2"><?php echo __('You can provide the above details to a third party who you wish to provide statistics to. <strong> Note </strong>that any other ad\'s you have with the same advertiser password will be available for them to view.'); ?> </font><br>
<BR>
<?php } ?>
<?php echo __('Credits'); ?>: <?php echo $arr['fsize']; ?><br>
<?php echo __('Views'); ?>: <?php echo $arr['freads']; ?>
<?php
			}
		}
	}
	?></td>
          </tr>
      </table></p><?php
}
?>
        <br>
        <br>
        <div align="center">
        <form name="jumpForm">
          <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
            <option value="index.php?tp=manageads&adType=banner" <?php if($adType == 'banner' || $adType == '') { echo "selected"; } ?>><?php echo __('Banners'); ?> (480x60)</option>
            <option value="index.php?tp=manageads&adType=fbanner" <?php if($adType == 'fbanner') { echo "selected"; } ?>><?php echo __('Featured Banners'); ?> (180x100)</option>
            <option value="index.php?tp=manageads&adType=fad" <?php if($adType == 'fad') { echo "selected"; } ?>><?php echo __('Featured Ad'); ?></option>
            <option value="index.php?tp=manageads&adType=flinks" <?php if($adType == 'flinks') { echo "selected"; } ?>><?php echo __('Featured Link'); ?></option>
            <option value="index.php?tp=manageads&adType=links" <?php if($adType == 'links') { echo "selected"; } ?>><?php echo __('Link / Paid to Click'); ?></option>
            <option value="index.php?tp=manageads&adType=signup" <?php if($adType == 'signup') { echo "selected"; } ?>><?php echo __('Paid To Sign-Up'); ?></option>
            <option value="index.php?tp=manageads&adType=email" <?php if($adType == 'email') { echo "selected"; } ?>><?php echo __('Paid To Read E-Mail'); ?></option>
            <option value="index.php?tp=manageads&adType=survey" <?php if($adType == 'survey') { echo "selected"; } ?>><?php echo __('Paid To Take Survey\'s'); ?></option>
            <option value="index.php?tp=manageads&adType=ptrad" <?php if($adType == 'ptrad') { echo "selected"; } ?>><?php echo __('Paid To Read Ad\'s'); ?></option>
		  </select>
        </form>
    </div></td></tr>
  <?php
  
  
  
  
  if($action == 'addCreditsConfirm') {
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
	$maxCredits = totalBannerCredits($_SESSION['login'], $adType);
	$cost = $_REQUEST['creditsToAdd'] * $multiplier; 
	if($cost > $maxCredits) {
		$error = 'An error has occurred - Your ad will cost '.$cost.' credits, and you only have '.$maxCredits.' available.';
		$action = 'addCredits';
	}
  }
  if($action == 'addCreditsConfirm') {
  ?><tr>
  <td><table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong><?php echo __('How many credits would you like to add?'); ?></strong> </td>
    </tr>
    <tr>
      <td>
	  <?php echo '<h1>'.$adTypes[$adType]['name'].'</h1>'; ?>
	  <?php $maxCredits = totalBannerCredits($_SESSION['login'], $adType); ?>
      <form name="addCredits" method="post" action="index.php">
        <?php echo __('Credits Available'); ?>: <?php echo $maxCredits; ?>
        <br>
        <?php echo __('Add Credits'); ?>: 
		<?php echo $_REQUEST['creditsToAdd']; ?> <BR />
        <?php
		if($adTypes[$adType]['hasTypes'] == TRUE) {
			?><?php echo __('Ad Type'); ?>:
				<?php
				$creditsUsed = getValue("SELECT `".$adTypes[$adType]['credits']."` - `".$adTypes[$adType]['usedCredits']."` FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
				if($creditsUsed < 1) $editable = TRUE; else $editable = FALSE;
				
				$credits = $adTypes[$adType]['prefix'].'PayCredits';
				$credits = $setupinfo[$credits];
				$credits = explode(',',$credits);
				
				$names = $adTypes[$adType]['prefix'].'PayNames';
				$names = $setupinfo[$names];
				$names = explode(',',$names);
				
				$timers = $adTypes[$adType]['prefix'].'PayTimers';
				$timers = $setupinfo[$timers];
				$timers = explode(',',$timers);
				
				if($editable == FALSE) {
					$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
					echo '<h1>'.$names[$creditID].' - '.$credits[$creditID].' Credits Each ('.$timers[$creditID].' Seconds)</h1>';
				} else {
					$creditID = $_REQUEST['creditID'];
					echo ''.$names[$creditID].' - '.$credits[$creditID].' Credits Each ('.$timers[$creditID].' Seconds)';
				}
				$multiplier = $credits[$creditID];
				
				?>
			</select><?php
		} else {
			$multiplier = 1;
		}
		$cost = $_REQUEST['creditsToAdd'] * $multiplier;
		echo '<h2>This ad will cost '.$cost.' '.$adTypes[$adType]['name'].' credits.</h2>';
		?>
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<input type="hidden" name="adType" value="<?php echo $adType; ?>">
		<input type="hidden" name="creditID" value="<?php echo $creditID; ?>">
		<input type="hidden" name="creditsToAdd" value="<?php echo $_REQUEST['creditsToAdd']; ?>">
		<input type="hidden" name="tp" value="manageads">
		<input type="hidden" name="action" value="addCreditsNow">
        <br>
        <br>
        <input type="submit" name="Submit4" value="Add Credits">      
              </form></td>
    </tr>
  </table></td>
  </tr>
  <?php
  }
  
  if($action == 'addCredits') {
	 
  ?><tr>
  <td><table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong><?php echo __('How many credits would you like to add?'); ?></strong> </td>
    </tr><?php
    if($error != '') {
		?>
    	<tr>
      		<td><strong style="color: #F00;"><?php echo __($error); ?></strong> </td>
	    </tr><?php
	}
	?>
    <tr>
      <td>
	  <?php echo '<h1>'.$adTypes[$adType]['name'].'</h1>'; ?>
	  <?php $maxCredits = totalBannerCredits($_SESSION['login'], $adType); ?>
      <form name="addCredits" method="post" action="index.php" onsubmit="return false;">
        <?php echo __('Credits Available'); ?>: <?php echo $maxCredits; ?>
        <br>
        <?php echo __('Add Credits'); ?>: 
		<input name="creditsToAdd" type="text" value="0" size="10" maxlength="15"> <BR />
        <?php
		if($adTypes[$adType]['hasTypes'] == TRUE) {
			?><?php echo __('Ad Type'); ?>:
				<?php
				$creditsUsed = getValue("SELECT `".$adTypes[$adType]['credits']."` - `".$adTypes[$adType]['usedCredits']."` FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
				if($creditsUsed < 1) $editable = TRUE; else $editable = FALSE;
				
				$credits = $adTypes[$adType]['prefix'].'PayCredits';
				$credits = $setupinfo[$credits];
				$credits = explode(',',$credits);
				
				$names = $adTypes[$adType]['prefix'].'PayNames';
				$names = $setupinfo[$names];
				$names = explode(',',$names);
				
				$timers = $adTypes[$adType]['prefix'].'PayTimers';
				$timers = $setupinfo[$timers];
				$timers = explode(',',$timers);
				
				if($editable == FALSE) {
					$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
					echo '<h1>'.$names[$creditID].' - '.$credits[$creditID].' Credits Each ('.$timers[$creditID].' Seconds)</h1>';
				} else {
					echo '<select name="creditID">';
					for($i = 0;$i < count($credits);$i++) echo '<option value="'.$i.'">'.$names[$i].' - '.$credits[$i].' Credits Each ('.$timers[$i].' Seconds)</option>';
					echo '</select>';
				}
				
				?>
			</select><?php
		}
		?>
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<input type="hidden" name="adType" value="<?php echo $adType; ?>">
		<input type="hidden" name="tp" value="manageads">
		<input type="hidden" name="action" value="addCreditsConfirm">
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
  <?php
  if($action == 'retractCredits') {
  ?><tr>
  <td><table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong><?php echo __('How many credits would you like to retract?'); ?></strong> </td>
    </tr>
    <tr>
      <td><?php $maxCredits = creditsLeft($_REQUEST['id'],$_REQUEST['adType']); ?><form name="retractCredits" method="post" action="index.php" onsubmit="return false;">
        <?php echo __('Credits Available'); ?>: <?php echo $maxCredits; ?>
        <br>
        <?php echo __('Retract Credits'); ?>: 
		<input name="creditsToRetract" type="text" value="0" size="10" maxlength="15">
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<input type="hidden" name="adType" value="<?php echo $adType; ?>">
		<input type="hidden" name="tp" value="manageads">
		<input type="hidden" name="action" value="retractCreditsNow"> (<?php echo __('MAX'); ?> <?php echo $maxCredits; ?>)
        <br>
        <br>
        <input type="submit" name="Submit4" value="Retract Credits" onClick="verifyRetractCredits(<?php echo $maxCredits; ?>)">      
              </form></td>
    </tr>
  </table></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td><?php
	$valid = FALSE;
	if($adType == '' || $adType == 'banner') {
		$adType = 'banner';
		$table = 'banners';
		$valid = TRUE;
	} else if($adType == 'fbanner') {
		$table = 'fbanners';
		$valid = TRUE;
	} else if($adType == 'fad') {
		$table = 'featuredads';
		$valid = TRUE;
	} else if($adType == 'flinks') {
		$table = 'featuredlinks';
		$valid = TRUE;
	} else if($adType == 'links') {
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
	} else {
		echo "Invalid type !";
	}
	
	
	if($valid == TRUE) {
		$sql = "SELECT * FROM `$table` WHERE `username` = ".quote_smart($_SESSION['login'])."";
		//echo "Running SQL: ".$sql."<BR><BR>";
		$query = mysql_query($sql) or die(mysql_error()." <BR>While running : ".$sql."<BR><BR>");
		$count = mysql_num_rows($query);
		if($count == 0) {
			echo "There are currently no campaigns to display.<BR>";
		} else {
			?>      <br>
      <table width="500" border="0" cellpadding="5" cellspacing="1" bgcolor="#FFFFCC">
        <tr valign="top">
          <?php
		  if($adType == 'banner' || $adType == 'fbanner' || $adType == 'fad' || $adType == 'flinks') {
			  if($adType == 'banner' || $adType == 'fbanner') { ?>
			   <td width="100"><strong>Image</strong></td>
			   <?php
			   }
			   ?>
			  <td width="181"><strong><?php echo __('Name');?></strong></td>
			  <td width="49"><strong><?php echo __('Credits Left');?></strong></td>
			  <td width="49"><strong><?php echo __('Views');?></strong></td>
			  <td width="42"><strong><?php echo __('Clicks');?></strong></td>
			  <td width="188"><div align="right"><strong><?php echo __('Options');?></strong></div></td>
			  <?php
		  }
		  if($adType == 'ptrad') { ?>
              <td width="181"><strong><?php echo __('Name');?></strong></td>
              <td width="49"><strong><?php echo __('Credits Left');?></strong></td>
              <td width="42"><strong><?php echo __('Clicks');?></strong></td>
              <td width="42"><strong><?php echo __('Ad Type');?></strong></td>
              <td width="188"><div align="right"><strong><?php echo __('Options');?></strong></div></td>
		  <?php
		  }
		  if($adType == 'links') { ?>
              <td width="181"><strong><?php echo __('Name');?></strong></td>
              <td width="49"><strong><?php echo __('Credits Left');?></strong></td>
              <td width="42"><strong><?php echo __('Clicks');?></strong></td>
              <td width="42"><strong><?php echo __('Ad Type');?></strong></td>
              <td width="188"><div align="right"><strong><?php echo __('Options');?></strong></div></td>
		  <?php
		  }
		  if($adType == 'email') { ?>
              <td width="181"><strong><?php echo __('Subject');?></strong></td>
              <td width="49"><strong><?php echo __('Credits Left');?></strong></td>
              <td width="49"><strong><?php echo __('Sent');?></strong></td>
              <td width="42"><strong><?php echo __('Ad Type');?></strong></td>
              <td width="188"><div align="right"><strong><?php echo __('Options');?></strong></div></td>
		  <?php
		  }
		  if($adType == 'signup') { 
		  ?>
              <td width="180"><strong><?php echo __('Name');?></strong></td>
              <td width="49"><strong><?php echo __('Credits Left');?></strong></td>
              <td width="49"><strong><?php echo __('Sign-ups');?></strong></td>
              <td width="42"><strong><?php echo __('Ad Type');?></strong></td>
              <td width="188"><div align="right"><strong><?php echo __('Options');?></strong></div></td>
		  <?php
		  }
		  if($adType == 'survey') { 
		  ?>
              <td width="180"><strong><?php echo __('Name');?></strong></td>
              <td width="49"><strong><?php echo __('Credits Left');?></strong></td>
              <td width="49"><strong><?php echo __('Reads / Visits');?></strong></td>
              <td><strong><?php echo __('Username');?></strong></td>
              <td width="42"><strong><?php echo __('Ad Type');?></strong></td>
              <td width="188"><div align="right"><strong><?php echo __('Options');?></strong></div></td>
		  <?php
		  }
		  ?>
        </tr>
        <?php
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($query, $i);
			$arr = mysql_fetch_array($query);
			?><tr valign="top" bgcolor="#FFFFFF" <?php if($i%2) echo "bgcolor=\"#EEEEEE\""; ?>>
			  
			  
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
				  <td width="300"><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Delete'); ?></a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Add Credits'); ?></a><br>			    
					<a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=retractCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Retract Credits'); ?></a>		  <?php
		  }
		  
		  if($adType == 'links') { ?>
	      <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php if($arr['fname'] != '') { echo $arr['fname']; } else if($arr['fsitename'] != '') { echo $arr['fsitename']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fvisits']; ?></td>
          <td><?php echo $arr['fvisits']; ?></td>
          <td><?php
			  	$idType = $adTypes[$adType]['idType'];
		  		$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$idType."`=".quote_smart($arr[$idType])."");
		  		
				$credits = $adTypes[$adType]['prefix'].'PayCredits';
				$credits = $setupinfo[$credits];
				$credits = explode(',',$credits);
				
				$names = $adTypes[$adType]['prefix'].'PayNames';
				$names = $setupinfo[$names];
				$names = explode(',',$names);
				
				$timers = $adTypes[$adType]['prefix'].'PayTimers';
				$timers = $setupinfo[$timers];
				$timers = explode(',',$timers);
				
				echo ''.$names[$creditID].' - '.$timers[$creditID].' Seconds';
		  ?></td>
			  <td width="300"><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php echo __('Delete'); ?></a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php echo __('Add Credits'); ?></a><br>			    
			    <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=retractCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php echo __('Retract Credits'); ?></a><?php
		  }
		  if($adType == 'ptrad') { ?>
	      <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php if($arr['fname'] != '') { echo $arr['fname']; } else if($arr['fsitename'] != '') { echo $arr['fsitename']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fvisits']; ?></td>
          <td><?php echo $arr['fvisits']; ?></td>
          <td><?php
			  	$idType = $adTypes[$adType]['idType'];
		  		$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$idType."`=".quote_smart($arr[$idType])."");
		  		
				$credits = $adTypes[$adType]['prefix'].'PayCredits';
				$credits = $setupinfo[$credits];
				$credits = explode(',',$credits);
				
				$names = $adTypes[$adType]['prefix'].'PayNames';
				$names = $setupinfo[$names];
				$names = explode(',',$names);
				
				$timers = $adTypes[$adType]['prefix'].'PayTimers';
				$timers = $setupinfo[$timers];
				$timers = explode(',',$timers);
				
				echo ''.$names[$creditID].' - '.$timers[$creditID].' Seconds';
		  ?></td>
			  <td width="300"><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php echo __('Delete'); ?></a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php echo __('Add Credits'); ?></a><br>			    
			    <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=retractCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fn']; ?>"><?php echo __('Retract Credits'); ?></a><?php
		  }
		  if($adType == 'email') { ?>
	      <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo $arr['fsubject']; ?></a></td>
          <td><?php echo $arr['fsize']-$arr['freads']; ?></td>
		  <td><?php echo $arr['freads']; ?></td>
          <td><?php
			  	$idType = $adTypes[$adType]['idType'];
		  		$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$idType."`=".quote_smart($arr[$idType])."");
		  		
				$credits = $adTypes[$adType]['prefix'].'PayCredits';
				$credits = $setupinfo[$credits];
				$credits = explode(',',$credits);
				
				$names = $adTypes[$adType]['prefix'].'PayNames';
				$names = $setupinfo[$names];
				$names = explode(',',$names);
				
				$timers = $adTypes[$adType]['prefix'].'PayTimers';
				$timers = $setupinfo[$timers];
				$timers = explode(',',$timers);
				
				echo ''.$names[$creditID].' - '.$timers[$creditID].' Seconds';
		  ?></td>
			  <td width="300"><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Delete'); ?></a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Add Credits'); ?></a><br>			    
			    <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=retractCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Retract Credits'); ?></a><?php
		  }
		  if($adType == 'signup') { ?>
	      <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php if($arr['fname'] != '') { echo $arr['fname']; } else if($arr['fsitename'] != '') { echo $arr['fsitename']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fsignups']; ?></td>
		  <td><?php echo $arr['fsignups']; ?></td>
          <td><?php
			  	$idType = $adTypes[$adType]['idType'];
		  		$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$idType."`=".quote_smart($arr[$idType])."");
		  		
				$credits = $adTypes[$adType]['prefix'].'PayCredits';
				$credits = $setupinfo[$credits];
				$credits = explode(',',$credits);
				
				$names = $adTypes[$adType]['prefix'].'PayNames';
				$names = $setupinfo[$names];
				$names = explode(',',$names);
				
				$timers = $adTypes[$adType]['prefix'].'PayTimers';
				$timers = $setupinfo[$timers];
				$timers = explode(',',$timers);
				
				echo ''.$names[$creditID].' - '.$timers[$creditID].' Seconds';
		  ?></td>
			  <td width="300"><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Delete'); ?></a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Add Credits'); ?></a><BR>			      
	      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=retractCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['fnum']; ?>"><?php echo __('Retract Credits'); ?></a><?php
		  }
		  if($adType == 'survey') { ?>
		   <td><a href="index.php?tp=manageads&action=viewAd&adType=<?php echo $adType; ?>&id=<?php echo $arr['id']; ?>"><?php if($arr['surveyname'] != '') { echo $arr['surveyname']; } else if($arr['surveyname'] != '') { echo $arr['surveyname']; } ?></a></td>
          <td><?php echo $arr['fsize']-$arr['fviews']; ?></td>
		  <td><?php echo $arr['fviews']; ?></td>
          <td><?php
			  	$idType = $adTypes[$adType]['idType'];
		  		$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$idType."`=".quote_smart($arr[$idType])."");
		  		
				$credits = $adTypes[$adType]['prefix'].'PayCredits';
				$credits = $setupinfo[$credits];
				$credits = explode(',',$credits);
				
				$names = $adTypes[$adType]['prefix'].'PayNames';
				$names = $setupinfo[$names];
				$names = explode(',',$names);
				
				$timers = $adTypes[$adType]['prefix'].'PayTimers';
				$timers = $setupinfo[$timers];
				$timers = explode(',',$timers);
				
				echo ''.$names[$creditID].' - '.$timers[$creditID].' Seconds';
		  ?></td>
		  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></td>
			  <td><div align="right"><a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=remove&adType=<?php echo $adType; ?>&id=<?php echo $arr['id']; ?>"><?php echo __('Delete'); ?></a> |			      <a href="index.php?tp=manageads&adType=<?php echo $adType; ?>&action=addCredits&adType=<?php echo $adType; ?>&id=<?php echo $arr['id']; ?>"><?php echo __('Add Credits'); ?></a><?php
		  }
		  ?>		  </tr>
		<?php
		}
		?>
      </table>      <BR>
	<?php 
		}
	}
	?>
    <br>
    <br>
    <br>	
    <table width="569" border="0" align="center" cellpadding="10" cellspacing="0">
      <tr>
        <td width="549"><?php
	if($adType == '' || $adType == 'banner') {
		?>
          <form name="form1" method="post" action="index.php">
            <table width="100%" border="0" align="center" cellpadding="10" bgcolor="#FFFFCC">
              <tr>
                <td width="234"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Ad Name'); ?>: </font></td>
                <td width="150"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="name" type="text" value="<?php echo  $fname?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr>
                <td width="234"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Banner link URL'); ?>:</font></td>
                <td width="150">
                  <input name="flink" type="text" maxlength="255">
                  <input type="hidden" name="tp" value="manageads">
                  <input type="hidden" name="adType" value="<?php echo $adType; ?>">
                  <input type="hidden" name="act" value="addBanner">
                </td>
              </tr>
              <tr>
                <td width="234"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Banner image URL'); ?>:</font></td>
                <td width="150">
                  <input name="furl" type="text" maxlength="255">
                </td>
              </tr>
			  <tr>
			  	<td colspan="2">
				
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
			  </tr>
              <tr>
                <td width="234"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input type="submit" name="Submit" value="<?php echo __('Add banner',false); ?>">
                </font></td>
                <td width="150"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
              </tr>
            </table>
          </form>
          <?php
	} else if($adType == 'fbanner') {
		?>
          <form name="form1" method="post" action="index.php">
            <table width="100%" border="0" align="center" cellpadding="10" bgcolor="#FFFFCC">
              <!--DWLayoutTable-->
              <tr>
                <td width="234"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Ad name'); ?></font></td>
                <td width="150"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="name" type="text" id="name" value="<?if ($act=='add') echo $fname?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Banner link URL'); ?>:</font></td>
                <td>
                  <input name="flink" type="text" id="flink" size="25" maxlength="255">
                  <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input name="act" type="hidden" id="act" value="addfbanner">
                  </font> </td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Banner image URL'); ?>:</font></td>
                <td>
                  <input name="furl" type="text" id="furl" size="25" maxlength="255">
                </td>
              </tr>
              <tr>
                <td height="70" colspan="2" valign="top">
				
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
                </tr>
              <tr>
                <td> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input type="submit" name="Submit" value="<?php echo __('Add banner'); ?>">
                </font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
              </tr>
            </table>
          </form>
          <?php
	} else if($adType == 'fad') {
		?>
          <form name="form1" method="post" action="index.php">
            <table width="100%" border="0" align="center" cellpadding="10" bgcolor="#FFFFCC">
              <!--DWLayoutTable-->
              <tr>
                <td width="127"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Ad Title / Name'); ?>: </font></td>
                <td width="257"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="name" type="text" id="name" value="<?if ($act=='add') echo $fname?>" size="25" maxlength="15">
</font></td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Ad URL'); ?> :</font></td>
                <td>
                  <input name="flink" type="text" id="flink" size="25" maxlength="255">
                </td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Your Ad'); ?> :<br>
                  (<?php echo __('40 Characters'); ?>)</font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <textarea name="description" cols="40" rows="4"><?if ($act=='add') echo $description?></textarea>
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input name="act" type="hidden" id="act" value="addfad">
                </font> </td>
              </tr>
              <tr>
                <td height="73" colspan="2" valign="top">
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
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
            <table width="100%" border="0" align="center" cellpadding="10" bgcolor="#FFFFCC">
              <!--DWLayoutTable-->
              <tr>
                <td width="234"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Ad Title / Name'); ?>: </font></td>
                <td width="150"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="name" type="text" id="name" value="<?if ($act=='add') echo $fname?>" size="25" maxlength="15">
</font></td>
              </tr>
              <tr>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Ad URL'); ?> :</font></td>
                <td>
                  <input name="flink" type="text" id="flink" size="25" maxlength="255">
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input name="act" type="hidden" id="act" value="addflink">
                </td>
              </tr>
              <tr>
                <td height="73" colspan="2" valign="top">
				
				
				
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
                </tr>
            </table>
            <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
              <input type="submit" name="Submit" value=" <?php echo __('Add Featured Ad'); ?> ">
            </font> </div>
          </form>
          <?php
	} else if($adType == 'links') {
		?>
          <form name="addvisit" method="post" action="index.php">
            <table width="100%" border="0" align="center" cellpadding="10" bgcolor="#FFFFCC">
              <!--DWLayoutTable-->
              <tr valign="top">
                <td width="234"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Campaign URL'); ?>:</font></td>
                <td width="150"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="url" type="text" value="<?php if($act=add) echo $_REQUEST['furl']; ?>" size="25" maxlength="255">
</font></td>
              </tr>
              <tr valign="top">
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Site name'); ?>:</font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="sitename" type="text" value="<?if($act=add) echo $_REQUEST['sitename']?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr valign="top">
                <td height="50" colspan="2" valign="top">
				
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
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
                </div></td>
              </tr>
            </table>
            <div align="center">
              <input type="submit" name="Submit2" value="<?php echo __('Add campaign'); ?>">
            </div>
            <div align="center"></div>
          </form>
          <?php
	 }else if($adType == 'ptrad') {
		?>
          <form name="addvisit" method="post" action="index.php">
            <table width="100%" border="0" align="center" cellpadding="10" bgcolor="#FFFFCC">
              <!--DWLayoutTable-->
              <tr valign="top">
                <td width="186"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Campaign URL'); ?>:</font></td>
                <td width="317"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input name="url" type="text" value="<?php if($act=='add') echo $_REQUEST['furl']; ?>" size="25" maxlength="255">
</font></td>
              </tr>
              <tr valign="top">
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Site name'); ?>:</font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="sitename" type="text" value="<?if($act=add) echo $_REQUEST['sitename']?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr valign="top">
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Paid to read Ad'); ?></font></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <textarea name="ptrad" cols="50" rows="8"><?php if($act=='add') echo $_REQUEST['ptrad']?></textarea>
                </font></td>
              </tr>
              <tr valign="top">
                <td height="50" colspan="2" valign="top">
				
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
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
                    <input name="act" type="hidden" id="act" value="addptrad">
                </div></td>
              </tr>
            </table>
            <div align="center">
              <input type="submit" name="Submit2" value="<?php echo __('Add campaign'); ?>">
            </div>
            <div align="center"></div>
          </form>
          <?php
	 } else if($adType == 'signup') {
		?>
         <form name="addvisit" method="post" action="index.php">
		 <table width="100%" height="312" border="0" align="center" cellpadding="10" bgcolor="#FFFFCC">
            <tr valign="top">
              <td width="49%" height="24"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Campaign URL'); ?>:</font></td>
              <td width="51%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input type="text" name="url" size="40" value="<?if($act=='add') echo $_REQUEST['furl']?>"><?php echo __(' Requires '); ?>http://
              </font></td>
            </tr>
            <tr valign="top">
              <td width="49%" height="24"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Site name'); ?>:</font></td>
              <td width="51%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input type="text" name="sitename" size="40" value="<?if($act=='add') echo $fsitename?>">
              </font></td>
            </tr>
            <tr valign="top">
              <td width="49%" height="88"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Site description'); ?>:</font></td>
              <td width="51%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <textarea name="note" cols="40" rows="4"><?if($act=='add') echo $fnote?></textarea>
              </font></td>
            </tr>
            <tr valign="top">
              <td width="49%" height="24"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php echo __('Secret question'); ?>:</font></td>
              <td width="51%"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
                <input type="text" name="squest" size="40" value="<?php if($act=='add') echo $_REQUEST['sq']; ?>">
                <br>
      <?php echo __('Must be an obvious question that can only be answered after successfully signing up.'); ?></font></td>
            </tr>
            <tr valign="top">
              <td width="49%" height="24"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php echo __('Secret answer'); ?>:</font></td>
              <td width="51%"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
                <input type="text" name="sansw" size="40" value="<?if($act=='add') echo $sa?>">
                <br>
      <?php echo __('Must be an obvious answer found after successfully signing up.'); ?></font></td>
            </tr>
			<tr valign="top">
				<td colspan="2">
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
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
              </div></td>
            </tr>
          </table>
         <div align="center">
           <input type="submit" name="Submit5" value="<?php echo __('Add campaign'); ?>">
         </div>
         </form>
          <?php 
	} else if($adType == 'email') {
		?>
          <form name="addvisit" method="post" action="index.php"><table border="0" cellspacing="0" cellpadding="0" bgcolor="FFFFCC" align="center" width="100%">
            <tr>
              <td>
                  <table width="100%" border="0" align="left" cellpadding="10" bgcolor="#FFFFCC">
                    <tr>
                      <td width="203"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Subject'); ?>:</font></td>
                      <td width="300">
                        <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                          <input type="text" name="subject" size="50" value="<?if($act=='add') echo$fsubject?>">
                      </font></div></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('E-mail content'); ?>:</font></div></td>
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
              <?php echo __('Text format'); ?>
              <input type="radio" name="mailformat" value="html" <?php if($act=='add' && $fformat=='html') echo"checked"?>>
              <?php echo __('HTML format'); ?></font></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <table width="340">
                          <tr>
                            <td align="right" class="light" width="147"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Mail coding'); ?>: </font></td>
                            <td align="left" class="light" width="181">
                              <select name="mailcoding">
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
                                <option value="UTF-8" selected="selected">English (UTF-8)</option>
                            </select></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <br>
                <?php echo __('Link for receive credit for reading this e-mail'); ?>:<br>
                <input type="text" name="mailurl" size="50" value="<?if($act=='add') echo$_REQUEST['furl']?>">
                <br>
                      </font></div></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                       
				
				<?php echo __('Provide Someone Unique Advertiser Login?'); ?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();"><?php echo __('Yes'); ?>
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" checked>
				<?php echo __('No'); ?>
				<div id="adDetailDiv" style="display:none;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password'); ?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password'); ?></font>
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
                      <td colspan="2">&nbsp;</td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table>
            <div align="center">
              <input type="submit" name="Submit3" value="<?php echo __('Send e-mail to members'); ?>">
            </div>
          </form>
          <?php
	} else if($adType == 'survey') {
	//echo "<pre>".print_r($_REQUEST,1)."</pre>";
		?><strong><?php echo __('Add your new Paid Survey below.'); ?></strong><br>
<?php echo __('You will need to add at least 2 questions to create a survey. Simply fill out the question information, add as many answers as you want for that question if it is multiple choice, and click Add This Survey to finish setting up your ad.'); ?><BR><br>
<HR>
          <form name="form1" method="post" action="index.php?tp=manageads&adType=<?php echo $adType; ?>">
            <table width="534" border="0" align="center" cellpadding="10">
              <tr>
                <td width="167"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Survey Name'); ?>:</font></td>
                <td width="321"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="surveyname" type="text" value="<?php if(isset($_REQUEST['siteurl'])) echo $_REQUEST['surveyname']; ?>" size="25" maxlength="20">
                </font></td>
              </tr>
              <tr>
                <td width="167"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Survey link URL'); ?>:</font></td>
                <td width="321">
                  <input name="siteurl" type="text" maxlength="255" value="<?php echo $_REQUEST['siteurl']; ?>">
                  <input name="tp" type="hidden" id="tp" value="manageads">
                  <input name="adType" type="hidden" id="adType" value="<?php echo $adType; ?>">
                  <input type="hidden" name="act" value="addSurvey">
                </td>
              </tr>
			  <tr>
			  <td colspan="2"><br>			    <table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#006633">
                  <tr>
                    <td bgcolor="#FFFFCC"><p><strong><?php echo __('Add a new Survey Question Below'); ?></strong></p>
                      <p align="center"><?php echo __('Your Question'); ?>:<br>
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
  <option value="text"><?php echo __('User Input Answer'); ?></option>
  <option value="radio"><?php echo __('Radio Button Answer'); ?></option>
  <option value="checkbox"><?php echo __('Check Box Answer'); ?></option>
  <option value="dropdown"><?php echo __('Drop Down Answer'); ?></option>
</select>
<?php
				  } else {
				  	if($_SESSION['answerType'] == 'radio') echo __("Radio Button User Input.");
				  	if($_SESSION['answerType'] == 'dropdown') echo __("Dropdown Menu User Input.");
				  	if($_SESSION['answerType'] == 'checkbox') echo __("Checkbox User Input.");
				  }
				  ?>
<br>
<br>
</p>
                      <div id="text" <?php if($_SESSION['answerType'] != '' && $_SESSION['answerType'] != 'text') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong><?php echo __('Input form user input.'); ?></strong><br>
        <?php echo __('A Form will be provided to the user to give their answer. You will be able to see what they wrote when a user completes the survey.'); ?> </td>
                          </tr>
                        </table>
                      </div>
                      <div id="radio"  <?php if($_SESSION['answerType'] != 'radio') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong><?php echo __('Radio Button user input.'); ?></strong><br>
        <?php echo __('Add at least 2 answers below. A radio button will be an either or situation.'); ?> <br>
        <?php echo __('Answer:'); ?>
        <input name="radioAnswer" type="text" size="25" maxlength="25">
        <br>
        <input type="submit" name="addAnswer" value="<?php echo __('Add Another Answer'); ?>"></td>
                          </tr>
                        </table>
                      </div>
                      <div id="checkbox"  <?php if($_SESSION['answerType'] != 'checkbox') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong><?php echo __('Checkbox user input'); ?>.</strong><br>
        <?php echo __('Add at least 2 answers below. A checkbox will be question where someone can choose multiple answers..'); ?> <br>
        <?php echo __('Answer'); ?>:
        <input name="checkboxAnswer" type="text" size="25" maxlength="25">
        <br>
        <input type="submit" name="addAnswer" value="<?php echo __('Add Another Answer'); ?>"></td>
                          </tr>
                        </table>
                      </div>
                      <div id="dropdown" <?php if($_SESSION['answerType'] != 'dropdown') echo "style=\"display: none;\""; ?>>
                        <table width="85%"  border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#FF0000">
                          <tr>
                            <td bgcolor="#FFFFFF"><strong><?php echo __('Dropdown user input.'); ?></strong><br>
        <?php echo __('Add at least 2 answers below. A dropdown will be question where someone can choose an answer from a list.'); ?> <br>
        <?php echo __('Answer'); ?>:
        <input name="dropdownAnswer" type="text" size="35" maxlength="35">
        <br>
        <input type="submit" name="addAnswer" value="<?php echo __('Add Another Answer'); ?>"></td>
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
                        <input type="submit" name="addQuestion" value="<?php echo __('Add This Question to my Survey'); ?>">
                      </div></td>
                  </tr>
                </table>
			    <p>&nbsp;                  </p>
			    <div align="center">
			      <table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
                    <tr>
                      <td bgcolor="#CCFFFF"><strong><?php echo __('Preview of Your Survey'); ?>:</strong><br><br>
                        
                        <?php
				if(isset($_SESSION['surveyquestions']) && is_array($_SESSION['surveyquestions'])) {

					foreach($_SESSION['surveyquestions'] as $k => $v) {
						?>
						<table width="98%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
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
							echo "<strong>Q:</strong> ".$v['question']."<BR>A: (".__('User Input Form').")<BR>";
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
				
				
				<?php echo __('Provide Someone Unique Advertiser Login?');?><br>
				<input type="radio" name="advertiserDetails" onClick="javascript:showAdvertiserDetails();" <?php if($_REQUEST['flogin'] != '' && $_REQUEST['fpassword'] != '') { echo "checked"; } ?>>Yes
				<input name="advertiserDetails" type="radio" onClick="javascript:hideAdvertiserDetails();" <?php if($_REQUEST['flogin'] == '' && $_REQUEST['fpassword'] == '') { echo "checked"; } ?>>
				No
				<div id="adDetailDiv" <?php if($_REQUEST['flogin'] == '' && $_REQUEST['fpassword'] == '') { ?>style="display:none;"<?php } ?>>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Login'); ?></font></div></td>
                <td>&nbsp;</td>
                  <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adLogin" type="text" id="adLogin" value="<?php echo $_SESSION['login']; ?>" disabled>
                  </font></td>
              </tr>
                  <tr>
                    <td height="4"></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top">
                      <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Password');?></font></div></td>
                <td></td>
                    <td valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="adPassword" type="text" id="adPassword"> <font size='1'><?php echo __('Unique Advertiser only Password');?></font>
                    </font></td>
              </tr>
                </table>
				</div>				</td>
			  </tr>
              <tr>
                <td width="167"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                  <br>
                  <br>
                  <br>
                  <input type="submit" name="Submit" value="<?php echo __('Add This Survey');?>">
                </font></td>
                <td width="321"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
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
                  <input type="submit" name="Submit" value="<?php echo __('Start Over from Scratch');?>">
                </font></td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </form>
          <?php
	}
	?></td>
      </tr>
    </table></td>
  </tr>
</table>



<div style="width: 90%; padding: 10px 10px 10px 10px; background-color: #FFFFCC; border: thin #FFCC66; margin: 10px 10px 10px 10px;">
<?php

$ptcAds = getValue("SELECT COUNT(fn) FROM `tasks` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=links">'.__('Paid to Click').' ('.$ptcAds.')</a> &nbsp; ';

$ptsAds = getValue("SELECT COUNT(fnum) FROM `signups` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=signup">'.__('Paid to Signup').' ('.$ptsAds.')</a> &nbsp; ';

$ptrAds = getValue("SELECT COUNT(fn) FROM `ptrads` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=ptrad">'.__('Paid to Read Ad\'s').' ('.$ptrAds.')</a> &nbsp; ';

$ptsurveyAds = getValue("SELECT COUNT(id) FROM `surveys` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=survey">'.__('Paid to Take Survey\'s').' ('.$ptsurveyAds.')</a> &nbsp; ';

$ptReadEmail = getValue("SELECT COUNT(fnum) FROM `reads` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=email">'.__('Paid to Read Email').' ('.$ptReadEmail.')</a> &nbsp; ';

$banners = getValue("SELECT COUNT(fnum) FROM `banners` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=banner">'.__('Banners').' ('.$banners.')</a> &nbsp; ';

$fbanners = getValue("SELECT COUNT(fnum) FROM `fbanners` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=fbanner">'.__('Featured Banners').' ('.$fbanners.')</a> &nbsp; ';

$featuredads = getValue("SELECT COUNT(fnum) FROM `featuredads` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=fad">'.__('Featured Ads').' ('.$featuredads.')</a> &nbsp; ';

$featuredlinks = getValue("SELECT COUNT(fnum) FROM `featuredlinks` WHERE username = ".quote_smart($_SESSION['login'])."");
echo '<a href="index.php?tp=manageads&adType=flinks">'.__('Featured Links').' ('.$featuredlinks.')</a> &nbsp; ';




?></div>



</p><?php echo $pageFooter; ?>