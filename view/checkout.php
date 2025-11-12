<?php; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Checkout</h1>

    <div id="checkoutSummary">
        <!-- JS can fetch cart items again or reuse session cart -->
        <p>Cart summary will appear here...</p>
    </div>

    <button id="simulatePayBtn">Simulate Payment</button>

    <div id="paymentModal" style="display:none;">
        <p>Confirm Payment?</p>
        <button id="simulatePayBtn">Yes, I've paid</button>
        <button id="cancelPayBtn">Cancel</button>
    </div>

    <div id="checkoutMessage"></div>

    <script src="checkout.js"></script>
</body>
</html>
