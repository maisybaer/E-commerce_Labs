<?php

header('Content-Type: application/json');

session_start();

$response = array();

// TODO: Check if the user is already logged in and redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

//recieve data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$country=$_POST['country'];
$city=$_POST['city'];
$phone_number = $_POST['phone_number'];
$user_image=null;
$role = $_POST['role'];

if (empty($name)||empty($email)||empty($password)||empty($country)||empty($phone_number)||empty($role))
    {
    $response['status'] = 'error';
    $response['message'] = 'Please fill in all fields!';
    echo json_encode($response);
    exit();
}





// For image upload
if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp   = $_FILES['user_image']['tmp_name'];
    $fileName  = basename($_FILES['user_image']['name']);
    $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed extensions
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileExt, $allowedExts)) {
        $response['status'] = 'error';
        $response['message'] = 'Only JPG, JPEG, PNG, and GIF images are allowed.';
        echo json_encode($response);
        exit();
    }

    // Unique filename
    $newFileName = uniqid("IMG_", true) . "." . $fileExt;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $destPath)) {
        $user_image = $destPath;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Image upload failed';
        echo json_encode($response);
        exit();
    }
}




//call register_user_ctr & return message
$user_id = register_user_ctr($name, $email, $password, $country, $city, $phone_number, $role, $user_image);

if ($user_id) {
    $response['status'] = 'success';
    $response['message'] = 'Registered successfully';
    $response['user_id'] = $user_id;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to register. See register_customer_action.php_register_user_ctr';
}

echo json_encode($response);