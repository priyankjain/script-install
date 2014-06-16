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
include("$s[phppath]/data/newsletter.php");

switch ($_GET[action]) {
case 'users_home'			: users_home();
case 'user_create'			: user_create_edit(0);
case 'user_edit'			: user_create_edit($_GET[user]);
case 'user_delete'			: user_delete($_GET[user]);
case 'users_searched'		: users_searched($_GET);
case 'newsletter'			: newsletter(0);
case 'email_users'			: email_users(0);
case 'users_unapproved_show': users_unapproved_show($_GET);
case 'delete_image'			: delete_image('u',$_GET);
case 'friend_delete'		: friend_delete($_GET);
case 'board_message_show'	: board_message_show($_GET[n]);
case 'board_message_delete'	: board_message_delete($_GET[n]);
case 'user_friends'			: user_friends($_GET[n]);

}
switch ($_POST[action]) {
case 'user_created'			: user_created($_POST);
case 'user_edited'			: user_edited($_POST);
case 'newsletter'			: newsletter($_POST);
case 'emailed_users'		: emailed_users($_POST);
case 'users_approved'		: users_approved($_POST);
}

#################################################################################
#################################################################################
#################################################################################

function users_home() {
global $s;
check_admin('users');
$s[ads] = select_ads(0);

ih();
echo $s[info];
echo page_title('Users');
$q = dq("select count(*) from $s[pr]users where confirmed = '1' and approved = '0'",0);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if ($pocet)
{ echo '<form method="get" action="users.php">
  <input type="hidden" name="action" value="users_unapproved_show">
  <table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td>
  <table border=0 width="100%" cellspacing="0" cellpadding="2" class="inside_table">
  <tr><td class="common_table_top_cell" colspan=2>Queue</td></tr>
  <tr><td align="center">
  Unapproved users in the queue: '.$pocet.
  '<br />Select number of users to display on one page<br />
  <select class="select10"name="perpage"><option value="0">All</option>';
  if ($pocet>5) echo '<option value="5">5</option>';
  if ($pocet>10) echo '<option value="10">10</option>';
  if ($pocet>20) echo '<option value="20">20</option>';
  if ($pocet>30) echo '<option value="30">30</option>';
  echo '</select> 
  <input type="submit" value="Submit" name="B1" class="button10">
  </td></tr></table></td></tr></table></form><br />';
}

?>
<form action="users.php" method="get">
<input type="hidden" name="action" value="users_searched">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan=2>Search for Users</td></tr>
<tr><td>
<table border=0 width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" nowrap>Number</td>
<td align="left"><input class="field10" name="n" style="width:100px" maxlength=15></td>
</tr>
<tr>
<td align="left" nowrap>Username contains&nbsp;&nbsp;</td>
<td align="left"><input class="field10" name="username" style="width:650px" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Any field contains </td>
<td align="left"><input class="field10" name="any" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Email contains </td>
<td align="left"><input class="field10" name="email" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Name contains </td>
<td align="left"><input class="field10" name="name" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Address contains </td>
<td align="left"><input class="field10" name="address" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Country contains </td>
<td align="left"><input class="field10" name="country" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Phones contains </td>
<td align="left"><input class="field10" name="phone" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Only users who receive </td>
<td align="left">
<select class="select10" name="newsletter" size=1>
<option value="0">N/A</option>
<?PHP
for ($x=1;$x<=5;$x++)
if ($s["news_$x"]) echo '<option value="'.$x.'">'.$s["news_$x"].'</option>';
echo '</select></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Funds balance&nbsp;&nbsp;</span></td>
<td align="left" colspan=2><span class="text13">From '.$s[currency].'<input class="field10" style="width:100px" name="balance_f" maxlength=10> To '.$s[currency].'<input class="field10" style="width:100px" name="balance_t" maxlength=10></span></td>
</tr>
<tr>
<td align="left" nowrap>Type of search </td>
<td align="left" nowrap>AND<input  type="radio" value="and" name="boolean" checked> OR<input type="radio" value="or" name="boolean"></td>
</tr>';
if ($s[l_who]==2)
{ echo '<tr>
  <td align="left" nowrap>Can submit links</td>
  <td align="left" nowrap>N/A<input type="radio" value="0" name="post_links" checked> Yes<input type="radio" value="yes" name="post_links"> No<input type="radio" value="no" name="post_links"></td>
  </tr>';
}
if ($s[a_who]==2)
{ echo '<tr>
  <td align="left" nowrap>Can post articles</td>
  <td align="left" nowrap>N/A<input type="radio" value="0" name="post_art" checked> Yes<input type="radio" value="yes" name="post_art"> No<input type="radio" value="no" name="post_art"></td>
  </tr>';
}
if ($s[b_who]==2)
{ echo '<tr>
  <td align="left" nowrap>Can post blogs</td>
  <td align="left" nowrap>N/A<input type="radio" value="0" name="post_blogs" checked> Yes<input type="radio" value="yes" name="post_blogs"> No<input type="radio" value="no" name="post_blogs"></td>
  </tr>';
}
echo '<tr>
<td align="left" nowrap>Results per page </td>
<td align="left"><select class="select10" name="perpage">
<option value="0">All</option><option value="10">10</option><option value="20">20</option>
<option value="50">50</option><option value="100">100</option>
<option value="200">200</option><option value="500">500</option></select>
</td></tr>
<tr><td align="left" nowrap>Sort by </td>
<td align="left"><select class="select10" name="sort">
<option value="username">Username</option><option value="email">Email</option>
<option value="name">Name</option><option value="joined">Date joined</option>
</select>
<select class="select10" name="order">
<option value="asc">Ascending</option><option value="desc">Descending</option>
</select></td></tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Search" class="button10"></td></tr>
</table>
</td></tr></table></form>';
ift();
}

#################################################################################

