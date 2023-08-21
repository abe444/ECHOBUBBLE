<?php 

include 'inc/CONFIGURATION.php';
include 'templates/header.php';
include 'templates/archivesheading.php';

$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

include 'inc/controller.php';
echo '<div class="collapsePost">';
echo '<summary class="threadTop"><strong><nav><a href="index.php" alt="index">Posts: '.$total_entries.'</a> ~ [Archives]</nav></strong></summary>';
$directory = 'archives/';

if (is_dir($directory)) {
    $contents = scandir($directory);

    $contents = array_diff($contents, array('.', '..'));

if (empty($contents)){ 
    echo "<p class='threadContent'><strong>No archives as of yet.</strong></p>";
}else{
    // Output the list of contents
    foreach (array_reverse($contents) as $key => $item) {
        echo '<p class="threadContent"><strong><a href="archives/'.$item.'" alt="archive page">' . $item . '</a></strong></p>' . PHP_EOL;
        }
    }
}

echo '</div>';
    
include 'templates/webring.php';
?>
