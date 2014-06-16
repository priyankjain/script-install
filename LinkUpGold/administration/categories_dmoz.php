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
set_time_limit(0); 

switch ($_GET[action]) {
case 'file_from_dmoz_download'	: file_from_dmoz_download($_GET[filename]);
case 'import_temp_categories'	: import_temp_categories();
case 'show_temp_categories'		: show_temp_categories($_GET[n]);
case 'categories_to_lug'		: categories_to_lug($_GET);
}
show_steps();

########################################################################################

function show_steps() {
global $s;
ih();
echo '<form action="categories_dmoz.php" method="get">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%" class="common_table_top_cell">Import categories from Dmoz.org</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left">Pick one of these steps.<br>Each of these steps may take a long time to finish. Some servers may be set to not to allow scripts so much time for their work. If the script stops to response, it\'s probably this case and the import will not work properly on your server.</td></tr>
<tr><td align="left"><input type="radio" name="action" value="file_from_dmoz_download">&nbsp;Download the file categories.txt<br><input class="field10" name="filename" value="http://rdf.dmoz.org/rdf/categories.txt" size="100"><br>This step needs 60MB of webspace.<br><span class="text10">It downloads the categories data file and saves it to your data directory. You also can download it manually, rename it to dmoz_categories.txt and upload it to your server to data directory.<br>Skip this step if the file has been downloaded in the past.<br></span></td></tr>
<tr><td align="left"><input type="radio" name="action" value="import_temp_categories">&nbsp;Load the file dmoz_categories.txt and import all categories in a temporary table in the database<br>This step needs 400MB of space in your database.<br><span class="text10">Skip this step if your already finished it in the past.<br></span></td></tr>
<tr><td align="left"><input type="radio" name="action" value="show_temp_categories">&nbsp;Show imported categories and optionally import them to Link Up Gold</td></tr>
<tr><td align="center"><input type="submit" name="co" value="Submit" class="button10"></td></tr>
</table></td></tr></table>
</form>';
ift();
}

########################################################################################

function file_from_dmoz_download($file_url) {
global $s;
ih();
$file = fopen($file_url,'r') or die("Unable to read file $file_url");
$openfile = fopen("$s[phppath]/data/dmoz_categories.txt",'w') or die("Unable to write to file $s[phppath]/data/dmoz_categories.txt");
while ($data = fread($file,1000))
{ increase_print_time(5,1);
  fwrite($openfile,$data) or die("Unable to write to file $s[phppath]/data/dmoz_categories.txt");
}
fclose ($file);
fclose($openfile);
increase_print_time(5,'end');
echo info_line('All done');
echo '<a href="categories_dmoz.php">Show list of available steps</a><br><br>';
ift();
}

########################################################################################

function import_temp_categories() {
global $s;
ih();
$fp = fopen("$s[phppath]/data/dmoz_categories.txt","r") or die("Unable to read file $s[phppath]/data/dmoz_categories.txt");
dq("truncate table $s[pr]cats_dmoz",1);
while ($category_path = fgets($fp,4096))
{ increase_print_time(5,1);
  $category_path = trim($category_path);
  $category_array = explode('/',$category_path);
  $level = count($category_array); if (!$level) continue;
  $category_title = array_pop($category_array);
  $parent_path = implode('/',$category_array);
  $category_title = str_replace("'",'',$category_title);
  $parent_path = str_replace("'",'',$parent_path);
  $category_path = str_replace("'",'',$category_path);
  dq("insert into $s[pr]cats_dmoz values(NULL,'$category_title','$level','$parent_path','$category_path')",1);
  if (!($pocet%1000)) echo "<b>$pocet</b> ";
  $pocet++; 
  //if ($pocet==100) exit;
}
increase_print_time(5,'end');
echo info_line('All done');
echo '<a href="categories_dmoz.php">Show list of available steps</a><br><br>';
ift();
}

########################################################################################

