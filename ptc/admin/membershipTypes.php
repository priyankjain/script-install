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

function displayPercentOptions($selected = 1,$max = 10) {
	$return = '';
	$selected = number_format($selected,2,".","");
	for($i = 1;$i < $max; $i = $i+(0.1)) {
		$return .= '<option value="'.$i.'"';
		$i = number_format($i,2,".","");
		if($selected == $i) $return .= ' selected';
		$return .= '>'.number_format($i*100,0,"",",").'% ';
		if($i == 1) $return .= '(No Change)';
		$return .= "</option>\n";
	}
	return $return;
}

?><script type="text/javascript">
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
if($act == 'suspend' && isset($_REQUEST['membershipID'])) {
	$query = "SELECT COUNT(id) FROM membershiptypes WHERE id = ".quote_smart($_REQUEST['membershipID'])."";
	if(getCount($query, "COUNT") > 0) {
		$query = "UPDATE membershiptypes SET active = '0' WHERE id = ".quote_smart($_REQUEST['membershipID'])."";
		$q = mysql_query($query);
		if(mysql_affected_rows()) {
			displaySuccess("Suspended Membership successfully.");
		} else {
			displayError("Failure to suspend Membership.");
		}
	} else {
		displayError("This membership is already suspended!");
	}
	$act = '';
}
if($act == 'unsuspend' && isset($_REQUEST['membershipID'])) {
	$query = "SELECT COUNT(id) FROM membershiptypes WHERE id = ".quote_smart($_REQUEST['membershipID'])."";
	if(getCount($query, "COUNT") > 0) {
		$query = "UPDATE membershiptypes SET active = '1' WHERE id = ".quote_smart($_REQUEST['membershipID'])."";
		$q = mysql_query($query);
		if(mysql_affected_rows()) {
			displaySuccess("Un-Suspended Membership successfully.");
		} else {
			displayError("Failure to un-suspend Membership.");
		}
	} else {
		displayError("This membership is already active!");
	}
	$act = '';
}
if($act == 'removeItemFromPack' && isset($_REQUEST['membershipID']) && $_REQUEST['itemID'] != '') {
	$query = "SELECT COUNT(id) FROM membershipitems WHERE membershipID = ".quote_smart($_REQUEST['membershipID'])." AND id = ".quote_smart($_REQUEST['itemID'])."";
	if(getCount($query, "COUNT") > 0) {
		$query = "DELETE FROM membershipitems WHERE id = ".quote_smart($_REQUEST['itemID'])." AND membershipID = ".quote_smart($_REQUEST['membershipID'])."";				$q = mysql_query($query);
		if(mysql_affected_rows()) {
			displaySuccess("Removed item from Membership successfully.");
		} else {
			displayError("Failure to remove item from Membership.");
		}
	} else {
		displayError("This item is already in this Membership!");
	}
	$act = 'editPack';
}
if($act == 'addItemToMembership' && isset($_REQUEST['membershipID']) && isset($_REQUEST['btnUpdateMembership'])) {
	if(getValue("SELECT COUNT(id) FROM membershiptypes WHERE id = ".quote_smart($_REQUEST['membershipID'])."") > 0) {
		if(strlen($_REQUEST['membershipName']) > 5 && strlen($_REQUEST['price']) > 1) {
			
			$sqlAddition =  ',clickBonus='.quote_smart($_REQUEST['clickbonus']);
			$sqlAddition .= ',readadBonus='.quote_smart($_REQUEST['readadbonus']);
			$sqlAddition .= ',signupBonus='.quote_smart($_REQUEST['signupbonus']);
			$sqlAddition .= ',reademailBonus='.quote_smart($_REQUEST['reademailbonus']);
			$sqlAddition .= ',takesurveyBonus='.quote_smart($_REQUEST['takesurveybonus']);
			$sqlAddition .= ',clickTimer='.quote_smart($_REQUEST['clickTimer']);
			$sqlAddition .= ',readadTimer='.quote_smart($_REQUEST['readadTimer']);
			$sqlAddition .= ',signupTimer='.quote_smart($_REQUEST['signupTimer']);
			$sqlAddition .= ',reademailTimer='.quote_smart($_REQUEST['reademailTimer']);
			$sqlAddition .= ',takesurveyTimer='.quote_smart($_REQUEST['takesurveyTimer']);
			$sql = "UPDATE membershiptypes SET length=".quote_smart($_REQUEST['membershipLength']).", lengthType=".quote_smart($_REQUEST['membershipLengthType']).",membershipName=".quote_smart($_REQUEST['membershipName']).", membershipPrice = ".quote_smart($_REQUEST['price'])." $sqlAddition WHERE id = ".quote_smart($_REQUEST['membershipID'])."";
			mysql_query($sql);
			displaySuccess("Your Membership has been updated.");
			$act = '';
		} else {
			displayError("Your price must be over 1 character, and your pack name must be over 5 characters.");
			$act = 'edit';
		}
	} else {
		displayError("Could not find Membership!");
		$act = 'edit';
	}
}
if($act == 'addItemToMembership' && isset($_REQUEST['membershipID']) && isset($_REQUEST['btnAddItem'])) {
	if(getValue("SELECT COUNT(id) FROM membershiptypes WHERE id = ".quote_smart($_REQUEST['membershipID'])."") > 0) {
		if(getValue("SELECT COUNT(fnum) FROM packages WHERE fnum = ".quote_smart($_REQUEST['membershipContents'])."") > 0) {
			$query = "SELECT COUNT(id) FROM membershipitems WHERE membershipID = ".quote_smart($_REQUEST['membershipID'])." AND itemID = ".quote_smart($_REQUEST['membershipContents'])."";
			if(getCount($query, "COUNT") == 0) {
				$query = "INSERT INTO membershipitems(itemLength,itemLengthType,itemID, membershipID) VALUES (".quote_smart($_REQUEST['itemLength']).",".quote_smart($_REQUEST['itemLengthType']).",".quote_smart($_REQUEST['membershipContents']).", ".quote_smart($_REQUEST['membershipID']).")";
				$q = mysql_query($query);
				if(mysql_affected_rows()) {
					displaySuccess("Inserted item into Membership successfully.");
				} else {
					displayError("Failure to insert item into Membership.");
				}
			} else {
				displayError("This item is already in this Membership!");
			}
		} else {
			displayError("Membership not found!");
		}
	} else {
		displayError("Pack not found!");
	}
	$act = 'editPack';
}
if($act == 'addMembership') {
	if(isset($_REQUEST['btnCreateMembership']) && $_REQUEST['btnCreateMembership'] == 'Create this Membership!') {
		if((isset($_SESSION['membershipitems']) && count($_SESSION['membershipitems']) > 0) || $_REQUEST['packSpecial'] == '0') {
			$newPrice = str_replace(".", "", trim($_REQUEST['price']));
			if(is_numeric($newPrice)) {
				if(strlen($_REQUEST['membershipName']) > 0) {
					$count = getValue("SELECT COUNT(id) FROM membershiptypes WHERE membershipName = ".quote_smart($_REQUEST['membershipName'])."");
					if($count == 0) {
						$sqlFields .= ',clickBonus';
						$sqlValues .= ','.quote_smart($_REQUEST['clickbonus']);
						
						$sqlFields .= ',readadBonus';
						$sqlValues .= ','.quote_smart($_REQUEST['readadbonus']);
						
						$sqlFields .= ',signupBonus';
						$sqlValues .= ','.quote_smart($_REQUEST['signupbonus']);
						
						$sqlFields .= ',reademailBonus';
						$sqlValues .= ','.quote_smart($_REQUEST['reademailbonus']);
						
						$sqlFields .= ',takesurveyBonus';
						$sqlValues .= ','.quote_smart($_REQUEST['takesurveybonus']);
						
						$sqlFields .= ',clickTimer';
						$sqlValues .= ','.quote_smart($_REQUEST['clickTimer']);
						
						$sqlFields .= ',readadTimer';
						$sqlValues .= ','.quote_smart($_REQUEST['readadTimer']);
						
						$sqlFields .= ',signupTimer';
						$sqlValues .= ','.quote_smart($_REQUEST['signupTimer']);
						
						$sqlFields .= ',reademailTimer';
						$sqlValues .= ','.quote_smart($_REQUEST['reademailTimer']);
						
						$sqlFields .= ',takesurveyTimer';
						$sqlValues .= ','.quote_smart($_REQUEST['takesurveyTimer']);
						
						$sqlFields .= ',length';
						$sqlValues .= ','.quote_smart($_REQUEST['membershipLength']);
						
						$sqlFields .= ',lengthType';
						$sqlValues .= ','.quote_smart($_REQUEST['membershipLengthType']);
						$sq = "INSERT INTO membershiptypes (
							membershipName,
							membershipPrice,
							active
							$sqlFields
						) VALUES (
							".quote_smart($_REQUEST['membershipName']).",
							".quote_smart(trim($_REQUEST['price'])).",
							".quote_smart('1')."
							$sqlValues
						)";
						$query = mysql_query($sq) or die(mysql_error());
						$membershipID = mysql_insert_id();
						foreach($_SESSION['membershipitems'] as $k => $v) {
							mysql_query("INSERT INTO membershipitems (itemLength, itemLengthType, itemID, membershipID) VALUES (".quote_smart($v['itemLength']).",".quote_smart($v['itemLengthType']).",".quote_smart($v['item']).",".quote_smart($membershipID).")");
						}
						displaySuccess('Created new Membership successfully.');
						$act = '';
						unset($_SESSION['membershipitems']);
					} else {
						unset($_SESSION['membershipitems']);
						$act = '';
					}
				} else {
					displayError('Invalid Membership Name');
				}
			} else {
				displayError('Invalid Price'); 
			}
		} else {
			displayError("You have not added any items to this Membership....\nAdd at least 1 item for a special..");
		}
		
	} else if(isset($_REQUEST['btnAddItem']) && $_REQUEST['btnAddItem'] == '<-- Add to Membership')  {
		$c = count($_SESSION['membershipitems']);
		if(count($_SESSION['membershipitems']) > 0) {
			$found = FALSE;
			foreach($_SESSION['membershipitems'] as $k => $v) {
				if($v == $_REQUEST['membershipContents']) $found = TRUE;
			}
			$j = count($_SESSION['membershipitems'])+1;
			if($found === FALSE) {
				$_SESSION['membershipitems'][$j]['item'] = $_REQUEST['membershipContents'];
				$_SESSION['membershipitems'][$j]['itemLength'] = $_REQUEST['itemLength'];
				$_SESSION['membershipitems'][$j]['itemLengthType'] = $_REQUEST['itemLengthType'];
				
			}
		} else {
			$_SESSION['membershipitems'][0]['item'] = $_REQUEST['membershipContents'];
			$_SESSION['membershipitems'][0]['itemLength'] = $_REQUEST['itemLength'];
			$_SESSION['membershipitems'][0]['itemLengthType'] = $_REQUEST['itemLengthType'];
		}
		$act = 'addMembership';
	} else {
		$act = 'addMembership';
	}
}
if($act == 'addMembership') {
	?>
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
        <thead><tr>
          <td>Add a new membership below.</td>
        </tr></thead><tbody>
        <tr valign="top">
          <td><table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><form name="form" method="post" action="index.php">
                  <p>Membership Name
                    <input name="membershipName" type="text" size="35" value="<?php echo $_REQUEST['membershipName']; ?>">
                    <br>
                    Membership Price
                    <input name="price" type="text" size="5" value="<?php echo $_REQUEST['price']; ?>">
                    <br />
                    Membership 
                    is for every:
                    <input name="membershipLength" type="text" value="<?php if($_REQUEST['membershipLength'] == '') echo '1'; else echo $_REQUEST['membershipLength']; ?>" size="5"> 
                      <select name="membershipLengthType">
                        <option value="d" <?php if($_REQUEST['membershipLengthType'] == 'd' || $_REQUEST['membershipLengthType'] == '') echo "selected"; ?>>Day</option>
                        <?php /*<option value="w" <?php if($_REQUEST['membershipLengthType'] == 'w' || $_REQUEST['membershipLengthType'] == '') echo "selected"; ?>>Week</option>*/ ?>
                        <option value="m" <?php if($_REQUEST['membershipLengthType'] == 'm') echo "selected"; ?>>Month</option>
                        <option value="y" <?php if($_REQUEST['membershipLengthType'] == 'y') echo "selected"; ?>>Year</option>
                      </select>
                      <br />
						 <br>
Paid To Click <br />
Bonus : 
<select name="clickbonus">
  <?php echo displayPercentOptions($_REQUEST['clickbonus'],10); ?>
</select>
Timer : 
<input name="clickTimer" type="text" value="<?php echo $setupinfo['ptClickTimer']; ?>" size="6" maxlength="3">
<span class="style1">(seconds)</span><br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Read Ads <br />
Bonus : 
<select name="readadbonus">
  <?php echo displayPercentOptions($_REQUEST['readadbonus'],10); ?>
</select>
Timer :
<input name="readadTimer" type="text" value="<?php echo $setupinfo['ptReadAdTimer']; ?>" size="6" maxlength="3" />
<span class="style1">(seconds)</span><br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Sign Up <br />
Bonus : 
<select name="signupbonus">
  <?php echo displayPercentOptions($_REQUEST['signupbonus'],10); ?>
</select>
<br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Read Email <br />
Bonus : 
<select name="reademailbonus">
  <?php echo displayPercentOptions($_REQUEST['reademailbonus'],10); ?>
</select>
Timer :
<input name="reademailTimer" type="text" value="<?php echo $setupinfo['ptReadEmailTimer']; ?>" size="6" maxlength="3" />
<span class="style1">(seconds)</span><br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Take Survey's <br />
Bonus : 
<select name="takesurveybonus">
  <?php echo displayPercentOptions($_REQUEST['takesurveybonus'],10); ?>
</select>
Timer : 
<input name="takesurveyTimer" type="text" value="<?php echo $setupinfo['ptSurveyTimer']; ?>" size="6" maxlength="3" />

          <span class="style1">(seconds)</span><br />
          <span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
          <BR>
          <?php
		  //if($_REQUEST['MembershipType'] == '1' || $_REQUEST['MembershipType'] == '') {
		  ?>
                  </p>
                  <div id="packType1" style="background-color: #FFFFCC; border: 2 solid #ff0000; padding: 10px; border-color: #ff0000;" <?php if($_REQUEST['packSpecial'] == '1' || $_REQUEST['packSpecial'] == '') { ?>style="display: block;"<?php } else { echo "style=\"display: none\""; } ?>>
                    Recurring Every:
                    <select name="itemLengthType">
                      <option value="d" selected>Day</option>
                      <?php /*<option value="w">Week</option>*/ ?>
                      <option value="m">Month</option>
                      <option value="y">Year</option>
                    </select>
                    <br>
					
                    <select name="membershipContents">
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
                    <input type="submit" name="btnAddItem" value="<-- Add to Membership">
                    <br>
                    </div>
                  <input type="hidden" name="act" value="addMembership">
                  <input type="hidden" name="tp" value="membershiptypes">
<?php
				$membershipitems = $_SESSION['membershipitems'];
				$c = count($membershipitems);
				if($c > 0) { 
					$inVar = '';
					foreach($membershipitems as $k => $v) {
						$inVar .= quote_smart($v['item']).",";
						$typeArray[$v['item']] = $v['itemLengthType'];
						$lengthArray[$v['item']] = $v['itemLength'];
					}
					$inVar = substr($inVar, 0, strlen($inVar)-1);
					//echo "SELECT * FROM packages WHERE fnum IN (".$inVar.")<BR><BR>";
					$query = mysql_query("SELECT * FROM packages WHERE fnum IN (".$inVar.")");
					$count = mysql_num_rows($query);
					if($count > 0) {
						for($i = 0;$i < $count;$i++) {
							mysql_data_seek($query, $i);
							$arr = mysql_fetch_array($query);
							$id = $arr['fnum'];
							$length = $lengthArray[$id];
							$type = $typeArray[$id];
							if($type == 'd') { $disp = 'Every Day'; }
							//if($type == 'w') { $disp = 'Every Week'; }
							if($type == 'm') { $disp = 'Every Month'; }
							if($type == 'y') { $disp = 'Every Year'; }
							echo $arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'])." Value) $disp<BR>";
						}				
					}
				}
			?>
                  <p align="center">
                    <input type="submit" name="btnCreateMembership" value="Create this Membership!">
                  </p>
              </form></td>
            </tr>
          </table></td>
        </tr>
        </tbody>
    </table></td>
  </tr>
</table>
<br>
<br>
<br>
<?php
}
if($act == 'editPack') {
$query = mysql_query("SELECT * FROM membershiptypes WHERE id = ".quote_smart($_REQUEST['membershipID'])."");
$count = mysql_num_rows($query);
if($count == 0) {
	displayError("Pack not found!<BR>");
} else {
	$arr = mysql_fetch_array($query);
	extract($arr);
?><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
        <thead><tr>
          <td>Edit your membership below.</td>
        </tr>
        </thead><tbody>
        <tr valign="top">
          <td><table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><form name="form2" method="post" action="index.php">
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="55%">Membership Name </td>
                          <td width="45%"><input name="membershipName" type="text" size="35" value="<?php echo $membershipName; ?>"></td>
                        </tr>
                        <tr>
                          <td>Membership Price </td>
                          <td><input name="price" type="text" size="5" value="<?php echo $membershipPrice; ?>"></td>
                        </tr>
						<tr>
                          <td> Recurring Every </td>
                          <td><input name="membershipLength" type="text" size="5" value="<?php echo $length; ?>"> 
          <select name="membershipLengthType">
            <option value="d" <?php if($lengthType == 'd') echo "selected"; ?>>Day</option>
            <?php /* <option value="w" <?php if($lengthType == 'w') echo "selected"; ?>>Week</option> */ ?>
            <option value="m" <?php if($lengthType == 'm') echo "selected"; ?>>Month</option>
            <option value="y" <?php if($lengthType == 'y') echo "selected"; ?>>Year</option>
          </select></td>
                        </tr>
						<tr>
                          <td colspan="2">Paid To Click <br />
Bonus : 
<select name="clickbonus">
  <?php echo displayPercentOptions($clickBonus,10); ?>
</select>
Timer : 
<input name="clickTimer" type="text" value="<?php echo $clickTimer; ?>" size="6" maxlength="3">
<span class="style1">(seconds)</span><br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Read Ads <br />
Bonus : 
<select name="readadbonus">
  <?php echo displayPercentOptions($readadBonus,10); ?>
</select>
Timer :
<input name="readadTimer" type="text" value="<?php echo $readadTimer; ?>" size="6" maxlength="3" />
<span class="style1">(seconds)</span><br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Sign Up <br />
Bonus : 
<select name="signupbonus">
  <?php echo displayPercentOptions($signupBonus,10); ?>
</select>
<br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Read Email <br />
Bonus : 
<select name="reademailbonus">
  <?php echo displayPercentOptions($reademailBonus,10); ?>
</select>
Timer :
<input name="reademailTimer" type="text" value="<?php echo $reademailTimer; ?>" size="6" maxlength="3" />
<span class="style1">(seconds)</span><br />
<span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
<br>
Paid To Take Survey's <br />
Bonus : 
<select name="takesurveybonus">
 <?php echo displayPercentOptions($takesurveyBonus,10); ?>
</select>
Timer : 
<input name="takesurveyTimer" type="text" value="<?php echo $takesurveyTimer; ?>" size="6" maxlength="3" />

          <span class="style1">(seconds)</span><br />
          <span class="style1">NOTE: 110% would be a 10% bonus on original earnings.</span><br />
          <br />
</td>
                        </tr>
						
						<tr valign="top">
                          <td colspan="2"><div style="background-color: #FFFFCC; border: 2 solid #ff0000; padding: 10px; border-color: #ff0000;">Membership Contents<br>
Recurring Type:
<select name="itemLengthType">
  <option value="d" selected>Day</option>
  <?php /* <option value="w">Week</option> */?>
  <option value="m">Month</option>
  <option value="y">Year</option>
</select>
<br>
<select name="membershipContents">
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
<input type="submit" name="btnAddItem" value="<-- Add to Membership"></div></td>
                        </tr>
                      </table>
                      <p> <input type="hidden" name="act" value="addItemToMembership">
                          <input type="hidden" name="tp" value="membershiptypes">
                          <input type="hidden" name="membershipID" value="<?php echo $_REQUEST['membershipID']; ?>">
                          <?php
				/*$membershipitems = $_SESSION['membershipitems'];
				$c = count($membershipitems);
				if($c > 0) { 
					$inVar = '';
					foreach($membershipitems as $k => $v) $inVar .= quote_smart($v).",";
					$inVar = substr($inVar, 0, strlen($inVar)-1);
					echo "SELECT * FROM Memberships WHERE fnum IN (".$inVar.")<BR><BR>";
					$query = mysql_query("SELECT * FROM Memberships WHERE fnum IN (".$inVar.")");
					$count = mysql_num_rows($query);
					if($count > 0) {
						for($i = 0;$i < $count;$i++) {
							mysql_data_seek($query, $i);
							$arr = mysql_fetch_array($query);
							echo $arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'])." Value) <a href=\"index.php?tp=membershiptypes&act=removeItemFromPack&membershipID=".$_REQUEST['membershipID']."&itemID=".$arr['id']."\">Remove</a><BR>";
						}				
					}
				}*/
				$query = mysql_query("SELECT * FROM membershipitems, packages WHERE membershipitems.membershipID = ".quote_smart($id)." AND packages.fnum = membershipitems.itemID");
				$count = mysql_num_rows($query);
				if($count > 0) {
					for($i = 0;$i < $count;$i++) {
						mysql_data_seek($query, $i);
						$arr = mysql_fetch_array($query);
						echo $arr['pack_name']." (".$setupinfo['currency'].number_format($arr['pack_price'])." Value) ";
						if($arr['itemLengthType'] == 'd') {
							$disp = 'Every Day';
						} else if($arr['itemLengthType'] == 'w') {
							$disp = 'Every Week';
						} else if($arr['itemLengthType'] == 'm') {
							$disp = 'Every Month';
						} else if($arr['itemLengthType'] == 'y') {
							$disp = 'Every Year';
						}
						echo $disp;
						echo " <a href=\"index.php?tp=membershiptypes&act=removeItemFromPack&membershipID=".$id."&itemID=".$arr['id']."\" title=\"Delete Item from Membership\" class=\"tooltip\"><img src=\"../images/icons/cross-circle.png\" border=\"0\"></a><BR>";
					}	
				} else {
					echo "No items found for this Membership.<BR>";
				}
			?>
                      </p>
                      <p align="center">
                        <input type="submit" name="btnUpdateMembership" value="Update this Membership!">
                      </p>
                  </form></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
        </tbody>
    </table></td>
  </tr>
</table>
<?php
	} //PACK COUNT > 0
}
if($act == '') {
	unset($_SESSION['membershipitems']);
	$query = mysql_query("SELECT * FROM membershiptypes WHERE membershipPrice != '0.00' ORDER BY active,id DESC") or die(mysql_error());
	$count = mysql_num_rows($query);
	if($count > 0) {
	?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
		<td><div class="hastable_disabled"><table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
		  <thead><tr>
		  	<td>&nbsp;<input type="checkbox" name="checkall" value="yes"/></td>
            <td>Membership</td>
            <td>Price</td>
            <td>Renews</td>
            <td><div align="right">Options</div></td>
		  </tr>
          </thead><tbody>
		  <?php
	  for($i = 0;$i < $count;$i++) {
		mysql_data_seek($query, $i);
		$arr = mysql_fetch_array($query);
	  ?>
		  <tr valign="top">
		  	<td>&nbsp;<input type="checkbox" name="checkall" value="yes"/></td>
            <td><a href="index.php?tp=membershiptypes&act=editPack&membershipID=<?php echo $arr['id']; ?>"><?php echo $arr['membershipName']; ?></a> (<?php echo getValue("SELECT COUNT(id) FROM membershipitems WHERE membershipID = ".quote_smart($arr['id']).""); ?> Items)</td>
            <td><?php echo $setupinfo['currency'].number_format($arr['membershipPrice'],2); ?></td>
            <td><?php echo "Every ".$arr['length']." ";
			if($arr['lengthType'] == 'd') {
				echo 'Day';
			} else if($arr['lengthType'] == 'w') {
				echo 'Week';
			} else if($arr['lengthType'] == 'm') {
				echo 'Month';
			} else if($arr['lengthType'] == 'y') {
				echo 'Year';
			} ?></td>
            <td><div align="right"><a href="index.php?tp=membershiptypes&act=editPack&membershipID=<?php echo $arr['id']; ?>" title="Edit Membership" class="tooltip"><img src="../images/icons/pencil-color.png" border="0" align="absmiddle" alt="Edit Membership"/></a> 
                  <?php
            if($arr['active'] == '1') {
				?>
              <a href="index.php?tp=membershiptypes&act=suspend&membershipID=<?php echo $arr['id']; ?>" title="Suspend Membership" class="tooltip"><img src="../images/icons/status.png" border="0" align="absmiddle" alt="Suspend Membership"/></a>
              <?php
			} else {
				?>
              <a href="index.php?tp=membershiptypes&act=unsuspend&membershipID=<?php echo $arr['id']; ?>" title="Re-Activate Membership" class="tooltip"><img src="../images/icons/status-busy.png" border="0" align="absmiddle" alt="Re-Activate Membership"/></a>
              <?php
			}
			
			?>
            </div></td>
		    <?php
			/*$q = mysql_query("SELECT b.pack_name, b.pack_price, a.itemLength, a.itemLengthType FROM membershipitems a, packages b WHERE a.itemID = b.fnum AND a.membershipID = ".quote_smart($arr['id'])."");
			$c = mysql_num_rows($q);
			if($c > 0) {
				for($k = 0;$k < $c;$k++) {
					mysql_data_seek($q,$k);
					$ar = mysql_fetch_array($q);
					if($ar['itemLengthType'] == 'd') {
						$disp = 'Every Day';
					} else if($ar['itemLengthType'] == 'w') {
						$disp = 'Every Week';
					} else if($ar['itemLengthType'] == 'm') {
						$disp = 'Every Month';
					} else if($ar['itemLengthType'] == 'y') {
						$disp = 'Every Year';
					}
					?><?php
                    echo $ar['pack_name']." (".$setupinfo['currency'].number_format($ar['pack_price'],2)." Value) $disp";
				}
			} else {
				echo "There are no items in this membership...<BR><a href=\"index.php?tp=membershiptypes&act=addItem&membershipID=".$arr['id']."\">Add a new item.</a>";
			}
			*/
		?>
		  </tr>
		  <?php
	  } //END FOR LOOP
	  ?></tbody>
		</table>
		</div></td>
	  </tr>
</table>
	<?php
	} else {//END IF COUNT($Memberships > 0)
		?>
	<div align="center"><br>
	  <br>
	<?php displayError('There are no memberships available.'); ?>
	<br>
	</div>
	<?php
	}
	?><BR><div align="center"><br>
	  <strong>To add a new membership, <a href="index.php?tp=membershiptypes&act=addMembership">click here</a>.</strong><br>
	</div>
	<?php
} //END IF $act == ''
?>