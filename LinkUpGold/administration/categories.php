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

if ($_GET[action]=='categories_import') categories_import();

if ($_GET[what]) $what = $_GET[what]; else $what = $_POST[what]; 
if (!in_array($what,$s[item_types_short])) exit;
check_admin('categories_'.$s[items_types_words][$what]);

switch ($_GET[action]) {
case 'categories_home'				: categories_home($what,0);
case 'categories_multiple_create'	: categories_home($what,0);
case 'categories_tree'				: categories_tree($what,$_GET[bigboss]);
case 'category_edit'				: category_edit($what,$_GET);
case 'category_copy'				: category_copy($what,$_GET);
case 'category_delete'				: category_delete($what,$_GET);
case 'categories_import_form'		: categories_import_form($_GET[what]);
}
switch ($_POST[action]) {
case 'category_created'				: category_created($_POST);
case 'category_edited'				: category_edited($_POST);
case 'categories_import_count'		: import_count('c','','categories.php','categories_imported',$_POST);
case 'categories_imported'			: categories_imported($_POST);
}

########################################################################################
########################################################################################
########################################################################################

function categories_home($what,$n) {
global $s;
$x[n] = $n;
$word = $s[items_types_Words][$what];
ih();
echo $s[info];
echo page_title('Categories for '.$word);
category_create_edit_form($what,'category_created',$x);
echo '<form action="categories.php" method="get" name="form1">
<input type="hidden" name="what" value="'.$what.'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Edit/copy/delete an existing category</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align=center><select class="select10" name="n">'.categories_selected($what,0,1,1,1,0).'</select></td></tr>
<tr><td align=center>Action: 
<input type="radio" name="action" value="category_edit" checked>Edit 
<input type="radio" name="action" value="category_copy">Copy 
<input type="radio" name="action" value="category_delete">Delete
</td></tr>
<tr><td align="center"><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form><br />';
echo '<form action="categories.php" method="get" name="form1">
<input type="hidden" name="what" value="'.$what.'">
<input type="hidden" name="action" value="categories_multiple_create">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Create multiple categories</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">Create <select class="select10" name="n">';
for ($x=1;$x<=100;$x++) echo '<option value="'.$x.'">'.$x.'</option>';
echo '</select> categories at once <input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table><br /></form>';
ift();
}

########################################################################################

