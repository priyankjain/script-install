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
 loginCheck(); ?> <?

 if($action=='sup')

 {

 $mcontent="Support request from $name. \n myPTRsite URL: $url  \n e-mail: $email \n $note";

 @mail('support@ptcshop.com', 'Support request', $mcontent);

 echo"YOUR SUPPORT REQUEST HAS BEEN SENT. WE CONTACT YOU SUN...";

 }

 ?>

<form name="form1" method="post" action="">

  <table border="0" width="100%" align="center">

    <tr bgcolor="#006666"> 

      <td colspan="2"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">myPTRsite 

        SUPPORT SERVICE </font></b></td>

    </tr>

    <tr> 

      <td width="17%">Your name:</td>

      <td width="83%">

        <input type="text" name="name" size="47">

      </td>

    </tr>

    <tr> 

      <td width="17%">Your e-mail:</td>

      <td width="83%">

        <input type="text" name="email" size="47">

      </td>

    </tr>

    <tr> 

      <td width="17%">Your myPTRsite URL:</td>

      <td width="83%"> 

        <input type="text" name="url" size="47">

      </td>

    </tr>

    <tr> 

      <td width="17%">Describe your problem:</td>

      <td width="83%"> 

        <textarea name="note" rows="8" cols="40"></textarea>

        <input type="hidden" name="action" value="sup">

      </td>

    </tr>

    <tr> 

      <td colspan="2">

        <input type="submit" name="Submit" value="Submit">

      </td>

    </tr>

  </table>

</form>



