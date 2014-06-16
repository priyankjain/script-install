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
check_admin('adv_prices_orders');

switch ($_GET[action]) {
case 'orders_search'			: orders_search();
case 'orders_searched'			: orders_searched($_GET);
case 'order_mark_paid'			: order_mark_paid($_GET);
case 'order_delete'				: order_delete($_GET[n]);
}
switch ($_POST[action]) {
}

############################################################################
############################################################################
############################################################################

function orders_search() {
global $s;
ih();
echo page_title('Orders');
$q = dq("select count(*) from $s[pr]links_extra_orders where paid = '0'",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if ($pocet)
{ echo '  <form method="get" action="orders_payments.php">
  <input type="hidden" name="action" value="orders_searched">
  <input type="hidden" name="paid" value="no">
  <input type="hidden" name="edit_forms" value="1">
  <table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td class="common_table_top_cell" colspan="2">Queue</td></tr>
  <tr><td align="center" width="100%">
  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
  <tr><td align="center" nowrap>Orders in the queue: '.$pocet.'</td></tr>
  <tr><td align="center" nowrap>Select number of orders to display on one page<br />
  <select class="select10" name="perpage"><option value="0">All</option>';
  if ($pocet>10) echo '<option value="10">10</option>';
  if ($pocet>20) echo '<option value="20">20</option>';
  if ($pocet>30) echo '<option value="30">30</option>';
  echo '</select> <input type="submit" value="Submit" name="B1" class="button10"></td></tr>
  </table></td></tr></table></form>
  <br />';
}

echo '<form method="GET" action="orders_payments.php">
<input type="hidden" name="action" value="orders_searched">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan="2">Search for Orders & Payments</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" nowrap>Order number </td>
<td align="left"><input class="field10" name="n" style="width:100px" maxlength=10></td>
</tr>
<tr>
<td align="left" nowrap>Owner\'s username </td>
<td align="left"><input class="field10" name="username" style="width:100px" maxlength=15></td>
</tr>
<tr>
<td align="left" nowrap>Order type / Package purchased </td>
<td align="left"><select class="select10" name="package"><option value="0">N/A</option>'.packages_select_box().'</select></td>
</tr>
<tr>
<td align="left" nowrap>Paid </td>
<td align="left" nowrap><input type="radio" name="paid" value="0"> N/A &nbsp;&nbsp;<input type="radio" name="paid" value="yes" checked> Yes &nbsp;&nbsp;<input type="radio" name="paid" value="no"> No</td>
</tr>
<tr><td align="left" nowrap>Results per page </td>
<td align="left"><select class="select10" name="perpage">
<option value="0">All</option><option value="10">10</option><option value="20">20</option>
<option value="50">50</option><option value="100">100</option>
<option value="200">200</option><option value="500">500</option></select>
</td></tr>
<tr>
<td align="left" nowrap>Sort by </td>
<td align="left"><select class="select10" name="sort"><option value="order_time">Date created</option><option value="user">Owner\'s number</option></select><select class="select10" name="order"><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>
</tr>
<tr><td colspan=2 align="center"><input type="submit" value="Search" name="B1" class="button10"></td></tr>
</table></td></tr></table></form><br />';
ift();
}

############################################################################

function orders_searched($in) {
global $s;
//foreach ($in as $k=>$v) echo "$k - $v<br />";
if ($in[n]) $where = "$s[pr]links_extra_orders.n = '$in[n]'";
else
{ if ($in[username])
  { $user = get_user_variables(0,$in[username]);
    $w[] = "user = '$user[n]'";
  }
  if ($in[user]) $w[] = "user = '$in[user]'";
  if ($in[package]=='simple_order_links') $w[] = "payment_type = 'link'";
  elseif ($in[package]=='simple_order_adlinks') $w[] = "payment_type = 'adlink'";
  elseif ($in[package]) $w[] = "(payment_type = 'package' and link_or_pack = '$in[package]')";
  if ($in[paid])
  { if ($in[paid]=='yes') $w[] = "paid = '1'";
    elseif ($in[paid]=='no') $w[] = "paid = '0'";
  }
  if ($w) $where = join (' AND ',$w);
}
if ($where) $where = " where $where ";

if (!$in[from]) $in[from] = 0; else $in[from] = $in[from] - 1;
if ($in[perpage]) $limit = " limit $in[from],$in[perpage]";

if ($in[sort]) $orderby = "order by $in[sort]";

$x = dq("select count(*) from $s[pr]links_extra_orders $where",1);
$pocet = mysql_fetch_row($x); $pocet = $pocet[0];

$q = dq("select * from $s[pr]links_extra_orders $where $orderby $in[order] $limit",1); 

ih();

if ( ($in[perpage]) AND ($pocet>$in[perpage]) )
{ $rozcesti = '<form action="orders_payments.php" method="GET" name="form1"><input type="hidden" name="action" value="orders_searched">';
  foreach ($in as $k => $v) { if ($v) $rozcesti .= '<input type="hidden" name="'.$k.'" value="'.$v.'">'; }
  $rozcesti .= 'Show records with begin of <select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($pocet/$in[perpage]);  
  for ($x=1;$x<$y;$x++) { $od = $x*$in[perpage]+1; $rozcesti .= '<option value="'.$od.'">'.$od.'</option>'; }
  $rozcesti .= '</select>&nbsp;&nbsp;<input type="submit" value="Submit" name="B1" class="button10">
  </form>';
}
else $rozcesti = '<br />';

$od = $in[from]+1;
$do = $in[from]+$in[perpage]; if ($do>$pocet) $do = $pocet; if (!$in[perpage]) $do = $pocet;

echo $s[info].'<span class="text13a_bold">Orders Found: '.$pocet;
if ( ($pocet>1) AND ($od!=$do) ) echo ", Showing Orders $od - $do";
echo '</span>';
echo "$rozcesti<br />";
$in[from] = $in[from] + 1;
while ($order=mysql_fetch_assoc($q)) show_one_order($order);
ift();
}

############################################################################
############################################################################
############################################################################

function show_one_order($data) {
global $s;
$user_vars = get_user_variables($data[user]);
if ($data[paid]) $paid = '<font color="#00BD30">Yes</font>'; 
else
{ $paid = '<font color="red">No</font>';
  $mark_paid = '[<a href="orders_payments.php?action=order_mark_paid&n='.$data[n].'">Mark it as paid</a>]&nbsp;';
}
$data = stripslashes_array($data);
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" width="20%" nowrap>Order number</span></TD>
<td align="left" width="80%" nowrap>'.$data[n].'</span></TD>
</TR>
<tr>
<td align="left" nowrap>Paid</span></TD>
<td align="left" nowrap>'.$paid.'</span></TD>
</TR>
<tr>
<td align="left" nowrap>User</span></TD>
<TD align="left" nowrap><a href="users.php?action=users_searched&n='.$data[user].'&boolean=and&sort=username&order=asc">'.$user_vars[username].'</a></span></TD>
</TR>
<tr>
<td align="left" nowrap>Amount</span></TD>
<td align="left" nowrap>'.$s[currency].$data[price].'</TD>
</TR>';
if ($data[payment_type]=='package')
{ $package = get_adv_package_variables($data[link_or_pack]);
  echo '<tr>
  <td align="left" nowrap>Related package </span></TD>
  <td align="left" nowrap><a href="prices.php?pack='.$data[link_or_pack].'&action=adv_package_edit">'.$package[title].'</a></TD>
  </TR>
  <tr>
  <td align="left" nowrap>Bonus</span></TD>
  <td align="left" nowrap>'.$package[bonus].'%</TD>
  </TR>
  <tr>
  <td align="left" nowrap>Value</span></TD>
  <td align="left" nowrap>'.$s[currency].number_format(($package[price]+($package[price]*$package[bonus]/100)),2).'</TD>
  </TR>';
}
elseif ($data[payment_type]=='link')
{ $link = get_item_variables('l',$data[link_or_pack]);
  echo '<tr>
  <td align="left" nowrap>Related link</span></TD>
  <td align="left" nowrap><a href="links.php?action=links_searched&n='.$data[link_or_pack].'&boolean=and&sort=title&order=asc">#'.$data[link_or_pack].' '.$link[title].'</a></TD>
  </TR>
  <tr>
  <td align="left" nowrap>Days included</span></TD>
  <td align="left" nowrap>'.round($data[days_clicks_or_value]).'</TD>
  </TR>';
}
elseif ($data[payment_type]=='adlink')
{ $adlink = get_adlink_variables($data[link_or_pack]);
  echo '<tr>
  <td align="left" nowrap>Related AdLink</span></TD>
  <td align="left" nowrap><a href="adlinks.php?action=adlinks_searched&n='.$data[link_or_pack].'&boolean=and&sort=title&order=asc">#'.$data[link_or_pack].' '.$adlink[title].'</a></TD>
  </TR>
  <tr>
  <td align="left" nowrap>Clicks included</span></TD>
  <td align="left" nowrap>'.round($data[days_clicks_or_value]).'</TD>
  </TR>';
}

echo '<tr>
<td align="left" nowrap>Order time</span></TD>
<td align="left" nowrap>'.datum ($data[order_time],1).'&nbsp;</span></TD>
</TR>
<tr>
<td align="left" valign="top" nowrap>Details</span></TD>
<td align="left">'.nl2br($data[notes]).'&nbsp;</span></TD>
</TR>
<tr><td align="left" colspan=2>'.$mark_paid.'[<a target="_self" href="javascript: go_to_delete(\'Are you sure?\',\'orders_payments.php?action=order_delete&n='.$data[n].'\')">Delete</a>]</TD></TR>
</table>
</td></tr></table>
<br />';
}

############################################################################

function order_mark_paid($in) {
global $s;
order_update_payment_info($in[n],1,'Admin','Marked as paid by admin','',0);
$order = get_order_variables($in[n]);
if ($order[payment_type]=='package') $message = "Funds added to user's account: $s[currency]$order[days_clicks_or_value]";
elseif ($order[payment_type]=='link') $message = "Days added to link #$order[link_or_pack]: ".round($order[days_clicks_or_value]);
elseif ($order[payment_type]=='adlink') $message = "Clicks added to AdLink #$order[link_or_pack]: ".round($order[days_clicks_or_value]);
ih();
echo info_line('Selected order has been marked as paid.',$message);
echo '<a href="javascript: history.go(-1)">Back to previous page</a>';
ift();
}

############################################################################

function order_delete($n) {
global $s;
dq("delete from $s[pr]links_extra_orders where n = '$n'",1);
ih();
echo info_line('Selected order has been deleted');
echo '<a href="javascript: history.go(-1)">Back to previous page</a>';
ift();
}

##################################################################################

function packages_select_box() {
global $s;
$a .= '<option value="simple_order_links">Simple order, links (no package)</option><option value="simple_order_adlinks">Simple order, AdLinks (no package)</option>';
$q = dq("select * from $s[pr]adv_packs order by price",1);
while ($x = mysql_fetch_assoc($q)) $a .= '<option value="'.$x[n].'">'.htmlspecialchars(stripslashes($x[title])).'</option>';
return $a;
}

############################################################################
############################################################################
############################################################################

?>