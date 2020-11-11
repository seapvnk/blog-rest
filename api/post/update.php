<?php

header('Access-Controll-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Controll-Allow-Methods: PUT');
header('Access-Controll-Allow-Headers: Access-Controll-Allow-Headers, Content-Type Access-Controll-Allow-Methods, Authorization, X-Request-Width');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

$db = (new Database())->connect();
$post = new Post($db);

$data = json_decode(file_get_contents("php://input"));

$post->id = $data->id;

$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

if ($post->update()) {
    echo json_encode([
        'message' => 'Post updated'
    ]);
} else {
    echo json_encode([
        'message' => 'Post not updated'
    ]);
}
