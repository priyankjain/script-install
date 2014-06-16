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
check_admin('templates');

switch ($_POST[action]) {
case 'template_edit'			: template_edit($_POST);
case 'template_edited'			: template_edited($_POST);
}
templates_home($_GET[selected_style]);

#################################################################################
#################################################################################
#################################################################################

function templates_home($selected_style) {
global $s;
if (!$selected_style) $selected_style = '_common';
if ($selected_style=='_common') $common_info = '<br />These templates are used for those styles which don\'t have available their own templates.';

$styles = get_styles_list(0);
$styles_list .= '<option value="_common">_common</option>';
foreach ($styles as $stlk=>$st) $styles_list .= '<option value="'.$st.'">'.str_replace('_',' ',$st).'</option>';

$dr = opendir("$s[phppath]/styles/$selected_style/templates");
rewinddir($dr);
while ($q = readdir($dr))
{ if (($q != '.') AND ($q != '..') AND (substr($q,0,1)!='.') AND (is_file("$s[phppath]/styles/$selected_style/templates/$q")))
  if (strstr($q,'.txt')) $pole_txt[] = $q;
  else $pole_html[] = $q;
}
closedir ($dr);
sort($pole_txt); sort($pole_html);

$dr = opendir("$s[phppath]/styles/$selected_style/email_templates");
rewinddir($dr);
while ($q = readdir($dr))
{ if (($q != '.') AND ($q != '..') AND (is_file("$s[phppath]/styles/$selected_style/email_templates/$q")))
  $pole_email[] = $q;
}
closedir($dr);
if (!$list_email) $list_email = '<center>No email templates available for the selected style,<br />this styles uses templates defined in folder "_common".<br />If you want to have special email templates for this style, copy directory<br />"email_templates" from directory "styles/_common" to directory "styles/'.$selected_style.'". ';

foreach ($pole_txt as $k => $v) $txt_list .= "<tr><td align=\"left\" nowrap><input type=\"radio\" name=\"template\" value=\"templates/$v\">$v</td></tr>\n";
foreach ($pole_html as $k => $v) $html_list .= "<tr><td align=\"left\" nowrap><input type=\"radio\" name=\"template\" value=\"templates/$v\">$v</td></tr>\n";
foreach ($pole_email as $k => $v) $email_list .= "<tr><td align=\"left\" nowrap><input type=\"radio\" name=\"template\" value=\"email_templates/$v\">$v</td></tr>\n";
if (!$html_list) $html_list = 'No HTML templates available for the<br />selected style. This style uses<br />templates defined in folder "_common".<br />If you want to have different<br />templates for this style,<br />copy them from directory <br />"styles/_common/templates" to<br />directory "styles/'.$selected_style.'/templates"<br />(create this directory if needed). ';
if (!$txt_list) $txt_list = 'No text templates available for the<br />selected style. This style uses<br />templates defined in folder "_common".<br />If you want to have different<br />templates for this style,<br />copy them from directory <br />"styles/_common/templates" to<br />directory "styles/'.$selected_style.'/templates"<br />(create this directory if needed). ';
if (!$email_list) $email_list = 'No email templates available for the<br />selected style. This style uses<br />templates defined in folder "_common".<br />If you want to have different<br />templates for this style,<br />copy them from directory <br />"styles/_common/templates" to<br />directory "styles/'.$selected_style.'/templates"<br />(create this directory if needed). ';

ih();
echo $s[info];

echo '<form action="templates.php" method="get">
<input type="hidden" name="action" value="templates_home">
<table border="0" cellspacing="0" cellpadding="2">
<tr><td align="center" colspan="3">

<table border="0" cellspacing="0" cellpadding="2" class="common_table" width="100%">
<tr><td class="common_table_top_cell">Edit Templates</td></tr>
<tr><td align="center" valign="top">Selected style: <b>'.$selected_style.'</b>, select another: <select class="select10" name="selected_style">'.$styles_list.'</select> <input type="submit" name="A1" value="Submit" class="button10">'.$common_info.'</td></tr>
</table>
</td></tr></table>
</form>


<form method="POST" action="templates.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="template_edit">
<input type="hidden" name="selected_style" value="'.$selected_style.'">

<table border="0" cellspacing="0" cellpadding="2">
<tr>
<td align="left" valign="top" nowrap height="100%">

<table border="0" cellspacing="0" cellpadding="0" height="100%" class="common_table" width="300">
<tr><td class="common_table_top_cell">HTML format</td></tr>
<tr><td align="center" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
'.$html_list.'
</table></td></tr></table>

</td>
<td align="left" valign="top" nowrap height="100%">

<table border="0" cellspacing="0" cellpadding="0" height="100%" class="common_table" width="300">
<tr><td class="common_table_top_cell">TXT format</td></tr>
<tr><td align="center" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
'.$txt_list.'
</table></td></tr></table>

</td><td align="left" valign="top" nowrap height="100%">

<table border="0" cellspacing="0" cellpadding="0" height="100%" class="common_table" width="300">
<tr><td class="common_table_top_cell">Email templates</td></tr>
<tr><td align="center" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
'.$email_list.'
</table></td></tr></table>

</td></tr>';
if (($pole_html) OR ($pole_txt)) echo '<tr><td align="center" colspan="3" nowrap><input type="submit" name="A1" value="Submit" class="button10"><br /></td></tr>';
echo '</table><br />';
ift();
}

########################################################################

function template_edit($in) {
global $s;
if (!$in[template]) templates_home();
if (!$in[selected_style]) $in[selected_style] = '_common';
$filename = "$s[phppath]/styles/$in[selected_style]/$in[template]";
$fd = fopen($filename,'r'); $ct = fread ($fd, filesize ($filename)); fclose ($fd);
$ct = htmlspecialchars(stripslashes($ct));
$template_name = str_replace('templates/','',str_replace('ads/','',$in[template]));
ih();
echo $s[info];
echo '<table border="0" cellspacing="0" cellpadding="2" class="common_table">
<tr><td class="common_table_top_cell">Template '.$template_name.', style '.$in[selected_style].'</td></tr>
<form method="POST" action="templates.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="template_edited">
<input type="hidden" name="selected_style" value="'.$in[selected_style].'">
<input type="hidden" name="template" value="'.$in[template].'">
<tr><td align="left" nowrap><textarea name="html" style="width:700px;height:300px;" class="field10">'.$ct.'</textarea></td></tr>
<tr><td align="center" nowrap>
<input type="submit" name="A1" value="Save" class="button10"><br />
</td></tr></form></table><br />
<a href="templates.php?action=templates_home&selected_style='. $in[selected_style].'">Back</a><br /><br />';
ift();
}

########################################################################

function template_edited($in) {
global $s;
if (!$in[template]) templates_home();
if (!$in[selected_style]) $in[selected_style] = '_common';
$filename = "$s[phppath]/styles/$in[selected_style]/$in[template]";
if (!$file = fopen($filename,'w')) problem("Cannot write to file '$filename'.");
$zapis = fwrite ($file,stripslashes($in[html]));
fclose($file);
if (!$zapis)
$s[info] = info_line('Can\'t write to file "'.$filename.'".<br />Make sure that your templates directory exists and has 777 permission and the file "'.$in[template].'" inside has permission 666. Can\'t continue.');
else
{ $template_name = str_replace('templates/','',str_replace('ads/','',$in[template]));
  $s[info] = info_line('Template updated');
}
template_edit($in);
}

#################################################################################
#################################################################################
#################################################################################

?>