<?php 
include 'templates/header.php';
require 'CONFIGURATION.php';
?>
<div class="threadAbout" style="text-align: center;padding:20px;">
    <img src="../public/images/stills/bravo.png" height="190" width="auto">
    <p>[XMR]<span class="greentext">
            47kHnKPhe7Fbu7UpjUv4mkW88z7QWdnd7iQdAeirDAMuT5Xe1AvcHAKjn5V3Ndd8M932NPevBxyym9nekFJiXKcz3QbCz8o
        </span>
        <details>
            <summary>About</summary>
            <p><?php echo $CONFIGURATION['SITE_DESCRIPTION']?></p>
        </details>
</div>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Fetch
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

if (isset($_GET['num'])) {
    $bumped_thread = [];
    foreach ($data as $entry) {
        if ($entry['number'] == $_GET['num']) {
            $bumped_thread[] = $entry;
        }
    }
    $data = $bumped_thread;
}

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo '<details open>';
    echo "<summary class='threadTop'><strong>(Viewing thread #".$_GET['num'].") </strong></summary>";

    foreach ($data as $key => $post) {
        $post_num = $post['number'];
        $post_date = $post['datetime'];
        $post_content = $post['content'];

        echo '<div class="thread">';
        echo '<details open>';
        echo    '<summary class="threadTop"><strong>[OP]</strong> Thread #'.$post_num.' '. $post_date . '</summary>';
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
} 

include 'templates/footer.html';
?>
