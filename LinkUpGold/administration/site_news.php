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
check_admin('site_news');

switch ($_GET[action]) {
case 'news_home'			: news_home();
case 'news_edit'			: news_edit($_GET[n]);
case 'news_reset'			: news_reset($_GET[n]);
case 'news_delete'			: news_delete($_GET[n]);
case 'news_manage'			: news_manage($_GET);
}
switch ($_POST[action]) {
case 'news_edited'			: news_edited($_POST);
case 'news_created'			: news_created($_POST);
}
news_home();

#################################################################################
#################################################################################
#################################################################################

function news_home() {
global $s;
$q = dq("select * from $s[pr]site_news order by time desc",1);
while ($news = mysql_fetch_assoc($q))
{ $list_news .= '<tr>
  <td align="left"><span class="text10">'.$news[title].' ('.datum($news[time],0).')&nbsp;</span></td>
  <td align="center"><span class="text10">[<a href="site_news.php?action=news_edit&n='.$news[n].'">Edit</a>]</span></td>
  <td align="center"><span class="text10">[<a href="javascript: go_to_delete(\'Are you sure?\',\'site_news.php?action=news_delete&n='.$news[n].'\')">Delete</a>]</span></td>

  </tr>';
}
$list_news = stripslashes($list_news);
ih();
echo $s[info];
news_create_edit_form(0);
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">View & Edit & Delete Existing News</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
'.stripslashes($list_news).'
</table>
</td></tr></table>
<br />';
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan="3">Info</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left">
News are displayed on the Home Page. The number of news to display is editable in configuration. The home page shows news with the highest date.<br />
All existing news are displayed at '.$s[site_url].'/site_news.php.
</table></td></tr></table>
<br />';
ift();
}

#################################################################################

function news_create_edit_form($n) {
global $s;
if ($n) { $action = 'news_edited'; $title = 'Edit Site News Item'; }
else { $action = 'news_created'; $title = 'Create Site News Item'; $news[time] = $s[cas]; } 
$q = dq("select * from $s[pr]site_news where n = '$n'",1);
$news = mysql_fetch_assoc($q);
if (!$news[time]) $news[time] = $s[cas];
echo '<form action="site_news.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="'.$action.'">
<input type="hidden" name="n" value="'.$n.'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan="2">'.$title.'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" nowrap>Title</td>
<td align="left"><input class="field10" name="title" value="'.$news[title].'" style="width:650px;" maxlength=255></td>
</tr>
<tr>
<td align="left" nowrap>Subtitle</td>
<td align="left"><input class="field10" name="subtitle" value="'.$news[subtitle].'" style="width:650px;" maxlength=255></td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">Text </td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">'.get_fckeditor('details',$news[details],'AdminToolbar').'</td>
</tr>
<tr>
<td align="left" nowrap>Date </td>
<td align="left">'.date_select($news[time],'time').'</td>
</tr>
<tr>
<td align="left" nowrap>Numbers of related links</td>
<td align="left"><input class="field10" name="related_l" value="'.$news[related_l].'" style="width:650px;" maxlength=255><span class="text10"><br />Optional. You can enter multiple numbers divided by spaces.</span></td>
</tr>
<tr>
<td align="left" nowrap>Numbers of related articles</td>
<td align="left"><input class="field10" name="related_a" value="'.$news[related_a].'" style="width:650px;" maxlength=255><span class="text10"><br />Optional. You can enter multiple numbers divided by spaces.</span></td>
</tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table>
</form>
<br />';
}

#################################################################################

function news_created($in) {
global $s;
$time = get_timestamp($in[time][d],$in[time][m],$in[time][y],'end');
$in = replace_html($in);
dq("insert into $s[pr]site_news values(NULL,'$in[title]','$in[subtitle]','$in[details]','$in[related_l]','$in[related_a]','$time')",1);
$s[info] = info_line('News created');
news_home();
}

#################################################################################

function news_edit($n) {
global $s;
ih();
news_create_edit_form($n);
echo '<a href="site_news.php">News home</a>';
ift();
}

#################################################################################

function news_edited($in) {
global $s;
$time = get_timestamp($in[time][d],$in[time][m],$in[time][y],'end');
$in = replace_html($in);
dq("update $s[pr]site_news set title = '$in[title]', subtitle = '$in[subtitle]', details = '$in[details]',related_l = '$in[related_l]', related_a = '$in[related_a]', time = '$time' where n = '$in[n]'",1);
$s[info] = info_line('News created');
news_edit($in[n]);
}

#################################################################################

function news_delete($n) {
global $s;
dq("delete from $s[pr]site_news where n = '$n'",1);
$s[info] = info_line('News deleted');
news_home();
}

#################################################################################
#################################################################################
#################################################################################

?>