function show_temp_categories($parent) {
global $s;
ih();
if ($parent)
{ $q = dq("select * from $s[pr]cats_dmoz where n = '$parent'",1);
  $parent_vars = mysqli_fetch_assoc($q);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $parent_vars[title] = iconv('UTF-8',$s[charset],$parent_vars[title]);
  if ($parent_vars[level]>1)
  $q = dq("select * from $s[pr]cats_dmoz where path = '$parent_vars[parent_path]'",1);
  $big_parent_vars = mysqli_fetch_assoc($q);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $big_parent_vars[title] = iconv('UTF-8',$s[charset],$big_parent_vars[title]);
}

echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">';
if ($parent_vars[n])
{ echo '<tr><td align="center" width="100%" class="common_table_top_cell">Category '.$parent_vars[title].'</td></tr>';
  if ($big_parent_vars[n]) echo '<tr><td align="left" width="100%"><a href="categories_dmoz.php?action=show_temp_categories&n='.$big_parent_vars[n].'">&nbsp;&nbsp;<<< Back to category '.$big_parent_vars[title].'</a></td></tr>';
  else echo '<tr><td align="left" width="100%"><a href="categories_dmoz.php?action=show_temp_categories">&nbsp;&nbsp;<<< Back to first level categories</a></td></tr>';
}
else echo '<tr><td align="center" width="100%" class="common_table_top_cell">First Level Categories</td></tr>';
echo '<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';

$q = dq("select * from $s[pr]cats_dmoz where parent_path = '$parent_vars[path]' order by title",1);
while ($cat = mysqli_fetch_assoc($q))
{ $q1 = dq("select count(*) from $s[pr]cats_dmoz where parent_path = '$cat[path]'",1); $subcategories = mysqli_fetch_row($q1);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $cat[title] = iconv('UTF-8',$s[charset],$cat[title]);
  echo '<tr>
  <td align="left" nowrap><b>'.str_replace('_',' ',$cat[title]).'</b></td>';
  if ($subcategories[0])   echo '<td align="left" nowrap><a href="categories_dmoz.php?action=show_temp_categories&n='.$cat[n].'">Show subcategories ('.$subcategories[0].')</a></td>
  <td align="left" nowrap><a href="categories_dmoz.php?action=categories_to_lug&n='.$cat[n].'">Import this category and its subcategories to Link Up Gold</a></td>';
  else echo '<td align="left" nowrap>No subcategories</td>
  <td align="left" nowrap><a href="categories_dmoz.php?action=categories_to_lug&n='.$cat[n].'">Import this category as a first level category to Link Up Gold</a></td>';
  echo '</tr>';
}

echo '</table></td></tr></table>
';
echo '<br><a href="categories_dmoz.php">Show list of available steps</a><br><br>';
ift();
}

########################################################################################

