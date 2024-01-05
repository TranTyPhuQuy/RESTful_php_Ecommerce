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
$productId = intval($_GET['productId'] ?? '');

// get category
if ($api == 'DELETE') {
	if ($user->deleteProductByProductId($productId)) {
		echo $user->message('Delete product suscessfully!',true);
	} else {
		echo $user->message('Failed to delete product!',false);
	}
}
