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
if (!session_id()) session_start();

# ~~~ Flash Source ~~~~~~~~~~ # 
define('flashSrc', '/templates/default/flash/Dracon_CAPTCHA.swf');  // flash source file

# ~~~ Secret Key ~~~~~~~~~~ # 
define('aesKey', 'znwoq8fq0jf2qjve8laper9f');  // 192bit 25 chars 

# ~~~ Anti-Hammering Protection ~~~~~~~~~~ # 
$capTimer = array(0,5,10,30,60,3600);  // delay in seconds

# ~~~ Code Generator ~~~~~~~~~~ # 
function dracon_CodeGen($Length=5,$Code='') { 
  $Chars = "abcdefghijklmnpqrstuvwxyz23456789"; 
  srand((double)microtime()*1000003);
  for ($i=0; $i<$Length; $i++) { 
    $Num = rand(0, strlen($Chars)-1);
    $Code = $Code.substr($Chars, $Num, 1); 
  } 
  return strtoupper($Code); 
}

# ~~~ Code Encryptor ~~~~~~~~~~ # 
function dracon_CodeEnc($secCode) { 
  $encType = 'rijndael-128';
  $aesMode = 'ecb';
  $encIV = "1234567890123450";
  $encObj = mcrypt_module_open($encType, '', $aesMode, '');
  mcrypt_generic_init($encObj, aesKey, $encIV);
  $secEncCode = mcrypt_generic($encObj, $secCode);
  mcrypt_generic_deinit($encObj);
  mcrypt_module_close($encObj);
  return bin2hex($secEncCode);
}

# ~~~ Ajax Code Check ~~~~~~~~~~ # 
if ($_SERVER['QUERY_STRING'] == 'ajax') {
  if (!isset($_SESSION['secCode'])) {
    echo 'hack';  // deleted cookies
    exit;
  }
  if (dracon_CodeEnc(strtoupper($_POST['secCode'])) == $_SESSION['secCode']) {
    $_SESSION['secCode_try'] = false;  // reset anti-hammering
    $_SESSION['secCode_ok'] = true;  // extra for submit check
    echo 'ok';  // correct security code
    exit;
  }
}


# ~~~ Anti-Hammering Check ~~~~~~~~~~ # 
$_SESSION['secCode_time'] = (isset($_SESSION['secCode_time'])) ? $_SESSION['secCode_time'] : time();
if ($_POST) {
  if ($_POST['secCode'] != 'reload') { 
    $_SESSION['secCode_try'] = (isset($_SESSION['secCode_try'])) ? $_SESSION['secCode_try']+1 : '0';
  }
}
if ($_SESSION['secCode_try'] > count($capTimer)-1) $_SESSION['secCode_try'] = count($capTimer)-1;

$secTimer = $_SESSION['secCode_time'] + $capTimer[$_SESSION['secCode_try']] - time();  // time left
if ($secTimer > 0) {
  echo 'timer:'.$secTimer;
  exit;
}



# ~~~ Flash Source ~~~~~~~~~~ # 
define('flashSrc', '/templates/default/flash/Dracon_CAPTCHA.swf');  // flash source file

# ~~~ Secret Key ~~~~~~~~~~ # 
define('aesKey', 'znwoq8fq0jf2qjve8laper9f');  // 192bit 25 chars 

# ~~~ Anti-Hammering Protection ~~~~~~~~~~ # 
$capTimer = array(0,5,10,30,60,3600);  // delay in seconds

# ~~~ Code Generator ~~~~~~~~~~ # 
function dracon_CodeGen($Length=5,$Code='') { 
  $Chars = "abcdefghijklmnpqrstuvwxyz23456789"; 
  srand((double)microtime()*1000003);
  for ($i=0; $i<$Length; $i++) { 
    $Num = rand(0, strlen($Chars)-1);
    $Code = $Code.substr($Chars, $Num, 1); 
  } 
  return strtoupper($Code); 
}

# ~~~ Code Encryptor ~~~~~~~~~~ # 
function dracon_CodeEnc($secCode) { 
  $encType = 'rijndael-128';
  $aesMode = 'ecb';
  $encIV = "1234567890123450";
  $encObj = mcrypt_module_open($encType, '', $aesMode, '');
  mcrypt_generic_init($encObj, aesKey, $encIV);
  $secEncCode = mcrypt_generic($encObj, $secCode);
  mcrypt_generic_deinit($encObj);
  mcrypt_module_close($encObj);
  return bin2hex($secEncCode);
}

# ~~~ Flash Display ~~~~~~~~~~ # 
$secCode = dracon_CodeGen(5);
$secEncCode = dracon_CodeEnc($secCode);
$_SESSION['secCode'] = $secEncCode;
$_SESSION['secCode_time'] = time();  // reset time when flash is shown
?>
<div id="dracon_captcha" style="width:150px; height:50px; border:1px solid #990000">
  <object data="<?php echo flashSrc; ?>?secEncCode=<?php echo $secEncCode; ?>" width="150" height="50" type="application/x-shockwave-flash">
    <param name="movie" value="<?php echo flashSrc; ?>?secEncCode=<?php echo $secEncCode; ?>" />
    <param name="quality" value="high" />
    <param name="menu" value="false" />
    <param name="swliveconnect" value="false">
    <param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" />
  </object>
</div>