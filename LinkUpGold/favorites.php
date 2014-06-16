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
if (!is_numeric($_GET[n])) exit;
$_GET = replace_array_text($_GET);

if ($_GET[action]=='add') dq("insert into $s[pr]u_favorites values ('$s[LUG_u_n]','$_GET[what]','$_GET[n]')",1);
elseif ($_GET[action]=='remove') dq("delete from $s[pr]u_favorites where user = '$s[LUG_u_n]' AND what = '$_GET[what]' AND n = '$_GET[n]'",1);

header ("Location: $_SERVER[HTTP_REFERER]");
exit;

?>