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

if ($tp == 'features' && $sp == 'sidebardisable') {

	$sql = mysql_query("UPDATE setupinfo SET displaytopearners=".quote_smart($_REQUEST['displaytopearners']).", sideBarLeft=".quote_smart(1).", displaylinks=".quote_smart($displaylinks1).", displaystats=".quote_smart($displaystats1).", displaytop5=".quote_smart($displaytop51).", displayfbanner=".quote_smart($displayfbanner1).", displaypoll=".quote_smart($displaypoll1).", displayfad=".quote_smart($displayfad1).", displaybenefits=".quote_smart($displaybenefits1)."")or die(mysql_error());

	echo "<div align=\"center\"><strong><font color=\"#FF0000\" size=\"4\" face=\"Arial, Helvetica, sans-serif\">SideBar Features Updated</font></strong><br><br></div>";

}

$sq=mysql_query('SELECT * FROM setupinfo');

$ar=mysql_fetch_array($sq); 

?> 

<style type="text/css">

<!--

.unnamed1 {

	font-family: Arial, Helvetica, sans-serif;

	font-size: 12px;

	color: #0000FF;

}

.style3 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }

-->

</style>



<div align="center"> 

  <p><strong> Feature Manager</strong></p>

  <form action="" name="sidebar" method="POST">

    <table width="571" border="0" align="center" cellpadding="5" cellspacing="0" class="box">

      <tr>

        <td width="557"><div align="center">

            <h2>Enable / Disable SideBar Features</h2>

        </div></td>

      </tr>

      <tr>

        <td>

          <table width="542" height="96" border="1" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td width="198" height="41" bgcolor="#E0E0E0"><div align="right"><span class="style3"> Display Website Stats</span></div></td>

              <td width="71" bgcolor="#E0E0E0"><span class="style3">

                <input name="displaystats1" type="radio" value="yes" <?php if ($ar['displaystats'] == 'yes') { echo "checked"; } ?>>

            On <br>

            <input name="displaystats1" type="radio" value="no" <?php if ($ar['displaystats'] == 'no') { echo "checked"; } ?>>

            Off </span></td>

              <td width="8">&nbsp;</td>

              <td width="189" bgcolor="#E0E0E0"><div align="right"><span class="style3">

                  <label id="displayfbanner">Display Featured Banners</label>

              </span></div></td>

              <td width="64" bgcolor="#E0E0E0"><span class="style3">

                <input type="radio" name="displayfbanner1" value="yes" <?php if ($ar['displayfbanner'] == 'yes') { echo "checked"; } ?>>

            On <br>

            <input type="radio" name="displayfbanner1" value="no" <?php if ($ar['displayfbanner'] == 'no') { echo "checked"; } ?>>

            Off </span></td>

            </tr>

            <tr>

              <td height="36" bgcolor="#E0E0E0"><div align="right"><span class="style3">

                  <label id="displaylinks1">Display Sponsored Links</label>

              </span></div></td>

              <td bgcolor="#E0E0E0"><span class="style3">

                <input type="radio" name="displaylinks1" value="yes" <?php if ($ar['displaylinks'] == 'yes') { echo "checked"; } ?>>

            On <br>

            <input type="radio" name="displaylinks1" value="no" <?php if ($ar['displaylinks'] == 'no') { echo "checked"; } ?>>

            Off </span></td>

              <td>&nbsp;</td>

              <td bgcolor="#E0E0E0"><div align="right"><span class="style3">Display Top Earners </span></div></td>

              <td bgcolor="#E0E0E0"><span class="style3">

                <input type="radio" name="displaytopearners" value="yes" <?php if ($ar['displaytopearners'] == 'yes') { echo "checked"; } ?>>

  On <br>

  <input type="radio" name="displaytopearners" value="no" <?php if ($ar['displaytopearners'] == 'no') { echo "checked"; } ?>>

  Off </span></td>

            </tr>

            <tr>

              <td width="198" bgcolor="#E0E0E0"><div align="right"><span class="style3">Display Featured Ads</span></div></td>

              <td width="71" bgcolor="#E0E0E0"><span class="style3">

                <input type="radio" name="displayfad1" value="yes" <?php if ($ar['displayfad'] == 'yes') { echo "checked"; } ?>>

            On <br>

            <input type="radio" name="displayfad1" value="no" <?php if ($ar['displayfad'] == 'no') { echo "checked"; } ?>>

            Off </span></td>

              <td>&nbsp;</td>

              <td bgcolor="#E0E0E0"><div align="right"><span class="style3">Display Top 5 Referers</span></div></td>

              <td width="64" bgcolor="#E0E0E0"><span class="style3">

                <input type="radio" name="displaytop51" value="yes" <?php if ($ar['displaytop5'] == 'yes') { echo "checked"; } ?>>

            On <br>

            <input type="radio" name="displaytop51" value="no" <?php if ($ar['displaytop5'] == 'no') { echo "checked"; } ?>>

            Off </span></td>

            </tr>

            <tr>

              <td bgcolor="#E0E0E0"><div align="right"><span class="style3">Display Benifits</span></div></td>

              <td bgcolor="#E0E0E0"><span class="style3">

                <input type="radio" name="displaybenefits1" value="yes" <?php if ($ar['displaybenefits'] == 'yes') { echo "checked"; } ?>>

            On <br>

            <input type="radio" name="displaybenefits1" value="no" <?php if ($ar['displaybenefits'] == 'no') { echo "checked"; } ?>>

            Off </span></td>

              <td>&nbsp;</td>

              <td bgcolor="#E0E0E0"><?php /*<div align="right"><span class="style3">Sidebar Position </span></div>*/ ?>&nbsp;</td>

              <td bgcolor="#E0E0E0"><?php /*<span class="style3">

                <input type="radio" name="sideBarLeft" value="1" <?php if ($ar['sideBarLeft'] == '1') { echo "checked"; } ?>>

  Left <br>

  <input type="radio" name="sideBarLeft" value="0" <?php if ($ar['sideBarLeft'] == '0') { echo "checked"; } ?>>

  Right </span>*/ ?>&nbsp;</td>

            </tr>

          </table>

          <div align="center">

            <input type="hidden" name="sp" value="sidebardisable">

            <input type="hidden" name="tp" value="features">

            <br>

            <input type="submit" name="Submit" value="Save Changes">

            <br>

        </div></td>

      </tr>

    </table>

  </form>

  

</div>