function user_created($in) {
global $s;
check_admin('users');
$in = $in[user][0];
$x = get_user_variables(0,$in[username]); if ($x[n]) problem('Selected username is already in use');
$in = replace_array_text($in);
$in[detail] = refund_html($in[detail]);
dq("insert into $s[pr]users values(NULL,'$in[username]','$in[password]','$in[email]','$in[name]','$in[nick]','$in[company]','$in[address1]','$in[address2]','$in[address3]','$in[country]','$in[phone1]','$in[phone2]','$in[url]','$in[site_title]','','$in[detail]','$in[user1]','$in[user2]','$in[user3]','$in[showemail]','$in[news_1]','$in[news_2]','$in[news_3]','$in[news_4]','$in[news_5]','1','$s[cas]','1','$in[approved]','$in[style]','$in[post_art]','$in[post_links]','$in[post_blogs]','0','0','0','0','0','$in[funds]','$in[funds]','$in[funds]')",1);
$n = mysql_insert_id();
upload_files('u',$n,0,0,'');
ih();
echo info_line('User Created');
show_one_user($n);
ift();
}

#################################################################################

function show_one_user($data) {
global $s;
check_admin('users');
if (!is_array($data)) // je to cislo jenom
{ $q = dq("select * from $s[pr]users where n = '$data'",1);
  $data = mysql_fetch_assoc($q);
}

$data = stripslashes_array($data);
$joined = datum ($data[joined],1); 
for ($x=1;$x<=5;$x++) if (($s["news_$x"]) AND ($data["news$x"])) $data[newsletters] .= $s["news_$x"].'<br />'; if (!$data[newsletters]) $data[newsletters] = '&nbsp';
if ($data[approved]) $data[approved] = 'Yes'; else $data[approved] = 'No';
if ($data[post_art]) $data[post_art] = 'Yes'; else $data[post_art] = 'No';
if ($data[post_blogs]) $data[post_blogs] = 'Yes'; else $data[post_blogs] = 'No';
if ($data[post_links]) $data[post_links] = 'Yes'; else $data[post_links] = 'No';
$s[email_users][] = $data[n];

echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" width="150" nowrap>Number</td>
<td align="left" width="350">'.$data[n].'</td>
</tr>
<tr>
<td align="left" nowrap>Username</td>
<td align="left">'.$data[username].'</td>
</tr>
<tr>
<td align="left" nowrap>Password</td>
<td align="left">'.$data[password].'</td>
</tr>
<tr>
<td align="left" nowrap>Email</td>
<td align="left"><a href="mailto:'.$data[email].'">'.$data[email].'</a></td>
</tr>
<tr>
<td align="left" nowrap>Name</td>
<td align="left">'.$data[name].'&nbsp;</td>
</tr>';
images_show_admin('u',$data,0);
echo '<tr>
<td align="left">Site</td>
<td align="left"><a target="_blank" href="'.$data[url].'">'.$data[site_title].'</a>&nbsp;</td>
</tr>
<tr>
<td align="left" nowrap>Address line 1</td>
<td align="left">'.$data[address1].'&nbsp;</td>
</tr>
<tr>
<td align="left" nowrap>Address line 2</td>
<td align="left">'.$data[address2].'&nbsp;</td>
</tr>
<tr>
<td align="left" nowrap>Address line 3</td>
<td align="left">'.$data[address3].'&nbsp;</td>
</tr>
<tr>
<td align="left" nowrap>Country </td>
<td align="left">'.$data[country].'&nbsp;</td>
</tr>
<tr>
<td align="left" nowrap>Phone 1 </td>
<td align="left">'.$data[phone1].'&nbsp;</td>
</tr>
<tr>
<td align="left" nowrap>Phone 2 </td>
<td align="left">'.$data[phone2].'&nbsp;</td>
</tr>
';
if ($s[l_who]==2)
{ echo '<tr>
  <td align="left" nowrap>Can submit links</td>
  <td align="left">'.$data[post_links].'</td>
  </tr>';
}
if ($s[a_who]==2)
{ echo '<tr>
  <td align="left" nowrap>Can post articles</td>
  <td align="left">'.$data[post_art].'</td>
  </tr>';
}
if ($s[b_who]==2)
{ echo '<tr>
  <td align="left" nowrap>Can post blogs</td>
  <td align="left">'.$data[post_blogs].'</td>
  </tr>';
}
echo '<tr>
<td align="left" valign="top" nowrap>Joined Newsletters</td>
<td align="left">'.$data[newsletters].'</td>
</tr>
<tr>
<td align="left">Rank</td>
<td align="left">'.$s['u_rank_n_'.$data[rank]].' ('.$data[reviews].' reviews)</td>
</tr>
<tr>
<td align="left" nowrap>Funds on account </td>
<td align="left">'.$s[currency].$data[funds_now].'</td>
</tr>
<tr>
<td align="left" nowrap>Joined </td>
<td align="left">'.datum($data[joined],1).'</td>
</tr>
<tr>
<td align="left" colspan=2 nowrap>
[<a target="_self" href="users.php?action=user_edit&user='.$data[n].'" >Edit user & news & friends</a>]&nbsp;&nbsp;
[<a target="_self" href="links.php?action=links_searched&owner='.$data[n].'&sort=title&order=asc&showtext=on">Links</a>]&nbsp;&nbsp;
[<a target="_self" href="adlinks.php?action=adlinks_searched&owner='.$data[n].'&sort=title&order=asc&showtext=on">AdLinks</a>]&nbsp;&nbsp;
[<a target="_self" href="blogs.php?action=blogs_searched&owner='.$data[n].'&sort=title&order=asc&showtext=on">Blogs</a>]&nbsp;&nbsp;
[<a target="_self" href="articles.php?action=articles_searched&owner='.$data[n].'&sort=title&order=asc&showtext=on">Articles</a>]&nbsp;&nbsp;
[<a target="_self" href="orders_payments.php?action=orders_searched&user='.$data[n].'&sort=order_time&order=desc&showtext=on">Orders & payments</a>]&nbsp;&nbsp;
[<a target="_self" href="javascript: go_to_delete(\'It completely removes this user and also\nall orders and AdLinks owned by this user.\n\nAre you sure?\',\'users.php?action=user_delete&user='.$data[n].'\')" title="Delete this user">Delete</a>]&nbsp;&nbsp;
</td></tr></table></td></tr></table>
<br />';
}

#################################################################################

