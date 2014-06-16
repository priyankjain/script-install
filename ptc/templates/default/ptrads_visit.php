<?php
if(!function_exists('__')) {function __($var=''){return $var;}}
$setupinfo = getArray("SELECT * FROM setupinfo");
if(!isset($_SESSION)) session_start();
if($_SESSION['login'] == '') exit(__("Invalid Login Details"));
$membership = getUserMembershipDetails($_SESSION['login']);
if($membership['status'] == TRUE) {
	if($membership['readadTimer'] == 0)
	$timerLength = $setupinfo['ptReadAdTimer'];
	else
	$timerLength = $membership['readadTimer'];
} else {
	$timerLength = $setupinfo['ptReadAdTimer'];
}
$id = $_REQUEST['id'];
$sql=mysql_query("SELECT * FROM ptrads WHERE fn=".quote_smart($id)."");
$rows=mysql_num_rows($sql);
if($rows > 0) { 
	$arr=mysql_fetch_array($sql);
	extract($arr);
	$t = $fn;
	$id = $fn;
	if(!isset($_SESSION['ptrRand']) || $_REQUEST['sF'] == '1') {
		$_SESSION['ptrRand'] = rand(6,25);
		?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title><?php echo __('Paid to Read Ads'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body bgcolor="#666666" text="#000000">
<form id="" name="Submit" action="index.php">
<input type="hidden" name="tp" value="ptrads_visit">
<div align="center">
  <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
</div>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr>
    <td><div style="overflow: scroll; width: 500px; height: 400px; background-color: #FFFFFF; border-color: #00CC00; border: 0;" width="500px" height="400px" align="center" > <?php echo $ptrad; ?> </div></td>
  </tr>
</table>
<div align="center"><br>
  <input type="submit" value="Go (<?php echo $timerLength; ?>)" disabled="disabled" id="submitButton" style="background-color: #FFFFFF; color: #999999;">
  <br>
  <br>
  <br>
</div>
</form>
<script type="text/javascript" language="Javascript">
	function setTimer(currentTime) {
		currentTime--;
		var submitBtn = document.getElementById('submitButton');
		if(currentTime == 0) {
			submitBtn.disabled = false;
			submitBtn.style.color = "#000000";
			submitBtn.style.border = 5;
			submitBtn.value = "     Go!     ";
		} else {
			timeCounter = setTimeout('setTimer('+currentTime+')',1000);
			submitBtn.value = "<?php echo __('Please Wait'); ?> (" + currentTime + ")";
		}
		
		return currentTime;
	}
	
	timeCounter = <?php echo $timerLength; ?>;
	timeCounter = setTimer(timeCounter);
	
</script>
</body>
</html><?php
	}
	
	
	
echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>'.$fsitename.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>

<frameset rows="1,79,*" cols="*" framespacing="0" frameborder="NO" border="0">
  <frame src="UntitledFrame-5"> 
<frame name=visit src="index.php?tp=toptask_ptrad&t='.$t.'&id='.$id.'" frameborder=0>
<frame name=visit src="'.$furl.'" frameborder=0>

</frameset>
<noframes><body bgcolor="#FFFFFF" text="#000000">
'.__('ERROR: Frames must be enabled in order to view this site correctly. Please switch to a frames enabled browser (Such as Firefox or Internet Explorer) in order to view the paid emails system.').'
</body></noframes>
</html>';
} else {
	echo __('Error: This page was entered incorrectly.<BR>'); 
}?>