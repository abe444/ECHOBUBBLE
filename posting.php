<?php
session_start();
require 'inc/CONFIGURATION.php';
include 'inc/formatting.php';

// Poster must be posting.
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    die('<h1>Systems has detected an abnormal request method.</h1> <p>Turn back now.</p>');
}

// Word length controller
if (isset($CONFIGURATION['MAX_WORD_LENGTH'])) {
    if (contains_long_ass_word(sanitize($_POST['message']), $CONFIGURATION['MAX_WORD_LENGTH'])) {
        die("<h1>Some of your words are enormously long.</h1> <p>The max WORD LENGTH is ".$CONFIGURATION['MAX_WORD_LENGTH']." characters.</p><p>Please reformat your message.</p>");
    }
}

// Flood controller
if (isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 45)
    die('<h1>Slow down fren.</h1><p>You are posting too fast.</p><p>Hold your horses.</p><p>Please wait at least '. 45 - (time()-$_SESSION['last_submit']) .' seconds</p>');
else
$_SESSION['last_submit'] = time();

// Captcha controller
if (isset($_POST['captcha'])) {
    $userInput = strtolower(trim($_POST['captcha']));
    
    if ($userInput === $_SESSION['captcha_fruit']) {
        $userInput === $_SESSION['captcha_fruit'];
    } else {
        die("<h1>CAPTCHA failed.</h1> <p>Please try again.</p>");
    }
} else {
    die("<h1>CAPTCHA Failed.</h1> <p>Input is missing.</p>");
}

// Poster is limited to a fixed number of linebreaks
if($CONFIGURATION['MAX_LINE_BREAKS'] < count(explode("\n",$_POST['message']))){
    die('<h1>Too many linebreaks.</h1><p>Please reformat your message to include less linebreaks.</p>');
}

// Honeypot controller
if (htmlspecialchars(trim($_POST['love_snare'])) !== "57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF"){
    die ('<h1>Systems has detected an automated bot request.</h1><p>Words to dwell over: Fuck off.</p>');
}
if (!empty(htmlspecialchars(trim($_POST['email'])))){
    die ('<h1>Systems has detected an automated bot request.</h1> <p>Words to dwell over: Fuck off.</p>');
}
// Honeypot controller

$msg_content = sanitize($_POST['message']);

if(!isset($msg_content) || trim($msg_content) == ''){
    echo '<h1>You have left the message field blank. <h1><p>Try writing something aye....</p>';
}

if(strlen($msg_content) < $CONFIGURATION['MIN_MESSAGE_LENGTH']){
    die('<h1>Message length is too short!</h1><p>Add more detail!</p><p>Minimum character count: '.$CONFIGURATION['MIN_MESSAGE_LENGTH'].'</p>');
}

if($CONFIGURATION['MAX_MESSAGE_LENGTH'] < strlen($msg_content)){
    die('<h1>Message length is too long!</h1><p>Lose a few characters would yah!</p><p>Maximum character count: '.$CONFIGURATION['MAX_MESSAGE_LENGTH'].'</p>');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
// Retrieve and decode database data
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

$id = bin2hex(random_bytes(16));
$post_count = count($data) + 1;
$timestamp = time();
$bump_stamp = time();

$formatted_msg = markdown_to_html(($msg_content));

$entry = [
    'is_post' => true,
    'id' => $id,
    'bump_stamp' => $bump_stamp,
    'datetime' => $timestamp,
    'content' => $formatted_msg,
    'number' => $post_count,
    'replies' => []
];

    $data[] = $entry;
    $send_final = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('database.json', $send_final);
    }

    header("Location: index.php");

exit();



