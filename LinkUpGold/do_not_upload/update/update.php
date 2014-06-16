<?PHP

error_reporting  (E_ERROR | E_PARSE);
include_once('./data/data.php');
$linkid = db_connect(); if (!$linkid) echo mysql_error();
if (ini_get("magic_quotes_sybase")) ini_set("magic_quotes_sybase",0);
if (!get_magic_quotes_gpc()) ini_set("magic_quotes_gpc",1);
ini_set("magic_quotes_runtime",1);

if (!$_GET[action]) show_frame();

include('./administration/_head.txt');
echo '<LINK href="./administration/styles.css" rel=StyleSheet>';
set_time_limit(300);

switch ($_GET[action]) {
case 'top_frame'						: top_frame();
case 'bottom_frame_display_steps'		: bottom_frame_display_steps();
case 'nic'								: exit;
case 'step1_new_tables'					: step1_new_tables();
case 'step2_update_tables'				: step2_update_tables();
case 'step3_convert_to_utf8'			: step3_convert_to_utf8();
}

###############################################################################
###############################################################################
###############################################################################

function show_frame() {
echo '<html>
<head>
<title>UPDATE</title>
<base target="bottom"></head>
<frameset rows="*,350" cols="1*" border="0">
<frame name="top" scrolling="auto" marginwidth="10" marginheight="14" src="update.php?action=top_frame">
<frame name="bottom" scrolling="auto" marginwidth="10" marginheight="14" src="update.php?action=nic"></frameset>
</html>';
exit;
}

###############################################################################

function top_frame() {
global $s;

dq("insert into $s[pr]u_friends values(1,1,1,1)",0);
$q = dq("select * from $s[pr]u_friends limit 1",0); 
$x = mysql_fetch_row($q);
dq("delete from $s[pr]u_friends where user1 = 1 and user2 = 1",0);
if ($x[0]) 
echo info_line('REALLY UPDATE?','It seems that you already have version 8.0 or you have ran some steps of this update. If you will run the same step twice, the database will be damaged. However if you have ran some steps only, click the link below to display all available steps.');
else echo info_line('UPDATE TO VERSION 8.0<br><br>Make sure to have a database backup before you run this update so you can get the current version back if something goes wrong.');
echo '<br><br><br><a target="bottom" href="update.php?action=bottom_frame_display_steps">Click here to continue</a>';
exit;
}

###############################################################################

function bottom_frame_display_steps() {
global $s;
echo 'Click on each of these links. Each of them will run one step of the update. Never run the same step twice, make sure to run these steps by the correct rank.<br /><br />
<a target="top" href="update.php?action=step1_new_tables">Step 1. - Create new tables</a><br />
<a target="top" href="update.php?action=step2_update_tables">Step 2. - Update existing tables</a><br />
<br /><br /><br />
Optional step:<br />
This step is HIGHLY recommended if your database currently uses another charset than UTF-8. You can decide not to run this step but not-English names of cities and states will not be displayed correctly.<br />
<a target="_top" href="update.php?action=step3_convert_to_utf8">Step 3. - Convert all tables to UTF-8</a><br />
';
exit;
}

###############################################################################

function step3_convert_to_utf8() {
global $s;
$sql = 'SHOW TABLES';
$result = mysql_query('SHOW TABLES');   
while ( $tables = mysql_fetch_row($result) ) {
  if (!strstr($tables[0],$s[pr])) continue;
    echo "$tables[0]<br>";
# Loop through all tables in this database
   $table = $tables[key($tables)];
   
  
   if ( !( $result2 = mysql_query("ALTER TABLE ".$table." COLLATE utf8_unicode_ci") ) ) {
        echo '<span style="color: red;">UTF SET - SQL Error: <br>' . "</span>\n";
     
          break;
           }
  
   print "$table changed to UTF-8 successfully.<br>\n";

   # Now loop through all the fields within this table
   if ( !($result2 = mysql_query("SHOW COLUMNS FROM ".$table) ) ) {
          echo '<span style="color: red;">Get Table Columns Query - SQL Error: <br>' . "</span>\n";
     
          break;
           }

   while ( $column = mysql_fetch_assoc($result2) )
   {
      $field_name = $column['Field'];
      $field_type = $column['Type'];
     
      # Change text based fields
      $skipped_field_types = array('char', 'text', 'enum', 'set');
     
      foreach ( $skipped_field_types as $type )
      {        
         if ( strpos($field_type, $type) !== false )
         {
            $sql4 = "ALTER TABLE $table CHANGE `$field_name` `$field_name` $field_type CHARACTER SET utf8 COLLATE utf8_general_ci";
            $result4 = mysql_query($sql4);

            //echo "---- $field_name changed to UTF-8 successfully.<br>\n";
         }
      }
   }
}
echo info_line('<br>All tables have been converted. Please change the charset in your configuration to UTF-8.');
echo info_line('<br>All done. Please delete the file update.php and follow remaining upgrade instructions.');
}

