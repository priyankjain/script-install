<?php echo $pageHeader; ?>
<h2><?php echo __('View Survey Results'); ?></h2>
<p><?php
if(!isset($_SESSION['login'])) exit(__("You must be logged in to view this page.<BR>"));
$surveyID = $_REQUEST['surveyID'];
$activity = $_REQUEST['aid'];

$sql = mysql_query("SELECT * FROM surveys WHERE id = ".quote_smart($surveyID)." AND username = ".quote_smart($_SESSION['login'])."");
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
<strong><?php echo __('Survey Results for'); ?> <?php echo $arr['username']; ?></strong><BR>
<?php echo __('Date of survey'); ?>: <?php echo $arr['dateTaken']; ?><BR>
<?php echo __('Survey Details are below'); ?>.<BR>
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
		exit(__("Invalid account and/or survey activity record.<BR>"));
	}
} else {
	exit(__("Invalid account and/or survey record.<BR>"));
}


?></p><?php echo $pageFooter; ?>