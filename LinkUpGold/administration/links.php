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

include('./links_functions.php');

switch ($_GET[action]) {
case 'links_unapproved_home'	: links_unapproved_home();
case 'links_unapproved_show'	: links_unapproved_show($_GET);
case 'links_editor_picks'		: links_editor_picks();
case 'links_duplicate_form'		: links_duplicate_form();
case 'links_duplicate'			: links_duplicate($_GET[what]);
case 'links_broken_form'		: links_broken_form();
case 'links_broken_find'		: links_broken_find($_GET);
case 'links_recips_missing_find': links_recips_missing_find($_GET);
case 'links_search'				: links_search();
case 'links_searched'			: links_searched($_GET);
case 'links_updated_multiple'	: links_updated_multiple($_GET);
}
switch ($_POST[action]) {
case 'links_approved'			: links_approved($_POST);
case 'links_edited_multiple'	: links_edited_multiple($_POST);
case 'links_updated_multiple'	: links_updated_multiple($_POST);
}

#############################################################################
#############################################################################
#############################################################################

function links_edited_multiple($in) {
global $s;
check_admin('all_links');
foreach ($in[link] as $k=>$v) { $link = $v; $link[n] = $k; link_edited_process($link); }
ih();
echo info_line('Entered changes have been saved');
echo '<a href="'.$_SERVER[HTTP_REFERER].'">Back</a>';
ift();
exit;
}

#################################################################################

function links_updated_multiple($in) {
global $s;
check_admin('all_links');
if ((!$in[to_do]) OR (!$in[link])) header("Location: $_SERVER[HTTP_REFERER]");
$x = 'n = \''.implode('\' OR n = \'',$in[link]).'\'';
ih();

if ($in[to_do]=='delete')
{ echo info_line('Total of '.count($in[link]).' links will be deleted. Continue?');
  echo '<form method="post" action="links.php">'.check_field_create('admin').'
  <input type="hidden" name="action" value="links_updated_multiple">
  <input type="hidden" name="to_do" value="deleted">
  <input type="hidden" name="back" value="'.$_SERVER[HTTP_REFERER].'">';
  foreach ($in[link] as $k=>$v) echo '<input type="hidden" name="link[]" value="'.$v.'">';
  echo '<input type="submit" name="submit" value="Submit" class="button10"></form><br /><br /><br />';
}
else
{ if ($in[to_do]=='enable')
  { dq("update $s[pr]links set status = 'enabled' where $x",1);
    $info = info_line(mysql_affected_rows().' links have been enabled');
  }
  elseif ($in[to_do]=='disable')
  { dq("update $s[pr]links set status = 'disabled' where $x",1);
    $info = info_line(mysql_affected_rows().' links have been disabled');
  }
  elseif ($in[to_do]=='deleted')
  { delete_items('l',$in[link]);
    $info = info_line('Total of '.count($in[link]).' links have been deleted');
  }
  elseif ($in[to_do]=='move')
  { dq("update $s[pr]links set c = '_".$in[category]."_' where $x",1);
    foreach ($in[link] as $k => $v) update_item_index('l',$v);
    $info = info_line(mysql_affected_rows().' links have been moved');
  }
  $info .= info_line('When you finish the editing, go to <a href="rebuild.php?action=reset_rebuild_home"><b>reset/rebuild</b></a> and run function "Recounts all links and articles"');
  echo $info;
}
if ($in[back]) $back = $in[back]; else $back = $_SERVER[HTTP_REFERER];
echo '<a href="'.$back.'">Back</a>';
ift();
exit;
}

#############################################################################
#############################################################################
#############################################################################

function links_broken_form() {
global $s;
check_admin('all_links');
ih();
$q = dq("select count(*) from $s[pr]links where status = 'enabled' or status = 'disabled'",1);
$count = mysql_fetch_row($q); $count = $count[0]/100;
echo $s[info];
echo info_line('Check for Existing Links & Reciprocal Links','These tests may take a long time if you have a bigger database. It is recommended don\'t check all links at once but in steps. If the connection to the script will be interrupted, try lower number of links.');
?>
<form action="links.php" method="get" name="form1">
<input type="hidden" name="action" value="links_broken_find">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Check for links</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">The script will try to connect to each URL in the database to check if the URL exists.</td></tr>
<tr><td align="center">Select how many links should be checked at once.
<select class="select10" name="perpage">
<option value="0">All</option><option value="10">10</option><option value="20">20</option><option value="50">50</option>
<option value="100">100</option><option value="200">200</option><option value="500">500</option>
<option value="1000">1000</option></select>
<?PHP
if ($count>=2)
{ echo 'Starting from <select class="select10" name="from">';
  for ($x<1;$x<=$count;$x++) echo '<option value="'.($x*100+1).'">'.($x*100+1).'</option>';
  echo '</select>';
}
?>
<br /><br />
Show detailed report <input type="checkbox" name="details" value="1"><br /><br />
</td></tr>
<tr><td align="center"><input type="submit" name="submit" value="Check Now!" class="button10"></td></tr>
</table></td></tr></table></form>

<br />

<form action="links.php" method="get" name="form1">
<input type="hidden" name="action" value="links_recips_missing_find">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Check for reciprocal links</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">The script will try to connect to each reciprocal URL in the database to check if there is a link back to you on these URL's.</td></tr>
<tr><td align="center">
Select how many links should be checked at once.
<select class="select10" name="perpage">
<option value="0">All</option><option value="10">10</option><option value="20">20</option><option value="50">50</option>
<option value="100">100</option><option value="200">200</option><option value="500">500</option>
<option value="1000">1000</option></select>
<?PHP
if ($count>=2)
{ echo 'Starting from <select class="select10" name="from">';
  for ($x1<1;$x1<=$count;$x1++) echo '<option value="'.($x1*100+1).'">'.($x1*100+1).'</option>';
  echo '</select>';
}
?>
<br /><br />
Ignore links with emails (separated by a comma):<br />
<input class="field10" name="ignored_emails" style="width:650px;"><br /><br />
Show detailed report <input type="checkbox" name="details" value="1"><br /><br />
</td></tr>
<tr><td align="center"><input type="submit" name="submit" value="Check Now!" class="button10"></td></tr>
</table></td></tr></table></form>
<?PHP
ift();
}

