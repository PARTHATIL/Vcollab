<?php
session_start();

$letter = strtoupper(substr($_GET['username'] ?? 'U', 0, 1));

$size = 100;
$image = imagecreatetruecolor($size, $size);

// Background color
$bg_color = imagecolorallocate($image, 0, 123, 255); // Blue background
$text_color = imagecolorallocate($image, 255, 255, 255); // White text

// Fill the background
imagefill($image, 0, 0, $bg_color);

// Set font properties
$font = __DIR__ . '/arial.ttf'; // Use a TTF font in your directory
$font_size = 40;
$bbox = imagettfbbox($font_size, 0, $font, $letter);
$x = ($size - ($bbox[2] - $bbox[0])) / 2;
$y = ($size - ($bbox[7] - $bbox[1])) / 2 + 10;

// Add text
imagettftext($image, $font_size, 0, $x, $y, $text_color, $font, $letter);

// Output image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
