<?php 
session_start();
require 'CONFIGURATION.php';
include 'formatting.php';

if (isset($CONFIGURATION['MAX_WORD_LENGTH'])) {
    if (contains_long_ass_word($_POST['message'], $CONFIGURATION['MAX_WORD_LENGTH'])) {
        die("<h1>Some of your words are enormously long.</h1> <p>The max WORD LENGTH is ".$CONFIGURATION['MAX_WORD_LENGTH']." characters.</p><p>Please reformat your message.</p>");
    }
}

if (isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 60)
    die('<h1>Slow down fren.</h1><p>You are posting too fast.</p><p>Hold your horses.<p><p>Please wait at least '. 60 - (time()-$_SESSION['last_submit']) .' seconds</p>');
else
$_SESSION['last_submit'] = time();

if($CONFIGURATION['MAX_LINE_BREAKS'] < count(explode("\n",$_POST['message']))){
    die('<h1>Too many linebreaks.</h1><p>Please reformat your message to include less linebreaks.</p>');
}

if (htmlspecialchars(trim($_POST['love_snare'])) !== "57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF"){
    die ('<h1>Systems has detected an automated bot request.</h1><p>Words to dwell over: Fuck off.</p>');
}

if (!empty(htmlspecialchars(trim($_POST['email'])))){
    die ('<h1>Systems has detected an automated bot request.</h1><p>Words to dwell over: Fuck off.</p>');
}

$msg_content = htmlspecialchars(trim(
$_POST['message']), 
ENT_QUOTES, 
'UTF-8');

if(!isset($msg_content) || trim($msg_content) == ''){
    echo '<h1>You have left the message field blank. <h1><p> Try writing something aye....</p>';
}

if(strlen($msg_content) < $CONFIGURATION['MIN_MESSAGE_LENGTH']){
    die('<h1>Message length is too short!</h1><p>Add more detail!</p><p>Minimum character count: '.$CONFIGURATION['MIN_MESSAGE_LENGTH'].'</p>');
}

if($CONFIGURATION['MAX_MESSAGE_LENGTH'] < strlen($msg_content)){
    die('<h1>Message length is too long!</h1><p>Lose a few characters would yah!</p><p>Maximum character count: '.$CONFIGURATION['MAX_MESSAGE_LENGTH'].'</p>');
}

if (!is_numeric($id)){
    header('Location: index.php');
}

$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

$id = htmlspecialchars(trim($_POST['id']));
$timestamp = time();
$bump_stamp = time();

$formatted_msg = markdown_to_html(post_referencer($msg_content));

foreach($data as $key => $post){
if ($id == $post['number']){
    $data[$key]['bump_stamp'] = $bump_stamp;
    $reply_count = end($data[$key]['replies']);
    $entry = [
    'is_reply' => true,
    'reply_to' => $id,
    'datetime' => $timestamp,
    //'datetime' => date('Y/m/d g:i e'),
    'content' => $formatted_msg,
    'number' => $reply_count['number'] + 1,
];
    $data[$key]['replies'][] = $entry;

}else{
    header('Location: /index.php');
}

}

$send_final = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('database.json', $send_final);

header("Location: /index.php");
exit();