##################################################################################

function links_broken_find($data) {
global $s;
check_admin('all_links');
set_time_limit(1000);

if (!$data[from]) $data[from] = 0; else $data[from] = $data[from] - 1;
if ($data[perpage]) $limit = " limit $data[from],$data[perpage]";
$q = dq("select count(*) from $s[pr]links where 1 $s[allowed_cats_query_l]",1);
$total = mysql_fetch_row($q); $total = $total[0];
if ( ($data[perpage]) AND ($total>$data[perpage]) )
{ $rozcesti = "
  <form action=\"links.php\" method=\"get\" name=\"form1\">
  <input type=\"hidden\" name=\"action\" value=\"links_broken_find\">
  <input type=\"hidden\" name=\"details\" value=\"$data[details]\">";
  foreach ($data as $k => $v)
  { if ($v) $rozcesti .= "<input type=\"hidden\" name=\"$k\" value=\"$v\">\n"; }
  $rozcesti .= 'Check for links with begin of <select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($total/$data[perpage]);  
  for ($x=1;$x<$y;$x++)
  { $od = $x*$data[perpage]+1; $rozcesti .= "<option value=\"$od\">$od</option>"; }
  $rozcesti .= "</select>&nbsp;&nbsp;<input type=\"submit\" value=\"Submit\" name=\"B1\" class=\"button10\">
  </form>";
}
if ($data[perpage]) $c = $data[perpage]; else $c = $total;
if ($c<=300) $y=1; elseif ($c<=3000) $y = 10; else $y = 50;
$x = 0;
ih();
echo '<span id="processing"><span class="text13a_bold"><b>Checking for links, please wait ...</b></span><br /><span class="text10">'.str_repeat(' ',5000).'.';

$q = dq("select url,n from $s[pr]links where 1 $s[allowed_cats_query_l] $orderby $data[order] $limit",1);
while ($url=mysql_fetch_row($q))
{ set_time_limit(30);
  if ($data[details])
  { echo str_repeat(' ',200)."Link $url[1] <a class=\"link10\" target=\"new\" href=\"$url[0]\">URL</a> 
    <a class=\"link10\" target=\"new\" href=\"links.php?action=links_searched&n=$url[1]\">Details</a> 
    <a class=\"link10\" target=\"new\" href=\"link_details.php?action=link_delete&n=$url[1]\">Delete</a> ";
    flush();
  }
  elseif (!($x%$y))
  { $x++;
    if (!$i) { echo 'Link checking: '; $i = 1; }
    echo str_repeat(' ',200)."$x\n"; flush();
  }
  if (!$fd=fetchURL($url[0])) { if (!$fd=fetchURL("$url[0]/")) $blbe[] = $url[1]; }
  if ($data[details]) { if ($fd) echo 'OK'; else echo 'LOST'; echo "<br />\n"; }
}
echo '</span></span>';
flush();
echo "<script>processing.style.display='none'</script>";

if ($blbe)
{ $blbewhere = join(" OR n = ", $blbe);
  //echo "select url,n from $s[pr]links where n = $blbewhere $orderby $data[order]";
  $q = dq("select * from $s[pr]links where n = $blbewhere $orderby $data[order]",1);
  $pocet = mysql_num_rows($q);
}
else $pocet = 0;

$od = $data[from]+1;
$do = $data[from]+$data[perpage]; if ($do>$total) $do = $total; if (!$data[perpage]) $do = $total;
echo $s[info].'<span class="text13a_bold">Total of '.$total.' links in the database';
if ( ($total>1) AND ($od!=$do) ) echo ", checking links $od - $do</span><br /><br />\n$rozcesti";
else echo '<br />';
echo info_line($pocet.' broken links found');
prepare_and_display_links($q,0);
ift();
}

##################################################################################

