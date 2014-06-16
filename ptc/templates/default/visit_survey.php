<?php
if(!isset($_SESSION)) session_start();
$id = $_REQUEST['id'];
$sql=mysql_query("SELECT * FROM surveys WHERE id=".quote_smart($id)."");
$rows=mysql_num_rows($sql);

$siteurl = prepURL($siteurl);
if($rows > 0) { 
	$arr=mysql_fetch_array($sql);
	extract($arr);
	if(getCount("SELECT COUNT(id) FROM surveyactivity WHERE username = ".quote_smart($_SESSION['login'])." AND surveyID = ".quote_smart($id)."", "COUNT") > 0) {
		//ACTIVITY FOUND;
	} else {
		exit(__("You have not yet completed this survey! You cannot earn credit for a survey until it has been completed successfully.<BR> While running 'query'"));
	}
	echo '
	<html>
	<head>
	<title>'.$fsurveyname.'</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	</head>
	
	<frameset rows="80,*" frameborder="NO" border="0" framespacing="0"> 
	<frame name="visit" src="index.php?tp=topsurveytask&t='.$t.'&id='.$id.'" frameborder="0">
	<frame name="visit" src="'.prepURL($siteurl).'" frameborder="0">
	
	</frameset>
	<noframes><body bgcolor="#FFFFFF" text="#000000">
	'.__('ERROR: Frames must be enabled in order to view this site correctly. Please switch to a frames enabled browser (Such as Firefox or Internet Explorer) in order to view the paid survey system.').'
	</body></noframes>
	</html>
	';
} else {
	echo __('Error: This page was entered incorrectly.<BR>');
}
?>