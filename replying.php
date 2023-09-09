<?php 
session_start();
require 'inc/CONFIGURATION.php';
include 'inc/formatting.php';

// Poster must be posting.
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: /index.php');
}

// Flood controller
if (isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 10)
    die('<h1>Slow down fren.</h1><p>You are posting too fast.</p><p>Hold your horses.</p><p>Please wait at least '. 10 - (time()-$_SESSION['last_submit']) .' seconds</p><img src="public/images/stills/gondolas/2.png" alt="gondola">');
else
$_SESSION['last_submit'] = time();

// Captcha controller
if (isset($_POST['captcha'])) {
    $userInput = trim($_POST['captcha']);
    
    if ($userInput === $_SESSION['captcha_fruit']) {
        $userInput === $_SESSION['captcha_fruit'];
    } else {
        die("<h1>CAPTCHA failed.</h1> <p>Please try again.</p><img src='public/images/stills/gondolas/1.png' alt='gondola'>");
    }
} else {
    die("<h1>CAPTCHA Failed.</h1> <p>Input is missing.</p>");
}


// Honeypot controller
if (htmlspecialchars(trim($_POST['love_snare'])) !== "57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF"){
    die ('<h1>Systems has detected an automated bot request.</h1><p>Words to dwell over: Fuck off.</p>');
}
if (!empty(htmlspecialchars(trim($_POST['email'])))){
    die ('<h1>Systems has detected an automated bot request.</h1><p>Words to dwell over: Fuck off.</p>');
}
// Honeypot controller

$msg_content = htmlspecialchars(trim(
$_POST['message']), 
ENT_QUOTES, 
'UTF-8');

if(!isset($msg_content) || trim($msg_content) == ''){
    echo '<h1>You have left the message field blank. <h1><p> Try writing something aye....</p>';
}

if(strlen($msg_content) < $CONFIGURATION['MIN_MESSAGE_LENGTH']){
    die('<h1>Message length is too short!</h1><p>Add more detail!</p><p>Minimum character count: '.$CONFIGURATION['MIN_MESSAGE_LENGTH'].'</p><img src="public/images/stills/gondolas/3.gif" alt="gondola">');
}

if($CONFIGURATION['MAX_MESSAGE_LENGTH'] < strlen($msg_content)){
    die('<h1>Message length is too long!</h1><p>Lose a few characters would yah!</p><p>Maximum character count: '.$CONFIGURATION['MAX_MESSAGE_LENGTH'].'</p>');
}

$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

$id = htmlspecialchars(trim($_POST['id']));
$post_id = bin2hex(random_bytes(16));
$timestamp = time();
$bump_stamp = time();

function reply_referencing(string $input): string {
    // Replace >> followed by numbers
    $input = preg_replace('/&gt;&gt;(\d+)/', '<a target="_self" href="/view.php?id=' . htmlspecialchars(trim($_POST['id']), ENT_QUOTES, 'UTF-8') . '&r=$1"><span class="reply_ref">&gt;&gt;$1</span></a>', $input);
    
    // Replace >>OP
    $input = preg_replace('/&gt;&gt;OP/', '<span class="reply_ref"">&gt;&gt;OP</span>', $input);

    return $input;
}

$formatted_msg = markdown_to_html(reply_referencing($msg_content));

foreach($data as $key => $post){
if ($id == $post['id']){
    $data[$key]['bump_stamp'] = $bump_stamp;
    $reply_count = end($data[$key]['replies']);
    $entry = [
    'is_reply' => true,
    'id' => $post_id,
    'reply_to' => $id,
    'datetime' => $timestamp,
    'content' => $formatted_msg,
    'number' => $reply_count['number'] + 1,
];
    $data[$key]['replies'][] = $entry;
    header("Location: thread.php?id=" . $id . '#bottom');
    }
}

$send_final = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('database.json', $send_final);

session_write_close();
exit();


