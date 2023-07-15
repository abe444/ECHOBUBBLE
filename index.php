<?php
require 'inc/CONFIGURATION.php';
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

include 'inc/controller.php';

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo '<summary class="threadTop"><strong><nav>[Post] ~ <a href="listing.php" alt="thred listing">Thread</a></nav></strong></summary>';

    $threads_cutoff = array_splice($data, 0, $CONFIGURATION['POSTS_DISPLAYED']);
    foreach ($threads_cutoff as $key => $post) {
        $post_id = $post['id'];
        $post_date = date('Y-m-d g:i e', $post['datetime']);
        $post_content = $post['content'];
        echo '<div class="thread">';
        echo '<details open>';
        echo    '<summary class="threadTop"><strong><a target="_self" href="thread.php?id='.$post_id.'">Reply</a></strong> ' . $post_date . ' </summary>';
        echo        '<p class="threadContent">' . $post_content . '</p>';
        echo    '</details>';
        echo '</div>';
        if (count($post['replies']) >= 2){
            echo '<p class="threadContent"><strong><span class="glow">Latest bumps: </span></strong></p>';
        }

    if ($reply_count >= 2) {
        array_splice($post['replies'], 0, -2);
        foreach ($post['replies'] as $key => $reply){
            $reply_id = $reply['id'];
            $reply_num = $reply['number'];
            $reply_date = date('Y-m-d g:i e', $reply['datetime']);
            $reply_content = $reply['content'];
        echo '<div class="reply">';
        echo '<details open>';
        echo    '<summary class="threadTop">#'.$reply_num.' '. $reply_date . '</summary>';
        echo        '<p class="threadContent">' . $reply_content . '</p>';
        echo '</div>';
            }
        }
    }
    echo '</details>';
    echo '</div>';
} else {
    echo '<center><span class="redtext">No posts YET. </span></center>';
}

?>