###############################################################################

function step1_new_tables() {
global $s;
$table[] = 'u_friends';
$q[] = "(
  `user1` int(10) unsigned NOT NULL,
  `user2` int(10) unsigned NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `work1` (`user1`,`accepted`),
  KEY `work2` (`user2`,`accepted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'u_wall';
$q[] = "(
  `n` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `n` (`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'city_zip';
$q[] = "(
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `country_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `zip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `lat` double(10,4) NOT NULL,
  `lon` double(10,4) NOT NULL,
  PRIMARY KEY (`n`),
  UNIQUE KEY `countryzip` (`country`,`zip`,`city`),
  KEY `zip` (`zip`),
  KEY `cityzip` (`city`,`zip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$table[] = 'items_maps';
$q[] = "(
  `what` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT '0',
  `latitude` double(12,7) NOT NULL DEFAULT '0.0000000',
  `longitude` double(12,7) NOT NULL DEFAULT '0.0000000',
  `map_zoom` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `country` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `zip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `work` (`what`,`n`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$tables = count($table);
for ($x=0;$x<=($tables-1);$x++)
{ if (mysql_query("DESCRIBE $s[pr]$table[$x]")) $uzbylo++;
  elseif ($q[$x])
  { $q1 = mysql_query("CREATE TABLE $s[pr]$table[$x] $q[$x]");
    if (!$q1) { chyba(mysql_error(),0); $chyby++; }
    else hlaseni("Table $s[pr]$table[$x] created.");
  }
}
if (!$chyby)
{ if ($uzbylo)
  { if ($uzbylo<$tables) hlaseni('<b>Setup created some tables, some tables have been created in the past.</b>');
    elseif ($uzbylo==$tables) hlaseni('<b>Setup did not create any tables, all necessary tables have been created in the past.</b>');
  }
}
echo info_line('<br>New tables created','Now continue with step #2');
exit;
}

###############################################################################

function step2_update_tables() {
global $s;

dq("ALTER TABLE $s[pr]cats ADD `rss_read_interval` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `rss_items`",1);
dq("update $s[pr]cats set rss_read_interval = '$s[n_load_interval_minutes]' where use_for = 'n'",1);
dq("update $s[pr]cats set rss_read_interval = '$s[v_load_interval_minutes]' where use_for = 'v'",1);
dq("ALTER TABLE $s[pr]cats ADD map_address varchar(255) DEFAULT NULL, 
ADD latitude double(12,7) NOT NULL DEFAULT '0.0000000', 
ADD longitude double(12,7) NOT NULL DEFAULT '0.0000000', 
ADD map_zoom tinyint(3) unsigned NOT NULL DEFAULT '0', 
ADD country varchar(10) CHARACTER SET utf8 DEFAULT NULL, 
ADD region varchar(255) CHARACTER SET utf8 DEFAULT NULL, 
ADD city varchar(255) CHARACTER SET utf8 DEFAULT NULL, 
ADD zip varchar(50) CHARACTER SET utf8 DEFAULT NULL",1);
dq("ALTER TABLE $s[pr]countries CHANGE `flag` `flag` varchar(255) CHARACTER SET utf8 DEFAULT NULL",1);
dq("ALTER TABLE $s[pr]cats ADD `dmoz_url` VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL",1);
dq("ALTER TABLE $s[pr]usit_list ADD `show_na` TINYINT(1) NOT NULL AFTER `kind`",1);
dq("ALTER TABLE $s[pr]users CHANGE `username` `username` VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL",1);
dq("ALTER TABLE $s[pr]users CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL",1);

mysql_query("truncate table $s[pr]countries");
mysql_query("INSERT INTO $s[pr]countries VALUES('AF', 'Afghanistan', 'Afghanistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AL', 'Albania', 'Albania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DZ', 'Algeria', 'Algeria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AS', 'American Samoa', 'American_Samoa.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AD', 'Andorra', 'Andorra.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AO', 'Angola', 'Angola.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AI', 'Anguilla', 'Anguilla.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AG', 'Antigua and Barbuda', 'Antigua_and_Barbuda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AR', 'Argentina', 'Argentina.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AM', 'Armenia', 'Armenia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AW', 'Aruba', 'Aruba.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AU', 'Australia', 'Australia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AT', 'Austria', 'Austria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AZ', 'Azerbaijan', 'Azerbaijan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BS', 'Bahamas', 'Bahamas.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BH', 'Bahrain', 'Bahrain.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BD', 'Bangladesh', 'Bangladesh.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BB', 'Barbados', 'Barbados.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BY', 'Belarus', 'Belarus.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BE', 'Belgium', 'Belgium.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BZ', 'Belize', 'Belize.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BJ', 'Benin', 'Benin.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BM', 'Bermuda', 'Bermuda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BT', 'Bhutan', 'Bhutan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BO', 'Bolivia', 'Bolivia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BA', 'Bosnia and Herzegovina', 'Bosnia_and_Herzegovina.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BW', 'Botswana', 'Botswana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BV', 'Bouvet Island', 'Bouvet_Island.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BR', 'Brazil', 'Brazil.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IO', 'British Indian Ocean Territory', 'British_Indian_Ocean_Territory.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BN', 'Brunei Darussalam', 'Brunei_Darussalam.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BG', 'Bulgaria', 'Bulgaria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BF', 'Burkina Faso', 'Burkina_Faso.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MM', 'Burma', 'Burma.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('BI', 'Burundi', 'Burundi.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KH', 'Cambodia', 'Cambodia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CM', 'Cameroon', 'Cameroon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CA', 'Canada', 'Canada.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CV', 'Cape Verde', 'Cape_Verde.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KY', 'Cayman Islands', 'Cayman_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CF', 'Central African Republic', 'Central_African_Republic.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TD', 'Chad', 'Chad.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CL', 'Chile', 'Chile.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CN', 'China', 'China.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CX', 'Christmas Island', 'Christmas_Island.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CC', 'Cocos (Keeling) Islands', 'Cocos_Keeling_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CO', 'Colombia', 'Colombia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KM', 'Comoros', 'Comoros.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CD', 'Congo, Democratic Republic of the', 'Congo_Democratic_Republic_of_the.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CG', 'Congo, Republic of the', 'Congo_Republic_of_the.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CK', 'Cook Islands', 'Cook_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CR', 'Costa Rica', 'Costa_Rica.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CI', 'Cote d&#039;Ivoire', 'Cote_dIvoire.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HR', 'Croatia', 'Croatia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CU', 'Cuba', 'Cuba.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CY', 'Cyprus', 'Cyprus.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CZ', 'Czech Republic', 'Czech_Republic.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DK', 'Denmark', 'Denmark.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DJ', 'Djibouti', 'Djibouti.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DM', 'Dominica', 'Dominica.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DO', 'Dominican Republic', 'Dominican_Republic.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EC', 'Ecuador', 'Ecuador.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EG', 'Egypt', 'Egypt.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SV', 'El Salvador', 'El_Salvador.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GQ', 'Equatorial Guinea', 'Equatorial_Guinea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ER', 'Eritrea', 'Eritrea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EE', 'Estonia', 'Estonia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ET', 'Ethiopia', 'Ethiopia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FK', 'Falkland Islands (Malvinas)', 'Falkland_Islands_Malvinas.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FO', 'Faroe Islands', 'Faroe_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FJ', 'Fiji', 'Fiji.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FI', 'Finland', 'Finland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FR', 'France', 'France.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GF', 'French Guiana', 'French_Guiana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PF', 'French Polynesia', 'French_Polynesia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TF', 'French Southern and Antarctic Lands', 'French_Southern_and_Antarctic_Lands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GA', 'Gabon', 'Gabon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GM', 'Gambia', 'Gambia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GE', 'Georgia', 'Georgia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('DE', 'Germany', 'Germany.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GH', 'Ghana', 'Ghana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GI', 'Gibraltar', 'Gibraltar.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GR', 'Greece', 'Greece.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GL', 'Greenland', 'Greenland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GD', 'Grenada', 'Grenada.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GP', 'Guadeloupe', 'Guadeloupe.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GU', 'Guam', 'Guam.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GT', 'Guatemala', 'Guatemala.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GG', 'Guernsey', 'Guernsey.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GN', 'Guinea', 'Guinea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GW', 'Guinea-Bissau', 'Guinea-Bissau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GY', 'Guyana', 'Guyana.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HT', 'Haiti', 'Haiti.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HM', 'Heard Island and McDonald Islands', 'Heard_Island_and_McDonald_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VA', 'Holy See (Vatican City)', 'Holy_See_Vatican_City.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HN', 'Honduras', 'Honduras.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HK', 'Hong Kong', 'Hong_Kong.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('HU', 'Hungary', 'Hungary.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IS', 'Iceland', 'Iceland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IN', 'India', 'India.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ID', 'Indonesia', 'Indonesia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IR', 'Iran', 'Iran.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IQ', 'Iraq', 'Iraq.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IE', 'Ireland', 'Ireland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IM', 'Isle of Man', 'Isle_of_Man.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IL', 'Israel', 'Israel.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('IT', 'Italy', 'Italy.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JM', 'Jamaica', 'Jamaica.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JP', 'Japan', 'Japan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JE', 'Jersey', 'Jersey.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('JO', 'Jordan', 'Jordan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KZ', 'Kazakhstan', 'Kazakhstan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KE', 'Kenya', 'Kenya.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KI', 'Kiribati', 'Kiribati.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KR', 'Korea', 'Korea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KP', 'Korea North', 'Korea_North.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KW', 'Kuwait', 'Kuwait.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KG', 'Kyrgyzstan', 'Kyrgyzstan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LA', 'Laos', 'Laos.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LV', 'Latvia', 'Latvia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LB', 'Lebanon', 'Lebanon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LS', 'Lesotho', 'Lesotho.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LR', 'Liberia', 'Liberia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LY', 'Libya', 'Libya.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LI', 'Liechtenstein', 'Liechtenstein.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LT', 'Lithuania', 'Lithuania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LU', 'Luxembourg', 'Luxembourg.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MO', 'Macau', 'Macau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MK', 'Macedonia', 'Macedonia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MG', 'Madagascar', 'Madagascar.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MW', 'Malawi', 'Malawi.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MY', 'Malaysia', 'Malaysia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MV', 'Maldives', 'Maldives.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ML', 'Mali', 'Mali.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MT', 'Malta', 'Malta.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MH', 'Marshall Islands', 'Marshall_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MQ', 'Martinique', 'Martinique.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MR', 'Mauritania', 'Mauritania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MU', 'Mauritius', 'Mauritius.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('YT', 'Mayotte', 'Mayotte.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MX', 'Mexico', 'Mexico.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('FM', 'Micronesia, Federated States of', 'Micronesia_Federated_States_of.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MD', 'Moldova', 'Moldova.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MC', 'Monaco', 'Monaco.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MN', 'Mongolia', 'Mongolia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ME', 'Montenegro', 'Montenegro.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MS', 'Montserrat', 'Montserrat.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MA', 'Morocco', 'Morocco.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MZ', 'Mozambique', 'Mozambique.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NA', 'Namibia', 'Namibia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NR', 'Nauru', 'Nauru.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NP', 'Nepal', 'Nepal.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NL', 'Netherlands', 'Netherlands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AN', 'Netherlands Antilles', 'Netherlands_Antilles.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NC', 'New Caledonia', 'New_Caledonia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NZ', 'New Zealand', 'New_Zealand.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NI', 'Nicaragua', 'Nicaragua.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NE', 'Niger', 'Niger.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NG', 'Nigeria', 'Nigeria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NU', 'Niue', 'Niue.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NF', 'Norfolk Island', 'Norfolk_Island.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('MP', 'Northern Mariana Islands', 'Northern_Mariana_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('NO', 'Norway', 'Norway.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('OM', 'Oman', 'Oman.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PK', 'Pakistan', 'Pakistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PW', 'Palau', 'Palau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PS', 'Palestinian Territory', 'Palestinian_Territory.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PA', 'Panama', 'Panama.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PG', 'Papua New Guinea', 'Papua_New_Guinea.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PY', 'Paraguay', 'Paraguay.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PE', 'Peru', 'Peru.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PH', 'Philippines', 'Philippines.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PN', 'Pitcairn_Islands', 'Pitcairn_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PL', 'Poland', 'Poland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PT', 'Portugal', 'Portugal.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PR', 'Puerto Rico', 'Puerto_Rico.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('QA', 'Qatar', 'Qatar.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RE', 'Reunion', 'Reunion.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RO', 'Romania', 'Romania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RU', 'Russia', 'Russia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RW', 'Rwanda', 'Rwanda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SH', 'Saint Helena Ascension and Tristan da Cunha', 'Saint_Helena_Ascension_and_Tristan_da_Cunha.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('KN', 'Saint Kitts and Nevis', 'Saint_Kitts_and_Nevis.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LC', 'Saint Lucia', 'Saint_Lucia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('PM', 'Saint Pierre and Miquelon', 'Saint_Pierre_and_Miquelon.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VC', 'Saint Vincent and the Grenadines', 'Saint_Vincent_and_the_Grenadines.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('WS', 'Samoa', 'Samoa.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SM', 'San Marino', 'San_Marino.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ST', 'Sao Tome and Principe', 'Sao_Tome_and_Principe.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SA', 'Saudi Arabia', 'Saudi_Arabia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SN', 'Senegal', 'Senegal.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('RS', 'Serbia', 'Serbia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SC', 'Seychelles', 'Seychelles.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SL', 'Sierra Leone', 'Sierra_Leone.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SG', 'Singapore', 'Singapore.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SK', 'Slovakia', 'Slovakia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SI', 'Slovenia', 'Slovenia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SB', 'Solomon Islands', 'Solomon_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SO', 'Somalia', 'Somalia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ZA', 'South Africa', 'South_Africa.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GS', 'South Georgia and South Sandwich Islands', 'South_Georgia_and_South_Sandwich_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ES', 'Spain', 'Spain.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('LK', 'Sri Lanka', 'Sri_Lanka.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SD', 'Sudan', 'Sudan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SR', 'Suriname', 'Suriname.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SZ', 'Swaziland', 'Swaziland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SE', 'Sweden', 'Sweden.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('CH', 'Switzerland', 'Switzerland.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('SY', 'Syria', 'Syria.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TW', 'Taiwan', 'Taiwan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TJ', 'Tajikistan', 'Tajikistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TZ', 'Tanzania', 'Tanzania.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TH', 'Thailand', 'Thailand.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TL', 'Timor-Leste', 'Timor-Leste.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TG', 'Togo', 'Togo.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TK', 'Tokelau', 'Tokelau.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TO', 'Tonga', 'Tonga.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TT', 'Trinidad and Tobago', 'Trinidad_and_Tobago.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TN', 'Tunisia', 'Tunisia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TR', 'Turkey', 'Turkey.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TM', 'Turkmenistan', 'Turkmenistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TC', 'Turks and Caicos Islands', 'Turks_and_Caicos_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('TV', 'Tuvalu', 'Tuvalu.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UG', 'Uganda', 'Uganda.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UA', 'Ukraine', 'Ukraine.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('AE', 'United Arab Emirates', 'United_Arab_Emirates.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('GB', 'United Kingdom', 'United_Kingdom.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('US', 'United States', 'United_States.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UM', 'United States Minor Outlying Islands', 'United_States_Minor_Outlying_Islands.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UY', 'Uruguay', 'Uruguay.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('UZ', 'Uzbekistan', 'Uzbekistan.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VU', 'Vanuatu', 'Vanuatu.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VE', 'Venezuela', 'Venezuela.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VN', 'Vietnam', 'Vietnam.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VG', 'Virgin Islands, British', 'Virgin_Islands_British.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('VI', 'Virgin Islands, U.S.', 'Virgin_Islands_US.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('WF', 'Wallis and Futuna', 'Wallis_and_Futuna.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('EH', 'Western Sahara', 'Western_Sahara.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('YE', 'Yemen', 'Yemen.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ZM', 'Zambia', 'Zambia.png', 0)");
mysql_query("INSERT INTO $s[pr]countries VALUES('ZW', 'Zimbabwe', 'Zimbabwe.png', 0)");

echo info_line('<br>Database updated successfully.','Now continue with the next step.');
exit;
}

##################################################################################

function increase_print_time($pause,$print) {
global $s;
// do not use $s[cas]
if ($s[no_increase_print]) set_time_limit(100); // public pages
else
{ if ((!$s[time_1]) AND (function_exists('ih'))) ih();
  $cas = time();
  if ($print=='end')
  { flush();
    echo '</span></span><script type="text/javascript">processing.style.display="none"</script>'; return false;
  }
  elseif ($print)
  { if (!$s[time_1]) { echo '<span id="processing"><span class="text13a_bold">Working, please wait ... </span><br /><span class="text10">'.str_repeat (' ',5000); flush(); }
    elseif ($cas>($s[time_1]+$pause)) { echo ' Working ... '.str_repeat (' ',4000); flush(); }
  }
  if ($cas>($s[time_1]+$pause)) { $s[time_1] = $cas; set_time_limit(100); }
}
}

###############################################################################

function discover_rewrite_url($in,$allow_slashes) {
global $s;
$in = str_replace('&#92;','',str_replace('&lt;','<',str_replace('&gt;','>',str_replace('&quot;','"',$in))));
if ($allow_slashes) $in = str_replace('/','slashslash',$in);
$in = preg_replace("/\W/e",'',preg_replace("/\s/e",'_',$in));
if ($allow_slashes) $in = str_replace('slashslash','/',$in);
return str_replace('_','-',$in);
}

###############################################################################

function dq($query,$check) {
global $s;
$q = mysql_query($query);
if (($check) AND (!$q)) chyba(mysql_error(),1);
return $q;
}

###############################################################################
###############################################################################
###############################################################################

function info_line($line1,$line2) {
$a = '<span class="text13a_bold">'.$line1.'</span>';
if ($line2) $a .= '<br />'.$line2.'</span>';
$a .= '<br /><br />';
return $a;
}

###############################################################################
###############################################################################
###############################################################################

function db_connect() {
global $s,$m;
unset($s[db_error],$s[dben]);
if ($s[nodbpass]) $link_id = mysql_connect($s[dbhost], $s[dbusername]);
else $link_id = mysql_connect($s[dbhost],$s[dbusername],$s[dbpassword]);
if(!$link_id)
{ $s[db_error] = "Unable to connect to database."; $s[dben] = mysql_errno(); return 0; }
if ( (!$s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if ( ($s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
return $link_id;
}

###############################################################################
###############################################################################
###############################################################################

function chyba($text,$fatal) {
echo '<span class="text13a_bold">'.$text.'</span><br />';
if ($fatal) { echo '<span class="text13a_bold"><br />Can\'t continue!</span><br />'; exit(); }
}


function hlaseni($text) {
echo ''.$text.'</span><br />';
}

function replace_once_text($x) {
if (!$x) return $x;
$x = stripslashes($x);
$x = str_replace('&amp;','&',str_replace(chr(92),'&#92;',htmlspecialchars($x,ENT_QUOTES)));
return $x;
}

function my_implode($item,$bool,$array) {
return '('.$item.' = \''.implode('\' '.$bool.' '.$item.' = \'',$array).'\')';
}

###############################################################################
###############################################################################
###############################################################################

?>