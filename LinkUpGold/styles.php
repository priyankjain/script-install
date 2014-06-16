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
$_SESSION['LUG_style'] = str_replace(' ','&nbsp;',$_GET[style]);
if ($_SERVER[HTTP_REFERER]) header("Location: $_SERVER[HTTP_REFERER]");
else header ("Location: $s[site_url]");

?>