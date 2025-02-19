<?php
session_start(); 
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Path to the 'uploads' directory
$uploadsDir = 'uploads';

// Check if the 'uploads' folder exists, if not create it and set permissions
if (!file_exists($uploadsDir)) {
    if (!mkdir($uploadsDir, 0777, true)) {
        die("Failed to create uploads folder.<br>");
    }
}

// Initialize connection
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

// Select the database
$conn->select_db($dbname);

// Check if user is logged in and get their user ID
if (!isset($_POST['email'])) {
    die("Email not provided.");
}
$email = $_POST['email']; // Getting email from POST data
$company = $_POST['company']; // Getting company from POST data
$user_email = $_SESSION['user_email'];

// Dynamically create the user table name based on email (making it safe for SQL)
$userTableName = "job_applications_" . md5($email);

// Dynamically create the company table name based on company name (making it safe for SQL)
$companyTableName = "company_applications_" . md5($company);

// Create the user job_applications table if it does not exist
$userTableCreationQuery = "CREATE TABLE IF NOT EXISTS $userTableName (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    company VARCHAR(100) NOT NULL,  -- Ensure this column exists
    education VARCHAR(50) NOT NULL,
    location VARCHAR(50) NOT NULL,
    experience TEXT NOT NULL,
    portfolio TEXT NOT NULL,
    cv VARCHAR(255) NOT NULL,
    job_status VARCHAR(20) NOT NULL DEFAULT 'Pending'
)";

if ($conn->query($userTableCreationQuery) !== TRUE) {
    echo "Error creating user table: " . $conn->error . "<br>";
}

// Create the company job_applications table if it does not exist
$companyTableCreationQuery = "CREATE TABLE IF NOT EXISTS $companyTableName (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    company VARCHAR(100) NOT NULL,  -- Ensure this column exists
    education VARCHAR(50) NOT NULL,
    location VARCHAR(50) NOT NULL,
    experience TEXT NOT NULL,
    portfolio TEXT NOT NULL,
    cv VARCHAR(255) NOT NULL,
    job_status VARCHAR(20) NOT NULL DEFAULT 'Pending'
)";

if ($conn->query($companyTableCreationQuery) !== TRUE) {
    echo "Error creating company table: " . $conn->error . "<br>";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $company = htmlspecialchars($_POST['company']);
    $education = htmlspecialchars($_POST['education']);
    $location = htmlspecialchars($_POST['location']);
    $experience = htmlspecialchars($_POST['experience']);
    $portfolio = htmlspecialchars($_POST['portfolio']);
    
    // Validate if the file is uploaded and valid
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cvName = $_FILES['cv']['name'];
        $cvTmpName = $_FILES['cv']['tmp_name'];
        $cvSize = $_FILES['cv']['size'];
        $cvType = $_FILES['cv']['type'];

        // Check file extension
        $allowedExtensions = ['pdf', 'doc', 'docx'];
        $fileExtension = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            // Generate a unique name for the file
            $cvNewName = uniqid() . '-' . $cvName;
            $cvPath = $uploadsDir . '/' . $cvNewName;
            
            // Move the uploaded file
            if (!move_uploaded_file($cvTmpName, $cvPath)) {
                die("Failed to upload CV.<br>");
            }
        } else {
            die("Invalid file type. Only PDF and DOC/DOCX files are allowed.<br>");
        }
    } else {
        die("Error uploading CV.<br>");
    }
    
    // Insert the data into the user table
    $stmtUser = $conn->prepare("INSERT INTO $userTableName (fullname, email, phone, company, education, location, experience, portfolio, cv, job_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmtUser->bind_param("sssssssss", $fullname, $user_email, $phone, $company, $education, $location, $experience, $portfolio, $cvPath);
    
    if (!$stmtUser->execute()) {
        echo "Error inserting into user table: " . $stmtUser->error . "<br>";
    }
    
    // Insert the data into the company table
    $stmtCompany = $conn->prepare("INSERT INTO $companyTableName (fullname, email, phone, company, education, location, experience, portfolio, cv, job_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmtCompany->bind_param("sssssssss", $fullname, $user_email, $phone, $company, $education, $location, $experience, $portfolio, $cvPath);
    
    if ($stmtCompany->execute()) {
        // Redirect to home_user.php after successful form submission
        header("Location: home_user.php");
        exit();
    } else {
        echo "Error inserting into company table: " . $stmtCompany->error . "<br>";
    }
    
    // Close the statements and connection
    $stmtUser->close();
    $stmtCompany->close();
    $conn->close();
}
?>