function users_searched($in) {
global $s;

check_admin('users');
if ($in[n]) $where = "where n = $in[n]";
else
{ if ($in[any]) $w[] = "(username like '%$in[any]%' OR email like '%$in[any]%' OR name like '%$in[any]%' OR nick like '%$in[any]%' OR company like '%$in[any]%' OR address1 like '%$in[any]%' OR address2 like '%$in[any]%' OR address3 like '%$in[any]%' OR country like '%$in[any]%' OR phone1 like '%$in[any]%' OR phone2 like '%$in[any]%')";
  if ($in[username]) $w[] = " username like '%$in[username]%'";
  if ($in[email]) $w[] = " email like '%$in[email]%'";
  if ($in[name]) $w[] = " name like '%$in[name]%'";
  if ($in[newsletter]) $w[] = " news$in[newsletter] = 1 ";
  if ($in[post_art]=='yes') $w[] = ' post_art = 1 '; elseif ($in[post_art]=='no') $w[] = ' post_art = 0 ';
  if ($in[post_blogs]=='yes') $w[] = ' post_blogs = 1 '; elseif ($in[post_blogs]=='no') $w[] = ' post_blogs = 0 ';
  if ($in[post_links]=='yes') $w[] = ' post_links = 1 '; elseif ($in[post_links]=='no') $w[] = ' post_links = 0 ';
  if ($in[address]) $w[] = "(address1 like '%$in[address]%' or address2 like '%$in[address]%' or address3 like '%$in[address]%')";
  if ($in[country]) $w[] = " country like '%$in[country]%'";
  if ($in[phone]) $w[] = "(phone1 like '%$in[phone]%' or phone2 like '%$in[phone]%')";
  if ($in[balance_f]) $w[] = "funds_now >= '$in[balance_f]'";
  if ($in[balance_t]) $w[] = "funds_now <= '$in[balance_t]'";
  if ($w) $where = '('.implode(" $in[boolean] ",$w).')';
  if ($where) $where .= " AND approved = '1' and confirmed = '1'"; else $where = "approved = '1' and confirmed = '1'";
  $where = 'where '.$where;
}

//echo $where;

if (!$in[from]) $in[from] = 0; else $in[from] = $in[from] - 1;
if ($in[perpage]) $limit = " limit $in[from],$in[perpage]";

$x = dq("select count(*) from $s[pr]users $where",0);
$pocet = mysql_fetch_row($x); $pocet = $pocet[0];

if ($in[sort]) $orderby = "order by $in[sort]";
$q = dq("select * from $s[pr]users $where $orderby $in[order] $limit",1); 

ih();

if ( ($in[perpage]) AND ($pocet>$in[perpage]) )
{ $rozcesti = '<form action="users.php" method="get" name="form1"><input type="hidden" name="action" value="users_searched">';
  foreach ($in as $k => $v)
  { if ($v) $rozcesti .= '<input type="hidden" name="'.$k.'" value="'.$v.'">'; }
  $rozcesti .= 'Show users with begin of <select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($pocet/$in[perpage]);  
  for ($x=1;$x<$y;$x++)
  { $od = $x*$in[perpage]+1; $rozcesti .= '<option value="'.$od.'">'.$od.'</option>'; }
  $rozcesti .= '</select>&nbsp;<input type="submit" value="Submit" name="B1" class="button10"></form><br />';
}

$od = $in[from]+1;
$do = $in[from]+$in[perpage]; if ($do>$pocet) $do = $pocet; if (!$in[perpage]) $do = $pocet;

echo $s[info].'<span class="text13a_bold">Users Found: '.$pocet;
if ( ($pocet>1) AND ($od!=$do) ) echo ", showing users $od - $do<br /><br />\n$rozcesti";
else echo '<br /><br />';
echo '</spam>';
$in[returnto] = 'search'; $in[from] = $in[from] + 1;
while ($x = mysql_fetch_assoc($q)) show_one_user($x,$in);
email_users_above_form($s[email_users]);
ift();
}

#################################################################################

function user_create_edit($n) {
global $s;
check_admin('users');

if ($_GET[action]) $current_action = $_GET[action]; else $current_action = $_POST[action];
if ($current_action != 'user_create')
{ $q = dq("select * from $s[pr]users where n = '$n'",1);
  $user = mysql_fetch_assoc($q);
  if (!$user[n]) problem ("User #$n does not exist.");
}
else { $user[n] = 0; }

$user[current_action] = $current_action;
switch ($current_action) {
case 'user_create'		: $action = 'user_created'; $s[table_title] = 'Create a New User'; break;
case 'user_edit'		: $action = 'user_edited'; $s[table_title] = 'Edit Selected User'; break;
case 'user_edited'		: $action = 'user_edited'; $s[table_title] = 'Edit Selected User'; break;
case 'friend_delete'	: $action = 'user_edited'; $s[table_title] = 'Edit Selected User'; break;
}

ih();
echo $s[info];
echo '<form ENCTYPE="multipart/form-data" action="users.php" method="post">'.check_field_create('admin').'<input type="hidden" name="action" value="'.$action.'">';
user_create_edit_form($user);
echo '<input type="submit" name="co" value="Save" class="button10"></form><br />';


if ($n)
{ echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td class="common_table_top_cell" align="center">User Board</td></tr>
  <tr><td align="center" width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
  <tr>
  <td align="left" width="100"><b>#</b></td>
  <td align="left" width="450" nowrap><b>Title</b></td>
  <td align="left" width="150" nowrap>&nbsp;</td>
  <td align="left" width="150" nowrap>&nbsp;</td>
  </tr>';

  $q = dq("select * from $s[pr]u_wall where user = '$n' order by time desc",1);
  while ($x = mysql_fetch_assoc($q))
  { echo '<tr>
    <td align="left">'.$x[n].' </td>
    <td align="left">'.$x[title].' </td>
    <td align="center"><a href="users.php?action=board_message_show&n='.$x[n].'">Show</a></td>
    <td align="center"><a href="users.php?action=board_message_delete&n='.$x[n].'">Delete</a></td>
    </tr>';
  }
  echo '</table></td></tr></table><br />';
}

user_friends_show($n,50);

ift();
}

#################################################################################

function user_friends($n) {
global $s;
check_admin('users');

ih();
user_friends_show($n,0);
ift();
}
	
