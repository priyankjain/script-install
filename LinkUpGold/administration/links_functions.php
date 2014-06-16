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

include_once('./common.php');
check_admin('links');
$q = dq("select count(*) from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$s[LUG_admin_username]' and $s[pr]admins.password = '$s[LUG_admin_password]' and $s[pr]admins_rights.n = $s[pr]admins.n and $s[pr]admins_rights.action = 'all_links'",1);
$x = mysql_fetch_row($q);
if ($x[0]) $s[admin_all_cats_l] = 1;
else get_allowed_categories('l');

###################################################################################
###################################################################################
###################################################################################

function show_one_link($link) {
global $s;

if (!is_array($link)) // je to cislo jenom
{ $link = get_item_variables('l',$link,0);
  $q = dq("select * from $s[pr]usit_values where use_for = 'l' AND n = '$link[n]'",1);
  while ($x = mysql_fetch_assoc($q))
  { $link['user_item_'.$x[item_n]][code] = $x[value_code];
    $link['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
if (!check_admin_categories('l',$link[c])) return false;
$link = stripslashes_array($link);
$user_items = user_defined_items_show('l',$link);
if ($link[status]=='enabled')
{ $manage = '<a href="link_details.php?action=link_manage&what=0&n='.$link[n].'">Disable</a>';
  $enabled = 'Yes';
}
else
{ $manage = '<a href="link_details.php?action=link_manage&what=1&n='.$link[n].'">Enable</a>';
  $enabled = 'No';
}
$created = datum ($link[created],1); 
if ( (($link[updated]+$s[l_marknew]) > $s[cas]) AND ($s[pref_upd]) ) $icon = $s[upd_img];
elseif ( ($link[created]+$s[l_marknew]) > $s[cas] ) $icon = $s[new_img];
elseif ( ($link[updated]+$s[l_marknew]) > $s[cas] ) $icon = $s[upd_img];
if ($link[pick]) $icon .= "&nbsp $s[pick_img]";
if ($link[popular]) $icon .= "&nbsp $s[pop_img]";
$rateicon = get_rateicon($link[rating]);
if ($link[updated]) $updated = datum ($link[updated],1); else $updated = 'Never yet';
if ($link[button]) $button = "<img src=\"$link[button]\" width=$link[bw] height=$link[bh]>";
else $button = '';
$dates = get_dates_links_text($link);
if (item_is_active($link[t1],$link[t2],$link[status],'l',0))
$active = '<font color="green">Link is active</font>'; else $active = '<font color="red">Link is inactive</font>';
if ($s[det_br]) $link[detail] = str_replace("\n",'<br />',$link[detail]);
if ($link[show_checkbox]) $checkbox = '<input class="bbb" type="checkbox" name="link[]" value="'.$link[n].'">&nbsp;&nbsp;';

echo '<table border="0" width="99%" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan=2>'.$checkbox.'
'.$button.'&nbsp;&nbsp;<a target="_blank" href="'.$link[url].'"><b>'.$link[title].'</b></a>&nbsp&nbsp </td></tr>
<tr>
<td align="left" width="20%">Reciprocal URL</td>
<td align="left" width="80%"><a target="_blank" href="'.$link[recip].'">'.$link[recip].'</a>&nbsp;</td>
</tr>
<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('l',$link[n],$link[rewrite_url],$link[category],1).'">'.get_detail_page_url('l',$link[n],$link[rewrite_url],$link[category],1).'</a></td>
</tr>
<tr>
<td align="left" valign="top">Description</td>
<td align="left">'.$link[description].'&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Detailed description&nbsp;</td>
<td align="left">'.$link[detail].'&nbsp;</td>
</tr>
<tr><td align="left" valign="top">Categories</td>
<td align="left">'.list_of_categories_for_item_admin('l',$link[c]).'</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Mail (street) address to show in a map  &nbsp;</td>
<td align="left">'.str_replace('_gmok_','',$link[map]).'&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>RSS URL &nbsp;</td>
<td align="left">'.$link[rss_url].'&nbsp;</td>
</tr>

<tr>
<td align="left" nowrap>Owner</td>
<td align="left"><a href="mailto:'.$link[email].'">'.$link[name].'</a>&nbsp;</td>
</tr>';
images_show_admin('l',$link,0);
if ($link[username]) echo '<tr>
<td align="left" nowrap>Owner\'s username</td>
<td align="left"><a href="users.php?action=users_searched&username='.$link[username].'&sort=username&order=asc">'.$link[username].'</a></td>
</tr>';
echo $user_items;
echo "<tr><td align=\"left\" colspan=2><span class=\"text10\">
Link number: $link[n], Enabled: $enabled, Valid from $dates[t1] to $dates[t2], $active<br />
Created: $created, Updated: $updated<br />
Rating: $link[rating] $rateicon ($link[votes] votes), Pick value: $link[pick]<br />
Outgoing clicks this month: $link[hits_m], Incoming clicks this month: $link[clicks_in_m], Outgoing clicks total: $link[hits], Incoming clicks total: $link[clicks_in]<br />
</td></tr>
<tr><td align=\"left\" colspan=2>
[$manage]&nbsp;&nbsp;
[<a target=\"_self\" href=\"link_details.php?action=link_edit&n=$link[n]\" title=\"Edit this link\">Edit</a>]&nbsp;&nbsp;
[<a target=\"_self\" href=\"javascript: go_to_delete('Are you sure?','link_details.php?action=link_delete&n=$link[n]')\" title=\"Delete this link\">Delete</a>]&nbsp;&nbsp;
[<a target=\"_self\" href=\"link_details.php?action=link_copy&n=$link[n]\" title=\"Copy this link\">Copy</a>]&nbsp;&nbsp;
[<a target=\"_self\" href=\"comments.php?action=comments_view&what=l&n=$link[n]\" title=\"Read comments\">Comments ($link[comments])</a>]&nbsp;&nbsp;
<!--[<a target=\"_self\" href=\"html_rebuild.php?action=rebuild_one_link&n=$link[n]\">Rebuild this link</a>]&nbsp;&nbsp;-->
</td></tr></table>
</td></tr></table>
<br />\n\n";
}

##################################################################################

function link_create_edit_form($link) {
global $s;
$n = $link[n];
if (!check_admin_categories('l',$link[c])) problem('You don\'t have the right to edit links in selected category(ies)');
$link = stripslashes_array($link);
for ($x=0;$x<=5;$x++) { if ($link[pick]==$x) $pick[$x] = ' selected'; }
if ($link[popular]) $popular = ', Is popular'; else $popular = '';
if ($link[updated]) $updated = datum ($link[updated],1); else $updated = 'Never yet';
$rating = get_one_item_rating('l',$n);
if ($link[owner]) $user = get_user_variables($link[owner]);
if (!$link[created]) $link[created] = $s[cas];
if ($link[current_action]=='link_copy') $link[rewrite_url] = '';
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
if ($link[status]=='queue')
{ $queue = 1;
  echo '<tr><td class="common_table_top_cell" colspan="2">Queue Options</td></tr>
  <tr><td align="left" colspan="2" nowrap>
  Approve it <input type="radio" name="link['.$n.'][approve]" value="yes" id="approve_'.$n.'">
  <a class="link10" href="#" onClick="uncheck_both('.$n.'); return false;">Uncheck these boxes</a><br />
  Reject it <input type="radio" name="link['.$n.'][approve]" value="no" id="reject_'.$n.'">
  and send email: <select class="select10" name="link['.$n.'][reject_email]">'.$link[reject_emails].'
  </select>
  or <input type="checkbox" name="link['.$n.'][reject_email_custom]" value="1" id="fullcust'.$n.'" onclick="show_hide_div(document.getElementById(\'fullcust'.$n.'\').checked,document.getElementById(\'test'.$n.'\'));" value="1"> Individual Message
  <tr><td align="left" colspan="2">
  <div id="test'.$n.'" style="display:none;">
  <table border=0 width=100% cellspacing=2 cellpadding=0>
  <tr>
  <td align="left">Subject</td>
  <td><input class="field10" name="link['.$n.'][email_subject]" style="width:650px;"></td>
  </tr>
  <tr>
  <td align="left" valign="top">Text<br /><span class="text10">Available variables:<br />#%title%# - Link Title<br />#%url%# - Link URL<br />#%description%# - Link description<br /></span></td>
  <td><textarea class="field10" name="link['.$n.'][email_text]" style="width:650px;height:250px;"></textarea></td>
  </tr>
  </table></DIV>
  </td></tr>';
  $x = get_item_variables('l',$n);
  if ($x[n]) { $link_old[rating] = $x[rating]; $link_old[votes] = $x[votes]; $link_old[clicks_in] = $x[clicks_in]; $link_old[clicks_in_m] = $x[clicks_in_m]; $link_old[hits] = $x[hits]; $link_old[hits_m] = $x[hits_m]; $link_old[pick] = $x[pick];  }
  $link = array_merge((array)$link,(array)$link_old);
}
echo '<tr><td class="common_table_top_cell" colspan="2">Public Data</td></tr>
<tr>
<td align="left"><a target="_blank" href="'.$link[url].'" title="Click here to visit this URL">URL</a></td>
<td align="left"><input class="field10" name="link['.$n.'][url]" style="width:650px;" maxlength=255 value="'.$link[url].'">';
if ($queue)
{ $x = parse_url($link[url]);
  $dom = str_replace('www.','',$x[host]);
  echo '<br /><a target="_blank" class="link10" target="_blank" href="blacklist.php?action=blacklist_updated&addremove=add&what=url&item='.$dom.'">Add domain '.$dom.' to blacklist</a>';
}
echo '</td></tr>
<tr>
<td align="left">Title</td>
<td align="left"><input class="field10" name="link['.$n.'][title]" style="width:650px;" maxlength=255 value="'.$link[title].'"></td>
</tr>';
if ($link[n]) echo '<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('l',$link[n],$link[rewrite_url],$link[category],1).'">'.get_detail_page_url('l',$link[n],$link[rewrite_url],$link[category],1).'</a></td>
</tr>';
echo '<tr>
<td align="left">Description</td>
<td align="left"><input class="field10" name="link['.$n.'][description]" style="width:650px;" maxlength=255 value="'.$link[description].'"></td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">Detailed description </td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">'.get_fckeditor('link['.$n.'][detail]',$link[detail],'AdminToolbar').'</td>
</tr>
<tr>
<td align="left" valign="top">Keywords<br /><span class="text10">One keyword or phrase per line or keywords separated by commas</span></td>
<td align="left" valign="top"><textarea class="field10" name="link['.$n.'][keywords]" style="width:650px;height:250px;">'.$link[keywords].'</textarea></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map  </td>
<td align="left" valign="top"><input class="field10" name="link['.$n.'][map]" style="width:650px;" maxlength=255 value="'.str_replace('_gmok_','',$link[map]).'"></td>
</tr>
<tr>
<td align="left" valign="top">RSS URL (it can automatically load news and show them on the link details page) </td>
<td align="left" valign="top"><input class="field10" name="link['.$n.'][rss_url]" style="width:650px;" maxlength=255 value="'.$link[rss_url].'"></td>
</tr>
';

$link[action] = $link[current_action];
echo user_defined_items_form('l',$link,$queue);
echo images_form_admin('l',$link,$queue);
echo '<tr><td class="common_table_top_cell" colspan="2">Link Features</td></tr>';
if ($n) echo '<tr>
<td align="left">Link number</td>
<td align="left">'.$n.'</td>
</tr>
<tr>
<td align="left">Comments</td>
<td align="left">'.$link[comments].'</td>
</tr>';
echo categories_rows_form('l',$link);
$q = dq("select * from $s[pr]links_recips_info where n = '$n'",1); $i_recip = mysql_fetch_assoc($q);
echo '<tr>
<td align="left" valign="top"><a target="_blank" href="'.$link[recip].'" title="Click here to visit this URL">Reciprocal URL</a></td>
<td align="left"><input class="field10" name="link['.$n.'][recip]" style="width:650px;" maxlength=255 value="'.$link[recip].'"><br /><span class="text10">'.$i_recip[info].'</span></td>
</tr>
<tr>
<td align="left">Date & time created</td>
<td align="left">'.date_select($link[created],'link['.$n.'][created]').' <input maxlength="5" name="link['.$n.'][created_time]" value="'.date('H:i',$link[created]).'" class="field10" style="width:50px"> Correct time format: 15:26</td>
</tr>
<tr>
<td align="left" valign="top">Valid</td>
<td align="left" nowrap>From '.date_select($link[t1],'link['.$n.'][t1]').' To '.date_select($link[t2],'link['.$n.'][t2]').'</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Page URL</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="link['.$n.'][rewrite_url]" maxlength=255 value="'.$link[rewrite_url].'"><br /><span class="text10">This is used if you have HTML Plugin. Only English letters, numbers and these characters: - _ .<br>If you let it blank, the script will generate the URL automatically by the Title field.</span></td>
</tr>
<tr>
<td align="left">Editor\'s Pick </td>
<td align="left" nowrap><select class="select10" name="link['.$n.'][pick]">
<option value="0"'.$pick[0].'>No</option><option value="1"'.$pick[1].'>1</option><option value="2"'.$pick[2].'>2</option>
<option value="3"'.$pick[3].'>3</option><option value="4"'.$pick[4].'>4</option><option value="5"'.$pick[5].'>5</option>
</select></td>
</tr>';
if ($n) votes_rating_form_admin('l',$n,$link[rating],$link[votes]);
echo '<tr>
<td align="left">Clicks in</td>
<td align="left"><input class="field10" name="link['.$n.'][clicks_in]" style="width:100px" maxlength=10 value="'.$link[clicks_in].'"></td>
</tr>
<tr>
<td align="left">Clicks out</td>
<td align="left"><input class="field10" name="link['.$n.'][hits]" style="width:100px" maxlength=10 value="'.$link[hits].'"></td>
</tr>
<tr>
<td align="left">Clicks in current month</td>
<td align="left"><input class="field10" name="link['.$n.'][clicks_in_m]" style="width:100px" maxlength=10 value="'.$link[clicks_in_m].'"></td>
</tr>
<tr>
<td align="left">Clicks out current month</td>
<td align="left"><input class="field10" name="link['.$n.'][hits_m]" style="width:100px" maxlength=10 value="'.$link[hits_m].'"></td>
</tr>
<tr>
<td align="left">Link is enabled </td>
<td align="left" nowrap><input type="checkbox" name="link['.$n.'][enabled]" value="1"'; if ((!$link[n]) OR ($link[status]=='queue') OR ($link[status]=='enabled')) echo ' checked'; echo '></td>
</tr>
<tr>
<td align="left" nowrap>Owner\'s username</td>
<td align="left"><input class="field10" name="link['.$n.'][username]" style="width:650px;" maxlength=255 value="'.$user[username].'"></td>
</tr>
<tr>
<td align="left" nowrap>Owner\'s name</td>
<td align="left"><input class="field10" name="link['.$n.'][name]" style="width:650px;" maxlength=255 value="'.$link[name].'"></td>
</tr>
<tr>
<td align="left"><a href="mailto:'.$link[email].'" title="Email the webmaster">Email</a></td>
<td align="left"><input class="field10" name="link['.$n.'][email]" style="width:650px;" maxlength=255 value="'.$link[email].'"></td>
</tr>
<tr>
<td align="left">Password</td>
<td align="left" nowrap><input class="field10" name="link['.$n.'][password]" style="width:100px" maxlength=15 value="'.$link[password].'"></td>
</tr>';
if (!$n) echo '<tr>
<td align="left" valign="top">Email link owner </td>
<td align="left" nowrap><input type="checkbox" name="link['.$n.'][email_owner]" value="1"><span class="text10"><br />It informs the link owner that this link has been created.<br />This email is based on template link_added_by_admin.txt.</span></td>
</tr>';
if ($n)
{ if (!$queue) echo '<tr>
  <td align="left">Mark it as Updated </td>
  <td align="left" nowrap><input type="checkbox" name="link['.$n.'][mark_updated]" checked></td>
  </tr>';
  $link_adv = get_link_adv_variables($n);
  echo '<tr><td class="common_table_top_cell" colspan="2">Advertising Features</td></tr>
  <tr><td colspan="2" align="center"><span class="text10">Example: 1000 = add 1000 impressions or clicks; -1000 = take 1000 impressions or clicks.<br />Each link can have any number of impressions OR any number of clicks OR any number of days.</span></td></tr>
  <tr>
  <td align="center" colspan="2">
  <table border=0 cellspacing=0 cellpadding=2>
  <tr>
  <td align="left" valign="top" nowrap>Static prices </td>
  <td align="left" nowrap>Impressions</td>
  <td align="left" nowrap>Current balance: '.$link_adv[i_now].'</td>
  <td align="left" nowrap>Add/take to balance </td>
  <td align="left" nowrap> <input class="field10" name="link['.$n.'][i_add_take]" size=6></td>
  <td align="left" nowrap>Total ordered </td>
  <td align="left" nowrap>'.$link_adv[i_order].'</td>
  <td align="left" nowrap>Price '.$s[currency].$s[i_static_price].' for 100 impressions </td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>&nbsp;</td>
  <td align="left" nowrap>Clicks</td>
  <td align="left" nowrap>Current balance '.$link_adv[c_now].'</td>
  <td align="left" nowrap>Add/take to balance </td>
  <td align="left" nowrap> <input class="field10" name="link['.$n.'][c_add_take]" size=6></td>
  <td align="left" nowrap>Total ordered </td>
  <td align="left" nowrap> '.$link_adv[c_order].'</td>
  <td align="left" nowrap>Price '.$s[currency].$s[c_static_price].' for 100 clicks </td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>&nbsp;</td>
  <td align="left" nowrap>Days </td>
  <td align="left" nowrap>Valid until '; if ($link_adv[d_validby]) echo datum($link_adv[d_validby]); else echo '-'; echo '</td>
  <td align="left" nowrap>Add/take days </td>
  <td align="left" nowrap> <input class="field10" name="link['.$n.'][d_add_take]" size=6></td>
  <td align="left" nowrap>Total ordered </td>
  <td align="left" nowrap> '.$link_adv[d_order].'</td>
  <td align="left" nowrap>Price '.$s[currency].$s[d_static_price].' for 1 day </td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Dynamic prices </td>
  <td align="left" nowrap>Clicks </td>
  <td align="left" nowrap>Current balance '.$link_adv[c_dynamic_now].'</td>
  <td align="left" nowrap>Add/take to balance </td>
  <td align="left" nowrap> <input class="field10" name="link['.$n.'][c_dynamic_add_take]" size=6></td>
  <td align="left" nowrap>Total ordered </td>
  <td align="left" nowrap> '.$link_adv[c_dynamic_order].'</td>
  <td align="left" nowrap>Price '.$s[currency].'<input class="field10" name="link['.$n.'][c_dynamic_price]" value="'.$link_adv[c_dynamic_price].'" size=6> for 1 click</td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Simple purchase</td>
  <td align="left" nowrap>Days</td>
  <td align="left" nowrap>Valid until '; if ($link_adv[d_validby_simple]) echo datum($link_adv[d_validby_simple]); else echo '-'; echo '</td>
  <td align="left" nowrap>Add/take days </td>
  <td align="left" nowrap> <input class="field10" name="link['.$n.'][d_add_take_simple]" size=6></td>
  <td align="left" nowrap>Total ordered </td>
  <td align="left" nowrap> '.$link_adv[d_order_simple].'</td>
  <td align="left" nowrap>&nbsp;</td>
  </tr>
  </table>
  </td>
  </tr>';
}
echo '</table></td></tr></table><br />';
}

#############################################################################

function link_edited_process($in) {
global $s;
$old = get_item_variables('l',$in[n]);
if ((!check_admin_categories('l',$old[c])) OR (!check_admin_categories('l',$in[categories]))) problem('You don\'t have the right to edit links in selected category(ies)');
$link_adv = get_link_adv_variables($in[n]);
$rating = rating_update_get_average('l',$in[n],$in[rates]);
$usit = item_updated_get_usit('l',$in);
if ($in[mark_updated]) $updated = "updated = '$s[cas]',"; else $updated = ''; 
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$in = replace_array_text($in);
$in[detail] = refund_html($in[detail]);
$c = categories_edited($in[categories]);
$en_cats = has_some_enabled_categories('l',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'l');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';

if ((trim($in[url])) AND ($s[add_http]) AND (!preg_match("/^(http:\/\/*+)/i",$in[url]))) $in[url] = 'http://'.$in[url];
if ((trim($in[recip])) AND ($s[add_http]) AND (!preg_match("/^(http:\/\/*+)/i",$in[recip]))) $in[recip] = 'http://'.$in[recip];
if ((trim($in[rss_url])) AND ($s[add_http]) AND (!preg_match("/^(http:\/\/*+)/i",$in[rss_url]))) $in[rss_url] = 'http://'.$in[rss_url];

if ($in[d_add_take])
{ if ($link_adv[d_validby]>$s[cas]) $d_valid_by = $link_adv[d_validby] + ($in[d_add_take]*86400);
  else $d_valid_by = $s[cas] + ($in[d_add_take]*86400);
}
else $d_valid_by = $link_adv[d_validby];
if ($in[d_add_take_simple])
{ if ($link_adv[d_validby_simple]>$s[cas]) $d_valid_by_simple = $link_adv[d_validby_simple] + ($in[d_add_take_simple]*86400);
  else $d_valid_by_simple = $s[cas] + ($in[d_add_take_simple]*86400);
}
else $d_valid_by_simple = $link_adv[d_validby_simple];
upload_files('l',$in[n],0,0,'');
if ($in[username]) $user = get_user_variables(0,$in[username]);
list($old_m,$old_d,$old_y) = explode('/',date('m/d/Y',$old[created]));
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
dq("update $s[pr]links set $updated url = '$in[url]', recip = '$in[recip]', title = '$in[title]', description = '$in[description]', detail = '$in[detail]', keywords = '$in[keywords]', map = '$in[map]$map_test', rss_url = '$in[rss_url]', c = '$c[categories]', c_path = '$c[categories_path]', owner = '$user[n]', name = '$in[name]', email = '$in[email]', password = '$in[password]', created = '$created', pick = '$in[pick]', rating = '$rating[average]', votes = '$rating[total_votes]', clicks_in = '$in[clicks_in]', clicks_in_m = '$in[clicks_in_m]', hits = '$in[hits]', hits_m = '$in[hits_m]', t1 = '$t1', t2 = '$t2', status = '$status', en_cats = '$en_cats', rewrite_url = '$in[rewrite_url]' where n = '$in[n]' and status != 'queue'",1);
dq("update $s[pr]links_adv set c_order = c_order + '$in[c_add_take]', c_now = c_now + '$in[c_add_take]', i_order = i_order + '$in[i_add_take]', i_now = i_now + '$in[i_add_take]', d_order = d_order + '$in[d_add_take]', d_validby = '$d_valid_by', c_dynamic_price = '$in[c_dynamic_price]', c_dynamic_order = c_dynamic_order + '$in[c_dynamic_add_take]', c_dynamic_now = c_dynamic_now + '$in[c_dynamic_add_take]', d_order_simple = d_order_simple + '$in[simple_d_add_take]', d_validby_simple = '$d_valid_by_simple', d_order_simple = d_order_simple + '$in[d_add_take_simple]' where n = '$in[n]'",1);
dq("update $s[pr]links_stat set c = '$in[hits]', c_month = '$in[hits_m]', r = (c/i)*100, r_month = (c_month/i_month)*100 where n = '$in[n]'",1);
add_update_user_items('l',$in[n],$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('l',$in[categories],$old[c]);
update_link_advertising_status($in[n]);
update_item_index('l',$in[n]);
update_item_image1('l',$in[n]);
}

##################################################################################
##################################################################################
##################################################################################

?>