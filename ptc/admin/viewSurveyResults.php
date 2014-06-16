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
 
$surveyID = $_REQUEST['surveyID'];
$activity = $_REQUEST['aid'];

$sql = mysql_query("SELECT * FROM surveys WHERE id = ".quote_smart($surveyID)."");
$count = mysql_num_rows($sql);
if($count > 0) {
	$query = mysql_query("SELECT * FROM surveyactivity WHERE surveyID = ".quote_smart($surveyID)." AND id = ".quote_smart($activity)."");
	$count = mysql_num_rows($query);
	if($count > 0) {
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($query, $i);
			$arr = mysql_fetch_array($query);
			?>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
<strong>Survey Results for <?php echo $arr['username']; ?></strong><BR>
Date of survey: <?php echo $arr['dateTaken']; ?><BR>
Survey Details are below.<BR>
<BR>
<table width="500"  border="0" cellpadding="5" cellspacing="1" bgcolor="#000099">
  <tr>
    <td bgcolor="#BAEFFE"><?php echo $arr['surveyResults']; ?></td>
  </tr>
</table>
<BR>
<BR><BR><?php
		}
	} else {
		exit("Invalid account and/or survey activity record.<BR>");
	}
} else {
	exit("Invalid account and/or survey record.<BR>");
}


?>