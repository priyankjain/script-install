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

<table class="fullwidth" border="0">
<thead>
  <tr> 

    <td colspan="2">CHEATERS CONTROL</td>

  </tr>
</thead><tbody>
  <tr> 

    <td width="49%"> 

      <form name="form1" method="post" action="">

        <table width="100%" border="0">

          <tr> 

            <td>Check member IP-address: 

              <input type="text" name="checkip">

              <input type="hidden" name="tp" value="chitcontrol">

              <input type="hidden" name="act" value="checkip">

            </td>

          </tr>

          <tr> 

            <td>

              <input type="submit" name="Submit" value="CHECK!">

            </td>

          </tr>

        </table>

      </form>

    </td>

    <td width="51%">

      <form name="form1" method="post" action="">

        <table width="100%" border="0">

          <tr> 

            <td>Check member email: 

              <input type="text" name="checkemail">

              <input type="hidden" name="tp" value="chitcontrol">

              <input type="hidden" name="act" value="checkemail">

            </td>

          </tr>

          <tr> 

            <td> 

              <input type="submit" name="Submit2" value="CHECK!">

            </td>

          </tr>

        </table>

      </form>

    </td>

  </tr>
</tbody>
</table>

<br>

<?

if($act=='checkip')include('ip_information.php');

if($act=='checkemail')include('email_information.php');

?>



