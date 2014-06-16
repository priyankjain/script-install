<?php
$mailcode = $_REQUEST['mailcode'];
$sql=mysql_query("SELECT * FROM `reads` WHERE `fnum`=".quote_smart($mailcode)."");
$rows=mysql_num_rows($sql); if($rows == 0) {echo"SORRY! THIS LINK HAS EXPIRIED..."; exit;}
	$arr=mysql_fetch_array($sql);
	extract($arr);
	$username = $_REQUEST['username'];
echo '
<html>
<head>
<title>'.__('Thank you for visiting our sponsor\'s site!').'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';

echo '
</head>

<frameset';
echo ' rows="80,*" frameborder="NO" border="0" framespacing="0"> 
<frame';
echo ' name=visit src="index.php?tp=toptaskmail&id='.$username.'&username='.$username.'&mailcode='.$mailcode.'" frameborder=0>
<frame'; echo ' name=visit src="'.prepURL(getValue("SELECT `furl` AS `url` FROM `reads` WHERE `fnum`=".quote_smart($mailcode)."")).'" frameborder=0>
</'; echo 'frameset>
<noframes><body bgcolor="#FFFFFF" text="#000000">
'.__('ERROR: Frames must be enabled in order to view this site correctly. Please switch to a frames enabled browser (Such as Firefox or Internet Explorer) in order to view the paid emails system.').'
</body></noframes>
</html>';
?>