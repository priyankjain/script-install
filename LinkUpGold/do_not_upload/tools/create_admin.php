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

$s['no_test'] = 1;
include('./common.php');

if (!$_POST) form();
else write_data($_POST);

function write_data($form) {
global $s;
ih();
$password = md5($form[password]);
dq("INSERT INTO $s[pr]admins VALUES (NULL,'$form[username]','$password','none@set.yet','',0)",1);
$n = mysql_insert_id();
$admin_rights = array(
'site_news',
'adlinks',
'ads',
'search_log',
'board',
'newsletter',
'email_users',
'users',
'polls',
'blacklist',
'email_owners',
'categories_news',
'all_news',
'news',
'categories_videos',
'all_videos',
'videos',
'categories_articles',
'all_articles',
'articles',
'categories_links',
'all_links',
'links',
'messages',
'templates',
'adv_prices_orders',
'admins',
'database_tools',
'configuration',
'reset_rebuild');
foreach ($admin_rights as $k=>$v) mysql_query("INSERT INTO $s[pr]admins_rights VALUES ('$n','$v')");
echo info_line("<br />Administrator $form[username] has been created.<br />Now remove file create_admin.php from your server.");
ift();
}





function form() {
global $s;
ih();
echo '<br />
<form method="POST" action="create_admin.php">
<table border=0 cellspacing=10 cellpadding=0 class="table1">
<tr><td colspan=2 align="center" nowrap><span class="text13b_bold">Create a new administrator with all available rights<br /></span>The username must not exist.<br />Both username and password should be 5-15 characters, letters and numbers only.</span></td></tr>
<tr>
<td align="right">Username</td>
<td align="left"><input class="field10" maxLength=15 size=15 name="username"></td>
</tr>
<tr>
<td align="right">Password</td>
<td align="left"><input class="field10" maxLength=15 size=15 name="password"></td>
</tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Save" class="button10"></td></tr>
</table></form>';
ift();
}

?>