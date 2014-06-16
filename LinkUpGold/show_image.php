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
$_GET = replace_array_text($_GET);

$q = dq("select * from $s[pr]files where what = '$_GET[what]' and n = '$_GET[n]' and file_type = 'image'",1);
$image = mysql_fetch_assoc($q);

if (!$image[filename]) exit;
$picture = preg_replace("/\/$image[item_n]-/","/$image[item_n]-big-",$image[filename]);
if (!is_file(str_replace($s[site_url],$s[phppath],$picture))) $picture = $image[filename];

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=#%charset%#">
<title>'.$image[description].'</title>
<script type="text/javascript">
<!--
function sizeImg() {
window.resizeTo(document.all.foto.width+5,document.all.foto.height+70);
}
-->
</script>
</head>
<body onLoad="sizeImg()" style="margin-top:0px;margin-left:0px;padding-top:0px;">
<div align="center">
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr><td align="center" valign="middle"><a href="javascript:window.close()"><img id="foto" src="'.$picture.'" border="0"></a></td></tr>
</table></div>
</body></html>';





?>