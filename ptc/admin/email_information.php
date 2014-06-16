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
 loginCheck(); ?>

<p><b>E-mail information:</b></p>

<p>

<?

$checkemail=explode("@", $checkemail);

$emailhost=$checkemail[1];

if(!getmxrr($emailhost, $mxhostsarr))

echo "THIS E_MAIL NOT EXIST!!!";

else

echo"Email is delivered via: ";

foreach($mxhostsarr as $mx);

echo"$mx";

?>

</p>



