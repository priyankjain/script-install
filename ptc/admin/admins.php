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
 loginCheck();
$privelage = getValue("SELECT privelages FROM admins WHERE username = ".quote_smart($_SESSION['admin'])."");
if($privelage != 'superadmin') exit("INVALID PERMISSIONS TO VIEW THIS PAGE.");
?><?
if(!$count) $count=10;
?><h2>Admin Accounts</h2>
<div class="hastable_disabled">
    <?php
if($_REQUEST['act'] == 'suspendNow') {
	if($demoMode === TRUE) {
		echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
	} else {
		mysql_query("UPDATE admins SET active = '0' WHERE username = ".quote_smart($_REQUEST['uid'])."");
	}
	displaySuccess("This admin account (".$_REQUEST['uid'].") has been suspended.");
	$_REQUEST['act'] = '';
}
if($_REQUEST['act'] == 'suspend') {
	if(getValue("SELECT COUNT(id) FROM admins WHERE active = '1'") == 0) {
		?>
  ERROR: You can not suspend the last active admin account. Activate at least 1 other account to suspend this account.<BR>
  <?php
	} else {
		?>
  Are you sure you wish to suspend <?php echo $_REQUEST['uid']; ?>?<BR>
  <BR>
  <a href="index.php?tp=admins&uid=<?php echo $_REQUEST['uid']; ?>&act=suspendNow">Yes</a> - Suspend Now<BR>
  <BR>
  <a href="index.php?tp=admins">No</a> - I changed my mind.
  <?php
	}
}
if($_REQUEST['act'] == 'unsuspendNow') {
	if($demoMode === TRUE) {
		echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
	} else {
		mysql_query("UPDATE admins SET active = '1' WHERE username = ".quote_smart($_REQUEST['uid'])."");
	}
	displaySuccess("This admin account (".$_REQUEST['uid'].") has been un suspended.");
	$_REQUEST['act'] = '';
}
if($_REQUEST['act'] == 'unsuspend') {
	?>
  Are you sure you wish to activate <?php echo $_REQUEST['uid']; ?>?<BR>
  <BR>
  <a href="index.php?tp=admins&uid=<?php echo $_REQUEST['uid']; ?>&act=unsuspendNow">Yes</a> - Activate Now<BR>
  <BR>
  <a href="index.php?tp=admins">No</a> - I changed my mind.
  <?php
}
if($_REQUEST['act'] == 'addNow') {
	if($_REQUEST['adminUsername'] == '' || $_REQUEST['adminPassword'] == '' || $_REQUEST['adminEmail'] == '') {
		displayError("Invalid Data Entered, please enter all of the information.");
		$_REQUEST['act'] = 'addNew';
	} else {
		if($demoMode === TRUE) {
			echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
		} else {
			mysql_query("INSERT INTO admins (id, username, fname, femail, fpassword, active, `privelages`) VALUES (
			'', ".quote_smart($_REQUEST['adminUsername']).", ".quote_smart($_REQUEST['adminName']).", ".quote_smart($_REQUEST['adminEmail']).", ".quote_smart($_REQUEST['adminPassword']).", '1', ".quote_smart($_REQUEST['privs'])."
			)");
		}
		
		$ptrurl = prepURL($ptrurl);
		
		$message = 'Dear '.$_REQUEST['adminName'].',
You have just been added as an administrative user for '.$ptrname.'.

Your admin panel can be viewed at 
'.$ptrurl.'admin
Login: '.$_REQUEST['adminUsername'].'
Password: '.$_REQUEST['adminPassword'].'

If you have questions, please contact the site administrator at '.$adminemail.'

NOTICE: Keep a copy of this e-mail, the only way to retreive your information is to request it from your site administrator.';
		
		$subject = $ptrname.' Site Administrator Access Granted';
		
		$headers="From: \"".$ptrname."\" <".$adminemail.">\n";
		
		$mail = mail($_REQUEST['adminEmail'], $subject, $message, $headers );
		
		displaySuccess("Your admin account has been created successfully.");
		$_REQUEST['act'] = '';
	}
}
if($_REQUEST['act'] == 'addNew') {
	?>
  Add new admin account by filling out the information below.<BR>
  <form name="form1" method="post" action="index.php">
  <input type="hidden" name="tp" value="admins">
  <input type="hidden" name="act" value="addNow">
  Last Login IP Address<table width="449" cellpadding="5" cellspacing="0" class="fullwidth">
  <tr><td width="116">Username</td><td width="311"><input type="text" name="adminUsername" value=""></td></tr>
  <tr><td>Name</td><td><input type="text" name="adminName" value=""></td></tr>
  <tr><td>Email Address</td><td><input type="text" name="adminEmail" value=""></td></tr>
  <tr><td>Password</td><td><input type="password" name="adminPassword" value=""></td></tr>
  <tr>
    <td>Access Privelages</td>
    <td><select name="privs" id="privs">
    	<option value="superadmin">
        Super Admin (Modify / Add Admin Accounts)
        </option>
        <option value="cs">
        Simple Admin (Administrate Website)
        </option>
    </select>
    </td>
  </tr>
  </table>
  <input type="submit" name="Submit" value="Add New Admin!">
  </form>
  <BR>
    <BR>
  <?php
}
if($_REQUEST['act'] == 'editNow') {
	if(getValue("SELECT COUNT(id) FROM admins WHERE username = ".quote_smart($_REQUEST['uid'])."") > 0) {
		if($demoMode === TRUE) {
			echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
		} else {
			mysql_query("UPDATE admins SET username = ".quote_smart($_REQUEST['adminUsername']).", `privelages` = ".quote_smart($_REQUEST['privs']).", `fpassword` = ".quote_smart($_REQUEST['adminPassword']).", femail = ".quote_smart($_REQUEST['adminEmail']).", fname = ".quote_smart($_REQUEST['adminName'])." WHERE username = ".quote_smart($_REQUEST['uid'])."");
		}
		displaySuccess("Admin Updated Successfully.");
		$_REQUEST['act'] = '';
	} else {
		displayError("Admin Account Not Found!");
		$_REQUEST['act'] = '';
	}
}
if($_REQUEST['act'] == 'edit') {
	$admin = getArray("SELECT * FROM admins WHERE username = ".quote_smart($_REQUEST['uid'])."");
	?>
  Edit admin account by filling out the information below.
  <form name="form1" method="post" action="index.php">
  <input type="hidden" name="tp" value="admins">
  <input type="hidden" name="act" value="editNow">
  <input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']; ?>">
  Last Login IP Address<table width="468" cellpadding="5" cellspacing="0" class="fullwidth">
  <tr><td width="119">Username</td><td width="327"><input type="text" name="adminUsername" value="<?php echo $admin['username']; ?>"></td></tr>
  <tr><td>Name</td><td><input type="text" name="adminName" value="<?php echo $admin['fname']; ?>"></td></tr>
  <tr><td>Email Address</td><td><input type="text" name="adminEmail" value="<?php echo $admin['femail']; ?>"></td></tr>
  <tr><td>Password</td><td><input type="password" name="adminPassword" value="<?php echo $admin['fpassword']; ?>"></td></tr>
  <tr>
    <td>Access Privelages</td>
    <td><select name="privs" id="privs">
        <option value="superadmin" <?php if($admin['privelages'] == 'superadmin') echo "selected=\"selected\""; ?>> Super Admin (Modify / Add Admin Accounts) </option>
        <option value="cs" <?php if($admin['privelages'] == 'cs') echo "selected=\"selected\""; ?>> Simple Admin (Administrate Website) </option>
      </select>    </td>
  </tr>
  </table>
  <input type="submit" name="Submit" value="Update!">
  </form>
  <BR>
    <BR>
    <?php
}
if($_REQUEST['act'] == '') {
?>
    <a href="index.php?tp=admins&act=addNew">Create New Admin Account</a><br>
    <br>
  <table border="0" cellpadding="0" cellspacing="0" class="fullwidth">
<thead>
        <tr>
          <td>Name</td>
          <td>Username</td>
          <td>Last Login IP</td>
          <td>Email Address</td>
          <td><div align="right">Options</div></td>
        </tr>
</thead><tbody>
        <?php

		$sql=mysql_query("SELECT * FROM admins ORDER BY id DESC");

	$rows=mysql_num_rows($sql);
	if(!$start) $start=0;
	$end=$start+$count;
	if($rows<=$end) $end=$rows;

	for($i=$start;$i<$end;$i++) {
	mysql_data_seek($sql,$i);
	$arr=mysql_fetch_array($sql);
	extract($arr);

	?><tr onMouseOver="this.bgColor='#ECECEC'" onMouseout="this.bgColor='#FFFFFF'">
        <td><?php echo $fname; ?></td>
		<td><?php echo $username; ?></td>
        <td><?php echo $loginIpAddress; ?></td>
        <td><?php echo $femail; ?></td>
        <td><div align="right"> <?php if($active == '1') { ?><a href="index.php?tp=admins&uid=<?php echo $username; ?>&act=suspend" target="_self">Suspend</a><?php } else { ?><a href="index.php?tp=admins&uid=<?php echo $username; ?>&act=unsuspend" target="_self">Activate</a><?php } ?> <a href="index.php?tp=admins&uid=<?php echo $username; ?>&act=edit" target="_self">Modify</a></div></td>
        </tr><?php
	}



	?><tr><td align=center colspan=11><?php



	if($start!=0) {$start=$start-$count; $fl=1; echo"<a href='index.php?tp=admins&start=$start'>Previous $count</a> | ";}



	if($end!=$rows) {if(!$fl) $start=$start+$count; else $start=$start+$count+$count; echo"<a href='index.php?tp=admins&start=$start'>Next $count</a>";}

?>

	</td></tr>
  <tr>

    <td colspan="12">TOTAL ADMINS: <?php echo  $rows?></td>
  </tr>
</tbody>
</table>
 <?php
}
?>
</div>
