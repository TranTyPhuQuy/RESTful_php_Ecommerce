<?php
    // Include CORS headers
    header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Headers: X-Requested-With');
    header ("Content-Type: application/json; charset=UTF-8");
    header ("Access-Control-Max-Age: 3600");

    // Include action.php file
	include_once 'db.php';
	// Create object of Users class
	$user = new Database();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

    $time = time();
    if ($api == 'POST') {
        $title = $user->test_input($_POST['title']);
        $descr = $user->test_input($_POST['descr']);
        $cateId = $user->test_input($_POST['cateId']);
        $base64 = $user->test_input($_POST['base64']);

        if(!is_dir('pdf')){
            mkdir ('pdf',0777);
        }
        $filePath = 'pdf'."/".$time.".pdf";
        file_put_contents ($filePath, base64_decode($base64));

        if($user -> insertBook($title, $descr,$cateId,$filePath)) {
            echo $user->message('Book added successfully!',200);
        } else {
            echo $user->message('Failed to add a book!',000);
        }
  
	}
?>