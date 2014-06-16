<?php
$t = $_REQUEST['t'];
$id = $_REQUEST['id'];
$sql=mysql_query("SELECT * FROM tasks WHERE fn=".quote_smart($t)."");
$rows=mysql_num_rows($sql);
$furl = prepURL($furl);
if($rows > 0) {
	$arr=mysql_fetch_array($sql);
	extract($arr);
	echo '
	<html>
	<head>
	<title>'.$fsurveyname.'</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	</head>
	
	<frameset rows="80,*" frameborder="NO" border="0" framespacing="0"> 
	<frame name=visit src="index.php?tp=toptask&t='.$t.'&id='.$id.'" frameborder=0>
	<frame name=visit src="'.prepURL($furl).'" frameborder=0>
	
	</frameset>
	<noframes><body bgcolor="#FFFFFF" text="#000000">
	'.__('ERROR: Frames must be enabled in order to view this site correctly. Please switch to a frames enabled browser (Such as Firefox or Internet Explorer) in order to view the paid to click system.').'
	</body></noframes>
	</html>
	';
} else {
	echo __('Error: This page was entered incorrectly.<BR>');
}?>