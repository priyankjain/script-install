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
check_admin('configuration');
switch ($_POST[action]) {
case 'configuration_edited_main'		: configuration_edited_main($_POST);
}
configuration_edit_main();

#################################################################################
#################################################################################
#################################################################################

function configuration_edit_main() {
global $s,$info;
$item_types_words = $s[item_types_words];
$item_types_Words = $s[item_types_Words];
$items_types_words = $s[items_types_words]; unset($items_types_words[u],$items_types_words[c]);
$items_types_Words = $s[items_types_Words];
$item_types_scripts = $s[item_types_scripts];
foreach ($s as $k=>$v) if ($v==1) unset($s[$k]);
include("../data/data.php");
foreach ($s as $k => $v) $s[$k] = str_replace("&#039;","'",$v);
$s = stripslashes_array($s);

// replace tags from version 7
$test = strip_tags($s[new_img]); if ($test!=$s[new_img]) $s[new_img] = $s[site_url].'/images/corner_new.png';
$test = strip_tags($s[upd_img]); if ($test!=$s[upd_img]) $s[upd_img] = $s[site_url].'/images/corner_updated.png';
$test = strip_tags($s[pick_img]); if ($test!=$s[pick_img]) $s[pick_img] = $s[site_url].'/images/corner_pick.png';
$test = strip_tags($s[pop_img]); if ($test!=$s[pop_img]) $s[pop_img] = $s[site_url].'/images/corner_popular.png';

foreach ($s as $k=>$v) if ($k!='img') $s[$k] = htmlspecialchars($v);
$x = explode(',',$s[l_sort]); foreach ($x as $k => $v) { $l_sort[$k] = stripslashes($v); }
$x = explode(',',$s[a_sort]); foreach ($x as $k => $v) { $a_sort[$k] = stripslashes($v); }
$x = explode(',',$s[v_sort]); foreach ($x as $k => $v) { $v_sort[$k] = stripslashes($v); }
$x = explode(',',$s[n_sort]); foreach ($x as $k => $v) { $n_sort[$k] = stripslashes($v); }
$x = explode(',',$s[b_sort]); foreach ($x as $k => $v) { $b_sort[$k] = stripslashes($v); }

if (!$s[site_url])
{ $s[site_url] = str_replace('/administration/home.php?action=left_frame','',getenv('HTTP_REFERER'));
  $s[new_img] = $s[site_url].'/images/corner_new.png';
  $s[upd_img] = $s[site_url].'/images/corner_updated.png';
  $s[pick_img] = $s[site_url].'/images/corner_pick.png';
  $s[pop_img] = $s[site_url].'/images/corner_popular.png';
  $s[sitemap_location] = "$s[phppath]/sitemap.html";
  $s[g_sitemap_location] = "$s[phppath]/sitemap.xml";
  $s[y_sitemap_location] = "$s[phppath]/sitemap.txt";
  $s[logo_url] = "$s[site_url]/images/logo.png";
}
if (!$s[p_domain]) { $x = parse_url($s[site_url]); $s[p_domain] = str_replace('www.','',$x[host]); }
ih();
echo $info;
$entered_info = '<br /><span class="text10">You already entered it.</span>';
$entered_info1 = '***************';
if ($s[p_user]) { $i[p_user] = $entered_info; $i1[p_user] = $entered_info1; }
if ($s[p_pass]) { $i[p_pass] = $entered_info; $i1[p_pass] = $entered_info1; }
if ($s[dbusername]) { $i[dbusername] = $entered_info; $i1[dbusername] = $entered_info1; }
if ($s[dbpassword]) { $i[dbpassword] = $entered_info; $i1[dbpassword] = $entered_info1; }
foreach ($s as $k=>$v) if ( ((strstr($k,'twitter_')) OR (strstr($k,'facebook_'))) AND ($v) ) $s[$k] = $entered_info1;

?>
<form method="POST" action="configuration_main.php"><?PHP echo check_field_create('admin') ?>
<input type="hidden" name="action" value="configuration_edited_main">
<input type="hidden" name="pr" value="<?PHP echo $s[pr]; ?>">
<table border=0 width=98% cellspacing=0 cellpadding=0 class="common_table">
<tr><td colspan=2 class="common_table_top_cell">Configuration</td></tr>
<tr><td colspan=2 align="center">Fields marked by * are required. If you leave some of them blank, the script will not work properly.</td></tr>
<tr>
<td align="center" style="font-weight:bold;">
Show:  &nbsp;
<a href="javascript:show_config(0)" style="font-weight:bold;">Whole form</a> &nbsp;
<a href="javascript:show_config(1)" style="font-weight:bold;">Main configuration & Home page</a> &nbsp;
<a href="javascript:show_config(2)" style="font-weight:bold;">Links & Payments</a> &nbsp;
<a href="javascript:show_config(3)" style="font-weight:bold;">Blogs & Articles</a> &nbsp;
<a href="javascript:show_config(4)" style="font-weight:bold;">News & Videos</a> &nbsp;
<a href="javascript:show_config(5)" style="font-weight:bold;">Users & Other variables</a> &nbsp;
<a href="javascript:show_config(6)" style="font-weight:bold;">HTML Plugin</a> &nbsp;
</td>
</tr>
<tr><td align="center">
<tr><td>





<div id="config_1">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td colspan=2 class="common_table_top_cell" width="50%">Your License</td></tr>
<tr><td colspan=2 align="center" width="50%">If you don't remember these data, <a target="_blank" href="http://www.phpwebscripts.com/scripts/owner.php">click here</a> to find them out</td></tr>
<tr>
<td align="left" valign="top">Your username at PHPWebScripts.com users area *</td>
<td align="left" valign="top"><input class="field10" style="width:650px" name="p_user" value="<?PHP echo $i1[p_user] ?>"><?PHP echo $i[p_user] ?></td>
</tr>
<tr>
<td align="left" valign="top">Your password at PHPWebScripts.com users area *</td>
<td align="left" valign="top"><input class="field10" style="width:650px" name="p_pass" type="password" value="<?PHP echo $i1[p_pass] ?>"><?PHP echo $i[p_pass] ?></td>
</tr>
<tr>
<td align="left" valign="top">Name of the domain you purchased the license for</td>
<td align="left" valign="top"><input class="field10" maxLength=255 style="width:650px;" name="p_domain" value="<?PHP echo $s[p_domain]; ?>"><br />
<span class="text10">Correct: mydomain.com<br />Wrong: www.mydomain.com, http://mydomain.com/</span></td>
</tr>



<tr><td colspan=2 class="common_table_top_cell">Mysql Database Data</td></tr>
<tr><td align="center" valign="top" colspan="2">You can change these values only if you created a new database and copied there all tables.</td></tr>
<tr>
<td align="left" valign="top">Mysql database host *</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="dbhost" value="<?PHP echo $s[dbhost]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Name of your mysql database *</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="dbname" value="<?PHP echo $s[dbname]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Your mysql database username</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="dbusername" value="<?PHP echo $i1[dbusername] ?>"><?PHP echo $i[dbusername] ?></td>
</tr>
<tr>
<td align="left" valign="top">Mysql database password</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="dbpassword" value="<?PHP echo $i1[dbpassword] ?>"><?PHP echo $i[dbpassword] ?></td>
</tr>
<tr>
<td align="left" valign="top">Prefix of all tables *</td>
<td align="left" valign="top"><input class="field10" maxLength="10" style="width:100px" name="pr" value="<?PHP echo $s[pr]; ?>" disabled></td>
</tr>



<tr><td colspan=2 class="common_table_top_cell">Global Variables</td></tr>
<tr>
<td align="left" valign="top">Failed login attempts<br /><span class="text10">You can set up that after given number of failed attempts to log in, the system locks the account and also the IP address that sends such requests. This option is valid for accounts of users (publishers, advertisers) and also for accounts of admins. Let these fields blank to disable this option.</span><br /></td>
<td align="left" valign="top">Lock given account and IP address for <input class="field10" maxlength=5 size=5 name="log_fail_hours" value="<?PHP echo $s[log_fail_hours] ?>"> hours<br />after <input class="field10" maxlength=5 size=5 name="log_fail_max" value="<?PHP echo $s[log_fail_max] ?>"> failed attempts to log in<br /></td>
</tr>
<tr>
<td align="left" valign="top">Email admin when someone tried to log in with incorrect data<br /><span class="text10">It requires the option above to be enabled.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="log_fail_email" value="1"<?PHP if ($s[log_fail_email]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Secret word *<br /><span class="text10">It is a "password" for your script 'rebuild.php'. It may contain letters and numbers.</span></td>
<td align="left" valign="top"><input class="field10" style="width:100px" name="secretword" value="<?PHP echo $s[secretword]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Run Daily Job automatically<br /><span class="text10">Not recommended - read Manual for more info.</span></td>
<td align="left" valign="top"><input type="checkbox" name="rebuild_auto" value="1"<?PHP if ($s[rebuild_auto]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Automatically delete items which are no more valid<br /><span class="text10">You can set an expiration date for each item. Expired items are not visible on public pages. If you check this field, these items will be removed from database by the Daily Job.</span></td>
<td align="left" valign="top"><input type="checkbox" name="daily_delete_expired" value="1"<?PHP if ($s[daily_delete_expired]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Recount all items on each daily rebuild<br /></td>
<td align="left" valign="top"><input type="checkbox" name="daily_recount" value="1"<?PHP if ($s[daily_recount]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Default style </td>
<td align="left" valign="top"><select class="select10" name="def_style" value="<?PHP echo $s[def_style]; ?>">
<?PHP
$styles_list = get_styles_list(0);
foreach ($styles_list as $k=>$v)
{ if ($v==$s[def_style]) $selected = ' selected'; else $selected = '';
  echo '<option value="'.$v.'"'.$selected.'>'.str_replace('_',' ',$v).'</option>';
}
?>
</select>
</td></tr>
<tr>
<td align="left" valign="top">Number of _inX.txt templates which can be used<br /><span class="text10">These templates can be used to show content shared by multiple pages.<br>Use as low number of these templates as possible. The default value is 3.</span></td>
<td align="left" valign="top"><input class="field10" style="width:50px" name="in_templates" value="<?PHP echo $s[in_templates]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Full path to the folder where your scripts reside *<br /><span class="text10">No trailing slash</span></td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="phppath" value="<?PHP echo $s[phppath]; ?>"><br /><span class="text10">Example for Linux: /htdocs/sites/user/html<br />Example for Windows: C:/somefolder/domain.com</span></td>
</tr>
<tr>
<td align="left" valign="top">URL of the folder where your scripts reside *<br /><span class="text10">No trailing slash</span></td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="site_url" value="<?PHP echo $s[site_url] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Date format</td>
<td align="left" valign="top">
<select class="select10" name="date_form_1">
<option value="d"<?PHP if ($s[date_form_1]=='d') echo ' selected' ?>>dd</option>
<option value="m"<?PHP if ($s[date_form_1]=='m') echo ' selected' ?>>mm</option>
<option value="y"<?PHP if ($s[date_form_1]=='y') echo ' selected' ?>>yyyy</option>
</select>
<select class="select10" name="date_form_1a">
<option value="Space"<?PHP if ($s[date_form_1a]=='Space') echo ' selected' ?>>Space</option>
<option value="Nothing"<?PHP if ($s[date_form_1a]=='Nothing') echo ' selected' ?>>Nothing</option>
<option value="-"<?PHP if ($s[date_form_1a]=='-') echo ' selected' ?>>-</option>
<option value="/"<?PHP if ($s[date_form_1a]=='/') echo ' selected' ?>>/</option>
<option value="."<?PHP if ($s[date_form_1a]=='.') echo ' selected' ?>>.</option>
</select>
<select class="select10" name="date_form_2">
<option value="d"<?PHP if ($s[date_form_2]=='d') echo ' selected' ?>>dd</option>
<option value="m"<?PHP if ($s[date_form_2]=='m') echo ' selected' ?>>mm</option>
<option value="y"<?PHP if ($s[date_form_2]=='y') echo ' selected' ?>>yyyy</option>
</select>
<select class="select10" name="date_form_2a">
<option value="Space"<?PHP if ($s[date_form_2a]=='Space') echo ' selected' ?>>Space</option>
<option value="Nothing"<?PHP if ($s[date_form_2a]=='Nothing') echo ' selected' ?>>Nothing</option>
<option value="-"<?PHP if ($s[date_form_2a]=='-') echo ' selected' ?>>-</option>
<option value="/"<?PHP if ($s[date_form_2a]=='/') echo ' selected' ?>>/</option>
<option value="."<?PHP if ($s[date_form_2a]=='.') echo ' selected' ?>>.</option>
</select>
<select class="select10" name="date_form_3">
<option value="d"<?PHP if ($s[date_form_3]=='d') echo ' selected' ?>>dd</option>
<option value="m"<?PHP if ($s[date_form_3]=='m') echo ' selected' ?>>mm</option>
<option value="y"<?PHP if ($s[date_form_3]=='y') echo ' selected' ?>>yyyy</option>
</select>
<select class="select10" name="date_form_3a">
<option value="Space"<?PHP if ($s[date_form_3a]=='Space') echo ' selected' ?>>Space</option>
<option value="Nothing"<?PHP if ($s[date_form_3a]=='Nothing') echo ' selected' ?>>Nothing</option>
<option value="-"<?PHP if ($s[date_form_3a]=='-') echo ' selected' ?>>-</option>
<option value="/"<?PHP if ($s[date_form_3a]=='/') echo ' selected' ?>>/</option>
<option value="."<?PHP if ($s[date_form_3a]=='.') echo ' selected' ?>>.</option>
</select>
</td>
</tr>
<tr>
<td align="left" valign="top">Time format *</td>
<td align="left" valign="top"><select class="select10" name="time_form">
<OPTION value="12"<?PHP if ($s[time_form]=='12') echo ' selected' ?>>12 hours (3:25 pm)</option>
<OPTION value="24"<?PHP if ($s[time_form]=='24') echo ' selected' ?>>24 hours (15:25)</option>
</select></td>
</tr>
<tr>
<td align="left" valign="top">Difference (if exists) between time on the server and your local time. Only hours.</td>
<td align="left" valign="top"><input class="field10" maxlength=5 style="width:50px" name="timeplus" value="<?PHP echo $s[timeplus]/3600; ?>"><span class="text10"><br />Example: Time on server is 8:00 but your local time is 10:00, you will write number 2, time on server is 10:00 but your local time is 8:00, you will write number -2</span></td>
</tr>
<tr>
<td align="left" valign="top">Admin email *</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="mail" value="<?PHP echo $s[mail]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Title of your site *</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="site_name" value="<?PHP echo $s[site_name]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Meta keywords<span class="text10"><br><br>These keywords are used by default. Each category and detail page can have its own unique keywords.<br></span></td>
<td align="left" valign="top"><textarea class="field10" name="site_keywords" style="width:650px;height:250px;"><?PHP echo $s[site_keywords]; ?></textarea></td>
</tr>
<tr>
<td align="left" valign="top">Meta description<span class="text10"><br><br>This description is used by default. Each category and detail page can have its own unique description.<br></span></td>
<td align="left" valign="top"><textarea class="field10" name="site_description" style="width:650px;height:250px;"><?PHP echo $s[site_description]; ?></textarea></td>
</tr>
<tr>
<td align="left" valign="top">URL of your logo *</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="logo_url" value="<?PHP echo $s[logo_url]; ?>"><br><span class="text10">It's displayed at the top of all pages.<br></span></td>
</tr>
<tr>
<td align="left" valign="top">Banner HTML code<br><br><span class="text10">It's displayed at the top of the middle column of all pages.<br></span></td>
<td align="left" valign="top"><textarea class="field10" name="banner_code" style="width:650px;height:250px;"><?PHP echo $s[banner_code]; ?></textarea></td>
</tr>
<tr>
<td align="left" valign="top">Character set to use for pages and emails *</td>
<td align="left" valign="top"><input class="field10" style="width:100px" name="charset" value="<?PHP echo $s[charset]; ?>"><br /><span class="text10">Example: ISO-8859-1</span></td>
</tr>
<tr>
<td align="left" valign="top">Visible sections</td>
<td align="left" valign="top">
<?PHP
foreach ($items_types_Words as $what=>$word) { echo '<input type="checkbox" name="section_'.$what.'" value="1"'; if ($s["section_$what"]) echo ' checked'; echo '> '.$word.'<br>'; }
?>
</td>
</tr>
<tr>
<td align="left" valign="top">Icons of folders<br /><span class="text10">You can mark folders with different icons by the time when there have been added the last items.</span></td>
<td align="left" valign="top" nowrap>
<?PHP
for ($x=1;$x<=4;$x++)
echo '<img border="0" src="../images/icon_folder_'.$x.'.gif">&nbsp;Folders with items less than <input class="field10" size=5 name="icon_folder_t'.$x.'" value="'.$s["icon_folder_t$x"].'"> days ago<br />';
echo '<img border="0" src="../images/icon_folder_5.gif">&nbsp;All other folders<br />';
?>
</td>
</tr>
<!--
<tr>
<td align="left" valign="top">Style of links to previous/next pages</td>
<td align="left" valign="top"><select class="select10" name="drop_down">
<?PHP
if ($s[drop_down]) $drop_down = ' selected'; else $prev_next_links = ' selected';
echo "<option value=\"1\"$drop_down>Drop-down select menu</option>
<option value=\"0\"$prev_next_links>Classic links</option>";
?>
</select></td>
</tr>
-->
<tr>
<td align="left" valign="top">Display maximum of </td>
<td align="left" valign="top"><input class="field10"  style="width:50px" name="pages_max_links" value="<?PHP echo $s[pages_max_links] ?>"> links to previous/next pages<br /><span class="text10">Let it blank to display links to all pages<br /></span></td>
</tr>
<tr>
<td align="left" valign="top">Use Ajax to load individual pages in categories </td>
<td align="left" valign="top"><input type="checkbox" name="category_use_ajax" value="1"<?PHP if ($s[category_use_ajax]) echo ' checked'; ?>><br /><span class="text10">If checked, pages in categories load faster however some search engines may be able to index only the first page in each category.<br />This option needs the charset UTF-8 to be used for your site.<br /></span></td>
</tr>
<tr>
<td align="left" valign="top">Show QR codes </td>
<td align="left" valign="top"><input type="checkbox" name="show_qr" value="1"<?PHP if ($s[show_qr]) echo ' checked'; ?>></td>
</tr>
<!--
<tr>
<td align="left" valign="top">Allow radius search </td>
<td align="left" valign="top"><input type="checkbox" name="radius_search" value="1"<?PHP if ($s[radius_search]) echo ' checked'; ?>><br /><span class="text10">Detailed info about this feature is available <a href="latitudes.php">here</a>.<br /></span></td>
</tr>
<tr>
<td align="left" valign="top">Radius search uses </td>
<td align="left" valign="top" nowrap>
<select class="field10" name="km_miles">
<?PHP
$$s[km_miles] = ' selected';
echo "
<option value=\"km\"$km>Kilometres</option>
<option value=\"miles\"$miles>Miles</option>
";
?>
</select>
</td></tr>
-->
<tr>
<td align="left" valign="top">Values of user defined fields are available in items listing in categories </td>
<td align="left" valign="top"><input type="checkbox" name="usit_in_cats" value="1"<?PHP if ($s[usit_in_cats]) echo ' checked'; ?>><br /><span class="text10">You can keep it disabled to save system resources.</span></td>
</tr>
<tr>
<td align="left" valign="top">Only words with *</td>
<td align="left" valign="top"><input class="field10"  style="width:50px" name="search_min" value="<?PHP echo $s[search_min] ?>"> or more characters can be searched</span></td>
</tr>
<tr>
<td align="left" valign="top">Highlight searched words/phrases in search results</td>
<td align="left" valign="top"><input type="checkbox" name="search_highlight" value="1"<?PHP if ($s[search_highlight]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Search for whole words only<br /><span class="text10">Examples: If checked and someone searches for "and", it finds only items that contain the word "and". If not checked, it finds also words like "land".</span></td>
<td align="left" valign="top"><input type="checkbox" name="search_words" value="1"<?PHP if ($s[search_words]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">URL of the image to mark an editor's pick</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="pick_img" value="<?PHP echo $s[pick_img]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">URL of the image to mark new items</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="new_img" value="<?PHP echo $s[new_img]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">URL of the image to mark updated items</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="upd_img" value="<?PHP echo $s[upd_img]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">URL of the image to mark popular items</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="pop_img" value="<?PHP echo $s[pop_img]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Send all emails generated by scripts in HTML format</td>
<td align="left" valign="top"><input type="checkbox" name="htmlmail" value="1"<?PHP if ($s[htmlmail]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">SMTP server<br><span class="text10">Optional feature. If your server is unable to properly send emails, you can use a smtp server to send emails.</span></td>
<td align="left" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td align="left">Server </td>
<td align="left"><input class="field10" style="width:450px" name="smtp_server" value="<?PHP echo $s[smtp_server] ?>"></td>
</tr>
<tr>
<td align="left">Username </td>
<td align="left"><input class="field10" style="width:450px" name="smtp_username" value="<?PHP echo $s[smtp_username] ?>"></td>
</tr>
<tr>
<td align="left">Password </td>
<td align="left"><input class="field10" style="width:450px" name="smtp_password" value="<?PHP echo $s[smtp_password] ?>"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Mark aliases of categories by <br /><span class="text10">Alias is not a real category but a shortcut which leads to another category. Search engines usually use @ to mark such "categories".</span></td>
<td align="left" valign="top">Before name <input class="field10" size=5 name="alias_pref" value="<?PHP echo $s[alias_pref] ?>"> After name <input class="field10" size=5 name="alias_after" value="<?PHP echo $s[alias_after] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of items in each of the small tables in the right column  *</span></td>
<td align="left" valign="top"><input class="field10" size=5 name="right_column_items" value="<?PHP echo $s[right_column_items] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of columns of tables with subcategories *</td>
<td align="left" valign="top"><input class="field10" size=5 name="subc_column" value="<?PHP echo $s[subc_column] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Allow HTML tags in RSS content that we read from other pages </td>
<td align="left" valign="top"><input type="checkbox" name="rss_allow_html" value="1"<?PHP if ($s[rss_allow_html]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Load new RSS content once a *</td>
<td align="left" valign="top"><input class="field10" size=5 name="rss_read_interval" value="<?PHP echo $s[rss_read_interval] ?>"> minutes</td>
</tr>
<tr>
<td align="left" valign="top">RSS URL to show on the home page </td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="rss_home_page_url" value="<?PHP echo $s[rss_home_page_url] ?>"><br><span class="text10">This field is available also in each of the category and link edit forms.<br>You can have unique news in each category and link details page.</span></td>
</tr>
<tr>
<td align="left" valign="top">Items to take from this URL </td>
<td align="left" valign="top"><input class="field10" size=5 name="rss_home_page_items" value="<?PHP echo $s[rss_home_page_items] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Display only those user items which have a value<br /><span class="text10">On public pages will be displayed only those user items which have any value. Empty items will be invisible.</span></td>
<td align="left" valign="top"><input type="checkbox" name="filter_usit" value="1"<?PHP if ($s[filter_usit]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Allow PHP commands in templates<br /><span class="text10">You can use PHP code in templates, the opening tag must be &lt;?, not &lt;?PHP.<br />Don't use any command to print something, use command "$line .=" if you want to show some result on the place where is your PHP command.<br />Note that not all commands will work because there may occur a conflict between your commands and our code.</span></td>
<td align="left" valign="top"><input type="checkbox" name="php_templates" value="1"<?PHP if ($s[php_templates]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Ignored tags <span class="text10"><br><br>Top words from tags are listed in the right column of all pages and also are used for suggestions in the simple search form. Enter words which should never be used for these purposes (prepositions etc.), separated by comma.<br></span></td>
<td align="left" valign="top"><textarea class="field10" name="ignored_tags" style="width:650px;height:250px;"><?PHP echo $s[ignored_tags]; ?></textarea></td>
</tr>
<tr>
<td align="left" valign="top">Replace titles on pages with the query searched by Google
<br /><span class="text10">When a visitor came from Google, it replaces the current category, link, article, blog, news item or video title with the words searched by the visitor in Google. For example Google referred a visitor to your category "Computers". This visitor searched for the words "Apple Computers". Normally it would show the category name "Computers" at the top of the middle column. However in this case it replaces the category title with the words "Apple Computers" which have been searched by the visitor in Google.<br></span>
</td>
<td align="left" valign="top"><input type="checkbox" name="search_engine_titles" value="1"<?PHP if ($s[search_engine_titles]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Allow visitors to suggest new subcategories</td>
<td align="left" valign="top"><input type="checkbox" name="suggest_category" value="1"<?PHP if ($s[suggest_category]) echo ' checked'; ?>></td>
</tr>



<tr><td colspan=2 class="common_table_top_cell">Home Page</td></tr>
<tr>
<td align="left" valign="top">Number of columns of tables with categories *</td>
<td align="left" valign="top"><input class="field10" size=5 name="ind_column" value="<?PHP echo $s[ind_column] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of subcategories to list under each main category *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="max_subc" value="<?PHP echo $s[max_subc] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Character(s) to separate subcategories </td>
<td align="left" valign="top"><input class="field10" maxLength=10 size=5 name="ind_sep_subc" value="<?PHP echo $s[ind_sep_subc] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of columns of table(s) with groups of categories *</td>
<td align="left" valign="top"><input class="field10" maxLength=10 size=5 name="ind_column_group" value="<?PHP echo $s[ind_column_group] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Organize categories on the home page alphabetically in *<br /><span class="text10">This option is valid for normal listing of categories as well as for categories in groups</span></td>
<td align="left" valign="top"><input type="radio" name="in_sort_rows" value="1"<?PHP if ($s[in_sort_rows]) echo ' checked'; ?>> rows &nbsp;&nbsp;<input type="radio" name="in_sort_rows" value="0"<?PHP if (!$s[in_sort_rows]) echo ' checked'; ?>> columns</td>
</tr>
<tr>
<td align="left" valign="top">Number of site news to show on the home page *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="news_home" value="<?PHP echo $s[news_home]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Automatic style for languages <br /><span class="text10">If you use templates for multiple languages, the system can automatically detect the language used in user's browser and choose the correct style (templates). Languages are detected only on the public home page and only if the user did not selected another style manually before.</span></td>
<td align="left" valign="top">
<?PHP
include("$s[phppath]/language_detection.php");
$all_languages = languages();
for ($x=1;$x<=25;$x++)
{ echo 'Use style <select class="select10" name="language_style'.$x.'"><option value="0">None</option>';
  foreach ($styles_list as $k=>$v)
  { if ($v==$s["language_style$x"]) $selected = ' selected'; else $selected = '';
    echo '<option value="'.$v.'"'.$selected.'>'.str_replace('_',' ',$v).'</option>';
  }
  echo '</select> ';
  echo ' For language <select class="select10" name="language'.$x.'"><option value="0">None</option>';
  foreach ($all_languages as $k=>$v)
  { if ($k==$s["language$x"]) $selected = ' selected'; else $selected = '';
    echo "<option value=\"$k\"$selected>$v ($k)</option>";
  }
  echo '</select><br>';
}
?>
</td>
</tr>



<!--
<tr><td colspan=2 class="common_table_top_cell">Google Search Integration</td></tr>
<tr>
<td align="center" valign="top" colspan="2">To get the required values join Google Custom Search at <a target="_blank" href="http://www.google.com/coop/cse/">http://www.google.com/coop/cse/</a>. If you enter these values, the simple search form at the top of all pages will offer the option to search by Google.</td>
</tr>

<tr>
<td align="left" valign="top">Google search user ID </td>
<td align="left" valign="top"><input class="field10" style="width:500px" name="google_search_id" value="<?PHP echo $s[google_search_id]; ?>"></td>
</tr>
-->

</table>
</div>




<div id="config_2">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td colspan=2 class="common_table_top_cell">Links</td></tr>
<tr>
<td align="left" valign="top" width="50%">Links per page in categories *</td>
<td align="left" valign="top" width="50%"><input class="field10" size=5 name="l_per_page" value="<?PHP echo $s[l_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Links to display in special categories *<br /><span class="text10">It includes: New Links, Top Rated Links, Popular Links, Editor Picks<br /></span></td>
<td align="left" valign="top"><input class="field10" size=5 name="l_new_page" value="<?PHP echo $s[l_new_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Links per page on RSS pages *</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_rss_per_page" value="<?PHP echo $s[l_rss_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of columns of links listing in categories and search results *</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_columns" value="<?PHP echo $s[l_columns]; ?>"></td>
</tr>
<?PHP if ($s[l_marknew]) $s[l_marknew] = $s[l_marknew]/86400; ?>
<tr>
<td align="left" valign="top">Number of days to mark each link as New *</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_marknew" value="<?PHP echo $s[l_marknew]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of links to mark as Popular *</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_popular" value="<?PHP echo $s[l_popular]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of links in search result *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_search_max" value="<?PHP echo $s[l_search_max]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Each link can be listed in maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_max_cats" value="<?PHP echo $s[l_max_cats] ?>"> categories</td>
</tr>
<tr>
<td align="left" valign="top">Admin can upload maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_max_pictures" value="<?PHP echo $s[l_max_pictures] ?>"> pictures for each link</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by admin<br /><span class="text10">This option needs GD library.<br />Let these fields blank to keep original size of images.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="l_image_small_w" value="<?PHP echo $s[l_image_small_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="l_image_small_h" value="<?PHP echo $s[l_image_small_h] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="l_image_big_w" value="<?PHP echo $s[l_image_big_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="l_image_big_h" value="<?PHP echo $s[l_image_big_h] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Links should be sorted by</td>
<td align="left" valign="top" nowrap>
<select class="select10" name="l_sortby">
<?PHP
unset($asc,$desc,$title,$description,$rating,$hits,$pick,$votes,$hits_m,$clicks_in_m);
$$s[l_sortby] = ' selected';
echo "<option value=\"created\"$created>Date created</option>
<option value=\"title\"$title>Title</option>
<option value=\"description\"$description>Description</option>
<option value=\"pick\"$pick>Editor's pick</option>
<option value=\"rating\"$rating>Rating</option>
<option value=\"votes\"$votes>No. of votes</option>
<option value=\"hits_m\"$hits_m>Outgoing clicks current month (popularity)</option>
<option value=\"clicks_in_m\"$clicks_in_m>Incoming clicks current month</option>
";
?>
</select>
<select class="select10" name="l_sortby_direct">
<?PHP
$$s[l_sortby_direct] = ' selected';
echo "<option value=\"asc\"$asc>Ascending</option><option value=\"desc\"$desc>Descending</option>";
?>
</select>
</td></tr>
<tr><td align="left" valign="top">Sort links by editor's pick<br />
<span class="text10">If checked, links in categories will be sorted by editor's pick number, Links with a higher editor's pick number will be always above.</span></td>
<td align="left" valign="top">
<input type="checkbox" name="sort_pick" value="1"<?PHP if ($s[sort_pick]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left" valign="top">Allow users to sort links by</td>
<td align="left" valign="top">
<input type="checkbox" name="l_sort[]" value="title"<?PHP if (in_array('title',$l_sort)) echo ' checked'; ?>> Title<br />
<input type="checkbox" name="l_sort[]" value="description"<?PHP if(in_array('description',$l_sort)) echo ' checked'; ?>> Description<br />
<input type="checkbox" name="l_sort[]" value="created"<?PHP if (in_array('created',$l_sort)) echo ' checked'; ?>> Date created<br />
<input type="checkbox" name="l_sort[]" value="pick"<?PHP if (in_array('pick',$l_sort)) echo ' checked'; ?>> Editor's pick<br />
<input type="checkbox" name="l_sort[]" value="rating"<?PHP if (in_array('rating',$l_sort)) echo ' checked'; ?>> Rating<br />
<input type="checkbox" name="l_sort[]" value="votes"<?PHP if (in_array('votes',$l_sort)) echo ' checked'; ?>> Number of votes <br />
<input type="checkbox" name="l_sort[]" value="hits_m"<?PHP if (in_array('hits_m',$l_sort)) echo ' checked'; ?>> Clicks actual month (popularity)<br />
<input type="checkbox" name="l_sort[]" value="clicks_in_m"<?PHP if (in_array('clicks_in_m',$l_sort)) echo ' checked'; ?>> Clicks in actual month<br />
</span></td>
</tr>
<tr>
<td align="left" valign="top">Show site thumbnails from this URL </td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="l_thumbnail_url" value="<?PHP echo $s[l_thumbnail_url]; ?>">
<span class="text10"><br />
Keep blank to disable this feature.<br />
The variable #%domain%# will be replaced with the domain of each link.<br />
This service is available at <a target="_blank" href="http://www.thumbshots.com/">Thumbshots.com</a>, <a target="_blank" href="http://www.shrinktheweb.com/">Shrinktheweb.com</a> and other similar sites.
</span></td>
</tr>
<tr>
<td align="left" valign="top">Prefer icon "Updated"<br /><span class="text10">Normally, if any link is updated at the time when it is marked as new, it doesn't get an 'Updated' icon but still has a 'New' icon. If this is checked, this link gets an 'Updated' icon immediately. </span></td>
<td align="left" valign="top"><input type="checkbox" name="pref_upd" value="1"<?PHP if ($s[pref_upd]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of similar categories for each category *</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_max_simcats" value="<?PHP echo $s[l_max_simcats] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see categories <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_links_c" value="1"<?PHP if ($s[users_only_links_c]) echo ' checked' ?>></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see link details pages <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_links" value="1"<?PHP if ($s[users_only_links]) echo ' checked' ?>></td>
</tr>
<tr>
<td align="left" valign="top">Registered users can claim for existing links <br /><span class="text10">It shows a claim listing form on link detail pages for links which are not owned by a registered users. Users can inform you that they own the URL. You can review the request and optionally assign the listing to the user who claimed for it.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="allow_claim_l" value="1"<?PHP if ($s[allow_claim_l]) echo ' checked' ?>></td>
</tr>
<!--
<tr><td align="center" colspan=2><b>Links in right column (new, popular, top rated links, top searches)</td></tr>
-->






<tr><td colspan=2 class="common_table_top_cell">Advertising/Sponsored Links</td></tr>
<tr>
<td align="left" valign="top">Inform owners<br /><span class="text10">If an advertising link just used the last click or impression available, the link owner gets an email based on template "advertising_link_end.txt". </span></td>
<td align="left" valign="top"><input type="checkbox" name="inform_sponsors" value="1"<?PHP if ($s[inform_sponsors]) echo ' checked'; ?>></td>
</tr>
<tr><td align="center" colspan=2><span class="text10"><b>Fixed prices, dynamic prices, what's that?</b><br />Fixed Pricing is an established price you set which your users will pay for some number of clicks/impressions/days they receive. Dynamic Pricing allows your users to set a price which they want to pay for each click they receive. They can set a different price for each link they have. The higher the price, the higher the position on your pages.<br />Links with dynamic prices are always displayed over all other advertising links.<br /></span></td></tr>
<tr><td align="center" colspan=2>
<span class="text10"><b>Fixed prices</b><br />Set the prices you want your advertisers will pay for their links.<br />
Enter only numbers, not a curency symbol.<br />Correct values: 1.50 or 125; Wrong values: 1,50 or 1 1/4</span></td></tr>
<tr>
<td align="left" valign="top">Price for 100 impressions</td>
<td align="left" valign="top"><?PHP echo $s[currency] ?><input class="field10" maxLength=8 size=8 name="i_static_price" value="<?PHP echo $s[i_static_price]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Price for 100 clicks</td>
<td align="left" valign="top"><?PHP echo $s[currency] ?><input class="field10" maxLength=8 size=8 name="c_static_price" value="<?PHP echo $s[c_static_price]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Price for 1 day</td>
<td align="left" valign="top"><?PHP echo $s[currency] ?><input class="field10" maxLength=8 size=8 name="d_static_price" value="<?PHP echo $s[d_static_price]; ?>"></td>
</tr>
<tr><td align="center" colspan=2>
<span class="text10"><b>Dynamic prices</b><br />Set up minimum and maximum allowed price for one click.<br />
Enter only numbers, not a curency symbol.<br />Correct values: 0.01 or 1.7; Wrong values: 0,01 or 1 3/4</span></td></tr>
<tr>
<td align="left" valign="top">Minimum price for one click</td>
<td align="left" valign="top"><?PHP echo $s[currency] ?><input class="field10" maxLength=8 size=8 name="price_dynamic_min" value="<?PHP echo $s[price_dynamic_min]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum price for one click</td>
<td align="left" valign="top"><?PHP echo $s[currency] ?><input class="field10" maxLength=8 size=8 name="price_dynamic_max" value="<?PHP echo $s[price_dynamic_max]; ?>"></td>
</tr>






<tr><td colspan=2 class="common_table_top_cell">AdLinks</td></tr>
<tr>
<td align="left" valign="top">Maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="adlink_max_cats" value="<?PHP echo $s[adlink_max_cats] ?>"> categories can be selected for each AdLink</td>
</tr>
<tr>
<td align="left" valign="top">Display maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="adlinks_max_per_page" value="<?PHP echo $s[adlinks_max_per_page] ?>"> AdLinks per page</td>
</tr>
<tr>
<td align="left" valign="top">Minimum price for one click *</td>
<td align="left" valign="top"><input class="field10" maxLength=8 size=8 name="adlinks_price_min" value="<?PHP echo $s[adlinks_price_min]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum price for one click *</td>
<td align="left" valign="top"><input class="field10" maxLength=8 size=8 name="adlinks_price_max" value="<?PHP echo $s[adlinks_price_max]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Count only one click from each IP/day</td>
<td align="left" valign="top"><input type="checkbox" name="adlink_checkip" value="1"<?PHP if ($s[adlink_checkip]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Inform owners<br /><span class="text10">If an AdLink just used the last click, the AdLink owner gets an email based on template "adlink_end.txt".</span></td>
<td align="left" valign="top"><input type="checkbox" name="inform_adlink_owners" value="1"<?PHP if ($s[inform_adlink_owners]) echo ' checked'; ?>></td>
</tr>







<tr><td colspan=2 class="common_table_top_cell">Payments</td></tr>
<tr>
<td align="left" valign="top">Currency mark to use for prices *</td>
<td align="left" valign="top"><input class="field10" size=5 name="currency" value="<?PHP echo $s[currency] ?>"><span class="text10"> Example: $</span></td>
</tr>
<tr><td align="center" colspan=2><span class="text10"><b>Paypal data</b><br />These values are required if you want to let your users pay automatically by using Paypal.com (purchased funds are instantly available on user accounts)<br /></span></td></tr>
<tr>
<td align="left" valign="top">Currency to use for payments </td>
<td align="left" valign="top"><?PHP echo pp_currency_select('pp_currency',$s[pp_currency]) ?></td>
</tr>
<tr>
<td align="left" valign="top">Email address of your account </td>
<td align="left" valign="top"><input class="field10" maxLength=255 style="width:650px;" name="pp_email" value="<?PHP echo $s[pp_email] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Mode </td>
<td align="left" valign="top">
<input type="radio" name="pp_test" value="0"<?PHP if (!$s[pp_test]) echo ' checked'; ?>> Normal mode - all sales are real<br />
<input type="radio" name="pp_test" value="1"<?PHP if ($s[pp_test]) echo ' checked'; ?>> Test mode - test the payment system by using <a target="_blank" href="https://developer.paypal.com/">Paypal Sandbox</a>
</td>
</tr>
<tr><td align="center" colspan=2><span class="text10"><b>2CheckOut data</b><br />These values are required if you want to let your users pay automatically by using 2CheckOut.com (purchased funds are instantly available on user accounts)<br /></span></td></tr>
<tr>
<td align="left" valign="top">Account number </td>
<td align="left" valign="top"><input class="field10" style="width:100px" name="co_number" value="<?PHP echo $s[co_number] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Secret word </td>
<td align="left" valign="top"><input class="field10" style="width:100px" name="co_secret_word" value="<?PHP echo $s[co_secret_word] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Mode </td>
<td align="left" valign="top">
<input type="radio" name="co_test" value="0"<?PHP if (!$s[co_test]) echo ' checked'; ?>> Normal mode - all sales are real<br />
<input type="radio" name="co_test" value="1"<?PHP if ($s[co_test]) echo ' checked'; ?>> Test mode - test the payment system
</td>
</tr>
<tr><td align="center" colspan=2>
<span class="text10">Enter complete HTML code of a link or button leading to any other payment company, it may be online or offline payment service. You also can enter instructions for multiple payment companies. These variables can be used: #%price%# (price for the order), #%order%# (order number).<br />
</td></tr>
<tr><td align="center" colspan=2><span class="text10"><b>Another payment company</b></td></tr>
<tr>
<td align="left" valign="top">HTML code <br><br><span class="text10">Enter complete HTML code of a link or button leading to any other payment company, it may be online or offline payment service. You also can enter instructions for multiple payment companies. These variables can be used: #%price%# (price for the order), #%order%# (order number).<br /></td>
<td align="left" valign="top"><textarea class="field10" name="other_payment_com" style="width:650px;height:250px;"><?PHP echo $s[other_payment_com] ?></textarea></td>
</tr>
</table>
</div>




<div id="config_3">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td colspan=2 class="common_table_top_cell">Blogs</td></tr>
<tr>
<td align="left" valign="top" width="50%">Blogs per page in categories *</td>
<td align="left" valign="top" width="50%"><input class="field10" size=5 name="b_per_page" value="<?PHP echo $s[b_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of blogs to display in special categories *<br /><span class="text10">It includes: New Blogs, Top Rated Blogs, Popular Blogs, Editor Picks<br /></span></td>
<td align="left" valign="top"><input class="field10" size=5 name="b_new_page" value="<?PHP echo $s[b_new_page] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Blogs per page on RSS pages *</td>
<td align="left" valign="top"><input class="field10" size=5 name="b_rss_per_page" value="<?PHP echo $s[b_rss_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of columns of blogs listing in categories and search results *</td>
<td align="left" valign="top"><input class="field10" size=5 name="b_columns" value="<?PHP echo $s[b_columns]; ?>"></td>
</tr>
<?PHP if ($s[b_marknew]) $s[b_marknew] = $s[b_marknew]/86400; ?>
<tr>
<td align="left" valign="top">Number of days to mark each blog as New *</td>
<td align="left" valign="top"><input class="field10" size=5 name="b_marknew" value="<?PHP echo $s[b_marknew]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of blogs to mark as Popular *</td>
<td align="left" valign="top"><input class="field10" size=5 name="b_popular" value="<?PHP echo $s[b_popular]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of blogs in search result *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="b_search_max" value="<?PHP echo $s[b_search_max]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Allow users to sort blogs by</td>
<td align="left" valign="top">
<input type="checkbox" name="b_sort[]" value="title"<?PHP if (in_array('title',$b_sort)) echo ' checked'; ?>> Title<br />
<input type="checkbox" name="b_sort[]" value="created"<?PHP if (in_array('created',$b_sort)) echo ' checked'; ?>> Date created<br />
<input type="checkbox" name="b_sort[]" value="pick"<?PHP if (in_array('pick',$b_sort)) echo ' checked'; ?>> Editor's pick<br />
<input type="checkbox" name="b_sort[]" value="rating"<?PHP if (in_array('rating',$b_sort)) echo ' checked'; ?>> Rating<br />
<input type="checkbox" name="b_sort[]" value="votes"<?PHP if (in_array('votes',$b_sort)) echo ' checked'; ?>> Number of votes <br />
<input type="checkbox" name="b_sort[]" value="hits_m"<?PHP if (in_array('hits_m',$b_sort)) echo ' checked'; ?>> Number of reads current month<br />
</td>
</tr>
<tr>
<td align="left" valign="top">Sort blogs by default by</td>
<td align="left" valign="top" nowrap>
<select class="select10" name="b_sortby"><?PHP
unset($asc,$desc,$title,$description,$rating,$hits,$pick,$votes,$hits_m,$clicks_in_m);
$$s[b_sortby] = ' selected';
echo "<option value=\"created\"$created>Date created</option>
<option value=\"title\"$title>Title</option>
<option value=\"rating\"$rating>Rating</option>
<option value=\"pick\"$pick>Editor's pick</option>
<option value=\"votes\"$votes>No. of votes</option>
<option value=\"hits_m\"$hits_m>Number of reads current month</option>";
?></select>
<?PHP mc_test(); ?>
<select class="select10" name="b_sortby_direct">
<?PHP
$$s[b_sortby_direct] = ' selected';
echo "<option value=\"asc\"$asc>Ascending</option><option value=\"desc\"$desc>Descending</option>";
?>
</select>
</td></tr>
<tr><td align="left" valign="top">Sort blogs by editor's pick<br />
<span class="text10">If checked, blogs in categories will be sorted by editor's pick number, blogs with higher editor's pick number will be always above.</span></td>
<td align="left" valign="top">
<input type="checkbox" name="b_sort_pick" value="1"<?PHP if ($s[b_sort_pick]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left" valign="top">Each blog can be listed in maximum of * </td>
<td align="left" valign="top"><input class="field10" size=5 name="b_max_cats" value="<?PHP echo $s[b_max_cats] ?>"> categories</td>
</tr>
<tr>
<td align="left" valign="top">Admin can upload maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="b_max_pictures" value="<?PHP echo $s[b_max_pictures] ?>"> pictures for each blog</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by admin<br /><span class="text10">This option needs GD library.<br />Let these fields blank to keep original size of images.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="b_image_small_w" value="<?PHP echo $s[b_image_small_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="b_image_small_h" value="<?PHP echo $s[b_image_small_h] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="b_image_big_w" value="<?PHP echo $s[b_image_big_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="b_image_big_h" value="<?PHP echo $s[b_image_big_h] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of similar categories for each category *</td>
<td align="left" valign="top"><input class="field10" size=5 name="b_max_simcats" value="<?PHP echo $s[b_max_simcats] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see categories <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_blogs_c" value="1"<?PHP if ($s[users_only_blogs_c]) echo ' checked' ?>></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see blog details pages <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_blogs" value="1"<?PHP if ($s[users_only_blogs]) echo ' checked' ?>></td>
</tr>







<tr><td colspan=2 class="common_table_top_cell">Articles</td></tr>
<tr>
<td align="left" valign="top">Articles per page in categories *</td>
<td align="left" valign="top"><input class="field10" size=5 name="a_per_page" value="<?PHP echo $s[a_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of articles to display in special categories *<br /><span class="text10">It includes: New Articles, Top Rated Articles, Popular Articles, Editor Picks<br /></span></td>
<td align="left" valign="top"><input class="field10" size=5 name="a_new_page" value="<?PHP echo $s[a_new_page] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Articles per page on RSS pages *</td>
<td align="left" valign="top"><input class="field10" size=5 name="a_rss_per_page" value="<?PHP echo $s[a_rss_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of columns of articles listing in categories and search results *</td>
<td align="left" valign="top"><input class="field10" size=5 name="a_columns" value="<?PHP echo $s[a_columns]; ?>"></td>
</tr>
<?PHP if ($s[a_marknew]) $s[a_marknew] = $s[a_marknew]/86400; ?>
<tr>
<td align="left" valign="top">Number of days to mark each article as New *</td>
<td align="left" valign="top"><input class="field10" size=5 name="a_marknew" value="<?PHP echo $s[a_marknew]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of articles to mark as Popular *</td>
<td align="left" valign="top"><input class="field10" size=5 name="a_popular" value="<?PHP echo $s[a_popular]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of articles in search result *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="a_search_max" value="<?PHP echo $s[a_search_max]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Allow users to sort articles by</td>
<td align="left" valign="top">
<input type="checkbox" name="a_sort[]" value="title"<?PHP if (in_array('title',$a_sort)) echo ' checked'; ?>> Title<br />
<input type="checkbox" name="a_sort[]" value="created"<?PHP if (in_array('created',$a_sort)) echo ' checked'; ?>> Date created<br />
<input type="checkbox" name="a_sort[]" value="pick"<?PHP if (in_array('pick',$a_sort)) echo ' checked'; ?>> Editor's pick<br />
<input type="checkbox" name="a_sort[]" value="rating"<?PHP if (in_array('rating',$a_sort)) echo ' checked'; ?>> Rating<br />
<input type="checkbox" name="a_sort[]" value="votes"<?PHP if (in_array('votes',$a_sort)) echo ' checked'; ?>> Number of votes <br />
<input type="checkbox" name="a_sort[]" value="hits_m"<?PHP if (in_array('hits_m',$a_sort)) echo ' checked'; ?>> Number of reads current month<br />
</td>
</tr>
<tr>
<td align="left" valign="top">Sort articles by default by</td>
<td align="left" valign="top" nowrap>
<select class="select10" name="a_sortby"><?PHP
unset($asc,$desc,$title,$description,$rating,$hits,$pick,$votes,$hits_m,$clicks_in_m);
$$s[a_sortby] = ' selected';
echo "<option value=\"created\"$created>Date created</option>
<option value=\"title\"$title>Title</option>
<option value=\"rating\"$rating>Rating</option>
<option value=\"pick\"$pick>Editor's pick</option>
<option value=\"votes\"$votes>No. of votes</option>
<option value=\"hits_m\"$hits_m>Number of reads current month</option>";
?></select>
<?PHP mc_test(); ?>
<select class="select10" name="a_sortby_direct">
<?PHP
$$s[a_sortby_direct] = ' selected';
echo "<option value=\"asc\"$asc>Ascending</option><option value=\"desc\"$desc>Descending</option>";
?>
</select>
</td></tr>
<tr><td align="left" valign="top">Sort articles by editor's pick<br />
<span class="text10">If checked, articles in categories will be sorted by editor's pick number, articles with higher editor's pick number will be always above.</span></td>
<td align="left" valign="top">
<input type="checkbox" name="a_sort_pick" value="1"<?PHP if ($s[a_sort_pick]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left" valign="top">Each article can be listed in maximum of * </td>
<td align="left" valign="top"><input class="field10" size=5 name="a_max_cats" value="<?PHP echo $s[a_max_cats] ?>"> categories</td>
</tr>
<tr>
<td align="left" valign="top">Admin can upload maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="a_max_pictures" value="<?PHP echo $s[a_max_pictures] ?>"> pictures for each article</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by admin<br /><span class="text10">This option needs GD library.<br />Let these fields blank to keep original size of images.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="a_image_small_w" value="<?PHP echo $s[a_image_small_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="a_image_small_h" value="<?PHP echo $s[a_image_small_h] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="a_image_big_w" value="<?PHP echo $s[a_image_big_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="a_image_big_h" value="<?PHP echo $s[a_image_big_h] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of similar categories for each category *</td>
<td align="left" valign="top"><input class="field10" size=5 name="a_max_simcats" value="<?PHP echo $s[a_max_simcats] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see categories <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_articles_c" value="1"<?PHP if ($s[users_only_articles_c]) echo ' checked' ?>></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see article details pages <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_articles" value="1"<?PHP if ($s[users_only_articles]) echo ' checked' ?>></td>
</tr>
</table>
</div>




<div id="config_4">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td colspan=2 class="common_table_top_cell">Videos</td></tr>
<!--<tr>
<td align="left" valign="top">Youtube.com developer ID *</td>
<td align="left" valign="top"><input class="field10" maxLength=255 style="width:650px;" name="youtube_id" value="<?PHP echo $s[youtube_id]; ?>"><br><span class="text10">You can get yours for free at youtube.com. Click to the link "Developer Profile" inside their users area.</span></td>
</tr>-->
<tr>
<td align="left" valign="top" width="50%">Videos per page in categories *</td>
<td align="left" valign="top" width="50%"><input class="field10" size=5 name="v_per_page" value="<?PHP echo $s[v_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of videos to display in special categories *<br /><span class="text10">It includes: New Videos, Top Rated Videos, Popular Videos, Editor Picks<br /></span></td>
<td align="left" valign="top"><input class="field10" size=5 name="v_new_page" value="<?PHP echo $s[v_new_page] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Videos per page on RSS pages *</td>
<td align="left" valign="top"><input class="field10" size=5 name="v_rss_per_page" value="<?PHP echo $s[v_rss_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of columns of videos listing in categories and search results *</td>
<td align="left" valign="top"><input class="field10" size=5 name="v_columns" value="<?PHP echo $s[v_columns]; ?>"></td>
</tr>
<?PHP if ($s[v_marknew]) $s[v_marknew] = $s[v_marknew]/86400; ?>
<tr>
<td align="left" valign="top">Number of days to mark each video as New *</td>
<td align="left" valign="top"><input class="field10" size=5 name="v_marknew" value="<?PHP echo $s[v_marknew]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of videos to mark as Popular *</td>
<td align="left" valign="top"><input class="field10" size=5 name="v_popular" value="<?PHP echo $s[v_popular]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of videos in search result *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="v_search_max" value="<?PHP echo $s[v_search_max]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Allow users to sort videos by</td>
<td align="left" valign="top">
<input type="checkbox" name="v_sort[]" value="title"<?PHP if (in_array('title',$v_sort)) echo ' checked'; ?>> Title<br />
<input type="checkbox" name="v_sort[]" value="created"<?PHP if (in_array('created',$v_sort)) echo ' checked'; ?>> Date created<br />
<input type="checkbox" name="v_sort[]" value="pick"<?PHP if (in_array('pick',$v_sort)) echo ' checked'; ?>> Editor's pick<br />
<input type="checkbox" name="v_sort[]" value="rating"<?PHP if (in_array('rating',$v_sort)) echo ' checked'; ?>> Rating<br />
<input type="checkbox" name="v_sort[]" value="votes"<?PHP if (in_array('votes',$v_sort)) echo ' checked'; ?>> Number of votes <br />
<input type="checkbox" name="v_sort[]" value="hits_m"<?PHP if (in_array('hits_m',$v_sort)) echo ' checked'; ?>> Number of views current month<br />
</td>
</tr>
<tr>
<td align="left" valign="top">Sort videos by default by</td>
<td align="left" valign="top" nowrap>
<select class="select10" name="v_sortby"><?PHP
unset($asc,$desc,$title,$description,$rating,$hits,$pick,$votes,$hits_m,$clicks_in_m);
$$s[v_sortby] = ' selected';
echo "<option value=\"created\"$created>Date created</option>
<option value=\"title\"$title>Title</option>
<option value=\"rating\"$rating>Rating</option>
<option value=\"pick\"$pick>Editor's pick</option>
<option value=\"votes\"$votes>No. of votes</option>
<option value=\"hits_m\"$hits_m>Number of views current month</option>";
?></select>
<select class="select10" name="v_sortby_direct">
<?PHP
$$s[v_sortby_direct] = ' selected';
echo "<option value=\"asc\"$asc>Ascending</option><option value=\"desc\"$desc>Descending</option>";
?>
</select>
</td></tr>
<tr><td align="left" valign="top">Sort videos by editor's pick<br />
<span class="text10">If checked, videos in categories will be sorted by editor's pick number, videos with higher editor's pick number will be always above.</span></td>
<td align="left" valign="top">
<input type="checkbox" name="v_sort_pick" value="1"<?PHP if ($s[v_sort_pick]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left" valign="top">Each video can be listed in maximum of * </td>
<td align="left" valign="top"><input class="field10" size=5 name="v_max_cats" value="<?PHP echo $s[v_max_cats] ?>"> categories</td>
</tr>
<tr>
<td align="left" valign="top">Admin can upload maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="v_max_pictures" value="<?PHP echo $s[v_max_pictures] ?>"> pictures for each video</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by admin <br /><span class="text10">This option needs GD library.<br />Let these fields blank to keep original size of images.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="v_image_small_w" value="<?PHP echo $s[v_image_small_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="v_image_small_h" value="<?PHP echo $s[v_image_small_h] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="v_image_big_w" value="<?PHP echo $s[v_image_big_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="v_image_big_h" value="<?PHP echo $s[v_image_big_h] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Load new videos from youtube.com once a  </td>
<td align="left" valign="top"><input class="field10" size=5 name="v_load_interval_minutes" value="<?PHP echo $s[v_load_interval_minutes]; ?>"> minutes<br /><span class="text10">This is used as a default value for new categories. For existing categories this value is editable in their edit forms.<br /></span></td>
</tr>
<?PHP if (!ini_get("allow_url_fopen")) { $allow_url_fopen_disabled = ' disabled'; $allow_url_fopen_inactive = '<span class="text10"><br>This feature can\'t be used because the option "allow_url_fopen" in your php.ini file is set to Off. You can set it to On to make this feature available.<br></span>'; } ?>
<tr>
<td align="left" valign="top">Copy thumbnails from youtube.com to this server </td>
<td align="left" valign="top"><input type="checkbox" name="video_download_thumbnails" value="1"<?PHP if ($s[video_download_thumbnails]) echo ' checked'; echo $allow_url_fopen_disabled; ?>><?PHP echo $allow_url_fopen_inactive ?></td>
</tr>
<tr>
<td align="left" valign="top">Import maximum </td>
<td align="left" valign="top"><input class="field10" size=5 name="v_max_description_chars" value="<?PHP echo $s[v_max_description_chars]; ?>"> characters from video description</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of similar categories for each category *</td>
<td align="left" valign="top"><input class="field10" size=5 name="v_max_simcats" value="<?PHP echo $s[v_max_simcats] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see categories <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_videos_c" value="1"<?PHP if ($s[users_only_videos_c]) echo ' checked' ?>></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see video details pages <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_videos" value="1"<?PHP if ($s[users_only_videos]) echo ' checked' ?>></td>
</tr>


<tr><td colspan=2 class="common_table_top_cell">News</td></tr>
<tr>
<td align="left" valign="top">News per page in categories *</td>
<td align="left" valign="top"><input class="field10" size=5 name="n_per_page" value="<?PHP echo $s[n_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of news to display in special categories *<br /><span class="text10">It includes: New News, Top Rated News, Popular News, Editor Picks<br /></span></td>
<td align="left" valign="top"><input class="field10" size=5 name="n_new_page" value="<?PHP echo $s[n_new_page] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">News per page on RSS pages *</td>
<td align="left" valign="top"><input class="field10" size=5 name="n_rss_per_page" value="<?PHP echo $s[n_rss_per_page]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of columns of news listing in categories and search results *</td>
<td align="left" valign="top"><input class="field10" size=5 name="n_columns" value="<?PHP echo $s[n_columns]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Delete imported news after </td>
<td align="left" valign="top"><input class="field10" size=5 name="n_delete_imported_days" value="<?PHP echo $s[n_delete_imported_days]; ?>"> days<br><span class="text10">Keep blank to never delete imported news</span></td>
</tr>
<tr>
<td align="left" valign="top">Load news from RSS sites once a  </td>
<td align="left" valign="top"><input class="field10" size=5 name="n_load_interval_minutes" value="<?PHP echo $s[n_load_interval_minutes]; ?>"> minutes</td>
</tr>
<?PHP if ($s[n_marknew]) $s[n_marknew] = $s[n_marknew]/86400; ?>
<tr>
<td align="left" valign="top">Number of days to mark each news item as New *</td>
<td align="left" valign="top"><input class="field10" size=5 name="n_marknew" value="<?PHP echo $s[n_marknew]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of news to mark as Popular *</td>
<td align="left" valign="top"><input class="field10" size=5 name="n_popular" value="<?PHP echo $s[n_popular]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of news in search result *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="n_search_max" value="<?PHP echo $s[n_search_max]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Allow users to sort news by</td>
<td align="left" valign="top">
<input type="checkbox" name="n_sort[]" value="title"<?PHP if (in_array('title',$n_sort)) echo ' checked'; ?>> Title<br />
<input type="checkbox" name="n_sort[]" value="created"<?PHP if (in_array('created',$n_sort)) echo ' checked'; ?>> Date created<br />
<input type="checkbox" name="n_sort[]" value="pick"<?PHP if (in_array('pick',$n_sort)) echo ' checked'; ?>> Editor's pick<br />
<input type="checkbox" name="n_sort[]" value="rating"<?PHP if (in_array('rating',$n_sort)) echo ' checked'; ?>> Rating<br />
<input type="checkbox" name="n_sort[]" value="votes"<?PHP if (in_array('votes',$n_sort)) echo ' checked'; ?>> Number of votes <br />
<input type="checkbox" name="n_sort[]" value="hits_m"<?PHP if (in_array('hits_m',$n_sort)) echo ' checked'; ?>> Number of views current month<br />
</td>
</tr>
<tr>
<td align="left" valign="top">Sort news by default by</td>
<td align="left" valign="top" nowrap>
<select class="select10" name="n_sortby"><?PHP
unset($asc,$desc,$title,$description,$rating,$hits,$pick,$votes,$hits_m,$clicks_in_m);
$$s[n_sortby] = ' selected';
echo "<option value=\"created\"$created>Date created</option>
<option value=\"title\"$title>Title</option>
<option value=\"rating\"$rating>Rating</option>
<option value=\"pick\"$pick>Editor's pick</option>
<option value=\"votes\"$votes>No. of votes</option>
<option value=\"hits_m\"$hits_m>Number of views current month</option>";
?></select>
<select class="select10" name="n_sortby_direct">
<?PHP
$$s[n_sortby_direct] = ' selected';
echo "<option value=\"asc\"$asc>Ascending</option><option value=\"desc\"$desc>Descending</option>";
?>
</select>
</td></tr>
<tr><td align="left" valign="top">Sort news by editor's pick<br />
<span class="text10">If checked, news in categories will be sorted by editor's pick number, news with higher editor's pick number will be always above.</span></td>
<td align="left" valign="top">
<input type="checkbox" name="n_sort_pick" value="1"<?PHP if ($s[n_sort_pick]) echo ' checked'; ?>>
</td></tr>
<tr>
<td align="left" valign="top">Each news can be listed in maximum of * </td>
<td align="left" valign="top"><input class="field10" size=5 name="n_max_cats" value="<?PHP echo $s[n_max_cats] ?>"> categories</td>
</tr>
<tr>
<td align="left" valign="top">Admin can upload maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="n_max_pictures" value="<?PHP echo $s[n_max_pictures] ?>"> pictures for each news</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by admin <br /><span class="text10">This option needs GD library.<br />Let these fields blank to keep original size of images.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="n_image_small_w" value="<?PHP echo $s[n_image_small_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="n_image_small_h" value="<?PHP echo $s[n_image_small_h] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="n_image_big_w" value="<?PHP echo $s[n_image_big_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="n_image_big_h" value="<?PHP echo $s[n_image_big_h] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of similar categories for each category *</td>
<td align="left" valign="top"><input class="field10" size=5 name="n_max_simcats" value="<?PHP echo $s[n_max_simcats] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see categories <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_news_c" value="1"<?PHP if ($s[users_only_news_c]) echo ' checked' ?>></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can see news detail pages <br /><span class="text10">This can be enabled also for individual categories. The option to do it is in the category edit form.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="users_only_news" value="1"<?PHP if ($s[users_only_news]) echo ' checked' ?>></td>
</tr>
</table>
</div>




<div id="config_5">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td colspan=2 class="common_table_top_cell">Registered Users</td></tr>
<tr>
<td align="left" valign="top" width="50%">Allow only one account per email</td>
<td align="left" valign="top" width="50%"><input type="checkbox" name="user_one_acc" value="1"<?PHP if ($s[user_one_acc]) echo ' checked' ?>></td>
</tr>
<?PHP
echo '<tr>
<td align="left" valign="top">Only users approved by admin can log in </td>
<td align="left" valign="top"><input type="checkbox" name="user_no_auto" value="1"'; if ($s[user_no_auto]) echo ' checked'; echo '></td>
</tr>
<tr>
<td align="left" valign="top">Inform users by email that their accounts have been approved</td>
<td align="left" valign="top"><input type="checkbox" name="user_i_approved" value="1"'; if ($s[user_i_approved]) echo ' checked'; echo '></td>
</tr>
<tr>
<td align="left" valign="top">Users receive an email each time when an item has been added to some of the categories they bookmarked in these sections </td>
<td align="left" valign="top">';
foreach ($items_types_Words as $what=>$word) { echo '<input type="checkbox" name="bookmarks_cats_email_'.$what.'" value="1"'; if ($s["bookmarks_cats_email_$what"]) echo ' checked'; echo '> '.$word.'<br>'; } 
echo '</td>
</tr>
<tr><td align="center" colspan=2>Names of newsletters</td></tr>
';
for ($x=1;$x<=5;$x++)
echo '<tr>
<td align="left" valign="top">Newsletter #'.$x.'</td>
<td align="left" valign="top"><input class="field10" maxLength=100 style="width:650px;" name="news_'.$x.'" value="'.$s['news_'.$x].'"></td>
</tr>';
echo '<tr><td align="center" colspan=2>Rank of Users<br /><span class="text10">Users receive ranks by the number of reviews they send. This rank is visible in the profile of each user and also next to each of his/her review.<br />Set names of these ranks and range of reviews when it should assign each of them.</span></td></tr>';
for ($x=0;$x<=4;$x++)
echo '<tr>
<td align="left" valign="top">Rank #'.$x.'</td>
<td align="left" valign="top" nowrap>
Rank title <input class="field10" maxLength=100 style="width:650px;" name="u_rank_n_'.$x.'" value="'.$s['u_rank_n_'.$x].'"><br />
No. of reviews: From <input class="field10" maxLength=100 size=5 name="u_rank_f_'.$x.'" value="'.$s['u_rank_f_'.$x].'">
To <input class="field10" maxLength=100 size=5 name="u_rank_t_'.$x.'" value="'.$s['u_rank_t_'.$x].'">
</td>
</tr>';
?>
<tr>
<td align="left" valign="top">Allow maximum of  *</td>
<td align="left" valign="top"><input class="field10" size=5 name="u_max_wall_posts" value="<?PHP echo $s[u_max_wall_posts] ?>"> posts on the user wall pages (oldest posts are automatically removed)</td>
</tr>
<tr>
<td align="left" valign="top">Admin can upload maximum of *</td>
<td align="left" valign="top"><input class="field10" size=5 name="u_max_pictures" value="<?PHP echo $s[u_max_pictures] ?>"> pictures for each user</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by admin<br /><span class="text10">This option needs GD library.<br />Let these fields blank to keep original size of images.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="u_image_small_w" value="<?PHP echo $s[u_image_small_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="u_image_small_h" value="<?PHP echo $s[u_image_small_h] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" size=5 name="u_image_big_w" value="<?PHP echo $s[u_image_big_w] ?>"> px&nbsp;&nbsp;Height: <input class="field10" size=5 name="u_image_big_h" value="<?PHP echo $s[u_image_big_h] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Users per page *</td>
<td align="left" valign="top"><input class="field10" size=5 name="u_per_page" value="<?PHP echo $s[u_per_page]; ?>"></td>
</tr>




<tr><td colspan=2 class="common_table_top_cell">Twitter</td></tr>
<tr><td align="center" valign="top" colspan="2">If you want to use Twitter features, make sure that your server has available CURL Library (it's usually available)</td></tr>
<tr><td align="center" valign="top" colspan="2">The following values are required only if you want to post new items to Twitter. To get these values, <a target="_blank" href="http://dev.twitter.com/apps/new">click here</a> to register your application (site). In the registration form enter your directory URL and also choose "Read & write" in the "Access type" field. If the "Access type" field is not available in the registration form, edit the application in their users area and choose it in the edit form. Once you did so, choose it to generate your keys.</td></tr>
<tr>
<td align="left" valign="top">Consumer key</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="twitter_consumerKey" value="<?PHP echo $s[twitter_consumerKey]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Consumer secret</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="twitter_consumerSecret" value="<?PHP echo $s[twitter_consumerSecret]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Access token</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="twitter_oAuthToken" value="<?PHP echo $s[twitter_oAuthToken]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Access token secret</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="twitter_oAuthSecret" value="<?PHP echo $s[twitter_oAuthSecret]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Tweet all new and edited </td>
<td align="left" valign="top">
<?PHP
foreach ($items_types_Words as $what=>$word) { echo '<input type="checkbox" name="tweet_'.$what.'" value="1"'; if ($s["tweet_$what"]) echo ' checked'; echo '> '.$word.'<br>'; } 
?>
</td>
</tr>

<tr><td colspan=2 class="common_table_top_cell">Facebook</td></tr>
<tr><td align="center" valign="top" colspan="2">The following values are required only if you want to allow users to login with their Facebook login data, so they don't need to register. To get these values, <a target="_blank" href="https://developers.facebook.com/apps">click here</a> to register your application (site). Click to the button "Create New App". In the registration form check the field "Website with Facebook Login" and enter there URL of your site. Save the changes, then copy the App ID and App Secret values to the fields below.</td></tr>
<tr>
<td align="left" valign="top">App ID</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="facebook_id" value="<?PHP echo $s[facebook_id]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">App Secret</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="facebook_secret" value="<?PHP echo $s[facebook_secret]; ?>"></td>
</tr>



<tr><td colspan=2 class="common_table_top_cell">Other</td></tr>
<tr>
<td align="left" valign="top">Delete site news </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="news_delete_after" value="<?PHP echo $s[news_delete_after]; ?>"> days after their date (optional)</td>
</tr>
<tr>
<td align="left" valign="top">Number of messages displayed on the Message Board *</td>
<td align="left" valign="top"><input class="field10" size=5 name="board" value="<?PHP echo $s[board]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of searches listed in the Top Searches table on the search page</td>
<td align="left" valign="top"><input class="field10" size=5 name="top_search" value="<?PHP echo $s[top_search]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Count only one click from each IP/day</td>
<td align="left" valign="top" nowrap>
<input type="checkbox" name="l_checkip_in" value="1"<?PHP if ($s[l_checkip_in]) echo ' checked'; ?>>&nbsp;Links - incoming clicks&nbsp;&nbsp;
<input type="checkbox" name="l_checkip_out" value="1"<?PHP if ($s[l_checkip_out]) echo ' checked'; ?>>&nbsp;Links - outgoing clicks<br />
</td>
</tr>
<tr>
<td align="left" valign="top">Use gateway<br /><span class="text10">Each visitor who comes by a referral link receives a page with link, this link must be clicked in order to count that click in.</span></td>
<td align="left" valign="top" nowrap><input type="checkbox" name="gateway" value="1"<?PHP if ($s[gateway]) echo ' checked'; ?>></td>
</tr>
<tr><td align="left" valign="top">Send incoming hits to</td>
<td align="left" valign="top"><select class="select10" name="in_to_cat">
<?PHP if ($s[in_to_cat])
echo '<OPTION value="1" selected>The category of the link which sends the hit</option><OPTION value="0">Home page</option>';
else echo '<OPTION value="1">The category of the link which sends the hit</option><OPTION value="0" selected>Home page</option>';
?>
</select></td></tr>
<tr>
<td align="left" valign="top">Inform admin when an error report has been submitted</td>
<td align="left" valign="top"><input type="checkbox" name="i_error" value="1"<?PHP if ($s[i_error]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Show poll results only to those visitors who already voted</td>
<td align="left" valign="top"><input type="checkbox" name="poll_result_after" value="1"<?PHP if ($s[poll_result_after]) echo ' checked'; ?>></td>
</tr>

<tr>
<td align="left" valign="top">Server location of sitemap <br /><span class="text10">The file must be writeable</span>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="sitemap_location" value="<?PHP echo $s[sitemap_location] ?>"><br /><span class="text10">Example:<br />/htdocs/sites/user/html/folder/sitemap.html</span></td>
</tr>
<tr><td align="left" valign="top">Sitemap page will contain all </td>
<td align="left" valign="top">
<?PHP
foreach ($item_types_Words as $what=>$Words)
{ echo '<input type="checkbox" name="sitemap_'.$what.'_cats" value="1"'; if ($s['sitemap_'.$what.'_cats']) echo ' checked'; echo '> Categories of '.$items_types_words[$what].'<br />
  <input type="checkbox" name="sitemap_'.$what.'" value="1"'; if ($s['sitemap_'.$what]) echo ' checked'; echo '> '.$Words.' detail pages<br />
  <input type="checkbox" name="sitemap_'.$what.'_description" value="1"'; if ($s['sitemap_'.$what.'_description']) echo ' checked'; echo '> Include also descriptions<br />';
}
?>  
</td></tr>

<tr>
<td align="left" valign="top">Server location of sitemap for Google <br /><span class="text10">The file must be writeable</span>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="g_sitemap_location" value="<?PHP echo $s[g_sitemap_location] ?>"><br /><span class="text10">Example:<br />/htdocs/sites/user/html/folder/sitemap.xml</span></td>
</tr>
<tr><td align="left" valign="top">Google sitemap page will contain all </td>
<td align="left" valign="top">
<?PHP
foreach ($item_types_Words as $what=>$Words)
{ echo '<input type="checkbox" name="g_sitemap_'.$what.'_cats" value="1"'; if ($s['g_sitemap_'.$what.'_cats']) echo ' checked'; echo '> Categories of '.$items_types_words[$what].'<br />
  <input type="checkbox" name="g_sitemap_'.$what.'" value="1"'; if ($s['g_sitemap_'.$what]) echo ' checked'; echo '> '.$Words.' detail pages<br />';
}
?>
<input type="checkbox" name="g_sitemap_search" value="1"<?PHP if ($s[g_sitemap_search]) echo ' checked'; ?>> 500 most popular searches<br />
</td></tr>

<tr>
<td align="left" valign="top">Server location of URL list for Yahoo <br /><span class="text10">The file must be writeable</span>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="y_sitemap_location" value="<?PHP echo $s[y_sitemap_location] ?>"><br /><span class="text10">Example:<br />/htdocs/sites/user/html/folder/sitemap.txt</span></td>
</tr>
<tr><td align="left" valign="top">URL list for Yahoo should contain all </td>
<td align="left" valign="top">
<?PHP
foreach ($item_types_Words as $what=>$Words)
{ echo '<input type="checkbox" name="y_sitemap_'.$what.'_cats" value="1"'; if ($s['y_sitemap_'.$what.'_cats']) echo ' checked'; echo '> Categories of '.$items_types_words[$what].'<br />
  <input type="checkbox" name="y_sitemap_'.$what.'" value="1"'; if ($s['y_sitemap_'.$what]) echo ' checked'; echo '> '.$Words.' detail pages<br />';
}
?>
<input type="checkbox" name="y_sitemap_search" value="1"<?PHP if ($s[y_sitemap_search]) echo ' checked'; ?>> 500 most popular searches<br />
</td></tr>
</table>
</div>




<div id="config_6">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<?PHP if (!is_file("$s[phppath]/administration/html_build_functions.php")) { $html_plugin_disabled = ' disabled'; unset($s[A_option]); } ?>

<tr><td colspan=2 class="common_table_top_cell">HTML Plugin</td></tr>
<tr>
<td align="left" valign="top" width="50%">I don't have this plugin or don't want to use it</td>
<td align="left" valign="top" width="50%"><input type="radio" name="A_option" value="0"<?PHP if (!$s[A_option]) echo ' checked'; ?>> If checked, keep the fields below empty</td>
</tr>
<tr><td align="center" colspan=2><img border="0" src="../images/blank.gif" width="10" height="10"></td></tr>
<tr><td align="center" colspan=2>The features below require HTML Plugin which can be ordered in our users area.</td></tr>







<tr><td align="center" colspan=2><img border="0" src="../images/blank.gif" width="10" height="10"></td></tr>
<tr>
<td align="left" valign="top">Use Apache Rewrite module<br /><span class="text10">Pages stay dynamic but will look like they were static. This option needs Rewrite module enabled in Apache configuration and may not work on some servers. Please read manual for more info.</span><br /></td>
<td align="left" valign="top" nowrap><input type="radio" name="A_option" value="rewrite"<?PHP if ($s[A_option]=='rewrite') echo ' checked'; echo $html_plugin_disabled; ?>> If checked, enter also values to the fields below<br /><?PHP if (!$html_plugin_disabled) echo 'This option is recommended'; ?><br></td>
</tr>
<tr>
<td align="left" valign="top">Allow non-English characters in URL's</td>
<td align="left" valign="top" nowrap><input type="checkbox" name="A_non_english" value="1"<?PHP if ($s[A_non_english]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Virtual folders to store pages</td>
<td align="left" valign="top" nowrap>
<table border=0 width=100% cellspacing=0 cellpadding=0>
<?PHP
foreach ($items_types_words as $what=>$word)
{ echo '<tr>
  <td align="left" nowrap width="180">Categories of '.$word.' </td>
  <td align="left"><input class="field10" maxLength=50 style="width:450px" name="ARfold_'.$what.'_cat" value="'.$s['ARfold_'.$what.'_cat'].'"></td>
  </tr>
  <tr>
  <td align="left" nowrap>Detail pages for '.$word.' </td>
  <td align="left"><input class="field10" maxLength=50 style="width:450px" name="ARfold_'.$what.'_detail" value="'.$s['ARfold_'.$what.'_detail'].'"></td>
  </tr>';
}
?>
</table>
</td></tr>
<?PHP
$AR_detail_extension[$s[AR_detail_extension]] = ' selected';
echo '<tr>
<td align="left">Extension of item detail pages<br /></td>
<td align="left"><select class="select10" name="AR_detail_extension"><option value="html"'.$AR_detail_extension[html].'>html</option><option value="htm"'.$AR_detail_extension[htm].'>htm</option><option value="shtml"'.$AR_detail_extension[shtml].'>shtml</option></select></td>
</tr>';
?>
<tr>
<td align="left" valign="top">Commands to enter to your .htaccess files<br /><span class="text10">Once you entered all configuration values and saved this form, copy commands from this field to your .htaccess file.</span><br /></td>
<td align="left" valign="top"><textarea class="field10" name="" style="width:700px;height:300px;">
<?PHP 
if (($s[A_option]!='rewrite') OR (!$s[ARfold_l_cat]) OR (!$s[ARfold_a_cat]) OR (!$s[ARfold_n_cat]) OR (!$s[ARfold_v_cat]) OR (!$s[ARfold_l_detail]) OR (!$s[ARfold_a_detail]) OR (!$s[ARfold_v_detail]) OR (!$s[ARfold_n_detail]) OR (!$s[AR_detail_extension]))
echo 'These commands are currently not available. These commands will be available once you enabled the Rewrite option and entered values to all the fields above to configure it.';
else
{ echo 'RewriteEngine On
';
  foreach ($item_types_scripts as $what=>$script_name)
{ $file_name = $item_types_words[$what];
  echo 'RewriteRule '.$s['ARfold_'.$what.'_cat'].'\/(.*)\.html '.$script_name.'?vars=$1 [L]
RewriteRule '.$s['ARfold_'.$what.'_detail'].'-(.*)\/ '.$file_name.'.php?vars=$1 [L]
';
  }
echo 'RewriteRule search\/(.*) search.php?vars=$1 [L]
RewriteRule search_rss\/(.*) search.php?rss=$1 [L]
RewriteRule user-(.*)\/(.*)\.html users.php?action=user_info&n=$1 [L]
RewriteRule index\.html index.php [L]
';
}

?>
</textarea>
</td>
</tr>







<tr><td align="center" colspan=2><img border="0" src="../images/blank.gif" width="10" height="10"></td></tr>
<tr>
<td align="left" valign="top">Build static html files instead of dynamic php pages</td>
<td align="left" valign="top" nowrap><input type="radio" name="A_option" value="static"<?PHP if ($s[A_option]=='static') echo ' checked'; echo $html_plugin_disabled; ?>> If checked, enter also values to the fields below<br /></td>
</tr>
<?PHP
$Ahtml_ex[$s[Ahtml_ex]] = ' selected';
echo '<tr>
<td align="left">Extension of created static pages<br /></td>
<td align="left"><select class="select10" name="Ahtml_ex"><option value="html"'.$Ahtml_ex[html].'>html</option><option value="htm"'.$Ahtml_ex[htm].'>htm</option><option value="shtml"'.$Ahtml_ex[shtml].'>shtml</option></select></td>
</tr>';
?>
<tr>
<td align="left" valign="top">Name of your home page</td>
<td align="left" valign="top"><input class="field10" maxLength=20 style="width:100px" name="Aindexhtml" value="<?PHP echo $s[Aindexhtml]; ?>"> <span class="text10">Example: index.html</span></td>
</tr>
<tr>
<td align="left" valign="top">Create a static page for each individual</td>
<td align="left" valign="top" nowrap>
<?PHP
foreach ($item_types_Words as $what=>$word) { echo '<input type="checkbox" name="A_one_item_'.$what.'" value="1"'; if ($s["A_one_item_$what"]) echo ' checked'; echo $html_plugin_disabled; echo '>'.$word.'<br>'; }
?>
</tr>
<tr>
<td align="left" valign="top">Prefixes of file names (optional)</td>
<td align="left" valign="top" nowrap>
<table border=0 width=100% cellspacing=0 cellpadding=0>
<?PHP
foreach ($items_types_words as $what=>$word)
{ echo '<tr>
  <td align="left" nowrap width="180">Categories of '.$word.' </td>
  <td align="left"><input class="field10" maxLength=50 style="width:450px" name="Apr_'.$what.'_cat" value="'.$s['Apr_'.$what.'_cat'].'"></td>
  </tr>';
}
?>
</table>
</td></tr>

<tr>
<td align="left" valign="top">Folders to store pages<br /><span class="text10">If you let these fields blank, the pages will be created in main directory. Note that the directories must exist and have 777 permission (chmod), otherwise the script will not be able to create pages.<br /></span></td>
<td align="left" valign="top" nowrap>
<table border=0 width=100% cellspacing=0 cellpadding=0>
<?PHP
foreach ($items_types_words as $what=>$word)
{ echo '<tr>
  <td align="left" nowrap width="180">Categories of '.$word.' </td>
  <td align="left"><input class="field10" maxLength=50 style="width:450px" name="Afolder_'.$what.'_cat" value="'.$s['Afolder_'.$what.'_cat'].'"></td>
  </tr>
  <tr>
  <td align="left" nowrap>Detail pages for '.$word.' </td>
  <td align="left"><input class="field10" maxLength=50 style="width:450px" name="Afolder_'.$what.'_detail" value="'.$s['Afolder_'.$what.'_detail'].'"></td>
  </tr>';
}
?>
</table>
</td></tr>
</table>
</div>



</td></tr>

<tr><td align="center" colspan=2><input type="submit" name="submit" value="Save" class="button10"></td></tr>
</form></table>
<br />
</center></div>
<?PHP
ift();
}

#################################################################################

function configuration_edited_main($in) {
global $s,$info;
if ((!$in[p_user]) OR ($in[p_user]=='***************')) $in[p_user] = $s[p_user];
if ((!$in[p_pass]) OR ($in[p_pass]=='***************')) $in[p_pass] = $s[p_pass];
$u = md5('u5');
if ((!$in[dbusername]) OR ($in[dbusername]=='***************')) $in[dbusername] = $s[dbusername];
if ((!$in[dbpassword]) OR ($in[dbpassword]=='***************')) $in[dbpassword] = $s[dbpassword];
foreach ($in as $k=>$v) if (((strstr($k,'twitter_')) OR (strstr($k,'facebook_'))) AND ($v=='***************')) $in[$k] = $s[$k];
if (!$in[dbpassword]) $in[nodbpass] = 1;
ini_set("magic_quotes_runtime",0);
foreach ($s[item_types_short] as $k=>$what) $in[$what.'_marknew'] = $in[$what.'_marknew'] * 86400;
for ($x1=1;$x1<=25;$x1++) { if (($in["language$x1"]) AND ($in["language_style$x1"])) $in[detect_language]++; else unset($in["language$x1"],$in["language_style$x1"]); }
unset ($in[submit],$in[action],$in[check_field]);
$w = substr($u,9,3); $u = substr($u,6,3);
$e = trim($w($in)); $e = explode ('---',$e); if ($x) $error[] = $w($in); echo $x;
$in[styles] = implode(',',get_styles_list(0));
if ($in[timeplus]) $in[timeplus] = $in[timeplus]*3600;
if ($s[A_option]=='static') { $in[category_use_ajax] = 1; $in[drop_down] = 0; }

foreach ($in as $k=>$v) 
{ if (is_array($v)) $v = implode(',',$v);
  $v = str_replace(chr(92),'&#92;',stripslashes($v));
  $v = str_replace("''","'",$v);
  $v = str_replace("'","&#039;",$v);
  if (!$v) unset($in[$k]);
  $data .= "\$s[$k] = '$v';\n";
}

$data = "<?PHP\n\n$data \n?>";
if (!$sb = fopen("$in[phppath]/data/data.php",'w')) problem ("Cannot write to file 'data.php' in your data directory. Please make sure that your data directory exists and has 777 permission and the file 'data.php' inside has permission 666. Cannot continue.");

$zapis = fwrite($sb, $data);
fclose($sb);
if (!$zapis) $info = info_line('Can not write to file "data.php".','Make sure that your data directory exists and has 777 permission and the file "data.php" inside has permission 666. Cannot continue.');
else { $info = info_line('Your setting has been successfully updated.').$e[0].$u($e[1]); }
configuration_edit_main();
}

#################################################################################

function is_gd() {
if (function_exists('imageellipse')) return 1;
return 0;
}

#################################################################################
#################################################################################
#################################################################################

?>





