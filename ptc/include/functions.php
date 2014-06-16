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

if(!isset($functionsIncludes)) {

	$functionsIncludes = TRUE;
	
	function getAdTimer($id, $adType, $username='') {
		global $adTypes, $setupinfo;
		if($username != '') $membership = getUserMembershipDetails($username);
		else $membership = array('status'=>FALSE);
		$membershipTimer = $adTypes[$adType]['membershipTimer'];
		if($membership['status'] == TRUE) $adjust = $membership[$membershipTimer]; else $adjust = 0;
		if($adjust == '') $adjust = 0;
		
		$prefix = $adTypes[$adType]['prefix'];
		$timer = explode(',',$setupinfo[$prefix.'PayTimers']);
		$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
		if($creditID == '') $creditID = 0;
		$time = ($timer[$creditID]-$adjust);
		echo $time;
	}
	
	function getAdPrize($id, $adType, $username='') {
		global $adTypes, $setupinfo;
		if($username != '') $membership = getUserMembershipDetails($username);
		else $membership = array('status'=>FALSE);
		$bonus = $adTypes[$adType]['adBonus'];
		if($membership['status'] == TRUE) $adjust = $membership[$bonus]; else $adjust = 0;
		if($adjust == '') $adjust = 0;
		
		$prefix = $adTypes[$adType]['prefix'];
		$credit = explode(',',$setupinfo[$prefix.'PayCredits']);
		$creditType = explode(',',$setupinfo[$prefix.'PayTypes']);
		$creditID = getValue("SELECT creditID FROM `".$adTypes[$adType]['table']."` WHERE `".$adTypes[$adType]['idType']."` = ".quote_smart($id)."");
		if($creditID == '') $creditID = 0;
		$credits = ($credit[$creditID]*$adjust);
		echo $credits;
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Cleanly escape mysql values for direct insertion into a mysql_query
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(!function_exists("quote_smart")) {
		function quote_smart($value)
		{
		   // Stripslashes
		   if (get_magic_quotes_gpc()) {
			   $value = stripslashes($value);
		   }
		   // Quote if not integer
		   if (!is_numeric($value)) {
			   $value = "'" . mysql_real_escape_string($value) . "'";
		   } else {
				$value = "'" . $value . "'";
		   }
		   return $value;
		}
	}
	
		
	# ~~~ Code Generator ~~~~~~~~~~ # 
	function dracon_CodeGen($Length=5,$Code='') { 
	  $Chars = "abcdefghijklmnpqrstuvwxyz23456789"; 
	  srand((double)microtime()*1000003);
	  for ($i=0; $i<$Length; $i++) { 
		$Num = rand(0, strlen($Chars)-1);
		$Code = $Code.substr($Chars, $Num, 1); 
	  } 
	  return strtoupper($Code); 
	}
	# ~~~ Code Encryptor ~~~~~~~~~~ # 
	function dracon_CodeEnc($secCode) { 
	  $encType = 'rijndael-128';
	  $aesMode = 'ecb';
	  $encIV = "1234567890123450";
	  $encObj = mcrypt_module_open($encType, '', $aesMode, '');
	  mcrypt_generic_init($encObj, aesKey, $encIV);
	  $secEncCode = mcrypt_generic($encObj, $secCode);
	  mcrypt_generic_deinit($encObj);
	  mcrypt_module_close($encObj);
	  return bin2hex($secEncCode);
	}
	function buildPageTags() {
		global $setupinfo;
		$return = array();
		//SITE DETAILS
		$return['{PTR_NAME}'] = $setupinfo['ptrname'];
		$return['{PTRURL}'] = $setupinfo['ptrurl'];
		$return['{SUPPORT_EMAIL}'] = $setupinfo['adminemail'];
		$return['{CURRENCY}'] = $setupinfo['currency'];
		$return['{CURRENCY_NAME}'] = $setupinfo['currencyName'];
		$return['{POINTS_NAME}'] = $setupinfo['pointsName'];
		$return['{TEMPLATE_NAME}'] = $setupinfo['siteTemplate'];
		
		//SITE STATISTICS
		$return['{TOTAL_USERS}'] = getValue("SELECT COUNT(fid) FROM users WHERE `accstatus` = 'active'");
		$return['{TOTAL_USERS_TODAY}'] = getValue("SELECT COUNT(fid) FROM users WHERE `accstatus` = 'active' AND regdate = DATE(NOW())");
		$return['{TOTAL_HITS_TODAY}'] = getValue("SELECT SUM(visits) FROM websitevisits WHERE visitDate = DATE(NOW())");
		$return['{TOTAL_PAID_OUT}'] = getValue("SELECT SUM(famount) FROM payrequest WHERE paidOut = 1");
		$return['{TOTAL_PTC_ADS}'] = getValue("SELECT COUNT(fn) FROM `tasks` WHERE `fsize` > `fvisits`");
		$return['{TOTAL_SURVEY_ADS}'] = getValue("SELECT COUNT(id) FROM `surveys` WHERE `fsize` > `fviews`");
		return $return;
	}
	
	function displayContent($content,$replace='') {
		
		$content = templateTagReplace($content,'{b}','{/b}','<strong>','</strong>');
		$content = templateTagReplace($content,'{i}','{/i}','<i>','</i>');
		//REPLACE TEMPLATE TAGS SUCH AS {NAME}
		if(is_array($replace) && count($replace) > 0) foreach($replace as $k => $v) str_replace($content, $k, $v);
		$content = templateTagTranslate($content);
		return $content;
	}
	function templateTagTranslate($content) {
		$content_processed = preg_replace_callback(
		  '#\{translate\}(.+?)\{\/translate\}#s',
		  create_function(
			'$matches',
			'return "".__($matches[1])."";'
		  ),
		  $content
		);
		return $content_processed;
	}
	
	function templateTagReplace($content,$tagStart,$tagEnd,$newTagStart,$newTagEnd) {
		$content_processed = preg_replace_callback(
		  '#'.preg_quote($tagStart).'(.+?)'.preg_quote($tagEnd).'#s',
		  create_function(
			'$matches',
			'return "'.str_replace('"','\"',$newTagStart).'".$matches[1]."'.str_replace('"','\"',$newTagEnd).'";'
		  ),
		  $content
		);
		return $content_processed;
	}
	
	function prepURL($url) {
		if(substr($url, 0, 4) != 'http')$url = 'http://'.$url;
		if(substr_count($url, "?") == 0 && substr($url, 0, -1) != '/')$url = $url.'/';
		return $url;
	}
	function getDefaultTemplate() {
		return getValue("SELECT templateIdentifier FROM `templates` WHERE `active` = '1' ORDER BY id ASC LIMIT 1");
	}
	function hasPreInclude($tp) {
		$rtn = getValue("SELECT hasPreInclude FROM siteactions WHERE tp = ".quote_smart($tp)."");
		if($rtn == 0) return FALSE; else return TRUE;
	}
	function isStandalonePage($tp) {
		$rtn = getValue("SELECT isStandalonePage FROM siteactions WHERE tp = ".quote_smart($tp)."");
		if($rtn == 0) return FALSE; else return TRUE;
	}
	
	function orphanCount() {

		return getValue("SELECT COUNT(fid) AS orphans FROM users WHERE frefer = ''");

	}

	function visibleAd($adID) {

		$orphans = orphanCount();

		$query = mysql_query("SELECT * FROM packages WHERE pack_price != '0.00' AND fnum = ".quote_smart($adID)." AND active = '1' ORDER BY packSpecial,active DESC") or die(mysql_error());

		$count = mysql_num_rows($query);

		if($count == 0) {

			return FALSE;

		} else {

			$arr = mysql_fetch_array($query);

			$packReferrals = packReferrals($arr['fnum']);

			if($packReferrals > 0) {

				if($orphans < $packReferrals) {

					return FALSE;

				} else {

					return TRUE;

				}

			} else {

				return TRUE;

			}

		}

	}

	//CHECK LOGIN SESSIONS
	function loginCheck() {
		if($_SESSION['admin'] == '') {
			return FALSE;
		} else {
			$sql = mysql_query("SELECT `loginIpAddress` FROM admins WHERE username = ".quote_smart($_SESSION['admin'])."") or die(mysql_error());
			$count = mysql_num_rows($sql);
			if($count > 0) {
				$ip = $_SERVER['REMOTE_ADDR'];
				$arr= mysql_fetch_array($sql);
				if($arr['loginIpAddress'] != $ip) {
					$_SESSION['admin'] = '';
					unset($_SESSION);
					session_unregister("admin");
					//echo "Your login session has been removed because your ip address did not match the one on record. Please try logging in again.";
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				$_SESSION['admin'] = '';
				unset($_SESSION);
				session_unregister("admin");
				//echo "You have been logged out because some information could not be verified. Please login again.";
				return FALSE;
			}
		}
	}
	function packReferrals($adID) {

		$query = mysql_query("SELECT * FROM packages WHERE pack_price != '0.00' AND fnum = ".quote_smart($adID)." AND active = '1' ORDER BY packSpecial,active DESC");

		$count = mysql_num_rows($query);

		if($count == 0) {

			return 0;

		} else {

			$arr = mysql_fetch_array($query);

			$hasRefs = FALSE;

			$refCount = 0;

			if($arr['packSpecial'] == '1') {

				$q = mysql_query("SELECT b.pack_name, b.pack_price, b.pack_credits_type,pack_credits FROM packitems a, packages b WHERE a.item = b.fnum AND a.package = ".quote_smart($arr['fnum'])." AND b.pack_credits_type = 'referrals'");

				$c = mysql_num_rows($q);

				if($c > 0) {

					for($k = 0;$k < $c;$k++) {

						mysql_data_seek($q,$k);

						$ar = mysql_fetch_array($q);

						if($ar['pack_credits_type'] == 'referrals') {

							$hasRefs = TRUE;

							$refCount += $ar['pack_credits'];

						}

					}

				} else {

					return 0;

				}

			} else {

				if($arr['pack_credits_type'] == 'referrals') {

					$hasRefs = TRUE;

					$refCount += $arr['pack_credits'];

				}

			}

			if($hasRefs == TRUE) {

				return $refCount;

			} else {

				return 0;

			}

		}

	}

	//RETURN TOTAL NUMBER OF POINTS ASSIGNED TO A USER

	function userPoints($username) {

		$debits = abs(getValue("SELECT SUM(famount) FROM debit WHERE `fid` = ".quote_smart($username)." AND `type` = 'points' AND famount > 0"));
		$credits = abs(getValue("SELECT SUM(famount) FROM debit WHERE `fid` = ".quote_smart($username)." AND `type` = 'points' AND famount < 0"));
		
		return number_format(($credits - $debits),6);

	}

	

	//DISPLAYS WEBSITE MENU'S

	function showMenu($menuType = 'website', $class = '', $style = 'font-color: black; font-weight: normal; font-size: 12px;',$spacer = ' &nbsp;&nbsp;&nbsp;') {
		global $templateFolder;
		$query = mysql_query("SELECT * FROM menus WHERE menuType = ".quote_smart($menuType)." ORDER BY sortOrder");

		$count = mysql_num_rows($query);

		if($count == 0) {

			return FALSE;

		} else {

			for($i = 0;$i < $count;$i++) {

				mysql_data_seek($query, $i);

				$arr = mysql_fetch_array($query);

				if($i != 0) echo $spacer;

				echo "<a href=\"".$arr['menuAction']."\" class=\"".$class."\" style=\"".$style."\">";
				if($arr['icon'] != '') echo "<img src=\"".$templateFolder."images/icons/".$arr['icon']."\" valign=\"absmiddle\" border=\"0\"> ";
				echo $arr['menuName'];
				echo "</a>";

			}

		}

	}

	//RETURN HOW MANY CREDITS ARE LEFT FOR A BANNER

	function creditsLeft($id, $adType) {



		$valid = FALSE;

		if($adType == '' || $adType == 'banner') {

			$adType = 'banner';

			$table = 'banners';

			$select = 'fsize-fshows AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'fbanner') {

			$table = 'fbanners';

			$select = 'fsize-fshows AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'fad') {

			$table = 'featuredads';

			$select = 'fsize-fshows AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'flinks') {

			$table = 'featuredlinks';

			$select = 'fsize-fshows AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'links') {

			$table = 'tasks';

			$select = 'fsize-fvisits AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'ptrad') {

			$table = 'ptrads';

			$select = 'fsize-fvisits AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'signup') {

			$table = 'signups';

			$select = 'fsize-fsignups AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'email') {

			$table = 'reads';

			$select = 'fsize-freads AS creditsLeft';

			$valid = TRUE;

		} else if($adType == 'survey') {

			$table = 'surveys';

			$select = 'fsize-fviews AS creditsLeft';

			$valid = TRUE;

		} else {

			return false;

		}

		

		if($valid == TRUE) {

			if($adType == 'links' || $adType == 'ptrad') { $idName = 'fn'; } else if($adType == 'survey') { $idName = 'id'; } else { $idName = 'fnum'; }

			$sql = "SELECT $select FROM `$table` WHERE `username` = ".quote_smart($_SESSION['login'])." AND `$idName` = ".quote_smart($id)."";

			//echo "Running SQL: ".$sql."<BR><BR>";

			$query = mysql_query($sql) or die(mysql_error() ." running query: ".$sql."<BR><BR>");

			$count = mysql_num_rows($query);

			if($count == 0) {

				return false;

			} else {

				$arr = mysql_fetch_array($query);

				return $arr['creditsLeft'];

			}

		} else {

			return false;

		}

	}

	//DISPLAYS AND INCREMENTS A BANNER IN THE ROTATOR

	function displayBanner() {

		$query = "SELECT fsize-fshows AS creditsLeft, flink, furl, fnum, fshows FROM banners WHERE fsize-fshows > 0";

		$query .=" ORDER BY RAND() LIMIT 1";

		

		$sql=mysql_query($query);

        $rows=mysql_num_rows($sql);

		

		if($rows > 0) {

			@$arr=mysql_fetch_array($sql);

			@extract($arr);

			echo"<a href=\"index.php?tp=out&id=$fnum&t=b\" target=\"_blank\"><img src=\"$furl\" border=0 alt='$falt' width=\"468\" height=\"60\"></a>";

			$shows=$fshows+1;

			mysql_query("UPDATE banners SET fshows=".quote_smart($shows)." WHERE fnum=".quote_smart($fnum)."");

		} else {

			echo "<a href=\"index.php?tp=advertise\">Advertise Here</a>";

		}

	}

	

	function displayFBanner() {

		$query = "SELECT fsize-fshows AS creditsLeft, flink, furl, fnum, fshows FROM fbanners WHERE fsize-fshows > 0";

		$query .=" ORDER BY RAND() LIMIT 1";

		$sql=mysql_query($query);

		$rows=mysql_num_rows($sql);

		

		if($rows > 0) {

			@$arr=mysql_fetch_array($sql);

			@extract($arr);

			echo"<a href=index.php?tp=out&id=$fnum&t=fb target=\"_blank\"><img src=\"$furl\" border=0 alt='$falt' width=\"180\" height=\"100\"></a>";

			$shows=$fshows+1;

			mysql_query("UPDATE fbanners SET fshows=".quote_smart($shows)." WHERE fnum=".quote_smart($fnum)."");

		} else {

			echo "<a href=\"index.php?tp=advertise\">Advertise Here</a>";

		}

	}

	function displayFAd() {

		$sql=mysql_query("SELECT fsize-fshows AS creditsLeft, flink, fname, fnum, fshows,description FROM featuredads WHERE fsize-fshows > 0 ORDER BY RAND() LIMIT 1");

		$rows=mysql_num_rows($sql);

		if($rows > 0) {

			@$arr=mysql_fetch_array($sql);

			@extract($arr);

			echo "<a href=\"index.php?tp=out&id=$fnum&t=fa\" target=\"_blank\"><font size=\"2\">".$fname."</font><br></a><font size=\"1\">".$description."</font>";

			$shows=$fshows+1;

			mysql_query("UPDATE featuredads SET fshows=".quote_smart($shows)." WHERE fnum=".quote_smart($fnum)."");

		} else {

			echo "<BR><BR>Put your ad here!<BR><BR><a href=\"index.php?tp=advertise\">Advertise Here</a>";

		}

	}

	function displayFLinks($count = 1) {

		$sql=mysql_query("SELECT fsize-fshows AS creditsLeft, flink, fname, fnum, fshows FROM featuredlinks WHERE fsize-fshows > 0 ORDER BY RAND() LIMIT $count");

		$rows=mysql_num_rows($sql);

		if($rows > 0) {

			for($i = 0;$i < $rows;$i++) {

				@mysql_data_seek($sql,$i);

				@$arr=mysql_fetch_array($sql);

				@extract($arr);

				echo "<a href=\"index.php?tp=out&id=$fnum&t=flink\" target=\"_blank\"><font size=\"2\">".$fname."</font><br></a><HR>";

				$shows=$fshows+1;

				mysql_query("UPDATE featuredlinks SET fshows=fshows+1 WHERE fnum=".quote_smart($fnum)."");

			}

		} else {

			echo "<a href=\"index.php?tp=advertise\">Advertise Here</a>";

		}

	}

	

	function debitAccountBalance($username, $type='debit', $amount,$payType='usd',$debitFor='account') {

		//if(is_numeric($amount)) {

			if($type == 'credit') $amount = '-'.$amount;

			$query = mysql_query("INSERT INTO debit (fnum,fdate,famount,fid,`type`,`debitFor`) VALUES (

			'',

			NOW(),

			".quote_smart($amount).",

			".quote_smart($username).",

			".quote_smart($payType).",

			".quote_smart($debitFor)."

			)");

			return TRUE;

		//} else {

			//return FALSE;

		//}

	}

	

	function debitAccount($username, $type, $credits,$bannerID = 0) {

		if(is_numeric($credits)) {

			$query = mysql_query("INSERT INTO creditdebits (username, credits, creditsFor, debitDate, bannerID) VALUES (

			".quote_smart($_SESSION['login']).",

			".quote_smart($credits).",

			".quote_smart($type).",

			NOW(),

			".quote_smart($bannerID)."

			)");

			return TRUE;

		} else {

			return FALSE;

		}

	}

	

	function creditAccount($username, $type, $credits,$bannerID = 0) {

		if(is_numeric($credits)) {

			$sql = "INSERT INTO creditadditions (username, credits, creditsFor, additionDate, bannerID) VALUES (

			".quote_smart($_SESSION['login']).",

			".quote_smart($credits).",

			".quote_smart($type).",

			NOW(),

			".quote_smart($bannerID)."

			)";

			$query = mysql_query($sql) or die(mysql_error().' -- WHILE RUNNING QUERY: <BR>'.$sql."<BR>");

			return TRUE;

		} else {

			return FALSE;

		}

	}

	

	//RETURNS A SINGLE VALUE OF A QUERY

	function getValue($query) {

		if(getCount($query) > 0) {

			return mysql_result(mysql_query($query),0);

		}

	}

	function getArray($query) {

		if($GLOBALS['debugModeInitiated'] == 'yes') {

			$runQuery = mysql_query($query) or die(mysql_error());

			if(getCount($query) > 0) {

				$arr = mysql_fetch_array($runQuery);

			} else {

				$arr[] = "";

			}

			return $arr;

		} else {

			$runQuery = @mysql_query($query);

			$arr = @mysql_fetch_array($runQuery);

			return $arr;

		}

	}

	

	

	

	function getFullArray($query) {

		if($GLOBALS['debugModeInitiated'] == 'yes') {

			$runQuery = mysql_query($query) or die(mysql_error());

			if(mysql_num_rows($runQuery) == 1) {

				$arr = mysql_fetch_array($runQuery);

			} else if(mysql_num_rows($runQuery) > 1) {

				$count = mysql_num_rows($runQuery);

				$finalArray = array();

				for($i = 0;$i < $count;$i++) {

					mysql_data_seek($runQuery, $i);

					$finalArray[] = mysql_fetch_array($runQuery);

				}

				$arr = $finalArray;

			} else {

				$arr[] = "";

			}

			return $arr;

		} else {

			$runQuery = @mysql_query($query);

			$count = @mysql_num_rows($runQuery);

			$finalArray = array();

			for($i = 0;$i < $count;$i++) {

				@mysql_data_seek($runQuery, $i);

				$finalArray[] = @mysql_fetch_array($runQuery);

			}

			$arr = $finalArray;

			return $arr;

		}

	}

	

	

	

	function getVars($query) {

		$arr = getArray($query);

		//if(count($arr) > 0) {

		if($GLOBALS['debugModeInitiated'] == 'yes') {

			echo "Functions.inc FetchVars (".count($arr).");<BR>\nQuery: ".$query."<BR>\n<BR>\n";

		}

		if(is_array($arr)) {

			if(count($arr) > 0) {

				foreach($arr AS $key => $value) {

					$GLOBALS[$key] = $value;

				}

			}

		}

		//}

		//return TRUE;

	}

	

	function getCount($query, $type = "", $countValue = "") {

		if($GLOBALS['echoDebug']) { $runQuery = mysql_query($query) or die("Error with SQL Query: $query<BR>The error was ".mysql_error().""); } else { $runQuery = @mysql_query($query); }

		if($type == "COUNT") {	

			return @mysql_result($runQuery,0,"COUNT(id)");

		} else if($type == "SUM") {

			if($countValue == "") {

				return mysql_result($runQuery,0,"SUM(id)");

			} else {

				return mysql_result($runQuery,0,$countValue);

			}

		} else {

			return mysql_num_rows($runQuery);

		}	

	}



	class ptcAPI {

		var $url; //PTCShop API URL

		var $regID; //SCRIPT REGISTRATION ID

		var $license; //SCRIPT LICENSE NUMBER

		var $scriptVersion; //CURRENT SCRIPT VERSION

		function ptcAPI($regID) {

			$this->url = 'http://www.ptcshop.com/api/scriptUpdate.api.php';

			$this->regID = $regID;

			$scriptVersion = getValue("SELECT scriptVersion FROM setupinfo LIMIT 1");

			$license = getValue("SELECT scriptLicense FROM setupinfo LIMIT 1");

			if($license == '') $license = 0;

			if($scriptVersion == '') $scriptVersion = 0;

			

			$this->license = $license;

			$this->scriptVersion = $scriptVersion;

			

			if($scriptVersion == 0) $this->detectScriptVersion();

		}

		

		function checkForUpdates() {

			$reqInfo .= "?action=checkForUpdates";

			$reqInfo .= "&regID=".$this->regID;

			$reqInfo .= "&license=".$this->license;

			$r = new HTTPRequest($this->url.$reqInfo);

			$result = $r->DownloadToString();

			$resultArray = explode(",",$result);

			if(count($resultsArray) == 2 && $resultsArray[0] == "0") {

				echo "Fatal Error: ".$resultsArray[1]."<BR>";

			} else {

				

			}

		}

		

		

		function detectScriptVersion() {

			$reqInfo .= "?action=getVersionHistory";

			$r = new HTTPRequest($this->url.$reqInfo);

			$result = $r->DownloadToString();

			$resultArray = explode(",",$result);

			if($resultArray[0] == '0') { exit("Failure retreiving updates list. Can't detect your script version.<BR>"); }

			if(count($resultsArray) > 2) {

				echo "Found ".(count($resultsArray)/2)." records.<BR>";

				for($i = 0;$i < (count($resultsArray)/2);$i=$i+2) {

					$key = $resultsArray[$i];

					$value = $resultsArray[$i+1];

					

				}

			} else {

				exit("Cannot retreive updates.<BR>");

			}

		}

		

	}

	

	if(class_exists('HTTPRequest')) {
		//SKIP CLASS DECLARATION
	} else {
		//NOT FOUND, DECLARE NEW CLASS
		class HTTPRequest {
	
			var $_fp;        // HTTP socket
	
			var $_url;        // full URL
	
			var $_host;        // HTTP host
	
			var $_protocol;    // protocol (HTTP/HTTPS)
	
			var $_uri;        // request URI
	
			var $_port;        // port
	
		   
	
			// scan url
	
			function _scan_url()
	
			{
	
				$req = $this->_url;
	
			   
	
				$pos = strpos($req, '://');
	
				$this->_protocol = strtolower(substr($req, 0, $pos));
	
			   
	
				$req = substr($req, $pos+3);
	
				$pos = strpos($req, '/');
	
				if($pos === false)
	
					$pos = strlen($req);
	
				$host = substr($req, 0, $pos);
	
			   
	
				if(strpos($host, ':') !== false)
	
				{
	
					list($this->_host, $this->_port) = explode(':', $host);
	
				}
	
				else
	
				{
	
					$this->_host = $host;
	
					$this->_port = ($this->_protocol == 'https') ? 443 : 80;
	
				}
	
			   
	
				$this->_uri = substr($req, $pos);
	
				if($this->_uri == '')
	
					$this->_uri = '/';
	
			}
	
		   
	
			// constructor
	
			function HTTPRequest($url)
	
			{
	
				$this->_url = $url;
	
				$this->_scan_url();
	
			}
	
		   
	
			// download URL to string
	
			function DownloadToString()
	
			{
	
				$crlf = "\r\n";
	
			   
	
				// generate request
	
				$req = 'GET ' . $this->_uri . ' HTTP/1.0' . $crlf
	
					.    'Host: ' . $this->_host . $crlf
	
					.    $crlf;
	
			   
	
				// fetch
	
				$this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
	
				fwrite($this->_fp, $req);
	
				while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
	
					$response .= fread($this->_fp, 1024);
	
				fclose($this->_fp);
	
			   
	
				// split header and body
	
				$pos = strpos($response, $crlf . $crlf);
	
				if($pos === false)
	
					return($response);
	
				$header = substr($response, 0, $pos);
	
				$body = substr($response, $pos + 2 * strlen($crlf));
	
			   
	
				// parse headers
	
				$headers = array();
	
				$lines = explode($crlf, $header);
	
				foreach($lines as $line)
	
					if(($pos = strpos($line, ':')) !== false)
	
						$headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
	
			   
	
				// redirection?
	
				if(isset($headers['location']))
	
				{
	
					$http = new HTTPRequest($headers['location']);
	
					return($http->DownloadToString($http));
	
				}
	
				else
	
				{
	
					return($body);
	
				}
	
			}
	
		}
	}
	function payzaPayment($user,$password,$amount,$receiveremail,$senderemail='',$purchasetype=3,$note = '',$currency='USD',$testmode = '0') {
		if($senderemail == '') $senderemail = $user;
		$var = array();
		//format and urlencode post variables 
		$var['USER'] = urlencode($user);
		$var['PASSWORD'] = urlencode($password);
		$var['AMOUNT'] = urlencode($amount);
		$var['CURRENCY'] = urlencode($currency);
		$var['RECEIVEREMAIL'] = urlencode($receiveremail);
		$var['SENDEREMAIL'] = urlencode($senderemail);
		$var['PURCHASETYPE'] = urlencode($purchasetype);
		$var['NOTE'] = urlencode($note);
		$var['TESTMODE'] = urlencode($testmode);
		foreach($var as $k => $v) $data .= $k."=".$v."&";
		//request the service to post and read the response
		$server = 'api.payza.com';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://' . $server . '/svc/api.svc/sendmoney');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		//UrlDecode the response
		parse_str (urldecode($result), $apiResponse);
		
		$returnCode = $apiResponse['RETURNCODE'];
		$referenceNumber = $apiResponse['REFERENCENUMBER'];
		$description = $apiResponse['DESCRIPTION'];
		$testMode = $apiResponse['TESTMODE'];
		
		//LOG THE ALERTPAY RETURN CODES
		logAPIPayment($apiResponse);
		
		if($returnCode == '100') {
			return array(TRUE,$referenceNumber);
		} else {
			return array(FALSE, $returnCode, $description, $referenceNumber);
		}
	}
	function logAPIPayment($response) {
		mysql_query("INSERT INTO payzaPayments (
			id,paymentDate,ipAddress,returnCode,referenceNumber,description,testMode
		) VALUES (
			'', NOW(), ".quote_smart($_SERVER['REMOTE_ADDR']).", ".quote_smart($response['RETURNCODE']).", ".quote_smart($response['REFERENCENUMBER']).", ".quote_smart($response['DESCRIPTION']).", ".quote_smart($response['TESTMODE'])."
		)");
	}
	function canUsePayzaAutopay($username,$requestAccount,$requestAmount) {
		$setupinfo = getArray("SELECT 
		usePayzaAutopay,freeMembersAutopay,autopayRequireOneManualValidation,autopayMinimum,autopayMaximum
		FROM setupinfo LIMIT 1");
		$return = array(TRUE,'');
		
		if($setupinfo['usePayzaAutopay'] != '1') $return = array(FALSE,'Autopay is disabled');
		$countOrders = "SELECT COUNT(fnum) FROM payrequest WHERE username = ".quote_smart($username)." AND (payout_method = 'payza' OR payout_method = 'payzaAutopay') AND paidOut = '1' AND payout_account = ".quote_smart($requestAccount)."";
		if($setupinfo['autopayRequireOneManualValidation'] == '1' && getValue($countOrders) == 0) {
			$return = array(FALSE,'No Manually Verified Payments.');
		}
		
		if($setupinfo['autopayMinimum'] > $requestAmount || $setupinfo['autopayMaximum'] < $requestAmount) $return = array(FALSE,'Does not meet minimum or maximum payment requirements.');
		
		if($setupinfo['freeMembersAutopay'] != '1') {
			$membership = getUserMembershipDetails($username);
			if($membership['status'] != TRUE) $return = array(FALSE,'Premium Membership is Required');
		}
		
		return $return;
	}
	function getUserMembershipDetails($username) {
		$membershipCount = getValue("SELECT COUNT(id) FROM memberships WHERE username = ".quote_smart($username)." AND active = '1' AND ( (startDate < NOW() AND endDate > NOW()) OR (lifetime = '1'))");
		if($membershipCount > 0) {
			$membership = getArray("SELECT membershipType,DATE(endDate) AS endDate,DATE(startDate) AS startDate,lifetime FROM memberships WHERE username = ".quote_smart($username)." AND active = '1' AND ( (startDate < NOW() AND endDate > NOW()) OR (lifetime = '1'))");
			$membershipDetails = getArray("SELECT * FROM membershiptypes WHERE id = ".quote_smart($membership['membershipType'])."");
			$return = array();
			$return['status'] = TRUE; //STATUS
			$return['startDate'] = $membership['startDate'];
			$return['endDate'] = $membership['endDate'];
			$return['lifetime'] = $membership['lifetime'];
			foreach($membershipDetails as $k => $v) $return[$k] = $v;
			return $return;
		} else {
			return array('status'=>FALSE);
		}
	}
	function getCommPrice($username,$type,$adID='') {
		global $adTypes,$setupinfo;
		$membership = getUserMembershipDetails($username);
		$ad = array();
		if($type == 'ptc' || $type == 'links') {
			$ad = $adTypes['links'];
		} else if($type == 'ptreadads' || $type == 'ptrad') {
			$ad = $adTypes['ptrad'];
		} else if($type == 'ptremail' || $type == 'ptr') {
			$ad = $adTypes['email'];
		} else if($type == 'ptsurvey' || $type == 'ptsurvey') {
			$ad = $adTypes['survey'];
		} else if($type == 'ptsignup' || $type == 'pts') {
			$ad = $adTypes['signup'];
		}
		if($membership['status'] == TRUE) {
			$bonusName = $ad['adBonus'];
			$bonus = $membership[$bonusName];
		}
		$prefix = $ad['prefix'];
		if($bonus == '' || $bonus == 0) $bonus = 1;
		if($ad['hasTypes'] == TRUE) {
			$qry = "SELECT creditID FROM `".$ad['table']."` WHERE `".$ad['idType']."` = ".quote_smart($adID)."";
			mysql_query($qry) or die("Error ".mysql_error()." running \"".$qry."\".<BR>(".$type.":".$adID."): ".print_r($ad,true)." <BR>LINE: ".__LINE__."");
			$creditID = getValue($qry);
		}
		if($creditID == '') $creditID = 0;
		$credits = explode(',',$setupinfo[$prefix.'PayAmounts']);
		$price = $credits[$creditID] * $bonus;
		return $price;
	}
	
	function getCommPayType($type,$adID='') {
		global $adTypes,$setupinfo;
		
		$ad = array();
		if($type == 'ptc' || $type == 'links') {
			$ad = $adTypes['links'];
		} else if($type == 'ptreadads' || $type == 'ptrad') {
			$ad = $adTypes['ptrad'];
		} else if($type == 'ptremail' || $type == 'ptr') {
			$ad = $adTypes['email'];
		} else if($type == 'ptsurvey' || $type == 'ptsurvey') {
			$ad = $adTypes['survey'];
		} else if($type == 'ptsignup' || $type == 'pts') {
			$ad = $adTypes['signup'];
		}
		$prefix = $ad['prefix'];
		if($ad['hasTypes'] == TRUE) {
			$qry = "SELECT creditID FROM `".$ad['table']."` WHERE `".$ad['idType']."` = ".quote_smart($adID)."";
			mysql_query($qry) or die("Error ".mysql_error()." running \"".$qry."\".<BR>(".$type.":".$adID."): ".print_r($ad,true)." <BR>LINE: ".__LINE__."");
			$creditID = getValue($qry);
		}
		if($creditID == '') $creditID = 0;
		$types = explode(',',$setupinfo[$prefix.'PayTypes']);
		
		return $types[$creditID];
	}
	
	function totalBalanceAboveMinPay($coef = 1) {
		//QUERY TO SUM UP BALANCE AND COMPARE TO COEFFICIENT * MINIMUM PAYMENT VALUE
		$return = getValue("SELECT
		SUM(
		(SELECT ABS(SUM(famount)) FROM debit WHERE fid = users.username AND type = 'usd' AND famount < 0)
		-
		(
		(SELECT ABS(SUM(famount)) FROM debit WHERE fid = users.username AND type = 'usd' AND famount > 0)
		+
		(SELECT ABS(SUM(famount)) FROM payrequest WHERE payrequest.username = users.username AND paidOut = '0')
		)
		) AS totalBalance FROM users HAVING totalBalance > (SELECT minpay * ".quote_smart($coef)." FROM setupinfo)
		");
		//RETURN THE RESULT
		return $return;
	}
	function payCommissions($username, $price, $type,$creditType='usd') {
		$setupinfo = getArray("SELECT * FROM setupinfo LIMIT 1"); //GET THE SETUPINFO FOR THIS SCRIPT
		$user = getArray("SELECT * FROM users WHERE username = ".quote_smart($username)."");
		//$creditType = usd / points
		
		if($price > 0 && is_numeric($price)) {
			$debitFor = $type;
			$sql = mysql_query("INSERT INTO debit (fid, famount, fdate, debitFor,`type`) VALUES(".quote_smart($username).", ".quote_smart("-".$price).", DATE(NOW()), ".quote_smart($debitFor).",".quote_smart($creditType).")");
			
			//CALCULATE REFERRAL BONUS
			$levels = $setupinfo['levels']; //GET NUMBER OF LEVELS SYSTEM SUPPORTS
			$crefbonus = 0;
			for($i = 0;$i < $levels;$i++) {
				$tier = $i+1;
				if($i == 0) $field = 'frefer'; else $field = 'frefer'.$tier;
				$bonus = number_format(($price/100*$setupinfo['ref'.$tier.'bonus']),5);
				/*$taskCount = getValue("SELECT COUNT(fn) FROM tasks WHERE fvisits < fsize AND enddate < DATE(NOW())
				AND fn NOT IN (
					SELECT fnum FROM taskactivity WHERE username=".quote_smart($user[$field])." AND DATE(fdate)=DATE(now())
				)");
				
				if($taskCount > 0) {*/
					//SPONSOR HAS NOT CLICKED ALL OF TODAY'S AD'S
					//COMMET THE FOLLOWING CODE TO NOT CREDIT FOR REFERRALS ACTIVITIES
					//ADDED BACK IN FOR GLOBAL SUPPORT
					$refBonusQuery = "INSERT INTO debit (fid, famount, fdate, debitFor,`type`) SELECT username,CONCAT(-".quote_smart($bonus)."), DATE(NOW()), CONCAT(".quote_smart($type.'RefBonus')."),CONCAT(".quote_smart($creditType).") FROM users WHERE username = ".quote_smart($user[$field])."";
					mysql_query($refBonusQuery);/*
				} else if($taskCount == 0) {
					$refBonusQuery = "INSERT INTO debit (fid, famount, fdate, debitFor,`type`) SELECT username,CONCAT(-".quote_smart($bonus)."), DATE(NOW()), CONCAT(".quote_smart($type.'RefBonus')."),CONCAT(".quote_smart($creditType).") FROM users WHERE username = ".quote_smart($user[$field])."";
					mysql_query($refBonusQuery);
				}*/
			}
			
		}
		return TRUE;
	}
	function GetRandomString($length) {
		// you could repeat the alphabet to get more randomness
		$template = "1234567890abcdefghijklmnopqrstuvwxyz";
		$rndstring = '';
		$a = 0;
		$b = 0;   
		for ($a = 0; $a <= $length; $a++) {
			$b = rand(0, strlen($template) - 1);
			$rndstring .= $template[$b];
		}
		return $rndstring;
	}
	function totalEarnings($uid) { //CALCULATE A USERS TOTAL USD ACCOUNT BALANCE
		global $setupinfo;
		//CALCULATE USER CURRENT BALANCE ( NOT INCLUDING DEBITS / WITHDRAWS
		$add = abs(getValue("SELECT SUM(famount) FROM debit WHERE fid = ".quote_smart($uid)." AND type = ".quote_smart('usd')." AND famount < 0"));
		//CALCULATE DEBITS AND WITHDRAWS / PAYOUTS
		$debit = abs(getValue("SELECT SUM(famount) FROM debit WHERE fid = ".quote_smart($uid)." AND type = ".quote_smart('usd')." AND famount > 0"));
		
		$withdrawRequests = abs(getValue("SELECT SUM(famount) FROM payrequest WHERE username = ".quote_smart($uid)." AND paidOut = '0'"));
		
		//GET FINAL TOTAL
		$total = $add - ($debit + $withdrawRequests); //ADD UP DEBITS AND EARNINGS
		$return = number_format($total, 5,".","");
	  	return $return;
	}
	function totalLinkCredits($username) {
		return number_format(getValue("
		SELECT SUM(a.credits)-SUM(b.credits) AS totalLinkCredits
		FROM creditadditions a, creditdebits b
		WHERE a.username = b.username
		AND b.username = ".quote_smart($username)."
		AND a.creditsFor = 'links'"),0,"","");
	}
	function totalBannerCredits($username, $adType = 'banner') {
		
		$creditsA = getValue("
		SELECT SUM(a.credits)
		FROM creditadditions a
		WHERE a.username = ".quote_smart($username)."
		AND a.creditsFor = ".quote_smart($adType)."");
		
		$creditsD = getValue("
		SELECT SUM(a.credits)
		FROM creditdebits a
		WHERE a.username = ".quote_smart($username)."
		AND a.creditsFor = ".quote_smart($adType)."");
		$credits = $creditsA - $creditsD;
		
		return number_format($credits,0,"","");
	}
	function totalFeaturedBannerCredits($username) {
		return number_format(getValue("
		SELECT SUM(a.credits)-SUM(b.credits) AS totalLinkCredits
		FROM creditadditions a, creditdebits b
		WHERE a.username = b.username
		AND b.username = ".quote_smart($username)."
		AND a.creditsFor = 'fbanners'"),0,"","");
	}
	function totalFeaturedAdCredits($username) {
		return number_format(getValue("
		SELECT SUM(a.credits)-SUM(b.credits) AS totalLinkCredits
		FROM creditadditions a, creditdebits b
		WHERE a.username = b.username
		AND b.username = ".quote_smart($username)."
		AND a.creditsFor = 'featuredads'"), 0,"","");
	}
	function totalPtrAdCredits($username) {
		return number_format(getValue("
		SELECT SUM(a.credits)-SUM(b.credits) AS totalLinkCredits
		FROM creditadditions a, creditdebits b
		WHERE a.username = b.username
		AND b.username = ".quote_smart($username)."
		AND a.creditsFor = 'ptrad'"), 0,"","");
	}
	function totalEmailCredits($username) {
		return number_format(getValue("
		SELECT SUM(a.credits)-SUM(b.credits) AS totalLinkCredits
		FROM creditadditions a, creditdebits b
		WHERE a.username = b.username
		AND b.username = ".quote_smart($username)."
		AND a.creditsFor = 'email'"), 0,"","");
	}
	function totalSignupCredits($username) {
		return number_format(getValue("
		SELECT SUM(a.credits)-SUM(b.credits) AS totalLinkCredits
		FROM creditadditions a, creditdebits b
		WHERE a.username = b.username
		AND b.username = ".quote_smart($username)."
		AND a.creditsFor = 'signup'"), 0,"","");
	}
	
	function totalCredits($username, $page) {
		$page = strtolower($page);
		if($page == 'featuredad' || $page == 'featuredads' || $page == 'fad' || $page == 'fads') {
			return totalFeaturedAdCredits($username);
		} else
		if($page == 'link' || $page == 'links') {
			return totalLinkCredits($username);
		} else
		if($page == 'ptrad') {
			return totalPtrAdCredits($username);
		} else
		if($page == 'banner' || $page == 'banners') {
			return totalFeaturedAdCredits($username);
		} else
		if($page == 'featuredbanner' || $page == 'featuredbanners' || $page == 'fbanner' || $page == 'fbanners') {
			return totalFeaturedAdCredits($username);
		} else
		if($page == 'email') {
			return totalEmailCredits($username);
		} else
		if($page == 'signup') {
			return totalSignupCredits($username);
		} else
		if($page == 'flinks') {
			return totalBannerCredits($username,'flinks');
		}  else
		if($page == 'ptrad') {
			return totalBannerCredits($username,'ptrad');
		} else {
			exit("Error controller: 201. An error report has been sent to administration.");
		}
	}
	function refundCredits($username, $type, $credits) {
		$query = mysql_query("INSERT INTO creditadditions
		(`username`, `credits`, `creditsFor`,`additionDate`,`orderID`)
		VALUES
		(".quote_smart($username).", ".quote_smart($credits).", ".quote_smart($type).",NOW(),'0')
		") or die(mysql_error());
	}
}

?>