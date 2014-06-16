<?php
/****************************************************************************\
* Dracon Flash CAPTCHA - Test Form                                           *
******************************************************************************
* Version: 2.1                                                               *
* Author: Searcher <searcher@dracon.biz>                                     *
* License: http://www.dracon.biz/license.php                                 *
\****************************************************************************/          

# ~~~ CAPTCHA Sessions ~~~~~~~~~~ # 
//session_name('dracon_captcha');
session_start();

# ~~~ CAPTCHA Form Submit ~~~~~~~~~~ # 
if ($_SERVER['QUERY_STRING'] == 'final') {
  if ($_SESSION['secCode_ok'] === true) {
    $_SESSION['secCode_ok'] = false;  // reset old results
    $Body     = '<body bgcolor="#ccc"><span style="font-family:tahoma; font-size:11px">'.$_POST['message'];
    $Subject  = 'Dracon CATPCHA Test Form';
    $Headers  = 'MIME-Version: 1.0\r\n';
    $Headers .= 'Content-type: text/html; charset=Utf-8\r\n';
    $Headers .= 'From: '.$_POST['name'].' <'.$_POST['email'].'>\r\n';
    $To       = 'ocnod1234@yahoo.com';
    mail($To, $Subject, $Body, $Headers);
    echo '<span class="final_txt">Thank you <b>'.$_POST['name'].'</b>!<br /><br />';
    echo 'Your message has been sent.</span>';
    exit;
  }
  else {
    echo '<span class="sec_code">Error, hacking attempt detected!</span>';
    exit;  
  }
}

# ~~~ System Reset ~~~~~~~~~~ # 
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dracon CAPTCHA v2.1</title>

<!--[if IE]><link rel="stylesheet" type="text/css" href="/templates/default/css/Dracon_IE.css" /><!--<[endif]-->
<!--[if !IE]><link rel="stylesheet" type="text/css" href="/templates/default/css/Dracon.css" /><!--<![endif]-->
<script src="/templates/default/js/FxCore.js" type="text/javascript"></script>
<script src="/templates/default/js/Effects.js" type="text/javascript"></script>
</head>

<body>

<br /><br />
<div id="dracon_captcha_test" class="hidden" align="center">

  <div class="title_bg">
    <?php picOverFx('title','/templates/default/images/title.png',420,40); ?>
  </div>


  <div class="main_table" style="border-top:1px solid #333">
  
    <div id="loader" align="center" class="hidden absolute">
      <img src="/templates/default/images/loading.gif">
    </div>
    
    <div id="counter" align="center" class="hidden absolute counter">
      <span></span> s
    </div>    
    
   <div id="captcha" class="hidden absolute">
      <?php include 'CodeGen.php'; ?>
    </div>

    <div id="captcha_sub" class="hidden">
      <div id="captcha_nfo1" class="sec_code hidden absolute">
        Mouse over to see a hidden char.
      </div>
      <br /><input id="code" class="input_captcha" maxlength="5" />
      <div id="captcha_nfo2" class="sec_code hidden absolute">
        &nbsp;&nbsp;Please enter the security code!
      </div>
    </div>
    
    <form id="main_form" method="post" style="margin:0">
    <table cellpadding="3" cellspacing="0">
      <tr><td height="20"></td></tr>
      <tr>  
        <td width="80">&nbsp;</td>
        <td class="label" valign="top">Your name</td>
        <td id="check_name" width="30"></td>
        <td><input id="name" class="input_field input_text" /></td>
        <td>&nbsp;</td>
      </tr>  
      <tr>  
        <td></td>
        <td class="label" valign="top">Your e-mail</td>
        <td id="check_email"></td>
        <td><input id="email" class="input_field input_text" /></td>
        <td></td>
      </tr>  
      <tr>  
        <td></td>
        <td class="label" valign="top">Your message</td>
        <td id="check_message" valign="top" style="padding-top:12px"></td>
        <td><textarea id="message" class="input_field input_text input_area"></textarea></td>
        <td></td>
      </tr>  
    </table>
    </form>

  </div>

  <div class="button_bg">
    <div style="float:left; margin-left:110px">
      <?php picOverFx('submit','/templates/default/images/submit.png',145,40); ?>
    </div>
    <div style="float:right; margin-right:100px">
      <?php picOverFx('reset','/templates/default/images/reset.png',145,40); ?>
    </div>
  </div>
  
</div>

</body>
</html>