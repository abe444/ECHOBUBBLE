<?php 
require 'inc/CONFIGURATION.php';
include 'templates/header.php';
include 'templates/threadhead.php';
include 'templates/reply_form.php';

// Fetch
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

include 'inc/controller.php';

if (isset($_GET['id'])) {
    $bumped_thread = [];
    foreach ($data as $entry) {
        if ($entry['id'] == htmlspecialchars(trim($_GET['id']))) {
            $bumped_thread[] = $entry;
        }
    }
    $data = $bumped_thread;
} elseif (!isset($_GET['id'])){
    header("Location: /index.php");
    exit;
}

echo '<center>';
echo '<a style="font-weight:bold;" href="#bottom">Bottom</a>';
echo '</center>';
echo '<hr>';

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo "<summary class='threadTop'><strong>ID: ".htmlspecialchars(trim($_GET['id']))." </strong></summary>";

    foreach ($data as $key => $post) {
        $post_date = date('Y-m-d g:i e', $post['datetime']);
        $post_content = $post['content'];

        echo '<div class="thread">';
        echo '<details open>';
        echo    '<summary class="threadTop">Post on '. $post_date . '</summary>';
        echo        '<p class="threadContent">' . $post_content . '</p>';
        echo    '</details>';
        echo '</div>';

        foreach ($post['replies'] as $key => $reply){
        $reply_num = $reply['number'];
        $reply_date = date('Y/m/d g:i e', $reply['datetime']);
        $reply_content = $reply['content'];
        echo '<div class="reply">';
        echo '<details open>';
        echo    '<summary class="threadTop">#'.$reply_num.' '. $reply_date . ' </summary>';
        echo        '<p class="threadContent">' . $reply_content . '</p>';
        echo    '</details>';
        echo '</div>';
        }
    }
    echo '</div>';
} 

include 'templates/footer.php';

if ($total_entries >= $CONFIGURATION['POST_LIMIT']){
    header('Location: templates/text.php');
//    header("Location: index.php");
}

?>
