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
case 'configuration_edited_submit_forms': configuration_edited_submit_forms($_POST);
}
configuration_edit_submit_forms();

#################################################################################
#################################################################################
#################################################################################

function configuration_edited_submit_forms($in) {
global $info;
include('../data/data.php');
ini_set("magic_quotes_runtime",0);
unset ($in[submit],$in[action],$in[check_field]);

foreach ($in as $k=>$v) 
{ if (is_array($v)) $v = implode(',',$v);
  if (!$v) unset($in[$k]);
  else $v = str_replace(chr(92),'&#92;',stripslashes($v));
  $data .= "\$s[$k] = '$v';\n";
}

$data = "<?PHP\n\n$data \n?>";
if (!$sb = fopen("$s[phppath]/data/data_forms.php",'w')) problem ("Cannot write to file data_forms.php in your data directory. Make sure that your data directory exists and has 777 permission and the file data_forms.php inside has permission 666. Cannot continue.");

$zapis = fwrite($sb, $data);
fclose($sb);
if (!$zapis) $info = info_line('Can not write to file "data_forms.php".','Make sure that your data directory exists and has 777 permission and the file "data_forms.php" inside has permission 666. Cannot continue.');
else $info = info_line('Your configuration has been successfully updated');
configuration_edit_submit_forms();
}

#################################################################################

