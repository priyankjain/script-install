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

##################################################################################
##################################################################################
##################################################################################

function user_defined_items_emails($all_user_items_list,$value_codes,$value_texts,$template,$email) {
global $s,$m;
if (!$all_user_items_list)
{ $q = dq("select * from $s[pr]usit_list where use_for = '$what' order by rank",1);
  while ($x = mysql_fetch_assoc($q)) $all_user_items_list[] = $x;
}
if (!$all_user_items_values) $all_user_items_values = get_all_user_items_values($what);

foreach ($all_user_items_list as $k=>$v)
{ if ($v[kind]=='checkbox')
  { if ($value_codes[$v[item_n]]) $x[value] = $m[yes];
    else $x[value] = $m[no];
  }
  else $x[value] = $value_texts[$v[item_n]];
  $x[name] = $v[description];
  $display_it[$v[item_n]] = $v[visible_forms];
  if ($email) $b[$v[item_n]] = $a['user_item_'.$v[item_n]] = parse_part($template,$x,1);
  else $b[$v[item_n]] = $a['user_item_'.$v[item_n]] = parse_part($template,$x);
  $a['user_item_value_'.$v[item_n]] = $x[value];
  $a['user_item_name_'.$v[item_n]] = $x[name];  
}
foreach ($b as $k=>$v) if (!$display_it[$k]) unset($b[$k]);
$a[user_defined] = $a[row_user_defined] = implode('',$b);
return $a;
}

##################################################################################

function user_defined_items_form($what,$in) {
global $s,$m;

$what1 = $what;

if (($in[action]=='link_edit') OR ($in[action]=='adv_link_edit') OR ($in[action]=='article_edit') OR ($in[action]=='blog_edit')) // edit
{ $q = dq("select * from $s[pr]usit_values where use_for = '$what' and n = '$in[n]'",1);
  while ($x = mysql_fetch_assoc($q)) $user_items[$x[item_n]] = $x;
}

$q = dq("select * from $s[pr]usit_avail_val where use_for = '$what1' order by rank",1);
while ($x = mysql_fetch_assoc($q)) $avail_val[$x[item_n]][$x[value_code]] = $x[description];

$q = dq("select * from $s[pr]usit_list where use_for = '$what1' order by rank",1);
while ($list = mysql_fetch_assoc($q))
{ unset($list[options]);
  if ($list[kind]=='text')
  { $template = 'form_user_item_text.txt';
    if (($in[action]=='link_edit') OR ($in[action]=='article_edit') OR ($in[action]=='blog_edit')) $list[value] = $user_items[$list[item_n]][value_text]; // edit
    elseif ($in['user_item_'.$list[item_n]]) $list[value] = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $list[value] = $list[def_value_text]; // new
    $usit_value = '<input maxlength="#%maxlength%#" size="70" name="user_item_'.$list[item_n].'" value="'.$list[value].'" class="field10">';
  }
  elseif ($list[kind]=='textarea')
  { $template = 'form_user_item_textarea.txt';
    if (($in[action]=='link_edit') OR ($in[action]=='article_edit') OR ($in[action]=='blog_edit')) $list[value] = $user_items[$list[item_n]][value_text]; // edit
    elseif ($in['user_item_'.$list[item_n]]) $list[value] = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $list[value] = $list[def_value_text]; // new
    $usit_value = '<textarea style="width:650px;height:250px;" name="user_item_'.$list[item_n].'" class="field10">'.$list[value].'</textarea>';
  }
  elseif ($list[kind]=='htmlarea')
  { $template = 'form_user_item_htmlarea.txt';
    if (($in[action]=='link_edit') OR ($in[action]=='article_edit') OR ($in[action]=='blog_edit')) $list[value] = $user_items[$list[item_n]][value_text]; // edit
    elseif ($in['user_item_'.$list[item_n]]) $list[value] = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $list[value] = $list[def_value_text]; // new
    $list[html_editor] = get_fckeditor('user_item_'.$list[item_n],refund_html($list[value]),'PublicToolbar');
  }
  else
  { if (($in[action]=='link_edit') OR ($in[action]=='article_edit') OR ($in[action]=='blog_edit')) $value = $user_items[$list[item_n]][value_code]; // edit
    elseif ($in['user_item_'.$list[item_n]]) $value = $in['user_item_'.$list[item_n]]; // new but already something entered
    else $value = $list[def_value_code]; // new
    if ($list[kind]=='checkbox')
    { $template = 'form_user_item_checkbox.txt';
      if ($value) $list[checked] = ' checked'; else $list[checked] = '';
      $usit_value = '<input type="checkbox" name="user_item_'.$list[item_n].'" value="1"'.$list[checked].'>';
    }
    elseif ($list[kind]=='radio')
    { $template = 'form_user_item_radio.txt';
      if ($list[show_na])
      { if (!$value) $x = ' checked'; else $x = '';
        $list[options] .= '<input type="radio" name="user_item_'.$list[item_n].'" value="0"'.$x.'>'.$m[na].'<br />';
      }
      foreach ($avail_val[$list[item_n]] as $k=>$v)
      { if ($value==$k) $x = ' checked'; else $x = '';
	    $list[options] .= '<input type="radio" name="user_item_'.$list[item_n].'" value="'.$k.'"'.$x.'>'.$v.'<br />';
      }
      $usit_value = $list[options];
    }
    elseif (($list[kind]=='select') OR ($list[kind]=='multiselect'))
    { $template = 'form_user_item_select.txt';
      if ($list[show_na])
      { if (!$value) $x = ' checked'; else $x = '';
        $list[options] .= '<option value="0"'.$x.'>'.$v.'</option><br />';
      }
      foreach ($avail_val[$list[item_n]] as $k=>$v)
      { if ($value==$k) $x = ' selected'; else $x = '';
	    $list[options] .= '<option value="'.$k.'"'.$x.'>'.$v.'</option><br />';
      }
      if ($list[kind]=='multiselect') $usit_value = '<select class="select10" name="user_item_'.$list[item_n].'">'.$list[options].'</select>';
      else $usit_value = '<select class="select10" name="user_item_'.$list[item_n].'">'.$list[options].'</select>';
    }
  }
  $display_it[$list[item_n]] = $list[visible_forms];
  $b[$list[item_n]] = $a['user_item_'.$list[item_n]] = parse_part($template,$list);
  $a['user_item_value_'.$list[item_n]] = $usit_value; unset($usit_value);
  $a['user_item_name_'.$list[item_n]] = $list[description];
}
foreach ($b as $k=>$v) if (!$display_it[$k]) unset($b[$k]);
$a[field_user_defined] = implode('',$b);
return $a;
}

##################################################################################

function categories_rows_form($what,$categories) {
global $s,$m;
//if ($_POST[categories][0]) return '<input type="hidden" name="categories[]" value='.$_POST[categories][0].'">';
//elseif ($_GET[c]) return '<input type="hidden" name="categories[]" value='.$_GET[c].'">';
if (!is_array($categories)) $categories = explode(' ',str_replace('_','',$categories));
$max_cats = $s[$what.'_max_cats_users'];
for ($x=0;$x<=$max_cats-1;$x++)
{ if ($what=='adlink') $b[content] .= all_categories_select('c[]',$categories[$x]).'<br />';
  else
  { $b[content] .= '<select class="select10" name="c[]">';
    if ($x) $b[content] .= '<option value="0">'.$m[none].'</option>';
    $b[content] .= categories_selected($what,$categories[$x],1,1,0,0);
    $b[content] .= '</select><br />';
  }
}
$b[item_name] = $m[categories];
$a = parse_part('form_no_field.txt',$b);
return $a;
}

