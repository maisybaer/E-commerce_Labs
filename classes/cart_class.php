<?php

require_once '../settings/db_class.php';

class Cart extends db_connection
{

    public function __construct()
    {
        parent::db_connect();
    }

    /**
     * Add a product to cart or update quantity if it exists
     */
    public function addToCart($product_id, $customer_id, $qty)
    {
        // Check if the product already exists in the cart for this customer
        $check_query = "SELECT qty FROM cart WHERE product_id = ? AND customer_id = ?";
        $stmt = $this->db->prepare($check_query);
        $stmt->bind_param("ii", $product_id, $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Product already in cart – update quantity
            $row = $result->fetch_assoc();
            $new_qty = $row['qty'] + $qty;

            $update_query = "UPDATE cart SET qty = ? WHERE product_id = ? AND customer_id = ?";
            $stmt = $this->db->prepare($update_query);
            $stmt->bind_param("iii", $new_qty, $product_id, $customer_id);
            return $stmt->execute();
        } else {
            // Add new product to cart
            $insert_query = "INSERT INTO cart (product_id, customer_id, qty) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($insert_query);
            $stmt->bind_param("iii", $product_id, $customer_id, $qty);
            return $stmt->execute();
        }
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function updateCart($product_id, $customer_id, $qty)
    {
        $query = "UPDATE cart SET qty = ? WHERE product_id = ? AND customer_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $qty, $product_id, $customer_id);
        return $stmt->execute();
    }

    /**
     * Remove a product from the cart.
     */
    public function removeFromCart($product_id, $customer_id)
    {
        $query = "DELETE FROM cart WHERE product_id = ? AND customer_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $product_id, $customer_id);
        return $stmt->execute();
    }

    /**
     * Retrieve all cart items for a specific customer (joins with products for display).
     */
public function getCart($customer_id)
{
    $query = "SELECT c.cart_id, c.product_id, c.qty, c.customer_id,
                     p.product_title, p.product_price, p.product_image
              FROM cart c
              JOIN products p ON c.product_id = p.product_id
              WHERE c.customer_id = ?";
    
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


    /**
     * Empty the cart completely for a specific customer.
     */
    public function emptyCart($customer_id)
    {
        $query = "DELETE FROM cart WHERE customer_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customer_id);
        return $stmt->execute();
    }

    /**
     * Check if a product already exists in the cart for a specific customer.
     */
    public function existingProductCheck($product_id, $customer_id)
    {
        $query = "SELECT * FROM cart WHERE product_id = ? AND customer_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $product_id, $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
?>