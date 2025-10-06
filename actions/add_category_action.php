<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = $_POST['cat_name'] ?? '';
    $user_id = getUserID();

    if (!empty($cat_name) && !empty($user_id)) {
        $result = add_cat_ctr($cat_name, $user_id);
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Category added successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add category."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing category name or user ID."]);
    }
}