#################################################################################

function user_friends_show($n,$in_limit) {
global $s;
check_admin('users');

if ($n)
{ echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td class="common_table_top_cell" align="center">User Friends</td></tr>
  <tr><td align="center" width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
  <tr>
  <td align="left" width="100">#</td>
  <td align="left" width="150" nowrap><b>Username</b></td>
  <td align="left" width="250" nowrap><b>Nick name</b></td>
  <td align="left" width="150" nowrap><b>Date</b></td>
  <td align="center" width="150" nowrap><b>Accepted</b></td>
  <td align="center" width="100" nowrap>&nbsp;</td>
  <td align="center" width="150" nowrap>&nbsp;</td>
  </tr>';
  if ($in_limit) $limit = "limit $in_limit"; else $limit = '';
  $q = dq("select $s[pr]u_friends.*,$s[pr]users.username,$s[pr]users.nick,$s[pr]users.n from $s[pr]users,$s[pr]u_friends where (($s[pr]u_friends.user2 = '$n' and $s[pr]u_friends.user1=$s[pr]users.n) OR ($s[pr]u_friends.user1 = '$n' and $s[pr]u_friends.user2=$s[pr]users.n)) order by time desc $limit",1);
  while ($x = mysql_fetch_assoc($q))
  { if ($x[accepted]) $accepted = 'Yes'; else $accepted = 'No';
    echo '<tr>
    <td align="left">'.$x[n].' </td>
    <td align="left" nowrap>'.$x[username].' </td>
    <td align="left" nowrap>'.$x[nick].' </td>
    <td align="left" nowrap>'.datum($x[time],0).' </td>
    <td align="center" nowrap>'.$accepted.'</td>
    <td align="center" nowrap><a href="users.php?action=user_edit&user='.$x[n].'">Show user</a></td>
    <td align="center" nowrap><a href="users.php?action=friend_delete&user1='.$n.'&user2='.$x[n].'">Delete friendship</a></td>
    </tr>';
    $pocet++;
  }
  echo '</table></td></tr></table><br />';
  //$in_limit=2;
  if (($pocet) AND ($pocet==$in_limit)) echo '<a href="users.php?action=user_friends&n='.$n.'">Show all friends</a><br>';
  elseif (!$limit) echo '<a href="users.php?action=user_edit&user='.$n.'">Show user details</a><br>';
}
}

#################################################################################

function board_message_delete($n) {
global $s;
check_admin('users');
$q = dq("select * from $s[pr]u_wall where n = '$n'",1);
$x = mysql_fetch_assoc($q);
dq("delete from $s[pr]u_wall where n = '$n'",1);
$s[info] = info_line('Selected post has been deleted');
user_create_edit($x[user]);
}

#################################################################################

function board_message_show($n) {
global $s;
check_admin('users');
ih();
$q = dq("select * from $s[pr]u_wall where n = '$n'",1);
$x = mysql_fetch_assoc($q);
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" align="center">'.$x[title].'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" colspan="2">'.$x[text].'</td>
</tr>
<tr>
<td align="center"><a href="users.php?action=user_edit&user='.$x[user].'">Show user</a></td>
<td align="center"><a href="users.php?action=board_message_delete&n='.$x[n].'">Delete this post</a></td>
</tr>
</table></td></tr></table><br />
';
ift();
}

#################################################################################

function friend_delete($in) {
global $s;
dq("delete from $s[pr]u_friends where ((user1 = '$in[user1]' and user2 = '$in[user2]') OR (user2 = '$in[user1]' and user1 = '$in[user2]')) limit 1",1);
$s[info] = info_line('Selected record has been deleted');
user_create_edit($in[user1]);
}

#################################################################################

