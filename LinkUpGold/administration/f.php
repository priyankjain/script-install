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

$s[cas] = time() + $s[timeplus];
$linkid = db_connect(); if (!$linkid) die($s[db_error]);
$s[ip] = get_ip();
list($s[year],$s[month],$s[day]) = explode('-',date("Y-n-j",$s[cas]));

#####################################################################################

function check_field_create($data) {
global $s;
//echo "kk $data<br><br>";
if (!$data) return false;
if (!$_SESSION[sess_check_field]) $_SESSION[sess_check_field] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,50);
$s[dbpassword] = str_replace('&amp;','&',$s[dbpassword]);
$s[phppath] = str_replace('&amp;','&',$s[phppath]);
if ($data=='admin') $s[check_field] = md5(base64_encode(md5("$s[dbpassword]$s[phppath]$_SESSION[sess_check_field]$s[ip]")));
else
{ $s[check_field] = md5(base64_encode(md5("$data$s[ip]")));
  //echo "mm $s[check_field] --- $data$s[ip]<br><br>";
}
return '<input type="hidden" name="check_field" value="'.$s[check_field].'">';
}

#####################################################################################

function check_field($data) {
global $s;
if ($s['no_test']) return false;
if (!$s[check_field]) check_field_create($data);
if ($_POST[check_field]) $check_field = $_POST[check_field]; else $check_field = $_GET[check_field];
//echo "check_field ((!$s[check_field]) OR ($s[check_field]!=$check_field))<br><br>";
if ((!$s[check_field]) OR ($s[check_field]!=$check_field)) problem('Security test failed. Please login again.');
}

##################################################################################

function parse_php_code($in) {
for ($parse_php=0;$parse_php<=strlen($in);$parse_php++)
{ $char = substr($in,$parse_php,2);
  if ($char=='<?')
  { $phpcommand = $char; $end = 0; $parse_php++;
    while (!$end) { $parse_php++; $char = substr($in,$parse_php,2); if ($char=='?>') $end = 1; else $phpcommand .= substr($in,$parse_php,1); }
    eval(stripslashes(str_replace('<?','',$phpcommand)));
    $parse_php++;
  }
  else $line .= substr($in,$parse_php,1);
}
return $line;
}

##################################################################################

function read_template($t) {
$line = implode('',file($t));
if ($s[php_templates]) $line = parse_php_code($line);
return $line;
}

function parse_variables_in_template($t,$vl) {
global $s;
$line = read_template($t);
$line = preg_replace("/(\[metatags\])(.*)\[\/metatags\]/isU",'',$line);
foreach ($s[item_types_short] as $k=>$v) if (!$s["section_$v"]) $line = preg_replace('/#%begin_'.$v.'%#(.*)#%end_'.$v.'%#/eisU','',$line);
$vl[current_year] = $s[year]; $vl[current_month] = $s[month]; $vl[current_day] = $s[day];
return parse_variables($line,$vl);
}

function parse_variables($line,$vl) {
preg_match_all("/(<FILE>)(.*)(<\/FILE>)/",$line,$x1);
foreach ($x1[0] as $k => $v) $line = str_replace($v,include_file($v,$vl),$line);
foreach ($vl as $k=>$v) $line = str_replace("#%$k%#",$v,$line);
$line = preg_replace("/#%[a-z0-9_]*%#/i",'',$line);
return stripslashes($line);
}

function include_file($tag,$vl) {
$file_url = str_replace('<FILE>','',str_replace('</FILE>','',$tag));
foreach ($vl as $k=>$v) $file_url = str_replace("#%$k%#",$v,$file_url);
//$line = fetchURL($c);
$f = fopen($file_url,'r');
$line = fread($f,100000);
fclose($f);
return $line;
}

#####################################################################################
#####################################################################################
#####################################################################################

