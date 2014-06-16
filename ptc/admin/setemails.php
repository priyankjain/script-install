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
if($act=='chset')
{
mysql_query("UPDATE design SET value=".quote_smart($emailRefContest).", subject=".quote_smart($emailRefContestSubject)." WHERE name='emailRefContest'");
mysql_query("UPDATE design SET value=".quote_smart($emailWelcome).", subject=".quote_smart($emailWelcomeSubject)." WHERE name='emailWelcome'");
mysql_query("UPDATE design SET value=".quote_smart($emailDebit).", subject=".quote_smart($emailDebitSubject)." WHERE name='emailDebit'");
mysql_query("UPDATE design SET value=".quote_smart($emailCredit).", subject=".quote_smart($emailCreditSubject)." WHERE name='emailCredit'");
mysql_query("UPDATE design SET value=".quote_smart($emailOrderThankyou).", subject=".quote_smart($emailOrderThankyouSubject)." WHERE name='emailOrderThankyou'");
//mysql_query("UPDATE design SET value=".quote_smart($news)." WHERE name='news'");
}
$sql = mysql_query("select name, comments, value, subject from design where name IN ('emailRefContest','emailWelcome','emailDebit','emailCredit','emailOrderThankyou')");
$count = mysql_num_rows($sql);
?>
<form name="form1" method="post" action="./index.php">
  <table class="fullwidth" border="0" cellpadding="0" cellspacing="0">
    <thead>
    <tr> 

      <td colspan="3">SYSTEM EMAILS EDITOR</td>
    </tr>
</thead><tbody>
    <tr> 
      <td colspan="2"> 
        <hr noshade size="1">
      </td>
    </tr>
	<?php
for($i = 0;$i < $count;$i++) {
	mysql_data_seek($sql,$i);
	$a = mysql_fetch_array($sql);
?>
    <tr valign="top"> 
      <td width="28%" align="right"> 
        <?php echo $a['comments']; ?>
      </td>
      <td width="72%">
	  Subject:<br>	  <input name="<?php echo $a['name']."Subject"; ?>" type="text" value="<?php echo $a['subject']; ?>" size="55" maxlength="255">
	  <br>
	  <BR>
	  Message:<BR>
      <textarea name="<?php echo $a['name']; ?>" cols="50" rows="8"><?php echo $a['value']; ?></textarea>      </td>
    </tr>
    <tr valign="top"> 
      <td colspan="2" align="right"> 
        <br>
      <hr noshade size="1">      </td>
    </tr>
      <?php
}
?>
    <tr valign="top"> 
      <td width="28%" align="right"> 
        <input type="hidden" name="tp" value="setemails">
        <input type="hidden" name="act" value="chset">
      </td>
      <td width="72%">&nbsp;</td>
    </tr>
    <tr valign="top"> 
      <td colspan="2" align="right"> 
        <div align="center"> 
          <input type="submit" name="Submit" value="Change settings">
        </div>
      </td>
    </tr>
    </tbody>
  </table>
</form>
