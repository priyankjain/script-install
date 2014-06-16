<?php

if(!function_exists('__')) {function __($var=''){return $var;}}
?>
<?php echo $pageHeader; ?><style type="text/css">
<!--
.styleGreen {
	color: #009900;
	font-weight: bold;
}
-->
</style>
        
        <h1><?php echo __('View available advertisements.'); ?></h1>
         <p>
        <?php echo __("With ".$ptrname.", we offer a variety of ad's that our members can view to earn. Whether it's a &quot;Paid to Click&quot;, &quot;Paid to Read&quot;, &quot;Paid to Take Survey's&quot; or some other advertisement, we can guarantee 1 thing, you will earn simply by viewing it ! (Or performing the task, depending on the ad type)."); ?></p>
        <BR />
<?php if($disablePTC != '1') {?>
 <img src="templates/default/images/chart.png" alt="picture" width="64" height="64" align="left" style="float: left;" />
 <h2><?php echo __("Paid to Click Ads"); ?></h2>
        <p><?php echo __("There are currently"); ?> <?php echo getValue("SELECT COUNT(fn) FROM `tasks` WHERE fvisits < fsize AND enddate < DATE(NOW())"); ?> <?php echo __("ad(s) that you can earn from simply by clicking on the link and viewing the advertisement for the specified amount of time (10 seconds, 30 seconds, 60 seconds, depending on the ad)."); ?></p>
        <div style="overflow: auto; width: 100%; height: 200;">
            <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EFEFEF">
              <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM tasks WHERE fvisits < fsize AND enddate < DATE(NOW())");
$rows=mysql_num_rows($sql);
if($rows > 0){
?><tr valign="top" align="left">
                <td width="971" bgcolor="<?php echo $bgcolor; ?>"><strong>
				<?php echo __("Ad Website"); ?></strong></td>
				<td width="208" align="right" bgcolor="<?php echo $bgcolor; ?>"><strong><?php echo __("You Earn"); ?></strong></td>
              </tr><?php
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	$bgcolor = '#EFEFEF';
	for($i=$start;$i<$end;$i++) {
		if($bgcolor == '#EFEFEF') $bgcolor = '#FFFFCC'; else $bgcolor = '#EFEFEF';
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
		
				$prise = $ptc_pay_amount;
				$fpaytype = $ptc_pay_type;
				//$prise = getCommPrice($prise,$_SESSION['login'],'ptc');
				$fprise = getCommPrice($_SESSION['login'],'ptc',$fn);
				$fpaytype = getCommPayType('ptc',$fn);
				$prise = $fprise;
				?>
              <tr valign="top" align="left">
                <td width="971" bgcolor="<?php echo $bgcolor; ?>">
				<?php
                if($_SESSION['login'] != '') {
				?><a href="index.php?tp=visit_task&t=<?php echo $fn; ?>&id=<?php echo $fn; ?>" target='_blank' onclick='document.location.reload();'><?php } else { echo "<a href=\"index.php?tp=signup\">"; } ?>
				<?php echo $fsitename; ?>
                </a>				</td>
				<td width="208" align="right" bgcolor="<?php echo $bgcolor; ?>"><?php
                if($_SESSION['login'] != '') {
				?><a href="index.php?tp=visit_task&t=<?php echo $fn; ?>&id=<?php echo $fn; ?>" target='_blank' onclick='document.location.reload();'><?php } else { echo "<a href=\"index.php?tp=signup\">"; } ?>
				    <span class="styleGreen">
				    <?php
				if($fpaytype=='points') { ?>
				    <?php echo $prise; ?> <?php echo $setupinfo['pointsName']; ?>(s)
				    <?php }
				else if($fpaytype=='usd') echo $setupinfo['currency'].$prise;
				?>
			    </span></a></td>
              </tr> <?php

	}
	?><TR><TD COLSPAN="2"><?php
	if($end<$rows) {
		echo __("And MORE!");
	}
	?></TD></TR><?php
	
}
?>
            </table>
        </div><BR />
<?php } //END IF PTC DISABLED ?>
<?php if($disablePTR != '1') { ?>
        <img src="templates/default/images/full_page_dollar.png" alt="picture" width="64" height="64" style="float: left;" /><h2><?php echo __("Paid to Read Ads"); ?></h2>
        <p><?php echo __("There are currently"); ?> <?php echo getValue("SELECT COUNT(fn) FROM `ptrads` WHERE fvisits < fsize AND enddate < DATE(NOW())"); ?> <?php echo __("ad(s) that you can earn from simply by reading the advertisement and viewing the website for the ad that you have read."); ?></p>
        <div style="overflow: auto; width: 100%; height: 200;">
          <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM ptrads WHERE fvisits < fsize");
$rows=mysql_num_rows($sql);
if($rows > 0) {
	$clicks = 0;
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	$bgcolor = '#EFEFEF';
	for($i=$start;$i<$end;$i++) {
		if($bgcolor == '#EFEFEF') $bgcolor = '#FFFFCC'; else $bgcolor = '#EFEFEF';
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
		
				$fprise = getCommPrice($_SESSION['login'],'ptrad',$fn);
				$fpaytype = getCommPayType('ptrad',$fn);
				$prise = $fprise;
				?>
  <tr valign="top" align="left">
                <td width="100%" bgcolor="<?php echo $bgcolor; ?>">
				<?php
                if($_SESSION['login'] != '') { ?><a href="index.php?tp=ptrads_visit&sF=1&id=<?php echo $fn; ?>" target="_blank" onclick="document.reload()"><?php } else { echo "<a href=\"index.php?tp=signup\">"; } ?>
				<?php echo $fsitename; ?></a>				</td>
			<td width="208" align="right" bgcolor="<?php echo $bgcolor; ?>"><?php echo __("You earn"); ?>: 
				<?php
				if($fpaytype=='points') { ?><?php echo $prise; ?> <?php echo $setupinfo['pointsName']; ?>(s)<?php }
				else if($fpaytype=='usd') echo $setupinfo['currency'].$prise;
				?>
				</td></tr> <?php
				$clicks++;

	}
	
	if($end<$rows) {
		echo "<tr><td COLSPAN=\"2\" bgcolor=\"".$bgcolor."\">".__("And Much More")."!</td></tr>";
	}
}
?>
          </table>
        </div>
        <BR />
<?php } //END PTR ?>
<?php if($disablePTS != '1') { ?>
        <!-- PTS -->
        <img src="templates/default/images/accept_page.png" alt="picture" width="64" height="64" style="float: left;" />
        <h2><?php echo __("Paid to Sign Up"); ?></h2>
          <?php echo __("Get paid to join our advertiser's websites! 
        Our advertisers want to pay you to join their online programs. Whether it's a free or paid membership depends on the advertisers, but one thing remains the same, we will pay you the amount shown next to the link under \"You Earn\"... Simply sign up to the website listed, paste your \"Username / Email\" address you used and the welcome letter (for approval, you can remove the sensitive information) and your sign up credit will be added to your account as soon as it has been verified!"); ?>
          <p></p>
          <div style="overflow: auto; width: 100%; height: 200;">
          <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EFEFEF">
            <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM signups WHERE fsignups < fsize");
$rows=mysql_num_rows($sql);
if($rows > 0) {
	$clicks = 0;
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	for($i=$start;$i<$end;$i++) {
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
		
				$fprise = getCommPrice($_SESSION['login'],'pts',$fnum);
				$fpaytype = getCommPayType('pts',$fnum);
				$prise = $fprise;
				?>
            <tr valign="top" align="left">
              <td width="60%">
              <?php
              if($_SESSION['login'] != '') { /*?><a href="index.php?tp=confirmreg&id=<?php echo $username; ?>&num=<?php echo $fnum; ?>" target="blank" onclick='location.reload()'><?php*/ 
			  ?><a href="index.php?tp=paidsignups" target="blank" onclick='location.reload()'><?php
			  } else {
			  	echo '<a href="index.php?tp=signup">';
			  } ?><?php echo $fsitename; ?></a><br />
              </td>
              <td width="40%" align="right"><?php echo __("You earn"); ?>:
                <?php
				if($fpaytype=='points') { ?>
                  <?php echo $prise.$setupinfo['pointsName']; ?>(s)
                <?php }
				else if($fpaytype=='usd') echo $setupinfo['currency'].$prise;
				?>
              </td>
            </tr>
            <?php
				$clicks++;

	}

	if($end<$rows) {
		echo "<tr><td COLSPAN=\"2\">".__("And Much More")."!</td></tr>";
	}

}
?>
          </table>
        </div><br />
<br />

<?php } ?>
<?php if($disablePTSURVEY != '1') { ?>
        <img src="templates/default/images/comments.png" alt="picture" width="64" height="64" style="float: left;" /><h2><?php echo __("Paid to Take Survey's"); ?></h2>
        <p><?php echo __("There are currently"); ?><?php echo getValue("SELECT COUNT(id) FROM `surveys` WHERE fviews < fsize"); ?><?php echo __(" ad(s) that you can earn from simply by reading the advertisement and viewing the website for the ad that you have read."); ?></p>
        <div style="overflow: auto; width: 100%; height: 200;">
          <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EFEFEF">
            <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM surveys WHERE fviews < fsize");
$rows=mysql_num_rows($sql);
if($rows > 0) {
	$clicks = 0;
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	for($i=$start;$i<$end;$i++) {
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
		
				$fprise = getCommPrice($_SESSION['login'],'ptsurvey',$id);
				$fpaytype = getCommPayType('ptsurvey',$id);
				$prise = $fprise;
				?>
            <tr valign="top" align="left">
              <td width="60%">
              <?php
              if($_SESSION['login'] != '') { ?><a href="index.php?tp=viewsurvey&id=<?php echo $id; ?>&amp;startSurvey=1" target="blank" onclick='location.reload()'><?php
			  } else {
			  	echo '<a href="index.php?tp=signup">';
			  } ?><?php echo $surveyname; ?></a><br />
                (<?php echo getCount("SELECT COUNT(DISTINCT `option`) FROM surveyquestions WHERE surveyID = ".quote_smart($id)."","SUM","COUNT(DISTINCT `option`)"); ?> Questions) </td>
              <td width="40%" align="right"><?php echo __("You earn"); ?>:
                <?php
				if($fpaytype=='points') { ?>
                  <?php echo $prise; ?> <?php echo $setupinfo['pointsName']; ?>(s)
                <?php }
				else if($fpaytype=='usd') echo $setupinfo['currency'].$prise;
				?>
              </td>
            </tr>
            <?php
				$clicks++;

	}

	if($end<$rows) {
		echo "<tr><td COLSPAN=\"2\">".__("And Much More")."!</td></tr>";
	}

}
?>
          </table>
        </div><br />
<br />

<?php }// END PAID TO SIGN UP ?>

        <?php
        if($disablePTEMAIL != '1') {
		?>
        <img src="<?php echo $templateFolder; ?>images/mail_send.png" hspace="10" vspace="10" align="left" /><h2>
            <?php echo __("Paid to Read E-Mail"); ?></h2> <?php if($_SESSION['login'] != '' && $paidEmails != "1") { echo '(<span style="color: #FF0000;">DISABLED! <a href="index.php?tp=editinfo&highlightPaidEmails=1">'.__("Click Here").'</a> '.__('to enable paid e-mails.').'</span>)'; } ?><br />
          <p><?php echo __("We will occasionally send an email to you. In that e-mail will be a link that you can click and get paid to view! Check your e-mail address often for these! If you do not wish to receive Paid Emails then simply go to your Profile and turn off this option.");?></p>
		  <div style="overflow: auto; width: 100%; height: 200;">
          <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EFEFEF">
            <?php
if(!$start) $start=0;
$count=35;
$sql=mysql_query("SELECT * FROM `reads` WHERE freads < fsize");
$rows=mysql_num_rows($sql);
if($rows > 0) {
	$clicks = 0;
	if($rows<=($start+$count)) $end=$rows; else $end=$start+$count;
	for($i=$start;$i<$end;$i++) {
		mysql_data_seek($sql,$i);
		$arr=mysql_fetch_array($sql);
		extract($arr);
			$fprise = getCommPrice($_SESSION['login'],'ptr',$fnum);
			$fpaytype = getCommPayType('ptr',$fnum);
			$prise = $fprise;
				?>
            <tr valign="top" align="left">
              <td width="60%">
              <?php
              if($_SESSION['login'] != '') { ?><a href="#" onclick='alert("<?php echo __("Emails are sent directly to your email address automatically on a regular basis so long as they are enabled in your account."); ?>");'><?php
			  } else {
			  	echo '<a href="index.php?tp=signup">';
			  } ?><?php echo $fsubject; ?></a> </td>
              <td width="40%" align="right"><?php echo __("You earn"); ?>:
                <?php
				if($fpaytype=='points') { ?>
                  <?php echo $prise; ?> <?php echo $setupinfo['pointsName']; ?>(s)
                <?php }
				else if($fpaytype=='usd') echo $setupinfo['currency'].$prise;
				?>
              </td>
            </tr>
            <?php
				$clicks++;

	}

	if($end<$rows) {
		echo "<tr><td COLSPAN=\"2\">".__("And Much More")."!</td></tr>";
	}

}
?>
          </table>
        </div><br />
<br />
<?php
		}
		?>       <p>&nbsp;</p>
        
        
<?php echo $pageFooter; ?>