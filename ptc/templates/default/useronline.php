<?php
$timeoutseconds 	= 300;                                                                                                 
$timestamp=time();
$timeout=$timestamp-$timeoutseconds;
$database = 'ptclive_ptc';
mysql_query("INSERT INTO useronline VALUES (".quote_smart($timestamp).",".quote_smart($_SERVER['REMOTE_ADDR']).",".quote_smart($_SERVER['PHP_SELF']).")") or die(mysql_error());
mysql_query("DELETE FROM useronline WHERE `usertime`<".quote_smart($timeout)."") or die(mysql_error());
$result=mysql_query("SELECT DISTINCT ip FROM useronline WHERE file=".quote_smart($_SERVER['PHP_SELF'])."") or die(mysql_error());
$user = mysql_num_rows($result);

if ($user==1) {
echo __("There is $user person online.");
} else {
echo __("There are $user people online.");
}
?>