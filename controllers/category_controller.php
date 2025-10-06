<?php

require_once '../classes/category_class.php';

//add category controller
function add_cat_ctr($cat_name,$user_id)
{
    $cat = new Category();
    return $cat->addCat($cat_name,$user_id);
}

//update category controller
function update_cat_ctr($cat_id,$cat_name, $user_id)
{
    $cat = new Category();
    return $cat->updateCat($cat_id,$cat_name,$user_id);
}

//delete category controller
function delete_cat_ctr($cat_id)
{
    $cat = new Category();
    return $cat->deleteCat($cat_id);
}

//get category controller
function get_cat_ctr($user_id)
{
    $cat = new Category();
    return $cat->getCat($user_id);
}