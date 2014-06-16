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
 loginCheck(); ?><style type="text/css">
<!--
.style5 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
.style8 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<?php
if($_REQUEST['act'] == 'addNewMenu') {
	mysql_query("INSERT INTO menus (`menuName`,`menuType`,`sortOrder`,`menuAction`) VALUES (".quote_smart($_REQUEST['menuName']).",".quote_smart($_REQUEST['menuType']).",".quote_smart($_REQUEST['sortOrder']).",".quote_smart($_REQUEST['menuAction']).")") or die(mysql_error());
	if(mysql_affected_rows()) {
		displaySuccess("Menu has been added.");
	}
}
if($_REQUEST['act'] == 'addMenu') {
		?><h2>Add a new menu</h2>
		<form action="index.php" method="post" name="form">
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>Menu Name</td>
    <td><input type="text" name="menuName" value=""></td>
  </tr>
  <tr>
    <td>Menu Action / URL</td>
    <td><input type="text" name="menuAction" value=""></td>
  </tr>
  <tr>
    <td>Menu Type</td>
    <td><select name="menuType"><option value="website">Main Website Menu</option><option value="member">Members BackOffice Menu</option></select></td>
  </tr>
  <tr>
    <td>Sort Order</td>
    <td><input type="number" name="sortOrder" value=""></td>
  </tr>
</table>
<p>
  <input type="hidden" name="act" value="addNewMenu">
  <input type="hidden" name="tp" value="menuEditor">
</p>
<p>
  <input type="submit" name="Submit" value="Submit">
</p>
		</form>
		<?php
}



if($_REQUEST['act'] == 'updateMenu') {
	if(getCount("SELECT COUNT(id) FROM menus WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		mysql_query("UPDATE menus SET menuName = ".quote_smart($_REQUEST['menuName']).", menuType = ".quote_smart($_REQUEST['menuType']).", sortOrder = ".quote_smart($_REQUEST['sortOrder']).", menuAction = ".quote_smart($_REQUEST['menuAction'])." WHERE id = ".quote_smart($_REQUEST['id'])."");
		if(mysql_affected_rows()) {
			displaySuccess("Menu has been updated.");
		}
	} else {
		displayError("Id Not Found!");
	}
}
if($_REQUEST['act'] == 'editMenu') {
	if(getCount("SELECT COUNT(id) FROM menus WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		$arr = getArray("SELECT * FROM menus WHERE id = ".quote_smart($_REQUEST['id'])."");
		?><h2>Update menu</h2>
		<form action="index.php" method="post" name="form">
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>Menu Name</td>
    <td><input type="text" name="menuName" value="<?php echo $arr['menuName']; ?>"></td>
  </tr>
  <tr>
    <td>Menu Action / URL</td>
    <td><input type="text" name="menuAction" value="<?php echo $arr['menuAction']; ?>"></td>
  </tr>
  <tr>
    <td>Menu Type</td>
    <td><select name="menuType"><option value="website" <?php if($arr['menuType'] == 'website') echo "selected"; ?>>Main Website Menu</option><option value="member" <?php if($arr['menuType'] == 'member') echo "selected"; ?>>Members BackOffice Menu</option>
    </select></td>
  </tr>
  <tr>
    <td>Sort Order</td>
    <td><input type="number" name="sortOrder" value="<?php echo $arr['sortOrder']; ?>"></td>
  </tr>
</table>
<p>
  <input type="hidden" name="act" value="updateMenu">
  <input type="hidden" name="tp" value="menuEditor">
  <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
</p>
<p>
  <input type="submit" name="Submit2" value="Submit">
</p>
		</form>
		<?php
	} else {
		displayError("Invalid Menu.");
	}
}


if($_REQUEST['act'] == 'confirmDeleteNow') {
	if(getCount("SELECT COUNT(id) FROM menus WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		mysql_query("DELETE FROM menus WHERE id = ".quote_smart($_REQUEST['id'])." LIMIT 1") or die(mysql_error()." while running delete query.");
		displaySuccess("Menu has been deleted.");
	} else {
		displayError("Invalid Menu.");
	}
}

if($_REQUEST['act'] == 'confirmDelete') {
	if(getCount("SELECT COUNT(id) FROM menus WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		?><BR><BR><a href="index.php?tp=menuEditor&act=confirmDeleteNow&id=<?php echo $_REQUEST['id']; ?>">Click Here to remove this menu permanently.</a><BR><BR><?php
	} else {
		displayError("Invalid Menu.");
	}
}
?>
<h2>View Menu Options</h2>
<table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <td width="94">Name</td>
    <td width="109">Action</td>
    <td width="93">Type</td>
    <td width="122">Options</td>
  </tr>
  </thead>
  <tbody>
  <?php
	$query = mysql_query("SELECT * FROM menus ORDER BY menuType,menuName ASC");
	$count = mysql_num_rows($query);
	if($count > 0) {
	  for($i = 0;$i < $count;$i++) {
		mysql_data_seek($query, $i);
		$arr = mysql_fetch_array($query);
	  ?>
  <tr bgcolor="#FFFFFF">
    <td><span class="style8"><?php echo $arr['menuName']; ?></span></td>
    <td><span class="style8"><?php echo $arr['menuAction']; ?></span></td>
    <td><span class="style8"><?php echo $arr['menuType']; ?></span></td>
    <td><span class="style8"><a href="index.php?tp=menuEditor&act=editMenu&id=<?php echo $arr['id']; ?>">Edit</a> | <a href="index.php?tp=menuEditor&act=confirmDelete&id=<?php echo $arr['id']; ?>">Delete</a></span></td>
  </tr>
  <?php
	  }
	} else {
		echo "<tr><td colspan=\"6\">There are currently no menus.</td></tr>";
	}
	?>
    </tbody>
</table>
<p align="center"><span class="style8"><a href="index.php?tp=menuEditor&act=addMenu">Add New Menu </a></span> </p>
