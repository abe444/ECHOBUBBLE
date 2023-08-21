<?php
session_start();

// Array of random fruits
$fruit_basket = ["apple", "banana", "cherry", "grape", "kiwi", "orange", "pear", "pineapple", "strawberry", "watermelon"];

// Randomly select a fruit from the array
$pick_fruit = $fruit_basket[array_rand($fruit_basket)];

// Scramble the selected fruit
$scrambledFruit = str_shuffle($pick_fruit);

// Store the selected fruit in a session variable
$_SESSION['captcha_fruit'] = $pick_fruit;

// Define image dimensions
$imageWidth = 170;
$imageHeight = 40;

// Create an image with GD library
$captchaImage = imagecreatetruecolor($imageWidth, $imageHeight);

// Allocate white background color
$bgColor = imagecolorallocate($captchaImage, 255, 255, 255);

// Allocate black text color
$textColor = imagecolorallocate($captchaImage, 0, 0, 0);

// Fill the image with white background
imagefilledrectangle($captchaImage, 0, 0, $imageWidth - 1, $imageHeight - 1, $bgColor);

// Calculate text position centered within the image
$textX = ($imageWidth - imagefontwidth(5) * strlen($scrambledFruit)) / 2;
$textY = ($imageHeight - imagefontheight(5)) / 2;

// Draw the scrambled fruit text on the image
imagestring($captchaImage, 5, $textX, $textY, $scrambledFruit, $textColor);

// Set the content type and output the image
header("Content-type: image/png");
imagepng($captchaImage);

// Clean up
imagedestroy($captchaImage);
?>
