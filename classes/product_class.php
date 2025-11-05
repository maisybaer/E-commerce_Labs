<?php

require_once '../settings/db_class.php';

class Product extends db_connection
{

    public function __construct()
    {
        parent::db_connect();
    }



    //function to add product
    public function addProduct($productCat, $productBrand, $productTitle, $productPrice, $productDes, $productImage, $productKey, $user_id)
    {
        $stmt = $this->db->prepare("INSERT INTO products(product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords, added_by) values(?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssisssi",$productCat, $productBrand, $productTitle, $productPrice, $productDes, $productImage, $productKey, $user_id);
        return $stmt->execute();
    }

    //function to update product
    public function updateProduct($product_id, $productCat, $productBrand, $productTitle, $productPrice, $productDes, $productImage, $productKey)
    {
    $stmt = $this->db->prepare("UPDATE products SET product_brand = ?, product_cat=?, product_title=?, product_price=?, product_desc=?, product_image=?, product_keywords=?  WHERE product_id = ?");
    $stmt->bind_param("sssisssi",$productCat, $productBrand, $productTitle, $productPrice, $productDes, $productImage, $productKey, $product_id);
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
        // Return product rows including both the category/brand names and their IDs
        $stmt = $this->db->prepare("SELECT 
            p.product_id,  p.product_cat,  p.product_brand,
            c.cat_name AS category, b.brand_name AS brand,
            p.product_title,  p.product_price, p.product_desc, p.product_image, p.product_keywords, p.added_by
        FROM products p
        JOIN categories c ON p.product_cat = c.cat_id
        JOIN brands b ON p.product_brand = b.brand_id
        WHERE p.added_by = ?");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    //view all products function
    public function viewAllProduct(){
        $stmt = $this->db->prepare("SELECT 
            p.product_id,  p.product_cat,  p.product_brand,
            c.cat_name AS category, b.brand_name AS brand,
            p.product_title,  p.product_price, p.product_desc, p.product_image, p.product_keywords, p.added_by
        FROM products p
        JOIN categories c ON p.product_cat = c.cat_id
        JOIN brands b ON p.product_brand = b.brand_id");

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //search products function
    public function search($query){
        $like = "%{$query}%";
        $stmt = $this->db->prepare("SELECT 
            p.product_id,  p.product_cat,  p.product_brand,
            c.cat_name AS category, b.brand_name AS brand,
            p.product_title,  p.product_price, p.product_desc, p.product_image, p.product_keywords, p.added_by
        FROM products p
        JOIN categories c ON p.product_cat = c.cat_id
        JOIN brands b ON p.product_brand = b.brand_id");

        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); 
    }


    //filter products function by category function
    public function filterByCat($cat_id){
        $stmt = $this->db->prepare("SELECT 
            p.product_id,  p.product_cat,  p.product_brand,
            c.cat_name AS category, b.brand_name AS brand,
            p.product_title,  p.product_price, p.product_desc, p.product_image, p.product_keywords, p.added_by
        FROM products p
        JOIN categories c ON p.product_cat = c.cat_id
        JOIN brands b ON p.product_brand = b.brand_id
        WHERE p.product_cat = ?");

        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    //filter products function by brand function
    public function filterByBrand($brand_id){
        $stmt = $this->db->prepare("SELECT 
            p.product_id,  p.product_cat,  p.product_brand,
            c.cat_name AS category, b.brand_name AS brand,
            p.product_title,  p.product_price, p.product_desc, p.product_image, p.product_keywords, p.added_by
        FROM products p
        JOIN categories c ON p.product_cat = c.cat_id
        JOIN brands b ON p.product_brand = b.brand_id
        WHERE p.product_brand = ?");

        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    //view single product function
    public function viewSingleProduct($product_id){
        $stmt = $this->db->prepare("SELECT 
            p.product_id,  p.product_cat,  p.product_brand,
            c.cat_name AS category, b.brand_name AS brand,
            p.product_title,  p.product_price, p.product_desc, p.product_image, p.product_keywords, p.added_by
        FROM products p
        JOIN categories c ON p.product_cat = c.cat_id
        JOIN brands b ON p.product_brand = b.brand_id
        WHERE p.product_id = ?");

        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}