function user_create_edit_form($user) {
global $s;
check_admin('users');
$user = stripslashes_array($user);
$joined = datum($user[joined],1);
$n = $user[n];
//foreach ($user as $k=>$v) echo "$k - $v<br />";

echo '<input type="hidden" name="user['.$n.'][n]" value="'.$n.'"></td>
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" align="center">'.$s[table_title].'</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';

if (($_GET[action]=='users_unapproved_show') OR ($_POST[action]=='users_approved'))
{ echo '<tr><td align="left" colspan="2" nowrap>
  Approve it <input type="radio" name="user['.$n.'][approve]" value="yes" id="approve_'.$n.'">
  <a class="link10" href="#" onClick="uncheck_both('.$n.'); return false;">Uncheck these boxes</a><br />
  Reject it <input type="radio" name="user['.$n.'][approve]" value="no" id="reject_'.$n.'">
  and send email: <select class="select10" name="user['.$n.'][reject_email]">'.$user[reject_emails].'
  </select>
  or <input type="checkbox" name="user['.$n.'][reject_email_custom]" value="1" id="fullcust'.$n.'" onclick="show_hide_div(document.getElementById(\'fullcust'.$n.'\').checked,document.getElementById(\'test'.$n.'\'));" value="1"> Individual Message
  <tr><td align="left" colspan="2">
  <div id="test'.$n.'" style="display:none;">
  <table border=0 width=100% cellspacing=2 cellpadding=0>
  <tr>
  <td align="left">Subject</td>
  <td><input class="field10" name="user['.$n.'][email_subject]" style="width:650px;"></td>
  </tr>
  <tr>
  <td align="left" valign="top">Text<br /><span class="text10">Available variables:<br />#%username%# - Username<br />#%email%# - User Email<br />#%name%# - Name<br /></span></td>
  <td><textarea class="field10" name="user['.$n.'][email_text]" style="width:650px;height:250px;"></textarea></td>
  </tr>
  </table></DIV>
  </td></tr>';
}
echo '<tr>
<td align="left">Username </td>
<td align="left"><input class="field10" name="user['.$n.'][username]" style="width:650px" maxlength="255" value="'.$user[username].'"></td>
</tr>
<tr>
<td align="left" valign="top">Password </td>
<td align="left" valign="top"><input class="field10" name="user['.$n.'][password]" style="width:650px" maxlength="15" value="'.$user[password].'">';
//if ($user[n]) echo '<br>Let this field blank to keep the current password';
echo '</td>
</tr>
<tr>
<td align="left">Name </td>
<td align="left"><input class="field10" name="user['.$n.'][name]" style="width:650px;" maxlength=255 value="'.$user[name].'"></td>
</tr>
<tr>
<td align="left">Nick </td>
<td align="left"><input class="field10" name="user['.$n.'][nick]" style="width:650px;" maxlength=255 value="'.$user[nick].'"></td>
</tr>
';
echo images_form_admin('u',$user,0);
echo '<tr>
<td align="left">Email </td>
<td align="left"><input class="field10" name="user['.$n.'][email]" style="width:650px;" maxlength=255 value="'.$user[email].'"></td>
</tr>
<tr>
<td align="left"><a target="nove" href="'.$user[url].'">URL</a> </td>
<td align="left"><input class="field10" name="user['.$n.'][url]" style="width:650px;" maxlength=255 value="'.$user[url].'"></td>
</tr>
<tr>
<td align="left">Site title</td>
<td align="left"><input class="field10" name="user['.$n.'][site_title]" style="width:650px;" maxlength=255 value="'.$user[site_title].'"></td>
</tr>
<tr>
<td align="left">Company </td>
<td align="left"><input class="field10" name="user['.$n.'][company]" style="width:650px;" maxlength=255 value="'.$user[company].'"></td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">User\'s article </td>
</tr>
<tr>
<td nowrap align="left" valign="top" colspan="2">'.get_fckeditor('user['.$n.'][detail]',$user[detail],'AdminToolbar').'</td>
</tr>
<tr>
<td align="left">Address line 1 </td>
<td align="left"><input class="field10" name="user['.$n.'][address1]" style="width:650px;" maxlength=255 value="'.$user[address1].'"></td>
</tr>
<tr>
<td align="left">Address line 2 </td>
<td align="left"><input class="field10" name="user['.$n.'][address2]" style="width:650px;" maxlength=255 value="'.$user[address2].'"></td>
</tr>
<tr>
<td align="left">Address line 3 </td>
<td align="left"><input class="field10" name="user['.$n.'][address3]" style="width:650px;" maxlength=255 value="'.$user[address3].'"></td>
</tr>
<tr>
<td align="left">Country </td>
<td align="left"><input class="field10" name="user['.$n.'][country]" style="width:650px;" maxlength=255 value="'.$user[country].'"></td>
</tr>
<tr>
<td align="left">Phone 1 </td>
<td align="left"><input class="field10" name="user['.$n.'][phone1]" style="width:650px;" maxlength=255 value="'.$user[phone1].'"></td>
</tr>
<tr>
<td align="left">Phone 2 </td>
<td align="left"><input class="field10" name="user['.$n.'][phone2]" style="width:650px;" maxlength=255 value="'.$user[phone2].'"></td>
</tr>
<tr>
<td align="left" valign="top">Newsletters </td>
<td align="left">';
for ($x=1;$x<=5;$x++)
{ if ($user["news$x"]) $checked = ' checked'; else $checked = '';
  if ($s["news_$x"]) echo '<input type="checkbox" name="user['.$n.'][news_'.$x.']" value="1"'.$checked.'>'.$s["news_$x"].'<br />';
}
echo '</td>
</tr>
<tr>
<td align="left">Style </td>
<td align="left"><select class="select10"name="user['.$n.'][style]">'; echo styles_select_box($user[style]); echo '</select></td>
</tr>';
if ($s[l_who]==2)
{ echo '<tr>
  <td align="left">Can submit links </td>
  <td align="left"><input type="checkbox" name="user['.$n.'][post_links]" value="1"'; if ($user[post_links]) echo ' checked'; echo '></td>
  </tr>';
}
if ($s[a_who]==2)
{ echo '<tr>
  <td align="left">Can post articles </td>
  <td align="left"><input type="checkbox" name="user['.$n.'][post_art]" value="1"'; if ($user[post_art]) echo ' checked'; echo '></td>
  </tr>';
}
if ($s[b_who]==2)
{ echo '<tr>
  <td align="left">Can post blogs </td>
  <td align="left"><input type="checkbox" name="user['.$n.'][post_blogs]" value="1"'; if ($user[post_blogs]) echo ' checked'; echo '></td>
  </tr>';
}
echo '<tr>
<td align="left">Approved by admin </td>
<td align="left" nowrap><input type="checkbox" name="user['.$n.'][approved]" value="1"'; if (($user[approved]) OR (!$user[n])) echo ' checked'; echo '></td>
</tr>';
if ($user[n]) echo '<tr>
<td align="left" valign="top" nowrap>Current funds balance&nbsp; </td>
<td align="left">'.$s[currency].$user[funds_now].'</td>
</tr>';
echo '<tr>
<td align="left" valign="top" nowrap>Add free funds&nbsp; </td>
<td align="left">'.$s[currency].'<input class="field10" style="width:100px" name="user['.$n.'][funds]" maxlength=15><br /><span class="text10">This can be used to purchase clicks, impressions, days for advertising links.</span></td>
</tr>
</table></td></tr></table><br />';
}

#################################################################################

function user_edited($in) {
global $s;
check_admin('users');
foreach ($in[user] as $k=>$v) { $user = $v; $user[n] = $k; user_edited_process($user); }
$s[info] = info_line('Selected user has been updated');
user_create_edit($user[n]);
}

######################################################################################

