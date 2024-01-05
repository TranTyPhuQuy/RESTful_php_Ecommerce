<?php
// Include CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

// Include action.php file
include_once 'db.php';
// Create object of Users class
$user = new Database();

// create a api variable to get HTTP method dynamically
$api = $_SERVER['REQUEST_METHOD'];

// get id from url
$userId = intval($_GET['userId'] ?? '');

// Get all or a single user from database
if ($api == 'GET') {
	if ($userId != 0) {
		$data = $user->getUser($userId);
	} else {
		$data = $user->getUser();
	}
	echo json_encode($data);
}

// register a new user into database
if ($api == 'POST') {
	// $userName = $user->test_input($_POST['userName']);
	$email = $user->test_input($_POST['email']);
	$password = $user->test_input($_POST['password']);

	// Hash the password before storing it
	$hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

	// Tách email thành mảng
	$array = explode('@', $email);

	// Lấy phần tử đầu tiên làm userName
	$userName = $array[0];
	
	if ($user->insert($userName, $email, $hash)) { // pass the hashed password
		echo $user->message('User added successfully!', true);
	} else {
		echo $user->message('Failed to add an user!', false);
	}
}

// Update an user in database
if ($api == 'PUT') {
	parse_str(file_get_contents('php://input'), $post_input);

	$userName = $user->test_input($post_input['userName']);
	$email = $user->test_input($post_input['email']);
	$pass = $user->test_input($post_input['pass']);
	$roleId = $user->test_input($post_input['roleId']);

	if ($id != null) {
		if ($user->update($name, $email, $pass, $roleId, $id)) {
			echo $user->message('User updated successfully!', 200);
		} else {
			echo $user->message('Failed to update an user!', 000);
		}
	} else {
		echo $user->message('User not found!', true);
	}
}

// Delete an user from database
if ($api == 'DELETE') {
	if ($userId != null) {
		if ($user->delete($id)) {
			echo $user->message('User deleted successfully!', 200);
		} else {
			echo $user->message('Failed to delete an user!', 000);
		}
	} else {
		echo $user->message('User not found!', true);
	}
}
