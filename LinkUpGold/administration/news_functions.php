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
check_admin('news');
$q = dq("select count(*) from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$s[LUG_admin_username]' and $s[pr]admins.password = '$s[LUG_admin_password]' and $s[pr]admins_rights.n = $s[pr]admins.n and $s[pr]admins_rights.action = 'all_news'",1);
$x = mysql_fetch_row($q);
if ($x[0]) $s[admin_all_cats_n] = 1;
else get_allowed_categories('n');

##################################################################################
##################################################################################
##################################################################################

function show_one_new($news) {
global $s;
if (!is_array($news)) // je to cislo jenom
{ $news = get_item_variables('n',$news,0);
  $q = dq("select * from $s[pr]usit_values where use_for = 'n' AND n = '$news[n]'",1);
  while ($x = mysql_fetch_assoc($q))
  { $news['user_item_'.$x[item_n]][code] = $x[value_code];
    $news['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
if (!check_admin_categories('n',$news[c])) return false;
if (($news[created]+$s[n_marknew]) > $s[cas]) $icon = $s[new_img];
$rateicon = get_rateicon($news[rating]);
if ($news[status]=='enabled')
{ $manage = '<a href="new_details.php?action=new_manage&what=0&n='.$news[n].'">Disable</a>';
  $enabled = 'Yes';
}
else
{ $manage = '<a href="new_details.php?action=new_manage&what=1&n='.$news[n].'">Enable</a>';
  $enabled = 'No';
}
$user_items = user_defined_items_show('n',$news);
$created = datum ($news[created],1);
if ($news[updated]) $updated = datum ($news[updated],1); else $updated = 'Never yet';
$dates = get_dates_links_text($news);
if (item_is_active($news[t1],$news[t2],$news[status],'n',0))
$active = '<font color="green">News item is active</font>'; else $active = '<font color="red">News item is inactive</font>';

if ($news[show_checkbox]) $checkbox = '<input class="bbb" type="checkbox" name="news[]" value="'.$news[n].'">&nbsp;&nbsp;';
$news = stripslashes_array($news);
echo '<table border="0" width="99%" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan=2>
<span class="text13a_bold">'.$checkbox.$news[title].'</span>&nbsp&nbsp </td></tr>
<tr>
<td align="left" valign="top" width="20%">Description</td>
<td align="left" width="80%">'.$news[description].'&nbsp;</td>
</tr>
<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('n',$news[n],$news[rewrite_url],$news[category],1).'">'.get_detail_page_url('n',$news[n],$news[rewrite_url],$news[category],1).'</a></td>
</tr>
';
if ($news[text])
echo '<tr>
<td align="left" valign="top" nowrap>Text</td>
<td align="left">'.str_replace('<new_page>','<br /><br />&lt;new_page&gt;<br /><br />',$news[text]).'&nbsp;</td>
</tr>';
echo '<tr><td align="left" valign="top">Categories</td>
<td align="left">'.list_of_categories_for_item_admin('n',$news[c]).'</td></tr>';
echo $user_items;
echo '<tr>
<td align="left" nowrap>Owner</td>
<td align="left"><a href="mailto:'.$news[email].'">'.$news[name].'</a>&nbsp;</td>
</tr>';
if ($news[owner]) echo '<tr>
<td align="left" nowrap>Owners\'s username</td>
<td align="left"><a href="users.php?action=users_searched&username='.$news[owner].'&sort=username&order=asc">'.$news[owner].'</a></td>
</tr>';
images_show_admin('n',$news,0);
echo '<tr>
<td align="left" colspan=2><span class="text10">
Number: '."$news[n], Enabled: $enabled, Valid from $dates[t1] to $dates[t2], $active<br />
Created: $created, Updated: $updated<br />
Rating: $news[rating] $rateicon ($news[votes] votes), No. of reads: $news[hits], Pick value: $news[pick]".'</span></td></tr>
<tr><td align="left" colspan=2>['.$manage.']&nbsp;&nbsp;
[<a target="_self" href="new_details.php?action=new_edit&n='.$news[n].'" title="Edit this news">Edit</a>]&nbsp;&nbsp;
[<a target="_self" href="javascript: go_to_delete(\'Are you sure?\',\'new_details.php?action=new_delete&n='.$news[n].'\')">Delete</a>]&nbsp;&nbsp;
[<a target="_self" href="new_details.php?action=new_copy&n='.$news[n].'" title="Copy this news">Copy</a>]&nbsp;&nbsp;
[<a target="_self" href="comments.php?action=comments_view&what=n&n='.$news[n].'" title="Comments">Comments ('.$news[comments].')</a>]&nbsp;&nbsp;
</td></tr></table>
</td></tr></table>
<br />';
}

##################################################################################
##################################################################################
##################################################################################

function replace_array_for_news($a) {
if (!$a) return $a; 
reset ($a);
// jen pro admin area !
while (list ($k,$v) = each ($a))
{ if (is_array($v)) continue;
  if ($k=='text') $v = stripslashes($v);
  else $v = htmlspecialchars(stripslashes($v));
  $a[$k] = str_replace('&amp;','&',str_replace(chr(92),'&#92;',str_replace("'",'&#039;',$v)));
}
return $a;
}

##################################################################################

function new_create_edit_form($new) {
global $s;
$n = $new[n];
if (!check_admin_categories('n',$new[c])) problem('You don\'t have the right to edit news in selected category(ies)');
$new = stripslashes_array($new);
for ($x=0;$x<=5;$x++) { if ($new[pick]==$x) $pick[$x] = ' selected'; }
if ($new[popular]) $popular = ', Is popular'; else $popular = '';
if ($new[updated]) $updated = datum ($new[updated],1); else $updated = 'Never yet';
$rating = get_one_item_rating('n',$n);
if ($new[owner]) $user = get_user_variables($new[owner]);
if (!$new[created]) $new[created] = $s[cas];
if ($new[current_action]=='new_copy') $new[rewrite_url] = '';
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
if ($new[status]=='queue')
{ $queue = 1;
  echo '<tr><td class="common_table_top_cell" colspan="2">Queue Options</td></tr>
  <tr><td align="left" colspan="2" nowrap>
  Approve it <input type="radio" name="new['.$n.'][approve]" value="yes" id="approve_'.$n.'">
  <a class="link10" href="#" onClick="uncheck_both('.$n.'); return false;">Uncheck these boxes</a><br />
  Reject it <input type="radio" name="new['.$n.'][approve]" value="no" id="reject_'.$n.'">
  and send email: <select class="select10" name="new['.$n.'][reject_email]">'.$new[reject_emails].'
  </select>
  or <input type="checkbox" name="new['.$n.'][reject_email_custom]" value="1" id="fullcust'.$n.'" onclick="show_hide_div(document.getElementById(\'fullcust'.$n.'\').checked,document.getElementById(\'test'.$n.'\'));" value="1"> Individual Message
  <tr><td align="left" colspan="2">
  <div id="test'.$n.'" style="display:none;">
  <table border=0 width=100% cellspacing=2 cellpadding=0>
  <tr>
  <td align="left">Subject</td>
  <td><input class="field10" name="new['.$n.'][email_subject]" style="width:650px;"></td>
  </tr>
  <tr>
  <td align="left" valign="top">Text<br /><span class="text10">Available variables:<br />#%title%# - new item Title<br />#%description%# - Subtitle<br /></span></td>
  <td><textarea class="field10" name="new['.$n.'][email_text]" style="width:650px;height:250px;"></textarea></td>
  </tr>
  </table></DIV>
  </td></tr>';
  $x = get_item_variables('n',$n);
  if ($x[n]) { $new_old[rating] = $x[rating]; $new_old[votes] = $x[votes]; $new_old[clicks_in] = $x[clicks_in]; $new_old[clicks_in_m] = $x[clicks_in_m]; $new_old[hits] = $x[hits]; $new_old[hits_m] = $x[hits_m]; $new_old[pick] = $x[pick];  }
  $new = array_merge((array)$new,(array)$new_old);
}
echo '<tr><td class="common_table_top_cell" colspan="2">Public Data</td></tr>
<tr>
<td align="left">Title</td>
<td align="left"><input class="field10" name="new['.$n.'][title]" style="width:650px;" maxlength=255 value="'.$new[title].'">';
if ($new[title]) echo '<a class="link10" target="_blank" href="news.php?action=news_searched&exact_title='.urlencode($new[title]).'&skip='.$n.'&boolean=and&showtext=1"><br />Search for news with the same title</a>';
echo '</td>
</tr>';
if ($new[n]) echo '<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('n',$new[n],$new[rewrite_url],$new[category],1).'">'.get_detail_page_url('n',$new[n],$new[rewrite_url],$new[category],1).'</a></td>
</tr>';
echo '<tr>
<td align="left">Subtitle</td>
<td align="left"><input class="field10" name="new['.$n.'][description]" style="width:650px;" maxlength=255 value="'.$new[description].'"></td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">Details </td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">'.get_fckeditor('new['.$n.'][text]',$new[text],'AdminToolbar').'</td>
</tr>
<tr>
<td align="left">Article URL</td>
<td align="left"><input class="field10" name="new['.$n.'][url]" style="width:650px;" value="'.$new[url].'"></td>
</tr>
<tr>
<td align="left" valign="top">Keywords<br /><span class="text10">One keyword or phrase per line or keywords separated by commas</span></td>
<td align="left" valign="top"><textarea class="field10" name="new['.$n.'][keywords]" style="width:650px;height:250px;">'.$new[keywords].'</textarea></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map  </td>
<td align="left" valign="top"><input class="field10" name="new['.$n.'][map]" style="width:650px;" maxlength=255 value="'.str_replace('_gmok_','',$new[map]).'"></td>
</tr>';

$new[action] = $new[current_action];
echo user_defined_items_form('n',$new,$queue);
echo images_form_admin('n',$new,$queue);
echo '<tr><td class="common_table_top_cell" colspan="2">News Item Features</td></tr>';
if ($n) echo '<tr>
<td align="left">News item number</td>
<td align="left">'.$n.'</td>
</tr>
<tr>
<td align="left">Comments</td>
<td align="left">'.$new[comments].'</td>
</tr>';
echo categories_rows_form('n',$new);
echo '<tr>
<td align="left">Date & time created</td>
<td align="left">'.date_select($new[created],'new['.$n.'][created]').' <input maxlength="5" name="new['.$n.'][created_time]" value="'.date('H:i',$new[created]).'" class="field10" style="width:50px"> Correct time format: 15:26</td>
</tr>
<tr>
<td align="left" valign="top">Valid</td>
<td align="left" nowrap>From '.date_select($new[t1],'new['.$n.'][t1]').' To '.date_select($new[t2],'new['.$n.'][t2]').'</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Page URL</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="new['.$n.'][rewrite_url]" maxlength=255 value="'.$new[rewrite_url].'"><br /><span class="text10">This is used if you have HTML Plugin. Only English letters, numbers and these characters: - _ .<br>If you let it blank, the script will generate the URL automatically by the Title field.</span></td>
</tr>
<tr>
<td align="left">Editor\'s Pick </td>
<td align="left" nowrap><select class="select10" name="new['.$n.'][pick]">
<option value="0"'.$pick[0].'>No</option><option value="1"'.$pick[1].'>1</option><option value="2"'.$pick[2].'>2</option>
<option value="3"'.$pick[3].'>3</option><option value="4"'.$pick[4].'>4</option><option value="5"'.$pick[5].'>5</option>
</select></td>
</tr>';
if ($n) votes_rating_form_admin('n',$n,$new[rating],$new[votes]);
echo '<tr>
<td align="left"># of reads</td>
<td align="left"><input class="field10" name="new['.$n.'][hits]" style="width:100px" maxlength=10 value="'.$new[hits].'"></td>
</tr>
<tr>
<td align="left" nowrap>News item is enabled</td>
<td align="left"><input type="checkbox" name="new['.$n.'][enabled]" value="1" '; if ((!$new[n]) OR ($new[status]=='queue') OR ($new[status]=='enabled')) echo ' checked'; echo '></td>
</tr>
<!--<tr>
<td align="left" nowrap>Owner\'s username</td>
<td align="left"><input class="field10" name="new['.$n.'][username]" style="width:650px;" maxlength=255 value="'.$user[username].'"></td>
</tr>-->
<tr>
<td align="left" nowrap>Owner\'s name</td>
<td align="left"><input class="field10" name="new['.$n.'][name]" style="width:650px;" maxlength=255 value="'.$new[name].'"></td>
</tr>
<tr>
<td align="left"><a href="mailto:'.$new[email].'" title="Email the owner">Owner\'s email</a></td>
<td align="left"><input class="field10" name="new['.$n.'][email]" style="width:650px;" maxlength=255 value="'.$new[email].'"></td>
</tr>
<!--<tr>
<td align="left">Password</td>
<td align="left" nowrap><input class="field10" name="new['.$n.'][password]" style="width:100px" maxlength=15 value="'.$new[password].'"></td>
</tr>-->';
if (($n) AND (!$queue))
{ echo '<tr>
  <td align="left">Mark it as Updated </td>
  <td align="left" nowrap><input type="checkbox" name="new['.$n.'][mark_updated]"'.$x.'></td>
  </tr>';
}
echo '</table></td></tr></table><br />';
}

##################################################################################

function new_edited_process($in) {
global $s;
$old = get_item_variables('n',$in[n]);
if ((!check_admin_categories('n',$old[c])) OR (!check_admin_categories('n',$in[categories]))) problem('You don\'t have the right to edit news in selected category(ies)');
$rating = rating_update_get_average('n',$in[n],$in[rates]);
$usit = item_updated_get_usit('n',$in);
if ($in[mark_updated]) $updated = "updated = '$s[cas]',"; else $updated = ''; 
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$in = replace_array_text($in);
$in[text] = refund_html($in[text]);
$c = categories_edited($in[categories]);

$en_cats = has_some_enabled_categories('n',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'n');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';

upload_files('n',$in[n],0,0,'');
if ($in[username]) $user = get_user_variables(0,$in[username]);
list($old_m,$old_d,$old_y) = explode('/',date('m/d/Y',$old[created]));
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
if (!$in[password]) $in[password] = get_random_password($in[title],$in[description],$c[categories]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
dq("update $s[pr]news set $updated title = '$in[title]', description = '$in[description]', text = '$in[text]', url = '$in[url]', keywords = '$in[keywords]', map = '$in[map]$map_test', c = '$c[categories]', c_path = '$c[categories_path]', owner = '$user[n]', name = '$in[name]', email = '$in[email]', password = '$in[password]', created = '$created', hits = '$in[hits]', pick = '$in[pick]', rating = '$rating[average]', votes = '$rating[total_votes]', t1 = '$t1', t2 = '$t2', status = '$status', en_cats = '$en_cats', rewrite_url = '$in[rewrite_url]' where n = '$in[n]' and status != 'queue'",1);
add_update_user_items('n',$in[n],$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('n',$in[categories],$old[c]);
update_item_index('n',$in[n]);
update_item_image1('n',$in[n]);
}

##################################################################################
##################################################################################
##################################################################################

?>