##################################################################################
##################################################################################
##################################################################################

function article_form_control($in) {
global $s,$m;

if (($in[action]!='article_edited') AND ($s[a_v_captcha]) AND (!$s[LUG_u_n])) { $x = check_entered_captcha($in[image_control]); if ($x) $problem[] = $x; }
if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email]; $in[password] = $user[password];
}

if (($s[a_r_title]) AND (!trim($in[title]))) $problem[] = $m[m_title];
if (trim($in[title]))
{ $y = strlen(trim($in[title]));
  if (($y<$s[a_min_title]) OR ($y>$s[a_max_title])) $problem[] = "$m[title_ls] $s[a_min_title] $m[a] $s[a_max_title] $m[characters].";
  $black = try_blacklist($in[title],'word'); if ($black) $problem[] = $black;
  if ($s[a_convert_title]) $in[title] = ucwords(my_strtolower($in[title]));
}

if (($s[a_r_description]) AND (!trim($in[description]))) $problem[] = $m[m_subt];
if (trim($in[description]))
{ $y = strlen(trim($in[description]));
  if (($y<$s[a_min_description]) OR ($y>$s[a_max_description])) $problem[] = "$m[subt_ls] $s[a_min_description] $m[a] $s[a_max_description] $m[characters].";
  $black = try_blacklist($in[description],'word'); if ($black) $problem[] = $black;
  if ($s[a_convert_description]) $in[description] = ucfirst(my_strtolower($in[description]));
}

if ( (!trim($in[text])) AND ($s[a_r_text]) ) $problem[] = $m[m_text];
if (trim($in[text]))
{ $y = strlen(trim(strip_tags($in[text])));
  if (($y<$s[a_min_text]) OR ($y>$s[a_max_text])) $problem[] = "$m[text_ls] $s[a_min_text] $m[a] $s[a_max_text] $m[characters].";
  if (!$s[a_text_html_editor]) $in[text] = strip_tags($in[text]);
  $black = try_blacklist($in[text],'word'); if ($black) $problem[] = $black;
}

if ((!trim($in[keywords])) AND ($s[a_r_keywords]) ) $problem[] = $m[m_keywords];
if (trim($in[keywords]))
{ $y = strlen(trim($in[keywords]));
  if (($y<$s[a_min_keywords]) OR ($y>$s[a_max_keywords])) $problem[] = "$m[keywords_ls] $s[a_min_keywords] $m[a] $s[a_max_keywords] $m[characters].";
  if (count(explode("\n",trim($in[keywords])))>$s[a_allowed_keywords]) $problem[] = "$m[max_allowed_keywords] $s[a_allowed_keywords] $m[keywords].";
  //$x = explode("\n",trim($in[keywords])); foreach ($x as $k=>$v) $x[$k] = substr(trim($v),0,15); $in[keywords] = implode("\n",$x);
  $black = try_blacklist($in[keywords],'word'); if ($black) $problem[] = $black;
}
$in[keywords] = prepare_keywords($in[keywords]);

if (($s[a_r_map]) AND (!trim($in[map]))) $problem[] = $m[m_map];
if (trim($in[map]))
{ $black = try_blacklist($in[map],'word'); if ($black) $problem[] = $black;
  $x = test_google_map($in[map]);
  if ($x) $in[map] .= $x; else $problem[] = $m[w_map];
}

//$problem
if ($in[selected_category]) $in[c][0] = $in[selected_category];
$in[c] = array_slice($in[c],0,$s[a_max_cats_users]);
if (!$in[c][0]) $problem[] = $m[m_cat];
elseif (!is_array($in[c])) $in[c][0] = $in[c];
else
{ foreach ($in[c] as $k=>$v)
  { if (!$v) { unset ($in[c][$k]); continue; }
    $x = get_category_variables($v);
    if ((!$k) AND (!$x[n])) $problem[] = $m[na_cat];
    elseif (!$x[submithere]) $problem[] = $m[w_cat_art];
    else { $in['c_'.$x[n]][level] = $x[level]; $in['c_'.$x[n]][parent] = $x[parent]; }
  }
}
$in[c] = array_unique($in[c]);

if (($s[a_r_name]) AND (!trim($in[name]))) $problem[] = "$m[mis_field] $m[name]";
elseif (strlen($in[name]) > 255) $problem[] = $m[a_name];
$in[name] = ucwords(my_strtolower($in[name]));

if (($s[a_r_email]) AND (!trim($in[email]))) $problem[] = "$m[mis_field] $m[email]";
elseif (strlen($in[email]) > 255) $problem[] = $m[a_email];
if ((trim($in[email])) AND (!check_email($in[email]))) $problem[] = $m[w_email];
$black = try_blacklist($in[email],'email'); if ($black) $problem[] = $black;

$in[all_user_items_list] = get_all_user_items_list('a');
foreach ($in[all_user_items_list] as $k=>$v)
{ if (($v[kind]=='text') OR ($v[kind]=='textarea'))
  { if (strlen($in['user_item_'.$v[item_n]]) > $v[maxlength]) $problem[] = $v[description].' '.$m[l_field].' '.$v[maxlength].' '.$m[characters];
    $black = try_blacklist($in['user_item_'.$v[item_n]],'word'); if ($black) $problem[] = $black;
  }
  elseif ($v[kind]=='htmlarea')
  { $in['user_item_'.$v[item_n]] = refund_html($in['user_item_'.$v[item_n]]);
    $black = try_blacklist($in['user_item_'.$v[item_n]],'word'); if ($black) $problem[] = $black;
  }
  if (($v[kind]!='checkbox') AND ($v[required]) AND (!$in['user_item_'.$v[item_n]])) $problem[] = $v[description].' '.$m[m_field];
}

if ((!$s[a_r_password]) AND (!trim($in[password]))) $in[password] = get_random_password($in[title],$in[email],$in[categories]);
if (!trim($in[password])) $problem[] = $m[m_pass];
elseif (strlen($in[password]) > 15) $problem[] = $m[l_pass];
elseif ($in[password] != htmlspecialchars(strip_tags($in[password]))) $problem[] = $m[w_pass];

if ($s[a_v_start_end])
{ $in[t1] = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
  $in[t2] = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
}
else $in[t1] = $in[t2] = 0;

if ($in[n]) $n = $in[n]; else $n = 0;

foreach ($_FILES[image_upload][name][$n] as $file_n=>$original_name)
{ if (!$original_name) continue;
  $imagesize = getimagesize($_FILES[image_upload][tmp_name][$n][$file_n]);
  if (($imagesize[2]!=1) AND ($imagesize[2]!=2) AND ($imagesize[2]!=3)) $problem[] = $original_name.' '.$m[incorrect_img_format];
  if ( (($s[a_image_max_w_users]) AND ($imagesize[0]>$s[a_image_max_w_users])) OR (($s[a_image_max_h_users]) AND ($imagesize[1]>$s[a_image_max_h_users])) ) $problem[] = $original_name.' '.$m[big_image];
  if (($s[a_image_max_bytes_users]) AND ($_FILES[image_upload][size][$n][$file_n]>$s[a_image_max_bytes_users])) $problem[] = $original_name.' '.$m[bytes_image_has].' '.$_FILES[image_upload][size][$n][$file_n].' '.$m[bytes].'. '.$m[bytes_allow_is].' '.$s[a_image_max_bytes_users].' '.$m[bytes];
}
$in = replace_array_text($in);
$in[text] = refund_html($in[text]);
return array ($problem,$in);
}