function links_recips_missing_find($data) {
global $s;
check_admin('all_links');
set_time_limit(1000);
if (trim($data[ignored_emails])) if ($data[ignored_emails]) $ignored_emails = 'AND NOT('.my_implode('email','or',explode(',',$data[ignored_emails])).')';

if (!$data[from]) $data[from] = 0; else $data[from] = $data[from] - 1;
if ($data[perpage]) $limit = " limit $data[from],$data[perpage]";
$q = dq("select count(*) from $s[pr]links where 1 $s[allowed_cats_query_l] $ignored_emails",0);
$total = mysql_fetch_row($q); $total = $total[0];
if ( ($data[perpage]) AND ($total>$data[perpage]) )
{ $rozcesti = '<form action="links.php" method="get" name="form1">
  <input type="hidden" name="action" value="links_recips_missing_find">
  <input type="hidden" name="details" value="'.$data[details].'">
  <input type="hidden" name="ignored_emails" value="'.$data[ignored_emails].'">';
  foreach ($data as $k => $v)
  { if ($v) $rozcesti .= "<input type=\"hidden\" name=\"$k\" value=\"$v\">\n"; }
  $rozcesti .= 'Check for links with begin of <select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($total/$data[perpage]);  
  for ($x=1;$x<$y;$x++)
  { $od = $x*$data[perpage]+1; $rozcesti .= "<option value=\"$od\">$od</option>"; }
  $rozcesti .= "</select>&nbsp;&nbsp;<input type=\"submit\" value=\"Submit\" name=\"B1\" class=\"button10\">
  </form>";
}
if ($data[perpage]) $c = $data[perpage]; else $c = $total;
if ($c<=300) $y=1; elseif ($c<=3000) $y = 10; else $y = 50;
$x = 0;
ih();
echo '<span id="processing"><span class="text13a_bold"><b>Checking for recip links, please wait ...</b></span><br /><span class="text10">'.str_repeat(' ',5000).'.';

$q = dq("select url,n,recip from $s[pr]links where 1 $s[allowed_cats_query_l] $ignored_emails $orderby $data[order] $limit",1);
while ($url=mysql_fetch_row($q))
{ set_time_limit(30);
  if ($data[details])
  { echo str_repeat(' ',200)."Link $url[1] 
    <a class=\"link10\" target=\"new\" href=\"$url[0]\">URL</a> 
    <a class=\"link10\" target=\"new\" href=\"$url[2]\">Recip URL</a> 
    <a class=\"link10\" target=\"new\" href=\"links.php?action=links_searched&n=$url[1]\">Details</a> 
    <a class=\"link10\" target=\"new\" href=\"link_details.php?action=link_delete&n=$url[1]\">Delete</a> ";
    flush();
  }
  elseif (!($x%$y))
  { $x++;
    if (!$i) { echo 'Link checking: '; $i = 1; }
    echo str_repeat(' ',200)."$x\n"; flush();
  }
  if (!trim($url[2])) { $blbe[] = $url[1]; $ok = 0; }
  else
  { $lines = file($url[2]);
    if (!$lines) $blbe[] = $url[1];
    else
    { unset($obsah); foreach ($lines as $k=>$v) $obsah .= $v."\n";
      if (strstr(stripslashes($obsah),stripslashes($s[reciplink]))) $ok = 1; else { $blbe[] = $url[1]; $ok = 0; }
    }
  }
  if ($data[details]) { if ($ok) echo 'FOUND'; else echo 'NOT FOUND'; echo "<br />\n"; }
}
echo '</span></span>';
flush();
echo "<script>processing.style.display='none'</script>";

if ($blbe)
{ $blbewhere = join(' OR n = ', $blbe);
  $q = dq("select * from $s[pr]links where n = $blbewhere $orderby $data[order]",1);
  $pocet = mysql_num_rows($q);
}
else $pocet = 0;

$od = $data[from]+1;
$do = $data[from]+$data[perpage]; if ($do>$total) $do = $total; if (!$data[perpage]) $do = $total;
echo $s[info].'<span class="text13a_bold">Total of '.$total.' links in the database';
if ( ($total>1) AND ($od!=$do) ) echo ", checking links $od - $do</span><br /><br />\n$rozcesti";
else echo '<br />';
echo info_line($pocet.' broken or missing reciprocal links found');
prepare_and_display_links($q,0);
ift();
}

#################################################################################
#################################################################################
#################################################################################

function links_editor_picks() {
global $s;
check_admin('all_links');
ih();
$q = dq("select * from $s[pr]links where pick > 0 $s[allowed_cats_query_l] order by pick desc",1);
$pocet = mysql_num_rows($q);
if (!$pocet)
{ echo info_line('No one link is marked as editor\'s pick<br /><br />');
  ift(); 
}
echo info_line('Links Found: '.$pocet);
prepare_and_display_links($q,0);
ift();
}

##################################################################################
##################################################################################
##################################################################################

function links_unapproved_home() {
global $s;
ih();
echo page_title('Queue for Links');
echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td class="common_table_top_cell" nowrap>Links</td></tr>
<tr><td align="center" width="100%">';
$q = dq("select count(*) from $s[pr]links where status = 'queue' $s[allowed_cats_query_l]",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet) echo 'No one new link in the queue';
else
{ echo 'Links in the queue: '.$pocet.
  '<br />Select number of links to display on one page<br />
  <form method="get" action="links.php">
  <input type="hidden" name="action" value="links_unapproved_show">
  <input type="hidden" name="what" value="new">
  <select class="select10" name="perpage"><option value="0">All</option>';
  if ($pocet>5) echo '<option value="5">5</option>';
  if ($pocet>10) echo '<option value="10">10</option>';
  if ($pocet>20) echo '<option value="20">20</option>';
  if ($pocet>30) echo '<option value="30">30</option>';
  echo '</select> 
  <input type="submit" value="Submit" name="B1" class="button10">
  </form>';
}
echo '</td></tr>';
if ($s[admin_all_cats_l]) comments_unapproved_info('l');
echo '</table>
</td></tr></table>';
ift();
}