function user_edited_process($in) {
global $s;
$old = get_user_variables($in[n],'');
if ($in[joined]) $joined = ', joined = '.get_timestamp($in[joined][d],$in[joined][m],$in[joined][y],'end');
$x = get_user_variables(0,$in[username]);
if (($x[n]) AND ($x[n]!=$in[n])) { $s[info] = info_line('Entered username is already in use'); user_create_edit($in[n]); }
$in = replace_array_text($in);
$in[detail] = refund_html($in[detail]);
dq("update $s[pr]users set username = '$in[username]', password = '$in[password]', approved = '$in[approved]', email = '$in[email]', name = '$in[name]', nick = '$in[nick]', url = '$in[url]', site_title = '$in[site_title]', showemail = '$in[showemail]', news1 = '$in[news_1]', news2 = '$in[news_2]', news3 = '$in[news_3]', news4 = '$in[news_4]', news5 = '$in[news_5]', style = '$in[style]',  post_art = '$in[post_art]', post_blogs = '$in[post_blogs]', post_links = '$in[post_links]', company = '$in[company]', detail = '$in[detail]', address1 = '$in[address1]', address2 = '$in[address2]', address3 = '$in[address3]', phone1 = '$in[phone1]', phone2 = '$in[phone2]', country = '$in[country]', funds_paid = funds_paid + '$in[funds]', funds_incl = funds_incl + '$in[funds]', funds_now = funds_now + '$in[funds]' where n = '$in[n]'",1);
dq("update $s[pr]articles set email = '$in[email]', name = '$in[name]' where owner = '$in[n]'",1);
dq("update $s[pr]links set email = '$in[email]', name = '$in[name]' where owner = '$in[n]'",1);
dq("update $s[pr]comments set email = '$in[email]', name = '$in[name]', user = '$in[username]' where user = '$old[username]'",1);
upload_files('u',$in[n],0,0,$in[delete_image]);
update_items_for_user($in[n]);
}

######################################################################################
######################################################################################
######################################################################################

function user_delete($n) {
global $s;
check_admin('users');
user_delete_process($n);
ih();
echo info_line('User deleted');
echo '<a href="javascript: history.go(-1)">Back</a>';
echo ift();
}

#################################################################################

function user_delete_process($n) {
global $s;
delete_images('u',$n);
dq("delete from $s[pr]users where n = '$n'",1);
dq("delete from $s[pr]u_favorites where user = '$n'",1);
dq("delete from $s[pr]u_private_notes where user = '$n'",1);
dq("delete from $s[pr]adlinks where owner = '$n'",1);
dq("delete from $s[pr]links_extra_orders where user = '$n'",1);
dq("update $s[pr]links set owner = '0' where owner = '$n'",1);
dq("update $s[pr]articles set owner = '0' where owner = '$n'",1);
}

#################################################################################

function styles_select_box($selected) {
global $s;
if (!$selected) $selected = $s[def_style];
$styles = get_styles_list(0);
foreach ($styles as $k=>$v)
{ if ($v==$selected) $x = ' selected'; else $x = '';
  $a .= '<option value="'.$v.'"'.$x.'>'.$v.'</option>';
}
return $a;
}

#################################################################################
#################################################################################
#################################################################################

function newsletter_form($data) {
global $s;
check_admin('newsletter');
ih();
echo $s[info];
if ($data) { $text = $data[text]; $subject = $data[subject]; }
else
{ $template = join ('',file("$s[phppath]/styles/_common/email_templates/newsletter.txt"));
  preg_match("/Subject: +([^\n\r]+)/i",$template,$regs); $subject = $regs[1];
  $subject = str_replace('HTML_EMAIL','',$subject); if ($subject!=$regs[1]) $htmlmail = 1;
  $text = preg_replace("/Subject: +([^\n\r]+)[\r\n]+/i",'',$template);
}
$subject = stripslashes($subject); $text = stripslashes($text);
if (!$data[days]) $days = 7; else $days = $data[days];
echo '<form action="users.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="newsletter">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Newsletter</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left">&nbsp;Send it to subscribers of newsletter <br />';
if (!$_POST[newsletter]) $_POST[newsletter] = 1;
for ($x=1;$x<=5;$x++)
{ if ($s["news_$x"])
  { if ($s["send_newsletter_$x"]) $last = datum($s["send_newsletter_$x"],1); else $last = 'Never yet';
    echo '<input type="radio" name="newsletter" value="'.$x.'"'; if ($_POST[newsletter]==$x) echo ' checked'; echo '>'.$s["news_$x"].' <span class="text10">Sent last time: '.$last.'</span><br />'; }
}
?>
</td></tr>
<tr><td align="left">
The following variables may be used in the text field:<br />
<b>#%name%#</b> for name of the user<br />
<b>#%links%#</b> for the list of links created in last days (select below how many days should be included)<br />
<b>#%articles%#</b> for the list of articles created in last days (select below how many days should be included)<br />
<b>#%blogs%#</b> for the list of blogs created in last days (select below how many days should be included)<br />
<b>#%videos%#</b> for the list of videos created in last days (select below how many days should be included)<br />
<b>#%news%#</b> for the list of news created in last days (select below how many days should be included)<br />
<b>#%unsubscribe%#</b> for the URL where the user can modify his/her profile<br />
</td></tr>
<tr><td align="left"> 
Replace variables #%links%#, #%articles%#, #%videos%#, #%news%# with items created in the last <input class="field10" type="text" size="2" name="days" value="<?PHP echo $days; ?>"> days</td></tr>
<tr><td align="left">Subject: <input class="field10" type="text" style="width:650px;" name="subject" value="<?PHP echo $subject; ?>"></td></tr>
<tr><td align="left">Text:<br /><textarea class="field10" style="width:700px;height:300px;" name="text"><?PHP echo $text; ?></textarea></td></tr>
<tr><td align="left"> Message format &nbsp;&nbsp;&nbsp;<input type="radio" name="htmlmail" value="0" checked> Text &nbsp;&nbsp;&nbsp;<input type="radio" name="htmlmail" value="1"> HTML</td></tr>
<tr><td align="left"> 
<input type="radio" value="preview" name="what" checked> Show preview<br />
<input type="radio" value="send" name="what"> Send now<br />
<input type="radio" value="test" name="what"> Send only a test email to <input class="field10" type="text" style="width:650px;" name="test_email" value="<?PHP echo $s[mail]; ?>"></td></tr>
<tr><td align="center"><input type=submit name=x value="Submit" class="button10"></td></tr>
</table>
</td></tr></table>
</form><br />
<?PHP
ift();
}

##############################################################################

