<?php

class DataBase {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'finplanner';
    private $connection;

    public function __construct() {
        $this->connect();
        $this->initializeDatabase();
    }

    private function connect() {
        $this->connection = new \mysqli($this->host, $this->user, $this->password);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    private function initializeDatabase() {
        $createDbQuery = "CREATE DATABASE IF NOT EXISTS " . $this->database;
        if ($this->connection->query($createDbQuery) === FALSE) {
            die("Error creating database: " . $this->connection->error);
        }

        $this->connection->select_db($this->database);

        $this->createTables();
    }

    private function createTables() {
        $queries = [
            "CREATE TABLE IF NOT EXISTS Users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            )",
            "CREATE TABLE IF NOT EXISTS Category (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT
            )",
            "CREATE TABLE IF NOT EXISTS Cash (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                category_id INT NOT NULL,
                type ENUM('income', 'expense') NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                date DATE NOT NULL,
                description TEXT,
                FOREIGN KEY (user_id) REFERENCES Users(id),
                FOREIGN KEY (category_id) REFERENCES Category(id)
            )",
            "CREATE TABLE IF NOT EXISTS Goals (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                category_id INT NOT NULL,
                target_amount DECIMAL(10, 2) NOT NULL,
                current_amount DECIMAL(10, 2) DEFAULT 0,
                description TEXT,
                FOREIGN KEY (user_id) REFERENCES Users(id),
                FOREIGN KEY (category_id) REFERENCES Category(id)
            )"
        ];

        foreach ($queries as $query) {
            if ($this->connection->query($query) === FALSE) {
                echo "Error creating table: " . $this->connection->error . "\n";
            }
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
