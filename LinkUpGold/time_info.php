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

error_reporting (E_ERROR | E_PARSE);
if (!is_numeric($_GET[hour])) exit;
switch ($_GET[action]) {
case 'image'	: image($_GET[hour]);
}

function image($hour) {
header ("Location: images/time_symbols/$hour.png");
}

?>