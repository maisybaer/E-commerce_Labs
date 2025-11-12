<?php
session_start();
require_once '../controllers/cart_controller.php';
require_once '../controllers/order_controller.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $cartController = new CartController();
    $orderController = new OrderController();
    $productController = new Product();

    // Get cart items
    $cart_items = $cartController->get_user_cart_ctr();
    if (empty($cart_items)) {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
        exit;
    }

    // Generate unique order reference (e.g., timestamp + random)
    $invoice_no = 'ORD-' . time() . '-' . rand(1000, 9999);
    $order_date = date('Y-m-d H:i:s');
    $order_status = 'Pending';

    // Create order
    $order_id = $orderController->create_order_ctr([
        'customer_id' => $user_id,
        'invoice_no' => $invoice_no,
        'order_date' => $order_date,
        'order_status' => $order_status
    ]);

    if (!$order_id) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create order']);
        exit;
    }

    // Add order details
    foreach ($cart_items as $item) {
        $orderController->add_order_details_ctr([
            'order_id' => $order_id,
            'product_id' => $item['product_id'],
            'qty' => $item['qty'],
            'price' => $item['product_price']
        ]);
    }

    // Simulate payment
    $payment_success = $orderController->record_payment_ctr([
        'amt' => array_sum(array_map(fn($i) => $i['qty'] * $i['product_price'], $cart_items)),
        'customer_id' => $user_id,
        'order_id' => $order_id,
        'currency' => 'USD',
        'payment_date' => $order_date
    ]);

    if ($payment_success) {
        // Empty cart
        $cartController->empty_cart_ctr();

        echo json_encode([
            'status' => 'success',
            'order_id' => $order_id,
            'invoice_no' => $invoice_no,
            'message' => 'Order placed successfully'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Payment failed']);
    }
}
?>
