<?php
if(!isset($_SESSION)) session_start();
$_SESSION['randomVerification'] = rand(1000,9999);
?>
<?php echo $pageHeader; ?>
 <style type="text/css">
<!--
.box {
	border: thin solid #3399FF;
}
.white14pxBoldArial {font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #FFFFFF;
	font-size: 14px;
}
.arial12pxReg {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style2 {
	font-size: 12px;
	font-weight: bold;
}
.style3 {
	font-size: 12;
	font-weight: bold;
}
.style4 {
	color: #006600;
	font-weight: bold;
}
-->
</style>
<h1><?php echo __('Reset your password'); ?></h1>
<p><?php echo __('You\'re almost done, to complete the password reset, enter your email address, new password and the "Password Reset Code" we sent to you after you filled out the forgotten password form.'); ?></p>
<div align="center">
          <form name="enter" method="post" action="index.php">
                      <br>
                      <?php if($error != '') echo $error; ?> 
                      
            <table width="429" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td width="125"> &nbsp;<?php echo __('E-Mail Address'); ?>:</td>
                          <td width="284">
                          <input type="text" name="emailAddress" value="<?php echo $_REQUEST['emailAddress']; ?>">                          </td>
                        </tr> <tr>
                          <td width="125"> &nbsp;<?php echo __('Password Reset Code'); ?>:</td>
                          <td width="284">
                          <input type="text" name="rpc" value="<?php echo $_REQUEST['rpc']; ?>">                          </td>
                        </tr><tr>
                          <td width="125"> &nbsp;<?php echo __('Enter Your New Password'); ?>:</td>
                          <td width="284">
                          <input type="text" name="newPass" value="">                          </td>
                        </tr><tr>
                          <td width="125"> &nbsp;<?php echo __('Confirm Your New Password'); ?>:</td>
                          <td width="284">
                          <input type="text" name="confirmNewPass" value="">                          </td>
                        </tr><?php if($setupinfo['enableSecondaryPassword'] == '1') { ?><tr>
                          <td width="125"> &nbsp;<?php echo __('Enter Your New <b>Secondary</b> Password'); ?>:</td>
                          <td width="284">
                          <input type="text" name="newSecondaryPass" value="">                          </td>
                        </tr><tr>
                          <td width="125"> &nbsp;<?php echo __('Confirm Your New <b>Secondary</b> Password'); ?>:</td>
                          <td width="284">
                          <input type="text" name="confirmNewSecondaryPass" value="">                          </td>
                        </tr><?php } ?>
                        <tr>
                          <td>                            <img src="<?php echo $templateFolder; ?>captcha_img.php?np=1" width="90" height="40" border="2"/></td>
                          <td>
                            <input name="randomValidationCode" type="text" value="" size="8">
                            <br />
 <?php echo __('Enter the numbers from the image.'); ?>                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <div align="center">
                              <p>
                                <input name="Submit" type="submit" value=" Update Password ">
                              </p>
                              <p><?php echo __('Want to login?'); ?> <a href="index.php?tp=member"><?php echo __('Click Here'); ?></a> </p>
                            </div></td>
                        </tr>
            </table>
                      
                      
                      
            <table width="315" height="44" align="center">
            <tr>
            <td>
            <p align="center"><br />
             <br />
             <br />
             <?php echo __('Not already a  member? <br>
             Take 3 Minutes to Sign Up and get started today!'); ?><br />
            <a href="index.php?tp=signup">
            <img src="<?php echo $templateFolder; ?>images/joinButton.png" alt="picture" width="314" height="43" border="0"/>
            </a>
            </p>
            </td></tr></table>
                      <input type="hidden" name="action" value="update password">
                      <input type="hidden" name="tp" value="member">
          </form>
                    </div>

<?php echo $pageFooter; ?>