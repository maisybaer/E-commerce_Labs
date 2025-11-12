<?php
session_start();
require_once '../controllers/cart_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['qty']);

    $cart = new CartController();
    $updated = $cart->update_cart_item_ctr($product_id, $qty);

    if ($updated) {
        echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update quantity']);
    }
}
?>
