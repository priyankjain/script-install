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
 loginCheck(); ?><script type="text/javascript">

function switchPackDivs(divID) {

	object1 = document.getElementById('packType1');

	object2 = document.getElementById('packType0');

	if(divID == 1) {

		object1.style.display = "block";

		object2.style.display = "none";

	} else {

		object1.style.display = "none";

		object2.style.display = "block";

	}

}

</script>

<?php

$act = $_REQUEST['act'];

$orphanCount = orphanCount();

if($act == 'deletePackage' && isset($_REQUEST['packID']) && $_REQUEST['packID'] != '') {

	$query = "SELECT COUNT(fnum) FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."";

	if(getValue($query) > 0) {

		$query = "SELECT COUNT(id) FROM packitems WHERE package = ".quote_smart($_REQUEST['packID'])."";

		if(getValue($query) > 0) {

			$query = "DELETE FROM packitems WHERE package = ".quote_smart($_REQUEST['packID'])."";

			$q = mysql_query($query);

		}

		$query = "DELETE FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."";

		$q = mysql_query($query);

		

		if(mysql_affected_rows()) {

			displaySuccess("Removed item from package successfully.");

		} else {

			displayError("Failure to remove item from package.");

		}

		$act = '';

	} else {

		displayError("Could not find package!");

		$act = 'edit';

	}

}

if($act == 'removeItemFromPack' && isset($_REQUEST['packID']) && $_REQUEST['itemID'] != '') {

	$query = "SELECT COUNT(id) FROM packitems WHERE package = ".quote_smart($_REQUEST['packID'])." AND id = ".quote_smart($_REQUEST['itemID'])."";

	if(getCount($query, "COUNT") > 0) {

		$query = "DELETE FROM packitems WHERE id = ".quote_smart($_REQUEST['itemID'])." AND package = ".quote_smart($_REQUEST['packID'])."";				$q = mysql_query($query);

		if(mysql_affected_rows()) {

			displaySuccess("Removed item from package successfully.");

		} else {

			displayError("Failure to remove item from package.");

		}

	} else {

		displayError("This item is already in this package!");

	}

	$act = 'editPack';

}

