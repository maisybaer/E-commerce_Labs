// Settings/core.php
<?php
session_start();

//for header redirection
ob_start();

//funtion to check for login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}


//function to get user ID
function getUserID()
    {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id']:null;
}

//function to check for role (admin, customer, etc)
function checkRole($user_id, $conn) {

    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['role'];
    } else {
        header("Location: login.php"); 
        exit;
    }
}


?>