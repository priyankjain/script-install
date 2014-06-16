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
$setupinfo = getArray("SELECT * FROM setupinfo LIMIT 1");
if($_REQUEST['act'] == 'addNow') {
	if(getValue("SELECT COUNT(fid) FROM users WHERE username = ".quote_smart($_REQUEST['username'])) > 0) {
		$referrals = $_REQUEST['referrals'];
		if(!is_numeric($referrals)) {
			displayError('ERROR: Referrals must be numeric. No comma for thousands.');
		} else if($referrals > orphanCount()) {
			displayError('ERROR: Referrals is greater than the number of current available referrals. Please enter '.orphanCount().' or less.');
		} else {
			$packRefs = $referrals;
			$username = $_REQUEST['username'];
			$sql = mysql_query("UPDATE users SET frefer=".quote_smart($username)." WHERE frefer = '' AND username != ".quote_smart($username)." LIMIT $packRefs");
			$receivedRefs = mysql_affected_rows();
			displaySuccess("User has received ".$receivedRefs);
		}
	} else {
		displayError('ERROR: Username not found !');
	}
}
?>
<h2>ASSIGN REFERRALS TO AN ACCOUNT</h2>
<form name="form1" method="post" action="index.php"><br>
<?php echo "There are currently <span style=\"font-size: 24px; color: #009900;\">".orphanCount()."</span> available referrals.<BR><BR>"; ?>
<input type="hidden" name="tp" value="assignreferrals"><input type="hidden" name="act" value="addNow">
Username: <input type="text" name="username" value="<?php echo $_REQUEST['username']; ?>">
<br />
<br>

Number of Referrals to add: 
<input name="referrals" type="text" value="<?php echo $_REQUEST['referrals']; ?>" size="6">
<br />
<br>
<input type="submit" name="Submit" value="Add Now"><br>
NOTE: THIS CAN NOT BE UN-DONE AND IS A PERMANENT TRANSFER OF SPONSORSHIP
</form>
