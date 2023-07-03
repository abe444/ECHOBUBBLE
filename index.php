<?php
date_default_timezone_set('UTC');
require 'CONFIGURATION.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$limit = array_slice($data, 0, $CONFIGURATION['POST_LIMIT']);

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo '<details open>';
    echo '<summary class="threadTop"><strong>Post </strong></summary>';

    foreach ($data as $key => $post) {
        $post_num = $post['number'];
        $post_date = $post['datetime'];
        $post_content = $post['content'];

        echo '<div class="thread">';
        echo '<details open>';
        echo    '<summary class="threadTop"><strong>[OP]</strong> Post #'.$post_num.' '. $post_date . ' <a style="color:black;font-weight:bold;" target="_self" href="reply_form.php?num='.$post_num.'">Reply</a></summary>';
        echo        '<p class="threadContent">' . $post_content . '</p>';
        echo    '</details>';
        echo '</div>';

        foreach ($post['replies'] as $key => $reply){
        echo '<div class="reply">';
        echo '<details open>';
        echo    '<summary class="threadTop">Reply #'.$reply['number'].' '. $reply['datetime'] . ' </summary>';
        echo        '<p class="threadContent">' . $reply['content'] . '</p>';
        echo    '</details>';
        echo '</div>';
        }
    }
    echo '</details>';
    echo '</div>';
} else {
    echo '<center><div style="padding: 10px;" class="collapsePost"><span class="redtext">The site\'s storage has been wiped. Now is your chance to start a new generation.</span><br><img src="public/images/stills/regeneration.png"></span></center>';
}

include 'templates/footer.html';
?>
