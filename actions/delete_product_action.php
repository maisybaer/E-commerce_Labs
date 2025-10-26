<?php
require_once '../controllers/product_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = getUserID();

    $success = delete_product_ctr($product_id);

    echo json_encode([
        "status" => $success ? "success" : "error",
        "message" => $success ? "Product deleted successfully!" : "Failed to delete product."
    ]);
}
