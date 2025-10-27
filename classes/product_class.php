<?php

require_once '../settings/db_class.php';

class Product extends db_connection
{

    public function __construct()
    {
        parent::db_connect();
    }



    //function to add product
    public function addProduct($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice, $user_id)
    {
        $stmt = $this->db->prepare("INSERT INTO products(product_brand, product_cat, product_title, product_price, product_desc, product_image, product_keywords, added_by) values(?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssisssi",$productBrand,$productCat, $productTitle, $productDes, $productKey, $productImage, $productPrice, $user_id);
        return $stmt->execute();
    }

    //function to update product
    public function updateProduct($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice,$product_id)
    {
    $stmt = $this->db->prepare("UPDATE products SET product_brand = ?, product_cat=?, product_title=?, product_price=?, product_desc=?, product_image=?, product_keywords=?  WHERE product_id = ?");
    $stmt->bind_param("sssisssi",$productBrand,$productCat, $productTitle, $productDes, $productKey, $productImage, $productPrice, $product_id);
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