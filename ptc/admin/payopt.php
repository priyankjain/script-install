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
$setupinfo = getArray("SELECT * FROM setupinfo LIMIT 1");
if($act=='chpayment') {
	$sq = "UPDATE payoptions SET
	confirmegold=".quote_smart($confirmegold).",
	confirmstormpay=".quote_smart($confirmstormpay).",
	confirmnetpay=".quote_smart($confirmnetpay).",
	confirmmoneybookers=".quote_smart($confirmmoneybookers).",
	confirmpaypal=".quote_smart($confirmpaypal).",
	confirmpayza=".quote_smart($confirmpayza).",
	adminegold=".quote_smart($adminegold).",
	adminstormpay=".quote_smart($adminstormpay).",
	adminnetpay=".quote_smart($adminnetpay).",
	adminmoneybookers=".quote_smart($adminmoneybookers).",
	adminpaypal=".quote_smart($adminpaypal).",
	adminpayza=".quote_smart($adminpayza).",
	payzacode=".quote_smart($payzacode).",
	payza_currency=".quote_smart($payza_currency).",
	paypal_currency=".quote_smart($paypal_currency)."
	";
	mysql_query($sq);
	
	displaySuccess("ADVERTISER'S PAYMENT OPTIONS HAS BEEN UPDATE SUCCESSFULLY...");
	
}


if($act=='chpayout') {
	
	$sq = "UPDATE
	payoptions
	SET
	memberegold=".quote_smart($memberegold).",
	memberstormpay=".quote_smart($memberstormpay).",
	membernetpay=".quote_smart($membernetpay).",
	membermoneybookers=".quote_smart($membermoneybookers).",
	memberpaypal=".quote_smart($memberpaypal).",
	memberpayza=".quote_smart($memberpayza)."
	";
	mysql_query($sq);
	
	if($setupinfo['usePayzaAutopay'] != $_REQUEST['usePayzaAutopay']) mysql_query("UPDATE setupinfo SET usePayzaAutopay = ".quote_smart($_REQUEST['usePayzaAutopay'])." WHERE 1");
	
	displaySuccess("MEMBER'S PAYOUT OPTIONS HAS BEEN UPDATE SUCCESSFULLY...");

}

@extract(mysql_fetch_array(mysql_query("SELECT * FROM payoptions")));
$setupinfo = getArray("SELECT * FROM setupinfo LIMIT 1");

?>

