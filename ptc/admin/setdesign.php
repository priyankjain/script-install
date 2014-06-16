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

$act = $_REQUEST['act'];

if($act=='chset') {
	if(getValue("SELECT COUNT(`name`) FROM `design` WHERE `name` = ".quote_smart($_REQUEST['dID'])."") > 0) {
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
		mysql_query("UPDATE design SET subject = ".quote_smart($_REQUEST['subject']).", value=".quote_smart($_REQUEST['value'])." WHERE name=".quote_smart($_REQUEST['dID'])."");
}
		displaySuccess("Updated design content.");
	} else {
		displayError("Name not found. Cannot save.");
	}

}



?>

<script language="JavaScript" type="text/JavaScript">

<!--

function MM_jumpMenu(targ,selObj,restore){ //v3.0

  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");

  if (restore) selObj.selectedIndex=0;

}

//-->

</script>
<form name="myform" action="index.php" method="post">

<input type="hidden" name="tp" value="setdesign">

<?php if(isset($_REQUEST['dID'])) { ?><input type="hidden" name="dID" value="<?php echo $_REQUEST['dID']; ?>"><?php } ?>
<h2>DESIGN AND CONTENT SETTINGS</h2>
  <table width="100%" border="0" cellpadding="5">


	<?php
	
	
	if($_REQUEST['dID'] != '') {
	
		$dID = $_REQUEST['dID'];
	
		$sql = "select `name`, `comments`, `value`,`subject` from `design` where `name`=".quote_smart($dID)."";
	
		$query = mysql_query($sql);
	
		$count = mysql_num_rows($query);
	
		if($count == 0) {
	
			mysql_query("INSERT INTO `design` ( `name` ,
	
			`comments` ,
	
			`value` ,
	
			`subject` ,
	
			`templateID` ,
	
			`templateName`
	
			)
	
			VALUES (
	
			".quote_smart($dID).", '', '', '', '0', 'default'
	
			)");
	
			$sql = "select `name`, `comments`, `value`,`subject` from `design` where `name`=".quote_smart($dID)."";
	
			$query = mysql_query($sql);
	
			$count = mysql_num_rows($query);
	
		}
	
		if($count > 0) {
	
			$arr = mysql_fetch_array($query); ?>
	
		
	
		<tr valign="top"> 
	
	
		  <td width="72%"> 
	
			<div align="center"><br />
				<br />
			  <?php echo $arr['comments']?><br />
              <br />
              <?php
			  if(substr($arr['comments'],0,13) == 'Virtual Page:') {
			  	?>
              This page's URL: <a href="http://<?php echo $ptrurl; ?>/index.php?tp=<?php echo $arr['name']; ?>" target="blank">http://<?php echo $ptrurl; ?>/index.php?tp=<?php echo $arr['name']; ?></a><br />
                <br />
              <?php
			  }
			  ?>
				Subject: <input name="subject" type="text" value="<?php echo $arr['subject']; ?>" size="50" maxlength="255" />
				<br />
				<br />
	<textarea name="value" rows="15" cols="75" class="wysiwyg"><?php echo $arr['value']; ?></textarea>
				<br />
				<br />
			</div></td>
		</tr>
	
    <tr valign="top"> 

      <td align="right"> 

        <div align="center"> 

          <input type="submit" name="Submit" value="Change settings">

          <br />

          <br />
        </div>      </td>
    </tr>
		<?php
	
		}
	} //END IF $_REQUEST['dID'] != ''
	?>

    <tr valign="top"> 

      <td width="28%" align="right"> 

        <input type="hidden" name="tp" value="setdesign">

        <input type="hidden" name="act" value="chset">      </td>

    </tr>


    <tr valign="top">

      <td align="right"><div align="center">

        <p>
          E-Mails
          <select name="menu2" onchange="MM_jumpMenu('parent',this,0)">
            <option value="#" selected="selected">Choose the email you wish to modify</option>
            <option value="index.php?tp=setdesign&amp;dID=ptrHeader">Content: Paid to Read Email Header</option>
            <option value="index.php?tp=setdesign&amp;dID=ptrFooter">Content: Paid to Read Email Footer</option>
            <option value="index.php?tp=setdesign&amp;dID=emailRefContest">Content: Won Referral Contest Email</option>
            <option value="index.php?tp=setdesign&amp;dID=emailWelcome">Content: New Member Welcome Email</option>
            <option value="index.php?tp=setdesign&amp;dID=emailDebit">Content: Debit to users account Email</option>
            <option value="index.php?tp=setdesign&amp;dID=emailCredit">Content: Credit to users account Email</option>
            <option value="index.php?tp=setdesign&amp;dID=emailOrderThankyou">Content: Thanks for your order Email</option>
          </select>
          <br />
          <br />
          Site Pages
          <select name="menu2" onchange="MM_jumpMenu('parent',this,0)">
            <option value="#" selected="selected">Choose the content you wish to modify</option>
            <option value="index.php?tp=setdesign&amp;dID=logo">Content: Site Logo</option>
            <option value="index.php?tp=setdesign&amp;dID=homepage">/home.php Home Page </option>
            <option value="index.php?tp=setdesign&amp;dID=howtoearn">/howtoearn.php How To Earn </option>
            <option value="index.php?tp=setdesign&amp;dID=faq">/faq.php Frequently Asked Questions </option>
            <option value="index.php?tp=setdesign&amp;dID=terms">/terms.php Terms and Conditions </option>
            <option value="index.php?tp=setdesign&amp;dID=homeright">/sidebar.php Sidebar Content </option>
            <option value="index.php?tp=setdesign&amp;dID=news">/news.php News</option>
            <?php
			$sql = mysql_query("SELECT * from `design` WHERE `comments` LIKE 'Virtual Page:%'");
			$count = mysql_num_rows($sql);
			if($count > 0) {
				for($i = 0;$i < $count;$i++) {
					mysql_data_seek($sql, $i);
					$arr = mysql_fetch_array($sql);
					?>
					<option value="index.php?tp=setdesign&dID=<?php echo $arr['name']; ?>"><?php echo $arr['comments']; ?></option>
					<?php
				}
			}
			?>
          </select>
</p>

        <p>&nbsp; </p>

      </div></td>
    </tr>
  </table>

</form>

