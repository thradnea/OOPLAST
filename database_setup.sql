-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS user_management;

-- Use the database
USE user_management;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Create user activities table
CREATE TABLE IF NOT EXISTS user_activities (
                                               id INT AUTO_INCREMENT PRIMARY KEY,
                                               user_id INT NOT NULL,
                                               activity TEXT NOT NULL,
                                               timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                               FOREIGN KEY (user_id) REFERENCES users(id)
    );