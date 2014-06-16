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

 if($act=='delcountry')

 {

 mysql_query("DELETE FROM countries WHERE country=".quote_smart($country).""); echo"<b>$country HAS BEEN DELETED FROM COUNTRY-LIST!</b>";

 }

 ?>

  <?

 if($act=='addcountry')

 {

 mysql_query("INSERT INTO countries(country)  VALUES(".quote_smart($country).")"); echo"<b>$country HAS BEEN ADDED IN COUNTRY-LIST!</b>";

 }

 ?>
<h3>COUNTRY LIST</h3>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>

    <td>

      <form name="form1" method="post" action="">

        Add country in country list 

        <input type="hidden" name="tp" value="country">

        <input type="hidden" name="act" value="addcountry">

        <input type="text" name="country">

        <input type="submit" name="Submit" value="Add">

      </form>

    </td>

  </tr>

  <tr>

    <td bgcolor="f5f5f5"> 

      <table>

        <?php

	$sql=mysql_query("SELECT * FROM countries ORDER BY country") or die(mysql_error());

	$rows=mysql_num_rows($sql);

	for($i=0;$i<$rows;$i++)

	{

	mysql_data_seek($sql,$i);

	$arr=mysql_fetch_array($sql);

	extract($arr);

	echo"<tr><td>$country</td><td><form><input type=hidden name=tp value=country><input type=hidden name=act value=delcountry><input type=hidden name=country value=\"".htmlspecialchars($country)."\"><input type=submit value=Remove></form></td></tr>";

	}

	?>
      </table>

    </td>

  </tr>

</table>

<p>&nbsp;</p>

