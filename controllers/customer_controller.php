<?php

require_once '../classes/customer_class.php';

//to invoke the customer_class::add($args) method
function login_user_ctr($email, $password)
{
    $customer = new Customer();
    return $customer->verifyLogin($email,$password);
}

//register_customer_ctr($kwargs) method to invoke the customer_class::add($args) method
function register_user_ctr($name, $email, $password,$country,$city, $phone_number, $role,$user_image)
{
    $customer = new Customer();
    return $customer->addCustomer($name, $email, $password,$country,$city, $phone_number, $role,$user_image);
}