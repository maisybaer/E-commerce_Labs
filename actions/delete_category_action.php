<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'];
    $user_id = getUserID();

    $success = delete_cat_ctr($cat_id);

    echo json_encode([
        "status" => $success ? "success" : "error",
        "message" => $success ? "Category deleted successfully!" : "Failed to delete category."
    ]);
}
