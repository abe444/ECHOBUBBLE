<?php 
require 'inc/CONFIGURATION.php';

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
echo '<a style="font-weight:bold;text-shadow: 1px 1px 10px black;" href="#bottom">Bottom</a>';
echo '</center>';

if (!empty($data)) {


    foreach ($data as $key => $post) {
        $post_date = date('Y-m-d g:i e', $post['datetime']);
        $post_title = $post['title'];
        $post_content = $post['content'];
        $last_bumped = time() - $post['bump_stamp'];

        if ($last_bumped < 60) {
            $timeLabel = 'last bumped ' . $last_bumped . ' seconds ago';
        }elseif ($last_bumped < 3600) {
            $timeLabel = 'last bumped ' . floor($last_bumped / 60) . ' minutes ago';
        } else {
            $timeLabel = 'last bumped ' . floor($last_bumped / 3600) . ' hours ago';
        }

        if ($last_bumped >= 172800) {
            $daysPassed = floor($last_bumped / 86400);
            $timeLabel = 'last bumped ' . $daysPassed . ' days ago';
        }

        echo '<div class="thread">';
        echo    '<p class="threadTop"><span style="color:#3FFF00;font-weight:bold;">OP</span> on '. $post_date . ' <span style="color:#fffb00;font-weight:bold;">' . $timeLabel. '</span></p>';
        //echo    '<p class="threadTop"><span style="color:#3FFF00;font-weight:bold;">OP</span> on '. $post_date . '</p>';
        echo        '<h2 class="threadContent">' . $post_title . '</h2>';
        echo        '<p class="threadContent">' . $post_content . '</p>';
        //echo    '<p><span style="color:#fffb00;font-weight:bold;">' . $timeLabel. '</span></p>';
        echo '</div>';

        foreach ($post['replies'] as $key => $reply){
        $reply_num = $reply['number'];
        $reply_date = date('Y/m/d g:i e', $reply['datetime']);
        $reply_content = $reply['content'];
        echo '<div class="reply">';
        echo    '<p class="threadTop"><a style="color:#88ffe9;font-weight:bold;"href="view.php?id='.$_GET['id'].'&r='.$reply['number'].'" alt="comment">#'.$reply_num.'</a> '. $reply_date . ' </p>';
        echo        '<p class="threadContent">' . $reply_content . '</p>';
        echo '</div>';
        }
    }
} 

include 'templates/reply_form.php';
include 'templates/footer.php';

echo "<center style='text-shadow: 1px 1px 10px black;'><strong>ID: ".htmlspecialchars(trim($_GET['id']))." </strong></center>";
?>
