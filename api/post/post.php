<?php

header('Access-Controll-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

$db = (new Database())->connect();
$post = new Post($db);
$post->id = $_GET['id']?? die();
$post->readOne();

$postArray = [
    'id' => $post->id,
    'title' => $post->title,
    'author' => $post->author,
    'body' => $post->body,
    'category_id' => $post->category_id,
    'category_name' => $post->category_name
];

print_r(json_encode($postArray));