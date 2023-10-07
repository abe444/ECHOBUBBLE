<?php 
session_start();
require 'inc/CONFIGURATION.php';
include 'inc/formatting.php';

// Poster must be posting.
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: /index.php');
}

$msg_content = sanitize($_POST['message']);

// Message controller
if(!isset($msg_content) || trim($msg_content) == ''){
    echo '<h1>You have left the message field blank. </h1><p>Try writing something aye....</p>';
}

if(strlen($msg_content) < $CONFIGURATION['MIN_MESSAGE_LENGTH']){
    die('<h1>Message length is too short!</h1><p>Add more detail!</p><p>Minimum character count: '.$CONFIGURATION['MIN_MESSAGE_LENGTH'].'</p><img src="public/images/stills/gondolas/3.gif" alt="gondola">');
}

if($CONFIGURATION['MAX_MESSAGE_LENGTH'] < strlen($msg_content)){
    die('<h1>Message length is too long!</h1><p>Lose a few characters would yah!</p><p>Maximum character count: '.$CONFIGURATION['MAX_MESSAGE_LENGTH'].'</p>');
}
// Message controller

// Captcha and flood controller
if (!isset($_POST['captcha']) || $_POST['captcha'] !== $_SESSION['captcha_fruit']) {
    //$_SESSION['captcha_wait_until'] = time() + 25;
    die("<meta name=viewport' content='width=device-width, initial-scale=1.0' /><h1>Captcha FAILED.</h1> <p>Please try again.</p><img src='public/images/stills/gondolas/3.gif' alt='gondola' width='300' height=auto>");
    //die("<meta name=viewport' content='width=device-width, initial-scale=1.0' /><h1>Captcha FAILED.</h1> <p>Posting cooldown will now commence </p><p>Please try again.</p><img src='public/images/stills/gondolas/3.gif' alt='gondola' width='250' height=auto><h2><a href='/index.php'>Back</a></h2>");
    //die('<h1>Incorrect captcha.</h1><p>Try again.</p>');
}
/*
elseif (isset($_SESSION['captcha_wait_until']) && $_SESSION['captcha_wait_until'] > time()) {
    $waitTime = $_SESSION['captcha_wait_until'] - time();
    die("<meta name=viewport' content='width=device-width, initial-scale=1.0' /><h1>You are in cooldown mode.</h2><p>Please wait $waitTime seconds before trying again.</p><img src='public/images/stills/gondolas/2.png' alt='gondola' width='450' height=auto><h2><a href='/index.php'>Back</a></h2>");
    // header("Location: index.php");
    // exit;
}
*/

// Flood controller
if (isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 50){
    die('<h1>Slow down fren.</h1><p>You are posting too fast.</p><p>Hold your horses.</p><p>Please wait at least '. 50 - (time()-$_SESSION['last_submit']) .' seconds</p><img src="public/images/stills/gondolas/2.png" width="450" height=auto alt="gondola">');
}else{
    $_SESSION['last_submit'] = time();
}


$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

$id = sanitize($_POST['id']);
$post_id = bin2hex(random_bytes(16));
$timestamp = time();
$bump_stamp = time();

function reply_referencing(string $input): string {
    // Replace >> followed by numbers
    $input = preg_replace('/&gt;&gt;(\d+)/', '<a target="_self" href="/view.php?id=' . sanitize($_POST['id']) . '&r=$1"><span class="reply_ref">&gt;&gt;$1</span></a>', $input);
    
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



