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


$action = $_REQUEST['action'];
$tp = $_REQUEST['tp'];
if($action == 'logout' || $tp == 'logout') {
	$_SESSION['admin'] = '';
}

if($action=='Login to my account' || $action=='Cancel my membership' || $action=='Resend password') {
	$id=$_REQUEST['username'];
	$password=$_REQUEST['password'];
	$sql=mysql_query("SELECT * FROM admins WHERE username=".quote_smart($id)." AND fpassword=".quote_smart($password)." AND active = '1'")or die(mysql_error());
	if(mysql_num_rows($sql) > 0){
		$id = $_REQUEST['username'];
		$_SESSION['admin'] = $id;
		if(!isset($tp)) $tp = 'home';
		mysql_query("UPDATE admins SET loginIpAddress = ".quote_smart($_SERVER['REMOTE_ADDR'])." WHERE username = ".quote_smart($_SESSION['admin'])."");
	} else {
		$errorToDisplay = "The details you entered are invalid.";
	}
}

//FORGOTTEN PASSWORD ROUTINE
if($_REQUEST['toDo'] == 'forgotPassword') {
	$adminEmail = getValue("SELECT adminemail FROM setupinfo");
	$arr = @mysql_fetch_array(mysql_query("SELECT username, fpassword FROM admins ORDER BY id DESC LIMIT 1"));
	if(count($arr) == 0) {
		exit("Fatal error: No administrative accounts found.<BR>");
	} else {
		$message = "Your administrative login details are listed below.\n\nUsername: ".$arr['username']."\nPassword: ".$arr['fpassword']."\n\nThank you, \nPTCScript Automated Email System.\n\n";
		$mail = mail($adminEmail,'Your administrative password for your GPT Site.',$message);
		if($mail) {
			echo "An email has been sent to your administrative email address with your password.<BR>";
		} else {
			echo "An email failed to send. Please check that your php mail() function is setup properly.<BR>";
		}
	}
}

if($tp == '' || $tp == 'home') {
	$query = mysql_query("SELECT * FROM orders WHERE orderPaid = '0' ORDER BY id DESC LIMIT 0, 500");
	$count = mysql_num_rows($query);
	if($count > 0) {
		$dispMessage = "You have $count pending orders that need to be reviewed. <a href=\"index.php?tp=orderHistory&viewIncomplete=1\">Click Here</a> to view the order history.";
	} else {
	
	}
}