function categories_to_lug($in) {
global $s;

ih();
if (!$in[n]) exit;

$q = dq("select * from $s[pr]cats_dmoz where n = '$in[n]'",1);
$parent_vars = mysqli_fetch_assoc($q);
if ((function_exists('iconv')) AND ($s[charset]!='')) $parent_vars[title] = iconv('UTF-8',$s[charset],$parent_vars[title]);
$parent_vars[title] = str_replace('_',' ',$parent_vars[title]);

$q = dq("select max(level) from $s[pr]cats_dmoz where parent_path like '$parent_vars[path]%'",1); $level = mysqli_fetch_row($q);
$q = dq("select * from $s[pr]cats_dmoz where parent_path = '$parent_vars[path]' OR parent_path like '$parent_vars[path]/%' order by level",1);

if (($num_subcats=mysqli_num_rows($q)) AND (!$in[confirmed]))
{ echo info_line('You have selected to import the category '.$parent_vars[title].' and its subcategories','This category has <b>'.$num_subcats.' subcategories</b>.<br>This category will be imported as a first level category to Link Up Gold.<br>Also its subcategories in levels 1 - '.($level[0]-$parent_vars[level]).' will be imported.');
  echo '<table border="0" width="750" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td class="common_table_top_cell">Choose one from the following options</td></tr>
  <tr><td align="center"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
';
  if ($level[0]-$parent_vars[level])
  { for ($x=1;$x<=($level[0]-$parent_vars[level]);$x++)
    if ($x==1)   echo '<tr><td align="center"><a href="categories_dmoz.php?action=categories_to_lug&n='.$in[n].'&confirmed=1&levels='.$x.'">Click here to import the category '.$parent_vars[title].' and its subcategories in level 1</a></td></tr>';
    else echo '<tr><td align="center"><a href="categories_dmoz.php?action=categories_to_lug&n='.$in[n].'&confirmed=1&levels='.$x.'">Click here to import the category '.$parent_vars[title].' and its subcategories in levels 1 - '.$x.'</a></td></tr>';
  }
  echo '</table></td></tr></table>';
  echo '<br><br><br><a href="categories_dmoz.php?action=show_temp_categories">Cancel this import</a><br><br>';
  ift();
}

if (!$in[what])
{ if (!$in[levels]) $in[levels] = 0;
  $q1 = dq("select * from $s[pr]cats_dmoz where (parent_path = '$parent_vars[path]' OR parent_path like '$parent_vars[path]/%') and level <= ($parent_vars[level]+$in[levels]) order by level",1);
  if ($num_subcats=mysqli_num_rows($q1))
  { if (!$in[levels]) $in[levels] = $level[0]-$parent_vars[level];
	echo info_line('You have selected to import the category '.$parent_vars[title].' and its subcategories','This category will be imported as a first level category to Link Up Gold.<br>Also its subcategories in levels 1 - '.$in[levels].' will be imported.<br>Total of <b>'.($num_subcats+1).' categories will be imported.</b>');
  }
  else echo info_line('You have selected to import the category '.$parent_vars[title].' and as a first level category to Link Up Gold.');
  
  echo '<table border="0" width="750" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td class="common_table_top_cell">Choose one from the following options</td></tr>
  <tr><td align="center"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';
  foreach ($s[items_types_Words] as $k=>$v) echo '<tr><td align="center"><a href="categories_dmoz.php?action=categories_to_lug&n='.$in[n].'&confirmed=1&what='.$k.'&levels='.$in[levels].'">Import these categories for '.$v.'</a></td></tr>';
  echo '</table></td></tr></table>';
  echo '<br><br><br><a href="categories_dmoz.php?action=show_temp_categories">Cancel this import</a><br><br>';
  ift();
}

increase_print_time(5,1);
$tmpl_one = $s[item_types_words][$in[what]].'_a.txt'; $tmpl_det = $s[item_types_words][$in[what]].'_details.html';

$m_keywords = str_replace(' ',', ',$parent_vars[title]); if ($in[what]=='v') $youtube_keywords = $m_keywords;
dq("insert into $s[pr]cats values (NULL,'0','1','0','$parent_vars[title]','','','','$m_keywords','','','','','','','','0','','','1','0','','0','','','category.html','$tmpl_one','$tmpl_det','','1','0','0','$in[what]','0','0','0','0','0','','0','0','0','0','$youtube_keywords','500','','','','7','','','','','')",1);
$cislo = mysqli_insert_id($s[db]);
update_category_paths($cislo);
$parents[$cislo] = $parent_vars[path];
echo 'Category "'.$parent_vars[title].'" has been created<br>';
increase_print_time(5,1);

while ($c = mysqli_fetch_assoc($q))
{ if (($in[levels]) AND (($c[level]-$parent_vars[level])>$in[levels])) break;
  $current_parent = array_search($c[parent_path],$parents);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $c[title] = iconv('UTF-8',$s[charset],$c[title]);
  $c[title] = str_replace('_',' ',$c[title]);
  $m_keywords = str_replace(' ',', ',$c[title]); if ($in[what]=='v') $youtube_keywords = $m_keywords;
  dq("insert into $s[pr]cats values (NULL,'$current_parent','0','0','$c[title]','','','','$m_keywords','','','','','','','','0','','','1','0','','0','','','category.html','$tmpl_one','$tmpl_det','','1','0','0','$in[what]','0','0','0','0','0','','0','0','0','0','$youtube_keywords','500','','','','7','','','','','')",1);
  $cislo = mysqli_insert_id($s[db]);
  update_category_paths($cislo);
  $parents[$cislo] = $c[path];
  echo 'Category "'.$c[title].'" has been created<br>';
  increase_print_time(5,1);
}
echo '</span></span>';
echo '<br><br><br><a href="categories_dmoz.php">Show list of available steps</a><br><br>';
ift();
}

########################################################################################
########################################################################################
########################################################################################

?>