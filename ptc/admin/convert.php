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
 loginCheck(); ?> <?php

 if($_REQUEST['act']=='convertpoints')

 {

 	if($_REQUEST['pointvalue'] != '' && is_numeric($_REQUEST['pointvalue']) && $_REQUEST['pointvalue'] > 0) {

	 $sql=mysql_query("SELECT username FROM users");

	 $rows=mysql_num_rows($sql);
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
	 for($i=0; $i<$rows; $i++) {

		mysql_data_seek($sql, $i);

		$arr = mysql_fetch_array($sql);

		$totalPoints = userPoints($arr['username']);

		$newCredit = $totalPoints*$_REQUEST['pointvalue'];

		debitAccountBalance($arr['username'], 'debit', $totalPoints, 'points','conversionPointsToCash');

		debitAccountBalance($arr['username'], 'credit', $newCredit, 'usd','conversionPointsToCash');

	 }
}

	 displaySuccess("ALL POINTS HAVE BEEN CONVERTED TO CASH...");

	} else {

		displayError("You must enter a point conversion rate > 0.");

	}

 }

 ?>

<form name="form1" method="post" action="index.php">

  <p>This form is meant to convert all user points earned over to cash. <font color="#FF0000">This change can not be un-done. </font></p>

  <table class="fullwidth" border="0">
<thead>
    <tr> 

      <td colspan="2">CONVERT POINTS TO USD</td>

    </tr>
</thead><tbody>
    <tr> 

      <td width="55%">1 POINT = 

        <input type="hidden" name="tp" value="convert">

        <input type="hidden" name="act" value="convertpoints">

        <input type="text" name="pointvalue" size="10">

        USD 

        <input type="submit" name="Submit" value="Convert points!">

      </td>

      <td width="45%">note: you must enter USD 

        value. For example, if you want to convert 1 point to 0,5 cents, you must 

        write: 0.005 in USD value.</td>

    </tr>
</tbody>
  </table>

</form>

<p><b>This process may take few minutes...</b></p>

