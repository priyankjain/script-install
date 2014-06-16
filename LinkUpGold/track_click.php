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

if (($_GET[adlink]) AND (is_numeric($_GET[adlink]))) count_click('out','adlink',$_GET[adlink]);
elseif (($_GET['link']) AND (is_numeric($_GET['link']))) count_click('out','link',$_GET['link']);
elseif (($_GET['new']) AND (is_numeric($_GET['new']))) count_click('out','new',$_GET['new']);


?>