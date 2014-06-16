<?PHP

include('./common.php');
$s[selected_menu] = 1;
get_messages('links.php');
$_GET = replace_array_text($_GET);

foreach ($_GET as $k=>$v) { $letter = substr($k,0,1); break; }
$allowed = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
if (!in_array($letter,$allowed)) exit;

$q = dq("select * from $s[pr]links where title like '$letter%' order by created desc",1);
while ($x = mysql_fetch_assoc($q)) { $a[links] .= '<a href="'.get_detail_page_url('l',$x[n],$x[rewrite_url],$x[category],1).'">'.$x[title].'</a><br>'; }

$a[letter] = $letter;

page_from_template('links_list.html',$a);

#############################################################################
#############################################################################
#############################################################################

/*
include('./common.php');
$s[selected_menu] = 1;
get_messages('articles.php');
$_GET = replace_array_text($_GET);

$letter = $_GET[letter];
$allowed = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
if (!in_array($letter,$allowed)) exit;

$q = dq("select * from $s[pr]cats where use_for = 'a' AND name like '$letter%' order by name",1);
while ($x = mysql_fetch_assoc($q)) { $a['list'] .= '<a href="'.category_url('a',$x[n],$x[alias_of],$x[name],1,'',$x[rewrite_url]).'">'.$x[name].'</a><br>'; }

$a[letter] = $letter;

page_from_template('cats_list.html',$a);
*/

?>