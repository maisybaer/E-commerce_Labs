<?php
require_once '../classes/cart_class.php';

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new Cart();
    }

    /**
     * Add a product to the cart
     * $params: ['product_id' => int, 'qty' => int]
     */
    public function add_to_cart_ctr($user_id,)
    {
        $product_id = $params['product_id'];
        $qty = $params['qty'];

        // Check if product already exists in cart
        if ($this->cart->existingProductCheck($product_id)) {
            // Increment quantity
            return $this->cart->addToCart($product_id, $qty);
        } else {
            // Add new product
            return $this->cart->addToCart($product_id, $qty);
        }
    }

    /**
     * Update the quantity of an existing cart item
     */
    public function update_cart_item_ctr($product_id, $qty)
    {
        return $this->cart->updateCart($product_id, $qty);
    }

    /**
     * Remove a product from the cart
     */
    public function remove_from_cart_ctr($product_id)
    {
        return $this->cart->removeFromCart($product_id);
    }

    /**
     * Get all items in the user's cart
     */
    public function get_user_cart_ctr()
    {
        // Using session user ID
        $customer_id = $_SESSION['user_id'];
        return $this->cart->getCart();
    }

    /**
     * Empty the user's cart
     */
    public function empty_cart_ctr()
    {
        $customer_id = $_SESSION['user_id'];
        return $this->cart->emptyCart();
    }
}
?>
