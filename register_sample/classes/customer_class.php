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

    private function getCustomer($email)
    {

        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        }
    

    public function verifyLogin($email, $password)
    {
        $user = $this->getCustomer($email);

        if ($user && password_verify($password,$user['customer_pass']))) {
            return $user;
        }
        return false;
    }

}