##################################################################################
##################################################################################
##################################################################################

function links_unapproved_show($in) {
global $s;
if (!$in[from]) $from = 0; else $from = $in[from] - 1;

$q = dq("select count(*) from $s[pr]links where status = 'queue' $s[allowed_cats_query_l]",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet) { ih(); echo $s[info].info_line('No one link in the queue'); ift(); }
$show[0] = $from + 1;
$show[1] = $from + $in[perpage]; if ($show[1]>$pocet) $show[1] = $pocet; if (!$in[perpage]) $show[1] = $pocet;

if (($in[perpage]) AND ($pocet>$in[perpage]))
{ $rozcesti = '
  <form action="links.php" method="get" name="form1">
  <input type="hidden" name="action" value="links_unapproved_show">
  <input type="hidden" name="perpage" value="'.$in[perpage].'">
  <input type="hidden" name="what" value="'.$in[what].'">
  Show links with begin of&nbsp;&nbsp;<select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($pocet/$in[perpage]);  
  for ($x=1;$x<$y;$x++)
  { $od = $x * $in[perpage] + 1;
    $rozcesti .= "<option value=\"$od\">$od</option>";
  }
  $rozcesti .= '</select>&nbsp;&nbsp;<input type="submit" value="Submit" name="B1" class="button10">
  </form><br />';
}

if ($in[perpage]) $limit = " limit $from,$in[perpage]";
$q = dq("select * from $s[pr]links where status = 'queue' $s[allowed_cats_query_l] order by n $limit",1);
while ($x = mysql_fetch_assoc($q)) { $links[$x[n]] = $x; $numbers[] = $x[n]; }
$reject_emails = get_reject_emails_list('reject_link_');
ih();

echo $s[info].info_line('Links in The Queue: '.$pocet.', Showing Links '.$show[0].' - '.$show[1]).$rozcesti;

$x = 'AND (n = \''.implode('\' OR n = \'',$numbers).'\')';
$q = dq("select * from $s[pr]usit_values where use_for = 'l_q' $x",1);
while ($x = mysql_fetch_assoc($q))
{ $links[$x[n]][user_items][$x[item_n]][value_code] = $x[value_code];
  $links[$x[n]][user_items][$x[item_n]][value_text] = $x[value_text];
}

echo '<form enctype="multipart/form-data" action="links.php" method="post" name="muj">'.check_field_create('admin').'
<input type="hidden" name="action" value="links_approved">
<input type="hidden" name="perpage" value="'.$in[perpage].'">
<input type="hidden" name="what" value="'.$in[what].'">
<input type="hidden" name="from" value="'.$from.'">';

foreach ($links as $k=>$v)
{ $v[reject_emails] = $reject_emails;
  link_create_edit_form($v);
}
echo '<input type="submit" name="submit" value="Submit" class="button10"></form>';
ift();
}

##################################################################################

