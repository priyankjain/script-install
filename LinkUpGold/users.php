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
$s[selected_menu] = 1;
get_messages('user.php');

switch ($_GET[action]) {
case 'user_info'			: user_info($_GET);
case 'new'					: show_users('new');
case 'active'				: show_users('active');
case 'most_submissions'		: show_users('most_items');
}
switch ($_POST[action]) {
}
user_info($_GET);

#########################################################################
#########################################################################
#########################################################################

function show_users($what) {
global $s,$m;

if ($what=='new') { $a[title] = $m[new_users]; $order_by = "joined desc"; }
elseif ($what=='active') { $a[title] = 'Active users'; $order_by = "joined desc"; }
elseif ($what=='most_items') { $a[title] = $m[users_most_items]; $order_by = "items desc"; }

$from = 0;
$q = dq("select *,(links+articles+blogs) as items from $s[pr]users group by n order by $order_by limit $from,$s[u_per_page]",1);
while ($x = mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }

$a[items] = get_complete_users($item,$numbers,'user_a.txt');
page_from_template('users_list.html',$a);
}

#########################################################################

function get_complete_users($user,$numbers,$template) {
global $s;
$s[u_columns] = 1;
$width = floor(100/$s[u_columns]);
foreach ($user as $k => $a)
{ if ($a[picture]) 
  { $a[image_1] = $a[picture];
	$big_file = preg_replace("/\/$a[n]-/","/$a[n]-big-",$a[picture]);
    if (file_exists(str_replace("$s[site_url]/","$s[phppath]/",$big_file))) $a[image_1_big] = $big_file;
    else $a[image_1_big] = $a[picture];
    $a[pictures]++;
  }
  if (!$a[pictures]) $a[image_1] = "$s[site_url]/images/no_picture.png";
  $a[title_no_tag] = strip_tags($a[title]);
  $a[item_details_url] = get_detail_page_url('u',$a[n],$a[nick]);
  $a[joined] = datum($a[joined],0);
  $a[user_rank] = $s['u_rank_n_'.$a[rank]];
  if (!$a[nick]) $a[nick] = $a[name];
  $complete_array[] = '<td valign="top" width="'.$width.'%">'.parse_part($template,$a).'</td>';
  $pocet++;
}
$rows = ceil($pocet/$s[u_columns]);
for ($x=$pocet+1;$x<=($rows*$s[u_columns]);$x++)
{ $complete_array[] = '<td>&nbsp;</td>';
  $pocet++;
}
for ($x=1;$x<=$rows;$x++)
{ $complete .= '<tr>';
  for ($y=($x-1)*$s[u_columns];$y<=$x*$s[u_columns]-1;$y++)
  $complete .= $complete_array[$y];
  $complete .= '</tr>';
}
return $complete;
}

#########################################################################
#########################################################################
#########################################################################

function user_info($in) {
global $s,$m;
if (is_numeric($in[n])) $a = get_user_variables($in[n]);
else 
{ $in = replace_array_text($in);
  $q = dq("select * from $s[pr]users where username = '$in[username]'",1);
  $a = mysql_fetch_assoc($q);
}
$a[user_rank] = $s['u_rank_n_'.$a[rank]];
$a[joined] = datum($a[joined],0);
if (!$a[nick]) $a[nick] = $a[name];

list($images,$files) = pictures_files_display_public('u',$a[n],0);
$images = detail_page_images('u',$images[$a[n]],$a[n],0);
if ($images[full_size_image]) $a[pictures_gallery] = $images[full_size_image];
if ($images[pictures_gallery]) { $a[pictures_gallery] = $images[pictures_gallery]; $a[previews_width] = $images[previews_width]; }
if (!$a[pictures_gallery]) { $a[hide_image_begin] = '<!--'; $a[hide_image_end] = '-->'; }

$a[contact_box] = contact_box('u',$a[n],'',1);
$x = array('l','a','b');
foreach ($x as $k=>$what)
{ unset($item,$numbers);
  $where = 'where '.get_where_fixed_part($what,0,'',$s[cas]);
  $table = $s[item_types_tables][$what];
  $template = $s[item_types_words][$what].'_a.txt';
  $function = 'get_complete_'.$s[items_types_words][$what];
  $q = dq("select * from $table $where and owner = '$a[n]' order by created desc limit 10",1);
  while ($x = mysql_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
  if ($numbers)
  { //foreach ($item as $k => $d) $item[$k][category] = $a[n];
    $a[$what.'_items'] = $function($item,$numbers,$template);
  }
  else $a[$what.'_items'] = '<td align="left"><br>'.$m["user_no_$what"].'</td>';
}
if (!$a[detail]) $a[detail] = $m[no_user_article];

$q = dq("select * from $s[pr]u_wall where user = '$in[n]'",1);
while ($x = mysql_fetch_assoc($q))
{ $a[wall_posts] .= '<tr><td align="left" class="table_item_top_cell">'.$x[title].'</td></tr>
  <tr><td align="left">'.nl2br($x[text]).'<br><i>'.datum($x[time],0).'</i></td></tr>';
}
if ( (trim($a[url])) AND (!preg_match("/^(http:\/\/*+)/i",$a[url])) AND (!preg_match("/^(https:\/\/*+)/i",$a[url])) ) $a[url] = 'http://'.$a[url];

$q = dq("select $s[pr]u_friends.*,$s[pr]users.username,$s[pr]users.name,$s[pr]users.nick,$s[pr]users.picture,$s[pr]users.n from $s[pr]users,$s[pr]u_friends where (($s[pr]u_friends.user2 = '$in[n]' and $s[pr]u_friends.user1=$s[pr]users.n) OR ($s[pr]u_friends.user1 = '$in[n]' and $s[pr]u_friends.user2=$s[pr]users.n)) and accepted = 1 order by time desc",1);
while ($x = mysql_fetch_assoc($q))
{ if (!$x[nick]) $x[nick] = $x[name];
  if (!$x[picture]) $x[picture] = "$s[site_url]/images/no_picture.png";
  $a[friends_list] .= '<div style="float:left;text-align:center;padding:10px;width:130px;height:110px;overflow:hidden;"><a href="'.get_detail_page_url('u',$x[n],$x[nick]).'"><img border="0" src="'.$x[picture].'" width="120" height="90"><br>'.$x[nick].'</a></div>';
}
if (!$a[friends_list]) $a[friends_list] = '<div style="float:left;text-align:center;padding:30px;">'.$m[nothing_found].'</div>';

if (($s[LUG_u_n]) AND ($s[LUG_u_n]!=$in[n]))
{ $q = dq("select * from $s[pr]u_friends where (user2 = '$s[LUG_u_n]' and user1 = '$in[n]') OR (user1 = '$s[LUG_u_n]' and user2 = '$in[n]') limit 1",1);
  if (!mysql_num_rows($q)) $a[friends_list] = '<div style="float:left;width:100%;text-align:left;padding:30px;"><a href="'.$s[site_url].'/user.php?action=user_friend_request&n='.$in[n].'"><b>'.$m[send_friend_request].'</b></a></div>'.$a[friends_list];
}
page_from_template('user_details.html',$a);
}

###############################################################################
###############################################################################
###############################################################################

?>