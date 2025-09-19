<?php

require_once '../settings/db_class.php';

/**
 * 
 */
class Customer extends db_connection
{

    public function __construct(){
        parent::db_connect();
    }

    private function getCustomer($email){

        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result 

    }
    

    public function verifyLogin($email, $password){
        $user = $this->getCustomer($email);

        if ($user && password_verify($password,$user['customer_pass'])) {
            return $user;
        }
        return false;
    }

        public function addCustomer($name,$email, $password,$country,$city,$contact,$role){
        $hashpassword=password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO customer (customer_name,customer_email,customer_pass,customer_country,customer_city,customer_contact,user_role) values(?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssi",$name, $email, $hashed_pass,$country,$city,$contact,$role);
        return $stmt->execute();
    }

    public function editCustomer($name,$email,$country,$city,$contact,$role){
        $stmt = $this->db->prepare("UPDATE customer SET customer_name=?,customer_email=?,customer_country=?,customer_city=?,customer_contact=?,user_role=? WHERE customer_id=?");
        $stmt->bind_param("sssssi",$name, $email,$country,$city,$contact,$role);
        return $stmt->execute();
    }

    public function deleteCustomer($id){
        $stmt = $this->db->prepare("DELETE FROM customer WHERE customer_id=?");
        $stmt->bind_param("i",$id);
        return $stmt->execute();
    }

}
