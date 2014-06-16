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

if ($_GET[action] == 'left_frame') left_frame();
if ($_GET[action] == 'home') home();
if ($_GET[action] == 'statistic') statistic();
if ($_GET[action] == 'categories_list') categories_list($_GET[what]);
header ("Location: index.php");
exit;

#########################################################################
#########################################################################
#########################################################################

function left_frame() {
global $s;
check_admin(0);
if (($_SESSION[LUG_admin_user]) AND ($_SESSION[LUG_admin_password]))
{ $username = $_SESSION[LUG_admin_user]; $password = $_SESSION[LUG_admin_password]; }
else { $username = $_COOKIE[LUG_admin_user]; $password = $_COOKIE[LUG_admin_password]; }
$q = dq("select $s[pr]admins_rights.* from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$username' and $s[pr]admins.password = '$password' and $s[pr]admins.n = $s[pr]admins_rights.n",1);
foreach ($s as $k=>$v) { $my_data .= "&$k=".urlencode($v); $pocet++; if ($pocet>20) break; }
while ($x = mysql_fetch_assoc($q)) $rights[] = $x[action];
$left = '<a target="right" href="'; $right = '</a><br />';
if ($s[Aindexhtml]) $homepage = $s[Aindexhtml]; else $homepage = 'index.php';
ih();

echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="common_table"><tr><td align="left" colspan="2">';

echo '<div class="common_div_title_left" style="text-align:left;">&nbsp;Link Up Gold</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div0">';
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div1\')";><img border="0" src="images/menu_links.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Links</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div1">';
if (in_array('links',$rights)) echo $left."links.php?action=links_unapproved_home\">Queue".$right;
if (in_array('links',$rights)) echo $left."link_details.php?action=link_create\">Create".$right;
if (in_array('all_links',$rights)) echo $left."links_reports.php?action=reports\">Error reports".$right;
if (in_array('links',$rights)) echo $left."links.php?action=links_search\">Search".$right;
if (in_array('links',$rights)) echo $left."links.php?action=links_editor_picks\">Editor picks".$right;
if (in_array('all_links',$rights)) echo $left."links.php?action=links_duplicate_form\">Duplicate".$right;
if (in_array('all_links',$rights)) echo $left."links.php?action=links_broken_form\">Check links".$right;
if (in_array('categories_links',$rights)) echo $left."categories.php?action=categories_home&what=l\">Categories".$right;
if (in_array('categories_links',$rights)) echo $left."categories.php?action=categories_tree&what=l\">All categories".$right;
if (in_array('email_owners',$rights)) echo $left."email_owners.php\">Email owners".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div2\')";><img border="0" src="images/menu_adlinks.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">AdLinks</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div2">';
if (in_array('adlinks',$rights)) echo $left."adlinks.php?action=adlink_create\">Create".$right;
if (in_array('adlinks',$rights)) echo $left."adlinks.php\">Search".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div3\')";><img border="0" src="images/menu_blogs.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Blogs</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div3">';
if (in_array('blogs',$rights)) echo $left."blogs.php?action=blogs_unapproved_home\">Queue".$right;
if (in_array('blogs',$rights)) echo $left."blog_details.php?action=blog_create\">Create".$right;
if (in_array('blogs',$rights)) echo $left."blogs.php?action=blogs_search\">Search".$right;
if (in_array('blogs',$rights)) echo $left."blogs.php?action=blogs_editor_picks\">Editor picks".$right;
if (in_array('categories_blogs',$rights)) echo $left."categories.php?action=categories_home&what=b\">Categories".$right;
if (in_array('categories_blogs',$rights)) echo $left."categories.php?action=categories_tree&what=b\">All categories".$right;
if (in_array('email_owners',$rights)) echo $left."email_owners.php\">Email owners".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div4\')";><img border="0" src="images/menu_articles.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Articles</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div4">';
if (in_array('articles',$rights)) echo $left."articles.php?action=articles_unapproved_home\">Queue".$right;
if (in_array('articles',$rights)) echo $left."article_details.php?action=article_create\">Create".$right;
if (in_array('articles',$rights)) echo $left."articles.php?action=articles_search\">Search".$right;
if (in_array('articles',$rights)) echo $left."articles.php?action=articles_editor_picks\">Editor picks".$right;
if (in_array('categories_articles',$rights)) echo $left."categories.php?action=categories_home&what=a\">Categories".$right;
if (in_array('categories_articles',$rights)) echo $left."categories.php?action=categories_tree&what=a\">All categories".$right;
if (in_array('email_owners',$rights)) echo $left."email_owners.php\">Email owners".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div5\')";><img border="0" src="images/menu_videos.gif" style="margin-top:6px;margin-left:4px;margin-right:6px;">Videos</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div5">';
if (in_array('videos',$rights)) echo $left."video_details.php?action=video_create\">Create".$right;
if (in_array('videos',$rights)) echo $left."videos.php?action=videos_search\">Search".$right;
if (in_array('videos',$rights)) echo $left."videos.php?action=videos_editor_picks\">Editor picks".$right;
if (in_array('categories_videos',$rights)) echo $left."categories.php?action=categories_home&what=v\">Categories".$right;
if (in_array('categories_videos',$rights)) echo $left."categories.php?action=categories_tree&what=v\">All categories".$right;
if (in_array('all_videos',$rights)) echo $left."videos.php?action=videos_unapproved_home\">Queue".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div6\')";><img border="0" src="images/menu_news.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">News</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div6">';
if (in_array('news',$rights)) echo $left."new_details.php?action=new_create\">Create".$right;
if (in_array('news',$rights)) echo $left."news.php?action=news_search\">Search".$right;
if (in_array('news',$rights)) echo $left."news.php?action=news_editor_picks\">Editor picks".$right;
if (in_array('categories_news',$rights)) echo $left."categories.php?action=categories_home&what=n\">Categories".$right;
if (in_array('categories_news',$rights)) echo $left."categories.php?action=categories_tree&what=n\">All categories".$right;
if (in_array('all_news',$rights)) echo $left."news.php?action=news_unapproved_home\">Queue".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div7\')";><img border="0" src="images/menu_tools.gif" style="margin-top:6px;margin-left:4px;margin-right:6px;">Tools</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div7">';
if (in_array('search_log',$rights)) echo $left."search_log.php\">Search log".$right;
if (in_array('polls',$rights)) echo $left."polls.php?action=polls_home\">Polls".$right;
if (in_array('board',$rights)) echo $left."board.php?action=board\">Message board".$right;
if (in_array('ads',$rights)) echo $left."ads.php?action=ads_home\">Ads".$right;
if (in_array('blacklist',$rights)) echo $left."blacklist.php?action=blacklist_home\">Blacklist".$right;
if (in_array('news',$rights)) echo $left."site_news.php?action=news_home\">Site news".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div8\')";><img border="0" src="images/menu_users.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Users</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div8">';
if (in_array('users',$rights)) echo $left."users.php?action=users_home\">Search".$right;
if (in_array('users',$rights)) echo $left."users.php?action=user_create\">Create".$right;
if (in_array('email_users',$rights)) echo $left."users.php?action=email_users\">Send email".$right;
if (in_array('newsletter',$rights)) echo $left."users.php?action=newsletter\">Newsletter".$right;
echo '</div>';

if (in_array('adv_prices_orders',$rights)) 
{ echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div9\')";><img border="0" src="images/menu_prices_orders.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Payments</div>
  <div style="padding:3px;padding-bottom:15px;display:block;" id="div9">';
  echo $left."orders_payments.php?action=orders_search\">Orders".$right;
  echo $left."prices.php?action=adv_packages_home\">Packages".$right;
  echo $left."prices.php?action=prices_home\">Simple prices".$right;
  echo '</div>';
}

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div10\')";><img border="0" src="images/menu_system.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">System</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div10">';
if (in_array('reset_rebuild',$rights)) echo $left."rebuild.php?action=reset_rebuild_home\">Reset/rebuild".$right;
if (in_array('database_tools',$rights)) echo $left."database_tools.php?action=database_home\">Database tools".$right;
if (in_array('admins',$rights)) echo $left."administrators.php\">Administrators".$right;
if (in_array('configuration',$rights)) echo $left."uninstall.php?action=uninstall\">Uninstall".$right;
echo '</div>';

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div11\')";><img border="0" src="images/menu_configuration.gif" style="margin-top:6px;margin-left:4px;margin-right:6px;">Configuration</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div11">';
if (in_array('configuration',$rights)) echo $left."configuration_main.php\">Main configuration".$right;
if (in_array('configuration',$rights)) echo $left."configuration_forms.php\">Public forms".$right;
if (in_array('configuration',$rights)) echo $left."user_items.php?action=user_items_home\">User defined items".$right;
if (in_array('templates',$rights)) echo $left."templates.php?action=templates_home\">Templates".$right;
if (in_array('messages',$rights)) echo $left."messages.php?action=messages_home\">Messages".$right;
if (in_array('configuration',$rights)) echo $left.'ip_country.php">IP/country data'.$right;
echo '</div>';

if ((in_array('all_links',$rights)) AND (file_exists("$s[phppath]/administration/spider.php")))
{ echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div12\')";><img border="0" src="images/menu_spider.gif" style="margin-top:6px;margin-left:4px;margin-right:6px;">Import</div>
  <div style="padding:3px;padding-bottom:15px;display:block;" id="div12">';
  echo $left."spider_dmoz.php\">Links from Dmoz".$right;
  echo $left."spider_google.php\">Links from Google".$right;
  echo $left."links_import.php\">Links from file".$right;
  echo $left."articles_import.php\">Articles from file".$right;
  echo $left."categories.php?action=categories_import\">Categories from file".$right;
  echo $left."categories_dmoz.php\">Categories from Dmoz".$right;
  echo '</div>';
}

echo '<div class="common_div_title_left" style="text-align:left;" onClick="open_close(\'div13\')";><img border="0" src="images/menu_other.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Other Options</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div13">';
echo $left.'home.php?action=statistic">Statistic'.$right;
echo '<a target="_blank" href="../'.$homepage.'">Your Home Page'.$right;
echo '<a target="_top" href="login.php?action=log_off">Log off'.$right;
echo mc_test();
echo '</div>';
echo '</td></tr></table>';

?>
<script>
for (x=1;x<=13;x++) { if (get_cookie('div'+x)==1) show_hide_div_id(0,'div'+x); }
</script>
<?PHP


if (file_exists($s[phppath].'/setup.php')) popup_security_window("You did not delete file \"setup.php\" in main directory. It\'s a security risk. Please delete it as soon as possible.");
elseif (file_exists($s[phppath].'/administration/create_admin.php')) popup_security_window("You did not delete file \"create_admin.php\" in administration directory. It\'s a security risk. Please delete it as soon as possible.");
elseif (file_exists($s[phppath].'/update.php')) popup_security_window("You did not delete file \"update.php\" in main directory. It\'s a security risk. Please delete it as soon as possible.");
elseif (file_exists($s[phppath].'/data/uninstall')) popup_security_window("You have file \"uninstall\" in data directory. It\'s a security risk. Please delete it if you don\'t plan to uninstall the script from this server in the near future.");
exit;
}