function links_approved($in) {
global $s;
foreach ($in[link] as $n=>$link)
{ if (!$in[link][$n][approve]) continue;
  $link[n] = $n;
  //foreach ($link as $k=>$v) echo "$k - $v<br />";exit;
  if (!check_admin_categories('l',$link[categories]))
  { $s[info] .= "Link $link[title] skipped. You do not have permissions to add a link to selected category/categories.<br />";
    continue;
  }
  $oznamit = 0;
  $old = get_item_variables('l',$n);
  if ($link[approve]=='yes')
  { if ($old[n]) // updated
    { $q = dq("select * from $s[pr]files where what = 'l' and item_n = '$n' and queue = '0'",1);
      while ($files=mysql_fetch_assoc($q))
      { unlink(str_replace($s[site_url],$s[phppath],$files[filename]));
        if ($files[file_type]=='image') unlink(str_replace($s[site_url],$s[phppath],preg_replace("/\/$n-/","/$n-big-",$files[filename])));
      }
      dq("delete from $s[pr]files where what = 'l' and item_n = '$n' and queue = '0'",1);
	  dq("update $s[pr]files set queue = '0' where what = 'l' and item_n = '$n' and queue = '1'",1);
      dq("delete from $s[pr]links where n = '$n' and status = 'queue'",1);
      dq("update $s[pr]links set status = 'enabled' where n = '$n' and status = 'queue'",1);
	  dq("delete from $s[pr]usit_values where n = '$n' and use_for = 'l_q'",1);
	  dq("delete from $s[pr]usit_search where n = '$n' and use_for = 'l_q'",1);
	  $link[mark_updated] = 1;
   	  link_edited_process($link);
    }
	else // new link
	{ dq("update $s[pr]links set status = 'enabled' where n = '$n' and status = 'queue'",1);
	  dq("update $s[pr]files set queue = '0' where what = 'l' and item_n = '$n' and queue = '1'",1);
	  dq("update $s[pr]usit_values set use_for = 'l' where n = '$n' and use_for = 'l_q'",1);
	  dq("update $s[pr]usit_search set use_for = 'l' where n = '$n' and use_for = 'l_q'",1);
	  link_edited_process($link);
      dq("insert into $s[pr]u_to_email values('l','$n')",1);
    }
    $s[info] .= 'Link #'.$n.' - <a target="_blank" href="'.$link[url].'">'.$link[title].'</a> has been approved';
    $oznamit = 1;
  }
  elseif ($link[approve]=='no')  // reject
  { if (!$old[n]) $is_new = 1;
    delete_queued_item('l',$n,$is_new);
	$s[info] .= 'Link <a target="_blank" href="'.$link[url].'">'.$link[title].'</a> has been rejected';
    $oznamit = 1;
  }
  dq("delete from $s[pr]links_recips_info where n = '$n'",1);
  // send emails
  if (!$oznamit) continue;
  $link[to] = $link[email];
  if ($link[approve]=='no')
  { if ($link[reject_email]) { $email_sent = 1; mail_from_template($link[reject_email],$link); }
    elseif (($link[reject_email_custom]) AND ($link[email_subject]) AND ($link[email_text]))
    { foreach ($link as $k=>$v) $link[email_text] = str_replace("#%$k%#",$v,$link[email_text]);
	  my_send_mail('','',$link[email],0,unhtmlentities($link[email_subject]),unhtmlentities($link[email_text]),1);
	  $email_sent = 1;
	}
  }
  elseif (($link[approve]=='yes') AND ($s[l_i_approved]))
  { $y = list_of_categories_for_item('l',0,$link[categories],"\n",1); $link[categories] = $y[categories_names]; $link[categories_urls] = $y[categories_urls];
    $link[detail_url] = get_detail_page_url('l',$link[n],'',0,1);
    mail_from_template('link_approved.txt',$link);
    $email_sent = 1;
  }
  if ($email_sent) $s[info] .= '. Email sent.<br />'; else $s[info] .= '. Email not sent.<br />';
}
if ($s[info]) $s[info] .= '<br>';
links_unapproved_show($in);
}

#################################################################################
#################################################################################
#################################################################################

function links_duplicate_form() {
global $s;
check_admin('all_links');
ih();
echo $s[info];
?>
<form action="links.php" method="get" name="form1">
<input type="hidden" name="action" value="links_duplicate">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell" nowrap>Search for Duplicate Links</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">This function may take a long time if you have a bigger database.</td></tr>
<tr><td align="center">Action 
<select class="select10" name="what">
<option value="show">Show duplicate links</option>
<option value="delete">Delete duplicate links</option>
</select><br /><br />
<input type="submit" name="submit" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form><br />
<?PHP
ift();
}

##################################################################################

