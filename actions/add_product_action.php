<?php
require_once '../controllers/product_controller.php';
require_once '../settings/core.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $user_id = $_POST['user_id'] ?? '';

    if (!empty($product_name) && !empty($user_id)) {
        $result = add_product_ctr($product_name, $user_id);
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Product added successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add product."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing product name or user ID."]);
    }
}