##################################################################################
##################################################################################
##################################################################################

function blog_form_control($in) {
global $s,$m;

if (($in[action]!='blog_edited') AND ($s[b_v_captcha]) AND (!$s[LUG_u_n])) { $x = check_entered_captcha($in[image_control]); if ($x) $problem[] = $x; }
if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email]; $in[password] = $user[password];
}

if (($s[b_r_title]) AND (!trim($in[title]))) $problem[] = $m[m_title];
if (trim($in[title]))
{ $y = strlen(trim($in[title]));
  if (($y<$s[b_min_title]) OR ($y>$s[b_max_title])) $problem[] = "$m[title_ls] $s[b_min_title] $m[a] $s[b_max_title] $m[characters].";
  $black = try_blacklist($in[title],'word'); if ($black) $problem[] = $black;
  if ($s[b_convert_title]) $in[title] = ucwords(my_strtolower($in[title]));
}

if (($s[b_r_description]) AND (!trim($in[description]))) $problem[] = $m[m_subt];
if (trim($in[description]))
{ $y = strlen(trim($in[description]));
  if (($y<$s[b_min_description]) OR ($y>$s[b_max_description])) $problem[] = "$m[subt_ls] $s[b_min_description] $m[a] $s[b_max_description] $m[characters].";
  $black = try_blacklist($in[description],'word'); if ($black) $problem[] = $black;
  if ($s[b_convert_description]) $in[description] = ucfirst(my_strtolower($in[description]));
}

if ( (!trim($in[text])) AND ($s[b_r_text]) ) $problem[] = $m[m_text];
if (trim($in[text]))
{ $y = strlen(trim(strip_tags($in[text])));
  if (($y<$s[b_min_text]) OR ($y>$s[b_max_text])) $problem[] = "$m[text_ls] $s[b_min_text] $m[a] $s[b_max_text] $m[characters].";
  if (!$s[b_text_html_editor]) $in[text] = strip_tags($in[text]);
  $black = try_blacklist($in[text],'word'); if ($black) $problem[] = $black;
}

if ((!trim($in[keywords])) AND ($s[b_r_keywords]) ) $problem[] = $m[m_keywords];
if (trim($in[keywords]))
{ $y = strlen(trim($in[keywords]));
  if (($y<$s[b_min_keywords]) OR ($y>$s[b_max_keywords])) $problem[] = "$m[keywords_ls] $s[b_min_keywords] $m[a] $s[b_max_keywords] $m[characters].";
  if (count(explode("\n",trim($in[keywords])))>$s[b_allowed_keywords]) $problem[] = "$m[max_allowed_keywords] $s[b_allowed_keywords] $m[keywords].";
  //$x = explode("\n",trim($in[keywords])); foreach ($x as $k=>$v) $x[$k] = substr(trim($v),0,15); $in[keywords] = implode("\n",$x);
  $black = try_blacklist($in[keywords],'word'); if ($black) $problem[] = $black;
}
$in[keywords] = prepare_keywords($in[keywords]);

if (($s[b_r_map]) AND (!trim($in[map]))) $problem[] = $m[m_map];
if (trim($in[map]))
{ $black = try_blacklist($in[map],'word'); if ($black) $problem[] = $black;
  $x = test_google_map($in[map]);
  if ($x) $in[map] .= $x; else $problem[] = $m[w_map];
}

//$problem
if ($in[selected_category]) $in[c][0] = $in[selected_category];
$in[c] = array_slice($in[c],0,$s[b_max_cats_users]);
if (!$in[c][0]) $problem[] = $m[m_cat];
elseif (!is_array($in[c])) $in[c][0] = $in[c];
else
{ foreach ($in[c] as $k=>$v)
  { if (!$v) { unset ($in[c][$k]); continue; }
    $x = get_category_variables($v);
    if ((!$k) AND (!$x[n])) $problem[] = $m[na_cat];
    elseif (!$x[submithere]) $problem[] = $m[w_cat_art];
    else { $in['c_'.$x[n]][level] = $x[level]; $in['c_'.$x[n]][parent] = $x[parent]; }
  }
}
$in[c] = array_unique($in[c]);

if (($s[b_r_name]) AND (!trim($in[name]))) $problem[] = "$m[mis_field] $m[name]";
elseif (strlen($in[name]) > 255) $problem[] = $m[b_name];
$in[name] = ucwords(my_strtolower($in[name]));

if (($s[b_r_email]) AND (!trim($in[email]))) $problem[] = "$m[mis_field] $m[email]";
elseif (strlen($in[email]) > 255) $problem[] = $m[b_email];
if ((trim($in[email])) AND (!check_email($in[email]))) $problem[] = $m[w_email];
$black = try_blacklist($in[email],'email'); if ($black) $problem[] = $black;

$in[all_user_items_list] = get_all_user_items_list('b');
foreach ($in[all_user_items_list] as $k=>$v)
{ if (($v[kind]=='text') OR ($v[kind]=='textarea'))
  { if (strlen($in['user_item_'.$v[item_n]]) > $v[maxlength]) $problem[] = $v[description].' '.$m[l_field].' '.$v[maxlength].' '.$m[characters];
    $black = try_blacklist($in['user_item_'.$v[item_n]],'word'); if ($black) $problem[] = $black;
  }
  elseif ($v[kind]=='htmlarea')
  { $in['user_item_'.$v[item_n]] = refund_html($in['user_item_'.$v[item_n]]);
    $black = try_blacklist($in['user_item_'.$v[item_n]],'word'); if ($black) $problem[] = $black;
  }
  if (($v[kind]!='checkbox') AND ($v[required]) AND (!$in['user_item_'.$v[item_n]])) $problem[] = $v[description].' '.$m[m_field];
}

if ((!$s[b_r_password]) AND (!trim($in[password]))) $in[password] = get_random_password($in[title],$in[email],$in[categories]);
if (!trim($in[password])) $problem[] = $m[m_pass];
elseif (strlen($in[password]) > 15) $problem[] = $m[l_pass];
elseif ($in[password] != htmlspecialchars(strip_tags($in[password]))) $problem[] = $m[w_pass];

if ($s[b_v_start_end])
{ $in[t1] = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
  $in[t2] = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
}
else $in[t1] = $in[t2] = 0;

if ($in[n]) $n = $in[n]; else $n = 0;

