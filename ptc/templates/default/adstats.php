<?php echo $pageHeader; ?>
<h1><?php echo __('Advertiser Real-Time Statistics'); ?></h1>
		<p><?php echo __('Have an ad running on our website? Here you can view statistics and information on your advertisements with real-time updates and statistics.'); ?></p><?php

if(!$adpassword && $_SESSION['adpassword']) $adpassword = $_SESSION['adpassword']; 
if(!$adlogin && $_SESSION['adlogin']) $adlogin = $_SESSION['adlogin']; 

if($adlogin && $adpassword) $loginSession = TRUE; else $loginSession = FALSE;


if($loginSession) {
	//if(mysql_num_rows($sql)) {$arr=mysql_fetch_array($sql); extract($arr);}
	$campaign[1] = FALSE; //PTC
	$campaign[2] = FALSE; //EMAIL
	$campaign[3] = FALSE; //PTS
	$campaign[4] = FALSE; //BANNER
	$campaign[5] = FALSE; //FEATURED BANNERS
	$campaign[6] = FALSE; //FEATURED ADS
	$campaign[7] = FALSE; //FEATURED LINKS
	$adsFound = FALSE; 
	$sql=mysql_query("SELECT * FROM ptrads WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[9] = TRUE;
	$sql=mysql_query("SELECT * FROM surveys WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[8] = TRUE;
	$sql=mysql_query("SELECT * FROM featuredlinks WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[7] = TRUE;
	$sql=mysql_query("SELECT * FROM featuredads WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[6] = TRUE;
	$sql=mysql_query("SELECT * FROM fbanners WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[5] = TRUE;
	$sql=mysql_query("SELECT * FROM banners WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[4] = TRUE;
	$sql=mysql_query("SELECT * FROM `reads` WHERE flog=".quote_smart($adlogin)." AND fpass=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[2] = TRUE;
	$sql=mysql_query("SELECT * FROM signups WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[3] = TRUE;
	$sql=mysql_query("SELECT * FROM tasks WHERE flog=".quote_smart($adlogin)." AND fpass=".quote_smart($adpassword)."") or die(mysql_error());
	if(mysql_num_rows($sql) > 0) $campaign[1] = TRUE;
	
	foreach($campaign as $k => $v) { if($v == TRUE) $adsFound = TRUE; }
} else {//END IF $loginSession == TRUE
	$adsFound = FALSE;
}
if($adsFound) { 
$_SESSION['adlogin'] = $adlogin;
$_SESSION['adpassword'] = $adlogin;
?>
<br>

<strong><?php echo __('You are currently logged in as'); ?> <?php echo $adlogin; ?>.</strong><BR><br>

<?php } ?>
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr valign="top"> 
    <td width="66%" class="bord1"> 
     
	 <?php
	 	if(!$adsFound) {
	 	?>
	  <p><br>
	 <?php if($loginSession == TRUE) { ?><strong style="color: #FF0000"><?php echo __('Your login could not be verified. Please check your login details and try again.'); ?></strong><BR><?php } ?>
	  </p>
      <form name="advertlogin" method="post" action="index.php">
        <div align="center"><?php echo __('Have an advertisers login to view statistics? Fill out the form below.'); ?> </div>
        <table width="326" border="0" align="center" cellpadding="5" cellspacing="2" bgcolor="#FF9900">
          <tr>
            <td width="312" bgcolor="#FFFFCC">
            
            
            <table width="43%" border="0" align="center" cellpadding="5">
                <tr>
                  <td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Advertisers Login'); ?>:</font></td>
                  <td width="50%">
                    <input type="text" name="adlogin">
                  </td>
                </tr>
                <tr>
                  <td width="50%" height="31"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo __('Advertisers Password'); ?>:</font></td>
                  <td width="50%" height="31">
                    <input type="password" name="adpassword">
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <div align="center">
                      <input type="submit" name="Submit" value="<?php echo __('Login to advertiser\'s area'); ?>">
                      <input type="hidden" name="tp" value="adstats">
                  </div></td>
                </tr>
            </table>
            
            </td>
          </tr>
        </table>
      </form>
        <?php
	 } else {
		 if($campaign[9] == TRUE) { ?><h2>&quot;<?php echo __('Paid to Read Ad\'s &quot; campaign(s)'); ?></h2>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Campaign Name'); ?></b></div></td>
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Credits'); ?></b></div></td>
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Campaign URL'); ?>:</b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Delivered visits'); ?></b></div></td>
  </tr>
  <?php
  $sql = "SELECT * FROM ptrads WHERE flog=".quote_smart($adlogin)." AND fpass=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to read.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr> 
    <td valign="top" align="left"><?php
		  echo"$fsitename";
		  ?> 
      <div align="left"></div></td>
    <td valign="top" align="left"> 
      <?php
		  echo"$fsize";
		  ?>
      <div align="left"></div></td>
    <td valign="top" align="left"> 
      <?php
		  echo"$furl";
		  ?>
      <div align="left"></div></td>
    <td valign="middle" align="right"> 
      <?php
		  echo"$fvisits";
		  ?>
      <div align="right"></div></td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table><?php }
		 if($campaign[8] == TRUE) { ?>
<table width="100%" cellspacing="1" cellpadding="5" bgcolor="#666666">
<tr><td><div align="center"><b style="color: #FFFFFF;"><?php echo __('&quot;Paid to Take Survey\'s &quot; campaign(s)'); ?></b></div></td></tr></table>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Survey Name'); ?></b></div></td>
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Credits'); ?></b></div></td>
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Campaign URL'); ?>:</b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Delivered Survey\'s'); ?> </b></div></td>
  </tr>
  <?php
  $sql = "SELECT * FROM surveys WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to read.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr> 
    <td valign="top" align="left"><?php
		  echo"$fsitename";
		  ?> 
      <div align="left"></div></td>
    <td valign="top" align="left"> 
      <?php
		  echo"$fsize";
		  ?>
      <div align="left"></div></td>
    <td valign="top" align="left"> 
      <?php
		  echo"$furl";
		  ?>
      <div align="left"></div></td>
    <td valign="middle" align="right"> 
      <?php
		  echo"$fviews";
		  ?>
      <div align="right"></div></td>
  </tr>
  <?php
  if($fviews > 0) {
 ?>
<br>
<?php echo __('Survey\'s are listed below by username.'); ?><HR>
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
  }
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table>
<?php }
		 if($campaign[7] == TRUE) { ?>
<h2><?php echo __('&quot;Featured Link &quot; campaign(s)'); ?></h2>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td> 
    <div align="left"><b><?php echo __('Campaign Name'); ?></b></div></td>
    <td> 
    <div align="left"><b><?php echo __('Credits'); ?></b></div></td>
    <td> 
    <div align="left"><b><?php echo __('Campaign URL'); ?>:</b></div></td>
    <td> 
      <div align="center"><strong><?php echo __('Views / Visits'); ?> </strong></div>
    </td>
  </tr>
  <?php
  
  $sql = "SELECT * FROM featuredlinks WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to click.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr valign="top"> 
    <td><?php
		  echo"$fname";
		  ?> 
      <div align="left"></div></td>
    <td> 
      <?php
		  echo"$fsize";
		  ?>
      <div align="left"></div></td>
    <td> 
      <?php
		  echo"$flink";
		  ?>
      <div align="left"></div></td>
    <td align="center"> 
      <?php
		  echo"$fshows / $fclicks";
		  ?>
    </td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table>
<?php }
		 if($campaign[6] == TRUE) { ?>
<h2><?php echo __('&quot;Featured Ad &quot; campaign(s)'); ?></h2>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td> 
    <div align="left"><b><?php echo __('Campaign'); ?></b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Credits'); ?></b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Views'); ?>:</b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Clicks'); ?></b></div></td>
  </tr>
  <?php
  
  $sql = "SELECT * FROM featuredads WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to click.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr valign="top"> 
    <td><?php
		  echo"<b>$fname</b><BR>$description";
		  ?> 
      <div align="left"></div></td>
    <td align="right"> 
      <?php
		  echo"$fsize";
		  ?>
      <div align="right"></div></td>
    <td align="right"> 
      <?php
		  echo"$fshows";
		  ?>
      <div align="right"></div></td>
    <td align="right"> 
      <?php
		  echo"$fclicks";
		  ?>
      <div align="right"></div></td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table>
<?php }
		 if($campaign[5] == TRUE) { ?>
<h2><?php echo __('&quot;Featured Banner &quot; campaign(s)'); ?></h2>

<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="57%" align="left"> 
    <div align="left"><b><?php echo __('Featured Banner'); ?></b></div></td>
    <td width="18%" align="right"> 
    <div align="right"><b><?php echo __('Credits'); ?></b></div></td>
    <td width="13%" align="right"> 
    <div align="right"><b><?php echo __('Views'); ?></b></div></td>
    <td width="12%" align="right"> 
    <div align="right"><b><?php echo __('Clicks'); ?></b></div></td>
  </tr>
  <?php
  
  $sql = "SELECT * FROM fbanners WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to click.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr valign="top" bgcolor="#FFFFFF"> 
    <td align="left"><?php
		  echo "<a href=\"$flink\" alt=\"$fname\" target=\"_blank\"><img src=\"$furl\"></a>";
		  ?> 
      <div align="left"></div></td>
    <td align="right"> 
      <?php
		  echo"$fsize";
		  ?>
      <div align="right"></div></td>
    <td align="right"> 
      <?php
		  echo"$fshows";
		  ?>
      <div align="right"></div></td>
    <td align="right"> 
      <?php
		  echo"$fclicks";
		  ?>
      <div align="right"></div></td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td colspan="5" align="center" valign="middle"> 
      <hr size="1">
    </td>
  </tr>
</table>
<?php }
		 if($campaign[4] == TRUE) { ?><h2><?php echo __('&quot;Banner &quot; campaign(s)'); ?></h2>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="57%" align="left"> 
    <div align="left"><b><?php echo __('Banner'); ?></b></div></td>
    <td width="18%" align="right"> 
    <div align="right"><b><?php echo __('Credits'); ?></b></div></td>
    <td width="13%" align="right"> 
    <div align="right"><b><?php echo __('Views'); ?></b></div></td>
    <td width="12%" align="right"> 
    <div align="right"><b><?php echo __('Clicks'); ?></b></div></td>
  </tr>
  <?php
  
  $sql = "SELECT * FROM banners WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to click.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr valign="top" bgcolor="#FFFFFF"> 
    <td align="left"><?php
		  echo "<a href=\"$flink\" alt=\"$fname\" target=\"_blank\"><img src=\"$furl\"></a>";
		  ?> 
    <div align="left"></div></td>
    <td align="right"> 
      <?php
		  echo"$fsize";
		  ?>
    <div align="right"></div></td>
    <td align="right"> 
      <?php
		  echo"$fshows";
		  ?>
    <div align="right"></div></td>
    <td align="right"> 
      <?php
		  echo"$fclicks";
		  ?>
    <div align="right"></div></td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table><?php }
		 if($campaign[3] == TRUE) { ?>
<h2><?php echo __('&quot;Paid to Signup &quot; campaign(s)'); ?></h2>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td> 
    <div align="left"><b><?php echo __('Site Name'); ?></b></div></td>
    <td> 
    <div align="left"><b><?php echo __('Description'); ?></b></div></td>
    <td> 
    <div align="left"><b><?php echo __('Campaign URL'); ?>:</b></div></td>
    <td align="left"> 
    <div align="left"><b><?php echo __('Question / Answer'); ?> </b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Signups'); ?></b></div></td>
  </tr>
  <?php
  
  $sql = "SELECT * FROM signups WHERE flogin=".quote_smart($adlogin)." AND fpassword=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to click.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr valign="top"> 
    <td><?php
		  echo"$fsitename";
		  ?> 
      <div align="left"></div></td>
    <td> 
      <?php
		  echo"$fnote";
		  ?>
      <div align="left"></div></td>
    <td> 
      <?php
		  echo"$furl";
		  ?>
      <div align="left"></div></td>
    <td align="left"> 
      <?php
		  echo"Q: $squest<BR>A: $sansw";
		  ?>
    </td>
	    <td align="right"> 
      <?php
		  echo"$fsignups of $fsize";
		  ?>
      <div align="right"></div>
    <div align="right"></div></td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table>

<?php }
		 if($campaign[2] == TRUE) { ?>
<h2><?php echo __('&quot;Paid to Read Email&quot; campaign(s)'); ?></h2>

<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td align="left"> 
    <div align="left"><b><?php echo __('Campaign URL'); ?></b></div></td>
    <td align="left"> 
    <div align="left"><b><?php echo __('Subject'); ?></b></div></td>
    <td align="left"> 
    <div align="left"><b><?php echo __('Email'); ?></b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Credits'); ?></b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Sent'); ?></b></div></td>
  </tr>
  <?php
  $sql = "SELECT * FROM `reads` WHERE flog=".quote_smart($adlogin)." AND fpass=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to click.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr valign="top"> 
    <td align="left"><?php
		  echo"$furl";
		  ?> 
      <div align="left"></div></td>
    <td align="left"><?php
		  echo"$fsubject";
		  ?> 
      <div align="left"></div></td>
    <td align="left"> 
      <?php
		  echo"$ftext";
		  ?>
      <div align="left"></div></td>
    <td align="right"> 
      <?php
		  echo"$fsize";
		  ?>
      <div align="right"></div></td>
    <td align="right"> 
      <?php
		  echo"$freads";
		  ?>
      <div align="right"></div></td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table>
<?php }
		 if($campaign[1] == TRUE) { ?>
<h2><?php echo __('&quot;Paid to Click &quot; campaign(s)'); ?></h2>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Campaign Name'); ?></b></div></td>
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Credits'); ?></b></div></td>
    <td align="left" valign="top"> 
      <div align="left"><b><?php echo __('Campaign URL'); ?>:</b></div></td>
    <td align="right"> 
    <div align="right"><b><?php echo __('Delivered visits'); ?></b></div></td>
  </tr>
  <?php
  $sql = "SELECT * FROM tasks WHERE flog=".quote_smart($adlogin)." AND fpass=".quote_smart($adpassword)."";
  $query = mysql_query($sql);
  $count = mysql_num_rows($query);
  if($count == 0) {
  	echo "<tr><td colspan='5'>".__('No ad\'s found for paid to click.')."</td></tr>";
  } else {
  
  for($i = 0; $i < $count;$i++) {
  	mysql_data_seek($query, $i);
	extract(mysql_fetch_array($query));
  ?><tr> 
    <td valign="top" align="left"><?php
		  echo"$fsitename";
		  ?> 
      <div align="left"></div></td>
    <td valign="top" align="left"> 
      <?php
		  echo"$fsize";
		  ?>
      <div align="left"></div></td>
    <td valign="top" align="left"> 
      <?php
		  echo"$furl";
		  ?>
      <div align="left"></div></td>
    <td valign="middle" align="right"> 
      <?php
		  echo"$fvisits";
		  ?>
      <div align="right"></div></td>
  </tr>
  <?php
  } //END FOR LOOP
  } //END > 0 COUNT
  ?>
  <tr> 
    <td colspan="5" valign="middle" align="center"> 
      <hr size="1">
    </td>
  </tr>
</table>
<?php }
	 } //END ADS FOND
	 ?>
      </p>     
    </td>
  </tr>
</table>

<br>
<br>
<?php echo $pageFooter; ?>