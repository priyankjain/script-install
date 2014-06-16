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

include('./spider.php');

switch ($_POST[action]) {
case 'google_spider_show'	: google_spider_show($_POST);
}
spider_form_google_search();

#####################################################################################

function spider_form_google_search() {
global $s;
ih();
echo '<form method="POST" action="spider_google.php" id="myform">'.check_field_create('admin').'
<input type="hidden" name="action" value="google_spider_show">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td colspan="2" class="common_table_top_cell">Import search results from google.com</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" nowrap>Keywords </td>
<td align="left"><input class="field10" name="words" style="width:650px;" value="'.$in[words].'"></td>
</tr>
<tr>
<td align="left" nowrap>Without words </td>
<td align="left"><input class="field10" name="without_words" style="width:650px;" value="'.$in[without_words].'"></td>
</tr>
<tr>
<td align="left" nowrap>Number of links to request </td>
<td align="left"><select class="select10" name="number_of_links"><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="50">50</option><option value="100">100</option></td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Keywords should be in </td>
<td align="left" valign="top" nowrap>
<input type="radio" name="keywords_where" value=""'; if (!$in[keywords_where]) echo ' checked'; echo '> Anywhere<br />
<input type="radio" name="keywords_where" value="url"'; if ($in[keywords_where]=='url') echo ' checked'; echo '> URL<br />
<input type="radio" name="keywords_where" value="title"'; if ($in[keywords_where]=='title') echo ' checked'; echo '> Title<br />
<input type="radio" name="keywords_where" value="body"'; if ($in[keywords_where]=='body') echo ' checked'; echo '> Page text<br />
</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Import only pages in first level of a domain </td>
<td align="left" valign="top"><input type="checkbox" name="first_level" value="1"'; if ($in[first_level]) echo ' checked'; echo '></td>
</tr>
<tr>
<td align="left" valign="top">Read meta title, description and keywords from imported URL\'s and use them instead of texts provided by Google </td>
<td align="left" valign="top"><input type="checkbox" name="metatags" value="1"'; if ($in[metatags]) echo ' checked'; echo '></td>
</tr>
<tr><td colspan=2 align="center">
<input type="submit" value="Continue" name="B" class="button10">
</td></tr>
</table>
</td></tr></table>
</form>';
ift();
}

###################################################################################

function google_spider_show($in) {
global $s;
$google_data = read_google_page($in);
$without_words = explode(' ',$in[without_words]);
ih();
$s[l_max_cats] = 1;
echo '<form action="spider.php" method="post" id="myform">'.check_field_create('admin').'
<input type="hidden" name="action" value="import_spidered_links">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td colspan="2" class="common_table_top_cell">Review Links & Finish Import</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan="2">'.str_replace('Categories','Import selected links to category',categories_rows_form('l',$link)).'</td></tr>
<tr><td align="left" colspan="2">';
show_check_uncheck_all();
echo '</td></tr>';
//$urls = get_all_links_from_url($in[url]);
foreach ($google_data[urls] as $k1=>$current_url)
{ if (!preg_match("/^(https?:\/\/+[\w\-]+\.[\w\-]+)/i", $current_url)) continue;
  foreach ($without_words as $k=>$v) { $v = trim($v); if (($v) AND ((strstr($current_url,$v)) OR (strstr($google_data[titles][$k1],$v)) OR (strstr($google_data[descriptions][$k1],$v)))) continue 2; }
  set_time_limit(30);
  spider_show_one_link($current_url,$google_data[titles][$k1],$google_data[descriptions][$k1],'');
}
echo '</table></td></tr></table><br /><input type="submit" name="co" value="Import selected links" class="button10"></form><br />';
ift();
}

#####################################################################################

function read_google_page($in) {
global $s;
$standard_url = "http://www.google.com/search?num=$in[number_of_links]&as_ft=i&as_qdr=all&as_dt=i&oe=$s[charset]&ie=$s[charset]";
//$standard_url = "http://www.google.com/search?hl=en&num=$in[number_of_links]&as_ft=i&as_qdr=all&as_dt=i&oe=$s[charset]&ie=$s[charset]";
if ($in[without_words]) $standard_url .= "&as_eq=".str_replace(' ','+',$in[without_words]);
if ($in[keywords_where]) $standard_url .= "&as_occt=$in[keywords_where]";

$keywords_array = explode(' ',"$keywords $in[words]");
foreach ($keywords_array as $k1=>$v1) $keywords_array[$k1] = urlencode($v1);
$google_url = $standard_url.'&as_q='.implode('+',$keywords_array);
//echo $google_url; exit;
set_time_limit(60);
$page_content = fetchURL($google_url);

//echo $page_content;exit;

$a = explode('<h3 class="r">',$page_content); unset($a[0]);
//<h3 class="r">
//foreach ($a as $k=>$v) echo "$k - $v<br><br><br><br><br>\n\n\n\n\n\n\n";
//foreach ($a as $k=>$v) echo "$k - ".strip_tags($v)."<br><br><br><br><br>\n\n\n\n\n\n\n";
//exit;
foreach ($a as $k=>$v)
{ $v = str_replace(' dir=rtl','',$v);
  preg_match("/(<cite>)(.*)(<\/cite>)/",$v,$x); $this_url = strip_tags($x[0]); if ($this_url) $this_url = "http://$this_url";
  preg_match("/(class=l>)(.*)(<\/h3>)/",$v,$x); 
  $x = explode('</h3>',$v); $this_title = trim(strip_tags(str_replace('"','',$x[0])));
  preg_match("/(class=\"s\">)(.*)(<br>)/",$v,$x); $this_description = trim(strip_tags(str_replace('"','',$x[2]))); //echo $this_description; exit;
  //echo "((!$this_url) OR (!$this_title))";
  if ((!$this_url) OR (!$this_title)) continue;
  if ($in[first_level]) { $parsed_url = parse_url($this_url); $this_url = "http://$parsed_url[host]/"; }
  $pocet++;
  $titles[$pocet] = $this_title; $descriptions[$pocet] = $this_description; $urls[$pocet] = $this_url;
  unset($this_url,$this_title,$this_description);
}
return array('titles'=>$titles,'urls'=>$urls,'descriptions'=>$descriptions);
}

#####################################################################################
#####################################################################################
#####################################################################################

?>