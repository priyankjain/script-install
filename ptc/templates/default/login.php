<?php
if(!isset($_SESSION)) session_start();
$_SESSION['randomVerification'] = rand(1000,9999);
?>
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
<div class="body1"><div class="body">
  <div class="body_resize">
	  <div <?php if($sideBarLeft == 0) echo 'class="left"'; else echo 'class="right"'; ?>>
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
<h1><?php echo __('LOGIN TO YOUR EARNINGS AREA BELOW'); ?></h1>
  
<div align="center">
        <form name="enter" method="post" action="index.php">
                      <br>
                     <?php if($error != '') echo $error; ?> 
                      
          <table width="429" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr><td colspan="2">
                        <?php echo __('To login enter your username, usually your website extension, and your password below.'); ?>
                        </td></tr>
                        <tr><td colspan="2"><?php
                        if($loginError === TRUE) {
							?>
                            <p>  <span style="color: #FF0000;font-weight: bold;"><?php echo __('ERROR'); ?>: </span><?php echo __('Your login details do not match our records. Please check your login details and try again.'); ?>
                            </p>
                            <?php
						}
						?></td>
                        </tr>
                        <tr>
                          <td width="140">
                          &nbsp;<?php echo __('Username'); ?>:</td>
                          <td width="269">
                          <input type="text" name="username" value="">                          </td>
            </tr>
                        <tr>
                          <td>
                            &nbsp;<?php echo __('Password'); ?>:</td>
                          <td>
                            <input type="password" name="password" value="">                          </td>
                        </tr>
                        <?php if($setupinfo['enableSecondaryPassword'] == '1') { ?>
                        <tr>
                          <td>
                            &nbsp;<?php echo __('Secondary Password'); ?>:</td>
                          <td>
                            <input type="password" name="secondaryPassword" value="">  (<?php echo __("Only if you have one set, otherwise leave blank"); ?>)                        </td>
                        </tr>
                        <?php } ?>
                        
                        <?php if($useDraconCaptcha === TRUE) { ?>
                        <?php
						$_SESSION['secCode_try'] = 0;  // restart captcha counter if this page is reloaded
						$_SESSION['secCode_ok'] = false;  // reset old results
						
						# ~~~ FadeFX Mouseover ~~~~~~~~~~ # 
						function picOverFx($picId,$picSrc,$picW,$picH,$Link=false) {
						  $picFx = '<div class="fade" id="'.$picId.'" style="background:url('.$picSrc.'); width:'.$picW.'px; height:'.$picH.'px">';
						  $picOver = explode(".", $picSrc); 
						  $picOver = $picOver[0].'_o.'.$picOver[1];
						  $picFx .= '<img id="'.$picId.'_o" class="hidden" src="'.$picOver.'"></div>';
						  echo $picFx;
						}
						?><tr><td>  <div class="title_bg">
    <?php picOverFx('title','/templates/default/images/title.png',420,40); ?>
  </div>
  
    <div id="loader" align="center" class="hidden absolute">
      <img src="gfx/loading.gif">
    </div>
    
    
    <div id="counter" align="center" class="hidden absolute counter">
      <span></span> s
    </div>    
    
   <div id="captcha" class="hidden absolute">
      <?php include 'CodeGen.php'; ?>
    </div>

    
    
  </td><td>
  
  <div id="captcha_sub" class="hidden">
      <div id="captcha_nfo1" class="sec_code hidden absolute">
        Mouse over to see a hidden char.
      </div>
      <br /><input id="code" class="input_captcha" maxlength="5" />
      <div id="captcha_nfo2" class="sec_code hidden absolute">
        &nbsp;&nbsp;Please enter the security code!
      </div>
    </div>
    
  <div class="button_bg">
    <div style="float:left; margin-left:110px">
      <?php picOverFx('submit','gfx/submit.png',145,40); ?>
    </div>
    <div style="float:right; margin-right:100px">
      <?php picOverFx('reset','gfx/reset.png',145,40); ?>
    </div>
  </div>
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
                          <td colspan="2">
                            <div align="center">
                              <p>
                                <input <?php if($_SESSION['lang'] == 'en') { ?>type="image" src="<?php echo $templateFolder; ?>images/login_now.gif"<?php } else echo 'type="Submit"'; ?> name="Submit" id="Submit" value=" <?php echo __('Login Now'); ?> ">
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
                      <input type="hidden" name="action" value="Login to my account">
                      <input type="hidden" name="tp" value="member">
        </form>
                    </div>
<?php echo $pageFooter; ?>