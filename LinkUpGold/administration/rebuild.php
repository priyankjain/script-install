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
if (($_GET[action]=='reset_rebuild_home') AND ($s[A_option]=='static')) { header ("Location: html_rebuild.php"); exit; }
include($s[phppath].'/administration/rebuild_functions.php');
check_admin('reset_rebuild');
ih();

switch ($_GET[action]) {
case 'reset_rebuild_home'		: reset_rebuild_home();
case 'do_daily_job'				: daily_job(0); break;
case 'do_reset_month'			: reset_month(0); break;
case 'do_count_stats'			: count_stats(0); break;
case 'do_delete_old'			: delete_old(0); break;
case 'do_update_popular'		: update_popular(0); break;

case 'do_delete_l_days'			: delete_l_days($_GET); break;
case 'do_rebuild_static_files'	: rebuild_static_files(0,0); break;

case 'do_recount_all_links'		: recount_all_links(1,$_GET[recount_category_l]); break;
case 'do_recount_all_articles'	: recount_all_articles(1,$_GET[recount_category_a]); break;
case 'do_recount_all_blogs'		: recount_all_blogs(1,$_GET[recount_category_b]); break;
case 'do_recount_all_videos'	: recount_all_videos(1,$_GET[recount_category_v]); break;
case 'do_recount_all_news'		: recount_all_news(1,$_GET[recount_category_n]); break;

case 'do_repair_path_cats'		: repair_path_cats($_GET[repair_path_category]); break;
case 'do_repair_path_l'			: repair_items_paths('l',$_GET[repair_path_l_category]); break;
case 'do_repair_path_a'			: repair_items_paths('a',$_GET[repair_path_a_category]); break;
case 'do_repair_path_b'			: repair_items_paths('b',$_GET[repair_path_a_category]); break;
case 'do_repair_path_v'			: repair_items_paths('v',$_GET[repair_path_v_category]); break;
case 'do_repair_path_n'			: repair_items_paths('n',$_GET[repair_path_n_category]); break;

case 'do_delete_expired_items'	: delete_expired_items(); break;
case 'reset_all_question'		: reset_all_question(); break;
case 'create_google_sitemap'	: create_google_sitemap(1); break;
case 'create_yahoo_sitemap'		: create_yahoo_sitemap(1); break;
case 'repair_image_sizes'		: repair_image_sizes($_GET); break;
case 'create_index'				: create_index(1); break;
}
switch ($_POST[action]) {
case 'reset_rebuild'			: reset_rebuild($_POST[what]);
case 'reset_all'				: reset_all();
}
reset_rebuild_home();

##################################################################################
##################################################################################
##################################################################################

