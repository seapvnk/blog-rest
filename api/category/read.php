<?php

header('Access-Controll-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$db = (new Database())->connect();
$result = (new Category($db))->read();
$num = $result->rowCount();

if ($num > 0) {
    $categories = [];
    $categories['data'] = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $categoryItem = [
            'id' => $id,
            'name' => $name,
        ];

        array_push($categories['data'], $categoryItem);
    }

    echo json_encode($categories);

} else {
    echo json_encode([
        'message' => 'no category found'    
    ]);
}