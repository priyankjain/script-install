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
case 'prices_home'				: prices_home();
case 'adv_packages_home'		: adv_packages_home();
case 'adv_package_edit'			: adv_package_edit($_GET);
case 'adv_package_delete'		: adv_package_delete($_GET);
}
switch ($_POST[action]) {
case 'prices_edited'			: prices_edited($_POST);
case 'adv_package_created'		: adv_package_created($_POST);
case 'adv_package_edited'		: adv_package_edited($_POST);
}

##################################################################################
##################################################################################
##################################################################################

function prices_edited($in) {
global $s;
dq("delete from $s[pr]links_adv_prices",1);

foreach ($in[days] as $k=>$days)
{ $price = $in[price][$k];
  if ((!$days) OR (!$price)) continue;
  dq("insert into $s[pr]links_adv_prices values ('$days','$price')",1);
}
$s[info] = info_line('Prices Updated');
prices_home();
}

##################################################################################

function prices_home() {
global $s;
ih();
echo $s[info];
echo '<form action="prices.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="prices_edited">
<table border="0" width="300" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Prices</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="center">Days</td>
<td align="center">Price</td>
</tr>';

$q = dq("select * from $s[pr]links_adv_prices order by days",1);
while ($price=mysql_fetch_assoc($q))
{ echo '<tr>
 <td align="center"><input class="field10" name="days[]" value="'.$price[days].'" size=10 maxlength=10></td>
 <td align="center">'.$s[currency].'<input class="field10" name="price[]" value="'.$price[price].'" size=10 maxlength=10></td>
 </tr>';
}
for ($x=1;$x<=10;$x++)
{ echo '<tr>
 <td align="center"><input class="field10" name="days[]" size=10 maxlength=10></td>
 <td align="center">'.$s[currency].'<input class="field10" name="price[]" size=10 maxlength=10></td>
 </tr>';
}
echo '<tr><td align="center" colspan="11"><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form>
<br />
<table border=0 width=600 cellspacing=0 cellpadding=2 class="common_table">
<tr><td class="common_table_top_cell">Info</td></tr>
<tr><td align="left"><span class="text10">
Link owners can order advertising links for the prices you set here. You can have any number of offers - periods. These prices are used in the simple order form - link owners choose what is the period they want to order and are sent to payment page. If they use PayPal or 2CheckOut for the payment, it\'s processed automatically and the link immediately gets the advertising features visible. It may include for example extra pictures, more info, depending on your configuration. Advertising links are also highlighted and are always displayed at the top of categories and search results.<br /><br />
This is not the only option for users to pay for extra link features. They also can use <a href="prices.php?action=adv_packages_home">packages</a>. By using this option your users can pay for clicks, impressions or time period. They also can set their own price for each click. If this is used, more expensive links are displayed at the top in categories and search results.
</span></td></tr></table>';
ift();
}

##################################################################################
##################################################################################
##################################################################################

function adv_packages_home() {
global $s;
$packs = packs_select();
ih();
echo $s[info];
echo page_title('Advertising Packages');
?>
<form action="prices.php" method="post"><?PHP echo check_field_create('admin') ?>
<input type="hidden" name="action" value="adv_package_created">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan="2">Create a new package</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left">Description <br /><span class="text10">It should contain all the info you want to provide to your advertisers</span></td>
<td align="left"><input class="field10" style="width:650px;" name="title" maxlength=100></td>
</tr>
<tr>
<td align="left">Price&nbsp;&nbsp;<br /><span class="text10">Enter only numbers, no dots, monetary units or other characters&nbsp;&nbsp;</span></td>
<td align="left"><input class="field10" style="width:100px" name="price" maxlength=10></td>
</tr>
<tr>
<td align="left">Bonus&nbsp;&nbsp;</td>
<td align="left"><input class="field10" style="width:100px" name="bonus" maxlength=10> %</td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table>
</form>
<br />
<form action="prices.php" method="get" name="form1">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Existing packages</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align=center><select class="select10" name="pack"><?PHP echo $packs ?></select></td></tr>
<tr><td align=center>Action: 
<input type="radio" name="action" value="adv_package_edit" checked>Edit
<input type="radio" name="action" value="adv_package_delete">Delete
</td></tr>
<tr><td align="center"><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form>
<br />
<?PHP
packages_info();
ift();
}

##################################################################################