foreach ($_FILES[image_upload][name][$n] as $file_n=>$original_name)
{ if (!$original_name) continue;
  $imagesize = getimagesize($_FILES[image_upload][tmp_name][$n][$file_n]);
  if (($imagesize[2]!=1) AND ($imagesize[2]!=2) AND ($imagesize[2]!=3)) $problem[] = $original_name.' '.$m[incorrect_img_format];
  if ( (($s[b_image_max_w_users]) AND ($imagesize[0]>$s[b_image_max_w_users])) OR (($s[b_image_max_h_users]) AND ($imagesize[1]>$s[b_image_max_h_users])) ) $problem[] = $original_name.' '.$m[big_image];
  if (($s[b_image_max_bytes_users]) AND ($_FILES[image_upload][size][$n][$file_n]>$s[b_image_max_bytes_users])) $problem[] = $original_name.' '.$m[bytes_image_has].' '.$_FILES[image_upload][size][$n][$file_n].' '.$m[bytes].'. '.$m[bytes_allow_is].' '.$s[b_image_max_bytes_users].' '.$m[bytes];
}
$in = replace_array_text($in);
$in[text] = refund_html($in[text]);
return array ($problem,$in);
}

##################################################################################
##################################################################################
##################################################################################

function link_form_control($in) {
global $s,$m;
if (($in[action]!='link_edited') AND ($s[l_v_captcha]) AND (!$s[LUG_u_n])) { $x = check_entered_captcha($in[image_control]); if ($x) $problem[] = $x; }
if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email]; $in[password] = $user[password];
}

if (($s[l_r_url]) AND (!trim($in[url]))) $problem[] = $m[m_url];
if (trim($in[url]))
{ if ( ($s[add_http]) AND (!preg_match("/^(http:\/\/*+)/i",$in[url])) ) $in[url] = 'http://'.$in[url];
  $checked_url = check_url($in[url],$s[checkurl]); if ($checked_url[1]) $problem[] = $checked_url[1];
  $black = try_blacklist($in[url],'url'); if ($black) $problem[] = $black;
  if ($in[action]!='link_edited')
  { if ($s[duplicate])
    { $duplicate = link_check_duplicate($in[url]);
      if ($duplicate) $problem[] = $duplicate;
    }
    if ($s[ls_dupl_domain])
    { $x = parse_url($in[url]); $dom = str_replace('www.','',$x[host]);
	  $q = dq("select count(*) from $s[pr]links where url like '%$dom%'",1);
      $y = mysql_fetch_row($q); if ($y[0]) $problem[] = "$m[domain_dupl] $dom";
    }
  }
  if ($s[l_pr_google_min])
  { include_once("$s[phppath]/popularity_google.php");
    $gpr = new GooglePR();
    $pr_google = $gpr->GetPR($in[url]);
    if ($pr_google<$s[l_pr_google_min]) $problem[] = "$m[l_pr_google_min1] $s[l_pr_google_min] $m[l_pr_google_min2] $pr_google.";
  }
}

if ($in[selected_category]) $in[c][0] = $in[selected_category];
$query = 'AND '.my_implode('n','OR',$in[c]);
$q = dq("select recip from $s[pr]cats where use_for = 'l' $query",0);
while ($x = mysql_fetch_row($q)) { if ($x[0]) { $s[l_r_recip] = 1; break; } }

if (strstr($in[recip],$s[site_url])) $in[recip] = '';
if (($s[l_r_recip]) AND (!$in[recip])) $problem[] = $m[m_recip];
elseif ($in[recip]) 
{ if (($s[add_http]) AND (!preg_match("/^(http:\/\/*+)/i",$in[recip]))) $in[recip] = 'http://'.$in[recip];
  $checked_url = check_url ($in[recip],$s[checkrecip]);
  if ($checked_url[1]) 
  { if ($s[l_r_recip]) $problem[] = $checked_url[1];
    else $in[i_recip] = "Recip URL error: $checked_url[1].";
  }
  elseif (strlen($checked_url[0])>1)
  { $checked_recip = check_recip ($checked_url[0]);
    if (!$checked_recip) 
    { if ($s[l_r_recip]) $problem[] = "$m[not_recip] $in[recip].";
      else $in[i_recip] = "Recip link not found at URL: $in[recip].";
    }
    else { $in[i_recip] = 'Recip link found.'; $in[pick] = $s[recip_pick]; }
  }
  else $in[i_recip] = "Recip link not tested.";
  $black = try_blacklist($in[recip],"url");
  if ($black) $problem[] = $black;
  if (($in[action]!='link_edited') AND ($s[duplicate]))
  { $duplicate = link_check_duplicate($in[recip]);
    if ($duplicate) $problem[] = $duplicate;
  }
}

if (($s[l_r_title]) AND (!trim($in[title]))) $problem[] = $m[m_title];
if (trim($in[title]))
{ $y = strlen(trim($in[title]));
  if (($y<$s[l_min_title]) OR ($y>$s[l_max_title])) $problem[] = "$m[title_ls] $s[l_min_title] $m[a] $s[l_max_title] $m[characters].";
  $black = try_blacklist($in[title],'word'); if ($black) $problem[] = $black;
  if ($s[l_convert_title]) $in[title] = ucwords(my_strtolower($in[title]));
}

if (($s[l_r_description]) AND (!trim($in[description]))) $problem[] = $m[m_desc];
if (trim($in[description]))
{ $y = strlen(trim($in[description]));
  if (($y<$s[l_min_description]) OR ($y>$s[l_max_description])) $problem[] = "$m[desc_ls] $s[l_min_description] $m[a] $s[l_max_description] $m[characters].";
  $black = try_blacklist($in[description],'word'); if ($black) $problem[] = $black;
  if ($s[l_convert_description]) $in[description] = ucfirst(my_strtolower($in[description]));
}

if ( (!trim($in[detail])) AND ($s[l_r_detail]) ) $problem[] = $m[m_detail];
if (trim($in[detail]))
{ $y = strlen(trim(strip_tags($in[detail])));
  if (($y<$s[l_min_detail]) OR ($y>$s[l_max_detail])) $problem[] = "$m[detail_ls] $s[l_min_detail] $m[a] $s[l_max_detail] $m[characters].";
  if (!$s[l_details_html_editor]) $in[detail] = strip_tags($in[detail]);
  $black = try_blacklist($in[detail],'word'); if ($black) $problem[] = $black;
}

if ((!trim($in[keywords])) AND ($s[l_r_keywords]) ) $problem[] = $m[m_keywords];
if (trim($in[keywords]))
{ $y = strlen(trim($in[keywords]));
  if (($y<$s[l_min_keywords]) OR ($y>$s[l_max_keywords])) $problem[] = "$m[keywords_ls] $s[l_min_keywords] $m[a] $s[l_max_keywords] $m[characters].";
  if (count(explode("\n",trim($in[keywords])))>$s[l_allowed_keywords]) $problem[] = "$m[max_allowed_keywords] $s[l_allowed_keywords] $m[keywords].";
  //$x = explode("\n",trim($in[keywords])); foreach ($x as $k=>$v) $x[$k] = substr(trim($v),0,15); $in[keywords] = implode("\n",$x);
  $black = try_blacklist($in[keywords],'word'); if ($black) $problem[] = $black;
}
$in[keywords] = prepare_keywords($in[keywords]);

if (($s[l_r_map]) AND (!trim($in[map]))) $problem[] = $m[m_map];
if (trim($in[map]))
{ $black = try_blacklist($in[map],'word'); if ($black) $problem[] = $black;
  $x = test_google_map($in[map]);
  if ($x) $in[map] .= $x; else $problem[] = $m[w_map];
}

