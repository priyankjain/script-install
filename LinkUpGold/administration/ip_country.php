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

switch ($_GET[action]) {
case 'ip_country_home'				: ip_country_home();
case 'ip_country_auto'				: ip_country_auto();
}
switch ($_POST[action]) {
case 'ip_country_uploaded'			: ip_country_uploaded($_POST,$_FILES[ip_country_file]);
case 'countries_edited'				: countries_edited($_POST);
}
ip_country_home();

#################################################################################

function ip_country_auto() {
global $s;
ih();
$file_url = 'http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip';
$file = fopen($file_url,'r') or problem("Unable to download file $file_url");
$openfile = fopen("$s[phppath]/data/ip_list.zip",'w') or problem("Unable to write to file $s[phppath]/data/ip_list.zip");
while ($data = fread($file,1000))
{ increase_print_time(5,1);
  fwrite($openfile,$data) or problem("Unable to write to file $s[phppath]/data/ip_list.zip");
}
fclose ($file);
fclose($openfile);
echo "<br><br><b>File downloaded, starting to unpack it</b><br>";
$zip = new ZipArchive();   
if ($zip->open("$s[phppath]/data/ip_list.zip")!==TRUE) problem("Could not open archive");
$zip->extractTo("$s[phppath]/data");
$zip->close();
unlink("$s[phppath]/data/ip_list.zip");
echo "<br><br><b>File unpacked, starting import it to your database</b><br><br>";
$in[ip_country_uploaded_file] = "GeoIPCountryWhois.csv";
$in[delete_current_data] = 1;
ip_country_uploaded($in);
}

#################################################################################

function ip_country_home() {
global $s;
ih();
echo $s[info];

echo '
<table border="0" width="800" cellspacing="0" cellpadding="2" class="common_table">
<tr><td class="common_table_top_cell">IP-Country Configuration - Info</td></tr>
<tr><td align="left">
This database is used to show the message "Welcome to COUNTRY NAME" on your home page. This message is not available if you did not upload the IP / country database.
</td></tr><table>
<br>
<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="ip_country_uploaded">
<table border="0" width="800" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Import Records to IP-Country Database Automatically</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="2">This script can automatically download the IP country database and unpack it to your server. This process may take a few minutes. This function needs <a target="_blank" href="http://pecl.php.net/package/zip">ZIP extension in PHP</a>. If you receive the message "Cannot instantiate non-existent class: ziparchive", this extension is not available on your server.<br>
 Once it ended its work, you should see a message that it was successful. If you can\'t see this message, your server is unable to use this automatic way. In this case please use the manual upload form below.<br>
</td></tr>
<tr>
<td align="center" valign="top" nowrap><a href="ip_country.php?action=ip_country_auto">Click here to download the file automatically</a></td>
</tr>
</table>
</td></tr></table>
</form>
<br>
<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="ip_country_uploaded">
<table border="0" width="800" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Import Records to IP-Country Database Manually</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="2">You can download the database at <a target="_blank" href="http://www.maxmind.com/app/geolite">http://www.maxmind.com/app/geolite</a>. There is available a free list and also a commercial list which is more precise. Download the database in CSV format, unpack the zip archive to receive a CSV file. Then upload that file to your "data" directory and enter name of the file to the field below. You also should upload new data from time to time to have the statistic as accurate as possible.</td></tr>
<tr>
<td align="left" valign="top" nowrap>Enter name of the file that you uploaded to data directory</span></td>
<td align="left" nowrap><input class="field10" style="width:400px" name="ip_country_uploaded_file" value="GeoIPCountryWhois.csv"></td>
</tr>
<tr>
<td align="left" nowrap>Delete existing IP-country data in database</span></td>
<td align="left" nowrap><input type="checkbox" name="delete_current_data" value="1" checked></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="A1" value="Submit" class="button10"></td></tr>
</table>
</td></tr></table>
</form>
<br>
<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="countries_edited">
<table border="0" width="800" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Countries & Flags</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="5">Do not edit the codes if you are not sure.<br>
<tr>
<td align="center">Code</span></td>
<td align="center">Name</span></td>
<td align="center" colspan="2">Flag</span></td>
</tr>';
$q = dq("select * from $s[pr]countries order by name",1);
while ($country=mysql_fetch_assoc($q))
{ if ($country[flag]) $flag = '<img border="0" src="'.$s[site_url].'/images/flags/small/'.$country[flag].'">'; else $flag = '';
  $x++;
  $country[name] = strip_replace_once($country[name]);
  if ($country[allowed]) $checked = ' checked'; else $checked = '';
  echo '<tr>
  <td align="center"><input class="field10" style="width:50px" maxlenght="3" name="code['.$x.']" value="'.$country[code].'"></td>
  <td align="center"><input class="field10" style="width:350px" maxlenght="50" name="name['.$x.']" value="'.$country[name].'"></td>
  <td align="center"><input class="field10" style="width:350px" maxlenght="5" name="flag['.$x.']" value="'.$country[flag].'"></td>
  <td align="center">'.$flag.'</td>
  </tr>';
}
for ($y=1;$y<=3;$y++)
{ $x++;
  $country[name] = strip_replace_once($country[name]);
  if ($country[allowed]) $checked = ' checked'; else $checked = '';
  echo '<tr>
  <td align="center"><input class="field10" style="width:50px" maxlenght="3" name="code['.$x.']" value=""></td>
  <td align="center"><input class="field10" style="width:350px" maxlenght="50" name="name['.$x.']" value=""></td>
  <td align="center"><input class="field10" style="width:350px" maxlenght="5" name="flag['.$x.']" value=""></td>
  <td align="center">&nbsp;</td>
  </tr>';
}
echo '<tr><td align="center" colspan="5"><input type="submit" name="A1" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form><br><br>';
ift();
}

