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
 
$language = $_REQUEST['language'];

if($_REQUEST['act'] == 'updateLanguages') {
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
	foreach($_REQUEST['key'] as $k => $v) {
		mysql_query("UPDATE translations SET translationValue = ".quote_smart($_REQUEST['value'][$k])." WHERE id = ".quote_smart($k)."") or die(mysql_error());
	}
}
	echo "Completed update<BR>";
}
if(isset($_REQUEST['enableLang']) && is_array($_REQUEST['enableLang']) && count($_REQUEST['enableLang']) >0) {
if($demoMode === TRUE) {
	echo"<b><font size=4 color= red>Disabled in DEMO MODE!</font></b>";
} else {
	foreach($_REQUEST['enableLang'] as $k => $v) {
		mysql_query("UPDATE languages SET enabled = ".quote_smart($v)." WHERE language = ".quote_smart($k)."");
	}
}
	echo "Completed update<BR>";
}
 ?>
<form action="index.php" method="post"> <input type="hidden" name="tp" value="translateeditor" />
   <div style="width: 400px; margin-left: 20px; padding: 10px 10px 10px 10px;"> 
<h2>Edit Language Content</h2><br />
Choose Your Language: <select name="language">
        <?php 
	$languages = array( 	"auto" => "automatic",
									"sq" => "albanian",
									"ar" => "arabic",
									"bg" => "bulgarian",
									"ca" => "catalan",
									"zh-CN" => "chinese",
									"hr" => "croatian",
									"cs" => "czech",
									"da" => "danish",
									"nl" => "dutch",
									"en" => "english",
									"et" => "estonian",
									"tl" => "filipino",
									"fi" => "finnish",
									"fr" => "french",
									"gl" => "galician",
									"de" => "german",
									"el" => "greek",
									"iw" => "hebrew",
									"hi" => "hindi",
									"hu" => "hungarian",
									"id" => "indonesian",
									"it" => "italian",
									"ja" => "japanese",
									"ko" => "korean",
									"lv" => "latvian",
									"lt" => "lithuanian",
									"mt" => "maltese",
									"no" => "norwegian",
									"fa" => "persian alpha",
									"pl" => "polish",
									"pt" => "portuguese",
									"ro" => "romanian",
									"ru" => "russian",
									"sr" => "serbian",
									"sk" => "slovak",
									"sl" => "slovenian",
									"es" => "spanish",
									"sv" => "swedish",
									"th" => "thai",
									"tr" => "turkish",
									"uk" => "ukrainian",
									"vi" => "vietnamese"
									 );/*
		$sql = mysql_query("SELECT DISTINCT `language` FROM translations");
		$count = mysql_num_rows($sql);
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($sql, $i);
			$arr = mysql_fetch_array($sql);
			echo '<option value="'.$arr['language'].'"';
			if($_REQUEST['language'] == $arr['language']) echo ' selected="selected"';
			echo '>'.$arr['language'].'</option>';
		}*/
		foreach($languages as $k => $v) {
			if(getValue("SELECT `enabled` FROM `languages` WHERE language = ".quote_smart($k)."") == 1) { 
				echo '<option value="'.$k.'"';
				if($_REQUEST['language'] == $k) echo ' selected="selected"';
				echo '>'.$v.'</option>';
			}
		}
		?>
    </select>
    <input type="submit" name="Choose Language to Edit" value="Submit" />
    </div>
</form><br />
<br /><?php 

