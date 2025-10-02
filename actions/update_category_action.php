<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'];
    $cat_name = $_POST['cat_name'];
    $user_id = getUserID();

    $success = update_cat_ctr($cat_id, $cat_name);

    echo json_encode([
        "status" => $success ? "success" : "error",
        "message" => $success ? "Category updated successfully!" : "Failed to update category."
    ]);
}