$in[c] = array_slice($in[c],0,$s[l_max_cats_users]);
if (!$in[c][0]) $problem[] = $m[m_cat];
elseif (!is_array($in[c])) $in[c][0] = $in[c];
else
{ foreach ($in[c] as $k=>$v)
  { if (!$v) { unset ($in[c][$k]); continue; }
    $x = get_category_variables($v);
    if ((!$k) AND (!$x[n])) $problem[] = $m[na_cat];
    elseif (!$x[submithere]) $problem[] = $m[w_cat_link];
    else { $in['c_'.$x[n]][level] = $x[level]; $in['c_'.$x[n]][parent] = $x[parent]; }
  }
}
$in[c] = array_unique($in[c]);

if (($s[l_r_name]) AND (!trim($in[name]))) $problem[] = "$m[mis_field] $m[name]";
elseif (strlen($in[name]) > 255) $problem[] = $m[l_name];
$in[name] = ucwords(my_strtolower($in[name]));

if (($s[l_r_email]) AND (!trim($in[email]))) $problem[] = "$m[mis_field] $m[email]";
elseif (strlen($in[email]) > 255) $problem[] = $m[l_email];
if ((trim($in[email])) AND (!check_email($in[email]))) $problem[] = $m[w_email];
$black = try_blacklist($in[email],'email'); if ($black) $problem[] = $black;

$in[all_user_items_list] = get_all_user_items_list('l');
foreach ($in[all_user_items_list] as $k=>$v)
{ if (($v[kind]=='text') OR ($v[kind]=='textarea'))
  { if (strlen($in['user_item_'.$v[item_n]]) > $v[maxlength]) $problem[] = $v[description].' '.$m[l_field].' '.$v[maxlength].' '.$m[characters];
    $black = try_blacklist($in['user_item_'.$v[item_n]],'word'); if ($black) $problem[] = $black;
  }
  elseif ($v[kind]=='htmlarea')
  { $in['user_item_'.$v[item_n]] = refund_html($in['user_item_'.$v[item_n]]);
    $black = try_blacklist($in['user_item_'.$v[item_n]],'word'); if ($black) $problem[] = $black;
  }
  if (($v[kind]!='checkbox') AND ($v[required]) AND (!$in['user_item_'.$v[item_n]])) $problem[] = $v[description].' '.$m[m_field];
}

if ((!$s[l_r_password]) AND (!trim($in[password]))) $in[password] = get_random_password($in[title],$in[email],$in[categories]);
if (!trim($in[password])) $problem[] = $m[m_pass];
elseif (strlen($in[password]) > 15) $problem[] = $m[l_pass];
elseif ($in[password] != htmlspecialchars(strip_tags($in[password]))) $problem[] = $m[w_pass];

if ($s[l_v_start_end])
{ $in[t1] = get_timestamp($in[t1][d],$in[t1][m],$in[t1][y],'start');
  $in[t2] = get_timestamp($in[t2][d],$in[t2][m],$in[t2][y],'end');
}
else $in[t1] = $in[t2] = 0;

if ($in[n]) $n = $in[n]; else $n = 0;
foreach ($_FILES[image_upload][name][$n] as $file_n=>$original_name)
{ if (!$original_name) continue;
  $imagesize = getimagesize($_FILES[image_upload][tmp_name][$n][$file_n]);
  if (($imagesize[2]!=1) AND ($imagesize[2]!=2) AND ($imagesize[2]!=3)) $problem[] = $original_name.' '.$m[incorrect_img_format];
  if ( (($s[l_image_max_w_users]) AND ($imagesize[0]>$s[l_image_max_w_users])) OR (($s[l_image_max_h_users]) AND ($imagesize[1]>$s[l_image_max_h_users])) ) $problem[] = $original_name.' '.$m[big_image];
  if (($s[l_image_max_bytes_users]) AND ($_FILES[image_upload][size][$n][$file_n]>$s[l_image_max_bytes_users])) $problem[] = $original_name.' '.$m[bytes_image_has].' '.$_FILES[image_upload][size][$n][$file_n].' '.$m[bytes].'. '.$m[bytes_allow_is].' '.$s[l_image_max_bytes_users].' '.$m[bytes];
}

$in = replace_array_text($in);
$in[detail] = refund_html($in[detail]);
return array ($problem,$in);
}

##################################################################################

function link_check_duplicate($url) {
global $s,$m;
$result = dq("select count(*) from $s[pr]links where (url like '$url') or (recip like '$url')",1);
$pole = mysql_fetch_row($result);
if ($pole[0]) return "$m[url_dupl] $url";
}

##################################################################################
##################################################################################
##################################################################################


function link_create_edit_form_public($in,$n) {
global $s,$m;
if ($s[l_v_start_end])
{ $x[date_1] = date_select($in[t1],'t1'); $x[date_2] = date_select($in[t2],'t2');
  $in[field_dates] = parse_part('form_dates.txt',$x);
}
if ($s[selected_category]) $in[selected_category] = $s[selected_category];
if ($in[selected_category])
{ $x = get_category_variables($in[selected_category]);
  if ($x[n])
  { $in[field_categories] = '<input type="hidden" name="selected_category" value="'.$in[selected_category].'">';
    $x[item_name] = $m[Category]; $y = list_of_categories_for_item('l',0,$in[selected_category],'<br />',1); $x[item_value] = $y[categories_names]; $in[field_categories] .= parse_part('form_submitted_row.txt',$x);
  }
}
else $in[field_categories] = categories_rows_form('l',$in[c]);
if ($s[l_v_url]) { $x[item_name] = $m[url]; $x[field_name] = 'url'; $x[field_value] = $in[url]; $x[field_maxlength] = 255; $in[field_url] = parse_part('form_field.txt',$x); }
if ($s[l_v_recip]) { $x[item_name] = $m[recip_url]; $x[field_name] = 'recip'; $x[field_value] = $in[recip]; $x[field_maxlength] = 255; $in[field_recip_url] = parse_part('form_field.txt',$x); }
if ($s[l_v_title]) { $x[item_name] = $m[title]; $x[field_name] = 'title'; $x[field_value] = $in[title]; $x[field_maxlength] = $s[m_title]; $in[field_title] = parse_part('form_field.txt',$x); }
if ($s[l_v_description]) { $x[item_name] = $m[description]; $x[field_name] = 'description'; $x[field_value] = $in[description]; $x[field_maxlength] = $s[m_desc]; $in[field_description] = parse_part('form_field.txt',$x); }
if ($s[l_v_detail])
{ $x[item_name] = $m[detail]; $x[field_name] = 'detail'; $x[field_value] = $in[detail]; $x[field_maxlength] = $s[l_max_detail]; $x[field_maxlength_now] = $s[l_max_detail] - strlen($in[detail]);
  if ($s[l_details_html_editor]) { $x[html_editor] = get_fckeditor('detail',$in[detail],'PublicToolbar'); $in[field_detail] = parse_part('form_detail_html.txt',$x); }
  else $in[field_detail] = parse_part('form_detail_textarea.txt',$x);
}
if ($s[l_v_keywords]) { /*$x[item_name] = $m[description];*/ $x[field_name] = 'keywords'; $x[field_value] = $in[keywords]; $x[field_maxlength] = $s[m_keywords]; $x[max_keywords] = $s[l_allowed_keywords]; $in[field_keywords] = parse_part('form_keywords.txt',$x); }
if ($s[l_v_map]) { $x[item_name] = $m[map]; $x[field_name] = 'map'; $x[field_value] = str_replace('_gmok_','',$in[map]); $x[field_maxlength] = 255; $in[field_map] = parse_part('form_field.txt',$x); }
if ($s[l_v_rss_url]) { $x[item_name] = $m[rss_url]; $x[field_name] = 'rss_url'; $x[field_value] = $in[rss_url]; $x[field_maxlength] = 255; $in[field_rss_url] = parse_part('form_field.txt',$x); }
if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email];
}
else
{ if ($s[l_v_password]) $in[field_password] = parse_part('form_password.txt',$in);
  if (($s[l_v_captcha]) AND (!$n)) $in[field_captcha_test] = parse_part('form_captcha_test.txt',$s);
  if ($s[l_v_name]) { $x[item_name] = $m[name]; $x[field_name] = 'name'; $x[field_value] = $in[name]; $x[field_maxlength] = 255; $in[field_name] = parse_part('form_field.txt',$x); }
  if ($s[l_v_email]) { $x[item_name] = $m[email]; $x[field_name] = 'email'; $x[field_value] = $in[email]; $x[field_maxlength] = 255; $in[field_email] = parse_part('form_field.txt',$x); }
}
$x = user_defined_items_form('l',$in); $in = array_merge((array)$in,(array)$x);

