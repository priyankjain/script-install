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
check_admin('configuration');

switch ($_POST[action]) {
case 'uninstall'			: uninstall(1);
}
uninstall();

##################################################################################
##################################################################################
##################################################################################

function uninstall($sure) {
global $s;
ih();
if (!is_array(file($s[phppath].'/data/uninstall')))
{ echo info_line('Unistallation is disabled for security reasons.','To enable it upload file "uninstall" which can be found in folder "do_not_upload/tools" to your data directory.');
  ift();
}
if (!$sure)
{ echo info_line('This function deletes all tables with names which begin to "'.$s[pr].'"<br />(it\'s prefix of tables which are used by Link Up Gold).<br />All data will be lost. This can\'t be undone.<br /><br />Are you sure?').
  '<form action="uninstall.php" method="post">'.check_field_create('admin').'
  <input type="hidden" name="action" value="uninstall">
  <input type="submit" name="x" value="Yes, I\'m sure" class="button10"></form>
  Once it deletes all the tables, you will not be able to use most functions from the menu on the left. If you will want to use the script furthermore, you will need to install it again.</span>';
  ift();
}
$q = mysql_list_tables($s[dbname]);
while ($table = mysql_fetch_row($q))
{ if (strstr($table[0],$s[pr]))
  { dq("drop table $table[0]",1);
    $x++;
  }
}
echo info_line('Total of '.$x.' tables have been deleted','If you want to use the script furthermore, you will need to install it again.');
ift();
}

##################################################################################
##################################################################################
##################################################################################

?>