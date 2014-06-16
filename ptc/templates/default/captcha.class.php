<?php
class captchaClass {

    var $image_info = null;

    function random_key($length) {
        $chars = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for ($i = 0; $i < $length; $i++) {
            $keys .= $chars[rand(0, strlen($chars)-1)];
        }
        return $keys;
    }

    function random_background($images) {
        if ($images) {
            $select_image = $images[rand(0, count($images) - 1)];
        }
        return $select_image;
    }

    function random_color($colors) {
        if ($colors) {
            $select_color = $colors[rand(0, count($colors) - 1)];
        }
        return $select_color;
    }

    function random_font($fonts) {
        if ($fonts) {
            $select_font = $fonts[rand(0, count($fonts) - 1)];
        }
        return $select_font;
    }

    function random_fontsizes($fontsizes) {
        if ($fontsizes) {
            $select_fontsize = $fontsizes[rand(0, count($fontsizes) - 1)];
        }
        return $select_fontsize;
    }

    function captcha_set($images, $length, $colors, $fonts, $fontsizes, $width, $height, $space,$key='random') {
        
		if($key == 'random') $key = $this -> random_key($length);
        unset($_SESSION['captcha_key']);
        $_SESSION['captcha_key'] = strtolower($key);

        $select_image = $this -> random_background($images);
        $this -> image_info = getImageSize($select_image);

        if ($this -> image_info['mime'] == 'image/jpeg' || $this -> image_info['mime'] == 'image/jpg') {
            $old_image = imageCreateFromJPEG($select_image);
        }elseif ($this -> image_info['mime'] == 'image/gif') {
            $old_image = imageCreateFromGIF($select_image);
        }elseif ($this -> image_info['mime'] == 'image/png') {
            $old_image = imageCreateFromPNG($select_image);
        }

        if (empty($width)) { $width = $this -> image_info[0]; }
        if (empty($height)) { $height = $this -> image_info[0]; }

        $new_image = imageCreateTrueColor($width, $height);
        $bg = imagecolorallocate($new_image, 255, 255, 255);
        imagefill($new_image, 0, 0, $bg);

        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $width, $height, $this -> image_info[0], $this -> image_info[1]);

        $bg = imagecolorallocate($new_image, 255, 255, 255);
        for ($i = 0; $i < strlen($key); $i++) {
            $color_cols = explode(',', $this -> random_color($colors));
            $fg = imagecolorallocate($new_image, trim($color_cols[0]), trim($color_cols[1]), trim($color_cols[2]));
			imagettftext($new_image, $this -> random_fontsizes($fontsizes), rand(-4, 4), 11 + ($i * $space), rand($height - 10, $height - 8), $fg, $this -> random_font($fonts), $key[$i]);
        }

        if ($this -> image_info['mime'] == 'image/jpeg') {
            header("Content-type: image/jpeg");
            header('Content-Disposition: inline; filename=captcha.jpg');
            imageJPEG($new_image);
        }elseif ($this -> image_info['mime'] == 'image/gif') {
            header("Content-type: image/gif");
            header('Content-Disposition: inline; filename=captcha.gif');
            imageGIF($new_image);
        }elseif ($this -> image_info['mime'] == 'image/png') {
            header("Content-type: image/png");
            header('Content-Disposition: inline; filename=captcha.png');
            imagePNG($new_image);
        }

        imageDestroy($old_image);
        imageDestroy($new_image);
    }

}
?>