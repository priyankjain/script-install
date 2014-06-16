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
$s[selected_menu] = 5;
get_messages('contact.php');
include_once("$s[phppath]/data/data_forms.php");

if (!$_POST) $_POST = $_GET;//new ajax
$_POST = replace_array_text($_POST);
$x = form_control($_POST);
$in = $x[1];
if ($x[0])
{ if ($_POST[suggest_form_category]) echo stripslashes(suggest_category_box($in[n],'<br>'.info_line($m[errorsfound],implode('<br />',$x[0]))));
  elseif ($_POST[action]=='claim_listing') echo stripslashes(claim_listing_box('l',$in[n],'<br>'.info_line($m[errorsfound],implode('<br />',$x[0]))));
  else echo stripslashes(contact_box($in[what],$in[n],'<br>'.info_line($m[errorsfound],implode('<br />',$x[0]))));
  exit;
}

$from = $in[email]; if (!$in[to]) $in[to] = $s[mail];
if ($s[subject]) $subject = $s[subject];
else $subject = $m[subject].' '.$s[site_name];

//echo "($from,$from,$in[to],0,$subject,$in[text],1)";
my_send_mail($from,$from,$in[to],0,$subject,$in[text],1);

if ((!$_POST[hide_cancel]) AND (!$_POST[suggest_form_category]) AND ($_POST[action]!='claim_listing'))
{ $close_it = '<br><a href="#page_top" onclick="show_hide_div_id(0,\'contact_box'.$_POST[what].$_POST[n].'\')">'.$m[close_this_window].'</a>';
  echo '<div class="common_div_main"><br>'.info_line($m[message_sent],$close_it).'</div>';
}
else echo '<br>'.info_line($m[message_sent]);
exit;

###############################################################################
###############################################################################
###############################################################################

function form_control($in) {
global $s,$m;
$in = replace_array_text($in);
//foreach ($x as $k => $v) echo "$k - $v<br>\n";
if ($in[suggest_form_category])
{ if (!$s[suggest_category]) exit;
  $in[suggest_form_subcategory] = trim($in[suggest_form_subcategory]);
  if (!$in[suggest_form_subcategory]) $chyba[] = "$m[mis_field] $m[category_name]";
  $black = try_blacklist($in[suggest_form_subcategory],"word"); if ($black) $chyba[] = $black;

  $in[suggest_form_name] = trim($in[suggest_form_name]);
  if (!$in[suggest_form_name]) $chyba[] = "$m[mis_field] $m[name]";

  $in[suggest_form_email] = trim($in[suggest_form_email]);
  if (!$in[suggest_form_email]) $chyba[] = "$m[mis_field] $m[email]";
  elseif (!check_email($in[suggest_form_email])) $chyba[] = $m[w_email];
}
elseif ($_POST[action]=='claim_listing')
{ $in[message] = trim($in[message]);
  if (!$in[message]) $chyba[] = $m[m_text];
  $black = try_blacklist($in[message],"word"); if ($black) $chyba[] = $black;
}
else
{ $in[message] = trim($in[message]);
  if (!$in[message]) $chyba[] = $m[m_text];
  $black = try_blacklist($in[message],"word"); if ($black) $chyba[] = $black;
  $in[name] = trim($in[name]);
  $in[email] = trim($in[email]);
}

if ($in[suggest_form_category])
{ $need_captcha = $s[message_to_us_captcha];
  $category_vars = get_category_variables(round($in[suggest_form_category])); if (!$category_vars[n]) exit;
  $in[n] = $in[suggest_form_category];
  $s[subject] = 'Category suggestion';
  $in[text] = "Parent ".$s[items_types_words][$category_vars[use_for]]." category: #$category_vars[n] $category_vars[name]\n\n\nEmail: $in[suggest_form_email]\nName: $in[suggest_form_name]\nIP: $s[ip]\n\n";
  $in[to] = $s[mail];
}
elseif ($_POST[action]=='claim_listing')
{ $item_vars = get_item_variables('l',round($in[n])); if ((!$s[allow_claim_l]) OR (!$item_vars[n]) OR ($item_vars[owner]) OR (!$s[LUG_u_n])) exit;
  $s[subject] = 'Claim listing request';
  $in[text] = "Link number: $item_vars[n]\nLink title: $item_vars[title]\n\n\nUser number: $s[LUG_u_n]\nUser email: $s[LUG_u_email]\nUsername: $s[LUG_u_username]\nIP: $s[ip]\n\n";
  $in[to] = $s[mail];
}
elseif ($in[what]=='u')
{ $need_captcha = $s[message_owner_captcha];
  $a = get_user_variables($in[n]);
  if ($a[email]) $in[to] = $a[email]; else problem($m[unable]);
  $in[text] = "$in[message]\n\n$m[email]: $in[email]\n$m[name]: $in[name]";
}
elseif (in_array($in[what],$s[item_types_short]))
{ $need_captcha = $s[message_owner_captcha];
  $a = get_item_variables($in[what],$in[n]);
  if ($a[email]) $in[to] = $a[email]; else problem($m[unable]);
  $in[text] = "$in[message]\n\n$m[email]: $in[email]\n$m[name]: $in[name]";
}
else
{ $need_captcha = $s[message_to_us_captcha];
  $in[text] = "$in[message]\nEmail: $in[email]\nName: $in[name]\nIP: $s[ip]\n\n";
  $in[to] = $s[mail];
}

if ($need_captcha) { $x = check_entered_captcha($in[image_control]); if ($x) $chyba[] = $x; }
return array ($chyba,$in);
}

###############################################################################
###############################################################################
###############################################################################

?>