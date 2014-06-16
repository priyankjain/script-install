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


//if (!strstr(getenv('HTTP_REFERER'),str_replace('/image_control.php','',getenv('SCRIPT_URI'))))// exit;

class image_control {
var $width;
var $height = 20;
var $background_color = "188 220 231";
var $text_color = "0 0 0";
var $border_color = "0 0 0";
var $font_spacing = 15;
var $lines_frequency = 15;
function image_control() {
session_start();
}
function gerText($num) {
$this->number_characters = $num;		
$this->texto = $this->get_text();
$_SESSION['image_valid_code'] = $this->texto;
}
function get_both_codes($entered_code) {
$this->entered_code = $entered_code;
$this->valid_code = $_SESSION['image_valid_code'];
}
function image_show() {
$this->image_draw();
header("Content-type: image/png");
ImagePng($this->im);
}
function image_draw() {
$this->width = ($this->number_characters*$this->font_spacing) + 40;
$this->im = imagecreatetruecolor($this->width, $this->height); 
imagefill($this->im, 0, 0, $this->get_color($this->border_color));
imagefilledrectangle ( $this->im, 1, 1, ($this->width-2), ($this->height-2), $this->get_color($this->background_color) );
for ($i=1;$i<=$this->lines_frequency;$i++) {
$randomcolor = imagecolorallocate ($this->im , rand(100,255), rand(100,255),rand(100,255));
imageellipse($this->im,rand(0,$this->width-10),rand(0,$this->height-3), rand(20,60),rand(20,60),$randomcolor);
}
$ident = 20;
for ($i=0;$i<$this->number_characters;$i++) {
$char = substr($this->texto,$i,1);
$font = 10;
$y = round(($this->height-15)/2);
$col = $this->get_color($this->text_color);
if (($i%2) == 0) { imagechar ( $this->im, $font, $ident, $y+1, $char, $col ); }
else { imagechar ( $this->im, $font, $ident, $y-1, $char, $col ); }
//else { imagecharup ( $this->im, $font, $ident, $y+10, $char, $col ); } // otocit sude pismena
$ident = $ident+$this->font_spacing;
}
}
function get_color($var) {
$rgb = explode(" ",$var);
$col = imagecolorallocate ($this->im, $rgb[0], $rgb[1], $rgb[2]);
return $col;
}
function get_text() {
rand(0,time());
$possible="0123456789";
while(strlen($str)<$this->number_characters) { $str.=substr($possible,(rand()%(strlen($possible))),1); }
return $str;
}
}


if ($_GET[action]=='get_image')
{ $size = 5;
  $image_control = new image_control();
  $image_control->gerText($size);
  $image_control->image_show();
}

?>