function pp_currency_select($field_name,$selected) {
global $s;
$s[pp_currencies] = array('USD'=>'U.S. Dollars','EUR'=>'Euros','GBP'=>'Pounds Sterling','AUD'=>'Australian Dollars','CAD'=>'Canadian Dollars','CZK'=>'Czech Koruna','DKK'=>'Danish Krone','HKD'=>'Hong Kong Dollar','HUF'=>'Hungarian Forint','NZD'=>'New Zealand Dollar','NOK'=>'Norwegian Krone','PLN'=>'Polish Zloty','SGD'=>'Singapore Dollar','SEK'=>'Swedish Krona','CHF'=>'Swiss Franc','JPY'=>'Yen');
foreach ($s[pp_currencies] as $k=>$v)
{ if ($k==$selected) $x = ' selected'; else $x = '';
  $a .= '<option value="'.$k.'"'.$x.'>'.$v.'</option>';
}
return '<select class="select10" name="'.$field_name.'">'.$a.'</select>';
}

#####################################################################################
#####################################################################################
#####################################################################################

function get_fckeditor($field_name,$value) {
global $s;
return '<script src="'.$s[site_url].'/ckeditor/ckeditor.js"></script>
<textarea name="'.$field_name.'">'.str_replace('&quot;','"',stripslashes($value)).'</textarea>
<script>
    CKEDITOR.replace( \''.$field_name.'\' );
</script>';
}

#####################################################################################

function refund_html($text) {
$a = str_split($text,1); unset($text); 
foreach ($a as $k=>$v) { if (ord($v)==160) $text .= ' '; elseif (ord($v)>30) $text .= $v; }
return str_replace('&amp;','&',str_replace('&#60;','<',str_replace('&lt;','<',str_replace('&gt;','>',str_replace('&quot;','"',$text)))));
}

###################################################################################

function info_line($line1,$line2) {
global $s;
$a = '<div class="info_line"><b><img border="0" src="'.$s[site_url].'/images/icon_info.png">&nbsp;'.$line1.'</b>';
if ($line2) $a .= '<br>'.$line2;
$a .= '</div><br>';
return $a;
}

###################################################################################
###################################################################################
###################################################################################

function db_connect() {
global $s;
unset($s[db_error],$s[dben]);
if ($s[nodbpass]) $link_id = mysql_connect($s[dbhost], $s[dbusername]);
else $link_id = mysql_connect($s[dbhost],$s[dbusername],$s[dbpassword]);
if(!$link_id)
{ $s[db_error] = "Unable to connect to the host $s[dbhost]. Check database host, username, password."; $s[dben] = mysql_errno(); return 0; }
if ( (!$s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if ( ($s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if (($s[charset]=='UTF-8') OR ($s[charset]=='utf-8')) MySQL_Query("SET NAMES utf8");
return $link_id;
}

#####################################################################################

function dq($query,$check) {
global $s;
$query = str_replace('insert into','insert ignore into',$query);
$query = str_replace("update $s[pr]","update ignore $s[pr]",$query);
$q = mysql_query($query);
//if ( ($check) AND (!$q) ) die($query);
if ( ($check) AND (!$q) ) die(mysql_error());
return $q;
}

#####################################################################################
#####################################################################################
#####################################################################################

function mail_head($html) {
global $s;
if ($html) return "\nMime-Version: 1.0\nContent-Type: text/html; charset=$s[charset]\nContent-Transfer-Encoding: 8bit";
else return "\nContent-Type: text/plain; charset=$s[charset]";
}

#####################################################################################

function unhtmlentities($string) {
$string = str_replace('&#039;',"'",$string);
$string = str_replace('&#92;','\\',$string);
$trans_tbl = get_html_translation_table(HTML_ENTITIES);
$trans_tbl = array_flip($trans_tbl);
return strtr($string,$trans_tbl);
}

#####################################################################################
#####################################################################################
#####################################################################################

function get_timestamp($d,$m,$y,$x,$time) {
if ((!$d) AND (!$m) AND (!$y)) return 0;
if ($time) list($hh,$mm) = explode(':',$time);
if (($hh) AND ($mm))
{ if ((!$d) AND (!$m)) { $d = 1; $m = 1; }
  elseif (!$d) $d = date('t',mktime(0,0,0,$m,15,$y));
  return mktime($hh,$mm,1,$m,$d,$y);
}
if ($x=='start')
{ if ((!$d) AND (!$m)) { $d = 1; $m = 1; }
  elseif (!$d) $d = date('t',mktime(0,0,0,$m,15,$y));
  return mktime(0,0,1,$m,$d,$y);
}
if ((!$d) AND (!$m)) { $d = 31; $m = 12; }
elseif (!$d) $d = date('t',mktime(0,0,0,$m,15,$y));
return mktime(23,59,59,$m,$d,$y);
}

#####################################################################################
#####################################################################################
#####################################################################################

function get_styles_list($plus_common) {
global $s;
$dr = opendir($s[phppath].'/styles');
rewinddir($dr);
while ($q = readdir($dr))
{ if (($q != '.') AND ($q != '..') AND ($q != '_common') AND (is_dir("$s[phppath]/styles/$q")))
  $styles_list[] = $q;
}
closedir ($dr);
if ($plus_common) $styles_list[] = '_common';
sort($styles_list);
return $styles_list;
}

########################################################################################

function my_implode($item,$bool,$array) {
// pripravi implode pro databaze dotaz
return '('.$item.' = \''.implode('\' '.$bool.' '.$item.' = \'',$array).'\')';
}

########################################################################################

function replace_special_characters($in) {
$in = strip_tags($in);
$search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
$replace = array ('','','','','','','','','','','','','');
return preg_replace($search,$replace,$in);
}

########################################################################################
########################################################################################
########################################################################################

function day_number($x) {
// vraci cislo aktualniho dne, musi se mu poslat $s[cas] ( tj. time()+$s[timeplus] )
global $s;
if (!$x) $x = $s[cas];
return date('j',$x);
}

########################################################################################

function month_number($x) {
// vraci cislo aktualniho mesice, musi se mu poslat $s[cas] ( tj. time()+$s[timeplus] )
global $s;
if (!$x) $x = $s[cas];
return date('n',$x);
}

########################################################################################

function year_number($x) {
// vraci cislo aktualniho roku, musi se mu poslat $s[cas] ( tj. time()+$s[timeplus] )
global $s;
if (!$x) $x = $s[cas];
return date('Y',$x);
}

###################################################################################

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

########################################################################################
########################################################################################
########################################################################################

function fetchURL($url) {
set_time_limit(120);
$url_parsed = parse_url($url);
$host = $url_parsed["host"];
$port = $url_parsed["port"];
if ($port==0) $port = 80;
$path = $url_parsed["path"];
if ($url_parsed["query"] != '') $path .= "?".$url_parsed["query"];
$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
$fp = fsockopen($host,$port,$errno,$errstr,3);
if ((!$fp) OR ($errno) OR ($errstr)) return false;
stream_set_timeout($fp,3);
fwrite($fp,$out);
$body = false;
while (!feof($fp)) { $s = fgets($fp,1024); if ($body) $in .= $s; if ( $s == "\r\n" ) $body = true; }
fclose($fp);
return stripslashes($in);
}

########################################################################################

function resize_image($file,$file1,$w,$h,$force_size) {
if (!file_exists($file)) return false;
$file_info=getimagesize($file); 
$original_w = $file_info[0];
$original_h = $file_info[1];
if ($force_size)
{ $new_w = round($w);
  $new_h = round($h);
}
else
{ if ($original_w>=$original_h) { $new_w = $w; $new_h = ($new_w/$original_w)*$original_h; }
  else { $new_h = $h; $new_w = ($new_h/$original_h)*$original_w; }
  $new_w = round($new_w);
  $new_h = round($new_h);
}
if($file_info[mime] == "image/gif")
{ $tmp=imagecreatetruecolor($new_w,$new_h); 
  $src=imagecreatefromgif($file); 
  imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_w, $new_h,$original_w,$original_h); 
  $con=imagegif($tmp, $file1); 
  imagedestroy($tmp); 
  imagedestroy($src);
  if($con) return true; else return false;
} 
else if(($file_info[mime] == "image/jpg") || ($file_info[mime] == "image/jpeg") )
{ $tmp=imagecreatetruecolor($new_w,$new_h); 
  $src=imagecreatefromjpeg($file);  
  imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_w, $new_h,$original_w,$original_h); 
  $con=imagejpeg($tmp, $file1); 
  imagedestroy($tmp); 
  imagedestroy($src); 
  if($con) return true; else return false;
} 
else if($file_info[mime] == "image/png")
{ $tmp=imagecreatetruecolor($new_w,$new_h); 
  $src=imagecreatefrompng($file); 
  imagealphablending($tmp, false); 
  imagesavealpha($tmp,true); 
  $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127); 
  imagefilledrectangle($tmp, 0, 0, $new_w, $new_h, $transparent);  
  imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_w, $new_h,$original_w,$original_h); 
  $con=imagepng($tmp, $file1); 
  imagedestroy($tmp); 
  imagedestroy($src);
  if($con) return true; else return false;
}
}

########################################################################################

function replace_html($x) {
// vhodne na html pred vlozenim do databaze, po vytazeni se ale musi vratit ' a \
if (!$x) return $x;
if (!is_array($x)) $x[0] = $x;
foreach ($x as $k => $v)
{ if (is_array($v)) continue;
  $v = stripslashes($v);
  $v = str_replace("''","'",str_replace("'","'",str_replace('"','"',$v)));
  $x[$k] = str_replace('&amp;','&',str_replace(chr(92),'&#92;',str_replace("'",'&#039;',$v)));
}
return $x;
}

########################################################################################

function get_ip() {
if (getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");
elseif ($_SERVER[REMOTE_ADDR]) $ip = $_SERVER[REMOTE_ADDR];
if ($ip=='127.0.0.1')
{ if (getenv("HTTP_X_REAL_IP")) $ip = getenv("HTTP_X_REAL_IP");
  elseif ($_SERVER[HTTP_X_REAL_IP]) $ip = $_SERVER[HTTP_X_REAL_IP];
}
if (!preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" . "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $ip)) $ip = 'UNKNOWN';
return $ip;
}

########################################################################################

function replace_once_html($x) {
// vhodne na html pred vlozenim do databaze, po vytazeni se ale musi vratit ' a \
if (!$x) return $x;
$x = stripslashes($x);
return str_replace('&amp;','&',str_replace(chr(92),'&#92;',str_replace("'",'&#039;',$x)));
}

########################################################################################

function unreplace_once_html($x) {
// na html po vytazeni z databaze
if (!$x) return $x;
$x = str_replace("''","'",str_replace(chr(92),'',$x));
return str_replace('&#039;',"'",str_replace("--BACKSLASH--",'\\\\',str_replace('&#92;','\\',$x)));
}

########################################################################################

function replace_once_text($x) {
// premeni < > ' " \
// vhodne na jakykoliv text pred vlozenim do databaze, ne na html
if (!$x) return $x;
$x = stripslashes($x);
$x = str_replace("''","'",str_replace("'","'",str_replace('"','"',$x)));
return str_replace('&amp;','&',str_replace(chr(92),'&#92;',htmlspecialchars($x,ENT_QUOTES)));
}

########################################################################################

function replace_array_text($x) {
// premeni < > ' " \
// vhodne na jakykoliv text pred vlozenim do databaze, ne na html
if (!$x) return $x;
foreach ($x as $k => $v)
{ if (is_array($v)) continue;
  $v = stripslashes($v);
  $x[$k] = str_replace('&amp;','&',str_replace(chr(92),'&#92;',htmlspecialchars($v,ENT_QUOTES)));
}
return $x;
}

########################################################################################

function strip_replace_once($in) {
if (is_array($in)) return $in;
$in = str_replace("''","'",$in); $in = str_replace(chr(92),'',$in);
return $in;
}

########################################################################################

function stripslashes_array($x) {
if (!$x) return $x; 
foreach ($x as $k => $v)
{ if (is_array($v)) continue;
  $x[$k] = stripslashes($v);
}
return $x;
}

########################################################################################

function get_random_password() {
list($usec,$sec) = explode(' ',microtime());
$x = $sec+($usec*1000000);
return substr(md5($x.$s[cas]),5,15);
}

########################################################################################

function my_substr($string,$i) {
if (strlen($string)<=$i) return $string;
while($i>0) { if($string{$i} == ' ') break; else $i--; }
$a = substr($string,0,$i);
return $a;
}

########################################################################################

function datum($cas,$plustime) {
global $s;
if (is_array($cas)) $cas = mktime(6,0,0,$cas[date_m],$cas[date_d],$cas[date_y]);
elseif (!$cas) $cas = $s[cas];
for ($y=1;$y<=3;$y++) if ($s['date_form_'.$y.'a']=='Space') $date_separator[$y] = ' '; elseif ($s['date_form_'.$y.'a']=='Nothing') $date_separator[$y] = ''; else $date_separator[$y] = $s['date_form_'.$y.'a'];
$x[d] = date('d',$cas); $x[m] = date('m',$cas); $x[y] = date('Y',$cas);
$datum = $x[$s[date_form_1]].$date_separator[1].$x[$s[date_form_2]].$date_separator[2].$x[$s[date_form_3]].$date_separator[3];
if ($plustime) { if ($s[time_form]=='12') $datum .= date(', g:i a',$cas); else $datum .= date(', G:i',$cas); }
return $datum;
}

########################################################################################

function date_select($in,$select_name) {
global $s;
if ($in) list($date_d,$date_m,$date_y) = explode('|',date('j|m|Y',$in));
$select[d] = '<select class="select10" name="'.$select_name.'[d]">'.select_days($date_d).'</select>';
$select[m] = '<select class="select10" name="'.$select_name.'[m]">'.select_months($date_m).'</select>';
$select[y] = '<select class="select10" name="'.$select_name.'[y]">'.select_years($date_y).'</select>';
for ($y=1;$y<=3;$y++) if ($s['date_form_'.$y.'a']=='Space') $date_separator[$y] = ' '; elseif ($s['date_form_'.$y.'a']=='Nothing') $date_separator[$y] = ''; else $date_separator[$y] = $s['date_form_'.$y.'a'];
$date = $select[$s[date_form_1]].$date_separator[1].$select[$s[date_form_2]].$date_separator[2].$select[$s[date_form_3]].$date_separator[3];
if (!$in) $date = str_replace(' selected','',$date);
return $date;
}

########################################################################################

function template_select($t,$email,$style) {
global $s;
if ($email) $folder = 'email_templates'; else $folder = 'templates';
if (file_exists("$s[phppath]/styles/$style/$folder/$t"))
return "$s[phppath]/styles/$style/$folder/$t";
return "$s[phppath]/styles/_common/$folder/$t";
}

##################################################################################

function my_send_mail($from,$from_name,$to,$html_mail,$subject,$body,$show_errors) {
global $s;
$show_errors = 0;
if (is_array($to)) $to_array = $to; else $to_array[0] = $to;
foreach ($to_array as $k=>$v) if (!trim($v)) unset($to_array[$k]);
if (!count($to_array)) $to_array[0] = $s[mail];

if (!$from) $from = $s[mail];
if (!$from_name) $from_name = $from;
$subject = str_replace('&#039;',"'",$subject);
$body = str_replace('&#039;',"'",$body);

require_once("$s[phppath]/phpmailer.php");
$mail = new PHPMailer();
if (trim($s[smtp_server]))
{ $mail->IsSMTP();
  $mail->Host = $s[smtp_server];
  if ((trim($s[smtp_username])) AND (trim($s[smtp_password]))) $mail->SMTPAuth = true;
  $mail->Username = $s[smtp_username];
  $mail->Password = $s[smtp_password];
}
$mail->From = $s[mail];
if ($s[site_title]) $mail->FromName = $s[site_title]; else $mail->FromName = $s[site_name];
foreach ($to_array as $k=>$v) $mail->AddAddress($v);
$mail->AddReplyTo($from);
$mail->CharSet = $s[charset];
$mail->WordWrap = 220;
if ($html_mail) $mail->IsHTML(true);
$mail->Subject = $subject;
$mail->Body    = $body;
if ($html_mail) $mail->AltBody = strip_tags($body);
if ((!$mail->Send()) AND ($show_errors)) { $error = $mail->ErrorInfo; die('Unable to send email. '.$error); }
}

########################################################################################
########################################################################################
########################################################################################

?>