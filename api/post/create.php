<?php

header('Access-Controll-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Controll-Allow-Methods: POST');
header('Access-Controll-Allow-Headers: Access-Controll-Allow-Headers, Content-Type Access-Controll-Allow-Methods, Authorization, X-Request-Width');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

$db = (new Database())->connect();
$post = new Post($db);

$data = json_decode(file_get_contents("php://input"));
auth($data->key) or die('ACCESS DENIED');

$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

if ($post->create()) {
    echo json_encode([
        'message' => 'Post created'
    ]);
} else {
    echo json_encode([
        'message' => 'Post not created'
    ]);
}
