<?php
session_start();
require_once '../controllers/cart_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);

    $cart = new CartController();
    $removed = $cart->remove_from_cart_ctr($product_id);

    if ($removed) {
        echo json_encode(['status' => 'success', 'message' => 'Product removed from cart']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove product']);
    }
}
?>
