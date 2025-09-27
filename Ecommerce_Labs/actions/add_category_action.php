<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = $_POST['cat_name'];
    $user_id = getUserID();

    $success = add_cat_ctr($user_id, $cat_name);

    echo json_encode([
        "status" => $success ? "success" : "error",
        "message" => $success ? "Category added successfully!" : "Failed to add category."
    ]);
}