function reset_rebuild_home() {
global $s;
load_times();
if ($s[info]) echo '<span class="text10a_bold">'.$s[info].'</span><br />';
?>
<form method="get" action="rebuild.php" name="form1">
<table border="0" width="99%" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Reset & Rebuild</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center" nowrap colspan=2><?PHP echo 'Current time '.datum(0,1) ?><br /><br /></td></tr>
<tr><td class="common_table_top_cell" colspan=2>Common Tasks</td></tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_daily_job" checked></td>
<td align="left">Daily job (this job last done: <?PHP echo datum($s[times_d],1); ?>)<br /><span class="text10">It recounts numbers of links and other statistic data which are available on the home page, rebuilds the home page, deletes unconfirmed links older than 24 hours, deletes unconfirmed user accounts older than 24 hours, deletes IP records, updates popular links, updates included files.<br />(This should be done automatically once a day, however if you need to do it manually, use this function)</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_reset_month"></td>
<td align="left">Reset monthly statistic (this job last done: <?PHP echo datum($s[times_m],1); ?>)<br /><span class="text10">It resets monthly statistic (numbers of incoming clicks/outgoing clicks) to zero.<br />(This should be done automatically once a month, however if you need to do it manually, use this function)</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_rebuild_static_files"></td>
<td align="left" nowrap>Update static files<span class="text10"><br>Home page, left, right columns on all pages<br>(Part on the daily job)</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_count_stats"></td>
<td align="left" nowrap>Recount statistic <span class="text10">(Part on the daily job)</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_delete_old"></td>
<td align="left">Delete old data<br /><span class="text10">It deletes unconfirmed links older than 24 hours, deletes unconfirmed user accounts older than 24 hours, deletes IP records<br />(Part on the daily job)</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_update_popular"></td>
<td align="left" nowrap>Update popular links, articles, blogs, videos and news <span class="text10">(Part on the daily job)</span></td>
</tr>
<tr><td class="common_table_top_cell" colspan=2>Sitemaps</td></tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="create_google_sitemap"></td>
<td align="left" nowrap>Create sitemap for Google<br /></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="create_yahoo_sitemap"></td>
<td align="left" nowrap>Create URL list for Yahoo<br /></td>
</tr>
<tr><td class="common_table_top_cell" colspan=2>Recount Items</td></tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_recount_all_links"></td>
<td align="left">Recount all links in category <select class="select10" name="recount_category_l"><option value="0">All categories</option><?PHP echo select_list_first_categories('l_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of links in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect. If the numbers are incorrect because a server error occurred when you have been moving categories from one parent category to another, use function "Repair path for links" before this one.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_recount_all_articles"></td>
<td align="left">Recount all articles in category <select class="select10" name="recount_category_a"><option value="0">All categories</option><?PHP echo select_list_first_categories('a_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of articles in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect. If the numbers are incorrect because a server error occurred when you have been moving categories from one parent category to another, use function "Repair path for articles" before this one.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_recount_all_blogs"></td>
<td align="left">Recount all blogs in category <select class="select10" name="recount_category_b"><option value="0">All categories</option><?PHP echo select_list_first_categories('b_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of blogs in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect. If the numbers are incorrect because a server error occurred when you have been moving categories from one parent category to another, use function "Repair path for blogs" before this one.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_recount_all_videos"></td>
<td align="left">Recount all videos in category <select class="select10" name="recount_category_v"><option value="0">All categories</option><?PHP echo select_list_first_categories('v_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of videos in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect. If the numbers are incorrect because a server error occurred when you have been moving categories from one parent category to another, use function "Repair path for videos" before this one.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_recount_all_news"></td>
<td align="left">Recount all news in category <select class="select10" name="recount_category_n"><option value="0">All categories</option><?PHP echo select_list_first_categories('n_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of news in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect. If the numbers are incorrect because a server error occurred when you have been moving categories from one parent category to another, use function "Repair path for news" before this one.</span></td>
</tr>
<tr><td class="common_table_top_cell" colspan=2>Repair Paths</td></tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_repair_path_l"></td>
<td align="left">Repair path for links in category <select class="select10" name="repair_path_l_category"><option value="0">All categories</option><?PHP echo select_list_first_categories('l_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of links in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect or if some categories are assigned in incorrect (parent) categories. It may be needed to use this function if a server error occurred when you have been moving categories from one parent category to another.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_repair_path_a"></td>
<td align="left">Repair path for articles in category <select class="select10" name="repair_path_a_category"><option value="0">All categories</option><?PHP echo select_list_first_categories('a_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of articles in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect or if some categories are assigned in incorrect (parent) categories. It may be needed to use this function if a server error occurred when you have been moving categories from one parent category to another.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_repair_path_b"></td>
<td align="left">Repair path for blogs in category <select class="select10" name="repair_path_a_category"><option value="0">All categories</option><?PHP echo select_list_first_categories('a_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of blogs in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect or if some categories are assigned in incorrect (parent) categories. It may be needed to use this function if a server error occurred when you have been moving categories from one parent category to another.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_repair_path_v"></td>
<td align="left">Repair path for videos in category <select class="select10" name="repair_path_v_category"><option value="0">All categories</option><?PHP echo select_list_first_categories('v_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of videos in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect or if some categories are assigned in incorrect (parent) categories. It may be needed to use this function if a server error occurred when you have been moving categories from one parent category to another.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_repair_path_n"></td>
<td align="left">Repair path for news in category <select class="select10" name="repair_path_n_category"><option value="0">All categories</option><?PHP echo select_list_first_categories('n_noalias',0) ?></select> and its subcategories<br /><span class="text10">Use this function if numbers of news in individual categories (these numbers on the home page or in a list of subcategories for a category) are incorrect or if some categories are assigned in incorrect (parent) categories. It may be needed to use this function if a server error occurred when you have been moving categories from one parent category to another.</span></td>
</tr>
<tr><td class="common_table_top_cell" colspan=2>Other Tasks</td></tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="create_index"></td>
<td align="left" nowrap>Repair index for searching<br /></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="repair_image_sizes"></td>
<td align="left">Resize all <select class="select10" name="resize_thumbnails"><option value="0">Full size images</option><option value="1">Thumbnails</option></select> for <select class="select10" name="resize_what"><option value="l">Links</option><option value="a">Articles</option><option value="b">Blogs</option></select> to width <input name="resize_w" size="4" class="field10">px, height <input name="resize_h" size="4" class="field10">px<br /><span class="text10">It resizes images selected by the entered options. Images that are smaller than the entered width and height will not be resized. Be very cautious, it doesn't check entered values, existing images are replaced without warning. It's recommended to backup existing images earlier than you run this function.</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="do_delete_expired_items"></td>
<td align="left" nowrap>Delete expired links, articles, blogs, news and videos. <span class="text10"><br />Removes all items which are no more valid (have been valid in the past)</span></td>
</tr>
<tr>
<td align="center" valign="top"><input type="radio" name="action" value="reset_all_question"></td>
<td align="left" nowrap>Reset overall statistic<br /><span class="text10">It resets overall statistic (numbers of incoming clicks/outgoing clicks) to zero.</span></td>
</tr>
<tr>
<td align="left" valign="top"><input type="radio" name="action" value="do_delete_l_days"></td>
<td align="left"> Delete daily statistics of links for 
<select class="select10" name="stat_delete_month"><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> 
<input name="stat_delete_year" size="4" maxlength="4" class="field10" value="2012"> and before
<br /><span class="text10">You should delete this statistic regulary in order to avoid too big (=slow) database.</span></td>
</tr>
<tr><td align="center" colspan=2><br /><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table>
</form><br />


<table width="90%" border="0" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan="2">Crontab Commands</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">You can use one of these commands to create a crontab command to run the daily job. Note that not each of these commands can be used on each server. If you are not sure which of them can work for you, ask your server admin for help or email us.<br>Please set it to run once a day at 12.01am (0.01). Once you configured the correct crontab command, uncheck the field which runs the Daily job automatically. This field is available in Configuration.</td></tr>
<?PHP
echo '<tr><td align="center">/usr/bin/wget --spider -q \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\'</td></tr>';
echo '<tr><td align="center">/usr/local/bin/wget --spider -q \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\'</td></tr>';
echo '<tr><td align="center">/usr/bin/lynx -dump \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\' >/dev/null</td></tr>';
echo '<tr><td align="center">/usr/local/bin/lynx -dump \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\' >/dev/null</td></tr>';
echo '<tr><td align="center">GET \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\'</td></tr>';
echo '<tr><td align="center">curl --silent \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\'</td></tr>';
echo '<tr><td align="center">/usr/bin/php -dump \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\' >/dev/null</td></tr>';
echo '<tr><td align="center">/usr/local/bin/php -dump \''.$s[site_url].'/rebuild.php?word='.$s[secretword].'\' >/dev/null</td></tr>';
?>
</table>
</td></tr></table>


<?PHP
ift();
}

##################################################################################
##################################################################################
##################################################################################

?>