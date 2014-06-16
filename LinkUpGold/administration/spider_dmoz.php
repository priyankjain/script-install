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
case 'dmoz_spider_show'		: dmoz_spider_show($_POST);
}
spider_form_dmoz_search();

#####################################################################################

function spider_form_dmoz_search() {
global $s;
ih();
echo '<form method="POST" action="spider_dmoz.php" id="myform">'.check_field_create('admin').'
<input type="hidden" name="action" value="dmoz_spider_show">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td colspan="2" class="common_table_top_cell">Import links from dmoz.org</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" nowrap>Category URL in dmoz.org</td>
<td align="left"><input class="field10" name="dmoz_url" style="width:650px;" value="'.$in[dmoz_url].'"></td>
</tr>';
if ((function_exists('iconv')) AND ($s[charset]!='UTF-8')) echo '<tr>
<td align="left" nowrap>Convert UTF-8 to '.$s[charset].' </td>
<td align="left"><input type="checkbox" name="convert" value="1"></td>
</tr>';
echo '
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

function dmoz_spider_show($in) {
global $s;
$dmoz_data = read_dmoz_page($in);
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
foreach ($dmoz_data[urls] as $k1=>$current_url)
{ if (!preg_match("/^(https?:\/\/+[\w\-]+\.[\w\-]+)/i", $current_url)) continue;
  set_time_limit(30);
  spider_show_one_link($current_url,$dmoz_data[titles][$k1],$dmoz_data[descriptions][$k1],'');
}
echo '</table></td></tr></table>
<br /><input type="submit" name="co" value="Import selected links" class="button10"></form><br />';
ift();
}

#####################################################################################
#####################################################################################
#####################################################################################

?>