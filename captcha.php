<?php
session_start();

// Array of random fruits
$fruit_basket = ["W42bk7", "2t75WK", "v9cFtH", "Ko4cUW", "bEoM8V", "LbVKx3", "kjXCn5", "DmX4Ur", "gX5Amy", "2wUE5D"];
//$fruit_basket = ["W42BK7", "2T7ZWK", "V9CFTH", "KO4CUW", "BEOM7V", "LBVKX3", "KJXCN5", "DMX4UR", "GX5AMY", "2WUE5D"];
 
// Randomly select a fruit from the array
$pick_fruit = $fruit_basket[array_rand($fruit_basket)];

// Scramble the selected fruit
$scrambledFruit = str_shuffle($pick_fruit);

// Store the selected fruit in a session variable
$_SESSION['captcha_fruit'] = $scrambledFruit;

// Letter spacing
$displayFruit = implode('  ', str_split($scrambledFruit));

// Define image dimensions
$imageWidth = 180; // Increased width to accommodate larger text
$imageHeight = 40; // Increased height to accommodate larger text

// Create an image with GD library
$captchaImage = imagecreatetruecolor($imageWidth, $imageHeight);

// Allocate white background color
$bgColor = imagecolorallocate($captchaImage, 255, 255, 255);

// Allocate black text color
$textColor = imagecolorallocate($captchaImage, 0, 0, 0);

// Fill the image with white background
imagefilledrectangle($captchaImage, 0, 0, $imageWidth - 1, $imageHeight - 1, $bgColor);

// Calculate text position centered within the image
$textX = ($imageWidth - imagefontwidth(7) * strlen($displayFruit)) / 2; // Increased font size (7)
$textY = ($imageHeight - imagefontheight(7)) / 2; // Increased font size (7)

// Draw the scrambled fruit text on the image with a larger font
imagestring($captchaImage, 7, $textX, $textY, $displayFruit, $textColor); // Increased font size (7)

// Add random noise lines
for ($i = 0; $i < 7; $i++) {
    $lineColor = imagecolorallocate($captchaImage, rand(0, 255), rand(0, 255), rand(0, 255));
    imageline($captchaImage, rand(0, $imageWidth), rand(0, $imageHeight), rand(0, $imageWidth), rand(0, $imageHeight), $lineColor);
}

// Add random noise dots
for ($i = 0; $i < 150; $i++) {
    $dotColor = imagecolorallocate($captchaImage, rand(0, 255), rand(0, 255), rand(0, 255));
    imagesetpixel($captchaImage, rand(0, $imageWidth), rand(0, $imageHeight), $dotColor);
}

// Set the content type and output the image
header("Content-type: image/png");
imagepng($captchaImage);

// Clean up
imagedestroy($captchaImage);
session_write_close();
?>

