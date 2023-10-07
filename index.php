<?php
require 'inc/CONFIGURATION.php';

$meta_description = $CONFIGURATION['SITE_DESCRIPTION'];

// Fetch
$fetch_data = file_get_contents('database.json', true);
$data = json_decode($fetch_data, true);

// Sort threads by most recently bumped
usort($data, function($a, $b){
    return $b['bump_stamp'] <=> $a['bump_stamp'];
});

include 'inc/controller.php';

// Very important pagination

// Items per page
$itemsPerPage = 20;

// Current page from the query string
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;

// Calculate total pages and offset
$totalItems = count($data);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;

// Get data for the current page
$currentPageData = array_slice($data, $offset, $itemsPerPage);

// Pagination links
$prevPage = ($page > 1) ? $page - 1 : null;
$nextPage = ($page < $totalPages) ? $page + 1 : null;

// Very important pagination

include 'templates/header.php';
include 'templates/about.php';
include 'templates/index_info.php';

if (!empty($data)) {

    foreach ($currentPageData as $key => $post) {
        $post_id = $post['id'];
        $post_date = date('Y-m-d g:i e', $post['datetime']);
        $post_title = $post['title'];
        $post_content = $post['content'];
        echo '<div class="thread">';
        echo    '<p class="threadTop"><a style="color:#88ffe9;"target="_self" href="thread.php?id='.$post_id.'"><b>Reply</b></a> ' . $post_date . ' </p>';
        echo        '<h2 class="threadContent"><a target="_self" href="thread.php?id='.$post_id.'">' . $post_title . '</a></h2>';
        echo '<p>Replies: '.count($post['replies']).'</p>';
        echo '</div>';
    }
} else {
    echo '<div class="collapsePost">';
    echo '<center><p><span class="redtext">DATABASE HAS BEEN OBLITERATED. </span></p><p class="redtext">Previous posts have been DELETED/archived. </p><p class="shaketext">Start a new post now.</p></center>';
    echo '</details>';
    echo '</div>';
}

include 'templates/form.php';
echo "<hr>";
echo "<center><p class='pagination'>Page ({$page} / {$totalPages})</p></center>";

echo '<center>';
// Display previous and next page links
if ($prevPage !== null) {
    echo "<a class='pagination' href=\"?p={$prevPage}\">Previous</a> ";
}
if ($nextPage !== null) {
    echo "<a class='pagination' href=\"?p={$nextPage}\">Next</a>";
}
echo '</center>';
include 'templates/webring.php';
exit();
?>
