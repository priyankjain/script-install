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
$s[selected_menu] = 2;
get_messages('article.php');
include($s[phppath].'/data/data_forms.php');
$_GET = replace_array_text($_GET);
if ($_GET[action]=='print') print_article($_GET[n]);
if ($_GET[track_hit]) { track_item_hit('a',$_GET[n]); exit; }

item_details_page('a');

#############################################################################
#############################################################################
#############################################################################

function print_article($n) {
global $s;
$a = get_item_variables('a',$n,0);
check_access_rights('a',$a[c],'');
if ($a[t1]>$a[created]) $a[created] = datum($a[t1],0); else $a[created] = datum($a[created],0);
$a[charset] = $s[charset];
echo stripslashes(parse_part('article_print.html',$a));
exit;
}

#############################################################################
#############################################################################
#############################################################################

?>