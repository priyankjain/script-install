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
$userip = $_REQUEST['userip'];
?>
<style type="text/css">
<!--
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
.style8 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>

<div align="center"><br>
  <br>
  <strong>Cheater Control Report for <?php echo $userip; ?></strong><br>
  Listing all known accounts for this ip address.
  <BR>
<BR>
</div>
<table width="833" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#EEEEEE">
  <tr>
    <td width="78"><span class="style5">IP</span></td>
	<td width="91"><span class="style5">Last Login IP</span></td>
    <td width="71"><span class="style5">Join Date</span></td>
    <td width="79"><span class="style5">Username</span></td>
    <td width="110"><span class="style5">Name</span></td>
    <td width="92"><span class="style5">Balance</span></td>
    <td width="118"><span class="style5">Account Status</span></td>
    <td width="175"><span class="style5">E-Mail Address </span></td>
  </tr>
  <?php
	
	$query = mysql_query("SELECT * FROM users WHERE userip = ".quote_smart($userip)." OR loginIpAddress = ".quote_smart($userip)." ORDER BY regdate DESC LIMIT 0, 500");
	$count = mysql_num_rows($query);
	if($count > 0) {
	  for($i = 0;$i < $count;$i++) {
		mysql_data_seek($query, $i);
		$arr = mysql_fetch_array($query);
	  ?>
  <tr bgcolor="#FFFFFF">
    <td><span class="style8"><?php echo $arr['userip']; ?></span></td>
    <td><span class="style8"><?php echo $arr['loginIpAddress']; ?></span></td>
    <td><span class="style8"><?php echo $arr['regdate']; ?></span></td>
    <td><span class="style8"><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']; ?></a></span></td>
    <td><span class="style8"><?php echo $arr['fname1']; ?></span></td>
    <td><span class="style8"><?php echo $setupinfo['currency']; ?><?php echo totalEarnings($arr['username']); ?></span></td>
    <td><span class="style8"><?php echo $arr['accstatus']; ?></span></td>
    <td><span class="style8"><?php echo $arr['femail']; ?></span></td>
  </tr>
  <?php
	  }
	} else {
		echo "<tr><td colspan=\"6\">There are currently no accounts for this ip address.</td></tr>";
	}
	?>
</table>


