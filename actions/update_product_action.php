<?php
require_once '../controllers/product_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];

    if ($product_id && $product_name) {
        $result = update_product_ctr($product_id, $product_name);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Product updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update product."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing fields."]);
    }

}
