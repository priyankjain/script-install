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
check_admin('videos');
$q = dq("select count(*) from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$s[LUG_admin_username]' and $s[pr]admins.password = '$s[LUG_admin_password]' and $s[pr]admins_rights.n = $s[pr]admins.n and $s[pr]admins_rights.action = 'all_videos'",1);
$x = mysql_fetch_row($q);
if ($x[0]) $s[admin_all_cats_v] = 1;
else get_allowed_categories('v');

##################################################################################
##################################################################################
##################################################################################

function show_one_video($video) {
global $s;
if (!is_array($video)) // je to cislo jenom
{ $video = get_item_variables('v',$video,0);
  $q = dq("select * from $s[pr]usit_values where use_for = 'v' AND n = '$video[n]'",1);
  while ($x = mysql_fetch_assoc($q))
  { $video['user_item_'.$x[item_n]][code] = $x[value_code];
    $video['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
if (!check_admin_categories('v',$video[c])) return false;
if (($video[created]+$s[v_marknew]) > $s[cas]) $icon = $s[new_img];
$rateicon = get_rateicon($video[rating]);
if ($video[status]=='enabled')
{ $manage = '<a href="video_details.php?action=video_manage&what=0&n='.$video[n].'">Disable</a>';
  $enabled = 'Yes';
}
else
{ $manage = '<a href="video_details.php?action=video_manage&what=1&n='.$video[n].'">Enable</a>';
  $enabled = 'No';
}
$user_items = user_defined_items_show('v',$video);
$created = datum ($video[created],1);
if ($video[updated]) $updated = datum ($video[updated],1); else $updated = 'Never yet';
$dates = get_dates_links_text($video);
if (item_is_active($video[t1],$video[t2],$video[status],'v',0))
$active = '<font color="green">Video is active</font>'; else $active = '<font color="red">Video is inactive</font>';

if ($video[show_checkbox]) $checkbox = '<input class="bbb" type="checkbox" name="videos[]" value="'.$video[n].'">&nbsp;&nbsp;';
$video = stripslashes_array($video);
echo '<table border="0" width="99%" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan=2>
<span class="text13a_bold">'.$checkbox.$video[title].'</span>&nbsp&nbsp </td></tr>
<tr>
<td align="left" valign="top" width="20%">Description</td>
<td align="left" width="80%">'.$video[description].'&nbsp;</td>
</tr>
<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('v',$video[n],$video[rewrite_url],$video[category],1).'">'.get_detail_page_url('v',$video[n],$video[rewrite_url],$video[category],1).'</a></td>
</tr>
';
echo '<tr><td align="left" valign="top">Categories</td>
<td align="left">'.list_of_categories_for_item_admin('v',$video[c]).'</td>
</tr>';
if ($video[youtube_thumbnail]) echo '<tr>
<td align="left" valign="top">Thumbnail</td>
<td align="left" valign="top"><img src="'.$video[youtube_thumbnail].'"></td>
</tr>';
echo '<tr>
<td align="left" nowrap>Length</td>
<td align="left">'.$video[youtube_length].' seconds</td>
</tr>';
echo $user_items;
echo '<tr>
<td align="left" nowrap>Owner</td>
<td align="left">'.$video[name].'&nbsp;</td>
</tr>';
if ($video[owner]) echo '<tr>
<td align="left" nowrap>Owners\'s username</td>
<td align="left"><a href="users.php?action=users_searched&username='.$video[owner].'&sort=username&order=asc">'.$video[owner].'</a></td>
</tr>';
images_show_admin('v',$video,0);
echo '<tr>
<td align="left" colspan=2><span class="text10">
Number: '."$video[n], Enabled: $enabled, Valid from $dates[t1] to $dates[t2], $active<br />
Created: $created, Updated: $updated<br />
Rating: $video[rating] $rateicon ($video[votes] votes), No. of reads: $video[hits], Pick value: $video[pick]".'</span></td></tr>
<tr><td align="left" colspan=2>['.$manage.']&nbsp;&nbsp;
[<a target="_self" href="video_details.php?action=video_edit&n='.$video[n].'" title="Edit this videos">Edit</a>]&nbsp;&nbsp;
[<a target="_self" href="javascript: go_to_delete(\'Are you sure?\',\'video_details.php?action=video_delete&n='.$video[n].'\')">Delete</a>]&nbsp;&nbsp;
[<a target="_self" href="video_details.php?action=video_copy&n='.$video[n].'" title="Copy this videos">Copy</a>]&nbsp;&nbsp;
[<a target="_self" href="comments.php?action=comments_view&what=v&n='.$video[n].'" title="Comments">Comments ('.$video[comments].')</a>]&nbsp;&nbsp;
</td></tr></table>
</td></tr></table>
<br />';
}

##################################################################################
##################################################################################
##################################################################################

function replace_array_for_videos($a) {
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

function video_create_edit_form($video) {
global $s;
$n = $video[n];
if (!check_admin_categories('v',$video[c])) problem('You don\'t have the right to edit videos in selected category(ies)');
$video = stripslashes_array($video);
for ($x=0;$x<=5;$x++) { if ($video[pick]==$x) $pick[$x] = ' selected'; }
if ($video[popular]) $popular = ', Is popular'; else $popular = '';
if ($video[updated]) $updated = datum ($video[updated],1); else $updated = 'Never yet';
$rating = get_one_item_rating('v',$n);
if ($video[owner]) $user = get_user_variables($video[owner]);
if (!$video[created]) $video[created] = $s[cas];
if ($video[current_action]=='video_copy') $video[rewrite_url] = '';
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
if ($video[status]=='queue')
{ $queue = 1;
  echo '<tr><td class="common_table_top_cell" colspan="2">Queue Options</td></tr>
  <tr><td align="left" colspan="2" nowrap>
  Approve it <input type="radio" name="video['.$n.'][approve]" value="yes" id="approve_'.$n.'">
  <a class="link10" href="#" onClick="uncheck_both('.$n.'); return false;">Uncheck these boxes</a><br />
  Reject it <input type="radio" name="video['.$n.'][approve]" value="no" id="reject_'.$n.'">
  and send email: <select class="select10" name="video['.$n.'][reject_email]">'.$video[reject_emails].'
  </select>
  or <input type="checkbox" name="video['.$n.'][reject_email_custom]" value="1" id="fullcust'.$n.'" onclick="show_hide_div(document.getElementById(\'fullcust'.$n.'\').checked,document.getElementById(\'test'.$n.'\'));" value="1"> Individual Message
  <tr><td align="left" colspan="2">
  <div id="test'.$n.'" style="display:none;">
  <table border=0 width=100% cellspacing=2 cellpadding=0>
  <tr>
  <td align="left">Subject</td>
  <td><input class="field10" name="video['.$n.'][email_subject]" style="width:650px;"></td>
  </tr>
  <tr>
  <td align="left" valign="top">Text<br /><span class="text10">Available variables:<br />#%title%# - Video Title<br />#%description%# - Subtitle<br /></span></td>
  <td><textarea class="field10" name="video['.$n.'][email_text]" style="width:650px;height:250px;"></textarea></td>
  </tr>
  </table></DIV>
  </td></tr>';
  $x = get_item_variables('v',$n);
  if ($x[n]) { $video_old[rating] = $x[rating]; $video_old[votes] = $x[votes]; $video_old[clicks_in] = $x[clicks_in]; $video_old[clicks_in_m] = $x[clicks_in_m]; $video_old[hits] = $x[hits]; $video_old[hits_m] = $x[hits_m]; $video_old[pick] = $x[pick];  }
  $video = array_merge((array)$video,(array)$video_old);
}
echo '<tr><td class="common_table_top_cell" colspan="2">Public Data</td></tr>
<tr>
<td align="left">Title</td>
<td align="left"><input class="field10" name="video['.$n.'][title]" style="width:650px;" maxlength=255 value="'.$video[title].'">';
if ($video[title]) echo '<a class="link10" target="_blank" href="videos.php?action=videos_searched&exact_title='.urlencode($video[title]).'&skip='.$n.'&boolean=and&showtext=1"><br />Search for videos with the same title</a>';
echo '</td>
</tr>';
if ($video[n]) echo '<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('v',$video[n],$video[rewrite_url],$video[category],1).'">'.get_detail_page_url('v',$video[n],$video[rewrite_url],$video[category],1).'</a></td>
</tr>';
echo '<tr>
<td nowrap align="left" valign="top" colspan="2">Description </td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">'.get_fckeditor('video['.$n.'][description]',$video[description],'AdminToolbar').'</td>
</tr>
<tr>
<td align="left" valign="top">Video URL at Youtube</td>
<td align="left" valign="top"><input class="field10" name="video['.$n.'][youtube_url]" style="width:650px" value="'.get_youtube_url($video[youtube_id]).'"></td>
</tr>
<tr>
<td align="left" valign="top">Complete code<br /><br /><span class="text10">Enter a complete code to embed a video from other service than Youtube</span></td>
<td align="left" valign="top"><textarea class="field10" name="video['.$n.'][video_code]" style="width:650px;height:250px;">'.$video[video_code].'</textarea></td>
</tr>
<tr>
<td align="left" valign="top">Video length </td>
<td align="left" valign="top"><input class="field10" name="video['.$n.'][youtube_length]" style="width:100px" value="'.$video[youtube_length].'"> seconds</td>
</tr>
<tr>
<td align="left" valign="top">Thumbnail </td>
<td align="left" valign="top"><input class="field10" name="video['.$n.'][youtube_thumbnail]" style="width:650px;" maxlength=255 value="'.$video[youtube_thumbnail].'"><br /><span class="text10">If you use a video from Youtube, keep it empty to use their default thumbnail.</span></td>
</tr>';
if ($video[youtube_thumbnail]) echo '<tr>
<td align="left" valign="top">Current thumbnail</td>
<td align="left" valign="top"><img src="'.$video[youtube_thumbnail].'"></td>
</tr>';
if ($video[youtube_id]) echo '<tr>
<td align="left" valign="top">Video </td>
<td align="left" valign="top">'.youtube_player($video[youtube_id]).'</td>
</tr>';
elseif ($video[video_code]) echo '<tr>
<td align="left" valign="top">Video </td>
<td align="left" valign="top">'.$video[video_code].'</td>
</tr>';
echo '<tr>
<td align="left" valign="top">Keywords<br /><span class="text10">One keyword or phrase per line or keywords separated by commas</span></td>
<td align="left" valign="top"><textarea class="field10" name="video['.$n.'][keywords]" style="width:650px;height:250px;">'.$video[keywords].'</textarea></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map  </td>
<td align="left" valign="top"><input class="field10" name="video['.$n.'][map]" style="width:650px;" maxlength=255 value="'.str_replace('_gmok_','',$video[map]).'"></td>
</tr>';

$video[action] = $video[current_action];
echo user_defined_items_form('v',$video,$queue);
echo images_form_admin('v',$video,$queue);
echo '<tr><td class="common_table_top_cell" colspan="2">Features</td></tr>';
if ($n) echo '<tr>
<td align="left">Videos number</td>
<td align="left">'.$n.'</td>
</tr>
<tr>
<td align="left">Comments</td>
<td align="left">'.$video[comments].'</td>
</tr>';
echo categories_rows_form('v',$video);
echo '<tr>
<td align="left">Date & time created</td>
<td align="left">'.date_select($video[created],'video['.$n.'][created]').' <input maxlength="5" name="video['.$n.'][created_time]" value="'.date('H:i',$video[created]).'" class="field10" style="width:50px"> Correct time format: 15:26</td>
</tr>
<tr>
<td align="left" valign="top">Valid</td>
<td align="left" nowrap>From '.date_select($video[t1],'video['.$n.'][t1]').' To '.date_select($video[t2],'video['.$n.'][t2]').'</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Page URL</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="video['.$n.'][rewrite_url]" maxlength=255 value="'.$video[rewrite_url].'"><br /><span class="text10">This is used if you have HTML Plugin. Only English letters, numbers and these characters: - _ .<br>If you let it blank, the script will generate the URL automatically by the Title field.</span></td>
</tr>
<tr>
<td align="left">Editor\'s Pick </td>
<td align="left" nowrap><select class="select10" name="video['.$n.'][pick]">
<option value="0"'.$pick[0].'>No</option><option value="1"'.$pick[1].'>1</option><option value="2"'.$pick[2].'>2</option>
<option value="3"'.$pick[3].'>3</option><option value="4"'.$pick[4].'>4</option><option value="5"'.$pick[5].'>5</option>
</select></td>
</tr>';
if ($n) votes_rating_form_admin('v',$n,$video[rating],$video[votes]);
echo '<tr>
<td align="left">Views</td>
<td align="left"><input class="field10" name="video['.$n.'][hits]" style="width:100px" maxlength=10 value="'.$video[hits].'"></td>
</tr>
<tr>
<td align="left" nowrap>Video is enabled</td>
<td align="left"><input type="checkbox" name="video['.$n.'][enabled]" value="1" '; if ((!$video[n]) OR ($video[status]=='queue') OR ($video[status]=='enabled')) echo ' checked'; echo '></td>
</tr>
<!--<tr>
<td align="left" nowrap>Owner\'s username</td>
<td align="left"><input class="field10" name="video['.$n.'][username]" style="width:650px;" maxlength=255 value="'.$user[username].'"></td>
</tr>-->
<tr>
<td align="left" nowrap>Owner\'s name</td>
<td align="left"><input class="field10" name="video['.$n.'][name]" style="width:650px;" maxlength=255 value="'.$video[name].'"></td>
</tr>
<tr>
<td align="left"><a href="mailto:'.$video[email].'" title="Email the owner">Owner\'s email</a></td>
<td align="left"><input class="field10" name="video['.$n.'][email]" style="width:650px;" maxlength=255 value="'.$video[email].'"></td>
</tr>
<!--<tr>
<td align="left">Password</td>
<td align="left" nowrap><input class="field10" name="video['.$n.'][password]" style="width:100px" maxlength=15 value="'.$video[password].'"></td>
</tr>-->';
if (($n) AND (!$queue))
{ echo '<tr>
  <td align="left">Mark it as Updated </td>
  <td align="left" nowrap><input type="checkbox" name="video['.$n.'][mark_updated]"'.$x.'></td>
  </tr>';
}
echo '</table></td></tr></table><br />';
}

##################################################################################

function video_edited_process($in) {
global $s;
$old = get_item_variables('v',$in[n]);
if ((!check_admin_categories('v',$old[c])) OR (!check_admin_categories('v',$in[categories]))) problem('You don\'t have the right to edit videos in selected category(ies)');
$rating = rating_update_get_average('v',$in[n],$in[rates]);
$usit = item_updated_get_usit('v',$in);
if ($in[mark_updated]) $updated = "updated = '$s[cas]',"; else $updated = ''; 
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$in = replace_array_text($in);
$in[description] = refund_html($in[description]);
$c = categories_edited($in[categories]);

$en_cats = has_some_enabled_categories('v',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'v');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';

upload_files('v',$in[n],0,0,'');
if ($in[username]) $user = get_user_variables(0,$in[username]);
list($old_m,$old_d,$old_y) = explode('/',date('m/d/Y',$old[created]));
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
if (!$in[password]) $in[password] = get_random_password($in[title],$in[description],$c[categories]);
$youtube_id = get_youtube_id($in[youtube_url]);
if ((!$in[youtube_thumbnail]) AND ($youtube_id)) $in[youtube_thumbnail] = str_replace('#%id%#',$youtube_id,$s[youtube_thumbnail]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
$in[video_code] = refund_html($in[video_code]);
dq("update $s[pr]videos set $updated title = '$in[title]', description = '$in[description]', keywords = '$in[keywords]', map = '$in[map]$map_test', c = '$c[categories]', c_path = '$c[categories_path]', owner = '$user[n]', name = '$in[name]', email = '$in[email]', password = '$in[password]', created = '$created', hits = '$in[hits]', pick = '$in[pick]', rating = '$rating[average]', votes = '$rating[total_votes]', t1 = '$t1', t2 = '$t2', status = '$status', en_cats = '$en_cats', rewrite_url = '$in[rewrite_url]', youtube_id = '$youtube_id', youtube_length = '$in[youtube_length]', youtube_thumbnail = '$in[youtube_thumbnail]', video_code = '$in[video_code]' where n = '$in[n]' and status != 'queue'",1);

add_update_user_items('v',$in[n],$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('v',$in[categories],$old[c]);
update_item_index('v',$in[n]);
update_item_image1('v',$in[n]);
}

##################################################################################
##################################################################################
##################################################################################

?>