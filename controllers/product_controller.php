<?php

require_once '../classes/product_class.php';

//add product controller
function add_product_ctr($product_name,$user_id)
{
    $product = new Product();
    return $product->addProduct($product_name,$user_id);
}

//update product controller
function update_product_ctr($product_id,$product_name)
{
    $product = new Product();
    return $product->updateProduct($product_id,$product_name);
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