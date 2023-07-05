<?php
require 'CONFIGURATION.php';
include 'templates/header.php';
include 'templates/about.php';
include 'templates/form.php';

// Fetch
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

// Sort threads by most recently bumped
usort($data, function($a, $b){
    return $b['bump_stamp'] <=> $a['bump_stamp'];
});

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo '<details open>';
    echo '<summary class="threadTop"><strong>Post </strong></summary>';

    foreach ($data as $key => $post) {
        $post_num = $post['number'];
        $post_date = date('Y/m/d g:i e', $post['datetime']);
        $post_content = $post['content'];

        echo '<div class="thread">';
        echo '<details open>';
        echo    '<summary class="threadTop"><strong><a style="color:black;font-weight:bold;" target="_self" href="reply_form.php?num='.$post_num.'">Reply</a></strong> Post #'.$post_num.' '. $post_date . ' </summary>';
        echo        '<p class="threadContent">' . $post_content . '</p>';
        echo    '</details>';
        echo '</div>';

        foreach ($post['replies'] as $key => $reply){
            $reply_num = $reply['number'];
            $reply_date = date('Y/m/d g:i e', $reply['datetime']);
            $reply_content = $reply['content'];
        echo '<div class="reply">';
        echo '<details open>';
        echo    '<summary class="threadTop">Reply #'.$reply_num.' '. $reply_date . ' </summary>';
        echo        '<p class="threadContent">' . $reply_content . '</p>';
        echo    '</details>';
        echo '</div>';
        }
    }
    echo '</details>';
    echo '</div>';
} else {
    echo '<center><div style="padding: 10px;" class="collapsePost"><span class="redtext">LIMIT REACHED. DATABASE FILE HAS BEEN WIPED. </span><br><img src="public/images/stills/regeneration.png"></span></center>';
}

include 'controller.php';
include 'templates/footer.php';
?>
