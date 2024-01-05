<?php
    // Include CORS headers
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Headers: X-Requested-With');
	header('Content-Type: application/json');

    // Include action.php file
	include_once 'db.php';
	// Create object of Users class
	$user = new Database();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

    // login user
	if ($api == 'POST') {
        // Xử lý yêu cầu login
        $email = $user->test_input($_POST['email']);
        $password = $user->test_input($_POST['password']);

        echo $user->login($email, $password); 
	}
?>