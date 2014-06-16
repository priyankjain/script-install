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
<h1><?php echo __('Login to Your Earnings Area Below'); ?></h1>
  
<div align="center">
          <form name="enter" method="post" action="index.php" id="main_form">
                      <br>
                     <?php if($error != '') echo $error; ?>
                     <table width="429" border="0" align="center" cellpadding="5" cellspacing="0">
                       <tr>
                         <td colspan="2"><?php echo __('To login enter your username, usually your website extension, and your password below.'); ?> </td>
                       </tr>
                       <tr>
                         <td colspan="2"><?php
                        if($loginError === TRUE) {
							?>
                             <p> <span style="color: #FF0000;font-weight: bold;"><?php echo __('ERROR'); ?>: </span><?php echo __('Your login details do not match our records. Please check your login details and try again.'); ?> </p>
                           <?php
						}
						?></td>
                       </tr>
                       <tr>
                         <td width="140">&nbsp;<?php echo __('Username'); ?>:</td>
                         <td width="269"><input type="text" name="username" value="" />
                         </td>
                       </tr>
                       <tr>
                         <td>&nbsp;<?php echo __('Password'); ?>:</td>
                         <td><input type="password" name="password" value="" />
                         </td>
                       </tr>
                       <tr>
                         <td>&nbsp;<?php echo __('Secondary Password'); ?>:</td>
                         <td><input type="password" name="secondaryPassword" value="" />
                           (<?php echo __("Only if you have one set, otherwise leave blank"); ?>) </td>
                       </tr>
                        <?php if($useDraconCaptcha === TRUE) { ?>
                        <?php
						
						$_SESSION['secCode_try'] = 0;  // restart captcha counter if this page is reloaded
						$_SESSION['secCode_ok'] = false;  // reset old results
						
						?><tr><td colspan="2"> 
  
      <?php
	  
	  
# ~~~ Flash Source ~~~~~~~~~~ # 
define('flashSrc', '/templates/default/flash/Dracon_CAPTCHA.swf');  // flash source file

# ~~~ Secret Key ~~~~~~~~~~ # 
define('aesKey', 'znwoq8fq0jf2qjve8laper9f');  // 192bit 25 chars 

# ~~~ Anti-Hammering Protection ~~~~~~~~~~ # 
$capTimer = array(0,5,10,30,60,3600);  // delay in seconds

# ~~~ Flash Display ~~~~~~~~~~ # 
$secCode = dracon_CodeGen(5);
$secEncCode = dracon_CodeEnc($secCode);
$_SESSION['secCode'] = $secEncCode;
$_SESSION['secCode_time'] = time();  // reset time when flash is shown
?>
<div id="dracon_captcha" style="width:150px; height:50px; border:1px solid #990000; margin-left: auto; margin-right: auto;">
  <object data="<?php echo flashSrc; ?>?secEncCode=<?php echo $secEncCode; ?>" width="150" height="50" type="application/x-shockwave-flash">
    <param name="movie" value="<?php echo flashSrc; ?>?secEncCode=<?php echo $secEncCode; ?>" />
    <param name="quality" value="high" />
    <param name="menu" value="false" />
    <param name="swliveconnect" value="false">
    <param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" />
  </object>
</div><br />

	  <div align="center">
Enter the Captcha Code Above:<br />
<input id="code" class="input_captcha" name="input_captcha" maxlength="5" /></div>
     
  </td></tr><?php
						
						} else { //USE DEFAULT CAPTCHA
						?>
                        <tr>
                          <td>                            <img src="<?php echo $templateFolder; ?>captcha_img.php?np=1" width="90" height="40" border="2"/></td>
                          <td>
                            <input name="randomValidationCode" type="text" value="" size="8">
                            (<?php echo __("Enter verification code"); ?>)<br />
 <?php echo __('Enter the numbers from the image.'); ?>                          </td>
                        </tr>
                        <?php
						}
						?>
                       <tr>
                         <td colspan="2"><div align="center">
                             <p>
                               <?php
                              // if($useDraconCaptcha !== TRUE) {
								   ?><input type="image" src="<?php echo $templateFolder; ?>images/login_now.gif" name="submit" id="submit" value=" <?php echo __('Login Now'); ?> " /><?php
							   /*} else {
								   ?><img src="<?php echo $templateFolder; ?>images/login_now.gif" name="submit" id="submit" onClick="form_submit();" /><?php
							   }*/
							   ?>
                             </p>
                           <p><?php echo __('Forgotten your login or password?'); ?> <a href="index.php?tp=forgotpass"><?php echo __('Click Here'); ?></a> </p>
                         </div></td>
                       </tr>
                     </table>
            <table width="315" height="44" align="center">
            <tr>
            <td>
            <p align="center"><br />
             <br />
             <br />
             <?php echo __('Not already a FREE member?<br />
             Take 3 Minutes to Sign Up and get started today!'); ?><br />
            <a href="index.php?tp=signup">
            <img src="<?php echo $templateFolder; ?>images/joinButton.png" alt="picture" width="314" height="43" border="0"/>            </a>            </p>
            </td></tr></table>
                      <input type="hidden" name="action" value="<?php echo ('Login to my account'); ?>">
                      <input type="hidden" name="tp" value="member">
          </form>
                    </div>
<?php echo $pageFooter; ?>