function newsletter($data) {
global $s;
check_admin('newsletter');
if ((!$data[text]) OR (!$data[subject]))     
{ if (($data[text]) OR ($data[subject])) $s[info] = info_line('Both fields are required.');
  newsletter_form($data);
}
if (!$data[days]) $data[days] = 7; $cas = $s[cas] - ($data[days] * 86400);
$q = dq("select n,name from $s[pr]cats",1); while ($x = mysql_fetch_assoc($q)) $categories[$x[n]] = $x[name];
foreach ($s[item_types_short] as $k=>$what)
{ $where = get_where_fixed_part('',0,'',$s[cas]);
  $table = $s[item_types_tables][$what];
  $q = dq("select * from $table where created > '$cas' and $where order by created desc",1);
  while ($item = mysql_fetch_assoc($q))
  { $item[created] = datum ($item[created],0);
    $item[url] = get_detail_page_url($what,$item[n],$item[rewrite_url],0,1);
    $cat = explode(' ',str_replace('_','',$item[c]));
    $item[category] = $categories[$cat[0]];
    $item[catlink] = category_url($what,$cat[0],0,$categories[$cat[0]],1,'','','','');
    $b[$s[item_types_words][$what]] .= parse_part('newsletter_item.txt',$item,1);
  }
}

if ($data[what] == 'preview')
{ foreach ($s[items_types_words] as $k=>$what) $data[text] = str_replace("#%$what%#",$b[$s[item_types_words][$k]],$data[text]);
  $data = replace_array_text($data);
  newsletter_form($data);
  exit;
}
if ($data[what]=='test')
{ $line = $data[text]; $data[subject] = unreplace_once_html($data[subject]);
  foreach ($s[items_types_words] as $k=>$what) $value[$what] = $b[$s[item_types_words][$k]];
  $value[name] = $address[1]; $value[email] = $data[test_email];
  $value[unsubscribe] = "$s[site_url]/";
  foreach($value as $k => $v) $line = str_replace("#%$k%#",$v,$line);
  $line = unreplace_once_html($line);
  $line = unhtmlentities($line); $data[subject] = unhtmlentities($data[subject]);
  my_send_mail('','',$data[test_email],$data[htmlmail],$data[subject],$line,1);
  $s[info] = info_line('Test email has been sent to '.$data[test_email]);
  newsletter_form($data);
}
elseif ($data[what]=='send')
{ $emaily = dq("select * from $s[pr]users where news$data[newsletter] = '1' and approved = '1' and confirmed = '1'",1);
  $num_rows = mysql_num_rows($emaily);
  if (!$num_rows) { ih(); echo info_line('There are no subscribers of newsletter #'.$data[newsletter]); ift(); }
  ih();
  echo info_line('Newsletter has been sent to:');
  while ($address = mysql_fetch_assoc($emaily))
  { $line = $data[text]; $data[subject] = unreplace_once_html($data[subject]);
  foreach ($s[items_types_words] as $k=>$what) $value[$what] = $b[$s[item_types_words][$k]];
    $value[name] = $address[name]; $value[email] = $address[email];
    $value[unsubscribe] = "$s[site_url]/";
    foreach($value as $k => $v) $line = str_replace("#%$k%#",$v,$line);
    $line = unreplace_once_html($line);
    $line = unhtmlentities($line); $data[subject] = unhtmlentities($data[subject]);
    my_send_mail('','',$address[email],$data[htmlmail],$data[subject],$line,1);
    echo "$address[email]<br />\n";
    set_time_limit(30);
  }
  unset($data);
  $fp = fopen("$s[phppath]/data/newsletter.php","w");
  $s["send_newsletter_$data[newsletter]"] = $s[cas];
  for ($x=1;$x<=5;$x++) { if (!$s["send_newsletter_$x"]) $s["send_newsletter_$x"] = 0; $data .= '$s[send_newsletter_'.$x.'] = '.$s["send_newsletter_$x"].';'; }
  fwrite ($fp,'<?PHP '.$data.' ?>');
  fclose($fp);
  chmod("$s[phppath]/data/newsletter.php",0666);

  ift();
}
exit;
}

##############################################################################
##############################################################################
##############################################################################

function email_users_above_form($email_users_array) {
global $s;
check_admin('email_users');
$data = replace_array_text($data);
ih();
echo $s[info];
echo '<form action="users.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="emailed_users">';
foreach ($email_users_array as $k=>$v) echo '<input type="hidden" name="users_list[]" value="'.$v.'">';
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Email All Users Above</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan="2"> 
The following variables can be used in the text:<br />
#%username%# for username of the user<br />
#%name%# for name of the user<br />
#%password%# for password of the user<br />
</td></tr>
<tr>
<td align="left">Subject</td>
<td align="left"><input class="field10" type="text" style="width:650px;" name="subject" value="'.$data[subject].'"></td>
</tr>
<tr>
<td align="left" valign="top">Text</td>
<td align="left" valign="top"><textarea class="field10" style="width:650px;height:300px;" name="text">'.$data[text].'</textarea></td>
</tr>
<tr>
<td align="left">Message format</td>
<td align="left"><input type="radio" name="htmlmail" value="0" checked> Text &nbsp;&nbsp;&nbsp;<input type="radio" name="htmlmail" value="1"> HTML</td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="B" value="Send now" class="button10"></td></tr>
</table>
</td></tr></table>
</form><br />';
ift();
}

##########################################################################

function email_users($data) {
global $s;
check_admin('email_users');
$data = replace_array_text($data);
ih();
echo $s[info];
echo '<form action="users.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="emailed_users">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Email All Registered Users</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" colspan="2"> 
The following variables can be used in the text:<br />
#%username%# for username of the user<br />
#%name%# for name of the user<br />
#%password%# for password of the user<br />
</td></tr>
<tr>
<td align="left">Subject</td>
<td align="left"><input class="field10" type="text" style="width:650px;" name="subject" value="'.$data[subject].'"></td>
</tr>
<tr>
<td align="left" valign="top">Text</td>
<td align="left" valign="top"><textarea class="field10" style="width:650px;height:300px;" name="text">'.$data[text].'</textarea></td>
</tr>
<tr>
<td align="left">Message format</td>
<td align="left"><input type="radio" name="htmlmail" value="0" checked> Text &nbsp;&nbsp;&nbsp;<input type="radio" name="htmlmail" value="1"> HTML</td>
</tr>
<tr>
<td align="left" valign="top">Action</td>
<td align="left" valign="top">
<input type="radio" value="send" name="what"> Send now<br />
<input type="radio" value="test" name="what" checked> Send only a test email to <input class="field10" type="text" style="width:300px;" name="test_email" value="'.$s[mail].'"></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="B" value="Send now" class="button10"></td></tr>
</table>
</td></tr></table>
</form><br />';
ift();
}

