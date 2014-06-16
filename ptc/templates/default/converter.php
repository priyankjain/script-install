<?php
if($action == 'convertPoints') {
	extract(getArray("SELECT * FROM users WHERE username = ".quote_smart($_SESSION['login']).""));
	$userBalance = userPoints($_SESSION['login']);
	$points = $_REQUEST['points'];
	$adType = $_REQUEST['adType'];
	
	if($points > $userBalance) {
		$points = $userBalance;
	}
	
	if($points <= 0) {
		?><script type="text/javascript" language="javascript">alert('<?php echo $credit; ?> <?php echo __($setup_info['currencyName'].' Added to your account.'); ?>');</script><?php
	} else {
		if($adType == 'cash') {
			$rate = $pointToCash;
			$credit = $points * $rate;
			debitAccountBalance($_SESSION['login'], 'credit', $credit);
			debitAccountBalance($_SESSION['login'], 'debit', $points, 'points');
			?><script type="text/javascript" language="javascript">alert('<?php echo $credit; ?> <?php echo __($setup_info['currencyName'].' Added to your account.'); ?>');</script><?php
		} else if($adType == 'banner') {
			$rate = $pointToBanners;
			$credit = $points * $rate;
			refundCredits($_SESSION['login'], $adType, $credit);
			debitAccountBalance($_SESSION['login'], 'debit', $points, 'points');
			?><script type="text/javascript" language="javascript">alert('Added <?php echo $credit; ?> <?php echo __('banner credits to your account.'); ?>');</script><?php
		} else if($adType == 'fbanner') {
			$rate = $pointToFBanners;
			$credit = $points * $rate;
			refundCredits($_SESSION['login'], $adType, $credit);
			debitAccountBalance($_SESSION['login'], 'debit', $points, 'points');
			?><script type="text/javascript" language="javascript">alert('Added <?php echo $credit; ?> <?php echo __('featured banner credits to your account.'); ?>');</script><?php
		} else if($adType == 'fad') {
			$rate = $pointToFAds;
			$credit = $points * $rate;
			refundCredits($_SESSION['login'], $adType, $credit);
			debitAccountBalance($_SESSION['login'], 'debit', $points, 'points');
			?><script type="text/javascript" language="javascript">alert('Added <?php echo $credit; ?> <?php echo __('featured ad credits to your account.'); ?>');</script><?php
		} else if($adType == 'links') {
			$rate = $pointToLinks;
			$credit = $points * $rate;
			refundCredits($_SESSION['login'], $adType, $credit);
			debitAccountBalance($_SESSION['login'], 'debit', $points, 'points');
			?><script type="text/javascript" language="javascript">alert('Added <?php echo $credit; ?> <?php echo __('link credits to your account.'); ?>');</script><?php
		} else {
			?><script type="text/javascript" language="javascript">alert('Invalid credit type to convert to.');</script><?php
		}
	}
	//if(isset($credit)) { 
		//mysql_query("UPDATE users SET ftotalclicks = ftotalclicks-".quote_smart($points)." WHERE username = ".quote_smart($_SESSION['login'])."");
	//}
}
extract(getArray("SELECT * FROM users WHERE username = ".quote_smart($_SESSION['login']).""));
?>
<script type="text/javascript" language="javascript">
	function updateRatio() {
		var adTypeObj = document.getElementById('adType');
		var adType = adTypeObj.value;
		var rate = getRate(adType);
		
		var pointsObj = document.getElementById('points');
		var points = pointsObj.value
		var quote = updateQuote(points,rate);
		if(quote == false) { quote = 0; }
		if(adType == 'cash') {
			var creditType = 'USD Cash';
		} else {
			if(adType == 'banner') {
				var creditType = '<?php echo __('Banner Credits'); ?>';
			} else if(adType == 'fbanner') {
				var creditType = '<?php echo __('Featured Banner Credits'); ?>';
			} else if(adType == 'fad') {
				var creditType = '<?php echo __('Featured Ad Credits'); ?>';
			} else if(adType == 'links') {
				var creditType = '<?php echo __('Link / Paid To Click Credits'); ?>';
			} else {
				var creditType = '<?php echo __('Credits'); ?>';
			}
		}
		
		dispResult = document.getElementById('convertTo');
		dispResult.innerHTML = quote + ' ' + creditType;

		return true;
	}
	function getRate(adType) {
		if(adType == 'cash') {
			var rate = <?php echo $pointToCash; ?>;
		} else if(adType == 'banner') {
			var rate = <?php echo $pointToBanners; ?>;
		} else if(adType == 'fbanner') {
			var rate = <?php echo $pointToFBanners; ?>;
		} else if(adType == 'fad') {
			var rate = <?php echo $pointToFAds; ?>;
		} else if(adType == 'links') {
			var rate = <?php echo $pointToLinks; ?>;
		} else {
			return false;
		}
		//alert('Returning Rate: ' + rate);
		return rate;
	}
	function updateQuote(credits,rate) {
		var userBalance = <?php echo number_format(userPoints($_SESSION['login']),0,"",""); ?>;
		if(userBalance < credits) {
			var pointsObj = document.converter.points;
			pointsObj.value = userBalance;
			credits = userBalance;
		}
		finalQuote = credits * rate;
		//alert('Returning final quote: ' + finalQuote);
		return finalQuote;
	}
	updateQuote(0,0);
</script>
<style type="text/css">
<!--
.white14pxBoldArial {font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #FFFFFF;
	font-size: 14px;
}
-->
</style>
<?php echo $pageHeader; ?>
        
        <h1><?php echo __(ucfirst($setupinfo['pointsName']).' Converter'); ?></h1><p><?php echo __('Have you earned '.$setupinfo['pointsName'].'? Convert them for use below.'); ?></p>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="box">
  <tr>
    <td height="44" bgcolor="#FFFFFF">

          <table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr valign="top">
              <td width="61%"><form action="index.php" method="post" name="converter" id="converter">
                <table width="441" border="0" cellspacing="0" cellpadding="2">
                  <tr valign="top">
                    <td width="215"><strong><?php echo __($setupinfo['pointsName'].' to convert'); ?> </strong></td>
                    <td width="206"><strong>Convert To </strong></td>
                  </tr>
                  <tr valign="top">
                    <td><input size="15" type="text" name="points" id="points" value="0" onBlur="updateRatio();" onChange="updateRatio();">
                        <br>
        <?php echo __('Your '.$setupinfo['pointsName'].'s'); ?> : <?php echo userPoints($_SESSION['login']); ?></td>
                    <td>
                      <select name="adType" id="adType" onChange="updateRatio();"  onblue="updateRatio();">
                        <option value="banner"><?php echo __('Banners'); ?> (480x60)</option>
                        <option value="fbanner"><?php echo __('Featured Banners'); ?> (180x100)</option>
                        <option value="fad"><?php echo __('Featured Ad'); ?></option>
                        <option value="links" selected><?php echo __('Link / Paid to Click'); ?></option>
                        <option value="cash"><?php echo __('Cash'); ?></option>
                      </select>
                      <br>
                      <div id="convertTo" name="convertTo"><?php echo __('0 Credits'); ?></div></td>
                  </tr>
                </table>
                <br>
                <input type="hidden" name="tp" value="converter">
                <input type="hidden" name="action" value="convertPoints">
                <input type="submit" name="Convert" value="<?php echo __('Convert (This is a final action)',false);?>">
              </form></td>
            </tr>
          </table>
      </td>
  </tr>
</table>
<br>
<BR>
<?php echo $pageFooter; ?>