function packages_info() {
?>
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Info</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left">
<span class="text10"><b>How it works</b><br />You create any number of packages here. Advertisers can select from these packages when they want to add funds to their accounts. Once an advertiser orders a package, he/she gets a button or link leading to your payment company. Automatic payments are available for Paypal and 2CheckOut but you can use any payment company, in this case an admin has to manually review and approve each order. Once an order has been paid, it's owner can immediately use the purchased funds to create a new link or to add new clicks/impressions/days to an existing link.<br /><br />
<b>What is ...<br />
Price</b> A real price which your users pay for a package<br />
<b>Bonus</b> You can add a free bonus to more extensive packages to stimulate users to purchase them. Example: If the base price of a package is $500 and you add a 20% bonus, then the user will pay $500 but he/she can use impressions or clicks for $600.<br /><br />
This is not the only option for users to pay for extra link features. Your users also can use <a href="prices.php?action=prices_home">simple prices</a>. By using this option your users can pay for a number of days. You can set multiple periods and a price for each of them. This order form is simple - they add days to a particular link, without using packages.
</span></td></tr>
</table></td></tr></table>

<br />
<?PHP
}

##################################################################################

function adv_package_created($form) {
global $s;
$form[title] = replace_once_text($form[title]);
$form[html] = replace_once_html($form[html]);
if ( (!$form[title]) OR (!$form[price]) ) problem("Please go back and fill in all fields.");
if (!is_numeric($form[price])) problem("Please insert only numbers to the Price field.");
dq("insert into $s[pr]adv_packs values (NULL,'$form[title]','$form[price]','$form[bonus]')",1);
$s[info] = info_line('New Advertising Package Has Been Created');
adv_packages_home();
}

##################################################################################

function adv_package_delete($form) {
global $s;
if (!$form[pack]) problem('An error has occurred. Please go back and try again.');
dq("delete from $s[pr]adv_packs where n = '$form[pack]'",1);
$s[info] = info_line('Selected package has been deleted');
adv_packages_home();
}

##################################################################################

function adv_package_edit($form) {
global $s;
ih();

$q = dq("select * from $s[pr]adv_packs where n='$form[pack]'",1);
$data = mysql_fetch_assoc($q);
$data = stripslashes_array($data);
echo $s[info];
echo '<form action="prices.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="adv_package_edited">
<input type="hidden" name="n" value="'.$data[n].'">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" colspan=2>Edit Selected Package</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr>
<td align="left" valign="top">Description <br /><span class="text10">It should contain all the info you want to provide to your advertisers</span></td>
<td align="left" valign="top"><input class="field10" style="width:650px;" name="title" maxlength=100 value="'.$data[title].'"></td>
</tr>
<tr>
<td align="left" valign="top">Price&nbsp;&nbsp;<br /><span class="text10">Enter only numbers, no dots, monetary units or other characters&nbsp;&nbsp;</span></td>
<td align="left" valign="top"><input class="field10" style="width:100px" name="price" maxlength=10 value="'.$data[price].'"></td>
</tr>
<tr>
<td align="left" valign="top">Bonus</td>
<td align="left" valign="top"><input class="field10" style="width:100px" name="bonus" maxlength=10 value="'.$data[bonus].'"> %</td>
</tr>
<tr><td align="center" colspan=2><input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form><br />';
packages_info();
echo '<a href="prices.php?action=adv_packages_home">Back</a>';
ift();
}

##################################################################################

function adv_package_edited($form) {
global $s;
$form[title] = replace_once_text($form[title]);
$form[html] = replace_once_html($form[html]);
if (!$form[n]) problem('An error has occurred. Please select the package again.');
if ((!$form[title]) OR (!$form[price])) problem('Please go back and fill in all fields.');
if (!is_numeric($form[price])) problem("Please insert only numbers to these fields: Price, Clicks, Impressions, Days.");
dq("update $s[pr]adv_packs set title = '$form[title]', price = '$form[price]', bonus = '$form[bonus]' where n = '$form[n]'",1);
$s[info] = info_line('Selected package has been edited');
$form[pack] = $form[n];
adv_package_edit($form);
exit;
}

############################################################################
############################################################################
############################################################################

function packs_select() {
global $s;
$q = dq("select n,title from $s[pr]adv_packs order by price",0);
while ($a=mysql_fetch_row($q)) $x .= "<option value=\"$a[0]\">$a[1]</option>";
return stripslashes($x);
}

############################################################################


?>