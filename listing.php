<?php 
require 'inc/CONFIGURATION.php';
include 'templates/header.php';
include 'templates/about.php';

// Fetch
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);


?>

<div class="collapsePost">
    <summary class="threadTop"><strong><nav><a href="index.php" alt="home page">Posts </a> ~ [Thread list] ~ <a href="archives.php" alt="le archives">Archives</a></nav></strong></summary>
<?php 

usort($data, function($a, $b){
    return $b['bump_stamp'] <=> $a['bump_stamp'];
});

echo '<table>';
echo    '<thead>';
echo    '<tr>';
echo        '<th>#</th>';
echo        '<th>Content</th>';
echo        '<th>Date</th>';
echo    '</tr>';
echo    '</thead>';
echo    '<tbody>';
foreach ($data as $key => $thread){
    $entry_id = $thread['id'];
    $entry_num = $key + 1; 
    $post_content = $thread['content'];
    $post_date = date('Y-m-d g:i e', $thread['datetime']);
echo    '<tr>';
echo        '<td>' . $entry_num . '</td>';
echo        '<td><a href="thread.php?id='.$entry_id.'" alt="Thread hyperlink">' . $post_content . '</a></td>';
echo        '<td>' . $post_date . '</td>';
echo    '</tr>';
}
echo    '</tbody>';
echo '</table>';
?>
</div>

<?php include 'templates/webring.php';?>