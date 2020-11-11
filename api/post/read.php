<?php

header('Access-Controll-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

$db = (new Database())->connect();
$post = new Post($db);

$result = $post->read();
$num = $result->rowCount();

if ($num > 0) {
    $postArray = [];
    $postArray['data'] = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $postItem = [
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name,
        ];

        array_push($postArray['data'], $postItem);
    }

    echo json_encode($postArray);

} else {
    echo json_encode([
        'message' => 'no post found'    
    ]);
}