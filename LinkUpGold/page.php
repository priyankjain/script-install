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
foreach ($_GET as $k => $v) { if ((!$v) AND (preg_match("/^([a-z0-9_]+)$/i",$k)) AND (file_exists($s[phppath].'/styles/_common/templates/'.$k.'.html'))) page_from_template($k.'.html',$a); }
if (($_GET[page]) AND (preg_match("/^([a-z0-9_]+)$/i",$_GET[page])) AND (file_exists($s[phppath].'/styles/_common/templates/'.$_GET[page].'.html'))) page_from_template($_GET[page].'.html',$a);

?>