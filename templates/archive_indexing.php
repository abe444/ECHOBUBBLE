<?php
include '../../templates/header.php';
include '../../templates/archivesheading.php';

$archive = '';

if($archive === ''){
    echo '<div class="collapsePost">';
    echo '<p class="threadContent"><b>Archive is currently being processed. Please standby.</b></p>';
    echo '</div>';
    exit();
}

// Fetch
$fetch_data = file_get_contents($archive, true);
$data = json_decode($fetch_data, true);

// Sort threads by most recently bumped
usort($data, function($a, $b){
    return $b['bump_stamp'] <=> $a['bump_stamp'];
});

include '../../inc/controller.php';

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo '<summary class="threadTop"><strong>'.$archive.'</strong></summary>';

    $threads_cutoff = array_splice($data, 0, $CONFIGURATION['POSTS_DISPLAYED']);
    foreach ($threads_cutoff as $key => $post) {
        $post_id = $post['id'];
        $post_num = $post['number'];
        $post_date = date('Y-m-d g:i e', $post['datetime']);
        $post_content = $post['content'];
        echo '<div class="thread">';
        echo '<details open>';
        echo    '<summary class="threadTop"><strong>Post </strong> #'.$post_num.' '. $post_date . ' </summary>';
        echo        '<p class="threadContent">' . $post_content . '</p>';
        echo    '</details>';
        echo '</div>';

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
    echo '</details>';
    echo '</div>';
    exit();
}
