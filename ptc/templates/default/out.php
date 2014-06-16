<?php
if($t == 'fb') {
	$count = getValue("SELECT COUNT(fnum) FROM fbanners WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	if($count > 0) {
		$arr = getArray("SELECT flink, fnum FROM fbanners WHERE fnum = ".quote_smart($_REQUEST['id'])."");
		$url = $arr['flink'];
		mysql_query("UPDATE fbanners SET fclicks = fclicks+1 WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	} else {
		exit(__("Invalid link..."));
	}
} else if($t == 'b'){
	$count = getValue("SELECT COUNT(fnum) FROM banners WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	if($count > 0) {
		$arr = getArray("SELECT flink, fnum FROM banners WHERE fnum = ".quote_smart($_REQUEST['id'])."");
		$url = $arr['flink'];
		mysql_query("UPDATE banners SET fclicks = fclicks+1 WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	} else {
		exit(__("Invalid link..."));
	}
} else if($t == 'fa'){
	$count = getValue("SELECT COUNT(fnum) FROM featuredads WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	if($count > 0) {
		$arr = getArray("SELECT flink, fnum FROM featuredads WHERE fnum = ".quote_smart($_REQUEST['id'])."");
		$url = $arr['flink'];
		mysql_query("UPDATE featuredads SET fclicks = fclicks+1 WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	} else {
		exit(__("Invalid link..."));
	}
} else if($t == 'flink'){
	$count = getValue("SELECT COUNT(fnum) FROM featuredlinks WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	if($count > 0) {
		$arr = getArray("SELECT flink, fnum FROM featuredlinks WHERE fnum = ".quote_smart($_REQUEST['id'])."");
		$url = $arr['flink'];
		mysql_query("UPDATE featuredlinks SET fclicks = fclicks+1 WHERE fnum = ".quote_smart($_REQUEST['id'])."");
	} else {
		exit(__("Invalid link..."));
	}
} else {
	exit(__("Invalid link..."));
}
if($url != '') {
	echo"<script>location.replace('$url')</script>";
} else {
	exit(__("url error..."));
}
?>
