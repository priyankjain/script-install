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
 loginCheck(); ?><?

if($act == 'addBonus') {

	if($_REQUEST['bonusType'] == 'advertising') {

		if(!is_numeric($_REQUEST['advertisingBonus'])) {

			$error = "Bonus type is invalid.".$_REQUEST['advertisingBonus']."";

		} else {

			$bonus = $_REQUEST['advertisingBonus'];

		}

	} else {

		if(!is_numeric($_REQUEST['otherBonus'])) {

			$error = "Bonus type is invalid.";

			//$error = TRUE;

		} else {

			$bonus = $_REQUEST['otherBonus'];

		}

	}

	if(!$error) {

		$q = mysql_query("INSERT INTO referralbonus (`place`, `bonus`, bonusType) VALUES (".quote_smart($_REQUEST['place']).",".quote_smart($bonus).",".quote_smart($_REQUEST['bonusType']).")") or die(mysql_error());

		displaySuccess("Inserted new bonus successfully!");

	} else {

		displayError("An error has occurred: ". $error);

	}

}

if($act == 'remBonus') {

	if(getCount("SELECT COUNT(id) FROM referralbonus WHERE id = ".quote_smart($_REQUEST['id'])."","COUNT") > 0) {

		mysql_query("DELETE FROM referralbonus WHERE id = ".quote_smart($_REQUEST['id'])."");

		displaySuccess("Removed contest bonus successfully.");

	} else {

		displayError("Could not remove contest bonus.");

	}

}

if($act=='ch')



{



mysql_query("UPDATE `refcontest` SET `datafrom`='$datafrom', datato='$datato', firstplace='$firstplace', secondplace='$secondplace', thirdplace='$thirdplace', winner1='$winner1', winner2='$winner2', winner3='$winner3', fpmethod='$fpmethod', spmethod='$spmethod', tpmethod='$tpmethod'") or die(mysql_error());







}

@extract(mysql_fetch_array(mysql_query("SELECT * FROM refcontest")));

if($act == 'newBonus') {

	?>

<p><br>

<script language="javascript" type="text/javascript">

function updateBonusType(bonusField) {

	//bonusField = document.getElementById("bonusType");

	selectedObject = bonusField[bonusField.selectedIndex].value;

	adForm = document.getElementById("advertisingForm");

	bonusForm = document.getElementById("bonusForm");

	if(selectedObject == 'advertising') {

		adForm.style.display = 'block';

		bonusForm.style.display = 'none';

	} else {

		adForm.style.display = 'none';

		bonusForm.style.display = 'block';

	}

}

</script>

</p>

<form name="form" id="form" method="post" action="index.php">

<input type="hidden" name="tp" value="refcontest">

<input type="hidden" name="act" value="addBonus">

<table width="500" border="0" cellpadding="5" cellspacing="1" bgcolor="#EFEFEF">

  <tr>

    <td colspan="2"><strong>New Bonus </strong></td>

  </tr>

  <tr bgcolor="#FFFFFF">

    <td>Bonus Type </td>

    <td><select name="bonusType" id="bonusType" onChange="javascript:updateBonusType(this.form.bonusType.options);">

      <option value="cash">Cash</option>

	  <option value="points">Points</option>

	  <option value="advertising">Advertising</option>

	  <option value="referrals">Referrals</option>

    </select></td>

  </tr>

  <tr bgcolor="#FFFFFF">

    <td>Bonus Amount / Package </td>

    <td><div id="bonusForm" style="display: block"><input type="text" name="otherBonus"></div><div id="advertisingForm" style="display: none"><select name="advertisingBonus" id="advertisingBonus">

      <?php

	  $query = mysql_query("SELECT fnum,pack_name, pack_price FROM packages WHERE packSpecial = 1 ORDER BY pack_name DESC");

	  $count = mysql_num_rows($query);

	  for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query,$i);

		$arr = mysql_fetch_array($query);

		?><option value="<?php echo $arr['fnum']; ?>"><?php echo '*'.$arr['pack_name'].' ($'.$arr['pack_price'].")"; ?></option>

		<?php

	  }

	  $query = mysql_query("SELECT pack_name, pack_price,fnum FROM packages WHERE packSpecial = 0 AND pack_name IS NOT NULL ORDER BY pack_name DESC");

	  $count = mysql_num_rows($query);

	  for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query,$i);

		$arr = mysql_fetch_array($query);

		?><option value="<?php echo $arr['fnum']; ?>"><?php echo $arr['pack_name'].' ($'.$arr['pack_price'].")"; ?></option>

		<?php

	  }

	  ?>

    </select></div></td>

  </tr>

  <tr bgcolor="#FFFFFF">

    <td>Place in Contest</td>

    <td><select name="place">

      <option value="1">1st Place</option>

	  <option value="2">2nd Place</option>

	  <option value="3">3rd Place</option>

	  <option value="4">4th Place</option>

	  <option value="5">5th Place</option>

	  <option value="6">6th Place</option>

	  <option value="7">7th Place</option>

	  <option value="8">8th Place</option>

	  <option value="9">9th Place</option>

	  <option value="10">10th Place</option>

    </select></td>

  </tr>

  <tr bgcolor="#FFFFFF">

    <td colspan="2"><div align="center">

      <input type="submit" name="Submit" value="Add this bonus now!">

    </div></td>

  </tr>

