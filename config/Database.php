<?php

include '../../config/config.php';

class Database {
    private $host = ENV['host'];
    private $db_name = ENV['database'];
    private $username = ENV['username'];
    private $password = ENV['password'];

    private $conn;

    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host={$this->host}; dbname={$this->db_name};", 
                $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOEexception $e) {
            echo "Connection error {$e->getMessage()}";
        }

        return $this->conn;
    }
}