<?php
require 'inc/CONFIGURATION.php';
include 'templates/header.php';
include 'templates/threadhead.php';

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

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo "<summary class='threadTop'><strong>ID: ".htmlspecialchars(trim($_GET['id']))." </strong></summary>";
    echo '<br>';

    foreach ($data as $key => $post) {
        foreach ($post['replies'] as $key => $reply){
        $reply_num = $reply['number'];
        $reply_date = date('Y/m/d g:i e', $reply['datetime']);
        $reply_content = $reply['content'];
        if ($reply_num == $_GET['r']){
        echo '<div class="reply">';
        echo '<details open>';
        echo    '<summary class="threadTop">#'.$reply_num.' '. $reply_date . ' </summary>';
        echo        '<p class="threadContent">' . $reply_content . '</p>';
        echo    '</details>';
        echo '</div>';
            }elseif(!isset($_GET['r'])){
                header("HTTP/1.0 404 Not Found");
            }
        }
    echo '</div>';
    } 
}

include 'templates/footer.php';
