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
if($act=='change'){
	// Checking form-data
	if(!$_REQUEST['email']) {
		echo"<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT e-mail! Please click 'BACK' button and try again...";
	} else if(!$_REQUEST['fname']) {
		echo"<b><font size=4 color= red>ERROR!</font></b><P>INCORRECT first name! Please click 'BACK' button and try again...";
	} else {
		$noerrors=1;
	}
	
	if($noerrors) {
		if($demoMode === TRUE) {
			echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
		} else {		
			$ch_base=mysql_query("
			UPDATE
				admins
			SET
				femail=".quote_smart($_REQUEST['email']).",
				fname=".quote_smart($_REQUEST['fname'])."
			WHERE
				username=".quote_smart($_SESSION['admin'])."
			") or die(mysql_error());
				if(mysql_affected_rows()) echo"<b>Your data has been changed successfully...</b>";
			if($_REQUEST['password'] == $_REQUEST['confirmpassword']) {
				mysql_query("UPDATE admins SET fpassword = ".quote_smart($_REQUEST['password'])." WHERE
				username=".quote_smart($_SESSION['admin'])."");
			}
		}
	}

}
	$sql=mysql_query("SELECT * FROM admins WHERE username=".quote_smart($_SESSION['admin'])."");
	$arr=mysql_fetch_array($sql);
	extract($arr);
?><h3>YOUR ADMINISTRATION ACCOUNT</h3>
<form name="changeprofile" method="post" action="index.php">
  <table border="0" width=100% cellspacing="0" cellpadding="5" align="center" class="fullwidth">
                    <tr>
                      <td width="21%" valign="top">                    <input type="hidden" name="tp" value="editinfo" />
                      <input type="hidden" name="act" value="change" />
Username:</td>
                      <td width="79%" valign="top">
                      <?php echo "<b>".$_SESSION['admin']."</b>"; ?></td>
                  </tr>
                  <tr>
                      <td valign="top"> Account Type:</td>
                      <td valign="top">
                      <?php 
					  if($privelages == 'cs') {
					  	echo "Customer Service";
					  } else if($privelages == 'admin') {
					  	echo "Super Admin";
					  } else {
					  	echo "Standard Account.";
					  }
					   ?></td>
                  </tr>
					<tr>
                      <td>E-Mail:</td>
                      <td>
                        <input name="email" type="text" value="<?php echo "$femail"?>">                      </td>
                    </tr>
                    <tr>
                      <td>Full  Name:</td>
                      <td>
                        <input type="text" name="fname" value="<?php echo "$fname"?>">                      </td>
                    </tr>
                    <tr>
                      <td>Password:</td>
                      <td>
                        <input type="password" name="password" value="">                      </td>
                    </tr>
                    <tr>
                      <td>Confirm Password:</td>
                      <td>
                        <input type="password" name="confirmpassword" value="">                      </td>
                    </tr>
                  
                    <tr valign="top">
                      <td colspan=2 align=center>
                        <br>
                        <input type="submit" value="         Update my profile!        " name="submit">                 </td>
                    </tr>
  </table>
</form>