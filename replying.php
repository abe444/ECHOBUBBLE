<?php 
include 'CONFIGURATION.php';
include 'formatting.php';

if (htmlspecialchars(trim($_POST['love_snare'])) !== "57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF"){
    die ('<span class="redtext">Systems has detected an automated bot request. Words to dwell over: Fuck off.</span>');
}

if (!empty(htmlspecialchars(trim($_POST['email'])))){
    die ('<span class="redtext">Systems has detected an automated bot request. Words to dwell over: Fuck off.</span>');
}

$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

$id = htmlspecialchars(trim($_POST['id']));
$timestamp = time();
$bump_stamp = time();

$msg_content = htmlspecialchars(trim(
$_POST['message']), 
ENT_QUOTES, 
'UTF-8');

$formatted_msg = markdown_to_html(post_referencer($msg_content));

if(!isset($msg_content) || trim($msg_content) == ''){
    echo '<h3>You have left the message field blank. <br><br> Try writing something aye....</h3>';
}

if(strlen($msg_content) < $CONFIGURATION['MIN_MESSAGE_LENGTH']){
    die('<center><div style="padding: 10px;" class="collapsePost"><span class="redtext">Message length is too short! Add more detail!</span><br><img height=400 width="auto" src="public/images/stills/terry.gif"><br><span class="redtext">Minimum character count: 5</span></center>');
}

if($CONFIGURATION['MAX_MESSAGE_LENGTH'] < strlen($msg_content)){
    die('<center><div style="padding: 10px;" class="collapsePost"><span class="redtext">Message length is too long! Lose a few characters would yah!</span><br><img height=400 width="auto" src="public/images/stills/terry.gif"><span class="redtext">Maximum character count: 5000</span></center>');
}

if (!is_numeric($id)){
    header('Location: index.php');
}

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



