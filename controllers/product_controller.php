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
    // Product::updateProduct expects ($product_id, $productCat, $productBrand, $productTitle, $productPrice, $productDes, $productImage, $productKey)
    return $product->updateProduct($product_id, $productCat, $productBrand, $productTitle, $productPrice, $productDes, $productImage, $productKey);
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

//view all products controller
function view_all_product_ctr(){
    $product = new Product();
    return $product->viewAllProduct();
}

//filter by category controller
function filter_by_cat_ctr($cat_id){
    $product = new Product();
    return $product->filterByCat($cat_id);
}   

//filter by brand controller
function filter_by_brand_ctr($brand_id){    
    $product = new Product();
    return $product->filterByBrand($brand_id);
}

//view single product controller 
function view_single_product_ctr($product_id){
    $product = new Product();
    return $product->viewSingleProduct($product_id);
}

