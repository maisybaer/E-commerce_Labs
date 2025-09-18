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

$email = $_POST['email'];
$password = $_POST['password'];

if(empty($email)||empty($password)){
    $response['status']='error';
    $respone['message']='Please type your email and password';
    echo json_encode($response);
    exit();
}

$user = login_user_ctr($email, $password);

if ($user) {
    $response['status'] = 'success';
    $response['message'] = 'Login successfully';
    $response['user'] = [
        'id'=>$user['customer_id'],
        'name'=>$user['customer_name'],
        'email'=>$user['customer_email'],
        'role'=>$user['user_role']
    ];
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid email or password';
}

echo json_encode($response);