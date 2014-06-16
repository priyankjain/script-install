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
.style8 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<?php
if($_REQUEST['act'] == 'addAction' && $_REQUEST['toDo'] == 'addNow') {
	
	if($_REQUEST['pageName'] == '') $error = 'Must include page name...';
	if($_REQUEST['tpValue'] == '') $error = 'Must include tp / action...';
	if(getValue("SELECT COUNT(id) FROM siteactions WHERE tp = ".quote_smart($_REQUEST['tpValue'])." AND actionType = ".quote_smart($_REQUEST['actionType'])) > 0) $error = 'This page (tp / action) already exists.';
	
	if($error) {
		displayError($error);
	} else {
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		//INSERT INTO siteactions
		mysql_query("INSERT INTO siteactions (
			`tp`,
			`incPage`,
			`actionType`,
			`virtualPage`,
			`isStandalonePage`,
			`hasPreInclude`,
			`pageName`
		) VALUES (
			".quote_smart($_REQUEST['tpValue']).",
			".quote_smart($_REQUEST['incPage']).",
			".quote_smart($_REQUEST['actionType']).",
			".quote_smart($_REQUEST['virtualPage']).",
			".quote_smart($_REQUEST['isStandalonePage']).",
			".quote_smart($_REQUEST['hasPreInclude']).",
			".quote_smart($_REQUEST['pageName'])."
		)");
		if($_REQUEST['virtualPage'] == '1') {
			//INSERT INTO design
			mysql_query("INSERT INTO `design` ( `name` ,
			`comments` ,
			`value` ,
			`subject` ,
			`templateID` ,
			`templateName`
			) VALUES (
			".quote_smart($_REQUEST['tpValue']).", ".quote_smart("Virtual Page: ".$_REQUEST['pageName']).", '', '', '0', 'default'
			)") or die(mysql_error());
		}
} //END DEMO MODE
		displaySuccess("Menu has been added. You may now update the new content of this page in the <a href=\"index.php?tp=setdesign\">\"Website Design\"</a> area of your admin panel.");
		$_REQUEST['act'] = '';
	}
}
if($_REQUEST['act'] == 'addAction') {
		?>
<h2>Add a Page / Site Action</h2>
		<form action="index.php" method="post" name="form">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="148" valign="top">Page Name</td>
    <td width="727" valign="top"><input type="text" name="pageName" value="<?php echo $_REQUEST['pageName']; ?>"></td>
  </tr>
  <tr>
    <td valign="top">TP / Action name</td>
    <td valign="top"><input type="text" name="tpValue" value="<?php echo $_REQUEST['tpValue']; ?>">
      <br />
    This will decide your url, ie http://www.site.com/index.php?tp=<strong>actionName</strong> , NO Spaces or Symbols!</td>
  </tr>
  <tr>
    <td valign="top">File Name</td>
    <td valign="top"><input type="text" name="incPage" value="<?php echo $_REQUEST['incPage']; ?>">
      <br />
    File to include. IE: manageads.php will include /templates/default/manageads.php</td>
  </tr>
  <tr>
    <td valign="top">Page  Type</td>
    <td valign="top"><select name="actionType"><option value="website">Main Website Page</option><option value="member">Members BackOffice Page</option></select></td>
  </tr>
  <tr>
    <td valign="top">Is Virtual Page?</td>
    <td valign="top"><input type="checkbox" name="virtualPage" id="virtualPage" value="1" <?php if($_REQUEST['virtualPage'] == '1') echo "checked"; ?> /> 
      (Doesn't include main site file. Only output's &quot;Design&quot; from database)</td>
  </tr>
  <tr>
    <td valign="top">Is Standalone Page?</td>
    <td valign="top"><input type="checkbox" name="isStandalonePage" id="isStandalonePage" value="1" <?php if($_REQUEST['isStandalonePage'] == '1') echo "checked"; ?> /> 
      (Doesn't include header and footer if checked.)</td>
  </tr>
  <tr>
    <td valign="top">Has Pre-Include File?</td>
    <td valign="top"><input type="checkbox" name="hasPreInclude" id="hasPreInclude" value="1" <?php if($_REQUEST['hasPreInclude'] == '1') echo "checked"; ?> /> 
      (File Name must be in the /preIncludes/fileName.php folder.)</td>
  </tr>
</table>
<p>NOTE: Virtual Pages will be added to the design editor for you to edit the content after you have created the page.</p>
<p>
  <input type="hidden" name="act" value="addAction">
  <input type="hidden" name="toDo" value="addNow">
  <input type="hidden" name="tp" value="siteactions">
</p>
<p>
  <input type="submit" name="Submit" value="Submit">
</p>
		</form>
		<?php
}



if($_REQUEST['act'] == 'updateAction' && $_REQUEST['toDo'] == 'updateNow') {
	if(getCount("SELECT COUNT(id) FROM siteactions WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		mysql_query("UPDATE siteactions SET
		tp = ".quote_smart($_REQUEST['tpValue']).",
		incPage = ".quote_smart($_REQUEST['incPage']).",
		actionType = ".quote_smart($_REQUEST['actionType']).",
		virtualPage = ".quote_smart($_REQUEST['virtualPage']).",
		isStandalonePage = ".quote_smart($_REQUEST['isStandalonePage']).",
		hasPreInclude = ".quote_smart($_REQUEST['hasPreInclude']).",
		pageName = ".quote_smart($_REQUEST['pageName'])."
		
		WHERE id = ".quote_smart($_REQUEST['id'])."");
		if(mysql_affected_rows()) {
			displaySuccess("Site Action has been updated.");
		}
		if($_REQUEST['virtualPage'] == '1' && getCount("SELECT COUNT(`name`) FROM `design` WHERE `name` = ".quote_smart($_REQUEST['tpValue'])." AND `comments` LIKE 'Virtual Page:%'", "COUNT") == 0) {
			//INSERT INTO design
			mysql_query("INSERT INTO `design` ( `name` ,
			`comments` ,
			`value` ,
			`subject` ,
			`templateID` ,
			`templateName`
			) VALUES (
			".quote_smart($_REQUEST['tpValue']).", ".quote_smart("Virtual Page: ".$_REQUEST['pageName']).", '', '', '0', 'default'
			)") or die(mysql_error());
		}
	} else {
		displayError("Id Not Found!");
	}
}
if($_REQUEST['act'] == 'updateAction') {
	if(getCount("SELECT COUNT(id) FROM siteactions WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		$arr = getArray("SELECT * FROM siteactions WHERE id = ".quote_smart($_REQUEST['id'])."");
		?>
<h2>Update Page / Site Action</h2>
		<form action="index.php" method="post" name="form">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="148" valign="top">Page Name</td>
    <td width="727" valign="top"><input type="text" name="pageName" value="<?php echo $arr['pageName']; ?>" /></td>
  </tr>
  <tr>
    <td valign="top">TP / Action name</td>
    <td valign="top"><input type="text" name="tpValue" value="<?php echo $arr['tp']; ?>" />
        <br />
      This will decide your url, ie http://www.site.com/index.php?tp=<strong>actionName</strong> , NO Spaces or Symbols!</td>
  </tr>
  <tr>
    <td valign="top">File Name</td>
    <td valign="top"><input type="text" name="incPage" value="<?php echo $arr['incPage']; ?>" />
        <br />
      File to include. IE: manageads.php will include /templates/default/manageads.php</td>
  </tr>
  <tr>
    <td valign="top">Page  Type</td>
    <td valign="top"><select name="actionType">
      <option value="website">Main Website Page</option>
      <option value="member">Members BackOffice Page</option>
    </select></td>
  </tr>
  <tr>
    <td valign="top">Is Virtual Page?</td>
    <td valign="top"><input type="checkbox" name="virtualPage" id="virtualPage" value="1" <?php if($arr['virtualPage'] == '1') echo "checked"; ?> />
      (Doesn't include main site file. Only output's &quot;Design&quot; from database)</td>
  </tr>
  <tr>
    <td valign="top">Is Standalone Page?</td>
    <td valign="top"><input type="checkbox" name="isStandalonePage" id="isStandalonePage" value="1" <?php if($arr['isStandalonePage'] == '1') echo "checked"; ?> />
      (Doesn't include header and footer if checked.)</td>
  </tr>
  <tr>
    <td valign="top">Has Pre-Include File?</td>
    <td valign="top"><input type="checkbox" name="hasPreInclude" id="hasPreInclude" value="1" <?php if($_REQUEST['hasPreInclude'] == '1') echo "checked"; ?> />
      (File Name must be in the /preIncludes/fileName.php folder.)</td>
  </tr>
</table>
<p>
  <input type="hidden" name="act" value="updateAction">
  <input type="hidden" name="toDo" value="updateNow">
  <input type="hidden" name="tp" value="siteactions">
  <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
</p>
<p>
  <input type="submit" name="Submit" value="Submit">
</p>
		</form>
		<p>
		  <?php
	} else {
		echo "Invalid Menu.<BR><BR>";
	}
}


if($_REQUEST['act'] == 'confirmDeleteNow') {
	if(getCount("SELECT COUNT(id) FROM siteactions WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		$action = getArray("SELECT virtualPage,tp FROM siteactions WHERE id = ".quote_smart($_REQUEST['id'])."");
		mysql_query("DELETE FROM siteactions WHERE id = ".quote_smart($_REQUEST['id'])." LIMIT 1");
		if($action['virtualPage'] == '1') mysql_query("DELETE FROM `design` WHERE `name` = ".quote_smart($action['tp'])." AND `comments` LIKE 'Virtual Page:%'");
		displaySuccess("Site Action has been deleted.");
	} else {
		displayError("Invalid Site Action.");
	}
}

if($_REQUEST['act'] == 'confirmDelete') {
	if(getCount("SELECT COUNT(id) FROM siteactions WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		?>
		  <BR>
		  <BR>
<a href="index.php?tp=siteactions&act=confirmDeleteNow&id=<?php echo $_REQUEST['id']; ?>">Click Here to remove this site action permanently.</a></p>
		<p>It is only safe to modify Virtual Pages unless you understand the risks of removing a default page. Virtual pages being defined as a custom MySQL page, and default page being defined as a hard coded file (Such as home.php / Home)<BR>
		  <BR>
		  <?php
	} else {
		displayError("Invalid Site Action.");
	}
}
?>
                </p>
<h2>Website Pages and Actions</h2>
<div align="right"><a href="index.php?tp=siteactions&amp;act=addAction">Add New Site Page / Action</a><br />
  <br />
</div>
<table border="0" cellpadding="0" cellspacing="0" class="fullwidth">
  <thead>
  <tr>
    <td><strong>Name</strong></td>
    <td><strong>Action(tp)</strong></td>
    <td><strong>Page For</strong></td>
    <td><strong>File</strong></td>
    <td><strong>Virtual Page</strong></td>
    <td><strong>Exclude Header/Footer</strong></td>
    <td><strong>Has Pre-Include</strong></td>
    <td><strong>Options</strong></td>
  </tr>
  </thead>
  <tbody>
  <?php
	$query = mysql_query("SELECT * FROM siteactions ORDER BY actionType,pageName ASC");
	$count = mysql_num_rows($query);
	if($count > 0) {
	  for($i = 0;$i < $count;$i++) {
		mysql_data_seek($query, $i);
		$arr = mysql_fetch_array($query);
	  ?>
  <tr bgcolor="#FFFFFF">
    <td><span class="style8"><?php echo $arr['pageName']; ?></span></td>
    <td><span class="style8"><?php echo $arr['tp']; ?></span></td>
    <td><span class="style8"><?php echo $arr['actionType']; ?></span></td>
    <td><span class="style8"><?php echo $arr['incPage']; ?></span></td>
    <td><span class="style8"><?php echo $arr['virtualPage']; ?></span></td>
    <td><span class="style8"><?php echo $arr['isStandalonePage']; ?></span></td>
    <td><span class="style8"><?php echo $arr['hasPreInclude']; ?></span></td>
    <td><span class="style8"><a href="index.php?tp=siteactions&act=updateAction&id=<?php echo $arr['id']; ?>">Edit</a> | <a href="index.php?tp=siteactions&act=confirmDelete&id=<?php echo $arr['id']; ?>">Delete</a></span></td>
  </tr>
  <?php
	  }
	} else {
		echo "<tr><td colspan=\"6\">There are currently no site actions (This is a bad thing....).</td></tr>";
	}
	?>
    </tbody>
</table>
<p>If Virtual is enabled, only content from the design entry (database) will be included regardless of the file value.</p>
<p>Action / tp stands for the page that is called. IE: http://<?php echo $ptrurl; ?>/index.php?tp=<strong>Action</strong></p>

<p>NOTE: Removing default pages can have unexpected results as links to those pages may still exist, and a &quot;Page Not Found&quot; error will be displayed. </p>
<p>&nbsp;</p>
<p align="center"><span class="style8"><a href="index.php?tp=siteactions&act=addAction">Add New Site Page / Action</a></span> </p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
