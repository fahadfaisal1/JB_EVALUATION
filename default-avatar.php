<?php
// Set the content type to image/png
header('Content-Type: image/png');

// Create a 150x150 image
$image = imagecreatetruecolor(150, 150);

// Colors
$bg = imagecolorallocate($image, 236, 240, 241); // Light gray background
$text_color = imagecolorallocate($image, 52, 73, 94); // Dark blue text

// Fill background
imagefilledrectangle($image, 0, 0, 150, 150, $bg);

// Add text
$text = isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : '?';
imagettftext($image, 60, 0, 55, 100, $text_color, 'C:\Windows\Fonts\arial.ttf', $text);

// Output image
imagepng($image);
imagedestroy($image);
?> 