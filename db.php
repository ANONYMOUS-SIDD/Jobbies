<?php
$servername = "localhost";
$username = "root"; // Default username for XAMPP/WAMP
$password = ""; // Default password for XAMPP/WAMP
$dbname = "merojob";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // echo "Database created successfully or already exists.";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create users table if not exists
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    userType VARCHAR(50) NOT NULL,
    companyName VARCHAR(50)
)";
if ($conn->query($sql) === TRUE) {
    // echo "Table created successfully or already exists.";
} else {
    die("Error creating table: " . $conn->error);
}

// Create jobs table if not exists
$sql = "CREATE TABLE IF NOT EXISTS jobs (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    company VARCHAR(50) NOT NULL,
    location VARCHAR(50) NOT NULL,
    description TEXT NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    // echo "Table created successfully or already exists.";
} else {
    die("Error creating table: " . $conn->error);
}
?>