if ($n)
{ $x = list_of_categories_for_item('l',0,$in[c],'<br />',1); $in[current_categories] = $x[categories_names];
  $files_pictures = get_item_files_pictures('l',$n,0);
}

if (($s[l_image_small_w_users]) AND ($s[l_image_small_h_users])) { $x[hide_max_size_begin] = '<!--'; $x[hide_max_size_end] = '-->'; }
else { $x[max_image_w] = $s[l_image_max_w_users]; $x[max_image_h] = $s[l_image_max_h_users]; $x[max_image_bytes] = $s[l_image_max_bytes_users]; }
for ($y=1;$y<=$s[l_max_pictures_users];$y++)
{ /*$x[item_name] = "$m[upload_picture]$y";*/ $x[field_name] = 'image_upload['.$n.']['.$y.']';
  $x[image_n] = $y;
  if ($in[image_description][$y]) $files_pictures[image_description][$n][$y] = $in[image_description][$y];
  $x[description_name] = 'image_description['.$n.']['.$y.']'; $x[description_value] = $files_pictures[image_description][$n][$y];
  $in[field_pictures] .= parse_part('form_upload.txt',$x);
  if (($n) AND ($files_pictures[image_url][$n][$y]))
  { $big_file = preg_replace("/\/$n-/","/$n-big-",$files_pictures[image_url][$n][$y]);
    //if (!file_exists(str_replace("$s[site_url]/","$s[phppath]/",$big_file))) $big_file = $files_pictures[image_url][$n][$y];
    $x[current_picture] = image_preview_code($files_pictures[image_n][$n][$y],$files_pictures[image_url][$n][$y],preg_replace("/\/$n-/","/$n-big-",$files_pictures[image_url][$n][$y]));
    $in[field_pictures] .= parse_part('form_picture_current.txt',$x);
  }
}
return $in;
}

###########################################################################

function article_create_edit_form_public($in,$n) {
global $s,$m;
if ($s[a_v_start_end])
{ $x[date_1] = date_select($in[t1],'t1'); $x[date_2] = date_select($in[t2],'t2');
  $in[field_dates] = parse_part('form_dates.txt',$x);
}
if ($s[selected_category]) $in[selected_category] = $s[selected_category];
if ($in[selected_category])
{ $x = get_category_variables($in[selected_category]);
  if ($x[n])
  { $in[field_categories] = '<input type="hidden" name="selected_category" value="'.$in[selected_category].'">';
    $x[item_name] = $m[Category]; $y = list_of_categories_for_item('a',0,$in[selected_category],'<br />',1); $x[item_value] = $y[categories_names]; $in[field_categories] .= parse_part('form_submitted_row.txt',$x);
  }
}
else $in[field_categories] = categories_rows_form('a',$in[c]);
if ($s[a_v_title]) { $x[item_name] = $m[title]; $x[field_name] = 'title'; $x[field_value] = $in[title]; $x[field_maxlength] = $s[m_title]; $in[field_title] = parse_part('form_field.txt',$x); }
if ($s[a_v_description]) { $x[item_name] = $m[description]; $x[field_name] = 'description'; $x[field_value] = $in[description]; $x[field_maxlength] = $s[m_description]; $in[field_description] = parse_part('form_field.txt',$x); }
if ($s[a_v_text])
{ $x[item_name] = $m[text]; $x[field_name] = 'text'; $x[field_value] = $in[text]; $x[field_maxlength] = $s[a_max_text]; $x[field_maxlength_now] = $s[a_max_text] - strlen($in[text]);
  if ($s[a_text_html_editor]) { $x[html_editor] = get_fckeditor('text',$in[text],'PublicToolbar'); $in[field_text] = parse_part('form_detail_html.txt',$x); }
  else $in[field_text] = parse_part('form_detail_textarea.txt',$x);
}
if ($s[a_v_keywords]) { /*$x[item_name] = $m[description];*/ $x[field_name] = 'keywords'; $x[field_value] = $in[keywords]; $x[field_maxlength] = $s[a_m_keywords]; $x[max_keywords] = $s[a_allowed_keywords]; $in[field_keywords] = parse_part('form_keywords.txt',$x); }
if ($s[a_v_map]) { $x[item_name] = $m[map]; $x[field_name] = 'map'; $x[field_value] = str_replace('_gmok_','',$in[map]); $x[field_maxlength] = 255; $in[field_map] = parse_part('form_field.txt',$x); }

if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email];
}
else
{ if ($s[a_v_password]) $in[field_password] = parse_part('form_password.txt',$in);
  if (($s[a_v_captcha]) AND (!$n)) $in[field_captcha_test] = parse_part('form_captcha_test.txt',$s);
  if ($s[a_v_name]) { $x[item_name] = $m[name]; $x[field_name] = 'name'; $x[field_value] = $in[name]; $x[field_maxlength] = 255; $in[field_name] = parse_part('form_field.txt',$x); }
  if ($s[a_v_email]) { $x[item_name] = $m[email]; $x[field_name] = 'email'; $x[field_value] = $in[email]; $x[field_maxlength] = 255; $in[field_email] = parse_part('form_field.txt',$x); }
}
$x = user_defined_items_form('a',$in); $in = array_merge((array)$in,(array)$x);

if ($n)
{ $x = list_of_categories_for_item('a',0,$in[c],'<br />',1); $in[current_categories] = $x[categories_names];
  $files_pictures = get_item_files_pictures('a',$n,0);
}

