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

error_reporting (E_ERROR | E_PARSE);
include('data/data.php');
$data = $_GET[url];
    
include "qrcode/qrlib.php";    
$errorCorrectionLevel = 'M'; $matrixPointSize = 4;
if (!trim($data)) die('data cannot be empty!');
        
$filename = "$s[phppath]/uploads/qrcodes/".md5($data).'.png';
if (!file_exists($filename)) QRcode::png($data,$filename,$errorCorrectionLevel,$matrixPointSize,2);    
/*
echo $filename;
echo '<img src="'.$s[site_url].'/uploads/qrcodes/'.basename($filename).'" />';  
*/  
$fp = fopen($filename,'rb');
header("Content-Type: image/png");
header("Content-Length: " . filesize($filename));
fpassthru($fp);
exit;

?>