function category_create_edit_form($what,$action,$in) {
global $s;
//foreach ($in as $k=>$v) echo "$k - $v<br />";
if (!$n_cats) $n_cats = 1;
if ($in[n])
{ $data = get_category_variables($in[n]);
  $data = stripslashes_array($data);
  foreach ($data as $k=>$v) $data[$k] = str_replace('<','&lt;',str_replace('>','&gt;',$v));
  if ($data[submithere]) $submithere = ' checked';
  if ($data[recip]) $recip = ' checked';
  $q = dq("select count(*) from $s[pr]cats where use_for = '$what' AND parent = '$in[n]'",1);
  $has_subcats = mysql_fetch_row($q);
  if ($data[visible])
  { $visible = ' checked';
    if ($has_subcats[0]) $visible_info = '<span class="text10">If you hide this category, all its subcategories will be hidden too.</span>';
  }
  if ($data[alias_of]) $parent = categories_selected($what,$data[parent],1,1,1,0); else $parent = categories_selected($what,$data[parent],1,1,0,0);
  if ($action=='category_created') { $info2 = 'Create a new category'; $data[pagename] = $data[rewrite_url] = ''; }
  else $info2 = 'Edit selected category';
}
else
{ $submithere = $visible = ' checked';
  $info2 = 'Create a new category';
  $parent = categories_selected($what,0,1,1,1,0);
  if ($what=='v') $data[rss_read_interval] = $s[v_load_interval_minutes];
  else $data[rss_read_interval] = $s[n_load_interval_minutes];
}
for ($x=1;$x<=3;$x++) $ads[$x] = select_ads($data["ad$x".'n']);

$sim = explode(' ',str_replace('_','',$data[similar]));
$max_similar = $s[$what.'_max_simcats'] - 1;

for ($x=0;$x<=$max_similar;$x++) $similar .= all_categories_select('similar['.$x.']',$sim[$x]).'<br />';
$tmpl_cat = category_template_select('category.html',$data[tmpl_cat]);
$tmpl_one = category_template_select($s[item_types_words][$what].'_a.txt',$data[tmpl_one]);
$tmpl_det = category_template_select($s[item_types_words][$what].'_details.html',$data[tmpl_det]);
$word = $s[items_types_words][$what];
$alias_of = all_categories_select('alias_of',$data[alias_of]);

for ($x=1;$x<=10;$x++)
{ if ($data[cat_group]==$x) $selected = ' selected'; else $selected = '';
  $cat_group .= '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
}

if ($_GET[action]=='categories_multiple_create') { $number_cats = $_GET[n]; $label_category_name = 'Names of categories'; }
else { $number_cats = 1; $label_category_name = 'Category name'; }

echo '
<form enctype="multipart/form-data" action="categories.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="'.$action.'">
<input type="hidden" name="what" value="'.$what.'">
<input type="hidden" name="n" value="'.$in[n].'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">'.$info2.'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" valign="top" nowrap>Parent category</td>
<td align="left" valign="top"><select class="select10" name="parent"><option value=0>None</option>'.$parent.'</select></td>
</tr>';
for ($x=1;$x<=$number_cats;$x++)
{ if ($x>1) unset($label_category_name);
  echo '<tr>
  <td align="left" valign="top" nowrap>'.$label_category_name.'&nbsp;</td>
  <td align="left" valign="top"><input class="field10" style="width:650px;" name="name[]" maxlength=255 value="'.$data[name].'"></td>
  </tr>';
}
echo '<tr>
<td align="left" valign="top">Group<br /><span class="text10">You can group categories on the home page by your needs.</span></td>
<td align="left" valign="top"><select class="select10" name="cat_group"><option value=0>None</option>'.$cat_group.'</select></td>
</tr>';
if (!$in[n])
{ echo '<tr>
  <td align="left" valign="top">Alias of category</td>
  <td align="left" valign="top">'.$alias_of.'</td>
  </tr>';
}
elseif ($data[alias_of])
{ echo '<tr>
  <td align="left" valign="top">Alias of category</td>
  <td align="left" valign="top">'.$alias_of.'</td>
  </tr>';
}
echo '<tr>
<td align="left" valign="top" nowrap>Description</td>
<td align="left" valign="top"><textarea class="field10" name="description" style="width:650px;height:250px;">'.$data[description].'</textarea></td>
</tr>';
if (!$data[alias_of])
{ if (!$in[n]) echo '<tr>
  <td align="center" valign="top" colspan="2" nowrap>All the fields below are not applicable for categories marked as Alias</td>
  </tr>';
  if ($what=='n') echo '  <tr>
  <td align="left" valign="top" nowrap>RSS URL to get news for this category </td>
  <td align="left" valign="top"><input class="field10" style="width:650px;" name="rss_url" value="'.$data[rss_url].'"></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Import these news once a </td>
  <td align="left" valign="top"><input class="field10" style="width:100px" name="rss_read_interval" value="'.$data[rss_read_interval].'"> minutes<br /><span class="text10">Enter 0 to never import news to this category automatically. In this case make sure to create a crontab command to import these news.<br /></span></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Maximum number of items in this category.<br /><span class="text10">It deletes the oldest items if this maximum has been reached.</span></td>
  <td align="left" valign="top"><input class="field10" style="width:100px" name="max_items" value="'.$data[max_items].'"><br /><span class="text10">Enter 0 to never delete old content.</span></td>
  </tr>';
  elseif ($what=='v') echo '<tr>
  <td align="left" valign="top" nowrap>Keywords to use to show videos from youtube.com </td>
  <td align="left" valign="top"><input class="field10" style="width:650px;" name="youtube_keywords" value="'.$data[youtube_keywords].'"></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Maximum number of items in this category.<br /><span class="text10">It deletes the oldest items if this maximum has been reached.</span></td>
  <td align="left" valign="top"><input class="field10" style="width:100px" name="max_items" value="'.$data[max_items].'"><br /><span class="text10">Enter 0 to never delete old content.</span></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Import videos once a </td>
  <td align="left" valign="top"><input class="field10" style="width:100px" name="rss_read_interval" value="'.$data[rss_read_interval].'"> minutes<br /><span class="text10">Enter 0 to never import videos to this category automatically. In this case make sure to create a crontab command to import these videos.<br /></span></td>
  </tr>';
  echo '<tr>
  <td align="left" valign="top" nowrap>Category icon </td>
  <td align="left" valign="top"><input type="file" class="field10" style="width:650px;" maxlength=255 name="image2" value="'.$data[image2].'"></td>
  </tr>';
  if ($data[n])
  { if ($data[image2]) $image = '<img border="0" src="'.$data[image2].'">'; else $image = 'Icon not defined. The default icons defined in configuration are used.';
    echo '<tr>
    <td align="left" valign="top" nowrap>Current image</td>
    <td align="left" valign="top">'.$image.'</td>
    </tr>';
  }
  echo '<tr>
  <td align="left" valign="top" nowrap>Image on the category page</td>
  <td align="left" valign="top"><input type="file" class="field10" style="width:650px;" maxlength=255 name="image1" value="'.$data[image1].'"></td>
  </tr>';
  if ($data[n])
  { if ($data[image1]) $image = '<img border="0" src="'.$data[image1].'">'; else $image = 'None';
    echo '<tr>
    <td align="left" valign="top" nowrap>Current image</td>
    <td align="left" valign="top">'.$image.'</td>
    </tr>';
  }
  echo '<tr>
  <td align="left" valign="top" nowrap>Meta keywords</td>
  <td align="left" valign="top"><textarea class="field10" name="m_keyword" style="width:650px;height:250px;">'.$data[m_keyword].'</textarea></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Meta description</td>
  <td align="left" valign="top"><textarea class="field10" name="m_desc" style="width:650px;height:250px;">'.$data[m_desc].'</textarea></td>
  </tr>
  <!--<tr>
  <td align="left" valign="top" nowrap>Keywords for AdManager<br /></td>
  <td align="left" valign="top"><input class="field10" style="width:650px;" name="ad_manager" maxlength=255 value="'.$data[ad_manager].'"><br /><span class="text10">Example: cars,audio,video</span></td>
  </tr>-->';
  echo user_defined_items_form('c_'.$what,$data);
  echo '<tr>
  <td align="left" valign="top" nowrap>Category page template</td>
  <td align="left" valign="top"><select class="select10" name="tmpl_cat">'.$tmpl_cat.'</select><br /><span class="text10">Template which will be used for the whole category page</span></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>One item template</td>
  <td align="left" valign="top"><select class="select10" name="tmpl_one">'.$tmpl_one.'</select><br /><span class="text10">Template which will be used for each item in this category</span></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Detail page template</td>
  <td align="left" valign="top"><select class="select10" name="tmpl_det">'.$tmpl_det.'</select><br /><span class="text10">Template which will be used for all details pages for items from this category</span></td>
  </tr>';
  for ($x=1;$x<=3;$x++)
  echo '<tr>
  <td align="left" valign="top" nowrap>Ad #'.$x.'</td>
  <td align="left" valign="top"><select class="select10" name="ad'.$x.'"><option value=0>None</option>'.$ads[$x].'</select></td>
  </tr>';
  echo '<tr>
  <td align="left" valign="top" nowrap>Similar categories</td>
  <td align="left" valign="top">'.$similar.'</td>
  </tr>';
  if (($what=='l') OR ($what=='a') OR ($what=='b')) echo '<tr>
  <td align="left" valign="top" nowrap>RSS URL to read</td>
  <td align="left" valign="top"><input class="field10" style="width:650px;" name="rss_url" value="'.$data[rss_url].'"></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Items to take from this URL</td>
  <td align="left" valign="top"><input class="field10" style="width:100px" name="rss_items" value="'.$data[rss_items].'"></td>
  </tr>
  ';
  if (($what=='l') OR ($what=='a') OR ($what=='b')) echo '<tr>
  <td align="left" valign="top" nowrap>Allow submissions</td>
  <td align="left" valign="top"><input type="checkbox" name="submithere" value="1"'.$submithere.'></td>
  </tr>';
  if ($what=='l') echo '<tr>
  <td align="left" valign="top" nowrap>Require reciprocal link </td>
  <td align="left" valign="top"><input type="checkbox" name="recip" value="1"'.$recip.'><span class="text10">If checked, links without a link back to you will be rejected. If you require recip for links in all categories, use checkfield in your Configuration instead.</span></td>
  </tr>';
  
  echo '<tr>
  <td align="left" valign="top" nowrap>Address to show in the center of the map on category pages </td>
  <td align="left" valign="top"><input class="field10" style="width:550px" name="map_address" maxlength=255 value="'.$data[map_address].'"></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Map co-ordinates </td>
  <td align="left" valign="top">Latitude: <input class="field10" style="width:150px" name="latitude" maxlength=255 value="'.$data[latitude].'"> Longitude: <input class="field10" style="width:150px" name="longitude" maxlength=255 value="'.$data[longitude].'"><br><span class="text10">Keep these fields empty to automatically count these values for the address entered above<br></span></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Map zoom </td>
  <td align="left" valign="top"><select class="field10" name="map_zoom">';
  unset($selected);
  for ($x=1;$x<=18;$x++) { if ($data[map_zoom]==$x) $selected = ' selected'; else $selected = ''; echo '<option value="'.$x.'"'.$selected.'>'.$x.'</option>'; }
  echo '</select></td>
  </tr>';
  if ($data[country])
  echo '<tr>
  <td align="left" valign="top" nowrap>Location details </td>
  <td align="left" valign="top">Country: '.$data[country].'<br>Region: '.$data[region].'<br>City: '.$data[city].'<br></td>
  </tr>';
  
  if ($what=='l') echo '<tr>
  <td align="left" valign="top" nowrap>URL at dmoz.org to import in regular intervals </td>
  <td align="left" valign="top"><input class="field10" style="width:550px" name="dmoz_url" maxlength=255 value="'.$data[dmoz_url].'"><br><span class="text10">The following crontab URL can be used to import links to this category from the URL defined above:<br>'.$s[site_url].'/rebuild.php?word='.$s[secretword].'&action=import_dmoz&c='.$data[n].'<br></span></td>
  </tr>';

  echo '<tr>
  <td align="left" valign="top" nowrap>Only registered users can see this category </td>
  <td align="left" valign="top"><input type="checkbox" name="users_only_cat" value="1"'; if ($data[users_only_cat]) echo ' checked'; echo '></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Only registered users can see details of '.$word.' from this category </td>
  <td align="left" valign="top"><input type="checkbox" name="users_only_items" value="1"'; if ($data[users_only_items]) echo ' checked'; echo '></td>
  </tr>';
  if (($in[n]) AND (!$data[visible]) AND ($has_subcats[0]))
  echo '
  <script type="text/javascript">
  function showHide(chkBox) 
  { if(chkBox.checked==true) { document.getElementById("vrstva").style.visibility = \'visible\'; document.getElementById("vrstva1").style.visibility = \'visible\'; }
    else { document.getElementById("vrstva").style.visibility = \'hidden\'; document.getElementById("vrstva1").style.visibility = \'hidden\'; }
  }
  </script>
  <tr>
  <td align="left" valign="top" nowrap>Is visible</td>
  <td align="left" valign="top"><input type="checkbox" name="visible" onClick=\'showHide(this)\' value="1"'.$visible.'></td>
  </tr>
  <tr>
  <td align="left" valign="top"><div id="vrstva" style="visibility:hidden">Enable also all subcategories of this category </div></td>
  <td align="left" valign="top"><div id="vrstva1" style="visibility:hidden"><input type="checkbox" name="visible_subcats" value="1" checked></div></td>
  </tr>';
  else echo '<tr>
  <td align="left" valign="top" nowrap>Is visible</td>
  <td align="left" valign="top"><input type="checkbox" name="visible" value="1"'.$visible.'>'.$visible_info.'</td>
  </tr>';
  echo '<tr>
  <td align="left" valign="top" nowrap>Show this category in the top menu</td>
  <td align="left" valign="top"><input type="checkbox" name="in_menu" value="1"'; if ($data[in_menu]) echo ' checked'; echo '></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Hide this category in the list of categories on home page </td>
  <td align="left" valign="top"><input type="checkbox" name="hide_home" value="1"'; if ($data[hide_home]) echo ' checked'; echo '></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Name of static html page&nbsp;<br />(without extension)</td>
  <td align="left" valign="top"><input class="field10" style="width:650px;" name="pagename" value="'.$data[pagename].'"><br /><span class="text10">This is used if you have HTML Plugin and use the option "Build static pages". It can contain only English letters, numbers and these characters: - _ . If you let it blank, the script will generate the value automatically by the Title field.</span></td>
  </tr>
  <tr>
  <td align="left" valign="top" nowrap>Rewrite URL</td>
  <td align="left" valign="top"><input class="field10" style="width:650px;" name="rewrite_url" value="'.$data[rewrite_url].'"><br /><span class="text10">This is used if you have HTML Plugin with the "Rewrite" option enabled. It can contain only English letters, numbers and these characters: - _ /. If you let it blank, the script will generate the value automatically by the Title field.</span></td>
  </tr>
  ';
}
echo '<tr>
<td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td>
</tr></table></form>
<br /><br />';
}

##################################################################################
##################################################################################
##################################################################################

function category_created($in) {
global $s;
$in[use_for] = $in[what];
$all_user_items_list = get_all_user_items_list('c_'.$in[use_for]);
$all_user_items_values = get_all_user_items_values('c_'.$in[use_for]);
foreach ($all_user_items_list as $k=>$v)
{ if (($v[kind]=='text') OR ($v[kind]=='textarea') OR ($v[kind]=='htmlarea')) $value_texts[$v[item_n]] = replace_once_text($in[user_item]['user_item_'.$v[item_n]]);
  elseif ($v[kind]=='checkbox') $value_codes[$v[item_n]] = $in[user_item]['user_item_'.$v[item_n]];
  else
  { $value_codes[$v[item_n]] = $x = $in[user_item]['user_item_'.$v[item_n]];
    $value_texts[$v[item_n]] = $all_user_items_values[$v[item_n]][$x];
  }
}
foreach ($in[name] as $k=>$v) if (trim($v)) $names[] = trim($v);
if (!$in[name][0])
{ $s[info] = info_line('Category name is required. Please try again.');
  categories_home($in[use_for],$in);
}
if ($in[parent])
{ $q = dq("select * from $s[pr]cats where use_for = '$in[use_for]' AND n = '$in[parent]'",1);
  $parent = mysql_fetch_assoc($q);
  if (!$parent[visible]) $in[visible] = 0;
  if (($parent[alias_of]) AND (!$in[alias_of])) { $s[info] = info_line('If a parent category is an alias, also its child category must be an alias. Please try again.'); categories_home($in); }
}
else { $parent[level] = 0; $parent[path_text] = ''; }
$level = $parent[level] + 1;

for ($x=1;$x<=3;$x++)
{ $y = "ad$x";
  if ($in[$y])
  { $q = dq("select html from $s[pr]ads where n = '$in[$y]'",1);
    $b = mysql_fetch_row($q);
    $ad[$x] = $b[0];
  }
}
foreach ($in[similar] as $k=>$v) if ($v) $sim[] = '_'.$v.'_'; $similar = trim(implode(' ',$sim));
if ($in[alias_of])
{ $q = dq("select items,visible from $s[pr]cats where n = '$in[alias_of]'",1);
  $x = mysql_fetch_assoc($q); $items = $x[items]; $in[visible] = $x[visible];
}

$in = replace_array_text($in);
foreach ($in as $k=>$v) $in[$k] = str_replace('&lt;','<',str_replace('&gt;','>',$v));
$in[description] = refund_html($in[description]);

foreach ($names as $k=>$v)
{ dq("insert into $s[pr]cats values (NULL,'$in[parent]','$level','$in[alias_of]','$v','$in[description]','','','$in[m_keyword]','$in[m_desc]','$ad[1]','$ad[2]','$ad[3]','$in[ad1]','$in[ad2]','$in[ad3]','$items','$path_text','$path_n','$in[submithere]','0','$similar','$in[recip]','$in[pagename]','$in[rewrite_url]','$in[tmpl_cat]','$in[tmpl_one]','$in[tmpl_det]','$in[ad_manager]','$in[visible]','$in[users_only_cat]','$in[users_only_items]','$in[use_for]','$in[cat_group]','$in[in_menu]','$in[hide_home]','0','0','$in[rss_url]','$in[rss_items]','$in[rss_read_interval]','0','0','$in[youtube_keywords]','$in[max_items]','$in[map_address]','','','7','','','','','$in[dmoz_url]')",1);
  $n = mysql_insert_id();
  get_geo_data($in[map_address],'c',$n);
  update_category_paths($n);
  update_disabled_categories($in[use_for]);
  if (!$in[alias_of]) add_update_user_items('c_'.$in[use_for],$n,$all_user_items_list,$value_codes,$value_texts);
  if ($in[parent]) $bigboss = find_bigboss_category($in[parent]); else $bigboss = $n;
  dq("update $s[pr]cats set bigboss = '$bigboss' where use_for = '$in[use_for]' AND n = '$n'",1);
  $info[] = 'Category "'.$v.'" has been created';
  if (count($names)>1) $numbers[] = $n; else $numbers = $n;
}
if ($_FILES[image1][name]) upload_category_image('c',$numbers,1,$_FILES[image1][name],$_FILES[image1][tmp_name],$old[image1]);
if ($_FILES[image2][name]) upload_category_image('c',$numbers,2,$_FILES[image2][name],$_FILES[image2][tmp_name],$old[image2]);

if (count($info)==1) $s[info] = info_line($info[0]);
else $s[info] = info_line('Multiple categories created',implode('<br />',$info));
categories_home($in[use_for],0);
}

##################################################################################

function category_copy($what,$in) {
global $s;
ih();
echo $s[info];
category_create_edit_form($what,'category_created',$in);
ift();
}

##################################################################################

function category_edit($what,$in) {
global $s;
ih();
echo $s[info];
category_create_edit_form($what,'category_edited',$in);
ift();
}

#################################################################################

function category_edited($in) {
global $s;
//foreach ($in as $k=>$v) echo "$k - $v<br />";exit;

$in[name] = $in[name][0];
$all_user_items_list = get_all_user_items_list('c_'.$in[what]);
$all_user_items_values = get_all_user_items_values('c_'.$in[what]);
foreach ($all_user_items_list as $k=>$v)
{ if (($v[kind]=='text') OR ($v[kind]=='textarea') OR ($v[kind]=='htmlarea')) $value_texts[$v[item_n]] = replace_once_text($in[user_item]['user_item_'.$v[item_n]]);
  elseif ($v[kind]=='checkbox') $value_codes[$v[item_n]] = $in[user_item]['user_item_'.$v[item_n]];
  else
  { $value_codes[$v[item_n]] = $x = $in[user_item]['user_item_'.$v[item_n]];
    $value_texts[$v[item_n]] = $all_user_items_values[$v[item_n]][$x];
  }
}

if (!$in[name])
{ $s[info] = info_line('Category name is required. Please try again.');
  category_edit($in[what],$in);
}
if ($in[parent])
{ if ($in[n]==$in[parent]) { $s[info] = info_line('Category may not be a self-parent'); category_edit($in[what],$in); }
  $parent = get_category_variables($in[parent]);
  $x = '_'.$in[n].'_';
  if (strstr($parent[path_n],$x)) { $s[info] = info_line('Category may not be paramount over itself'); category_edit($in[what],$in); }
  $bigboss = find_bigboss_category($in[parent]);
  if (!$parent[visible]) $in[visible] = 0;
}
else { $parent[level] = 0; $parent[path_text] = ''; $bigboss = $in[n]; }
$level = $parent[level] + 1;
$old = get_category_variables($in[n]);
if ($old[parent]!=$in[parent]) $fix_both_paths_cat_and_subcats = 1;
elseif ($old[name]!=$in[name]) $fix_text_path_cat_and_subcats = 1; //repair_path($in[what],$in[n],$path_text);
if (!$in[alias_of])
{ if ((!$old[visible]) AND ($in[visible]))
  { manage_subcategories_of_category(1,$in[what],$in[n]);
    dq("update $s[pr]cats set visible = '1' where alias_of = '$in[n]'",1);
    $info = 'You have enabled at least one category.';
  }
  if (($old[visible]) AND (!$in[visible]))
  { manage_subcategories_of_category(0,$in[what],$in[n]);
    dq("update $s[pr]cats set visible = '0' where alias_of = '$in[n]'",1);
    $info = 'You have disabled at least one category.';
  }
}

for ($x=1;$x<=3;$x++)
{ $y = "ad$x";
  if ($in[$y])
  { $q = dq("select html from $s[pr]ads where n = '$in[$y]'",1);
    $b = mysql_fetch_row($q);
    $ad[$x] = $b[0];
  }
}
foreach ($in[similar] as $k=>$v) if ($v) $sim[] = '_'.$v.'_'; $similar = trim(implode(' ',$sim));
if ($in[alias_of])
{ $x = get_category_variables($in[alias_of]);
  $items = ",items = '$x[items]'"; $in[visible] = $x[visible];
  dq("delete from $s[pr]usit_values WHERE use_for = '".'c_'.$in[what]."' AND n = '$in[n]'",1);
}

$in = replace_array_text($in);
foreach ($in as $k=>$v) $in[$k] = str_replace('&lt;','<',str_replace('&gt;','>',$v));
$in[description] = refund_html($in[description]);

dq("update $s[pr]cats set parent = '$in[parent]', level = '$level', alias_of = '$in[alias_of]', name = '$in[name]', description = '$in[description]', m_keyword = '$in[m_keyword]', m_desc = '$in[m_desc]', ad1 = '$ad[1]', ad2 = '$ad[2]', ad3 = '$ad[3]', ad1n = '$in[ad1]', ad2n = '$in[ad2]', ad3n = '$in[ad3]', submithere = '$in[submithere]', bigboss = '$bigboss', similar = '$similar', recip = '$in[recip]', ad_manager = '$in[ad_manager]', tmpl_cat = '$in[tmpl_cat]', tmpl_one = '$in[tmpl_one]', tmpl_det = '$in[tmpl_det]', users_only_cat = '$in[users_only_cat]', users_only_items = '$in[users_only_items]', visible = '$in[visible]', pagename = '$in[pagename]', rewrite_url = '$in[rewrite_url]', cat_group = '$in[cat_group]', in_menu = '$in[in_menu]', hide_home = '$in[hide_home]', rss_url = '$in[rss_url]', rss_items = '$in[rss_items]', rss_read_interval = '$in[rss_read_interval]', youtube_keywords = '$in[youtube_keywords]', max_items = '$in[max_items]', map_address = '$in[map_address]', map_zoom = '$in[map_zoom]', dmoz_url = '$in[dmoz_url]' $items where use_for = '$in[what]' AND n = '$in[n]'",1);
if ($_FILES[image1][name]) upload_category_image('c',$in[n],1,$_FILES[image1][name],$_FILES[image1][tmp_name],$old[image1]);
if ($_FILES[image2][name]) upload_category_image('c',$in[n],2,$_FILES[image2][name],$_FILES[image2][tmp_name],$old[image2]);
update_disabled_categories($in[what]);
get_geo_data($in[map_address],'c',$in[n]);
if (!$in[alias_of])
{ update_en_cats_in_items($in[what],get_items_in_category($in[what],$in[n]));
  add_update_user_items('c_'.$in[what],$in[n],$all_user_items_list,$value_codes,$value_texts);
}
update_category_paths($in[n]);
if ($fix_both_paths_cat_and_subcats)
{ $q = dq("select * from $s[pr]cats where path_n like '%\_$in[n]\_%' order by level",1);
  while ($x=mysql_fetch_assoc($q)) { update_category_paths($x[n]); $items_in_cats_update[] = $x[n]; }
  if ($items_in_cats_update[0]) // repair path for links, articles
  { $table = $s[item_types_tables][$in[what]];
    $query = "c like '%\_".implode("\_%' or c like '%\_",$items_in_cats_update)."\_%'";
    $q = dq("select * from $table where $query",1);
    while ($item = mysql_fetch_assoc($q))
    { $array = explode(' ',str_replace('_','',$item[c]));
      $paths = categories_edited($array);
      dq("update $table set c_path = '$paths[categories_path]' where n = '$item[n]'",1);
    }
  }
  recount_items_in_category_up($in[n]);
  recount_items_in_category_up($old[parent]);
}
elseif ($fix_text_path_cat_and_subcats)
{ $q = dq("select * from $s[pr]cats where path_n like '%\_$in[n]\_%' order by level",1);
  while ($x=mysql_fetch_assoc($q)) update_category_paths($x[n]);
}
$word = $s[items_types_words][$in[what]];
if ($info) $info = '<br />'.$info.' Therefore now you have to go to <a href="rebuild.php?action=reset_rebuild_home"><b>reset/rebuild</b></a> and run function "Recount all '.$word.'"';
$s[info] = info_line('Category has been edited. '.$info);
category_edit($in[what],$in);
}

#######################################################################################

function recount_items_in_category_up($c) {
global $s;
while ($c)
{ $cat = get_category_variables($c);
  if (!$cat) return false;
  $table = $s[item_types_tables][$cat[use_for]];
  $where = get_where_fixed_part('',0,$c,$s[cas]);
  $q = dq("select count(*) from $table where $where",1);
  $x = mysql_fetch_row($q);
  dq("update $s[pr]cats set items = '$x[0]' where n = '$cat[n]'",1);
  $c = $cat[parent];
}
}

#######################################################################################

function category_delete($what,$in) {
global $s;
$table = $s[item_types_tables][$what];
$items = $s[items_types_words][$what];

if (!$in[ok])
{ $q = dq("select count(*) from $s[pr]cats where parent = '$in[n]'",1);
  $x = mysql_fetch_row($q); if ($x[0]) $has_subcats = 1;
  if (!$has_subcats)
  { $q = dq("select count(*) from $table where c = '_$in[n]_'",1);
    $data = mysql_fetch_row($q);
  }
  if (($data[0]) OR ($has_subcats))
  { ih();
    echo '<form action="categories.php" method="get" name="form1">
    <input type="hidden" name="action" value="category_delete">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="n" value="'.$in[n].'">
    <input type="hidden" name="what" value="'.$what.'">
    <br /><table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
    <tr><td align="center" width="100%">
    <table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
    <tr><td align="center"><span class="text13a_bold">';
    if ($has_subcats) echo 'Selected category has one or more subcategories. These subcategories and all '.$items.' that are listed only in this category and the subcategories will be removed as well. Continue?';
    else echo 'Total of '.$data[0].' '.$items.' are listed in this category only. If you delete it, these '.$items.' will be deleted as well. Continue?';
    echo '</span></td></tr>
    <tr><td align="center"><input type="submit" name="submit" value="Yes, delete it" class="button10"></td></tr>
    </table></td></tr></table></form>';
    ift();
  }
}

$category = get_category_variables($in[n]);

$q = dq("select n from $s[pr]cats where path_n like '%\_$in[n]\_%'",1);
while ($x=mysql_fetch_assoc($q)) $x1[] = $x[n];
$query = "c_path like '%\_".implode("\_%' or c_path like '%\_",$x1)."\_%'";

dq("delete from $s[pr]cats where path_n like '%\_$in[n]\_%'",1);
dq("delete from $table where c = '_$in[n]_'",1);
$q = dq("select n from $table where $query",1);
while ($x=mysql_fetch_assoc($q)) $affected_items[] = $x[n];
if ($affected_items[0])
{ $x = my_implode('n','or',$affected_items);
  $q = dq("select n,c from $table where $x",1);
  while ($item = mysql_fetch_assoc($q))
  { $array = explode(' ',str_replace('_','',$item[c]));
    $paths = categories_edited($array);
    dq("update $table set c = '$paths[categories]', c_path = '$paths[categories_path]' where n = '$item[n]'",1);
    unset($item_c,$item_c_path);
  }
  update_en_cats_in_items($what,$affected_items);
}

if ($category[parent]) recount_items_in_category_up($category[parent]);
dq("delete from $s[pr]cats where alias_of = '$in[n]' or n = '$in[n]'",1);
if ((trim($category[image1])) AND (strstr($category[image1],"$s[site_url]/images/"))) unlink(str_replace($s[site_url],$s[phppath],$category[image1]));
if ((trim($category[image2])) AND (strstr($category[image2],"$s[site_url]/images/"))) unlink(str_replace($s[site_url],$s[phppath],$category[image2]));
$s[info] = info_line('Selected category has been deleted');
if ($in[backto]=='categories_tree') categories_tree($what);
else categories_home($what,0);
}

#######################################################################################
#######################################################################################
#######################################################################################

function manage_subcategories_of_category($action,$use_for,$in_n) {
global $s;
dq("update $s[pr]cats set visible = '$action' where n = '$in_n'",1);
$n[0] = $list[] = $in_n;
while (count($n))
{ $k = array_rand($n);
  $q = dq("select n from $s[pr]cats where parent = '$n[$k]'",1);
  while ($x = mysql_fetch_row($q))
  { dq("update $s[pr]cats set visible = '$action' where n = '$x[0]'",1);
    $n[] = $list[] = $x[0];
  }
  unset($n[$k]);
}
update_disabled_categories($use_for); // must be here because update_en_cats_in_items() needs it
$list = array_unique($list);
foreach ($list as $k=>$v) $kkk[] = get_items_in_category($use_for,$v);
foreach ($kkk as $k=>$v) if (is_array($v)) foreach ($v as $k1=>$v1) $items[] = $v1;
update_en_cats_in_items($use_for,$items);
}

#######################################################################################

function update_disabled_categories($use_for) {
global $s;
dq("delete from $s[pr]cats_disabled where use_for = '$use_for'",1);
$q = dq("select n from $s[pr]cats where use_for = '$use_for' AND visible = '0'",1);
while ($x = mysql_fetch_row($q)) dq("insert into $s[pr]cats_disabled values ('$use_for','$x[0]')",1);
}

#######################################################################################
#######################################################################################
#######################################################################################

function find_bigboss_category($category) {
global $s;
while ($category)
{ $old_category = $category;
  $y = get_category_variables($old_category);
  $category = $y[parent];
}
return $old_category;
}

#######################################################################################
#######################################################################################
#######################################################################################

function categories_tree($what,$first_level) {
global $s;
//if ($first_level) $first_level = " and bigboss = '$first_level'"; else { $first_level = " and level = '1'"; $only_first_level = 1; }
$q = dq("select * from $s[pr]cats where use_for = '$what' $first_level order by path_text",1);
while ($a=mysql_fetch_assoc($q))
{ set_time_limit(300);
  if (time()>($time1+10)) { $time1=time(); echo str_repeat (' ',4000); flush(); }
  if (!$a[visible]) $hidden = ' <b><font color="red">i</font></b>'; else $hidden = '';
  $mo = ''; for ($i=1;$i<$a[level];$i++) $mo .= '&raquo;';
  $a[path_text] = preg_replace("/<%.+%>/",'',$a[path_text]);
  $a[path_text] = preg_replace("/<%.+$/",$a[name],$a[path_text]);
  if (!$a[path_text]) $a[path_text] = $a[name];
  if ($a[alias_of]) $a[path_text] = $s[alias_pref].$a[path_text].$s[alias_after];
  if ($only_first_level) $categories .= "$mo <a href=\"categories.php?action=categories_tree&bigboss=$a[n]&what=$what\">$a[path_text]</b></a> ";
  else
  { if ($a[level]==1) $categories .= "<br>$mo <a href=\"categories.php?action=category_edit&n=$a[n]&what=$a[use_for]\"><big><big>$a[path_text]</big></big></a> ";
    elseif ($a[level]==2) $categories .= "$mo <a href=\"categories.php?action=category_edit&n=$a[n]&what=$a[use_for]\"><big>$a[path_text]</big></a> ";
    else $categories .= "$mo <a href=\"categories.php?action=category_edit&n=$a[n]&what=$a[use_for]\">$a[path_text]</a> ";
  }
  $categories .= "#$a[n] ";
  $categories .= "$hidden - 
  <a href=\"categories.php?action=category_copy&n=$a[n]&what=$what\">Copy</a> - ";
  $categories .= '<a href="'.$s[items_types_words][$what].'.php?action='.$s[items_types_words][$what].'_searched&category='.$a[n].'">'.$s[items_types_Words][$what].'</a>';
  if ($a[level]==1) $categories .= ' - <a href="'.$s[items_types_words][$what].'.php?action='.$s[items_types_words][$what].'_searched&bigboss='.$a[n].'">'.$s[items_types_Words][$what].' incl. subcategories</a>';
  $categories .= " - <a href=\"javascript: go_to_delete('Are you sure?','categories.php?action=category_delete&n=$a[n]&what=$what&backto=categories_tree')\" title=\"Delete this category\">x</a>";
  $categories .= '<br />';
}
$items = $s[items_types_Words][$what];
ih();
echo $s[info];
echo page_title('Categories for '.$s[items_types_Words][$what]);
echo '<table border="0" width="99%" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">All Categories</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align=left><span class="text10">'.stripslashes($categories).'</span></td></tr>
</table></td></tr></table>
<br />
<b><font color="red">i</font></b> - Invisible category<br />
<font color="blue">#25</font> - Category n<br />
x - Delete category<br />';
ift();
}

#############################################################################
#############################################################################
#############################################################################

function upload_category_image($what,$n,$file_n,$original_name,$tmp_name,$old_file) {
global $s,$m;
$folder_name = $s[items_types_words][$what];
$extension = str_replace('.','',strrchr($original_name,'.'));
$working_name = "$s[phppath]/images/$folder_name/".md5(microtime()).'.'.$extension;
if (!is_uploaded_file($tmp_name)) return array('','','','Unable to upload file '.$original_name);
if (file_exists($working_name)) unlink($working_name);
move_uploaded_file($tmp_name,$working_name);

if (is_array($n)) $numbers = $n; else $numbers[0] = $n;
foreach ($numbers as $k=>$n)
{ $file_name = "$n-$file_n-$s[cas].$extension";
  $file_path = "$s[phppath]/images/$folder_name/$file_name";
  if (trim($old_file)) unlink(str_replace($s[site_url],$s[phppath],$old_file));
  copy($working_name,$file_path); $file_url = "$s[site_url]/images/$folder_name/$file_name";
  if (file_exists($file_path)) 
  { chmod($file_path,0644);
    dq("update $s[pr]cats set image$file_n = '$file_url' where n = '$n'",1);
  }
}
unlink($working_name);
//return array('url'=>$file_url,'extension'=>$extension,'size'=>$file_size,'problem'=>$problem);
}

#############################################################################
#############################################################################
#############################################################################

function categories_import() {
global $s;

ih();

echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td colspan="4" class="common_table_top_cell">Choose Type of Categories</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';

foreach ($s[items_types_Words] as $k=>$v) echo '<tr><td align="center"><a href="categories.php?action=categories_import_form&what='.$k.'">'.$v.'</a></td></tr>';

echo '</table>
</td></tr></table>';
ift();
}

#############################################################################

function categories_import_form($what) {
global $s;

ih();

$q = dq("select * from $s[pr]usit_list where use_for = 'c_$what' order by rank",1);
while ($x = mysql_fetch_assoc($q)) $all_user_items_list[$x[item_n]] = $x[description];
$pocet = 11 + mysql_num_rows($q);

echo '<form ENCTYPE="multipart/form-data" action="categories.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="categories_import_count">
<input type="hidden" name="what" value="'.$what.'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td colspan="4" class="common_table_top_cell">Import Categories for '.$s[items_types_Words][$what].'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center" colspan="4">Structure of your file<br />
<span class="text10">Make sure to read Instructions below<br /></span>
</td></tr>';
for ($x=1;$x<=$pocet;$x++)
{ if ($x%2) echo '<tr>';
  echo '<td align="left" nowrap>Rank #'.$x.'</td>
  <td align="left"><select class="select10" name="rank['.$x.']">
  <option value="0">None</option>
  <option value="title">Title</option>
  <option value="parent">Parent category</option>
  <option value="description">Description</option>
  <option value="image1">Image 1 URL</option>
  <option value="image2">Image 2 URL</option>
  <option value="m_keyword">Meta keywords</option>
  <option value="m_desc">Meta description</option>
  <option value="pagename">Name of static page</option>
  <option value="rewrite_url">Rewrite URL</option>
  <option value="tmpl_cat">Template - category</option>
  <option value="tmpl_one">Template - single item</option>
  <option value="tmpl_det">Template - details page</option>';
  foreach ($all_user_items_list as $k=>$v) echo '<option value="user_item_'.$k.'">'.$v.'</option>';
  echo '</select>&nbsp;</td>';
  if (!$x%2) echo '</tr>';
}
echo '<tr>
<td align="left" colspan="4" nowrap>Separator (it separates individual values on the same line) 
<select class="select10" name="separator">
<option value="|">|</option><option value=",">,</option><option value=";">;</option>
</select><br />
<span class="text10">The character which will be used as separator should not be used in any value </span>
</td></tr>
<tr>
<td align="left" colspan="4" nowrap>
<!--<input type="checkbox" name="special_import" value="1"> Extra import structure (when checked, the fields above are not applicable)<br><br>-->
File 
<input style="width:650px;" type="file" name="datafile" class="field10">
</td></tr>
<tr>
<td align="center" colspan="4" nowrap>
<input type="submit" name="submit" value="Submit" class="button10">
</td></tr>
</table></td></tr></table>
</form>
<br />
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Info & Instructions</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left">
<span class="text10">
All the data of each category must be placed on the same line.<br />
One blank line is required at the end of the file.<br />
Required items: Title, Parent category.<br />
Parent category - enter a complete name or number of a parent category or number 0 if it\'s a first-level category<br />
User defined items - select boxes and radio must have one of the available values<br />
User defined items - select box can have only value 1 or 0<br />
<br />
Create the text file very carefully.<br />The script grabs the categories and places them to the database without any kind of control.<br />
<br /></span>
<span class="text10a_bold">Example</span><br />
<span class="text10">If you have a file with the following structure:<br />
<span class="text10">
Title|Parent_category|Description|Image_URL<br />
Title|Parent_category|Description|Image_URL<br />
Title|Parent_category|Description|Image_URL<br />
Title|Parent_category|Description|Image_URL<br />
.....<br /></span>
Select these values:<br />
Rank&nbsp;#1&nbsp;Title&nbsp;&nbsp; 
Rank&nbsp;#2&nbsp;Parent&nbsp;category&nbsp;&nbsp; 
Rank&nbsp;#3&nbsp;Description&nbsp;&nbsp; 
Rank&nbsp;#4&nbsp;Image&nbsp;URL&nbsp;&nbsp; 
<br />
Separator <b>|</b><br />
Each value is enclosed by <b>None</b><br />
<br />';
echo '<b>Problems?</b><br>If you cannot save your current data to the required format, we are able to do it for you. Please <a href="mailto:info@phpwebscripts.com">email us</a> for more info.';
echo '</span></td></tr></table></td></tr></table>';
ift();
}

#################################################################################

function categories_imported($data) {
global $s;
//foreach ($data as $k=>$v) echo "$k - $v<br />";exit;
$all_user_items_list = get_all_user_items_list("c_$data[what]");
$all_user_items_values = get_all_user_items_values("c_$data[what]");

ih();
echo '<br />';
$time0 = time(); $pocet = 0;

$delimiter = $data[separator]; if ($data[step]) $limit = "limit $data[step]";
$q = dq("select * from $s[pr]import_temp where what = 'c' order by n $limit",1);
while ($a = mysql_fetch_assoc($q))
{ $pocet ++; $to_delete_last = $a[n];
  $line = htmlspecialchars_decode($a[line],ENT_QUOTES);
  $pole = preg_split( "/[$delimiter]*\\\"([^\\\"]+)\\\"[$delimiter]*|[$delimiter]+/",trim(stripslashes($line)), 0, PREG_SPLIT_DELIM_CAPTURE );
  set_time_limit(600);
  if (time()>$time0+30) { header('X-pmaPing: Pong'); $time0 = time(); }
//  if (time()>($time1+10)) { $time1=time(); echo ' Working ... '.str_repeat (' ',4000); flush(); }
  if ((!trim($pole[0])) AND (!trim($pole[1])) AND (!trim($pole[2]))) continue;
  $pole = replace_array_text($pole);
  foreach ($data[rank] as $k=>$v) { $x = $k - 1; $$v = $pole[$x]; }  
    
  if ($data[special_import])
  { $tmpl_cat = 'category.html'; $tmpl_one = $s[item_types_words][$data[what]].'_a.txt'; $tmpl_det = $s[item_types_words][$data[what]].'_details.html';
    $line_array = explode(':',$line);
    for ($x=0;$x<=(count($line_array)-1);$x++)
    { if ($x==0) $parent = 0; else $parent = $n;
      $title = trim($line_array[$x]); $level = $x + 1;
	  $q = dq("select * from $s[pr]cats where use_for = '$data[what]' and name = '$title' and level = '$level' and parent = '$parent'",1);
	  $cat = mysql_fetch_assoc($q);
	  if ($cat[n]) $n = $cat[n];
	  else
	  { dq("insert into $s[pr]cats values (NULL,'$parent','0','0','$title','','','','$title','','','','','0','0','0','0','','','1','0','','0','','','$tmpl_cat','$tmpl_one','$tmpl_det','','1','0','0','$data[what]','0','0','0','0','0','','0','0','0','0','$youtube_keywords','$max_items','','','','7','','','','','')",1);
        $n = mysql_insert_id();
        update_category_paths($n);
	  }
	    
    }
  }
  else
  { unset ($user_items); $arr = get_defined_vars();
    foreach ($arr as $k=>$v) 
    { if (strstr($k,'user_item_'))
      { $x = str_replace('user_item_','',$k); $user_items[$x] = $v; }
    }
    foreach ($all_user_items_list as $k=>$v)
    { if (isset($user_items[$v[item_n]]))
      { if (($v[kind]=='text') OR ($v[kind]=='textarea') OR ($v[kind]=='htmlarea')) { $value_texts[$v[item_n]] = $user_items[$v[item_n]]; $value_codes[$v[item_n]] = ''; }
        elseif ($v[kind]=='checkbox') { $value_texts[$v[item_n]] = ''; $value_codes[$v[item_n]] = $user_items[$v[item_n]]; }
        elseif (($v[kind]=='select') OR ($v[kind]=='radio'))
        { $value_texts[$v[item_n]] = $user_items[$v[item_n]];
	      foreach ($all_user_items_values[$v[item_n]] as $k1=>$v1)
	      if ($v1==$user_items[$v[item_n]]) { $value_codes[$v[item_n]] = $k1; break; }
        }
      }
    }
    
    if ($parent)
    { $q1 = dq("select name,n,visible from $s[pr]cats where use_for = '$data[what]' and (name = '$parent' OR n = '$parent')",1);
      $parent_cat = mysql_fetch_assoc($q1);
      if ($parent_cat[n]) { $visible = $parent_cat[visible]; $parent = $parent_cat[n]; }
      else { $parent = 0; $visible = 1; }
    }
    else { $parent = 0; $visible = 1; }
    if (!$tmpl_cat) $tmpl_cat = 'category.html';
    if (!$tmpl_one) $tmpl_one = $s[item_types_words][$data[what]].'_a.txt';
    if (!$tmpl_det) $tmpl_det = $s[item_types_words][$data[what]].'_details.html';
   
    //echo "insert into $s[pr]cats values (NULL,'$parent','0','0','$title','$description','$image1','$image2','$m_keyword','$m_desc','','','','0','0','0','0','','','1','0','$similar','0','$pagename','$rewrite_url','$tmpl_cat','$tmpl_one','$tmpl_det','','$visible','$users_only_cat','$users_only_items','$data[what]','0','0','0','0','0','$rss_url','$rss_items','$in[rss_read_interval]','0','0','$youtube_keywords','$max_items','','','','7','','','','','')<br>";
    dq("insert into $s[pr]cats values (NULL,'$parent','0','0','$title','$description','$image1','$image2','$m_keyword','$m_desc','','','','0','0','0','0','','','1','0','$similar','0','$pagename','$rewrite_url','$tmpl_cat','$tmpl_one','$tmpl_det','','$visible','$users_only_cat','$users_only_items','$data[what]','0','0','0','0','0','$rss_url','$rss_items','$rss_read_interval','0','0','$youtube_keywords','$max_items','','','','7','','','','','')",1);
    $n = mysql_insert_id();
    update_category_paths($n);
    add_update_user_items("c_$data[what]",$n,$all_user_items_list,$value_codes,$value_texts);
    if ($parent) $bigboss = find_bigboss_category($parent); else $bigboss = $n;
    dq("update $s[pr]cats set bigboss = '$bigboss' where use_for = '$data[what]' AND n = '$n'",1);
  }
  if (($data[step]) AND ($pocet>=$data[step])) break;
  unset($tmpl_cat,$tmpl_one,$tmpl_det);
}
dq("delete from $s[pr]import_temp where what = 'c' and n <= '$to_delete_last'",1);
$q = dq("select * from $s[pr]import_temp where what = 'c'",1); $rest = mysql_num_rows($q);

$to = $data[from] + $data[step] - 1;
if ($rest)
{ echo 'Categories '.$data[from].' to '.$to.' created<br />
  <form method="post" action="categories.php">'.check_field_create('admin').'
  <input type="hidden" name="action" value="categories_imported">
  <input type="hidden" name="what" value="'.$data[what].'">
  <input type="hidden" name="total_items" value="'.$data[total_items].'">
  <input type="hidden" name="separator" value="'.$data[separator].'">
  <input type="hidden" name="from" value="'.($to+1).'">
  <input type="hidden" name="step" value="'.$data[step].'">';
  foreach ($data[rank] as $k=>$v) echo '<input type="hidden" name="rank['.$k.']" value="'.$v.'">';
  echo '<td align="center"><input type="submit" name="submit" value="Import next 100 categories" class="button10"></td></form>';
}
else echo info_line('All categories imported.');
update_disabled_categories($data[what]);
exit;
}

##################################################################################
##################################################################################
##################################################################################

?>