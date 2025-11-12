<?php
require_once '../settings/core.php';
require_once '../settings/db_class.php';
//require_once '../controllers/cart_controller.php';

$user_id = getUserID();
$role = getUserRole();
//$cart_items = get_user_cart_ctr($customer_id);

$db = new db_connection();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../settings/styles.css">
</head>

<body>
    <header class="menu-tray mb-3">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="../index.php" class="btn btn-sm btn-outline-secondary">Home</a>
            <a href="../login/logout.php" class="btn btn-sm btn-outline-secondary">Logout</a>
            <a href="basket.php" class="btn btn-sm btn-outline-secondary">Basket</a>
        <?php else: ?>
            <a href="../login/register.php" class="btn btn-sm btn-outline-primary">Register</a>
            <a href="../login/login.php" class="btn btn-sm btn-outline-secondary">Login</a>
        <?php endif; ?>
    </header>

    <main>  
  <div class="cart-container">
    <div class="cart-card">
      <h2>Your Shopping Cart</h2>

      <<?php if (!empty($cart_items)) { ?>
        <table class="cart-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Image</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="cartItems">
            <?php foreach ($cart_items as $item) { ?>
              <tr data-cart-id="<?= $item['cart_id']; ?>">
                <td><?= htmlspecialchars($item['product_title']); ?></td>
                <td><img src="../images/<?= htmlspecialchars($item['product_image']); ?>" alt="<?= htmlspecialchars($item['product_title']); ?>"></td>
                <td>GHS <?= number_format($item['product_price'], 2); ?></td>
                <td>
                  <input type="number" class="qty-input" value="<?= $item['qty']; ?>" min="1" data-cart-id="<?= $item['cart_id']; ?>">
                </td>
                <td>GHS <?= number_format($item['product_price'] * $item['qty'], 2); ?></td>
                <td>
                  <button class="remove-btn" data-cart-id="<?= $item['cart_id']; ?>">Remove</button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

        <div class="cart-summary">
          <h3>Total: <span id="cartTotal">
            GHS <?= number_format(array_sum(array_map(fn($i) => $i['product_price'] * $i['qty'], $cart_items)), 2); ?>
          </span></h3>
          <div class="cart-actions">
            <button onclick="window.location.href='all_product.php'">Continue Shopping</button>
            <button id="emptyCartBtn">Empty Cart</button>
            <button id="checkoutBtn">Proceed to Checkout</button>
          </div>
        </div>

      //<?php } else { ?>
        //<p class="empty-msg">Your cart is empty. <a href="all_product.php">Continue shopping</a>.</p>
      //<?php } ?>
    </div>
  </div>

    </main>


  
  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/brand.js"></script>

    
</body>

</html>