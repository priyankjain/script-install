<?php
if($_REQUEST['username'] != '' && $_REQUEST['password'] != '') {
	$id=$_REQUEST['username'];
	$password=$_REQUEST['password'];
	$sql=mysql_query("SELECT * FROM admins WHERE username=".quote_smart($id)." AND fpassword=".quote_smart($password)." AND active = '1'")or die(mysql_error());
	if(mysql_num_rows($sql) > 0){
		$id = $_REQUEST['username'];
		$_SESSION['admin'] = $id;
		mysql_query("UPDATE admins SET loginIpAddress = ".quote_smart($_SERVER['REMOTE_ADDR'])." WHERE username = ".quote_smart($_SESSION['admin'])."");
	}
}

include_once('../include/cfg.php');
include_once("../include/dbconnect.php");
include_once('../include/functions.php');

loginCheck();
include("dbSnapshot.php");

echo 'Running database snapshot system : '.$snapshot.'<BR>';

$sql = mysql_query("SHOW TABLES;");
$count = mysql_num_rows($sql);

$database2 = array();
$db2Fields = array();
for($i = 0;$i < $count;$i++) {
	mysql_data_seek($sql, $i);
	$arr = mysql_fetch_array($sql);
	$database2[] = $arr[0];
	$tempSql = mysql_query("SHOW COLUMNS FROM `".$arr[0]."`");
	$tempCount = mysql_num_rows($tempSql);
	for($k = 0;$k < $tempCount;$k++) {
		mysql_data_seek($tempSql, $k);
		$tempArr = mysql_fetch_array($tempSql);
		$table = $arr[0];
		$db2Fields[$table][] = $tempArr;
	}
}

foreach($database1 as $k => $v) {
	if(!findInArray($v, $database2)) {
		$db1TableQueries[$v] = str_replace('\n', "\n", $db1TableQueries[$v]);
		mysql_query($db1TableQueries[$v]) or die(mysql_error()." running '".$db1TableQueries[$v]."'");
	} else {
		foreach($db1Fields[$v] as $tableName => $field) {
			if(!findInArray($field['Field'], $db2Fields[$v])) {
				$query = 'ALTER TABLE `'.$v.'` ADD `'.$field['Field'].'` '.$field['Type'].'';
				if($field['Null'] == 'NO') $query .= ' NOT NULL';
				if($field['Default'] != '') $query .= ' DEFAULT \''.$field['Default'].'\'';
				$query .= ';';
				mysql_query($query) or die(mysql_error()." running '".$query."'");
			} else {
				$thisField = $field['Field'];
				$fieldID = 0;
				for($fieldI = 0;$fieldI < count($db2Fields[$v]); $fieldI++) {
					if($db2Fields[$v][$fieldI]['Field'] == $field['Field']) {
						$fieldID = $fieldI; break;
					}
				}
				if($field['Type'] != $db2Fields[$v][$fieldID]['Type']) {
					$query = 'ALTER TABLE `'.$v.'` CHANGE `'.$field['Field'].'` `'.$field['Field'].'` '.$field['Type'].'';
					if($field['Null'] == 'NO') $query .= ' NOT NULL';
					if($field['Default'] != '') $query .= ' DEFAULT \''.$field['Default'].'\'';
					$query .= ';';
					mysql_query($query) or die(mysql_error()." running '".$query."'");
				}
			}
		}
	}
}

if(getValue("SELECT COUNT(id) FROM `templates`") == 0) mysql_query("
INSERT INTO `templates` (`id`, `templateName`, `templateIdentifier`, `active`) VALUES
(1, 'Default Template', 'default', 1);
");

if(getValue("SELECT COUNT(id) FROM `admins`") == 0) mysql_query("
INSERT INTO `admins` (`id`, `femail`, `fname`, `username`, `fpassword`, `loginIpAddress`, `active`, `privelages`) VALUES (1, 'ocnod1234@yahoo.com', 'PTCShop v2.5.6', 'admin', 'admin', '76.185.41.118', 1, 'superadmin');
");

if(getValue("SELECT COUNT(id) FROM `languages`") == 0) mysql_query("
INSERT INTO `languages` (`id`, `language`, `enabled`) VALUES
(1, 'auto', 0),
(2, 'sq', 0),
(3, 'ar', 0),
(4, 'bg', 0),
(5, 'ca', 0),
(6, 'zh-CN', 0),
(7, 'hr', 0),
(8, 'cs', 0),
(9, 'da', 0),
(10, 'nl', 0),
(11, 'en', 1),
(12, 'et', 0),
(13, 'tl', 0),
(14, 'fi', 0),
(15, 'fr', 0),
(16, 'gl', 0),
(17, 'de', 0),
(18, 'el', 1),
(19, 'iw', 0),
(20, 'hi', 0),
(21, 'hu', 0),
(22, 'id', 0),
(23, 'it', 0),
(24, 'ja', 0),
(25, 'ko', 0),
(26, 'lv', 0),
(27, 'lt', 0),
(28, 'mt', 0),
(29, 'no', 0),
(30, 'fa', 0),
(31, 'pl', 0),
(32, 'pt', 1),
(33, 'ro', 0),
(34, 'ru', 1),
(35, 'sr', 0),
(36, 'sk', 0),
(37, 'sl', 0),
(38, 'es', 1),
(39, 'sv', 0),
(40, 'th', 0),
(41, 'tr', 0),
(42, 'uk', 0),
(43, 'vi', 0);
");

echo 'The database snapshot system has run successfully and the system is currently at snapshot '.$snapshot.'.<BR>';



function findInArray($search, $array) {
	$return = FALSE;
	foreach($array as $k => $v) {
		if(is_array($v)) {
			if(findInArray($search, $v) === TRUE) $return = TRUE; 
		}
		else if($search == $v) $return = TRUE;
	}
	return $return;
}


?>