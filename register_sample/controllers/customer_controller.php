<?php

require_once '../classes/user_class.php';

function register_customer_ctr($name, $email, $password, $phone_number, $role)
{
    $customer = new Customer();
    $customer_id = $customer->create($name, $email, $password, $phone_number,$country, $city, $role);
    if ($customer_id) {
        return $customer_id;
    }
    return false;
}

function login_customer_ctr($email, $password)
{
    $customer = new Customer();
    return $customer->verifyLogin($email,$password);
}

function add_customer_ctr($name,$email,$password,$phone_number,$country,$city,$role){
    $customer=new Customer();
    return $customer->addCustomer($name,$email,$password,$phone_number,$country,$city,$role);
}
