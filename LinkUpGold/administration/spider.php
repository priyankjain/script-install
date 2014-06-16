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

include('./links_functions.php');
check_admin('all_links');

switch ($_POST[action]) {
case 'import_spidered_links': import_spidered_links($_POST);
}

##################################################################################
##################################################################################
##################################################################################

function spider_show_one_link($url,$title,$description,$keywords) {
global $s;
$s[x]++;
if ($_POST[metatags]) $metatags = get_metatags($url);
if (trim($metatags[title])) $title = $metatags[title];
if (trim($metatags[description])) $description = $metatags[description];
if (trim($metatags[keywords])) $keywords = $metatags[keywords];
echo '<tr>
<td align="left" colspan="2"><span class="text13"><input class="bbb" type="checkbox" name="import_it[]" value="'.$s[x].'" checked> <a target="nove" href="'.$url.'">'.$title.'</a><br />'.$description.'<br /></td>
</tr>
<tr>
<td align="left"><span class="text13">URL: </td>
<td align="left"><input class="field10" name="url['.$s[x].']" style="width:650px;" maxlength=255 value="'.trim(str_replace('dir=rtl','',$url)).'"></td>
</tr>
<tr>
<td align="left"><span class="text13">Title: </td>
<td align="left"><input class="field10" name="title['.$s[x].']" style="width:650px;" maxlength=255 value="'.$title.'"></td>
</tr>
<tr>
<td align="left"><span class="text13">Description: </td>
<td align="left"><input class="field10" name="description['.$s[x].']" style="width:650px;" maxlength=255 value="'.replace_once_text($description).'"></td>
</tr>
<tr>
<td align="left"><span class="text13">Keywords: </td>
<td align="left"><input class="field10" name="keywords['.$s[x].']" style="width:650px;" value="'.$keywords.'"></td>
</tr>';
}

##################################################################################

function import_spidered_links($in) {
global $s;
ih();
echo '<span class="text10"><br /><b>These links have been imported:</b><br />';
foreach ($in[import_it] as $k=>$n)
{ //echo str_repeat (' ',4000); flush();
  set_time_limit(100);
  $b[url] = replace_once_text($in[url][$n]);
  $q = dq("select count(*) from $s[pr]links where url = '$b[url]'",1); $x = mysql_fetch_row($q);
  if ($x[0]) { echo '<a class="link10" href="'.$b[url].'" target="url_preview">'.$b[url].'</a> skipped - URL already listed'.str_repeat (' ',4000); flush(); echo "<br />\n"; continue; }
  $b[title] = replace_once_text($in[title][$n]);
  $b[description] = refund_html(replace_once_text($in[description][$n]));
  $b[keywords] = replace_once_text($in[keywords][$n]);
  $b[categories] = $in[link][0][categories];
  $n = enter_link($b);
  dq("insert into $s[pr]usit_search (use_for,n) values('l','$n')",1);
  update_item_index('l',$n);
  echo '<a href="'.$url.'" target="url_preview">'.stripslashes($b[title]).'</a>'.str_repeat (' ',4000); flush(); echo "<br />\n";
}
recount_items_cats('l',$in[link][0][categories][0],'');
echo '<br /><br /><b>ALL DONE</b><br /><br />';
ift();
}

######################################################################################
######################################################################################
######################################################################################

?>