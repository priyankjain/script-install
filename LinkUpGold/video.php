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
$s[selected_menu] = 3;
get_messages('video.php');
include($s[phppath].'/data/data_forms.php');
$_GET = replace_array_text($_GET);
if ($_GET[track_hit]) { track_item_hit('v',$_GET[n]); exit; }

item_details_page('v');

#############################################################################
#############################################################################
#############################################################################

?>