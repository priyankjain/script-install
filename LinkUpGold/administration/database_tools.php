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
check_admin('database_tools');

switch ($_GET[action]) {
case 'database_home'			: database_home();
case 'database_backup'			: database_backup($_GET);
case 'database_restore'			: database_restore($_GET);
case 'database_optimize'		: database_optimize($_GET);
case 'uninstall'				: uninstall(0);
}
switch ($_POST[action]) {
case 'uninstall'				: uninstall(1);
case 'database_command'			: database_command($_POST[command]);
}

#################################################################################
#################################################################################
#################################################################################

function database_home() {
global $s;
ih();
echo $s[info];
echo page_title('Database Tools');
?>
<form method="get" action="database_tools.php">
<input type="hidden" name="action" value="database_backup">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" class="common_table_top_cell">Backup Database To A File</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" nowrap>Name of a backup file to create in your data directory: <input class="field10" name="backupfile" style="width:100px" maxlength="15" value="backup.db"></td></tr>
<tr><td align="center">
<input type="submit" name="A1" value="Submit" class="button10"><br /><br />
<span class="text10">
Note: To restore the database from this backup use <a class="link10" target=_new" href="http://www.phpmyadmin.net">PHPMyAdmin</a>. You also can use it to create the backup. It's a free tool for managing a mysql database.
</span></td></tr></table></td></tr></table></form>
<br />
<form method="get" action="database_tools.php">
<input type="hidden" name="action" value="database_optimize">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" class="common_table_top_cell">Database Optimization</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center" nowrap><span class="text13a_bold">Optimize all tables</span><br />
You should run this function from time to time.<br />Optimized database works faster.</td></tr>
<tr><td align="center">
<input type="submit" name="A1" value="Submit" class="button10">
</td></tr></table></td></tr></table></form>
<br />
<form method="post" action="database_tools.php"><?PHP echo check_field_create('admin') ?>
<input type="hidden" name="action" value="database_command">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" class="common_table_top_cell">Run A Mysql Command</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="center">Enter a mysql command to run. Never use this function if you are not sure what you are doing.</td></tr>
<tr><td align="center" nowrap><textarea class="field10" name="command" style="width:650px;height:250px;"></textarea></td></tr>
<tr><td align="center">
<input type="submit" name="A1" value="Submit" class="button10">
</td></tr></table></td></tr></table></form>
<?PHP
ift();
}

#########################################################################

function database_backup($data) {
global $s;
ini_set("magic_quotes_runtime",0);
set_time_limit(1000);
$line_separator = "\n";
$tables = mysql_list_tables($s[dbname]);
$num_tables = mysql_numrows($tables);
if ($num_tables == 0) $backup .= "# No tables found";
else
{ $i = 0;
  $backup .= "# Database name: $s[dbname]\n";
  while ($i < $num_tables)
  { $table = mysql_tablename($tables,$i);
    $i++;
    if (!strstr($table,$s[pr])) continue;
    $backup .= "\n#\n# Structure of table '$table'\n#\n\n";
    $backup .= get_table_def($table,$line_separator,0).";\n\n";
    $backup .= "#\n# Data in table '$table'\n#\n\n";
    $backup .= get_table_content($table);
    increase_print_time(2,1);
  }
}
$write = fopen("$s[phppath]/data/$data[backupfile]",'w');
fwrite($write,$backup);
chmod("$s[phppath]/data/$data[backupfile]",0666);
increase_print_time(2,'end');
ih();
echo info_line('Finished!');
echo 'Your mysql database has been backed up to file '.$data[backupfile].' in your "data" directory.<br />It\'s recommended to <b>check this file</b>. If this file is empty or is missing, you don\'t have the right to backup a database on this server. In this case you have to use phpmyadmin.<br />';
ift();
}

#########################################################################

function get_table_def($table,$line_separator,$drop) {
global $s;
if ($drop) $schema_create .= "DROP TABLE IF EXISTS $table;$line_separator";
$schema_create .= "CREATE TABLE $table ($line_separator";
$q = mysql_query("SHOW FIELDS FROM $table");
while ($row = mysql_fetch_assoc($q))
{ $schema_create .= "   $row[Field] $row[Type]";
  if (!empty($row["Default"])) $schema_create .= " DEFAULT '$row[Default]'";
  if ($row["Null"] != "YES") $schema_create .= " NOT NULL";
  if ($row["Extra"] != "") $schema_create .= " $row[Extra]";
  $schema_create .= ",$line_separator";
}
$schema_create = preg_replace("/,$line_separator$/",'',$schema_create);
$q = mysql_query("SHOW KEYS FROM $table");
while ($row = mysql_fetch_assoc($q))
{ $kname=$row['Key_name'];
  if (($kname != "PRIMARY") && ($row['Non_unique'] == 0)) $kname="UNIQUE|$kname";
  if ($row[Index_type]!='BTREE') $kname="FULLTEXT|$kname";
  if(!is_array($index[$kname])) $index[$kname] = array();
  $index[$kname][] = $row['Column_name'];
}
while(list($x,$columns) = each($index))
{ $schema_create .= ",$line_separator";
  if($x == "PRIMARY") $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
  elseif (substr($x,0,6) == 'UNIQUE') $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
  elseif (substr($x,0,8) == 'FULLTEXT') $schema_create .= "   FULLTEXT KEY  (" . implode($columns, ", ") . ")";
  else $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
}
$schema_create .= "$line_separator)";
return (stripslashes($schema_create));
} 

#########################################################################

function get_table_content($table) {
$q = mysql_query("SELECT * FROM $table");
while ($row = mysql_fetch_row($q))
{ set_time_limit(60);
  $schema_insert = "INSERT INTO $table VALUES(";
  for ($j=0; $j<mysql_num_fields($q);$j++)
  { if (!isset($row[$j])) $schema_insert .= " NULL,";
    elseif ($row[$j] != "") $schema_insert .= " '".addslashes($row[$j])."',";
    else $schema_insert .= " '',";
  }
  $schema_insert = preg_replace("/,$/",'',$schema_insert);
  $schema_insert .= ")";
  $backup .= trim($schema_insert).";\n";
}
return $backup;
}

#################################################################################

function database_optimize() {
global $s;
$q = mysql_list_tables($s[dbname]);
while ($table = mysql_fetch_row($q))
{ if (strstr($table[0],$s[pr])) dq("optimize table $table[0]",1); }
$s[info] = info_line('All tables used by Link Up Gold have been optimized');
database_home();
}

#################################################################################

function database_command($command) {
global $s;
$command = stripslashes($command);
$q = dq($command,1);
$s[info] = info_line('Your command has been run.<br />If your database returned a result, you can see it below this line',$q);
database_home();
}

#################################################################################
#################################################################################
#################################################################################

function uninstall($sure) {
global $s;
ih();
if (!is_array(file($s[phppath].'/data/uninstall')))
{ echo info_line('Unistallation is disabled for security reasons. To enable it upload file "uninstall" which can be found in folder "do_not_upload/tools" to your data directory.');
  ift();
}
if (!$sure)
{ echo info_line('This function deletes all tables with names which begin to "'.$s[pr].'"<br />(it\'s prefix of tables which are used by Link Up Gold).<br />All data will be lost. This can\'t be undone.<br /><br />Are you sure?').
  '<form action="database_tools.php" method="post">
  <input type="hidden" name="action" value="uninstall">
  <input type="submit" name="x" value="Yes, I\'m sure" class="button10"></form>
  Once it deletes all the tables, you will not be able to use most functions from the menu on the left. If you will want to use the script furthermore, you will need to install it again.';
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

#################################################################################
#################################################################################
#################################################################################

?>