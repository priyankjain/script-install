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
$s[selected_menu] = 1;
get_messages('link.php');
include($s[phppath].'/data/data_forms.php');
$_GET = replace_array_text($_GET);




item_details_page('l');

#############################################################################

function show_rss_content1($url,$items) {
global $s;
require_once("$s[phppath]/rss_reader.php");
$rss = clsRSS($url,$s[charset],$items);
foreach ($rss as $k=>$item)
{ $b[url] = strip_tags($item[url]);
  $b[title] = strip_tags($item[title]);
  $b[description] = $item[description];
  if (!$s[rss_allow_html]) $b[description] = strip_tags(str_replace('&#60;','<',str_replace('&lt;','<',str_replace('&gt;','>',$b[description]))));
  $a .= parse_part('rss_one_item.txt',$b);
}
$a = stripslashes(str_replace('&#60;','<',str_replace('&lt;','<',str_replace('&gt;','>',str_replace('’',"'",str_replace('Â','',str_replace('©','',str_replace('&amp;','&',$a))))))));
$a = replace_once_html($a);
return $a;
}

function rss_replace($a) {
$a = stripslashes(str_replace('&#60;','<',str_replace('&lt;','<',str_replace('&gt;','>',str_replace('’',"'",str_replace('Â','',str_replace('©','',str_replace('&amp;','&',$a))))))));
return replace_once_html($a);
}

/*#%hide_rss_content_begin%#
<br />
<table border="0" cellspacing="0" cellpadding="2" width="100%">
#%rss_content%#
</table>
#%hide_rss_content_end%#
*/

#############################################################################
#############################################################################
#############################################################################

?>