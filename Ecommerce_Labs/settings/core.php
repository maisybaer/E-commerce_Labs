<?php
session_start();

//for header redirection
//ob_start();

//funtion to check for login
function checkLogin($email, $password) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login/login.php");
        exit;
    }
}


//function to get user ID
function getUserID() {
    return $_SESSION['user_id'] ?? null;  
}

//function to check for role (admin, customer, etc)
function getUserRole() {
    return $_SESSION['role'] ?? null;     
}

?>