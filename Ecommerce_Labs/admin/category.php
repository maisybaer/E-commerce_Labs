<?php
session_start();
require_once '../settings/core.php';
require_once '../settings/db_class.php';

//checks if user is logged in
checkLogin($email, $password);


//checks if user is not an admin
$user_id = getUserID();
$role = getUserRole();

if ($role !== '1') {
    header("Location: ../login/login.php");
    exit();
}


$db = new db_connection();

//retrieve


// create


//update


//delete

