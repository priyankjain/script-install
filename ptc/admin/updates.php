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
 loginCheck(); ?><script type="text/javascript" language="javascript">
	function updateStatus(statusMsg) {
		document.getElementById('txtStatus').innerHTML = statusMsg;
		return true;
	}
</script>
<div id="txtStatus">Javascript must be enabled for this web-page to properly work. For optimal performance, we recommend using FireFox 2.0 or greater.</div><?php
echo '<script type="text/javascript" language="javascript">updateStatus(\'Script Started\');</script>';
flush();
$r = new HTTPRequest('http://www.ptcshop.com/checkUpdates.php?scriptVersion=2.5.86');
echo '<script type="text/javascript" language="javascript">updateStatus(\'Update data retrieved...\');</script>';
flush();
$result = $r->DownloadToString();

echo '<script type="text/javascript" language="javascript">updateStatus(\''.str_replace("'","\'",str_replace("\n",'\n',$result)).'\');</script>';
flush();
?>