if (($s[a_image_small_w_users]) AND ($s[a_image_small_h_users])) { $x[hide_max_size_begin] = '<!--'; $x[hide_max_size_end] = '-->'; }
else { $x[max_image_w] = $s[a_image_max_w_users]; $x[max_image_h] = $s[a_image_max_h_users]; $x[max_image_bytes] = $s[a_image_max_bytes_users]; }
for ($y=1;$y<=$s[a_max_pictures_users];$y++)
{ /*$x[item_name] = "$m[upload_picture]$y";*/ $x[field_name] = 'image_upload['.$n.']['.$y.']';
  $x[image_n] = $y;
  if ($in[image_description][$y]) $files_pictures[image_description][$n][$y] = $in[image_description][$y];
  $x[description_name] = 'image_description['.$n.']['.$y.']'; $x[description_value] = $files_pictures[image_description][$n][$y];
  $in[field_pictures] .= parse_part('form_upload.txt',$x);
  if (($n) AND ($files_pictures[image_url][$n][$y]))
  { $big_file = preg_replace("/\/$n-/","/$n-big-",$files_pictures[image_url][$n][$y]);
    //if (!file_exists(str_replace("$s[site_url]/","$s[phppath]/",$big_file))) $big_file = $files_pictures[image_url][$n][$y];
    $x[current_picture] = image_preview_code($files_pictures[image_n][$n][$y],$files_pictures[image_url][$n][$y],preg_replace("/\/$n-/","/$n-big-",$files_pictures[image_url][$n][$y]));
    $in[field_pictures] .= parse_part('form_picture_current.txt',$x);
  }
}
return $in;
}

#################################################################################

function article_created_edited_thankyou($in,$template) {
global $s,$m;
if (!$s[use_for]) $s[use_for] = 'a';
$x = user_defined_items_display($s[use_for],$in[all_user_items_list],'',$in[n],'user_item_submitted.txt',0,0,1,0); $in[row_user_defined] = $x[$in[n]];
if ($s[a_v_start_end]) $in[row_dates] = submitted_show_dates('a',$in[t1],$in[t2]);
$x[item_name] = $m[categories]; $y = list_of_categories_for_item('a',0,$in[c],'<br />',1); $x[item_value] = $y[categories_names]; $in[row_categories] = parse_part('form_submitted_row.txt',$x);
if ($s[a_v_title]) { $x[item_name] = $m[title]; $x[item_value] = $in[title]; $in[row_title] = parse_part('form_submitted_row.txt',$x); }
if ($s[a_v_description]) { $x[item_name] = $m[description]; $x[item_value] = $in[description]; $in[row_description] = parse_part('form_submitted_row.txt',$x); }
if ($s[a_v_text]) { $x[item_name] = $m[text]; $x[item_value] = $in[text]; $in[row_text] = parse_part('form_submitted_row.txt',$x); }
if ($s[a_v_map]) { $x[item_name] = $m[map]; $x[item_value] = str_replace('_gmok_','',$in[map]); $in[row_map] = parse_part('form_submitted_row.txt',$x); }
if (!$s[LUG_u_n])
{ if ($s[a_v_name]) { $x[item_name] = $m[name]; $x[item_value] = $in[name]; $in[row_name] = parse_part('form_submitted_row.txt',$x); }
  if ($s[a_v_email]) { $x[item_name] = $m[email]; $x[item_value] = $in[email]; $in[row_email] = parse_part('form_submitted_row.txt',$x); }
}
$x[item_name] = $m[n]; $x[item_value] = $in[n]; $in[row_number] = parse_part('form_submitted_row.txt',$x);
$in[row_images] = created_edited_images($s[use_for],$in[n]);
page_from_template($template,$in);
}

#################################################################################


