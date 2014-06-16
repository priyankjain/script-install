<?PHP
session_start();

require_once('captcha.class.php');

$captcha = new captchaClass();

$images = array('images/bg_1.png', 'images/bg_2.png', 'images/bg_white.png'); // jpg, gif, png
$images = array('images/bg_white.png'); // jpg, gif, png
$fonts = array('fonts/Antipasto.ttf','fonts/Harabara.ttf','fonts/OldSansBlack.ttf','fonts/OldSansBlackUnderline.ttf'); // ttf fonts
$fontsizes = array(13, 14, 15);
$colors = array('40,55,9', '41,71,106', '77,16,16', '75,16,77', '77,76,16', '137,82,24'); // RGB colors


if($_REQUEST['np'] == '') {
	$numbers[0] = array(0,'zero');
	$numbers[1] = array(1,'one');
	$numbers[2] = array(2,'two');
	$numbers[3] = array(3,'three');
	$numbers[4] = array(4,'four');
	$numbers[5] = array(5,'five');
	$numbers[6] = array(6,'six');
	$numbers[7] = array(7,'seven');
	$numbers[8] = array(8,'eight');
	$numbers[9] = array(9,'nine');
	
	$randKey = rand(0,1);
	$key = $_SESSION['randomVerification'];
	$number = $numbers[$key][$randKey];
	$width = 320;
	$height = 30;
} else if($_SESSION['randomVerification'] != '') {
	$number = $_SESSION['randomVerification'];
	$width = 90;
	$height = 30;
} else {
	$number = 'No validation code registered.';
	$width = 320;
	$height = 30;
}
$caractersSpace = 12;
if($number == '') {
	$phrase = 'error';
} else {
	if($_REQUEST['np'] != '') {
		$phrase = $number.'';
	} else {
		$phrase = 'Press the number '.$number;
	}
}
$captcha -> captcha_set($images, 5, $colors, $fonts, $fontsizes, $width, $height, $caractersSpace,$phrase);

?>