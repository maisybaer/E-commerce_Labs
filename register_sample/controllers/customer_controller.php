<?php

require_once '../classes/user_class.php';


function login_user_ctr($email, $password)
{
    $customer = new Customer();
    return $customer->verifyLogin($email,$password);
}

