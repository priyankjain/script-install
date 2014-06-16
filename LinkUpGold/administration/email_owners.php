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
check_admin('email_owners');

switch ($_GET[action]) {
case 'email_owners_show'	: email_owners_show($_GET[what]);
}
switch ($_POST[action]) {
case 'email_owners_sent'	: email_owners_sent($_POST);
}
email_owners_form();

#################################################################################
#################################################################################
#################################################################################

function email_owners_form($in) {
global $s;
$in = replace_array_text($in);
ih();
echo $s[info];
?>
<form action="email_owners.php" method="post"><?PHP echo check_field_create('admin') ?>
<input type="hidden" name="action" value="email_owners_sent">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Email All Owners of Links or Articles</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left"> 
These variables can be used for links:<br />
#%name%# for name of the owner<br />
#%n%# for link number<br />
#%url%# for link URL<br />
#%title%# for link title<br /><br />
These variables can be used for articles and blogs:<br />
#%name%# for name of the owner<br />
#%n%# for article number<br />
#%title%# for article title<br />
</td></tr>
<tr><td align="left">Subject: <input class="field10" type="text" style="width:650px;" name="subject" value="<?PHP echo $in[subject]; ?>"></td></tr>
<tr><td align="left">Text:<br /><textarea class="field10" style="width:700px;height:300px;" name="text"><?PHP echo $in[text]; ?></textarea></td></tr>
<tr><td align="left"> 
<input type="checkbox" name="onemailonly">Each owner should receive one email only<span class="text10"><br />If checked, each owner receives a single emai not depending of how many links/articles they own. The following variables can't be used: #%url%# #%title%# #%n%#.
</td></tr>
<tr><td align="left">Message format &nbsp;&nbsp;&nbsp;<input type="radio" name="htmlmail" value="0" checked> Text &nbsp;&nbsp;&nbsp;<input type="radio" name="htmlmail" value="1"> HTML</td></tr>
<tr><td align="left">
<input type="radio" value="l" name="what"> Send this message to owners of all links<br />
<input type="radio" value="a" name="what"> Send this message to owners of all articles<br />
<input type="radio" value="b" name="what"> Send this message to owners of all blogs<br />
<input type="radio" value="test" name="what" checked> Send only a test email to <input class="field10" type="text" style="width:650px;" name="test_email" value="<?PHP echo $s[mail]; ?>"><br /><br />
</td></tr>
<tr><td align="center"><input type=submit name=xx value="Send mass email" class="button10"></td></tr>
</table></form></td></tr></table>
<br />
<a href="email_owners.php?action=email_owners_show&what=l">Display emails of all link owners</a><br /><br />
<a href="email_owners.php?action=email_owners_show&what=a">Display emails of all owners of articles</a><br /><br />
<a href="email_owners.php?action=email_owners_show&what=b">Display emails of all owners of blogs</a><br /><br />
<?PHP
ift();
}

##########################################################################

function email_owners_sent($in) {
global $s;
if ((!$in[text]) OR (!$in[subject])) { $s[info] = info_line('Both fields are required'); email_owners_form($in); }    
if ($in[what] == 'test')
{ $line = $in[text];
  $line = unhtmlentities($line); $in[subject] = unhtmlentities(unreplace_once_html($in[subject]));
  my_send_mail('','',$in[test_email],$in[htmlmail],$in[subject],$line,1);
  $s[info] = info_line('Test email has been sent to '.$in[test_email]);
  email_owners_form($in);
}
if ($in[onemailonly]) $onemail = "group by email";
$table = $s[item_types_tables][$in[what]];
$emaily = dq("select * from $table $onemail",1);
while ($address = mysql_fetch_assoc($emaily))
{ set_time_limit(1000);
  $value[name] = $address[name]; $value[url] = $address[url]; $value[title] = $address[title]; $value[password] = $address[password]; $value[n] = $address[n];
  $line = $in[text];
  foreach ($value as $k=>$v) $line = str_replace("#%$k%#",$v,$line);
  $line = unhtmlentities(str_replace(chr(92),'',unreplace_once_html($line)));
  $in[subject]=unreplace_once_html($in[subject]); $in[subject]=unhtmlentities($in[subject]);
  my_send_mail('','',$address[email],$in[htmlmail],$in[subject],$line,1);
  $seznam .= "<br />$address[email]";
}
ih();
echo info_line('Mass email has been sent to:',$seznam);
ift();
}

##########################################################################

function email_owners_show($what) {
global $s;
ih();
$table = $s[item_types_tables][$what];
$title = 'Emails of all owners of '.$s[items_types_words][$what];
$q = dq("select email from $table group by email",1);
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">'.$title.'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center"><textarea class="field10" style="width:700px;height:300px;" name="text">';
while ($email = mysql_fetch_row($q)) echo "$email[0]\n";
echo '</textarea></td></tr></table></td></tr></table>';
ift();
}

##########################################################################
##########################################################################
##########################################################################

?>