<h1>PAYMENT / PAYOUT OPTIONS</h1>
Advertiser's payment options
      <form name="form1" method="post" action="">

        <table width="100%" border="0" bgcolor="f5f5f5">
          <!--DWLayoutTable-->

          <tr> 

            <td width="1009" height="194" valign="top">Manual Payment Methods
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <!--DWLayoutTable-->
              <tr>
                <td width="502" height="19" valign="top"> 

                  <div align="right">Advertisers can pay via E-GOLD</div></td>

            <td width="4">&nbsp;</td>
              <td width="503" valign="top"> 

              <input type="radio" name="confirmegold" value="yes" <?php if($confirmegold=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="confirmegold" value="no" <?php if($confirmegold=='no') echo 'checked'?>>

              No </td>
          </tr>
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Admin E_GOLD account #</div></td>

            <td></td>
                <td valign="top"> 

                  <input type="text" name="adminegold" value="<?php echo  $adminegold?>">            </td>
          </tr>
          <?php /*
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td height="19" valign="top"> 

                  <div align="right">Advertisers can pay via STORMPAY</div></td>

            <td></td>
                <td valign="top"> 

              <input type="radio" name="confirmstormpay" value="yes" <?php if($confirmstormpay=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="confirmstormpay" value="no" <?php if($confirmstormpay=='no') echo 'checked'?>>

              No </td>
          </tr>
		  */ ?>
              
              <!-- STORPAY DEPRICATED -->
              <!--
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Admin STORMPAY account e-mail</div></td>

            <td></td>
                <td valign="top"> 

                  <input type="text" name="adminstormpay" value="<?php echo  $adminstormpay?>">            </td>
          </tr>
          -->
          
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              <!-- NETPAY DEPICATED -->
              <!--
              <tr>
                <td height="19" valign="top"> 

                  <div align="right">Advertisers can pay via NETPAY</div></td>

            <td></td>
                <td valign="top"> 

              <input type="radio" name="confirmnetpay" value="yes" <?php if($confirmnetpay=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="confirmnetpay" value="no" <?php if($confirmnetpay=='no') echo 'checked'?>>

              No </td>
          </tr>
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Admin NETPAY account #</div></td>

            <td></td>
                <td valign="top"> 

                  <input type="text" name="adminnetpay" value="<?php echo  $adminnetpay?>">            </td>
          </tr>
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              -->
              
              
              <tr>
                <td height="19" valign="top"> 

                  <div align="right">Advertisers can pay via SKRILL / MoneyBookers</div></td>

            <td></td>
                <td valign="top"> 

              <input type="radio" name="confirmmoneybookers" value="yes" <?php if($confirmmoneybookers=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="confirmmoneybookers" value="no" <?php if($confirmmoneybookers=='no') echo 'checked'?>>

              No </td>
          </tr>
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Admin MONEYBOOKERS account e-mail</div></td>

            <td></td>
                <td valign="top"> 

                  <input type="text" name="adminmoneybookers" value="<?php echo  $adminmoneybookers?>">            </td>
          </tr>
            </table></td>

          </tr>

          <tr> 

            <td height="47" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <!--DWLayoutTable-->
              <tr>
                <td width="502" height="19" valign="top"> 

                  <div align="right">Advertisers can pay via PAYPAL</div></td>

            <td width="4">&nbsp;</td>
              <td width="503" valign="top"> 

              <input type="radio" name="confirmpaypal" value="yes" <?php if($confirmpaypal=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="confirmpaypal" value="no" <?php if($confirmpaypal=='no') echo 'checked'?>>

              No </td>
          </tr>
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Admin PAYPAL account e-mail</div></td>

            <td></td>
                <td valign="top"> 

                  <input type="text" name="adminpaypal" value="<?php echo  $adminpaypal?>">            </td>
          </tr>
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Paypal Currency</div></td>

            <td></td>
                <td valign="top"> 

                  <select name="paypal_currency">
                  <option value="USD" <?php if($paypal_currency == 'USD') echo 'selected'; ?>>USD (US Dollar)</option>
                  <option value="AUD" <?php if($paypal_currency == 'AUD') echo 'selected'; ?>>AUD (Australian Dollar)</option>
                  <option value="BRL" <?php if($paypal_currency == 'BRL') echo 'selected'; ?>>BRL (Brazilian Real)</option>
                  <option value="CAD" <?php if($paypal_currency == 'CAD') echo 'selected'; ?>>CAD (Canadian Dollar)</option>
                  <option value="CZK" <?php if($paypal_currency == 'CZK') echo 'selected'; ?>>CZK (Czech Koruna)</option>
                  <option value="DKK" <?php if($paypal_currency == 'DKK') echo 'selected'; ?>>DKK (Danish Krone)</option>
                  <option value="EUR" <?php if($paypal_currency == 'EUR') echo 'selected'; ?>>EUR (Euro)</option>
                  <option value="HKD" <?php if($paypal_currency == 'HKD') echo 'selected'; ?>>HKD (Hong Kong Dollar)</option>
                  <option value="HUF" <?php if($paypal_currency == 'HUF') echo 'selected'; ?>>HUF (Hungarian Forint)</option>
                  <option value="ILS" <?php if($paypal_currency == 'ILS') echo 'selected'; ?>>ILS (Israeli New Sheqel)</option>
                  <option value="JPY" <?php if($paypal_currency == 'JPY') echo 'selected'; ?>>JPY (Japanese Yen)</option>
                  <option value="MYR" <?php if($paypal_currency == 'MYR') echo 'selected'; ?>>MYR (Malaysian Ringgit)</option>
                  <option value="MXN" <?php if($paypal_currency == 'MXN') echo 'selected'; ?>>MXN (Mexican Peso)</option>
                  <option value="NOK" <?php if($paypal_currency == 'NOK') echo 'selected'; ?>>NOK (Norwegian Krone)</option>
                  <option value="NZD" <?php if($paypal_currency == 'NZD') echo 'selected'; ?>>NZD (New Zealand Dollar)</option>
                  <option value="PHP" <?php if($paypal_currency == 'PHP') echo 'selected'; ?>>PHP (Philippine Peso)</option>
                  <option value="PLN" <?php if($paypal_currency == 'PLN') echo 'selected'; ?>>PLN (Polish Zloty)</option>
                  <option value="GBP" <?php if($paypal_currency == 'GBP') echo 'selected'; ?>>GBP (Pound Sterling)</option>
                  <option value="SGD" <?php if($paypal_currency == 'SGD') echo 'selected'; ?>>SGD (Singapore Dollar)</option>
                  <option value="SEK" <?php if($paypal_currency == 'SEK') echo 'selected'; ?>>SEK (Swedish Krona)</option>
                  <option value="CHF" <?php if($paypal_currency == 'CHF') echo 'selected'; ?>>CHF (Swiss Franc)</option>
                  <option value="TWD" <?php if($paypal_currency == 'TWD') echo 'selected'; ?>>TWD (Taiwan New Dollar)</option>
                  <option value="THB" <?php if($paypal_currency == 'THB') echo 'selected'; ?>>THB (Thai Baht)</option>
                  <option value="TRY" <?php if($paypal_currency == 'TRY') echo 'selected'; ?>>TRY (Turkish Lira)</option>
                  </select>            </td>
          </tr>
            </table></td>

          </tr>
		  

          <tr> 

            <td height="47" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <!--DWLayoutTable-->
              <tr>
                <td width="502" height="19" valign="top"> 

                  <div align="right">Advertisers can pay via Payza</div></td>

            <td width="4">&nbsp;</td>
              <td width="503" valign="top"> 

              <input type="radio" name="confirmpayza" value="yes" <?php if($confirmpayza=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="confirmpayza" value="no" <?php if($confirmpayza=='no') echo 'checked'?>>

              No </td>
          </tr>
              <tr>
                <td height="4"></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Admin Payza account e-mail</div></td>

            <td></td>
                <td valign="top"> 

                  <input type="text" name="adminpayza" value="<?php echo  $adminpayza?>">            </td>
          </tr>
<tr>
                <td height="22" valign="top"> 

                  <div align="right">Admin Payza Security Code (For IPN)</div></td>

            <td></td>
                <td valign="top"> 

                  <input type="text" name="payzacode" value="<?php echo  $payzacode?>">            </td>
          </tr>
              <tr>
                <td height="22" valign="top"> 

                  <div align="right">Payza Currency</div></td>

            <td></td>
                <td valign="top"> 

                  <select name="payza_currency">
                      <option value="USD" <?php if($payza_currency == 'USD') echo 'selected'; ?>>USD (US Dollar)</option>
                      <option value="AUD" <?php if($payza_currency == 'AUD') echo 'selected'; ?>>AUD (Australian Dollar)</option>
                      <option value="BGN" <?php if($payza_currency == 'BGN') echo 'selected'; ?>>BGN (Bulgarian Lev)</option>
                      <option value="CAD" <?php if($payza_currency == 'CAD') echo 'selected'; ?>>CAD (Canadian Dollar)</option>
                      <option value="CHF" <?php if($payza_currency == 'CHF') echo 'selected'; ?>>CHF (Swiss Franc)</option>
                      <option value="CZK" <?php if($payza_currency == 'CZK') echo 'selected'; ?>>CZK (Czech Koruna)</option>
                      <option value="DKK" <?php if($payza_currency == 'DKK') echo 'selected'; ?>>DKK (Danish Krone)</option>
                      <option value="EEK" <?php if($payza_currency == 'EEK') echo 'selected'; ?>>EEK (Estonia Kroon)</option>
                      <option value="EUR" <?php if($payza_currency == 'EUR') echo 'selected'; ?>>EUR (Euro)</option>
                      <option value="GBP" <?php if($payza_currency == 'GBP') echo 'selected'; ?>>GBP (Pound Sterling)</option>
                      <option value="HKD" <?php if($payza_currency == 'HKD') echo 'selected'; ?>>HKD (Hong Kong Dollar)</option>
                      <option value="HUF" <?php if($payza_currency == 'HUF') echo 'selected'; ?>>HUF (Hungarian Forint)</option>
                      <option value="INR" <?php if($payza_currency == 'INR') echo 'selected'; ?>>INR (Indian Rupee)</option>
                      <option value="LTL" <?php if($payza_currency == 'LTL') echo 'selected'; ?>>LTL (Lithuanian Litas)</option>
                      <option value="MYR" <?php if($payza_currency == 'MYR') echo 'selected'; ?>>MYR (Malaysian Ringgit)</option>
                      <option value="MKD" <?php if($payza_currency == 'MKD') echo 'selected'; ?>>MKD (Macedonian Denar)</option>
                      <option value="NOK" <?php if($payza_currency == 'NOK') echo 'selected'; ?>>NOK (Norwegian Krone)</option>
                      <option value="NZD" <?php if($payza_currency == 'NZD') echo 'selected'; ?>>NZD (New Zealand Dollar)</option>
                      <option value="PLN" <?php if($payza_currency == 'PLN') echo 'selected'; ?>>PLN (Polish Zloty)</option>
                      <option value="RON" <?php if($payza_currency == 'RON') echo 'selected'; ?>>RON (Romanian New Leu)</option>
                      <option value="SEK" <?php if($payza_currency == 'SEK') echo 'selected'; ?>>SEK (Swedish Krona)</option>
                      <option value="SGD" <?php if($payza_currency == 'SGD') echo 'selected'; ?>>SGD (Singapore Dollar)</option>
                      <option value="ZAR" <?php if($payza_currency == 'ZAR') echo 'selected'; ?>>ZAR (South African Rand)</option>
                  </select>            </td>
          </tr>
<tr>
                <td valign="top" colspan="3"> 

                  <div align="center">
                    <p><strong>To activate Payza IPN</strong>, You must first set your Notify URL for your account IPN Settings <br>
                      on Payza's back office for your account to <strong>                      <?php echo $ptrurl; ?>/notify_ap.php </strong></p>
                  </div></td>
          </tr>
            </table></td>

          </tr>
		  
		  

          <tr> 

            <td height="26"> 

              <div align="center">

                <br>
                <input type="hidden" name="act" value="chpayment">

                <input type="hidden" name="tp" value="payopt">

                <input type="submit" name="Submit" value="SAVE settings">
              </div>

            </td>

          </tr>

        </table>

      </form>
Member's payouts options

      <form name="form2" method="post" action="">

        <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="f5f5f5">

          <tr> 

            <td width="50%"> 

              <div align="right">Members can request payouts via E-GOLD</div>

            </td>

            <td width="50%"> 

              <input type="radio" name="memberegold" value="yes" <?php if($memberegold=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="memberegold" value="no" <?php if($memberegold=='no') echo 'checked'?>>

              No </td>

          </tr>
			
            
            <!-- STORPAY DEPRICATED -->
            <!--
          <tr> 

            <td width="50%"> 

              <div align="right">Members can request payouts via STORMPAY</div>

            </td>

            <td width="50%"> 

              <input type="radio" name="memberstormpay" value="yes" <?php if($memberstormpay=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="memberstormpay" value="no" <?php if($memberstormpay=='no') echo 'checked'?>>

              No </td>

          </tr>
          -->
          
          
<!-- NETPAY DEPRICATED -->
<!--
          <tr> 

            <td width="50%"> 

              <div align="right">Members can request payouts via NETPAY</div>

            </td>

            <td width="50%"> 

              <input type="radio" name="membernetpay" value="yes" <?php if($membernetpay=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="membernetpay" value="no" <?php if($membernetpay=='no') echo 'checked'?>>

              No </td>

          </tr>
-->
          <tr> 

            <td width="50%"> 

              <div align="right">Members can request payouts via MONEYBOOKERS</div>

            </td>

            <td width="50%"> 

              <input type="radio" name="membermoneybookers" value="yes" <?php if($membermoneybookers=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="membermoneybookers" value="no" <?php if($membermoneybookers=='no') echo 'checked'?>>

              No </td>

          </tr>

          <tr> 

            <td width="50%"> 

              <div align="right">Members can request payouts via PAYPAL</div>

            </td>

            <td width="50%"> 

              <input type="radio" name="memberpaypal" value="yes" <?php if($memberpaypal=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="memberpaypal" value="no" <?php if($memberpaypal=='no') echo 'checked'?>>

              No </td>

          </tr>

          <tr> 

            <td width="50%"> 

              <div align="right">Members can request payouts via Payza</div>

            </td>

            <td width="50%"> 

              <input type="radio" name="memberpayza" value="yes" <?php if($memberpayza=='yes') echo 'checked'?>>

              Yes 

              <input type="radio" name="memberpayza" value="no" <?php if($memberpayza=='no') echo 'checked'?>>

              No </td>

          </tr>
          <tr> 

            <td width="50%"> 

              <div align="right">Members can request payouts via Payza <strong>Automatically</strong>?</div>

            </td>

            <td width="50%"> 

              <input type="radio" name="usePayzaAutopay" value="1" <?php if($setupinfo['usePayzaAutopay'] == '1') echo 'checked'?>>

              Yes 

              <input type="radio" name="usePayzaAutopay" value="0" <?php if($setupinfo['usePayzaAutopay'] == '0') echo 'checked'?>>

              No </td>

          </tr>

          <tr> 

            <td colspan="2"> 

              <div align="center">

                <input type="hidden" name="act" value="chpayout">

                <input type="hidden" name="tp" value="payopt">

                <input type="submit" name="Submit2" value="SAVE settings">

              </div>

            </td>

          </tr>

        </table>

      </form>
