<?php

class db_connection {
    protected $db;

    public function __construct() {
        $this->db_connect();
    }

    protected function db_connect() {
        $servername = "localhost";   
        $username   = "root";        
        $password   = "smarty9Aa.g@";           
        $dbname     = "shoppin"; 

        $this->db = new mysqli($servername, $username, $password, $dbname);

        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }
}