function links_duplicate($what) {
global $s;
check_admin('all_links');
//echo $what; exit;
ih();
echo '<span id="processing"><span class="text13a_bold">Searching for duplicate links. It may take some time if you have a bigger database.<br />Please wait ...</span>'.str_repeat(' ',5000).'.</span>';
flush();
set_time_limit(1000);
$q = dq("select t1.url,t1.n from $s[pr]links as t1,$s[pr]links as t2 
where t1.url = t2.url AND t1.n != t2.n group by t1.url",1); 
$pocet = mysql_num_rows($q); if (!$pocet) $pocet = 0;

if (!$pocet) { echo info_line('No one duplicate link found'); ift(); }
echo "<script>processing.style.display='none'</script>";

if ($what=='show')
{ echo $s[info].info_line($pocet.' Duplicate Links Found');
  echo show_check_uncheck_all().'<form method="get" action="links.php" id="myform"><input type="hidden" name="action" value="links_updated_multiple">';
  while ($url = mysql_fetch_row($q))
  { echo info_line('URL: <a target="_blank" href="'.$url[0].'">'.$url[0].'</a>');
    $q1 = dq("select * from $s[pr]links where url = '$url[0]'",1);
    prepare_and_display_links_duplicate($q1);
  }
  echo 'Action to do with selected links: 
  <select class="select10" name="to_do"><option value="0">No action</option>
  <option value="enable">Enable</option>
  <option value="disable">Disable</option>
  <option value="delete">Delete</option>
  </select>
  <input type="submit" name="submit" value="Submit" class="button10"></form>';
}
if ($what=='delete')
{ while ($url = mysql_fetch_row($q))
  { $q1 = dq("select n from $s[pr]links where url = '$url[0]' AND not(n = '$url[1]')",1);
    while ($x = mysql_fetch_assoc($q1)) $links_to_delete[] = $x[n];
    delete_items('l',$links_to_delete);
  }
  echo info_line('Duplicate Links Deleted: '.count($links_to_delete),'Now run function <a href="rebuild.php?action=reset_rebuild_home">Recount all links and articles</a>');
}
ift();
}

##################################################################################

function prepare_and_display_links_duplicate($q) {
global $s;
while ($x = mysql_fetch_assoc($q)) { $links[$x[n]] = $x; $link_numbers[] = $x[n]; }
if (!$link_numbers[0]) return false;
$x = 'AND (n = \''.implode('\' OR n = \'',$link_numbers).'\')';
$q = dq("select * from $s[pr]usit_values where use_for = 'l' $x",1);
while ($x = mysql_fetch_assoc($q))
{ $links[$x[n]]['user_item_'.$x[item_n]][code] = $x[value_code];
  $links[$x[n]]['user_item_'.$x[item_n]][text] = $x[value_text];
}
foreach ($links as $k=>$v) { $v[show_checkbox] = 1; show_one_link($v); }
}

##################################################################################
##################################################################################
##################################################################################

function links_search() {
global $s;
$categories = categories_selected('l',0,1,1,0,0);
$categories_first = categories_selected('l_first',0,1,1,0,0);
ih();
echo '<form method="get" action="links.php">
<input type="hidden" name="action" value="links_searched">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Search for Links</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left">Number <span class="text10">&nbsp;&nbsp;&nbsp;If you enter a number here, it will find this link, not depending if it meets any other criteria<br /></span></td>
<td align="left"><input class="field10" name="n" style="width:100px" maxlength=10></td></tr>
<tr>
<td align="left" nowrap>Category</td>
<td align="left"><select class="select10" name="category"><option value="0">Any category</option>'.$categories.'</select></td>
</tr>
<tr>
<td align="left" nowrap>Category</td>
<td align="left" nowrap><select class="select10" name="bigboss"><option value="0">Any category</option>'.$categories_first.'</select> incl. subcategories</td>
</tr>
<tr>
<td align="left" nowrap>Any field contains </td>
<td align="left"><input class="field10" name="any" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Title contains </td>
<td align="left"><input class="field10" name="title" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>URL contains </td>
<td align="left"><input class="field10" name="url" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Description contains </td>
<td align="left"><input class="field10" name="description" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Detailed description contains </td>
<td align="left"><input class="field10" name="detail" style="width:650px;" maxlength=100></td>
</tr>';
echo user_defined_items_form('l','search_form');
echo '
<tr>
<td align="left" nowrap>Owner\'s username </td>
<td align="left"><input class="field10" name="username" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Contact name contains </td>
<td align="left"><input class="field10" name="name" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Contact email contains </td>
<td align="left"><input class="field10" name="email" style="width:650px;" maxlength=100></td>
</tr>
<tr>
<td align="left" nowrap>Rating (0-5)</td>
<td align="left" nowrap>From <input  class="field10" name="rating_f" size=4 maxlength=2> To <input  class="field10" name="rating_t" size=4 maxlength=2></td>
</tr>
<tr>
<td align="left" nowrap>Votes </td>
<td align="left" nowrap>From <input  class="field10" name="votes_f" size=4 maxlength=2> To <input  class="field10" name="votes_t" size=4 maxlength=2></td>
</tr>
<tr>
<td align="left" nowrap>Incoming clicks this month </td>
<td align="left" nowrap>From <input  class="field10" name="clicks_in_m_f" style="width:100px" maxlength=10> To <input  class="field10" name="clicks_in_m_t" style="width:100px" maxlength=10></td>
</tr>
<tr>
<td align="left" nowrap>Outgoing clicks this month </td>
<td align="left" nowrap>From <input  class="field10" name="hits_m_f" style="width:100px" maxlength=10> To <input  class="field10" name="hits_m_t" style="width:100px" maxlength=10></td>
</tr>
<tr>
<td align="left" nowrap>Incoming clicks total </td>
<td align="left" nowrap>From <input  class="field10" name="clicks_in_f" style="width:100px" maxlength=10> To <input  class="field10" name="clicks_in_t" style="width:100px" maxlength=10></td>
</tr>
<tr>
<td align="left" nowrap>Outgoing clicks total </td>
<td align="left" nowrap>From <input  class="field10" name="hits_f" style="width:100px" maxlength=10> To <input  class="field10" name="hits_t" style="width:100px" maxlength=10></td>
</tr>
<tr>
<td align="left" nowrap>Enabled </td>
<td align="left" nowrap>N/A<input type="radio" name="enabled" value="0" checked>&nbsp; Yes<input type="radio" name="enabled" value="yes">&nbsp; No<input type="radio" name="enabled" value="no"></td>
</tr>
<tr>
<td align="left" nowrap>Validity </td>
<td align="left"><select class="select10" name="valid">
<option value="0">N/A</option><option value="yes">Links which are currently valid</option>
<option value="no">Links which are currently not valid</option><option value="future">Links which will valid in the future</option><option value="past">Links which have been valid in the past</option></select>
</td>
</tr>
<tr>
<td align="left" nowrap>Type of search </td>
<td align="left" nowrap>AND <input type="radio" value="and" name="boolean" checked> OR <input type="radio" value="or" name="boolean"></td>
</tr>
<tr>
<td align="left" nowrap>Results per page </td>
<td align="left"><select class="select10" name="perpage">
<option value="0">All</option><option value="10">10</option><option value="20">20</option>
<option value="50">50</option><option value="100">100</option>
<option value="200">200</option><option value="500">500</option></select>
</td>
</tr>
<tr>
<td align="left" nowrap>Sort by </td>
<td align="left"><select class="select10" name="sort">
<option value="title">Title</option><option value="rating">Rating</option>
<option value="votes">No of votes</option><option value="hits_m">Popularity (outgoing clicks this month)</option>
<option value="clicks_in_m">Incoming clicks this month</option><option value="hits">Outgoing clicks total</option>
<option value="clicks_in">Incoming clicks total</option><option value="created">Date created</option>
<option value="name">Contact name</option></select>
<select class="select10" name="order"><option value="asc">Ascending</option><option value="desc">Descending</option></select>
</td>
</tr>
<tr>
<td align="left" nowrap>Edit forms </td>
<td align="left"><input type="checkbox" name="edit_forms" value="1"></td>
</tr>
<tr><td colspan=2 align="center"><input type="submit" value="Search" name="B1" class="button10"></td></tr>
</table></td></tr></table></form>';
ift();
}

##################################################################################
##################################################################################
##################################################################################

function links_searched($data) {
global $s;
if (!$data[boolean]) $data[boolean] = 'and';
foreach ($data as $k=>$v)
{ if (is_array($v)) foreach ($v as $k1=>$v1) { if ($v1) $x[] = $k.'['.$k1.']='.$v1; }
  elseif ($v) $x[] = "$k=$v";
}
$referral = implode('&',$x);
ih();

if (!$data[n])
{ if ($data[bigboss]) $w[] = "c_path like '%\_$data[bigboss]\_%'";
  if (strstr($data[title],'|')) $w[] = "title like '".str_replace('|','',$data[title])."%'";
  elseif ($data[title]) $w[] = "title like '%$data[title]%'";
  if ($data[url]) $w[] = "url like '%$data[url]%'";
  if ($data[description]) $w[] = "description like '%$data[description]%'";
  if ($data[detail]) $w[] = "detail like '%$data[detail]%'";
  if ($data[owner]) $w[] = "owner = '$data[owner]'";
  elseif ($data[username]) { $user = get_user_variables(0,$data[username]); $w[] = "owner = '$user[n]'"; }
  if ($data[name]) $w[] = "name like '%$data[name]%'";
  if ($data[email]) $w[] = "email like '%$data[email]%'";
  if ($data[category]) $w[] = "c like '%\_$data[category]\_%'";
  if (($data[rating_f]) OR ($data[rating_t]))
  { if (!$data[rating_f]) $data[rating_f] = 0; $x1 = "rating >= $data[rating_f]";
    if (!$data[rating_t]) $data[rating_t] = 10; $x2 = "rating <= $data[rating_t]"; 
    $w[] = "($x1 AND $x2)";
  }
  if (($data[votes_f]) OR ($data[votes_t]))
  { if (!$data[votes_f]) $data[votes_f] = 0; $x1 = "votes >= $data[votes_f]";
    if (!$data[votes_t]) $data[votes_t] = 1000000; $x2 = "votes <= $data[votes_t]"; 
    $w[] = "($x1 AND $x2)";
  }
  if (($data[clicks_in_m_f]) OR ($data[clicks_in_m_t]))
  { if (!$data[clicks_in_m_f]) $data[clicks_in_m_f] = 0; $x1 = "clicks_in_m >= $data[clicks_in_m_f]";
    if (!$data[clicks_in_m_t]) $data[clicks_in_m_t] = 1000000; $x2 = "clicks_in_m <= $data[clicks_in_m_t]"; 
    $w[] = "($x1 AND $x2)";
  }
  if (($data[hits_m_f]) OR ($data[hits_m_t]))
  { if (!$data[hits_m_f]) $data[hits_m_f] = 0; $x1 = "hits_m >= $data[hits_m_f]";
    if (!$data[hits_m_t]) $data[hits_m_t] = 1000000; $x2 = "hits_m <= $data[hits_m_t]"; 
    $w[] = "($x1 AND $x2)";
  }
  if (($data[clicks_in_f]) OR ($data[clicks_in_t]))
  { if (!$data[clicks_in_f]) $data[clicks_in_f] = 0; $x1 = "clicks_in >= $data[clicks_in_f]";
    if (!$data[clicks_in_t]) $data[clicks_in_t] = 1000000; $x2 = "clicks_in <= $data[clicks_in_t]"; 
    $w[] = "($x1 AND $x2)";
  }
  if (($data[hits_f]) OR ($data[hits_t]))
  { if (!$data[hits_f]) $data[hits_f] = 0; $x1 = "hits >= $data[hits_f]";
    if (!$data[hits_t]) $data[hits_t] = 1000000; $x2 = "hits <= $data[hits_t]"; 
    $w[] = "($x1 AND $x2)";
  }
  switch ($data[valid])
  { case 'yes'		: $w[] = "(t1 < '$s[cas]' AND (t2 > '$s[cas]' OR t2 = 0))"; break;
    case 'no'		: $w[] = "(t1 > '$s[cas]' OR (t2 < '$s[cas]' AND t2 != 0))"; break;
    case 'future'	: $w[] = "t1 > '$s[cas]'"; break;
    case 'past'		: $w[] = "(t2 < '$s[cas]' AND t2 != 0)"; break;
  }
  if ($data[enabled]=='yes') $w[] = "status = 'enabled'";
  elseif ($data[enabled]=='no') $w[] = "status = 'disabled'";
  if ($data[any])
  { if (!$w[0]) $only_any = 1;
    $w[] = "(title like '%$data[any]%' OR url like '%$data[any]%' OR description like '%$data[any]%' OR detail like '%$data[any]%' OR name like '%$data[any]%' OR email like '%$data[any]%')";
  }
  if ($w) $where = ' where '.join (" $data[boolean] ", $w).' ';
  //echo $where;
  $list_of_numbers = searched_get_list_of_numbers('l',$where,$data[any],$only_any,$data[user_item],$data[boolean]);
  if (!$list_of_numbers) no_result('link');
  elseif ($data[active])
  { unset($x);
    if ($data[active]=='yes') $x = "(status = 'enabled' AND (t1<$s[cas] OR t1=0) AND (t2>$s[cas] OR t2=0))";
    elseif ($data[active]=='no') $x = "(status = 'disabled' OR t1>$s[cas] OR (t2<$s[cas] AND t2>0))";
    if ($x)
    { if ($list_of_numbers) $list_of_numbers .= " AND $x ";
      else $list_of_numbers = $x; // jedina podminka bylo active
    }
  }
  if ($list_of_numbers) $where = "where ($list_of_numbers) and (status = 'enabled' or status = 'disabled')";
  else no_result('link');
}
else $where = "where n = '$data[n]' and (status = 'enabled' or status = 'disabled')";
if ($where) $where .= $s[allowed_cats_query_l]; else $where = " where 1 $s[allowed_cats_query_l] ";
//echo $where;

if (!$data[from]) $data[from] = 0; else $data[from] = $data[from] - 1;
if ($data[perpage]) $limit = " limit $data[from],$data[perpage]";

$x = dq("select count(*) from $s[pr]links $where",1);
$pocet = mysql_fetch_row($x); $pocet = $pocet[0];

if (!$pocet) no_result('link');

if ($data[sort]) $orderby = "order by $data[sort]";
$q = dq("select * from $s[pr]links $where group by n $orderby $data[order] $limit",1);

if (($data[perpage]) AND ($pocet>$data[perpage]))
{ $rozcesti = '<form method="get" action="links.php">
  <input type="hidden" name="action" value="links_searched">';
  foreach ($data as $k => $v)
  { if ($v)
    { if (is_array($v)) foreach ($v as $k1=>$v1) { if ($v1) $rozcesti .= '<input type="hidden" name="'.$k.'['.$k1.']" value="'.$v1.'">'; }
      else $rozcesti .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
    }
  }
  $rozcesti .= 'Show links with begin of <select class="select10" name="from"><option value="1">1</option>';
  $y = ceil($pocet/$data[perpage]);  
  for ($x=1;$x<$y;$x++) { $od = $x*$data[perpage]+1; $rozcesti .= '<option value="'.$od.'">'.$od.'</option>'; }
  $rozcesti .= '</select>&nbsp;<input type="submit" value="Submit" name="B1" class="button10"></form><br />';
}

$od = $data[from]+1;
$do = $data[from]+$data[perpage]; if ($do>$pocet) $do = $pocet; if (!$data[perpage]) $do = $pocet;

echo $s[info].'<span class="text13a_bold">Links Found: '.$pocet;
if (($pocet>1) AND ($od!=$do)) echo ', Showing Links '.$od.' - '.$do.'</span><br /><br />'.$rozcesti;
else echo '<br /><br />';
echo '</span>';
$data[from] = $data[from] + 1;
prepare_and_display_links($q,$data[edit_forms]);
ift();
}

######################################################################################
######################################################################################
######################################################################################

function prepare_and_display_links($q,$edit_forms) {
global $s;

while ($x = mysql_fetch_assoc($q)) { $links[$x[n]] = $x; $link_numbers[] = $x[n]; }
if (!$link_numbers[0]) return false;

$x = 'AND (n = \''.implode('\' OR n = \'',$link_numbers).'\')';
$q = dq("select * from $s[pr]usit_values where use_for = 'l' $x",1);
while ($x = mysql_fetch_assoc($q))
{ $links[$x[n]]['user_item_'.$x[item_n]][code] = $x[value_code];
  $links[$x[n]]['user_item_'.$x[item_n]][text] = $x[value_text];
}
if ($edit_forms) echo '<form enctype="multipart/form-data" method="post" action="links.php">'.check_field_create('admin').'<input type="hidden" name="action" value="links_edited_multiple">';
else echo show_check_uncheck_all().'<form method="get" action="links.php" id="myform"><input type="hidden" name="action" value="links_updated_multiple">';
foreach ($links as $k=>$v)
{ if ($edit_forms) { $v[current_action] = 'link_edit'; $v[update_no_check] = '1'; link_create_edit_form($v); }
  else { $v[show_checkbox] = 1; show_one_link($v); }
}
if (!$link_numbers) ift();
if (!$edit_forms)
{ echo 'Action to do with selected links: 
  <select class="select10" name="to_do"><option value="0">No action</option>
  <option value="enable">Enable</option>
  <option value="disable">Disable</option>
  <option value="move">Move to category</option>
  <option value="delete">Delete</option>
  </select>
  <select class="select10" name="category">'.categories_selected('l',0,1,1,0,0).'</select>
  ';
}
echo '<input type="submit" name="submit" value="Submit" class="button10"></form>';
}

######################################################################################
######################################################################################
######################################################################################

?>