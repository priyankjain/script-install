<?php echo $pageHeader; ?>
		<h1><?php echo __('Contact and Support'); ?></h1>
        <p><?php echo __('Please fill out the contact form below to email '.$ptrname.' . <br>
        Please note, that if the issue you are emailing about is clearly explained on our site, you will not receive a response.'); ?></p>
        
        <p>
		<?php
if($send==1) {
	if($_SESSION['sentMessage'] > time()) {
		echo __("<BR><STRONG><FONT COLOR=RED>ERROR: </FONT></STRONG>You must wait at least 10 minutes before sending another contact message.<BR>");
	} else {
		$fr="From: $adminemail\r\n";
		$content_email="$to / From: $from_name, $from_email. Message: $message";
		if(mail($adminemail, $ptrname.': Contact Us Form Submission', $content_email, $fr)){
			echo "<script type=\"Javascript\" language=\"text/javascript\">alert('".__('Your message has been sent successfully.')."');</script>";
			echo __("<BR><BR><BR><STRONG><FONT COLOR=GREEN>THANK YOU!</FONT></STRONG> YOUR MESSAGE HAS BEEN RECEIVED SUCCESSFULLY!");
			$sentSuccessfully = TRUE;
			$_SESSION['sentMessage'] = time() + (60*10);
		} else {
			echo __("<BR><STRONG><FONT COLOR=RED>ERROR: </FONT></STRONG>Your message could not be sent.<BR>");
		}
	}
}
?>
<style type="text/css">
<!--
.white14pxBoldArial {font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #FFFFFF;
	font-size: 14px;
}
-->
</style>
<?php
if(!$sentSuccessfully) { ?>

    <div align="center">
          <table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr valign="top">
              <td width="61%"><table width="97%" border="0">
                <tr>
                  <td background="images/fon.gif.gif">
                    
                    <form name="form1" method="post" action="index.php">
                      <table width="80%">
                        <input name="tp" type="hidden" value="contacts">
                        <tbody>
                          <tr>
                            <td width="32%" valign="top">
                            <p><?php echo __('Support Department'); ?></p></td>
                            <td width="68%" valign="top">
                              <select name="to">
                                <option value=4><?php echo __('Pending Order',false); ?></option>
								<option selected value=3><?php echo __('Abuse / SPAM',false); ?></option>
                                <option value=2><?php echo __('Help / Support',false); ?></option>
                                <option value=1><?php echo __('Sales',false); ?></option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td valign="top">
                            <p><?php echo __('Your Name'); ?></p></td>
                            <td valign="top">
                              <input name=from_name size=15>
                            </td>
                          </tr>
                          <tr>
                            <td valign="top">
                            <p><?php echo __('Your E-Mail'); ?></p></td>
                            <td valign="top">
                              <input name=from_email size=25>
                            </td>
                          </tr>
                          <tr>
                            <td valign="top">
                            <p><?php echo __('Message'); ?></p></td>
                            <td valign="top">
                              <textarea cols=30 name=message rows=10 wrap=virtual></textarea>
                          <tr>
                                          <td colspan=2 valign="top">
                                            <input type="hidden" name="send" value="1">
                            </td>
                          </tr>
                                        <tr align=middle>
                                          <td 
              colspan=2 valign="top">
                                            <input type=submit value="<?php echo __('Send Mail',false); ?>" name="submit">
                                          </td>
                                        </tr>
                        </TBODY>
                      </table>
                    </form>
                    <div align="center"></div></td>
                </tr>
              </table></td>
            </tr>
          </table>
      </div>
      
<?php
}
?></p><?php echo $pageFooter; ?>