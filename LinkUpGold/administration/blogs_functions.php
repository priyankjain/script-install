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
check_admin('blogs');
$q = dq("select count(*) from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$s[LUG_admin_username]' and $s[pr]admins.password = '$s[LUG_admin_password]' and $s[pr]admins_rights.n = $s[pr]admins.n and $s[pr]admins_rights.action = 'all_blogs'",1);
$x = mysql_fetch_row($q);
if ($x[0]) $s[admin_all_cats_b] = 1;
else get_allowed_categories('b');

##################################################################################
##################################################################################
##################################################################################

function show_one_blog($blog) {
global $s;
if (!is_array($blog)) // je to cislo jenom
{ $blog = get_item_variables('b',$blog,0);
  $q = dq("select * from $s[pr]usit_values where use_for = 'b' AND n = '$blog[n]'",1);
  while ($x = mysql_fetch_assoc($q))
  { $blog['user_item_'.$x[item_n]][code] = $x[value_code];
    $blog['user_item_'.$x[item_n]][text] = $x[value_text];
  }
}
if (!check_admin_categories('b',$blog[c])) return false;
if (($blog[created]+$s[b_marknew]) > $s[cas]) $icon = $s[new_img];
$rateicon = get_rateicon($blog[rating]);
if ($blog[status]=='enabled')
{ $manage = '<a href="blog_details.php?action=blog_manage&what=0&n='.$blog[n].'">Disable</a>';
  $enabled = 'Yes';
}
else
{ $manage = '<a href="blog_details.php?action=blog_manage&what=1&n='.$blog[n].'">Enable</a>';
  $enabled = 'No';
}
$user_items = user_defined_items_show('b',$blog);
$created = datum ($blog[created],1);
if ($blog[updated]) $updated = datum ($blog[updated],1); else $updated = 'Never yet';
$dates = get_dates_links_text($blog);
if (item_is_active($blog[t1],$blog[t2],$blog[status],'b',0))
$active = '<font color="green">Blog is active</font>'; else $active = '<font color="red">Blog is inactive</font>';

if ($blog[show_checkbox]) $checkbox = '<input class="bbb" type="checkbox" name="blog[]" value="'.$blog[n].'">&nbsp;&nbsp;';
$blog = stripslashes_array($blog);
echo '<table border="0" width="99%" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan=2>
<span class="text13a_bold">'.$checkbox.$blog[title].'</span>&nbsp&nbsp </td></tr>
<tr><td align="left" valign="top" width="20%">Subtitle</td>
<td align="left" width="80%">'.$blog[description].'</td>
</tr>
<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('b',$blog[n],$blog[rewrite_url],$blog[category],1).'">'.get_detail_page_url('b',$blog[n],$blog[rewrite_url],$blog[category],1).'</a></td>
</tr>
';
if ($blog[text])
echo '<tr><td align="left" valign="top" nowrap>Text</td>
<td align="left">'.str_replace('<new_page>','<br /><br />&lt;new_page&gt;<br /><br />',$blog[text]).'</td></tr>';
echo '<tr><td align="left" valign="top">Categories</td>
<td align="left">'.list_of_categories_for_item_admin('b',$blog[c]).'</td></tr>';
echo $user_items;
echo '<tr>
<td align="left" nowrap>Owner</td>
<td align="left"><a href="mailto:'.$blog[email].'">'.$blog[name].'</a>&nbsp;</td>
</tr>';
if ($blog[owner]) echo '<tr>
<td align="left" nowrap>Owner\'s username</td>
<td align="left"><a href="users.php?action=users_searched&username='.$blog[owner].'&sort=username&order=asc">'.$blog[owner].'</a></td>
</tr>';
images_show_admin('b',$blog,0);
echo '<tr>
<td align="left" colspan=2><span class="text10">
Number: '."$blog[n], Enabled: $enabled, Valid from $dates[t1] to $dates[t2], $active<br />
Created: $created, Updated: $updated<br />
Rating: $blog[rating] $rateicon ($blog[votes] votes), No. of reads: $blog[hits], Pick value: $blog[pick]".'</span></td></tr>
<tr><td align="left" colspan=2>['.$manage.']&nbsp;&nbsp;
[<a target="_self" href="blog_details.php?action=blog_edit&n='.$blog[n].'" title="Edit this blog">Edit</a>]&nbsp;&nbsp;
[<a target="_self" href="javascript: go_to_delete(\'Are you sure?\',\'blog_details.php?action=blog_delete&n='.$blog[n].'\')" title="Delete this blog">Delete</a>]&nbsp;&nbsp;
[<a target="_self" href="blog_details.php?action=blog_copy&n='.$blog[n].'" title="Copy this blog">Copy</a>]&nbsp;&nbsp;
[<a target="_self" href="comments.php?action=comments_view&what=b&n='.$blog[n].'" title="Comments">Comments ('.$blog[comments].')</a>]&nbsp;&nbsp;
</td></tr></table>
</td></tr></table>
<br />';
}

