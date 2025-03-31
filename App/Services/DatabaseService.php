<?php
namespace App\Services;

class DatabaseService {
    private $conn;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'user_management';
    
    public function __construct() {
        try {
            // First connect without specifying a database
            $this->conn = new \PDO(
                "mysql:host=$this->host", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            // Create the database if it doesn't exist
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS $this->database");
            
            // Now connect to the specific database
            $this->conn = new \PDO(
                "mysql:host=$this->host;dbname=$this->database", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            // Create necessary tables
            $this->createTables();
            
        } catch (\PDOException $e) {
            echo "Database Connection Error: " . $e->getMessage();
            die();
        }
    }
    
    private function createTables() {
        // Create users table if it doesn't exist
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(50) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Create user_activities table if it doesn't exist
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS user_activities (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                activity TEXT NOT NULL,
                timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function saveUser($name, $email, $hashedPassword, $role) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO users (name, email, password, role) 
                VALUES (:name, :email, :password, :role)
            ");
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo "Error saving user: " . $e->getMessage();
            return false;
        }
    }
    
    public function getUserByEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Error retrieving user: " . $e->getMessage();
            return false;
        }
    }
    
    public function logUserActivity($userId, $activity) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO user_activities (user_id, activity, timestamp) 
                VALUES (:user_id, :activity, NOW())
            ");
            
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':activity', $activity);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo "Error logging activity: " . $e->getMessage();
            return false;
        }
    }
}
?>