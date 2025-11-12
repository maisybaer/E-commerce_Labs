<?php
session_start();
require_once '../controllers/cart_controller.php';
require_once '../settings/core.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please login to add items to cart']);
        exit;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $customer_id = $_SESSION['user_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Validate inputs
    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity']);
        exit;
    }

    // Add to cart using controller
    $cartController = new CartController();
    $result = $cartController->add_to_cart_ctr($product_id, $customer_id, $quantity);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Item added to cart successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add item to cart']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
