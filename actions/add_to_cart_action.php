<?php
require_once '../controllers/cart_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $customer_id = getUserID();
    $quantity = $_POST['quantity'];

    // EDIT: Added call to add_to_cart_ctr()
    $result = add_to_cart_ctr($product_id, $customer_id, $quantity);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add item']);
    }
}
?>
