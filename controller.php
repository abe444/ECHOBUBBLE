<?php 

$thread_count = count($data);
$reply_count = [];
foreach ($data as $reply){
    $reply_count = array_merge($reply_count, $reply['replies']);
}
$total_entries = count($reply_count) + $thread_count;

if($total_entries >= $CONFIGURATION['POST_LIMIT']){
$data = [];

$data = array_values($data);

$jsonData = json_encode($data);

file_put_contents('database.json', $jsonData, true);
exit();
}