##########################################################################

function emailed_users($in) {
global $s;
check_admin('email_users');
if ((!$in[text]) OR (!$in[subject]))
{ if ($in[users_list]) problem('Both fields are required');
  $s[info] = info_line('Both fields are required');
  email_users($in);
}
ih();
if ($in[what] == 'test')
{ $line = $in[text];
  $value[name] = $address[1]; $value[username] = $address[0]; $value[email] = $in[test_email]; $value[password] = $address[3];
  while( list ($key, $val) = each ($value)) 
  { $line = str_replace("#%$key%#",$val,$line);
    $line = unreplace_once_html($line);
  }
  reset ($value);
  $line = unhtmlentities(unreplace_once_html($line)); $in[subject] = unhtmlentities(unreplace_once_html($in[subject]));
  my_send_mail('','',$in[test_email],$in[htmlmail],$in[subject],$line,1);
  $s[info] = info_line('Test email has been sent to '.$in[test_email]);
  email_users($in);
}

if ($in[users_list]) $where = my_implode('n','or',$in[users_list]);
else $where = " approved = '1' and confirmed = '1'";
$emaily = dq("select * from $s[pr]users where $where",1);
while ($address = mysql_fetch_assoc($emaily))
{ set_time_limit(300);
  $line = $in[text];
  foreach ($address as $k => $v)
  { $line = str_replace("#%$k%#","$v",$line);
    $line = unreplace_once_html($line);
  }
  reset ($value);
  $line = unhtmlentities(unreplace_once_html($line));
  $in[subject] = unhtmlentities(unreplace_once_html($in[subject]));
  my_send_mail('','',$address[email],$in[htmlmail],$in[subject],$line,1);
  $seznam .= "<br />$address[email]\n";
}
ih();
echo info_line('Emails has been sent to:',$seznam);
ift();
}

##########################################################################
##########################################################################
##########################################################################

function users_unapproved_show($in) {
global $s;
check_admin('users');
if (!$in[from]) $from = 0; else $from = $in[from] - 1;
$q = dq("select count(*) from $s[pr]users where approved = '0' and confirmed = '1'",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet) { ih(); echo $s[info].info_line('No one user in the queue'); ift(); }
$show[0] = $from + 1;
$show[1] = $from + $in[perpage]; if ($show[1]>$pocet) $show[1] = $pocet; if (!$in[perpage]) $show[1] = $pocet;

if (($in[perpage]) AND ($pocet>$in[perpage]))
{ $rozcesti = '
  <form ENCTYPE="multipart/form-data" action="users.php" method="get" name="form1">
  <input type="hidden" name="action" value="users_unapproved_show">
  <input type="hidden" name="perpage" value="'.$in[perpage].'">
  Show users with begin of&nbsp;&nbsp;<select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($pocet/$in[perpage]);  
  for ($x=1;$x<$y;$x++)
  { $od = $x * $in[perpage] + 1;
    $rozcesti .= "<option value=\"$od\">$od</option>";
  }
  $rozcesti .= '</select>&nbsp;&nbsp;<input type="submit" value="Submit" name="B1" class="button10">
  </form><br />';
}

if ($in[perpage]) $limit = " limit $from,$in[perpage]";
$q = dq("select * from $s[pr]users where approved = '0' and confirmed = '1' order by n $limit",1);
$reject_emails = get_reject_emails_list('reject_user_');
ih();

echo $s[info].info_line('Users in the Queue: '.$pocet.', Showing Users '.$show[0].' - '.$show[1]).$rozcesti;

while ($x = mysql_fetch_assoc($q)) { $user[$x[n]] = $x; $numbers[] = $x[n]; }

echo '<form ENCTYPE="multipart/form-data" action="users.php" method="post" name="muj">'.check_field_create('admin').'
<input type="hidden" name="action" value="users_approved">
<input type="hidden" name="perpage" value="'.$in[perpage].'">
<input type="hidden" name="from" value="'.$from.'">';

foreach ($user as $k=>$v)
{ $v[reject_emails] = $reject_emails;
  user_create_edit_form($v);
}
echo '<input type="submit" name="submit" value="Submit" class="button10"></form>';
ift();
}

##################################################################################

function users_approved($in) {
global $s;
check_admin('users');
$s[info] = '';
foreach ($in[user] as $key=>$value)
{ if (!$in[user][$key][approve]) continue;
  $user = $value; $user[n] = $key;
  $oznamit = 0;
  $q = dq("select * from $s[pr]users where n = '$user[n]'",1);
  $old_data = mysql_fetch_assoc($q); $user = array_merge((array)$old_data,(array)$user);
  if ($user[approve]=='yes')
  { $user[approved] = 1; user_edited_process($user);
    $s[info] .= 'User '.$user[username].' has been approved.<br>';
    $oznamit = 1;
  }
  elseif ($user[approve]=='no')  // reject
  { $s[info] .= 'User '.$user[username].' has been rejected.<br>';
    $oznamit = 1;
    user_delete_process($user[n]);
  }
  // send emails
  if (!$oznamit) continue;
  unset($email_sent); $user[to] = $user[email];
  if ($user[approve]=='no')
  { if ($user[reject_email]) mail_from_template($user[reject_email],$user);
    elseif (($user[reject_email_custom]) AND ($user[email_subject]) AND ($user[email_text]))
    { while (list($k,$v) = each($user)) $user[email_text] = str_replace("#%$k%#",$v,$user[email_text]);
      my_send_mail('','',$user[to],0,unhtmlentities($user[email_subject]),unhtmlentities($user[email_text]),1);
	}
  }
  elseif (($user[approve]=='yes') AND ($s[user_i_approved])) mail_from_template('user_approved.txt',$user);
}
if ($s[info]) $s[info] .= '<br>';
users_unapproved_show($in);
exit;
}

######################################################################################
######################################################################################
######################################################################################

?>