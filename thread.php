<?php 
require 'inc/CONFIGURATION.php';
include 'templates/header.php';
include 'templates/threadhead.php';
?>

<div class="replyingFORM">
<details open>
<summary class="threadTop">[Mode]: Replying</summary><br>
<form method="POST" id="POST" action="replying.php">
    <textarea spellcheck="true" required="" rows="10" cols="60" name="message" maxlength="3000" placeholder="2500 character limit."></textarea>
    <input type="hidden" name="love_snare" value="57yx42HUTnWgkxKW2puHngtUjX24twWj2ifYF" placeholder="love_snare">
    <input type="hidden" name="email" placeholder="email">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars(trim($_GET['id'])); ?>" readonly="true">
    <br><input class="buttonPOST" type="submit" id="last_submit" name="last_submit" value="POST">
</form>
</details>
</div>


<?php
// Fetch
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

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

if (!empty($data)) {

    echo '<div class="collapsePost">';
    echo '<details open>';
    echo "<summary class='threadTop'><strong>ID: ".htmlspecialchars(trim($_GET['id']))." </strong></summary>";

    foreach ($data as $key => $post) {
        $post_num = $post['number'];
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
    echo '</details>';
    echo '</div>';
} 

include 'templates/footer.php';
?>
