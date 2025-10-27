<?php

require_once '../classes/product_class.php';

//add product controller
function add_product_ctr($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice, $user_id)
{
    $product = new Product();
    return $product->addProduct($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice, $user_id);
}

//update product controller
function update_product_ctr($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice,$product_id)
{
    $product = new Product();
    return $product->updateProduct($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice, $product_id);
}

//delete product controller
function delete_product_ctr($product_id)
{
    $product = new Product();
    return $product->deleteProduct($product_id);
}

//get product controller
function get_product_ctr($user_id)
{
    $product = new Product();
    return $product->getProduct($user_id);
}