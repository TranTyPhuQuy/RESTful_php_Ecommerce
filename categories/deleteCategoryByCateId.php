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
$cateId = intval($_GET['cateId'] ?? '');

// get category
if ($api == 'DELETE') {
	if ($user->deleteCategoryByCateId($cateId)) {
		echo $user->message('Delete category suscessfully!',true);
	} else {
		echo $user->message('Failed to delete category!',false);
	}
}
