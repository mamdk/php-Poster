<?php
session_start();
$str = "abcdefghijklmnopqrstuvwxyz0123456789";
$captcha = "";
$option = ["w" => 200, "h" => 60, "len_str" => rand(5, 7)];
$color = [];
$img = imagecreate($option["w"], $option["h"]);

for ($i = 0; $i < $option["len_str"]; $i++) {
    $captcha .= substr($str, rand(0, strlen($str)), 1);
}



$r = rand(125, 185);
$g = rand(125, 185);
$b = rand(125, 185);

for ($i = 0; $i < 4; $i++) {
    $color[$i] = imagecolorallocate($img, $r - ($i * 30), $g - ($i * 30), $b - ($i * 30));
}

imagefill($img, 0, 0, $color[0]);

for ($i = 0; $i < 4; $i++) {
    imagesetthickness($img, rand(2, 8));
    $rect_color = $color[rand(1, 3)];
    imagerectangle($img, rand(-5, 5), rand(-5, 5), rand(100, $option["w"] + 5), rand(50, $option["h"] + 5), $rect_color);
    imagesetthickness($img, rand(1, 5));
    imagefilledarc($img, rand(10, $option["w"] - 5), rand(10, $option["h"] - 5), rand(1, 10), rand(1, 10), 0, 360, $color[rand(1, 3)], IMG_ARC_PIE);
}

$text_color = [imagecolorallocate($img, 0, 0, 0), imagecolorallocate($img, 200, 200, 200)];
$fonts = [dirname(__FILE__) . '\font\Ubuntu-Bold.ttf', dirname(__FILE__) . '\font\Ubuntu-BoldItalic.ttf', dirname(__FILE__) . '\font\Ubuntu-Italic.ttf', dirname(__FILE__) . '\font\Ubuntu-Medium.ttf', dirname(__FILE__) . '\font\Ubuntu-Regular.ttf'];

$_SESSION["captcha"] = $captcha;

for ($i = 0; $i < $option["len_str"]; $i++) {
    $x = round($i * ($option["w"] / $option["len_str"])) + 5;
    $y = round($option["h"] / 2) + rand(-5, 15);
    imagettftext($img, 25, rand(-10, 15), $x, $y, $text_color[rand(0, 1)], $fonts[array_rand($fonts)], substr($captcha, $i, 1));
}

header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
