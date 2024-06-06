<?php

class Database
{
    private static ?Database $instance = null;
    private string $servername = DB_HOST;
    private string $username = DB_USERNAME;
    private string $password = DB_PASSWORD;
    private string $database = DB_NAME;
    protected PDO $conn;

    private function __construct()
    {

        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}

