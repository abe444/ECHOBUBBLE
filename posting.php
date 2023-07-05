<?php
date_default_timezone_set('UTC');
include 'templates/header.php';
include 'templates/about.php';
include 'CONFIGURATION.php';
include 'formatting.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (htmlspecialchars(trim($_POST['love_snare'])) !== "57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF"){
    die ('<span class="redtext">Systems has detected an automated bot request. Words to dwell over: Fuck off.</span>');
}

if (!empty(htmlspecialchars(trim($_POST['email'])))){
    die ('<span class="redtext">Systems has detected an automated bot request. Words to dwell over: Fuck off.</span>');
}

// Retrieve and decode database data
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
// Retrieve and decode database data
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

$post_count = count($data) + 1;
$timestamp = time();
$bump_stamp = time();

$msg_content = htmlspecialchars(trim(
$_POST['message']), 
ENT_QUOTES, 
'UTF-8');

if(!isset($msg_content) || trim($msg_content) == ''){
    echo '<h3>You have left the message field blank. <br><br> Try writing something aye....</h3>';
}

if(strlen($msg_content) < $CONFIGURATION['MIN_MESSAGE_LENGTH']){
    die('<center><div style="padding: 10px;" class="collapsePost"><span class="redtext">Message length is too short! Add more detail!</span><br><img height=400 width="auto" src="public/images/stills/soma_angel2.png"><br><span class="redtext">Minimum character count: 5</span></center>');
}

if($CONFIGURATION['MAX_MESSAGE_LENGTH'] < strlen($msg_content)){
    die('<center><div style="padding: 10px;" class="collapsePost"><span class="redtext">Message length is too long! Lose a few characters would yah!</span><br><img height=400 width="auto" src="public/images/stills/soma_angel2.png"><span class="redtext">Maximum character count: 5000</span></center>');
}

$formatted_msg = markdown_to_html(post_referencer($msg_content));

// Blacklisting glowie posts
if (preg_match('/mega/i', $formatted_msg)){
$formatted_msg = preg_replace('/loli/i', '<span class="redtext">[MESSAGE DELETED] [GLOWTARDS BTFO\'ED]</span>', $formatted_msg);
$formatted_msg = preg_replace('/mega/i', '<span class="redtext">[MESSAGE DELETED] [GLOWIE BTFO\'ED]</span>', $formatted_msg);
}

$entry = [
    'is_post' => true,
    'bump_stamp' => $bump_stamp,
    'datetime' => $timestamp,
    //'datetime' => date('Y/m/d g:i e'),
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



