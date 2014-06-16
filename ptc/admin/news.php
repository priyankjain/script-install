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
loginCheck();
$act = $_REQUEST['act'];
if($act == 'submitAdd') {
	if($_REQUEST['title'] != '' && strlen($_REQUEST['title']) > 5) {
		if($_REQUEST['article'] != '' && strlen($_REQUEST['article']) > 5) {
			if($demoMode === TRUE) {
				echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
			} else {	
				mysql_query("INSERT INTO newsarchive (newsDate, article, title) VALUES (NOW(), ".quote_smart($_REQUEST['article']).",".quote_smart($_REQUEST['title']).")");
				if(mysql_affected_rows()) {
					displaySuccess("Your article has been added!");
					$act = '';
				} else {
					displayError("Your article could not be added.");
					$act = '';
				}
			} //END DEMO MODE
		} else	{
			displayError("Your article must be greater than 5 characters long.");
			$act = 'add';
		}
	} else {
		displayError("Your title must be greater than 5 characters long.");
		$act = 'add';
	}
}
if($act == 'add') {
	?><table width="600" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#666666">
  <tr>
    <td><table width="600" border="0" align="center" cellpadding="5" cellspacing="1">
        <tr bgcolor="#CECECE">
          <td width="600" bgcolor="#ECECEC"><strong>News Articles </strong></td>
        </tr>
        <tr valign="top" bgcolor="#FFFFFF">
          <td><form name="form1" method="post" action="">
			<input type="hidden" name="tp" value="news">
			 <input type="hidden" name="act" value="submitAdd"><p>
              <input name="title" type="text" value="<?php echo $_REQUEST['title']; ?>" size="45" maxlength="255">
          (title)<br>
          <br>
          Article.
          <BR>
          <textarea name="article" cols="65" rows="10" class="wysiwyg"><?php echo $_REQUEST['article']; ?></textarea>
          </p>
            
              <input type="submit" name="Submit" value="Submit">
            </form>            <p>&nbsp;            </p></td>
        </tr>
    </table></td>
  </tr>
</table>
<?php
}
if($act == 'submitEdit' && $_REQUEST['id'] != '') {
	if(getCount("SELECT COUNT(id) FROM newsarchive WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		if(strlen($_REQUEST['title']) > 5) {
			$query = mysql_query("UPDATE newsarchive SET article = ".quote_smart($_REQUEST['article']).", title = ".quote_smart($_REQUEST['title']).", newsDate = ".quote_smart($_REQUEST['newsDate'])." WHERE id = ".quote_smart($_REQUEST['id'])."");
			displaySuccess("Article Updated.");
			$act = '';
		} else {
			displayError("Your news title must be greater than 5 characters...");
			$act = 'edit';
		}
	} else {
		displayError("Article ID Missing or invalid.");
		$act = 'edit';
	}

}
if($act == 'edit' && $_REQUEST['id'] != '') {
	if(getCount("SELECT COUNT(id) FROM newsarchive WHERE id = ".quote_smart($_REQUEST['id'])."", "COUNT") > 0) {
		?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#666666">
  <tr>
    <td><table width="600" border="0" align="center" cellpadding="5" cellspacing="1">
        <tr bgcolor="#CECECE">
          <td width="600" bgcolor="#ECECEC"><strong>News Articles </strong></td>
        </tr>
        <?php
		$query = mysql_query("SELECT title, article, id, newsDate FROM newsarchive WHERE id = ".quote_smart($_REQUEST['id'])."");
		$count = mysql_num_rows($query);
		if($count == 0) {
			echo "<TR><TD>There are no news articles in this system.<BR></TD></TR>";
		} else {
			$arr = mysql_fetch_array($query);
			?>
        <tr valign="top" bgcolor="#FFFFFF">
          <td><fieldset><form name="form1" method="post" action="">
			<input type="hidden" name="tp" value="news">
			 <input type="hidden" name="act" value="submitEdit"><p>
              <input type="hidden" name="id" value="<?php echo $arr['id']; ?>">
              <input name="newsDate" type="text" value="<?php echo $arr['newsDate']; ?>" size="15" maxlength="10">
              (date) 
          -
              <input name="title" type="text" value="<?php echo $arr['title']; ?>" size="45" maxlength="255">
          (title)<br>
          <br>
          Article.
          <BR>
              <textarea name="article" cols="65" rows="10" class="wysiwyg"><?php echo $arr['article']; ?></textarea>
			 </p>
			 <input type="submit" name="Submit" value="Submit">
            </form></fieldset>            <p>&nbsp;            </p></td>
        </tr>
        <?php
		} //END COUNT > 0
	  ?>
    </table></td>
  </tr>
</table>
<?php
	} else {
		displayError("There are no articles with this id.");
		$act = '';
	}
}

if($act == '') {
?>
<p><a href="index.php?tp=news&act=add"><img src="../images/icons/plus-circle.png" border="0" align="absmiddle"/> Add New Article </a></p>
<table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
        <tr>
          <td><strong>News Articles </strong></td>
        </tr>
        <?php
		$query = mysql_query("SELECT title, article, id, newsDate FROM newsarchive ORDER BY newsDate DESC");
		$count = mysql_num_rows($query);
		if($count == 0) {
			echo "<TR><TD>There are no news articles in this system.<BR></TD></TR>";
		} else {
			for($i = 0;$i < $count;$i++) {
				mysql_data_seek($query, $i);
				$arr = mysql_fetch_array($query);
				?>
				<tr valign="top">
				  <td><a href="index.php?tp=news&act=edit&id=<?php echo $arr['id']; ?>"><STRONG><?php echo $arr['newsDate']; ?> - <?php echo $arr['title']; ?></STRONG></a>
				  
				  <BR><?php echo $arr['article']; ?></td>
				</tr>
				<?php
			} //END FOR LOOP
		} //END COUNT > 0
	  ?>
    </table></td>
  </tr>
</table>
<p><a href="index.php?tp=news&act=add"><img src="../images/icons/plus-circle.png" border="0" align="absmiddle"/> Add New Article </a></p>
<?php
}//END IF act == ''
?>