#################################################################################

function countries_edited($in) {
global $s;
$in[name] = replace_array_text($in[name]);
dq("delete from $s[pr]countries",1);
foreach ($in[name] as $k=>$v)
{ if (!trim($v)) continue;
  $code = $in[code][$k]; $name = $in[name][$k]; $flag = $in[flag][$k]; $allowed = $in[allowed][$k];
  dq("insert into $s[pr]countries values('$code','$name','$flag','$allowed')",1);
}
$s[info] = info_line('Records of countries updated');
ip_country_home();
}

#################################################################################

function ip_country_uploaded($in,$in_file) {
global $s;
$file = "$s[phppath]/data/ip_country_file";
if (is_uploaded_file($in_file[tmp_name])) 
{ if (file_exists($file)) unlink($file);
  move_uploaded_file($in_file[tmp_name],$file);
  chmod ($file,0644);
}
elseif ((trim($in[ip_country_uploaded_file])) AND (file_exists("$s[phppath]/data/$in[ip_country_uploaded_file]")))
{ if (file_exists($file)) unlink($file);
  rename("$s[phppath]/data/$in[ip_country_uploaded_file]",$file);
}
else { $s[upload_error] = 1; ip_country_home(); }
if ($in[delete_current_data]) dq("delete from $s[pr]ip_country",1);
$fd = fopen ($file,'r');
while (!feof ($fd))
{ $buffer = fgets($fd,10000);
  if (!trim($buffer)) continue;
  $pocet++; 
  $x = explode(',',trim($buffer));
  set_time_limit(300);
  dq("insert into $s[pr]ip_country values(".str_replace('\"','"',$x[2]).','.str_replace('\"','"',$x[3]).','.str_replace('\"','"',$x[4]).")",1);
  increase_print_time(2,1);
}
fclose ($fd);
unlink ($file);
increase_print_time(2,'end');
$s[info] = info_line('Import successful. Records imported: '.$pocet);
ip_country_home();
}


#################################################################################

?>