//EXPORT MEMBERS DATA FOR THIRD PARTY APPLICATIONS
if($_SESSION['admin'] != '' && $_REQUEST['toDo'] == 'exportMembersListNow') {
	$query = "SELECT ";
	
	if($_REQUEST['username'] == '1') {
		$query .= "`username` AS 'Username',";
		$numCount++;
	}
	
	if($_REQUEST['fname1'] == '1') {
		$query .= "`fname1` AS 'Name',";
		$numCount++;
	}
	
	if($_REQUEST['email'] == '1') {
		$query .= "`femail` AS 'Email Address',";
		$numCount++;
	}
	
	if($_REQUEST['address'] == '1') {
		$query .= "`faddress` AS 'Address',";
		$numCount++;
	}
	
	if($_REQUEST['city'] == '1') {
		$query .= "`fcity` AS 'City',";
		$numCount++;
	}
	
	if($_REQUEST['state'] == '1') {
		$query .= "`fstate` AS 'State',";
		$numCount++;
	}
	
	if($_REQUEST['zip'] == '1') {
		$query .= "`fzip` AS 'Zip Code',";
		$numCount++;
	}
	if($_REQUEST['country'] == '1') {
		$query .= "`fcountry` AS 'Country',";
		$numCount++;
	}
	if($_REQUEST['gender'] == '1') {
		$query .= "`fgender` AS 'Gender',";
		$numCount++;
	}
	if($_REQUEST['age'] == '1') {
		$query .= "`fage` AS 'Age',";
		$numCount++;
	}
	if($_REQUEST['income'] == '1') {
		$query .= "`fincoming` AS 'Income',";
		$numCount++;
	}
	
	if($_REQUEST['ipAddress'] == '1') {
		$query .= "`userip` AS 'IP Address',";
	}
	
	if($_REQUEST['regdate'] == '1') {
		$query .= "`regdate` AS 'Registration Date',";
	}
	
	
	
	if ($query{strlen($query)-1} == ',') { $query{strlen($query)-1} = ' '; }

	$query .= "FROM users ";
	
	$query .= "ORDER BY fid DESC";
	
	if($_REQUEST['Submit'] == 'XLS Spreadsheet') { 
		$sql = mysql_query($query) or die(mysql_error());
		$fields = mysql_num_fields($sql); 
		
		for ($i = 0; $i < $fields; $i++) { 
			$header .= mysql_field_name($sql, $i) . "\t"; 
		} 
		
		while($row = mysql_fetch_row($sql)) { 
			$line = '';
			foreach($row as $k => $value) {
				if($k == $numCount && $removePhoneSymbols == 'yes') { 
					$value = remSymbols($value);
				}                                 
				if ((!isset($value)) OR ($value == "")) {
					$value = "\t"; 
				} else {
					$value = str_replace('"', '""', $value); 
					$value = '"' . $value . '"' . "\t"; 
				}
				$line .= $value; 
			} 
			$data .= trim($line)."\n"; 
		} 
		$data = str_replace("\r","",$data);
		$date = date("m-d-y");
		header("Content-type: application/x-msdownload"); 
		header("Content-Disposition: attachment; filename=".$setupinfo['ptrname']."_".$date."_Members.xls"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		print "$header\n$data";
		exit;
	} else if($_REQUEST['Submit'] == 'CSV Comma Delimited') {
		$sql = mysql_query($query) or die(mysql_error());
		$fields = mysql_num_fields($sql); 
		
		for ($i = 0; $i < $fields; $i++) { 
			$header .= mysql_field_name($sql, $i) . ", ";
		} 
		
		while($row = mysql_fetch_row($sql)) { 
			$line = '';
			foreach($row as $k => $value) {    
				if($k == $numCount && $removePhoneSymbols == 'yes') { 
					$value = remSymbols($value);
				}
				if (!isset($value) || $value == "") {
					$value = ',';
				} else {
					$value = str_replace('"', '""', $value); 
					$value = '"' . $value . '"' . ",";
				}
				$line .= $value;
			} 
			$data .= trim($line)."\n"; 
		} 
		$data = str_replace("\r","",$data);
		$date = date("m-d-y");
		header("Content-type: application/x-msdownload"); 
		header("Content-Disposition: attachment; filename=".$setupinfo['ptrname']."_".$date."_Members.csv"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		print "$header\n$data";
		exit;
	} else if($_REQUEST['Submit'] == 'TXT (Emails only)') { 
		
		$query = "SELECT femail AS 'Email Address' FROM users ORDER BY fid DESC";
		
		
		$sql = mysql_query($query);
		$fields = mysql_num_fields($sql); 
		
		for ($i = 0; $i < $fields; $i++) { 
			//$header .= mysql_field_name($sql, $i) . ", ";
		} 
		
		while($row = mysql_fetch_row($sql)) { 
			$line = '';
			foreach($row as $k => $value) {    
				if($k == $numCount && $removePhoneSymbols == 'yes') { 
					$value = remSymbols($value);
				}
				if (!isset($value) || $value == "") {
					//$value = ',';
				} else {
					//$value = str_replace('"', '""', $value); 
					//$value = '"' . $value . '"' . ",";
				}
				$line .= $value;
			} 
			$data .= trim($line)."\n"; 
		} 
		$data = str_replace("\r","",$data);
		$date = date("m-d-y");
		header("Content-type: application/x-msdownload"); 
		header("Content-Disposition: attachment; filename=".$setupinfo['ptrname']."_".$date."_Members.txt"); 
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		print $data;
		exit;
	}
}



function displaySuccess($message) {
	echo '
	<div class="response-msg success ui-corner-all">
		<span>Success</span>
		'.$message.'
	</div>';
}
function displayError($message) {
	echo '
	<div class="response-msg error ui-corner-all">
		<span>An error has occurred</span>
		'.$message.'
	</div>';
}
function getTotalCommPayout($amount) {
	global $setupinfo;
	$total = 0;
	for($i = 1;$i < ($setupinfo['levels']+1);$i++) {
		$total = $total + $setupinfo['ref'.$i.'bonus'];
	}
	$comm = $amount * ($total/100);
	return $comm + $amount;
}
//REGISTER GLOBALS SMALL HACK FOR THIS SCRIPT, WILL BE MERGED OUT IN LATER VERSIONS
foreach($_REQUEST as $k => $v) $GLOBALS[$k] = $v;

?>