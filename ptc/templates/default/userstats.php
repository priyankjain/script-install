<?php
if(isset($_SESSION['login'])) {
	getVars("SELECT * FROM users WHERE username = ".quote_smart($_SESSION['login'])."");
} else {
	exit(__("Cannot continue. You are  not logged in or your session has timed out..."));
}
$uid=$id;


//echo getValue("select value from design where name='backOfficeHome'");
?>
<h1><?php echo __('GET STARTED EARNING WITH OUR SYSTEM BELOW!'); ?></h1>
<?php echo __('It\'s easy to earn with '.$ptrname.'! To get started, find a way to earn below and click the &quot;Click Here&quot; link next to it to view those paid advertisements.'); ?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="">
  <tr>
    <td bgcolor="#FFFFFF"><br />
    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
        <!--DWLayoutTable-->
        <?php
        if($disablePTC != '1') {
		?><tr>
          <td height="29" valign="top"><img src="<?php echo $templateFolder; ?>images/chart.png" hspace="10" vspace="10" align="left" /><a href="index.php?tp=paidclicks"><strong><font size="4"><br />
            <?php echo __('Get Paid to Click - Viewing Websites'); ?></font></strong> (<?php echo __('Click Here'); ?>)</a> :<strong> (<?php echo getCount("SELECT COUNT(fn) FROM tasks WHERE fsize > fvisits AND fn NOT IN (SELECT `task` FROM taskactivity WHERE username = ".quote_smart($_SESSION['login'])." AND fdate = DATE(NOW())) ", "SUM", "COUNT(fn)"); ?> <?php echo __('Links'); ?>)</strong> <br />
          <?php echo __('Earn money by clicking on website links, and then viewing the website for the alloted amount of time for that specific website.'); ?> </td>
        </tr>
        <?php
		}
		?>
        <?php
        if($disablePTS != '1') {
		?>
        <tr>
          <td height="29" valign="top"><img src="<?php echo $templateFolder; ?>images/accept_page.png" hspace="10" vspace="10" align="left" /><a href="index.php?tp=paidsignups"><strong><font size="4"><br />
            <?php echo __('Get Paid to Sign-Up for Other Websites'); ?></font></strong> (<?php echo __('Click Here');?>)</a> :<strong> (<?php
		  	$sql=mysql_query("SELECT fnum FROM signups WHERE fsize > fsignups");
			$rows=mysql_num_rows($sql);
			$totalTasks=0;
			for($i=0;$i<$rows;$i++) {
				mysql_data_seek($sql,$i);
				$arr=mysql_fetch_array($sql);
				$count = getValue("SELECT COUNT(fourlog) FROM signtask WHERE fourlog = ".quote_smart($_SESSION['login'])." AND tasknum = ".quote_smart($arr['fnum'])."");
				if($count == 0) $totalTasks++;
			}
			echo $totalTasks;
		  ?> <?php echo __('Guaranteed Signups'); ?>)</strong> <br />
          <?php echo __('Here you can earn money for signing up for one of our sponsored sign-up programs');?>. </td>
        </tr>
        <?php
		}
		?>
        <?php
        if($disablePTSURVEY != '1') {
		?>
        <tr>
          <td height="29" valign="top"><img src="<?php echo $templateFolder; ?>images/comments.png" hspace="10" vspace="10" align="left" /><a href="index.php?tp=paidsurvey"><strong><font size="4"><br />
            <?php echo __('Get Paid to Take Surveys'); ?> </font></strong> (<?php echo __('Click Here'); ?>)</a> :<strong> (<?php echo getCount("SELECT COUNT(id) FROM surveys WHERE fsize > fviews AND id NOT IN (SELECT surveyID FROM surveyactivity WHERE username = ".quote_smart($_SESSION['login']).")", "SUM", "COUNT(id)"); ?> Survey's)</strong> <br />
          <?php echo __('Here you can get paid by taking part in short survey\'s offered by our advertisers.'); ?></td>
        </tr>
        <?php
		}
		?>
        <?php
        if($disablePTR != '1') {
		?>
        <tr>
          <td height="29" valign="top"><img src="<?php echo $templateFolder; ?>images/full_page_dollar.png" hspace="10" vspace="10" align="left" /><a href="index.php?tp=ptrads"><strong><font size="4"><br />
            <?php echo __('Get Paid to Read Ads'); ?> </font> </strong> (<?php echo __('Click Here'); ?>)</a> :<strong> (<?php echo getCount("SELECT COUNT(fn) FROM ptrads WHERE fsize > fvisits AND fn NOT IN (SELECT `task` FROM ptradsactivity WHERE username=".quote_smart($_SESSION['login'])." AND fdate=DATE(now()))", "SUM", "COUNT(fn)"); ?> Ads)</strong> <br />
          <?php echo __('Here you can get paid for reading short ads, and visiting websites posted by our sponsors.'); ?></td>
        </tr>
        <?php
		}
		?>
        <?php
        if($disablePTEMAIL != '1') {
		?>
        <tr>
          <td height="29" valign="top"><img src="<?php echo $templateFolder; ?>images/mail_send.png" hspace="10" vspace="10" align="left" /><strong><font size="4"><br />
            <?php echo __('Read E-Mail'); ?></font> <?php if($paidEmails != "1") { echo '(<span style="color: #FF0000;">DISABLED! <a href="index.php?tp=editinfo&highlightPaidEmails=1">Click Here</a> to enable paid e-mails.</span>)'; } ?></strong><br />
          <?php echo __('We will occasionally send an email to you. In that e-mail will be a link that you can click and get paid to view! Check your e-mail address often for these! If you do not wish to receive Paid Emails then simply go to your Profile and turn off this option.'); ?></td>
        </tr>
        <?php
		}
		?>
        <tr>
          <td height="29" valign="top"><img src="<?php echo $templateFolder; ?>images/users.png" hspace="10" vspace="10" align="left" /><strong><font size="4"><br />
            <?php echo __('Refer Others'); ?></font></strong><br /> 
            <?php echo __('Using your personalized referral url, you can share it with others, and when someone uses that url to join our system, you will earn when these members perform &quot;Paid Tasks&quot; in their account. You will get a percentage of each earning and it will be deposited instantly into your account!'); ?>
</td>
        </tr>
    </table></td>
  </tr>
</table>
