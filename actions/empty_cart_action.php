<?php
session_start();
require_once '../controllers/cart_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = new CartController();
    $emptied = $cart->empty_cart_ctr();

    if ($emptied) {
        echo json_encode(['status' => 'success', 'message' => 'Cart emptied']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to empty cart']);
    }
}
?>