##################################################################################
##################################################################################
##################################################################################

function replace_array_for_blogs($a) {
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

function blog_create_edit_form($blog) {
global $s;
$n = $blog[n];
if (!check_admin_categories('b',$blog[c])) problem('You don\'t have the right to edit blogs in selected category(ies)');
$blog = stripslashes_array($blog);
for ($x=0;$x<=5;$x++) { if ($blog[pick]==$x) $pick[$x] = ' selected'; }
if ($blog[popular]) $popular = ', Is popular'; else $popular = '';
if ($blog[updated]) $updated = datum ($blog[updated],1); else $updated = 'Never yet';
$rating = get_one_item_rating('l',$n);
if ($blog[owner]) $user = get_user_variables($blog[owner]);
if (!$blog[created]) $blog[created] = $s[cas];
if ($blog[current_action]=='blog_copy') $blog[rewrite_url] = '';
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
if ($blog[status]=='queue')
{ $queue = 1;
  echo '<tr><td class="common_table_top_cell" colspan="2">Queue Options</td></tr>
  <tr><td align="left" colspan="2" nowrap>
  Approve it <input type="radio" name="blog['.$n.'][approve]" value="yes" id="approve_'.$n.'">
  <a class="link10" href="#" onClick="uncheck_both('.$n.'); return false;">Uncheck these boxes</a><br />
  Reject it <input type="radio" name="blog['.$n.'][approve]" value="no" id="reject_'.$n.'">
  and send email: <select class="select10" name="blog['.$n.'][reject_email]">'.$blog[reject_emails].'
  </select>
  or <input type="checkbox" name="blog['.$n.'][reject_email_custom]" value="1" id="fullcust'.$n.'" onclick="show_hide_div(document.getElementById(\'fullcust'.$n.'\').checked,document.getElementById(\'test'.$n.'\'));" value="1"> Individual Message
  <tr><td align="left" colspan="2">
  <div id="test'.$n.'" style="display:none;">
  <table border=0 width=100% cellspacing=2 cellpadding=0>
  <tr>
  <td align="left">Subject</td>
  <td><input class="field10" name="blog['.$n.'][email_subject]" style="width:650px;"></td>
  </tr>
  <tr>
  <td align="left" valign="top">Text<br /><span class="text10">Available variables:<br />#%title%# - Blog Title<br />#%description%# - Subtitle<br /></span></td>
  <td><textarea class="field10" name="blog['.$n.'][email_text]" style="width:650px;height:250px;"></textarea></td>
  </tr>
  </table></DIV>
  </td></tr>';
  $x = get_item_variables('b',$n);
  if ($x[n]) { $blog_old[rating] = $x[rating]; $blog_old[votes] = $x[votes]; $blog_old[clicks_in] = $x[clicks_in]; $blog_old[clicks_in_m] = $x[clicks_in_m]; $blog_old[hits] = $x[hits]; $blog_old[hits_m] = $x[hits_m]; $blog_old[pick] = $x[pick];  }
  $blog = array_merge((array)$blog,(array)$blog_old);
}
echo '<tr><td class="common_table_top_cell" colspan="2">Public Data</td></tr>
<tr>
<td align="left">Title</td>
<td align="left"><input class="field10" name="blog['.$n.'][title]" style="width:650px;" maxlength=255 value="'.$blog[title].'">';
if ($blog[title]) echo '<a class="link10" target="_blank" href="blogs.php?action=blogs_searched&exact_title='.urlencode($blog[title]).'&skip='.$n.'&boolean=and&showtext=1"><br />Search for blogs with the same title</a>';
echo '</td>
</tr>';
if ($blog[n]) echo '<tr>
<td align="left">Public URL</td>
<td align="left"><a target="_blank" href="'.get_detail_page_url('b',$blog[n],$blog[rewrite_url],$blog[category],1).'">'.get_detail_page_url('b',$blog[n],$blog[rewrite_url],$blog[category],1).'</a></td>
</tr>';
echo '<tr>
<td align="left">Subtitle</td>
<td align="left"><input class="field10" name="blog['.$n.'][description]" style="width:650px;" maxlength=255 value="'.$blog[description].'"></td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">Full text </td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">'.get_fckeditor('blog['.$n.'][text]',$blog[text],'AdminToolbar').'</td>
</tr>
<tr>
<td align="left" valign="top">Keywords<br /><span class="text10">One keyword or phrase per line or keywords separated by commas</span></td>
<td align="left" valign="top"><textarea class="field10" name="blog['.$n.'][keywords]" style="width:650px;height:250px;">'.$blog[keywords].'</textarea></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map  </td>
<td align="left" valign="top"><input class="field10" name="blog['.$n.'][map]" style="width:650px;" maxlength=255 value="'.str_replace('_gmok_','',$blog[map]).'"></td>
</tr>
';

$blog[action] = $blog[current_action];
echo user_defined_items_form('b',$blog,$queue);
echo images_form_admin('b',$blog,$queue);
echo '<tr><td class="common_table_top_cell" colspan="2">Blog Features</td></tr>';
if ($n) echo '<tr>
<td align="left">Blog number</td>
<td align="left">'.$n.'</td>
</tr>
<tr>
<td align="left">Comments</td>
<td align="left">'.$blog[comments].'</td>
</tr>';
echo categories_rows_form('b',$blog);
echo '<tr>
<td align="left">Date & time created</td>
<td align="left">'.date_select($blog[created],'blog['.$n.'][created]').' <input maxlength="5" name="blog['.$n.'][created_time]" value="'.date('H:i',$blog[created]).'" class="field10" style="width:50px"> Correct time format: 15:26</td>
</tr>
<tr>
<td align="left" valign="top">Valid</td>
<td align="left" nowrap>From '.date_select($blog[t1],'blog['.$n.'][t1]').' To '.date_select($blog[t2],'blog['.$n.'][t2]').'</td>
</tr>
<tr>
<td align="left" nowrap>Link number </td>
<td align="left"><input class="field10" name="blog['.$n.'][link_n]" style="width:650px;" maxlength=255 value="'.$blog[link_n].'"></td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Page URL</td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="blog['.$n.'][rewrite_url]" maxlength=255 value="'.$blog[rewrite_url].'"><br /><span class="text10">This is used if you have HTML Plugin. Only English letters, numbers and these characters: - _ .<br>If you let it blank, the script will generate the URL automatically by the Title field.</span></td>
</tr>
<tr>
<td align="left">Editor\'s Pick </td>
<td align="left" nowrap><select class="select10" name="blog['.$n.'][pick]">
<option value="0"'.$pick[0].'>No</option><option value="1"'.$pick[1].'>1</option><option value="2"'.$pick[2].'>2</option>
<option value="3"'.$pick[3].'>3</option><option value="4"'.$pick[4].'>4</option><option value="5"'.$pick[5].'>5</option>
</select></td>
</tr>';
if ($n) votes_rating_form_admin('b',$n,$blog[rating],$blog[votes]);
echo '<tr>
<td align="left"># of reads</td>
<td align="left"><input class="field10" name="blog['.$n.'][hits]" style="width:100px" maxlength=10 value="'.$blog[hits].'"></td>
</tr>
<tr>
<td align="left" nowrap>Blog is enabled</td>
<td align="left"><input type="checkbox" name="blog['.$n.'][enabled]" value="1" '; if ((!$blog[n]) OR ($blog[status]=='queue') OR ($blog[status]=='enabled')) echo ' checked'; echo '></td>
</tr>
<tr>
<td align="left" nowrap>Owner\'s username</td>
<td align="left"><input class="field10" name="blog['.$n.'][username]" style="width:650px;" maxlength=255 value="'.$user[username].'"></td>
</tr>
<tr>
<td align="left" nowrap>Owner\'s name</td>
<td align="left"><input class="field10" name="blog['.$n.'][name]" style="width:650px;" maxlength=255 value="'.$blog[name].'"></td>
</tr>
<tr>
<td align="left"><a href="mailto:'.$blog[email].'" title="Email the owner">Owner\'s email</a></td>
<td align="left"><input class="field10" name="blog['.$n.'][email]" style="width:650px;" maxlength=255 value="'.$blog[email].'"></td>
</tr>
<tr>
<td align="left">Password</td>
<td align="left" nowrap><input class="field10" name="blog['.$n.'][password]" style="width:100px" maxlength=15 value="'.$blog[password].'"></td>
</tr>';
if (!$n) echo '<tr>
<td align="left" valign="top">Email blog owner </td>
<td align="left" nowrap><input type="checkbox" name="blog['.$n.'][email_owner]" value="1"><span class="text10"><br />It informs the blog owner that this blog has been created.<br />This email is based on template blog_added_by_admin.txt.</span></td>
</tr>';
if (($n) AND (!$queue))
{ echo '<tr>
  <td align="left">Mark it as Updated </td>
  <td align="left" nowrap><input type="checkbox" name="blog['.$n.'][mark_updated]"'.$x.'></td>
  </tr>';
}
echo '</table></td></tr></table><br />';
}

##################################################################################

function blog_edited_process($in) {
global $s;
$old = get_item_variables('b',$in[n]);
if ((!check_admin_categories('b',$old[c])) OR (!check_admin_categories('b',$in[categories]))) problem('You don\'t have the right to edit blogs in selected category(ies)');
$rating = rating_update_get_average('b',$in[n],$in[rates]);
$usit = item_updated_get_usit('b',$in);
if ($in[mark_updated]) $updated = "updated = '$s[cas]',"; else $updated = ''; 
$t1 = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
$t2 = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
$in = replace_array_text($in);
$in[text] = refund_html($in[text]);
$c = categories_edited($in[categories]);
$en_cats = has_some_enabled_categories('b',$c[categories]);
if (!$in[rewrite_url]) $in[rewrite_url] = discover_rewrite_url($in[title],0,'b');
if ($in[enabled]) $status = 'enabled'; else $status = 'disabled';

upload_files('b',$in[n],0,0,'');
if ($in[username]) $user = get_user_variables(0,$in[username]);
list($old_m,$old_d,$old_y) = explode('/',date('m/d/Y',$old[created]));
$created = get_timestamp($in[created][d],$in[created][m],$in[created][y],'start',$in[created_time]);
$in[keywords] = prepare_keywords($in[keywords]);
$map_test = test_google_map($in[map]);
dq("update $s[pr]blogs set $updated title = '$in[title]', description = '$in[description]', text = '$in[text]', keywords = '$in[keywords]', map = '$in[map]$map_test', c = '$c[categories]', c_path = '$c[categories_path]', owner = '$user[n]', name = '$in[name]', email = '$in[email]', password = '$in[password]', created = '$created', hits = '$in[hits]', pick = '$in[pick]', rating = '$rating[average]', votes = '$rating[total_votes]', t1 = '$t1', t2 = '$t2', status = '$status', en_cats = '$en_cats', rewrite_url = '$in[rewrite_url]' where n = '$in[n]' and status != 'queue'",1);
add_update_user_items('b',$in[n],$usit[all_user_items_list],$usit[value_codes],$usit[value_texts]);
recount_items_cats('b',$in[categories],$old[c]);
update_item_index('b',$in[n]);
update_item_image1('b',$in[n]);
}

##################################################################################
##################################################################################
##################################################################################

?>