</table>

</form>

 <?php

}

?>



<table width="100%" border="0" cellspacing="0" cellpadding="5">



  <tr> 



    <td colspan="2" bgcolor="#006666"><font color="#FFFFFF"><b>REFERRAL CONTEST</b></font></td>



  </tr>



  <tr valign="top"> 



    <td width="61%">    <table width="100%" border="0" cellspacing="0" cellpadding="5">

      <?php

	  $query = mysql_query("SELECT * FROM referralbonus ORDER BY place ASC");

	  $count = mysql_num_rows($query);

	  if($count > 0) {

	  	?>      <tr>

        <td colspan="3"><strong>Referral Bonus Settings</strong></td>

      </tr>

		<?php

	  	for($i = 0;$i < $count;$i++) {

			mysql_data_seek($query, $i);

			$arr = mysql_fetch_array($query);

			

	  ?><tr>

        <td width="20%"><?php if($prevPlace != $arr['place']) { echo 'Place: '.$arr['place']; } ?></td>

        <td width="67%"><?php 

		

		if($arr['bonusType'] == 'cash') {

			echo $setupinfo['currency'].number_format($arr['bonus'],5);

		} else if($arr['bonusType'] == 'points') {

			echo $arr['bonus']." Points";

		} else if($arr['bonusType'] == 'advertising') {

			echo getValue("SELECT pack_name FROM packages WHERE fnum = ".quote_smart($arr['bonus'])."");

		} else if($arr['bonusType'] == 'referrals') {

			echo $arr['bonus']." Referrals";

		}?></td>

		<td width="13%"><div align="right"><a href="index.php?tp=refcontest&act=remBonus&id=<?php echo $arr['id']; ?>">Delete</a></div></td>

      </tr>

	  <?php

	  	$prevPlace = $arr['place'];

	  	}

	  }

	  ?>

    </table>

	<a href="index.php?tp=refcontest&act=newBonus">Add new bonus</a>	</td>



    <td width="39%">



      <table width="90%" border="0" cellspacing="0" cellpadding="5" align="center">



        <tr> 



          <td colspan="3" bgcolor="#CCCCCC"><b>CURRENT REFERRAL CONTEST</b></td>



        </tr>



        <tr> 



          <td><b><font size="1">Place</font></b></td>



          <td><b><font size="1">ID#</font></b></td>



          <td><b><font size="1">Referrals</font></b></td>



        </tr>



		



		<?



		$sql=mysql_query("SELECT * FROM users ORDER BY refcount DESC");



		$rows=mysql_num_rows($sql);



		if($rows<10) $fin=$rows; else $fin=10;



		for($i=0;$i<$fin;$i++)



		{



		mysql_data_seek($sql,$i);



		extract(mysql_fetch_array($sql));



		$a=$i+1;



		if(is_int($i/2))echo"<tr bgcolor=f5f5f5>"; else echo"<tr>";



		echo"<td>$a</td><td>$username</td><td>$refcount referrals</td></tr>



		



		";



		}



		?>

      </table>



    </td>



  </tr>

</table>



