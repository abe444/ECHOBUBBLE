<?php 
session_start();
require 'inc/CONFIGURATION.php';
include 'inc/formatting.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    die('<h1>Systems has detected an abnormal request method.</h1> <p>Turn back now.</p>');
}

if (isset($CONFIGURATION['MAX_WORD_LENGTH'])) {
    if (contains_long_ass_word($_POST['message'], $CONFIGURATION['MAX_WORD_LENGTH'])) {
        die("<h1>Some of your words are enormously long.</h1> <p>The max WORD LENGTH is ".$CONFIGURATION['MAX_WORD_LENGTH']." characters.</p><p>Please reformat your message.</p>");
    }
}

if (isset($_SESSION['last_submit']) && time()-$_SESSION['last_submit'] < 75)
    die('<h1>Slow down fren.</h1><p>You are posting too fast.</p><p>Hold your horses.<p><p>Please wait at least '. 75 - (time()-$_SESSION['last_submit']) .' seconds</p>');
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

$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

$id = htmlspecialchars(trim($_POST['id']));
$post_id = bin2hex(random_bytes(16));
$timestamp = time();
$bump_stamp = time();

function reply_referencing(string $input): string{
    return $input = preg_replace('/&gt;&gt;(\d+)/', "<a class=\"reply_ref\" target=\"_self\" style=\"color: #dfff00;\" href=\"/view.php?id=".htmlspecialchars(trim($_POST['id']), ENT_QUOTES, 'UTF-8')."&r=$1\">&gt;&gt;$1</a>", $input);
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
    header("Location: thread.php?id=" . $id);
    }else{
        header('Location: /index.php');
    }
}

$send_final = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('database.json', $send_final);

exit();