function blog_create_edit_form_public($in,$n) {
global $s,$m;
if ($s[b_v_start_end])
{ $x[date_1] = date_select($in[t1],'t1'); $x[date_2] = date_select($in[t2],'t2');
  $in[field_dates] = parse_part('form_dates.txt',$x);
}
if ($s[selected_category]) $in[selected_category] = $s[selected_category];
if ($in[selected_category])
{ $x = get_category_variables($in[selected_category]);
  if ($x[n])
  { $in[field_categories] = '<input type="hidden" name="selected_category" value="'.$in[selected_category].'">';
    $x[item_name] = $m[Category]; $y = list_of_categories_for_item('b',0,$in[selected_category],'<br />',1); $x[item_value] = $y[categories_names]; $in[field_categories] .= parse_part('form_submitted_row.txt',$x);
  }
}
else $in[field_categories] = categories_rows_form('b',$in[c]);
if ($s[b_v_title]) { $x[item_name] = $m[title]; $x[field_name] = 'title'; $x[field_value] = $in[title]; $x[field_maxlength] = $s[m_title]; $in[field_title] = parse_part('form_field.txt',$x); }
if ($s[b_v_description]) { $x[item_name] = $m[description]; $x[field_name] = 'description'; $x[field_value] = $in[description]; $x[field_maxlength] = $s[m_description]; $in[field_description] = parse_part('form_field.txt',$x); }
if ($s[b_v_text]) { $x[item_name] = $m[text]; $x[field_name] = 'text'; $x[field_value] = $in[text]; $x[field_maxlength] = $s[b_max_text]; $x[field_maxlength_now] = $s[b_max_text] - strlen($in[text]); if ($s[b_text_html_editor]) { $x[html_editor] = get_fckeditor('text',$in[text],'PublicToolbar'); $in[field_text] = parse_part('form_detail_html.txt',$x); } else $in[field_text] = parse_part('form_detail_textarea.txt',$x); }
if ($s[b_v_keywords]) { /*$x[item_name] = $m[description];*/ $x[field_name] = 'keywords'; $x[field_value] = $in[keywords]; $x[field_maxlength] = $s[b_m_keywords]; $x[max_keywords] = $s[b_allowed_keywords]; $in[field_keywords] = parse_part('form_keywords.txt',$x); }
if ($s[b_v_map]) { $x[item_name] = $m[map]; $x[field_name] = 'map'; $x[field_value] = str_replace('_gmok_','',$in[map]); $x[field_maxlength] = 255; $in[field_map] = parse_part('form_field.txt',$x); }

if ($s[LUG_u_n])
{ $user = get_user_variables($s[LUG_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email];
}
else
{ if ($s[b_v_password]) $in[field_password] = parse_part('form_password.txt',$in);
  if (($s[b_v_captcha]) AND (!$n)) $in[field_captcha_test] = parse_part('form_captcha_test.txt',$s);
  if ($s[b_v_name]) { $x[item_name] = $m[name]; $x[field_name] = 'name'; $x[field_value] = $in[name]; $x[field_maxlength] = 255; $in[field_name] = parse_part('form_field.txt',$x); }
  if ($s[b_v_email]) { $x[item_name] = $m[email]; $x[field_name] = 'email'; $x[field_value] = $in[email]; $x[field_maxlength] = 255; $in[field_email] = parse_part('form_field.txt',$x); }
}
$x = user_defined_items_form('b',$in); $in = array_merge((array)$in,(array)$x);

if ($n)
{ $x = list_of_categories_for_item('b',0,$in[c],'<br />',1); $in[current_categories] = $x[categories_names];
  $files_pictures = get_item_files_pictures('b',$n,0);
}

if (($s[b_image_small_w_users]) AND ($s[b_image_small_h_users])) { $x[hide_max_size_begin] = '<!--'; $x[hide_max_size_end] = '-->'; }
else { $x[max_image_w] = $s[b_image_max_w_users]; $x[max_image_h] = $s[b_image_max_h_users]; $x[max_image_bytes] = $s[b_image_max_bytes_users]; }
for ($y=1;$y<=$s[b_max_pictures_users];$y++)
{ /*$x[item_name] = "$m[upload_picture]$y";*/ $x[field_name] = 'image_upload['.$n.']['.$y.']';
  $x[image_n] = $y;
  if ($in[image_description][$y]) $files_pictures[image_description][$n][$y] = $in[image_description][$y];
  $x[description_name] = 'image_description['.$n.']['.$y.']'; $x[description_value] = $files_pictures[image_description][$n][$y];
  $in[field_pictures] .= parse_part('form_upload.txt',$x);
  if (($n) AND ($files_pictures[image_url][$n][$y]))
  { $big_file = preg_replace("/\/$n-/","/$n-big-",$files_pictures[image_url][$n][$y]);
    //if (!file_exists(str_replace("$s[site_url]/","$s[phppath]/",$big_file))) $big_file = $files_pictures[image_url][$n][$y];
    $x[current_picture] = image_preview_code($files_pictures[image_n][$n][$y],$files_pictures[image_url][$n][$y],preg_replace("/\/$n-/","/$n-big-",$files_pictures[image_url][$n][$y]));
    $in[field_pictures] .= parse_part('form_picture_current.txt',$x);
  }
}
return $in;
}

#################################################################################

function blog_created_edited_thankyou($in,$template) {
global $s,$m;
if (!$s[use_for]) $s[use_for] = 'b';
$x = user_defined_items_display($s[use_for],$in[all_user_items_list],'',$in[n],'user_item_submitted.txt',0,0,1,0); $in[row_user_defined] = $x[$in[n]];
if ($s[b_v_start_end]) $in[row_dates] = submitted_show_dates('b',$in[t1],$in[t2]);
$x[item_name] = $m[categories]; $y = list_of_categories_for_item('b',0,$in[c],'<br />',1); $x[item_value] = $y[categories_names]; $in[row_categories] = parse_part('form_submitted_row.txt',$x);
if ($s[b_v_title]) { $x[item_name] = $m[title]; $x[item_value] = $in[title]; $in[row_title] = parse_part('form_submitted_row.txt',$x); }
if ($s[b_v_description]) { $x[item_name] = $m[description]; $x[item_value] = $in[description]; $in[row_description] = parse_part('form_submitted_row.txt',$x); }
if ($s[b_v_text]) { $x[item_name] = $m[text]; $x[item_value] = $in[text]; $in[row_text] = parse_part('form_submitted_row.txt',$x); }
if ($s[b_v_map]) { $x[item_name] = $m[map]; $x[item_value] = str_replace('_gmok_','',$in[map]); $in[row_map] = parse_part('form_submitted_row.txt',$x); }
if (!$s[LUG_u_n])
{ if ($s[b_v_name]) { $x[item_name] = $m[name]; $x[item_value] = $in[name]; $in[row_name] = parse_part('form_submitted_row.txt',$x); }
  if ($s[b_v_email]) { $x[item_name] = $m[email]; $x[item_value] = $in[email]; $in[row_email] = parse_part('form_submitted_row.txt',$x); }
}
$x[item_name] = $m[n]; $x[item_value] = $in[n]; $in[row_number] = parse_part('form_submitted_row.txt',$x);
$in[row_images] = created_edited_images($s[use_for],$in[n]);
page_from_template($template,$in);
}

#################################################################################

function link_created_edited_thankyou($in,$template) {
global $s,$m;
if (!$s[use_for]) $s[use_for] = 'l';
$x = user_defined_items_display($s[use_for],$in[all_user_items_list],'',$in[n],'user_item_submitted.txt',0,0,1,0); $in[row_user_defined] = $x[$in[n]];
$x[item_name] = $m[categories]; $y = list_of_categories_for_item('l',0,$in[c],'<br />',1); $x[item_value] = $y[categories_names]; $in[row_categories] = parse_part('form_submitted_row.txt',$x);
$in[row_dates] = submitted_show_dates('l',$in[t1],$in[t2]);
if ($s[l_v_title]) { $x[item_name] = $m[title]; $x[item_value] = $in[title]; $in[row_title] = parse_part('form_submitted_row.txt',$x); }
if ($s[l_v_url]) { $x[item_name] = $m[url]; $x[item_value] = $in[url]; $in[row_url] = parse_part('form_submitted_row.txt',$x); }
if ($s[l_v_recip]) { $x[item_name] = $m[recip_url]; $x[item_value] = $in[recip]; $in[row_recip_url] = parse_part('form_submitted_row.txt',$x); }
if ($s[l_v_description]) { $x[item_name] = $m[description]; $x[item_value] = $in[description]; $in[row_description] = parse_part('form_submitted_row.txt',$x); }
if ($s[l_v_detail]) { $x[item_name] = $m[detail]; if ($s[det_br]) $in[detail] = str_replace("\n",'<br />',$in[detail]); $x[item_value] = $in[detail]; $in[row_detail] = parse_part('form_submitted_row.txt',$x); }
if ($s[l_v_map]) { $x[item_name] = $m[map]; $x[item_value] = str_replace('_gmok_','',$in[map]); $in[row_map] = parse_part('form_submitted_row.txt',$x); }
if (!$s[LUG_u_n])
{ if ($s[l_v_name]) { $x[item_name] = $m[name]; $x[item_value] = $in[name]; $in[row_name] = parse_part('form_submitted_row.txt',$x); }
  if ($s[l_v_email]) { $x[item_name] = $m[email]; $x[item_value] = $in[email]; $in[row_email] = parse_part('form_submitted_row.txt',$x); }
  if ($s[l_v_password]) { $x[item_name] = $m[password]; $x[item_value] = $in[password]; $in[row_password] = parse_part('form_submitted_row.txt',$x); }
}
$x[item_name] = $m[n]; $x[item_value] = $in[n]; $in[row_number] = parse_part('form_submitted_row.txt',$x);
$in[row_images] = created_edited_images($s[use_for],$in[n]);
page_from_template($template,$in);
}

##################################################################################

function created_edited_images($what,$n) {
global $s;
$x = explode('_',$what);
$what = $x[0]; if (($x[1]=='q') OR ($x[1]=='w')) $queue = 1; else $queue = 0;
$files_pictures = get_item_files_pictures($what,$n,$queue);
foreach ($files_pictures[image_url][$n] as $k=>$v) $a[pictures] .= '<img border="0" src="'.$v.'"><br />'.$files_pictures[image_description][$n][$k].'<br /><br />';
return parse_part('form_submitted_pictures.txt',$a);
}

##################################################################################
##################################################################################
##################################################################################

function link_login_form($n) {
global $s;
if ((!is_numeric($n)) AND ($s[LUG_u_n])) links_list();
$s[n] = $n;
page_from_template('link_edit_login.html',$s);
}

##################################################################################

function check_link_access_rights($n,$url,$password) {
global $s,$m;
if ($_SESSION['link_edit_'.$n]) return get_item_variables('l',$n);
if ((($n) or ($url)) AND (!$password) AND (!$s[LUG_u_n])) link_login_form($n);
if ($n) $link = get_item_variables('l',$n);
elseif ($url)
{ $url = trim($url);
  $q = dq("select * from $s[pr]links where url = '$url'",1);
  $link = mysql_fetch_assoc($q);
}
if (!$link[n]) problem($m[not_found]);
elseif ((!$password) AND ($s[LUG_u_n]!=$link[owner])) problem($m[no_auth_link]);
elseif (($password) AND ($password!=$link[password])) problem($m[wrong_pass]);
$_SESSION['link_edit_'.$n] = 1;
return $link;
}

##################################################################################
##################################################################################
##################################################################################

?>