######################################################################################

function popup_security_window($text) {
echo '<script type="text/javascript">
  <!--
  alert(\''.$text.'\');
  -->
  </script>';
}

#########################################################################
#########################################################################
#########################################################################

function home() {
global $s;
check_admin(0);
ih();
echo '<br>'.page_title('Link Up Gold Administration');
$q = dq("select count(*) from $s[pr]links where status = 'queue'",1); $pocet = mysql_fetch_row($q); if ($pocet[0]) $queue .= '<tr><td align="left">Links in the queue: '.$pocet[0].'</td></tr>';
$q = dq("select count(*) from $s[pr]articles where status = 'queue'",1); $pocet = mysql_fetch_row($q); if ($pocet[0]) $queue .= '<tr><td align="left">Articles in the queue: '.$pocet[0].'</td></tr>';
$q = dq("select count(*) from $s[pr]comments where approved = '0'",1); $pocet = mysql_fetch_row($q); if ($pocet[0]) $queue .= '<tr><td align="left">Comments in the queue: '.$pocet[0].'</td></tr>';
$q = dq("select count(*) from $s[pr]adlinks where approved = '0'",1); $pocet = mysql_fetch_row($q); if ($pocet[0]) $queue .= '<tr><td align="left">AdLinks in the queue: '.$pocet[0].'</td></tr>';
$q = dq("select count(*) from $s[pr]users where confirmed = '1' and approved = '0'",0); $pocet = mysql_fetch_row($q); if ($pocet[0]) $queue .= '<tr><td align="left">Users in the queue: '.$pocet[0].'</td></tr>';
$q = dq("select count(*) from $s[pr]links_extra_orders where paid = '0'",0); $pocet = mysql_fetch_row($q); if ($pocet[0]) $queue .= '<tr><td align="left">Unpaid orders: '.$pocet[0].'</td></tr>';
if ($queue) echo '<table border="0" width="500" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Queue</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">'.$queue.'</table>
</td></tr></table><br />';
statistic_table();
get_news();
ift(); 
}

#########################################################################

function get_news() {
global $s;
if ($news=fetchURL("http://www.phpwebscripts.com/scripts/info_for_users.php?sc=$s[cs]"))
{ echo '<br /><table border=0 width=500 cellspacing=0 cellpadding=2 class="common_table">
  <tr><td class="common_table_top_cell">Latest News</td></tr>
  <tr><td align="center">
  <table border=0 width=100% cellspacing=10 cellpadding=0>
  <tr><td align="left">'.$news.'</td></tr>
  <tr><td align="center"><a target="_blank" href="http://www.phpwebscripts.com/">www.phpwebscripts.com</a></td></tr>
  </table>
  </td></tr></table>';
}
if ($contact_form=fetchURL("http://www.phpwebscripts.com/scripts/contact_form.php?sc=$s[cs]"))
{ echo '<br /><table border=0 width=500 cellspacing=0 cellpadding=2 class="common_table">
  <tr><td class="common_table_top_cell">Contact PHPWebScripts.com</td></tr>
  <tr><td align="center">
  <table border=0 width=100% cellspacing=10 cellpadding=0>
  <tr><td align="left">'.$contact_form.'</td></tr>
  </table>
  </td></tr></table>';
}
}

#########################################################################

function categories_list($what) {
global $s;
check_admin(0);
include('./rebuild_functions.php');
$q = dq("select * from $s[pr]cats where use_for = '$what'",1);
while ($a=mysql_fetch_assoc($q))
{ if ($a[parent]) { $q1 = dq("select name from $s[pr]cats where n = '$a[parent]'",1); $b=mysql_fetch_assoc($q1); $a[parent] = $b[name]; }
  else $a[parent] = '&nbsp;';
  if ($a[cat_group]) $a[group] = $a[cat_group]; else $a[group] = '&nbsp;';
  if ($a[alias_of]) { $q1 = dq("select name from $s[pr]cats where n = '$a[alias_of]'",1); $b=mysql_fetch_assoc($q1); $a[alias] = $b[name]; }
  else $a[alias] = '&nbsp;';
  if ($a[submithere]) $a[submit_here] = 'Yes'; else $a[submit_here] = 'No';
  if ($a[similar])
  { $similar = "n = '".str_replace('_','',str_replace('_ _',"' OR n = '",$a[similar]))."'";
    unset($a[similar]);
    $q1 = dq("select name from $s[pr]cats where $similar",1); while ($b=mysql_fetch_assoc($q1)) $a[similar] .= $b[name].'<br />';
  }
  else $a[similar] = '&nbsp;';

  $list .= php_rebuild_parse_part('list_category.txt','_common',$a);
}
echo $list;

}

#########################################################################

function statistic() {
global $s;
check_admin(0);
ih();
statistic_table();
ift(); 
}

#########################################################################
#########################################################################
#########################################################################

?>