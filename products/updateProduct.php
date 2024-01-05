<?php
// Include CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

// Include action.php file
include_once '../db.php';
// Create object of Users class
$user = new Database();

// create a api variable to get HTTP method dynamically
$api = $_SERVER['REQUEST_METHOD'];
// get category
if ($api == 'POST') {
    $productId = $_POST['productId'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    if ($user->updateProduct($productId,$name,$price,$description,$quantity)) {
        echo $user->message('Update successfully', true);
    } else {
        echo $user->message('Failed to update', false);
    }
}
