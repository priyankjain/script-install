<?php
if(!isset($_SESSION)) session_start();
$id = $_REQUEST['id'];
$sql=mysql_query("SELECT * FROM surveys WHERE id=".quote_smart($id)."");
$rows=mysql_num_rows($sql);

if($rows > 0) { 
$arr=mysql_fetch_array($sql);
extract($arr);
$query = mysql_query("SELECT * FROM surveyactivity WHERE username = ".quote_smart($_SESSION['login'])." AND  surveyID = ".quote_smart($id)."");
$count = mysql_num_rows($query);
if($count > 0) {
	$alreadyVisited = TRUE;
}
if($_REQUEST['act'] == 'submitSurvey') {
	$q = mysql_query("SELECT DISTINCT `option`,optionType,question FROM surveyquestions WHERE surveyID = ".quote_smart($id)." ORDER BY `id` ASC");
	$c = mysql_num_rows($q);
	if($c > 0 ) { 
		$surveyResults = '';
		for($i = 0;$i < $c;$i++) {
			mysql_data_seek($q,$i);
			$a = mysql_fetch_array($q);
			$surveyResults .= "<HR>Question: ".$a['question']."<BR>";
			if($a['optionType'] == 'checkbox') { //CHECKBOX'S
				if(is_array($_REQUEST['option'.$a['option']])) {
					foreach($_REQUEST['option'.$a['option']] as $k => $v) {
						$surveyResults .=  "A:".getValue("SELECT optionName FROM surveyquestions WHERE surveyID = ".quote_smart($id)." AND `option` = ".quote_smart($a['option'])." AND optionValue=".quote_smart($v)."")."<BR>";
					}
				} else {
					$surveyResults .=  "A:".getValue("SELECT optionName FROM surveyquestions WHERE surveyID = ".quote_smart($id)." AND `option` = ".quote_smart($a['option'])." AND optionValue=".quote_smart($_REQUEST['option'.$a['option']])."")."<BR>";
				}
			} else if($a['optionType'] == 'text') { //TEXT INPUT
				$surveyResults .=  "A: ".$_REQUEST['option'.$a['option']]."<BR>";
			} else { //RADIO OR DROP DOWN MENU
				$sq = "SELECT optionName FROM surveyquestions WHERE surveyID = ".quote_smart($id)." AND `option` = ".quote_smart($a['option'])." AND optionValue=".quote_smart($_REQUEST['option' . $a['option']]);
				$surveyResults .=  "A: ".getValue($sq)."<BR>";
			}
		}
		if($surveyResults != '') {
			mysql_query("INSERT INTO surveyactivity (username, surveyName, surveyID, surveyResults, dateTaken) VALUES (
			".quote_smart($_SESSION['login']).",
			".quote_smart($arr['surveyName']).",
			".quote_smart($id).",
			".quote_smart($surveyResults).",
			NOW()
			)") or die(mysql_error());
			if(mysql_affected_rows()) {
				header("Location: index.php?tp=visit_survey&id=".$id."");
			} else {
				exit(__("ERROR: Could not proccess your survey. Please ensure you do not have any odd characters inserted into your answers (Such as a foreign input character.)."));
			}
		}
	}
}
	?>
<html>
<head>
<title><?php echo "$fsurveyname"?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body bgcolor="#FFFFFF" text="#000000">
<?php
if($alreadyVisited == TRUE) {
	echo __("You have already completed this survey. You can only complete a survey once.<BR>");
} else {
	?> <a href="<?php echo $arr['siteurl']; ?>" target="_blank"><?php echo $arr['surveyname']; ?></a><BR><?php echo $arr['siteurl']; ?><br>
	  <br>
	  <table width="100%"  border="0" cellpadding="5" cellspacing="1" bgcolor="#3366CC">
		<tr>
		  <td bgcolor="#CCFFFF"><strong><?php echo __('Preview of Your Survey'); ?>:</strong><br><br>
			
			<?php
	$q = mysql_query("SELECT * FROM surveyquestions WHERE surveyID = ".quote_smart($arr['id'])." ORDER BY `id` ASC");
	$c = mysql_num_rows($q);
	if($c > 0 ) { 
		?><form action="index.php" name="SurveyForm">
			<input type="hidden" name="tp" value="viewsurvey">
			<input type="hidden" name="act" value="submitSurvey">
			<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
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
				if($array['optionType'] == 'dropdown') echo "<select name=\"option".$array['option']."\">\n";
			}
			
			if($array['optionType'] == 'radio') {
				echo "<input type=\"radio\" name=\"option".$array['option']."\" value=\"".$array['optionValue']."\"> ".$array['optionName']."<BR>\n\n"; 
			} else if($array['optionType'] == 'dropdown') {
				echo "<option value=\"".$array['optionValue']."\">".$array['optionName']."</option>\n";
			} else if($array['optionType'] == 'checkbox') {
				echo "<input type=\"checkbox\" name=\"option".$array['option']."[]\" value=\"".$array['optionValue']."\"> ".$array['optionName']."<BR>\n\n"; 
			} else if($array['optionType'] == 'text') {
				echo "<input type=\"text\" name=\"option".$array['option']."\" value=\"\">\n\n<BR>"; 
			}
			$prevName = $array['question'];
			$prevType = $array['optionType'];
		}
		?></td></tr></table><BR><BR><input type="submit" name="Submit" value="<?php echo __('Submit my survey');?>"></form><?php
	}
	
	?></td>
		</tr>
	  </table>
	  
	  <?php
	  
}
?>
</body>
</html>
<?php
} else {
	echo __('Error: This page was entered incorrectly'); 
}?>