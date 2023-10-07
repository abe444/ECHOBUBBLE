<?php
require 'inc/CONFIGURATION.php';

// Fetch
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

if (isset($_GET['r'])) {
    $bumped_thread = [];
    foreach ($data as $entry) {
        if ($entry['id'] == htmlspecialchars(trim($_GET['id']))) {
            $bumped_thread[] = $entry;
        }
    }
    $data = $bumped_thread;
} elseif (!isset($_GET['r'])){
    header("Location: /index.php");
    exit;
}

foreach($data as $title){
    if (isset($title['title'])){
        $title_desc = $title;
        break;
    }
}

if (!isset($title_desc)){
    header("Location: /index.php");
}

$bubble_titler = htmlspecialchars_decode(str_replace(["\r\n", "\n", "\r"], ' ', $title_desc['title']));
if (strlen($bubble_titler) > 50) {
    $substring = substr($bubble_titler, 0, 50);
    $header_desc = htmlspecialchars($substring . "...", ENT_QUOTES, 'UTF-8');
} else {
    $header_desc = htmlspecialchars($bubble_titler, ENT_QUOTES, 'UTF-8');
}

$meta_description = $bubble_titler;

include 'templates/header.php';
include 'templates/threadhead.php';

echo '<center>';
echo '<p><a style="font-weight:bold;text-shadow: 1px 1px 2px black;" href="#bottom">Bottom</a></p>';
echo '</center>';
//echo '<p class="threadContent" style="font-weight: bold;margin-left: 2.5em;text-shadow: 1px 1px 2px black;">Replying to reply # '. $_GET['r'] . '</p>';

if (!empty($data)) {

    foreach ($data as $key => $post) {
        foreach ($post['replies'] as $key => $reply){
        $reply_num = $reply['number'];
        $reply_date = date('Y/m/d g:i e', $reply['datetime']);
        $reply_content = $reply['content'];
        if ($reply_num == $_GET['r']){
        $da_link_in_question = 'view.php?id='.$_GET['id'].'&r='.$_GET['r'].''; 
        echo '<div class="reply">';
        echo    '<p class="threadTop"><a style="font-weight:bold;color:#88ffe9;" rel="nofollow" onclick="reply_2_reply('.$_GET['r'].'); return false;" href="'.$da_link_in_question.'">#'.$reply_num.'</a> '. $reply_date . ' </p>';
        echo        '<p class="threadContent">' . $reply_content . '</p>';
        echo '</div>';
            }elseif(!isset($_GET['r'])){
                header("HTTP/1.0 404 Not Found");
            }
        }
    } 
}
include 'templates/reply_form.php';
echo '<hr>';
echo '<p class="threadContent" style="font-weight:bold;text-shadow: 1px 1px 10px black;"><a href="/thread.php?id='.$_GET['id'].'" alt="back">Back</a></p>';
echo "<center id='bottom' style='text-shadow: 1px 1px 10px black;'><strong>ID: ".htmlspecialchars(trim($_GET['id']))." </strong></center>";
echo '<script src="js/main.js"></script>';
exit();

