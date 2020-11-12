<?php

header('Access-Controll-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Controll-Allow-Methods: POST');
header('Access-Controll-Allow-Headers: Access-Controll-Allow-Headers, Content-Type Access-Controll-Allow-Methods, Authorization, X-Request-Width');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$db = (new Database())->connect();
$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));
auth($data->key) or die('ACCESS DENIED');


$category->name = $data->name;

if ($category->create()) {
    echo json_encode([
        'message' => 'Category created'
    ]);
} else {
    echo json_encode([
        'message' => 'Category not created'
    ]);
}
