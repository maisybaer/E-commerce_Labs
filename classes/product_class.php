<?php

require_once '../settings/db_class.php';

class Product extends db_connection
{

    public function __construct()
    {
        parent::db_connect();
    }

    //function to add product
    public function addProduct($product_name, $user_id)
    {
        $stmt = $this->db->prepare("INSERT INTO products(products_name,added_by) values(?,?)");
        $stmt->bind_param("si",$product_name,$user_id);
        return $stmt->execute();
    }

    //function to update product
    public function updateProduct($product_id,$product_name)
    {
    $stmt = $this->db->prepare("UPDATE products SET product_name = ? WHERE product_id = ?");
    $stmt->bind_param("si", $product_name, $product_id);
    return $stmt->execute();
    }

    //function to delete product
    public function deleteProduct($product_id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id=?");
        $stmt->bind_param("i",$product_id);
        return $stmt->execute();
    }

    //function to get product based on user ID
    public function getProduct($user_id)
    {

        $stmt = $this->db->prepare("SELECT * FROM products WHERE added_by = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);

        }



}