function configuration_edit_submit_forms() {
global $info;
include("../data/data_forms.php");
$s = stripslashes_array($s);
foreach ($s as $k=>$v) $s[$k] = htmlspecialchars($v);
$x = explode(',',$s[l_sort]); foreach ($x as $k => $v) { $l_sort[$k] = $v; }
ih();
echo $info;
?>
<form method="POST" action="configuration_forms.php"><?PHP echo check_field_create('admin') ?>
<input type="hidden" name="action" value="configuration_edited_submit_forms">
<table border="0" width="98%" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" class="common_table_top_cell">Configuration - Submit Forms</td></tr>
<tr><td align="center" colspan=2>These options are valid for forms on public pages only</td></tr>
<tr>
<td align="center" style="font-weight:bold;">
Show:  &nbsp;
<a href="javascript:show_config(0)" style="font-weight:bold;">Whole form</a> &nbsp;
<a href="javascript:show_config(1)" style="font-weight:bold;">Links & AdLinks</a> &nbsp;
<a href="javascript:show_config(2)" style="font-weight:bold;">Users</a> &nbsp;
<a href="javascript:show_config(3)" style="font-weight:bold;">Blogs</a> &nbsp;
<a href="javascript:show_config(4)" style="font-weight:bold;">Articles</a> &nbsp;
<a href="javascript:show_config(5)" style="font-weight:bold;">Comments & Rates & Message board & Other forms</a> &nbsp;
</td>
</tr>
<tr><td align="center">
<tr><td>





<div id="config_1">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td align="center" colspan=2 class="common_table_top_cell">Links</td></tr>
<tr><td align="left" valign="top">Submit form fields</td>
<td align="left" valign="top">

<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Required</td>
</tr>
<tr>
<td align="left" valign="top">Title</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_title" value="1"<?PHP if ($s[l_v_title]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_title" value="1"<?PHP if ($s[l_r_title]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">URL</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_url" value="1"<?PHP if ($s[l_v_url]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_url" value="1"<?PHP if ($s[l_r_url]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Reciprocal URL<br /><span class="text10">You also can require a reciprocal link in some categories only. In this case don't check the "required" field here and set it in the category edit form.<br /></span></td>
<td align="center" valign="top"><input type="checkbox" name="l_v_recip" value="1"<?PHP if ($s[l_v_recip]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_recip" value="1"<?PHP if ($s[l_r_recip]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Description</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_description" value="1"<?PHP if ($s[l_v_description]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_description" value="1"<?PHP if ($s[l_r_description]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Detailed Description</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_detail" value="1"<?PHP if ($s[l_v_detail]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_detail" value="1"<?PHP if ($s[l_r_detail]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">HTML editor for Detailed Description</td>
<td align="center" valign="top"><input type="checkbox" name="l_details_html_editor" value="1"<?PHP if ($s[l_details_html_editor]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Keywords</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_keywords" value="1"<?PHP if ($s[l_v_keywords]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_keywords" value="1"<?PHP if ($s[l_r_keywords]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map </td>
<td align="center" valign="top"><input type="checkbox" name="l_v_map" value="1"<?PHP if ($s[l_v_map]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_map" value="1"<?PHP if ($s[l_r_map]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">RSS URL (it can automatically load news and show them on the link details page)</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_rss_url" value="1"<?PHP if ($s[l_v_rss_url]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_rss_url" value="1"<?PHP if ($s[l_r_rss_url]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Start/end dates when the link is valid</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_start_end" value="1"<?PHP if ($s[l_v_start_end]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Name</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_name" value="1"<?PHP if ($s[l_v_name]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_name" value="1"<?PHP if ($s[l_r_name]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_email" value="1"<?PHP if ($s[l_v_email]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_email" value="1"<?PHP if ($s[l_r_email]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Password</td>
<td align="center" valign="top"><input type="checkbox" name="l_v_password" value="1"<?PHP if ($s[l_v_password]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_r_password" value="1"<?PHP if ($s[l_r_password]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Number of categories </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="l_max_cats_users" value="<?PHP echo $s[l_max_cats_users] ?>"></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Number of fields to upload an image </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="l_max_pictures_users" value="<?PHP echo $s[l_max_pictures_users] ?>"></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<?PHP if (is_gd())
{ echo '<tr>
  <td align="left" valign="top">CAPTCHA image test <a href="#help-captcha">What\'s that?</a><br /></td>
  <td align="center" valign="top"><input type="checkbox" name="l_v_captcha" value="1"'; if ($s[l_v_captcha]) echo ' checked'; echo '></td>
  <td align="center" valign="top">&nbsp;</td>
  </tr>';
}
?>
</table>
</td></tr>
<tr>
<td align="left" valign="top">Allowed sizes in characters <br /><span class="text10">Enter the lowest required number of characters to the first field, the biggest allowed number of characters to the second field<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Title</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_min_title" value="<?PHP echo $s[l_min_title] ?>"> - <input class="field10" maxLength=5 size=5 name="l_max_title" value="<?PHP echo $s[l_max_title] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Description</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_min_description" value="<?PHP echo $s[l_min_description] ?>"> - <input class="field10" maxLength=5 size=5 name="l_max_description" value="<?PHP echo $s[l_max_description] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Detailed Description </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_min_detail" value="<?PHP echo $s[l_min_detail] ?>"> - <input class="field10" maxLength=5 size=5 name="l_max_detail" value="<?PHP echo $s[l_max_detail] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Keywords </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_min_keywords" value="<?PHP echo $s[l_min_keywords] ?>"> - <input class="field10" maxLength=5 size=5 name="l_max_keywords" value="<?PHP echo $s[l_max_keywords] ?>"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of keywords/phrases</td>
<td align="left" valign="top"><span class="text10"><input class="field10" size=5 name="l_allowed_keywords" value="<?PHP echo $s[l_allowed_keywords]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by link owners <br /><span class="text10">This option needs GD library.<br />If this library is not available, let these fields blank and enter values to the fields below.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="l_image_small_w_users" value="<?PHP echo $s[l_image_small_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="l_image_small_h_users" value="<?PHP echo $s[l_image_small_h_users] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="l_image_big_w_users" value="<?PHP echo $s[l_image_big_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="l_image_big_h_users" value="<?PHP echo $s[l_image_big_h_users] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum size of each image<br /><span class="text10">Enter values to these fields only if you don't have GD library available. The script will not resize uploaded images.</span></td>
<td align="left" valign="top" nowrap>
Width <input class="field10" maxLength=5 size=5 name="l_image_max_w_users" value="<?PHP echo $s[l_image_max_w_users] ?>"> px&nbsp;&nbsp;
Height <input class="field10" maxLength=5 size=5 name="l_image_max_h_users" value="<?PHP echo $s[l_image_max_h_users] ?>"> px <br />
<input class="field10" maxLength=10 style="width:100px" name="l_image_max_bytes_users" value="<?PHP echo $s[l_image_max_bytes_users]; ?>"> Bytes</td>
</tr>
<tr>
<td align="left" valign="top">Who can submit links</td>
<td align="left">
<input type="radio" name="l_who" value="0"<?PHP if (!$s[l_who]) echo ' checked'; ?>> Any visitor<br />
<input type="radio" name="l_who" value="1"<?PHP if ($s[l_who]==1) echo ' checked'; ?>> All registered users<br />
<input type="radio" name="l_who" value="2"<?PHP if ($s[l_who]==2) echo ' checked'; ?>> Registered users who have been approved by an administrator to submit links<br /></td>
</tr>
<tr>
<td align="left" valign="top">Check site URL<br /><span class="text10">The script tries to connect  to the entered URL to check if the URL exists</span></td>
<td align="left" valign="top"><input type="checkbox" name="checkurl" value="1"<?PHP if ($s[checkurl]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Check for a reciprocal link<br /><span class="text10">The script tries to connect  to the Recip URL and check if the page contains your recip link</span></td>
<td align="left" valign="top"><input type="checkbox" name="checkrecip" value="1"<?PHP if ($s[checkrecip]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Reciprocal HTML<br /><span class="text10">What we have to search when checking reciprocal link</span></td>
<td align="left" valign="top"><span class="text10"><input class="field10" style="width:650px;" name="reciplink" value="<?PHP echo $s[reciplink]; ?>"><br />Example: yourdomain.com</span></td>
</tr>
<tr>
<td align="left" valign="top">Assign editor's pick value <br /><span class="text10">This assigns the selected pick value only in the time when a link is submitted or updated by the public submit form.<br /></span></td>
<td align="left" valign="top"><select class="select10" name="recip_pick"><option value="0">N/A</option>
<?PHP
for ($x=1;$x<=5;$x++)
{ if ($s[recip_pick]==$x) $selected = ' selected'; else $selected = '';
  echo '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
}
?>
</select> if a reciprocal link has been found</td>
</tr>
<tr>
<td align="left" valign="top">Reject duplicate URL's<br /><span class="text10">If someone tries to submit a URL already listed in the database, the submission will be rejected</span></td>
<td align="left" valign="top"><input type="checkbox" name="duplicate" value="1"<?PHP if ($s[duplicate]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Allow only one submission per domain/subdomain</td>
<td align="left" valign="top"><input type="checkbox" name="ls_dupl_domain" value="1"<?PHP if ($s[ls_dupl_domain]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Accept submissions of URL's with Google pagerank </td>
<td align="left" valign="top"><select class="select10" name="l_pr_google_min">
<?PHP
for ($x=0;$x<=10;$x++)
{ if ($s[l_pr_google_min]==$x) $selected = ' selected'; else $selected = '';
  echo '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
}
?>
</select> or higher</td>
</tr>
<tr>
<td align="left" valign="top">Automatically add http:// if it's missing</td>
<td align="left" valign="top"><input type="checkbox" name="add_http" value="1"<?PHP if ($s[add_http]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Convert link title<br /><span class="text10">This converts all link title characters to lowercase letters and the first character of each word to upper case.<br>It can damage non-English characters. If your site uses another language than English, we recommend to uncheck this field.</span></td>
<td align="left" valign="top"><input type="checkbox" name="l_convert_title" value="1"<?PHP if ($s[l_convert_title]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Convert description<br /><span class="text10">This converts all characters in the link description to lowercase letters except the first letter of the description.<br>It can damage non-English characters. If your site uses another language than English, we recommend to uncheck this field.</span></td>
<td align="left" valign="top"><input type="checkbox" name="l_convert_description" value="1"<?PHP if ($s[l_convert_description]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Submissions must be confirmed by email<br /><span class="text10">The script sends an email with random code to the address provided with the submission. The person who submitted the link must click on the link to confirm the submission.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="conf_sub" value="1"<?PHP if ($s[conf_sub]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Delete unconfirmed links after </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_unconfirmed_delete_after" value="<?PHP echo $s[l_unconfirmed_delete_after]; ?>"> days </td>
</tr>
<tr>
<td align="left" valign="top">Auto-approve all submissions<br /><span class="text10">All submissions will be automatically added to database without reviewing</span></td>
<td align="left" valign="top"><input type="checkbox" name="autoapr" value="1"<?PHP if ($s[autoapr]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Whom to email when a link is submitted or updated</td>
<td align="left" valign="top" nowrap><input type="checkbox" name="l_i_new" value="1"<?PHP if ($s[l_i_new]) echo ' checked'; ?>>Directory&nbsp;owner&nbsp;&nbsp;&nbsp;<input type="checkbox" name="l_i_new_admins" value="1"<?PHP if ($s[l_i_new_admins]) echo ' checked'; ?>>&nbsp;Admin&nbsp;of&nbsp;category</span></td>
</tr>
<tr>
<td align="left" valign="top">Email to link owner immediately when a link has been submitted</td>
<td align="left" valign="top"><input type="checkbox" name="l_i_owner" value="1"<?PHP if ($s[l_i_owner]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email to link owner when a link has been approved by admin</td>
<td align="left" valign="top"><input type="checkbox" name="l_i_approved" value="1"<?PHP if ($s[l_i_approved]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Link owners who are registered users can delete their links </td>
<td align="left" valign="top"><input type="checkbox" name="users_can_delete_l" value="1"<?PHP if ($s[users_can_delete_l]) echo ' checked'; ?>></td>
</tr>






<tr><td align="center" colspan=2 class="common_table_top_cell">Advertising Links<br />
<tr><td align="center" valign="top" colspan=2>Most of the options available for free links are valid also for advertising links. The differences can be configured below.</td></tr>
<tr><td align="left" valign="top">Submit form fields </td>
<td align="left" valign="top">

<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Required</td>
</tr>
<tr>
<td align="left" valign="top">Title</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_title" value="1"<?PHP if ($s[l_adv_v_title]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_title" value="1"<?PHP if ($s[l_adv_r_title]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">URL</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_url" value="1"<?PHP if ($s[l_adv_v_url]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_url" value="1"<?PHP if ($s[l_adv_r_url]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Reciprocal URL<br /><span class="text10">You also can rquire a reciprocal link in some categories only. In this case don't check the "required" field here and set it in the category edit form.<br /></span></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_recip" value="1"<?PHP if ($s[l_adv_v_recip]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_recip" value="1"<?PHP if ($s[l_adv_r_recip]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Description</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_description" value="1"<?PHP if ($s[l_adv_v_description]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_description" value="1"<?PHP if ($s[l_adv_r_description]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Detailed Description</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_detail" value="1"<?PHP if ($s[l_adv_v_detail]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_detail" value="1"<?PHP if ($s[l_adv_r_detail]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">HTML editor for Detailed Description</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_details_html_editor" value="1"<?PHP if ($s[l_adv_details_html_editor]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Keywords</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_keywords" value="1"<?PHP if ($s[l_adv_v_keywords]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_keywords" value="1"<?PHP if ($s[l_adv_r_keywords]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map </td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_map" value="1"<?PHP if ($s[l_adv_v_map]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_map" value="1"<?PHP if ($s[l_adv_r_map]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">RSS URL (it can automatically load news and show them on the link details page)</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_rss_url" value="1"<?PHP if ($s[l_adv_v_rss_url]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_r_rss_url" value="1"<?PHP if ($s[l_adv_r_rss_url]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Start/end dates when the link is valid</td>
<td align="center" valign="top"><input type="checkbox" name="l_adv_v_start_end" value="1"<?PHP if ($s[l_adv_v_start_end]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Number of categories </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="l_adv_max_cats_users" value="<?PHP echo $s[l_adv_max_cats_users] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Number of fields to upload an image </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="l_adv_max_pictures_users" value="<?PHP echo $s[l_adv_max_pictures_users] ?>"></td>
</tr>
</table>
</td></tr>
<tr>
<td align="left" valign="top">Allowed sizes in characters <br /><span class="text10">Enter the lowest required number of characters to the first field, the biggest allowed number of characters to the second field<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Title</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_adv_min_title" value="<?PHP echo $s[l_adv_min_title] ?>"> - <input class="field10" maxLength=5 size=5 name="l_adv_max_title" value="<?PHP echo $s[l_adv_max_title] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Description</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_adv_min_description" value="<?PHP echo $s[l_adv_min_description] ?>"> - <input class="field10" maxLength=5 size=5 name="l_adv_max_description" value="<?PHP echo $s[l_adv_max_description] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Detailed Description </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_adv_min_detail" value="<?PHP echo $s[l_adv_min_detail] ?>"> - <input class="field10" maxLength=5 size=5 name="l_adv_max_detail" value="<?PHP echo $s[l_adv_max_detail] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Keywords </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="l_adv_min_keywords" value="<?PHP echo $s[l_adv_min_keywords] ?>"> - <input class="field10" maxLength=5 size=5 name="l_adv_max_keywords" value="<?PHP echo $s[l_adv_max_keywords] ?>"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of keywords/phrases</td>
<td align="left" valign="top"><input class="field10" size=5 name="l_adv_allowed_keywords" value="<?PHP echo $s[l_adv_allowed_keywords]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Accept submissions of URL's with Google pagerank </td>
<td align="left" valign="top"><select class="select10" name="l_adv_pr_google_min">
<?PHP
for ($x=0;$x<=10;$x++)
{ if ($s[l_adv_pr_google_min]==$x) $selected = ' selected'; else $selected = '';
  echo '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
}
?>
</select> or higher</td>
</tr>






<tr><td align="center" colspan=2 class="common_table_top_cell">AdLinks<br />
<tr><td align="left" valign="top">Submit form fields</td>
<td align="left" valign="top">

<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Max length</td>
</tr>
<tr>
<td align="left" valign="top">Title</td>
<td align="center" valign="top"><input type="checkbox" name="" value="1" checked disabled></td>
<td align="center" valign="top"><input class="field10" size=5 name="adlink_m_title" value="<?PHP echo $s[adlink_m_title] ?>"></td>
</tr>
<?PHP 
for ($x=1;$x<=10;$x++)
{ echo '<tr>
  <td align="left" valign="top">Text #'.$x.'</td>
  <td align="center" valign="top"><input type="checkbox" name="adlink_v_text'.$x.'" value="1"'; if ($s["adlink_v_text$x"]) echo ' checked'; echo '></td>
  <td align="center" valign="top"><input class="field10" size=5 name="adlink_m_text'.$x.'" value="'.$s["adlink_m_text$x"].'"></td>
  </tr>';
}
?>
<tr>
<td align="left" valign="top">HTML code</td>
<td align="center" valign="top"><input type="checkbox" name="adlink_v_html" value="1"<?PHP if ($s[adlink_v_html]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">"Enabled" checkbox</td>
<td align="center" valign="top"><input type="checkbox" name="adlink_v_enabled" value="1"<?PHP if ($s[adlink_v_enabled]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
</table>
</td></tr>
<tr>
<td align="left" valign="top">Email admin when an AdLink has been created or edited </td>
<td align="left" valign="top"><input type="checkbox" name="adlink_i_admin" value="1"<?PHP if ($s[adlink_i_admin]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Automatically approve new and edited AdLinks </td>
<td align="left" valign="top"><input type="checkbox" name="adlink_autoapr" value="1"<?PHP if ($s[adlink_autoapr]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email owners when an AdLink has been approved by admin </td>
<td align="left" valign="top"><input type="checkbox" name="adlink_i_approved" value="1"<?PHP if ($s[adlink_i_approved]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Number of categories </td>
<td align="left" valign="top"><input class="field10" maxLength=3 size=2 name="adlink_max_cats_users" value="<?PHP echo $s[adlink_max_cats_users] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Owners can delete their AdLinks </td>
<td align="left" valign="top"><input type="checkbox" name="users_can_delete_adlinks" value="1"<?PHP if ($s[users_can_delete_adlinks]) echo ' checked'; ?>></td>
</tr>
</table>
</div>




<div id="config_2">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td align="center" colspan=2 class="common_table_top_cell">Users<br />
<tr><td align="left" valign="top">Submit form fields</td>
<td align="left" valign="top">

<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Required</td>
</tr>
<tr>
<td align="left" valign="top">Name</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_name" value="1"<?PHP if ($s[u_v_name]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_name" value="1"<?PHP if ($s[u_r_name]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Company</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_company" value="1"<?PHP if ($s[u_v_company]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_company" value="1"<?PHP if ($s[u_r_company]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Address line 1</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_address1" value="1"<?PHP if ($s[u_v_address1]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_address1" value="1"<?PHP if ($s[u_r_address1]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Address line 2</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_address2" value="1"<?PHP if ($s[u_v_address2]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_address2" value="1"<?PHP if ($s[u_r_address2]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Address line 3</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_address3" value="1"<?PHP if ($s[u_v_address3]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_address3" value="1"<?PHP if ($s[u_r_address3]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Country</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_country" value="1"<?PHP if ($s[u_v_country]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_country" value="1"<?PHP if ($s[u_r_country]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Phone 1</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_phone1" value="1"<?PHP if ($s[u_v_phone1]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_phone1" value="1"<?PHP if ($s[u_r_phone1]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Phone 2</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_phone2" value="1"<?PHP if ($s[u_v_phone2]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_phone2" value="1"<?PHP if ($s[u_r_phone2]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Styles</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_styles" value="1"<?PHP if ($s[u_v_styles]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">URL & Site title</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_site_info" value="1"<?PHP if ($s[u_v_site_info]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_site_info" value="1"<?PHP if ($s[u_r_site_info]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Users Article</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_detail" value="1"<?PHP if ($s[u_v_detail]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="u_r_detail" value="1"<?PHP if ($s[u_r_detail]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">HTML editor for Users Article</td>
<td align="center" valign="top"><input type="checkbox" name="u_details_html_editor" value="1"<?PHP if ($s[u_details_html_editor]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Newsletters</td>
<td align="center" valign="top"><input type="checkbox" name="u_v_newsletters" value="1"<?PHP if ($s[u_v_newsletters]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Number of fields to upload an image </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="u_max_pictures_users" value="<?PHP echo $s[u_max_pictures_users] ?>"></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<?PHP if (is_gd())
{ echo '<tr>
  <td align="left" valign="top">CAPTCHA image test <a href="#help-captcha">What\'s that?</a><br /></td>
  <td align="center" valign="top"><input type="checkbox" name="u_v_captcha" value="1"'; if ($s[u_v_captcha]) echo ' checked'; echo '></td>
  <td align="center" valign="top">&nbsp;</td>
  </tr>';
}
?>
</table>

</td></tr>
<tr>
<td align="left" valign="top">Resize images uploaded by users <br /><span class="text10">This option needs GD library.<br />If this library is not available, let these fields blank and enter values to the fields below.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="u_image_small_w_users" value="<?PHP echo $s[u_image_small_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="u_image_small_h_users" value="<?PHP echo $s[u_image_small_h_users] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="u_image_big_w_users" value="<?PHP echo $s[u_image_big_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="u_image_big_h_users" value="<?PHP echo $s[u_image_big_h_users] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum size of an image<br /><span class="text10">Enter values to these fields only if you don't have GD library available. The script will not resize uploaded images.</span></td>
<td align="left" valign="top" nowrap>
Width <input class="field10" maxLength=5 size=5 name="u_image_max_w_users" value="<?PHP echo $s[u_image_max_w_users] ?>"> px&nbsp;&nbsp;
Height <input class="field10" maxLength=5 size=5 name="u_image_max_h_users" value="<?PHP echo $s[u_image_max_h_users] ?>"> px <br />
<input class="field10" maxLength=10 style="width:100px" name="u_image_max_bytes_users" value="<?PHP echo $s[u_image_max_bytes_users]; ?>"> Bytes</td>
</td>
</tr>

<tr>
<td align="left" valign="top">Email admin when an user joined or an account has been edited</td>
<td align="left" valign="top"><input type="checkbox" name="i_admin_user_joined" value="1"<?PHP if ($s[i_admin_user_joined]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email owner of an account whose data has been edited</td>
<td align="left" valign="top"><input type="checkbox" name="i_edited_user" value="1"<?PHP if ($s[i_edited_user]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Each new user must be confirmed by a link sent by email<br /><span class="text10">The script sends an email with a random code to each new user. The person who wants to join must click on the link to confirm the membership.<br /></span></td>
<td align="left" valign="top"><input type="checkbox" name="user_conf_sub" value="1"<?PHP if ($s[user_conf_sub]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Delete unconfirmed users after </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="user_unconfirmed_delete_after" value="<?PHP echo $s[user_unconfirmed_delete_after]; ?>"> days</td>
</tr>
</table>
</div>




<div id="config_3">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td align="center" colspan=2 class="common_table_top_cell">Blogs</td></tr>
<tr><td align="left" valign="top">Submit form fields</td>
<td align="left" valign="top">

<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Required</td>
</tr>
<tr>
<td align="left" valign="top">Title</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_title" value="1"<?PHP if ($s[b_v_title]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_title" value="1"<?PHP if ($s[b_r_title]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Subtitle</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_description" value="1"<?PHP if ($s[b_v_description]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_description" value="1"<?PHP if ($s[b_r_description]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Blog text</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_text" value="1"<?PHP if ($s[b_v_text]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_text" value="1"<?PHP if ($s[b_r_text]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">HTML editor for Blog text</td>
<td align="center" valign="top"><input type="checkbox" name="b_text_html_editor" value="1"<?PHP if ($s[b_text_html_editor]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Keywords</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_keywords" value="1"<?PHP if ($s[b_v_keywords]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_keywords" value="1"<?PHP if ($s[b_r_keywords]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map </td>
<td align="center" valign="top"><input type="checkbox" name="b_v_map" value="1"<?PHP if ($s[b_v_map]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_map" value="1"<?PHP if ($s[b_r_map]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Start/end dates when the blog is valid</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_start_end" value="1"<?PHP if ($s[b_v_start_end]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Name</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_name" value="1"<?PHP if ($s[b_v_name]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_name" value="1"<?PHP if ($s[b_r_name]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_email" value="1"<?PHP if ($s[b_v_email]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_email" value="1"<?PHP if ($s[b_r_email]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Password</td>
<td align="center" valign="top"><input type="checkbox" name="b_v_password" value="1"<?PHP if ($s[b_v_password]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="b_r_password" value="1"<?PHP if ($s[b_r_password]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Number of categories </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="b_max_cats_users" value="<?PHP echo $s[b_max_cats_users] ?>"></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Number of fields to upload an image </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="b_max_pictures_users" value="<?PHP echo $s[b_max_pictures_users] ?>"></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<?PHP if (is_gd())
{ echo '<tr>
  <td align="left" valign="top">CAPTCHA image test <a href="#help-captcha">What\'s that?</a><br /></td>
  <td align="center" valign="top"><input type="checkbox" name="b_v_captcha" value="1"'; if ($s[b_v_captcha]) echo ' checked'; echo '></td>
  <td align="center" valign="top">&nbsp;</td>
  </tr>';
}
?>
</table>

</td></tr>
<tr>
<td align="left" valign="top">Who can post blogs</td>
<td align="left">
<input type="radio" name="b_who" value="0"<?PHP if (!$s[b_who]) echo ' checked'; ?>> Any visitor<br />
<input type="radio" name="b_who" value="1"<?PHP if ($s[b_who]==1) echo ' checked'; ?>> All registered users<br />
<input type="radio" name="b_who" value="2"<?PHP if ($s[b_who]==2) echo ' checked'; ?>> Registered users who have been approved by an administrator to post blogs<br />
</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by blog owners <br /><span class="text10">This option needs GD library.<br />If this library is not available, let these fields blank and enter values to the fields below.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="b_image_small_w_users" value="<?PHP echo $s[b_image_small_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="b_image_small_h_users" value="<?PHP echo $s[b_image_small_h_users] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="b_image_big_w_users" value="<?PHP echo $s[b_image_big_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="b_image_big_h_users" value="<?PHP echo $s[b_image_big_h_users] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum size of each image<br /><span class="text10">Enter values to these fields only if you don't have GD library available. The script will not resize uploaded images.</span></td>
<td align="left" valign="top" nowrap>
Width <input class="field10" maxLength=5 size=5 name="b_image_max_w_users" value="<?PHP echo $s[b_image_max_w_users] ?>"> px&nbsp;&nbsp;
Height <input class="field10" maxLength=5 size=5 name="b_image_max_h_users" value="<?PHP echo $s[b_image_max_h_users] ?>"> px <br />
<input class="field10" maxLength=10 style="width:100px" name="b_image_max_bytes_users" value="<?PHP echo $s[b_image_max_bytes_users]; ?>"> Bytes</td>
</td>
</tr>

<tr>
<td align="left" valign="top">Auto-approve blogs<br /><span class="text10">All submissions will be visible immediately without reviewing</span></td>
<td align="left" valign="top"><input type="checkbox" name="b_autoapr" value="1"<?PHP if ($s[b_autoapr]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Allowed sizes in characters <br /><span class="text10">Enter the lowest required number of characters to the first field, the biggest allowed number of characters to the second field<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Title</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="b_min_title" value="<?PHP echo $s[b_min_title] ?>"> - <input class="field10" maxLength=5 size=5 name="b_max_title" value="<?PHP echo $s[b_max_title] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Subtitle </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="b_min_description" value="<?PHP echo $s[b_min_description] ?>"> - <input class="field10" maxLength=5 size=5 name="b_max_description" value="<?PHP echo $s[b_max_description] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Text </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="b_min_text" value="<?PHP echo $s[b_min_text] ?>"> - <input class="field10" maxLength=5 size=5 name="b_max_text" value="<?PHP echo $s[b_max_text] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Keywords </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="b_min_keywords" value="<?PHP echo $s[b_min_keywords] ?>"> - <input class="field10" maxLength=5 size=5 name="b_max_keywords" value="<?PHP echo $s[b_max_keywords] ?>"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of keywords/phrases</td>
<td align="left" valign="top"><span class="text10"><input class="field10" size=5 name="b_allowed_keywords" value="<?PHP echo $s[b_allowed_keywords]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Inform admin by email when a blog has been submitted</td>
<td align="left" valign="top"><input type="checkbox" name="b_i_new" value="1"<?PHP if ($s[b_i_new]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email to the owner when a blog has been submitted</td>
<td align="left" valign="top"><input type="checkbox" name="b_i_owner" value="1"<?PHP if ($s[b_i_owner]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email to blog owner when a blog has been approved by admin</td>
<td align="left" valign="top"><input type="checkbox" name="b_i_approved" value="1"<?PHP if ($s[b_i_approved]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Blog owners who are registered users can delete their blogs </td>
<td align="left" valign="top"><input type="checkbox" name="users_can_delete_b" value="1"<?PHP if ($s[users_can_delete_b]) echo ' checked'; ?>></td>
</tr>
</table>
</div>




<div id="config_4">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td align="center" colspan=2 class="common_table_top_cell">Articles</td></tr>
<tr><td align="left" valign="top">Submit form fields</td>
<td align="left" valign="top">

<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Required</td>
</tr>
<tr>
<td align="left" valign="top">Title</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_title" value="1"<?PHP if ($s[a_v_title]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_title" value="1"<?PHP if ($s[a_r_title]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Subtitle</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_description" value="1"<?PHP if ($s[a_v_description]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_description" value="1"<?PHP if ($s[a_r_description]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Article text</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_text" value="1"<?PHP if ($s[a_v_text]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_text" value="1"<?PHP if ($s[a_r_text]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">HTML editor for Article text</td>
<td align="center" valign="top"><input type="checkbox" name="a_text_html_editor" value="1"<?PHP if ($s[a_text_html_editor]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Keywords</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_keywords" value="1"<?PHP if ($s[a_v_keywords]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_keywords" value="1"<?PHP if ($s[a_r_keywords]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Mail (street) address to show in a map </td>
<td align="center" valign="top"><input type="checkbox" name="a_v_map" value="1"<?PHP if ($s[a_v_map]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_map" value="1"<?PHP if ($s[a_r_map]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Start/end dates when the article is valid</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_start_end" value="1"<?PHP if ($s[a_v_start_end]) echo ' checked'; ?>></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Name</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_name" value="1"<?PHP if ($s[a_v_name]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_name" value="1"<?PHP if ($s[a_r_name]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_email" value="1"<?PHP if ($s[a_v_email]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_email" value="1"<?PHP if ($s[a_r_email]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Password</td>
<td align="center" valign="top"><input type="checkbox" name="a_v_password" value="1"<?PHP if ($s[a_v_password]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="a_r_password" value="1"<?PHP if ($s[a_r_password]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Number of categories </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="a_max_cats_users" value="<?PHP echo $s[a_max_cats_users] ?>"></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<tr>
<td align="left" valign="top">Number of fields to upload an image </td>
<td align="center" valign="top"><input class="field10" maxLength=3 size=2 name="a_max_pictures_users" value="<?PHP echo $s[a_max_pictures_users] ?>"></td>
<td align="center" valign="top">&nbsp;</td>
</tr>
<?PHP if (is_gd())
{ echo '<tr>
  <td align="left" valign="top">CAPTCHA image test <a href="#help-captcha">What\'s that?</a><br /></td>
  <td align="center" valign="top"><input type="checkbox" name="a_v_captcha" value="1"'; if ($s[a_v_captcha]) echo ' checked'; echo '></td>
  <td align="center" valign="top">&nbsp;</td>
  </tr>';
}
?>
</table>

</td></tr>
<tr>
<td align="left" valign="top">Who can post articles</td>
<td align="left">
<input type="radio" name="a_who" value="0"<?PHP if (!$s[a_who]) echo ' checked'; ?>> Any visitor<br />
<input type="radio" name="a_who" value="1"<?PHP if ($s[a_who]==1) echo ' checked'; ?>> All registered users<br />
<input type="radio" name="a_who" value="2"<?PHP if ($s[a_who]==2) echo ' checked'; ?>> Registered users who have been approved by an administrator to post articles<br />
</td>
</tr>
<tr>
<td align="left" valign="top">Resize images uploaded by article owners <br /><span class="text10">This option needs GD library.<br />If this library is not available, let these fields blank and enter values to the fields below.<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Thumbnails&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="a_image_small_w_users" value="<?PHP echo $s[a_image_small_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="a_image_small_h_users" value="<?PHP echo $s[a_image_small_h_users] ?>"> px</td>
</tr>
<tr>
<td align="left" valign="top">Full size images&nbsp;&nbsp;</td>
<td align="left" valign="top">Width: <input class="field10" maxLength=3 size=5 name="a_image_big_w_users" value="<?PHP echo $s[a_image_big_w_users] ?>"> px&nbsp;&nbsp;Height: <input class="field10" maxLength=3 size=5 name="a_image_big_h_users" value="<?PHP echo $s[a_image_big_h_users] ?>"> px</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum size of each image<br /><span class="text10">Enter values to these fields only if you don't have GD library available. The script will not resize uploaded images.</span></td>
<td align="left" valign="top" nowrap>
Width <input class="field10" maxLength=5 size=5 name="a_image_max_w_users" value="<?PHP echo $s[a_image_max_w_users] ?>"> px&nbsp;&nbsp;
Height <input class="field10" maxLength=5 size=5 name="a_image_max_h_users" value="<?PHP echo $s[a_image_max_h_users] ?>"> px <br />
<input class="field10" maxLength=10 style="width:100px" name="a_image_max_bytes_users" value="<?PHP echo $s[a_image_max_bytes_users]; ?>"> Bytes</td>
</td>
</tr>

<tr>
<td align="left" valign="top">Auto-approve articles<br /><span class="text10">All submissions will be visible immediately without reviewing</span></td>
<td align="left" valign="top"><input type="checkbox" name="a_autoapr" value="1"<?PHP if ($s[a_autoapr]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Allowed sizes in characters <br /><span class="text10">Enter the lowest required number of characters to the first field, the biggest allowed number of characters to the second field<br /></span></td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">Title</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="a_min_title" value="<?PHP echo $s[a_min_title] ?>"> - <input class="field10" maxLength=5 size=5 name="a_max_title" value="<?PHP echo $s[a_max_title] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Subtitle </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="a_min_description" value="<?PHP echo $s[a_min_description] ?>"> - <input class="field10" maxLength=5 size=5 name="a_max_description" value="<?PHP echo $s[a_max_description] ?>"> 255 is maximum</td>
</tr>
<tr>
<td align="left" valign="top">Text </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="a_min_text" value="<?PHP echo $s[a_min_text] ?>"> - <input class="field10" maxLength=5 size=5 name="a_max_text" value="<?PHP echo $s[a_max_text] ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Keywords </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="a_min_keywords" value="<?PHP echo $s[a_min_keywords] ?>"> - <input class="field10" maxLength=5 size=5 name="a_max_keywords" value="<?PHP echo $s[a_max_keywords] ?>"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="left" valign="top">Maximum number of keywords/phrases</td>
<td align="left" valign="top"><span class="text10"><input class="field10" size=5 name="a_allowed_keywords" value="<?PHP echo $s[a_allowed_keywords]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top">Inform admin by email when an article has been submitted</td>
<td align="left" valign="top"><input type="checkbox" name="a_i_new" value="1"<?PHP if ($s[a_i_new]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email to the owner when an article has been submitted</td>
<td align="left" valign="top"><input type="checkbox" name="a_i_owner" value="1"<?PHP if ($s[a_i_owner]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email to article owner when an article has been approved by admin</td>
<td align="left" valign="top"><input type="checkbox" name="a_i_approved" value="1"<?PHP if ($s[a_i_approved]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Article owners who are registered users can delete their articles </td>
<td align="left" valign="top"><input type="checkbox" name="users_can_delete_a" value="1"<?PHP if ($s[users_can_delete_a]) echo ' checked'; ?>></td>
</tr>
</table>
</div>




<div id="config_5">
<table border=0 width="100%" cellspacing=0 cellpadding=2 class="inside_table" style="table-layout:fixed;">
<tr><td align="center" colspan=2 class="common_table_top_cell">Comments</td></tr>
<tr><td align="left" valign="top">Submit form fields</td>
<td align="left" valign="top">

<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Required</td>
</tr>
<tr>
<td align="left" valign="top">Name</td>
<td align="center" valign="top"><input type="checkbox" name="comm_v_name" value="1"<?PHP if ($s[comm_v_name]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="comm_r_name" value="1"<?PHP if ($s[comm_r_name]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email</td>
<td align="center" valign="top"><input type="checkbox" name="comm_v_email" value="1"<?PHP if ($s[comm_v_email]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="comm_r_email" value="1"<?PHP if ($s[comm_r_email]) echo ' checked'; ?>></td>
</tr>
<?PHP if (is_gd())
{ echo '<tr>
  <td align="left" valign="top">CAPTCHA image test <a href="#help-captcha">What\'s that?</a><br /></td>
  <td align="center" valign="top"><input type="checkbox" name="comm_v_captcha" value="1"'; if ($s[comm_v_captcha]) echo ' checked'; echo '></td>
  <td align="center" valign="top">&nbsp;</td>
  </tr>';
}
?>
</table>
</td></tr>
<tr>
<td align="left" valign="top">Maximum size of User Comment *</td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="m_comment" value="<?PHP echo $s[m_comment]; ?>"> characters</td>
</tr>
<tr>
<td align="left" valign="top">Each visitor can submit one comment only to each link or article</td>
<td align="left" valign="top"><input type="checkbox" name="com_duplicate" value="1"<?PHP if ($s[com_duplicate]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Auto-approve all comments<br /><span class="text10">All comments will be automatically added to the database without reviewing</span></td>
<td align="left" valign="top"><input type="checkbox" name="com_autoapr" value="1"<?PHP if ($s[com_autoapr]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can add comments</td>
<td align="left" valign="top"><input type="checkbox" name="register_com" value="1"<?PHP if ($s[register_com]) echo ' checked'; ?>></td>
</tr>









<tr><td align="center" colspan=2 class="common_table_top_cell">Rates</td></tr>
<tr>
<td align="left" valign="top">Each visitor can rate each link or article only once</td>
<td align="left" valign="top"><input type="checkbox" name="rate_duplicate" value="1"<?PHP if ($s[rate_duplicate]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Only registered users can rate</td>
<td align="left" valign="top"><input type="checkbox" name="rate_reg_only" value="1"<?PHP if ($s[rate_reg_only]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">When we count an average rating, exclude </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="rate_exclude" value="<?PHP echo $s[rate_exclude] ?>">% of the best and worst rates</td>
</tr>








<tr><td align="center" colspan=2 class="common_table_top_cell">Message Board</td></tr>
<tr><td align="left" valign="top">Submit form fields</td>
<td align="left" valign="top">
<table border=0 cellspacing=0 cellpadding=2>
<tr>
<td align="left" valign="top">&nbsp;</td>
<td align="center" valign="top">Available</td>
<td align="center" valign="top">Required</td>
</tr>
<tr>
<td align="left" valign="top">Name</td>
<td align="center" valign="top"><input type="checkbox" name="board_v_name" value="1"<?PHP if ($s[board_v_name]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="board_r_name" value="1"<?PHP if ($s[board_r_name]) echo ' checked'; ?>></td>
</tr>
<tr>
<td align="left" valign="top">Email</td>
<td align="center" valign="top"><input type="checkbox" name="board_v_email" value="1"<?PHP if ($s[board_v_email]) echo ' checked'; ?>></td>
<td align="center" valign="top"><input type="checkbox" name="board_r_email" value="1"<?PHP if ($s[board_r_email]) echo ' checked'; ?>></td>
</tr>
<?PHP if (is_gd())
{ echo '<tr>
  <td align="left" valign="top">CAPTCHA image test <a href="#help-captcha">What\'s that?</a><br /></td>
  <td align="center" valign="top"><input type="checkbox" name="board_v_captcha" value="1"'; if ($s[board_v_captcha]) echo ' checked'; echo '></td>
  <td align="center" valign="top">&nbsp;</td>
  </tr>';
}
?>
</table>
</td></tr>
<tr>
<td align="left" valign="top">Maximum size of a Message </td>
<td align="left" valign="top"><input class="field10" maxLength=5 size=5 name="board_max" value="<?PHP echo $s[board_max]; ?>"> characters</td>
</tr>
<tr><td align="left" valign="top">Only registered users can post messages</td>
<td align="left" valign="top">
<input type="checkbox" name="board_reg_only" value="1"<?PHP if ($s[board_reg_only]) echo ' checked'; ?>>
</td></tr>






<?PHP if (is_gd())
{ echo '<tr><td align="center" colspan=2 class="common_table_top_cell">CAPTCHA test in other forms</td></tr>
  <tr>
  <td align="left" valign="top">Message to us</td>
  <td align="left" valign="top"><input type="checkbox" name="message_to_us_captcha" value="1"'; if ($s[message_to_us_captcha]) echo ' checked'; echo '></td>
  </tr>
  <tr>
  <td align="left" valign="top">Message to link/article owner</td>
  <td align="left" valign="top"><input type="checkbox" name="message_owner_captcha" value="1"'; if ($s[message_owner_captcha]) echo ' checked'; echo '></td>
  </tr>
  <tr>
  <td align="left" valign="top">Tell a friend</td>
  <td align="left" valign="top"><input type="checkbox" name="tell_friend_captcha" value="1"'; if ($s[tell_friend_captcha]) echo ' checked'; echo '></td>
  </tr>
  <tr>
  <td align="left" valign="top">Error report</td>
  <td align="left" valign="top"><input type="checkbox" name="report_captcha" value="1"'; if ($s[report_captcha]) echo ' checked'; echo '></td>
  </tr>
  <tr>
  <td align="left" valign="top">User login </td>
  <td align="left" valign="top"><input type="checkbox" name="user_login_captcha" value="1"'; if ($s[user_login_captcha]) echo ' checked'; echo '></td>
  </tr>';
}
?>
</table>
</div>



</td></tr>

<tr><td align="center" colspan=2><input type="submit" name="submit" value="Save" class="button10"></td></tr>
</form></table>

<?PHP if (is_gd()) echo '<br /><a name="help-captcha"></a><b>CAPTCHA Image Test - What\'s That?</b><br />It displays an image with random characters, the person who fills in the form has to enter these characters to a form field. It is used to check if there is a live person, not a robot/computer.<br />';
echo '</center></div>';
exit;
}

#################################################################################
#################################################################################
#################################################################################

function is_gd() {
if (function_exists('imageellipse')) return 1;
return 0;
}

?>





