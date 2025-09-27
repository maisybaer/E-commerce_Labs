<?php

require_once '../settings/db_class.php';

/**
 * 
 */
class Customer extends db_connection
{

    public function __construct()
    {
        parent::db_connect();
    }

    //function to add customer
    public function addCustomer($name,$email, $password,$country,$city,$phone_number,$role,$user_image)
    {
        $hashpassword=password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO customer (customer_name,customer_email,customer_pass,customer_country,customer_city,customer_contact,user_role,customer_image) values(?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssss",$name, $email, $hashpassword,$country,$city,$phone_number,$role,$user_image);
        return $stmt->execute();
    }

    //function to edit customer
    public function editCustomer($name,$email,$country,$city,$phone_number,$role,$user_image)
    {
        $stmt = $this->db->prepare("UPDATE customer SET customer_name=?,customer_email=?,customer_country=?,customer_city=?,customer_contact=?,user_role=?,customer_image=? WHERE customer_id=?");
        $stmt->bind_param("sssssss",$name, $email,$country,$city,$phone_number,$role);
        return $stmt->execute();
    }

    //function to delete customer
    public function deleteCustomer($user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM customer WHERE customer_id=?");
        $stmt->bind_param("i",$user_id);
        return $stmt->execute();
    }

    //function to get customer via email
    private function getCustomerByEmail($email)
    {

        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;

        }

     //function to verify login by checking
    public function verifyLogin($email, $password)
    {
        $user = $this->getCustomerByEmail($email);

        if ($user && password_verify($password,$user['customer_pass'])) {
            return $user;
        }
        return false;
    }

}