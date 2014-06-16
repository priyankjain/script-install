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
if ($_GET[action]=='top') frame_top($_GET);
else frame_main();

##################################################################################
##################################################################################
##################################################################################

function frame_main() {
global $s,$m;
if (is_numeric($_GET[link])) $n = $_GET[link]; else problem($m[item_not_exists]);
if (is_numeric($_GET[c])) $c = $_GET[c];
$a = get_item_variables('l',$n);
if (!$a[n]) problem($m[item_not_exists]);
$a[c] = $c;
$a[code_url] = urlencode($a[url]); 
$a[meta_title] = $a[title];
$a[meta_description] = $a[description];
$a[meta_keywords] = str_replace("\n",', ',$a[keywords]);
page_from_template_no_headers('link_in_frame.html',$a);
}

##################################################################################

function frame_top($in) {
global $s;
if (!is_numeric($in[n])) exit;
$link = get_item_variables('l',$in[n]);
$link[item_details_url] = get_detail_page_url('l',$link[n],$link[rewrite_url],$in[c],1);

if ((!$in[c]) OR (!is_numeric($in[c]))) { $x = explode(' ',str_replace('_','',$link[c])); $in[c] = $x[0]; }
$category = get_category_variables($in[c]);
$link[category_name] = $category[name];
$link[category_url] = category_url('l',$category[n],0,$category[name],1,$category[pagename],$category[rewrite_url],'','');
$x = previous_next_links('l',$in[c],$link[c],$link[n],1); $link = array_merge((array)$link,(array)$x);

page_from_template('link_in_frame_top.html',$link);
}

##################################################################################
##################################################################################
##################################################################################

?>