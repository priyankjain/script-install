<?PHP

#################################################
##                                             ##
##               Link Up Gold                  ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                                             ##
##               version:  8.0                 ##
##            copyright (c) 2012               ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include('./common.php');
if ((!$s[facebook_id]) OR (!$s[facebook_secret])) exit;
require 'files/facebook/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => $s[facebook_id],
  'secret' => $s[facebook_secret],
));

if ((!$_GET[state]) OR (!$_GET[code]))
//if (!$user)
{ //print_r($_SESSION);
  $loginUrl = $facebook->getLoginUrl();
  echo 'document.write(\'<a href="'.$loginUrl.'"><img border="0" src="'.$s[site_url].'/images/facebook_login.gif"></a>\');';
  exit;
}

$user = $facebook->getUser();
if ($user)
{ //try { 
  $user_profile = $facebook->api('/me'); 
  //}
  //catch (FacebookApiException $e) { error_log($e); $user = null; }
}

//print_r($user_profile);

$q = dq("select * from $s[pr]users where username = '$user_profile[username].facebook'",1);
if (!mysql_num_rows($q))
{ $password = get_random_password();
  $user_profile = replace_array_text($user_profile);
  dq("insert into $s[pr]users values(NULL,'$user_profile[username].facebook','$password','$user_profile[username]@facebook.com','$user_profile[name]','$user_profile[name]','','','','','','','','','','','','','','','','','','','','','1','$s[cas]','1','1','$s[def_style]','0','0','0','0','0','0','0','0','0','0','0')",1);
  $n = mysql_insert_id();
  $user_vars = get_user_variables($n);
}
else $user_vars = mysql_fetch_assoc($q);

//foreach ($user_vars as $k => $v) echo "$k - $v<br>\n";

$_SESSION[LUG_u_username] = $user_vars[username];
$_SESSION[LUG_u_password] = $user_vars[password];
$_SESSION[LUG_u_n] = $user_vars[n];
$_SESSION[LUG_u_email] = $user_vars[email];
$_SESSION[LUG_u_style] = $user_vars[style];
$s[LUG_u_username] = $user_vars[username];
$s[LUG_u_password] = $user_vars[password];
$s[LUG_u_email] = $user_vars[email];
$s[LUG_u_n] = $user_vars[n];
$s[LUG_u_style] = $s[LUG_style] = $user_vars[style];

header ("Location: $s[site_url]/user.php?action=user_home"); exit;

?>