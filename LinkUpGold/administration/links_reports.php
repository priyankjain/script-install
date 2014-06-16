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
check_admin('all_links');

switch ($_GET[action]) {
case 'reports'		: reports();
case 'report_delete'	: report_delete($_GET[n]);
}

##################################################################################
##################################################################################
##################################################################################

function report_delete($n) {
global $s;
dq("delete from $s[pr]er_reports where n = '$n'",1);
$s[info] = info_line('Selected Error Report Has Been Deleted');
reports();
}

##################################################################################

function reports() {
global $s;
ih();
$q = dq("select * from $s[pr]er_reports",1);
if (!mysql_num_rows($q)) { echo info_line('No one error report found<br />'); ift(); }
echo $s[info];
echo page_title('Error Reports');
while ($report = mysql_fetch_assoc($q))
{ $report = stripslashes_array($report);
  $info = "Error report #$report[n], name: $report[name], email: <a href=\"mailto:$report[email]\">$report[email]</a><br />
  Date: ".datum($report[time],0).", IP address: $report[ip]<br />
  Text: $report[text]<br />
  <a href=\"links_reports.php?action=report_delete&n=$report[n]\">Delete this error report</a><br />";
  $q1 = dq("select * from $s[pr]links where n = '$report[link]'",1);
  $link = mysql_fetch_assoc($q1);
  if (!$link[url]) dq("delete from $s[pr]er_reports where n = $report[n]",1);
  else { echo $info; show_one_link($link); $pocet1 = 1; }
}
if (!$pocet1) { echo '<meta http-equiv="Refresh" content="0; URL=links_reports.php?action=reports">'; exit; }
ift();
}

######################################################################################
######################################################################################
######################################################################################

?>