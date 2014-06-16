<?php
session_start();
echo "Current Session.<HR>";
if(count($_SESSION) == 0) {
	echo "No session found.<BR>";
} else {
	foreach($_SESSION as $k => $v) echo $k." = ".$v."<BR>";
	echo "<HR>";
	echo "New Session.<HR>";
	foreach($_SESSION as $k => $v) echo $k." = ".$v."<BR>";
	echo "<HR>";
	echo "END";
}
?>