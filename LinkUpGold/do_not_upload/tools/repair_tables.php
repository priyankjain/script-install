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

include("./common.php");

if (!$_GET) form();
repair_table($_GET[table]);

function repair_table($table) {
global $s;
$q = dq("REPAIR TABLE $table",1);
$s[info] = info_line('The script tried to repair the selected table','It does not mean that this action was successful however it should help in most cases.');
form();
}

function form() {
global $s;
include('./_head.txt');
echo $s[info];
echo info_line('Repair Tables Tool','The following tables have perfix '.$s[pr].', so they are probably used by Link Up Gold.<br />Click on name of the table which you want to repair.');
$q = mysql_list_tables($s[dbname]);
while ($table = mysql_fetch_row($q))
{ if (eregi("^$s[pr].*",$table[0])) echo '<a href="repair_tables.php?table='.$table[0].'">'.$table[0].'</a><br />'; }
include('./_footer.txt'); exit;
}

?>