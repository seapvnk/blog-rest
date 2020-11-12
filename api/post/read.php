<?php

header('Access-Controll-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

$db = (new Database())->connect();
$post = new Post($db);

$page = $_GET['p']?? 1;
$resultsPerPage = $_GET['r']?? 4;
$category = $_GET['c']?? -1;

$result = $post->read((int) $page, (int) $resultsPerPage, (int) $category);
$num = $result["data"]->rowCount();

if ($num > 0) {
    $postArray = [];
    $postArray['options'] = $result['options'];
    $postArray['data'] = [];

    while ($row = $result["data"]->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $postItem = [
            'id' => $id,
            'title' => $title,
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