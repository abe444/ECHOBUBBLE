<?php 
require 'CONFIGURATION.php';
include 'templates/header.php';
include 'templates/threadhead.php';

?>

<div class="replyingFORM">
<details open>
<summary class="threadTop">Replying to thread #<?php echo htmlspecialchars($_GET['num'])?></summary><br>
<form method="POST" id="POST" action="replying.php">
    <textarea spellcheck="true" required="" rows="10" cols="60" name="message" maxlength="3000" placeholder="Use >># formatting to reply to other THREADS."></textarea>
    <input type="hidden" name="love_snare" value="57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF" placeholder="love_snare">
    <input type="hidden" name="email" placeholder="email">
    <input type="hidden" name="id" value="<?php echo $_GET['num']; ?>" readonly="true">
    <br><input class="buttonPOST" type="submit" id="submit" name="submit" value="POST">
</form>
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
    echo "<summary class='threadTop'><strong>(Replying to thread #".$_GET['num'].") </strong></summary>";

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