if($act == 'addItemToPackage' && isset($_REQUEST['packID']) && isset($_REQUEST['btnUpdatePackage'])) {

	if(getValue("SELECT COUNT(fnum) FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."") > 0) {

		if(strlen($_REQUEST['packName']) > 5 && strlen($_REQUEST['packPrice']) > 1) {

			$sqlAddition = '';

			$packSpecial = getValue("SELECT packSpecial FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."");

			if($packSpecial != '1') {

				$sqlAddition = ", pack_credits = ".quote_smart($_REQUEST['pack_credits']).", pack_credits_type = ".quote_smart($_REQUEST['pack_credits_type'])."";

			}

			mysql_query("UPDATE packages SET pack_name=".quote_smart($_REQUEST['packName']).", pack_price = ".quote_smart($_REQUEST['packPrice'])." $sqlAddition WHERE fnum = ".quote_smart($_REQUEST['packID'])."");

			displaySuccess("Your package has been updated.");

			$act = '';

		} else {

			displayError("Your price must be over 1 character, and your pack name must be over 5 characters.");

			$act = 'edit';

		}

	} else {

		displayError("Could not find package!");

		$act = 'edit';

	}

}

if($act == 'addItemToPackage' && isset($_REQUEST['packID']) && isset($_REQUEST['btnAddItem'])) {

	if(getValue("SELECT COUNT(fnum) FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."") > 0) {

		if(getValue("SELECT COUNT(fnum) FROM packages WHERE fnum = ".quote_smart($_REQUEST['packContents'])."") > 0) {

			$query = "SELECT COUNT(id) FROM packitems WHERE package = ".quote_smart($_REQUEST['packID'])." AND item = ".quote_smart($_REQUEST['packContents'])."";

			if(getCount($query, "COUNT") == 0) {

				$query = "INSERT INTO packitems(item, package) VALUES (".quote_smart($_REQUEST['packContents']).", ".quote_smart($_REQUEST['packID']).")";

				$q = mysql_query($query);

				if(mysql_affected_rows()) {

					displaySuccess("Inserted item into package successfully.");

				} else {

					displayError("Failure to insert item into package.");

				}

			} else {

				displayError("This item is already in this package!");

			}

		} else {

			displayError("Package not found!");

		}

	} else {

		displayError("Pack not found!");

	}

	$act = 'editPack';

}

if($act == 'addPackage') {

	if(isset($_REQUEST['btnCreatePackage']) && $_REQUEST['btnCreatePackage'] == 'Create this package!') {

		if((isset($_SESSION['packitems']) && count($_SESSION['packitems']) > 0) || $_REQUEST['packSpecial'] == '0') {

			$newPrice = str_replace(".", "", trim($_REQUEST['packPrice']));

			if(is_numeric($newPrice)) {

				if(strlen($_REQUEST['packName']) > 0) {

					$count = getValue("SELECT COUNT(fnum) FROM packages WHERE pack_name = ".quote_smart($_REQUEST['packName'])."");

					if($count == 0) {

						if($_REQUEST['packSpecial'] == '1') {

							$categoryCode = getValue("SELECT pack_category_code+1 FROM packages ORDER BY pack_category_code DESC LIMIT 0, 1");

							$catName = 'Specials';

						} else {

							if($_REQUEST['pack_credits_type'] == 'banner') {

								$catName = 'Banner Credits';

							} else if($_REQUEST['pack_credits_type'] == 'email') {

								$catName = 'Paid e-mail credits';

							} else if($_REQUEST['pack_credits_type'] == 'links') {

								$catName = ' 	Paid-To-Click  credits';

							} else if($_REQUEST['pack_credits_type'] == 'signup') {

								$catName = 'Paid-To-Signup credits';

							} else if($_REQUEST['pack_credits_type'] == 'fbanner') {

								$catName = 'Featured Banner Credits';

							} else if($_REQUEST['pack_credits_type'] == 'fad') {

								$catName = 'Featured Ad Credits';

							} else if($_REQUEST['pack_credits_type'] == 'flinks') {

								$catName = 'Featured Link Credits';

							} else if($_REQUEST['pack_credits_type'] == 'survey') {

								$catName = 'Paid Survey Credits';

							} else if($_REQUEST['pack_credits_type'] == 'ptrad') {

								$catName = 'Paid to Read Ad Credits';

							} else if($_REQUEST['pack_credits_type'] == 'referrals') {

								$catName = 'Referrals';

							}

							

							$categoryCode = getValue("SELECT pack_category_code FROM packages WHERE pack_category_name = ".quote_smart($catName)."");

							$sqlFields = ',pack_credits,pack_credits_type';

							$sqlValues = ','.quote_smart($_REQUEST['pack_credits']).','.quote_smart($_REQUEST['pack_credits_type']);

						}

						$query = mysql_query("INSERT INTO packages (

							pack_category_code,

							pack_category_name,

							pack_name,

							pack_price,

							packSpecial,

							active

							$sqlFields

						) VALUES (

							".quote_smart($categoryCode).",

							".quote_smart($catName).",

							".quote_smart($_REQUEST['packName']).",

							".quote_smart(trim($_REQUEST['packPrice'])).",

							".quote_smart($_REQUEST['packSpecial']).",

							".quote_smart('1')."

							$sqlValues

						)") or die(mysql_error());

						$packID = mysql_insert_id();

						if($_REQUEST['packSpecial'] == '1') { 

							foreach($_SESSION['packitems'] as $k => $v) mysql_query("INSERT INTO packitems (item, package) VALUES (".quote_smart($v).",".quote_smart($packID).")");

						}

						displaySuccess("Created new package successfully.<BR>Pack Name: <strong>".$_REQUEST['packName']."</strong>");

						$act = 'addPackage';

						unset($_SESSION['packitems']);

					} else {

						unset($_SESSION['packitems']);

						$act = 'addPackage';

					}

				} else {

					displayError("Invalid Pack Name");

				}

			} else {

				displayError("Invalid Price");

			}

		} else {

			displayError("You have not added any items to this package....\nAdd at least 1 item for a special...");

		}

		

	} else if(isset($_REQUEST['btnAddItem']) && $_REQUEST['btnAddItem'] == '<-- Add to package')  {

		$c = count($_SESSION['packitems']);

		if(count($_SESSION['packitems']) > 0) {

			$found = FALSE;

			foreach($_SESSION['packitems'] as $k => $v) {

				if($v == $_REQUEST['packContents']) $found = TRUE;

			}

			$j = count($_SESSION['packitems'])+1;

			if($found === FALSE) $_SESSION['packitems'][] = $_REQUEST['packContents'];

		} else {

			$_SESSION['packitems'][$j] = $_REQUEST['packContents'];

		}

		$act = 'addPackage';

	} else {

		$act = 'addPackage';

	}

}

if($act == 'addPackage') {

	?>

<style type="text/css">

<!--

.style1 {

	font-family: Arial, Helvetica, sans-serif;

	font-size: 12px;

}

-->

</style>



<br>

<table class="fullwidth" border="0" cellpadding="0" cellspacing="0">

        <thead>
        <tr>

          <td>Add a new Special package below.</td>
          

        </tr>
</thead><tbody>
<tr valign="top" bgcolor="#FFFFFF">

          <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td><form name="form" method="post" action="index.php">

                  <p>Package Name

                      <input name="packName" type="text" size="35" value="<?php echo $_REQUEST['packName']; ?>">

                      <br>

          Package Price

          <input name="packPrice" type="text" size="5" value="<?php echo $_REQUEST['packPrice']; ?>">

          <br>

          Package Type:

          <input name="packSpecial" type="radio" onClick="javascript:switchPackDivs(1);" value="1" <?php if($_REQUEST['packSpecial'] == '1' || $_REQUEST['packSpecial'] == '') { echo "checked"; } ?>>

          Special

          <input name="packSpecial" type="radio" value="0" <?php if($_REQUEST['packSpecial'] == '0') { echo "checked"; } ?> onClick="javascript:switchPackDivs(0)">

          Base <BR>

          <?php

		  //if($_REQUEST['packageType'] == '1' || $_REQUEST['packageType'] == '') {

		  ?>

                  <div id="packType1" <?php if($_REQUEST['packSpecial'] == '1' || $_REQUEST['packSpecial'] == '') { ?>style="display: block;"<?php } else { echo "style=\"display: none\""; } ?>>

                    <select name="packContents">

                      <?php

				$query = mysql_query("SELECT * FROM packages WHERE packSpecial != '1' AND pack_name != ''");

				$count = mysql_num_rows($query);

				if($count > 0) {

					for($i = 0;$i < $count;$i++) {

						mysql_data_seek($query, $i);

						$arr = mysql_fetch_array($query);

						echo "<option value=\"".$arr['fnum']."\"";
						if($arr['fnum'] == $_REQUEST['packContents']) echo " selected=\"selected\"";
						echo ">".$arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'], 2)." Value)</option>";

					}				

				}

			?>

                    </select>

                    <input type="submit" name="btnAddItem" value="<-- Add to package">

                    <br>

                    <br>

                  </div>

                  <?php

		  //} else {

		  ?>

                  <div id="packType0" <?php if($_REQUEST['packSpecial'] == '0') { echo "style=\"display: block\""; } else { echo "style=\"display: none\""; } ?>> Credits:

                      <input name="pack_credits" type="text" value="<?php echo $pack_credits; ?>" size="8">

                      <BR>

          Credits Type:

          <select name="pack_credits_type">

            <option value="links" <?php if($pack_credits_type == 'links') echo "selected"; ?>>Link / PTC Credits</option>

            <option value="banner" <?php if($pack_credits_type == 'banner') echo "selected"; ?>>Banner (468x60) Credits</option>

            <option value="fbanner" <?php if($pack_credits_type == 'fbanner') echo "selected"; ?>>Featured Banner (180x100) Credits</option>

            <option value="fad" <?php if($pack_credits_type == 'fad') echo "selected"; ?>>Featured Ad Credits</option>

            <option value="flinks" <?php if($pack_credits_type == 'flinks') echo "selected"; ?>>Featured Link Credits</option>

            <option value="signup" <?php if($pack_credits_type == 'signup') echo "selected"; ?>>Paid to Sign-Up Credits</option>

            <option value="email" <?php if($pack_credits_type == 'email') echo "selected"; ?>>Paid to Read E-Mail Credits</option>

		    <option value="survey" <?php if($pack_credits_type == 'survey') echo "selected"; ?>>Paid Survey Credits</option>

			<option value="ptrad" <?php if($pack_credits_type == 'ptrad') echo "selected"; ?>>Paid to Read Ad Credits</option>

			<option value="referrals" <?php if($pack_credits_type == 'referrals') echo "selected"; ?>>Referrals</option>

          </select>

                  </div>

                  <?php

		 // }

		  ?>

                  <input type="hidden" name="act" value="addPackage">

                  <input type="hidden" name="tp" value="packSpecials">

<?php

				$packitems = $_SESSION['packitems'];

				$c = count($packitems);

				if($c > 0) { 

					$inVar = '';

					foreach($packitems as $k => $v) $inVar .= quote_smart($v).",";

					$inVar = substr($inVar, 0, strlen($inVar)-1);

					//echo "SELECT * FROM packages WHERE fnum IN (".$inVar.")<BR><BR>";

					$query = mysql_query("SELECT * FROM packages WHERE fnum IN (".$inVar.")");

					$count = mysql_num_rows($query);

					if($count > 0) {

						for($i = 0;$i < $count;$i++) {

							mysql_data_seek($query, $i);

							$arr = mysql_fetch_array($query);

							echo $arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'])." Value)<BR>";

						}				

					}

				}

			?>

                  <p align="center">

                    <input type="submit" name="btnCreatePackage" value="Create this package!">

                  </p>

              </form></td>

            </tr>

          </table></tbody></td>

        </tr>

</table>

<p>&nbsp;</p>
<p align="center"><a href="index.php?tp=packSpecials">Back to Advertising Packs</a></p>
<p align="center"><br>
  
  <br>
  
  <br>
  
  <?php

}

if($act == 'editPack') {

$query = mysql_query("SELECT * FROM packages WHERE fnum = ".quote_smart($_REQUEST['packID'])."");

$count = mysql_num_rows($query);

if($count == 0) {

	echo "Pack not found!<BR>";

} else {

	$arr = mysql_fetch_array($query);

	extract($arr);

?>
</p>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="1">

        <tr bgcolor="#CECECE">

          <td bgcolor="#ECECEC"><strong>Edit your package below. </strong></td>

        </tr>

        <tr valign="top" bgcolor="#FFFFFF">

          <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td><form name="form2" method="post" action="index.php">

                      <table width="100%"  border="0" cellspacing="0" cellpadding="2">

                        <tr>

                          <td>Package Name </td>

                          <td><input name="packName" type="text" size="35" value="<?php echo $pack_name; ?>"></td>

                        </tr>

                        <tr>

                          <td>Package Price </td>

                          <td><input name="packPrice" type="text" size="5" value="<?php echo $pack_price; ?>"></td>

                        </tr>

                        <?php

		  if($packSpecial == '1') { ?>

                        <tr>

                          <td>Contents:</td>

                          <td><select name="packContents">

                              <?php

				$query = mysql_query("SELECT * FROM packages WHERE packSpecial != '1' AND pack_name != ''");

				$count = mysql_num_rows($query);

				if($count > 0) {

					for($i = 0;$i < $count;$i++) {

						mysql_data_seek($query, $i);

						$arr = mysql_fetch_array($query);

						echo "<option value=\"".$arr['fnum']."\">".$arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'], 2)." Value)</option>";

					}				

				}

			?>

                            </select>

                              <input type="submit" name="btnAddItem" value="<- Add to pack"></td>

                        </tr>

                        <?php } else { ?>

                        <tr>

                          <td>Credits: </td>

                          <td><input name="pack_credits" type="text" value="<?php echo $pack_credits; ?>" size="8"></td>

                        </tr>

                        <tr>

                          <td> Credits Type: </td>

                          <td><select name="pack_credits_type">

                              <option value="links" <?php if($pack_credits_type == 'links') echo "selected"; ?>>Link / PTC Credits</option>

                              <option value="banner" <?php if($pack_credits_type == 'banner') echo "selected"; ?>>Banner (468x60) Credits</option>

                              <option value="fbanner" <?php if($pack_credits_type == 'fbanner') echo "selected"; ?>>Featured Banner (180x100) Credits</option>

                              <option value="fad" <?php if($pack_credits_type == 'fad') echo "selected"; ?>>Featured Ad Credits</option>

            				  <option value="flinks" <?php if($pack_credits_type == 'flinks') echo "selected"; ?>>Featured Link Credits</option>

                              <option value="signup" <?php if($pack_credits_type == 'signup') echo "selected"; ?>>Paid to Sign-Up Credits</option>

                              <option value="email" <?php if($pack_credits_type == 'email') echo "selected"; ?>>Paid to Read E-Mail Credits</option>

							  <option value="survey" <?php if($pack_credits_type == 'survey') echo "selected"; ?>>Paid Survey Credits</option>

							  <option value="ptrad" <?php if($pack_credits_type == 'ptrad') echo "selected"; ?>>Paid to Read Ad Credits</option>

                          	  <option value="referrals" <?php if($pack_credits_type == 'referrals') echo "selected"; ?>>Referrals</option>

						  </select></td>

                        </tr>

                        <?php } ?>

                      </table>

                      <p>                          <input type="hidden" name="act" value="addItemToPackage">

                          <input type="hidden" name="tp" value="packSpecials">

                          <input type="hidden" name="packID" value="<?php echo $_REQUEST['packID']; ?>">

                          <?php

				/*$packitems = $_SESSION['packitems'];

				$c = count($packitems);

				if($c > 0) { 

					$inVar = '';

					foreach($packitems as $k => $v) $inVar .= quote_smart($v).",";

					$inVar = substr($inVar, 0, strlen($inVar)-1);

					echo "SELECT * FROM packages WHERE fnum IN (".$inVar.")<BR><BR>";

					$query = mysql_query("SELECT * FROM packages WHERE fnum IN (".$inVar.")");

					$count = mysql_num_rows($query);

					if($count > 0) {

						for($i = 0;$i < $count;$i++) {

							mysql_data_seek($query, $i);

							$arr = mysql_fetch_array($query);

							echo $arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'])." Value) <a href=\"index.php?tp=packSpecials&act=removeItemFromPack&packID=".$_REQUEST['packID']."&itemID=".$arr['id']."\">Remove</a><BR>";

						}				

					}

				}*/

			if($packSpecial == '1') {

				$query = mysql_query("SELECT * FROM packitems, packages WHERE packitems.package = ".quote_smart($fnum)." AND packages.fnum = packitems.item");

				$count = mysql_num_rows($query);

				if($count > 0) {

					for($i = 0;$i < $count;$i++) {

						mysql_data_seek($query, $i);

						$arr = mysql_fetch_array($query);

						echo $arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'])." Value) <a href=\"index.php?tp=packSpecials&act=removeItemFromPack&packID=".$fnum."&itemID=".$arr['id']."\">Remove</a><BR>";

					}	

				} else {

					echo "No items found for this package.<BR>";

				}

			}

			?>

                      </p>

                      <p align="center">

                        <input type="submit" name="btnUpdatePackage" value="Update this package!">

                      </p>

                  </form>

                    <form name="form2" method="post" action="index.php">

                     

                        <div align="center">

                          <p>&nbsp;</p>

                          <p>

                            <input type="hidden" name="act" value="deletePackage">

                            <input type="hidden" name="tp" value="packSpecials">

                            <input type="hidden" name="packID" value="<?php echo $_REQUEST['packID']; ?>">

                       

                            

                          <input type="submit" name="btnRemovePackage" value="DELETE this package PERMANENTLY!" style="color: #FF0000">

                         

                        </p>

                        </div>

                    </form>

                    <p>&nbsp;</p></td>

                </tr>

              </table></td>

            </tr>

          </table></td>

        </tr>

</table>

<?php

	} //PACK COUNT > 0

}

if($act == '') {

	$query = mysql_query("SELECT * FROM packages WHERE pack_price != '0.00' ORDER BY packSpecial DESC, pack_credits_type DESC, active DESC,fnum ASC");

	$count = mysql_num_rows($query);

	if($count > 0) {

	?>

<BR><div align="center"><br><strong>To add a new package, <a href="index.php?tp=packSpecials&act=addPackage">click here</a>.</strong></div>

	<br><div class="hastable_disabled">
<table class="fullwidth" border="0" cellpadding="0" cellspacing="0" width="600px">
<thead>
		  <tr>

		  	<td width="109">Pack Sales</td>

			<td width="364">Name / Contents</td>

			<td width="127">Referrals</td>

	  </tr>
</thead><tbody>
		  <?php

	  for($i = 0;$i < $count;$i++) {

		mysql_data_seek($query, $i);

		$arr = mysql_fetch_array($query);

	  ?>
    <tr valign="top" <?php if($arr['packSpecial'] == '1') { ?>class="odd"<?php } ?>>

		  	<td valign="top"><br />
		  	  <img src="images/icons/credit<?php if($arr['packSpecial'] == '1') echo 's'; ?>.png" hspace="0" vspace="5" border="0" /><br /><?php if($arr['packSpecial'] == '1') echo 'Advertising Package'; else echo 'Credits Price'; ?>
<?php //echo $setupinfo['currency'].number_format(getValue("SELECT SUM(orderTotal) FROM orders WHERE orderPaid = '1' AND packageID = ".quote_smart($arr['fnum']).""),2); ?></td>
			<td valign="top"><a href="index.php?tp=packSpecials&act=editPack&packID=<?php echo $arr['fnum']; ?>"><br />
		    <?php echo $arr['pack_name']; ?></a> (<?php echo $setupinfo['currency']; ?><?php echo number_format($arr['pack_price'],2); ?>)<br>

		    <?php

		if($arr['packSpecial'] == '1') {

			$q = mysql_query("SELECT b.pack_name, b.pack_price, b.pack_credits_type FROM packitems a, packages b WHERE a.item = b.fnum AND a.package = ".quote_smart($arr['fnum'])."");

			$c = mysql_num_rows($q);

			if($c > 0) {

				for($k = 0;$k < $c;$k++) {

					mysql_data_seek($q,$k);

					$ar = mysql_fetch_array($q);

					echo $ar['pack_name']." (".$setupinfo['currency'].number_format($ar['pack_price'],2)." Value)<BR>";

				}

			} else {

				echo "There are no items in this special...<BR><a href=\"index.php?tp=packSpecials&act=addItem&packID=".$arr['fnum']."\">Add a new item.</a>";

			}

		} else {

			?>Credits Type: <?php echo $arr['pack_credits_type']; ?><BR>

			Credit Amount: <?php echo $arr['pack_credits']; ?>

	<?php

		}

		$refCount = packReferrals($arr['fnum']);

		if($orphanCount < $refCount) echo "<HR><FONT COLOR=RED>This pack is not displaying because there are not enough referrals to honor the purchase. There are currently $orphanCount orphans, and $refCount referrals are needed for this pack.</FONT>";

		?><td valign="top"><br />
	  <?php echo $refCount; ?> </td>
    </tr><?php

	  } //END FOR LOOP

	  ?>
</tbody>
</table></div>
	<?php

	} else {//END IF COUNT($packages > 0)

		?>

	<div align="center"><br>

	  <br>

	There are no packages available.

	<br>

	</div>

	<?php

	}

	?><BR><div align="center"><br>

	  <strong>To add a new package, <a href="index.php?tp=packSpecials&act=addPackage">click here</a>.</strong><br>

	</div>

	<?php

} //END IF $act == ''

?>