if($_REQUEST['act'] == '' && $_REQUEST['language'] == '') {
?>
<form action="index.php" method="post"> <input type="hidden" name="tp" value="translateeditor" />
  <div style="width: 400px; margin-left: 20px; padding: 10px 10px 10px 10px;">

<h2>Enable / Disable Languages</h2><br />
<table width="400" cellpadding="10" cellspacing="0"><tr><td>Language</td><td>Enable</td><td>Disable</td></tr><?php
	foreach($languages as $k => $v) {
		if(getValue("SELECT * FROM languages WHERE language = ".quote_smart($k)."") == 0) mysql_query("INSERT INTO `languages` (`id`, `language`, `enabled`) VALUES ('',".quote_smart($k).", ".quote_smart('0').")");
		echo "<tr><td><strong>".$v."</strong></td><td><input type=\"radio\" value=\"1\" name=\"enableLang[".$k."]\" id=\"enableLang[".$k."]\" ";
		$enabled = getValue("SELECT `enabled` FROM `languages` WHERE `language` = ".quote_smart($k).""); 
		if($enabled == '1') echo 'checked="checked"';
        echo "><label for=\"enableLang[".$k."]\"> Enabled</label> </td><td><input type=\"radio\" value=\"0\" name=\"enableLang[".$k."]\" id=\"disableLang[".$k."]\" ";
		if($enabled != '1') echo 'checked="checked"';
		echo "><label for=\"disableLang[".$k."]\"> Disabled</label> </td></tr>";
	}
	?>
    </table>
      <p>
        <input type="submit" name="Submit" value="Save Language Settings" />
      </p>
    </div>
</form>

<?php
}	
	if($_REQUEST['language'] != '' && $_REQUEST['act'] != 'singleLookup') {
		?><br /><br />
		<form action="index.php" method="post">
        <input type="hidden" name="tp" value="translateeditor" />
		<input type="hidden" name="act" value="updateLanguages" />
		<input type="hidden" name="language" value="<?php echo $_REQUEST['language']; ?>" />
		<input type="submit" name="Submit" value="Update Language Content" />
		<br />
		<br />
		<table width="100%" border="0" cellspacing="1" cellpadding="10">
		  <tr>
			<td width="50%"><strong>English</strong></td>
			<td width="50%" bgcolor="#EFEFEF"><strong>Other Language (<?php echo $_REQUEST['language']; ?>)</strong></td>
		  </tr>
		<?php
		$language = $_REQUEST['language'];
		$sql = mysql_query("SELECT * FROM translations WHERE `language` = ".quote_smart($language)."");
		$count = mysql_num_rows($sql);
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($sql, $i);
			$arr = mysql_fetch_array($sql);
			$key = $arr['translationKey'];
			$value = $arr['translationValue'];
			?>
		  <tr>
			<td><?php echo $arr['page']; ?><BR /><textarea name="key[<?php echo $arr['id']; ?>]" cols="65" rows="5"><?php echo $key; ?></textarea><a href="index.php?tp=translateeditor&act=singleLookup&language=<?php echo $language; ?>&lookupKey=<?php echo urlencode($key); ?>">Edit All for This Key</a></td>
			<td bgcolor="#EEEEEE"><?php echo $arr['page']; ?><BR />
			  <textarea name="value[<?php echo $arr['id']; ?>]" cols="65" rows="5"><?php echo $value; ?></textarea></td>
		  </tr>
		  <tr>
		  <td colspan="2" bgcolor="#EFEFEF" height="1"><img src="images/spacer.gif" height="1" /></td>
		  </tr>
		<?php
		}
		?>
		</table>
		<br />
		<input type="Submit" name="Submit" value="Update Language Content" />
		</form>
		<?php
	} else if($_REQUEST['act'] == 'singleLookup') {
		?>Edit all translations for <i><?php echo $_REQUEST['lookupKey']; ?></i><?php
		$sql = mysql_query("SELECT * FROM translations WHERE `language` = ".quote_smart($language)." AND translationKey = ".quote_smart($_REQUEST['lookupKey'])."");
		$count = mysql_num_rows($sql);
		echo "<BR><BR>Found ".$count." records for this key.<BR>";
		?><br /><br />
		<form action="index.php" method="post">
        <input type="hidden" name="tp" value="translateeditor" />
		<input type="hidden" name="act" value="updateLanguages" />
		<input type="hidden" name="language" value="<?php echo $_REQUEST['language']; ?>" />
		<input type="submit" name="Submit" value="Update Language Content" />
		<br />
		<br />
		<table width="100%" border="0" cellspacing="1" cellpadding="10">
		  <tr>
			<td width="50%"><strong>English</strong></td>
			<td width="50%" bgcolor="#EFEFEF"><strong>Korean</strong></td>
		  </tr>
		<?php
		$language = $_REQUEST['language'];
		$sql = mysql_query("SELECT * FROM translations WHERE `language` = ".quote_smart($language)." AND translationKey = ".quote_smart($_REQUEST['lookupKey'])."");
		$count = mysql_num_rows($sql);
		
		for($i = 0;$i < $count;$i++) {
			mysql_data_seek($sql, $i);
			$arr = mysql_fetch_array($sql);
			$key = $arr['translationKey'];
			$value = $arr['translationValue'];
			?>
		  <tr>
			<td><?php echo $arr['page']; ?><BR /><textarea name="key[<?php echo $arr['id']; ?>]" cols="65" rows="5"><?php echo $key; ?></textarea><a href="index.php?tp=translateeditor&act=singleLookup&lookupKey=<?php echo urlencode($key); ?>">Edit All for This Key</a></td>
			<td bgcolor="#EEEEEE"><?php echo $arr['page']; ?><BR />
			  <textarea name="value[<?php echo $arr['id']; ?>]" cols="65" rows="5"><?php echo $value; ?></textarea></td>
		  </tr>
		  <tr>
		  <td colspan="2" bgcolor="#EFEFEF" height="1"><img src="images/spacer.gif" height="1" /></td>
		  </tr>
		<?php
		}
		?>
		</table>
		<br />
		<input type="Submit" name="Submit" value="Update Language Content" />
		</form>
		<?php
	}
?>