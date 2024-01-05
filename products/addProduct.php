<?php
	header('Access-Control-Allow-Origin: * ');
	header('Access-Control-Allow-Methods: POST, GET, DELETE');
	header('Access-Control-Allow-Headers: X-Requested-With');
	header('Content-Type: application/json');

	include_once '../db.php';
	$user = new Database();

	$api = $_SERVER['REQUEST_METHOD'];
    
	if ($api == 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $cateId = $_POST['cateId'];
		$quantity = $_POST['quantity'];
        $image = $_POST['image'];

        $user->addProduct($name, $price, $description,$cateId,$quantity, $image);
	}
?>