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

//if (!$_SESSION[image_valid_code]) exit;
if ((!$_GET[n]) OR (!is_numeric($_GET[n]))) exit;
$a = get_item_variables('n',$_GET[n],0);
$a[code_url] = urlencode($a[url]); 
//foreach ($a as $k=>$v) echo "$k - $v<br>";
page_from_template_no_headers('news_in_frame.html',$a);
}

##################################################################################

function frame_top($in) {
global $s;

if ((!$in[n]) OR (!is_numeric($in[n]))) exit;
$a = get_item_variables('n',$in[n],0);

$a[item_details_url] = get_detail_page_url('n',$a[n],$a[rewrite_url],$in[c],1);

$x = explode(' ',str_replace('_','',$a[c])); $in[c] = $x[0]; $category = get_category_variables($in[c]); $a[category_name] = $category[name];
$a[category_url] = category_url('n',$category[n],0,$category[name],1,$category[pagename],$category[rewrite_url],'','');
$x = str_replace('link=','n=',str_replace('link_in_frame','news_in_frame',previous_next_links('n',$in[c],$a[c],$a[n],1))); $a = array_merge((array)$a,(array)$x);

page_from_template('news_in_frame_top.html',$a);
}

##################################################################################
##################################################################################
##################################################################################

?>