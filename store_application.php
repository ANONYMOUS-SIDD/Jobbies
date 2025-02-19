<?php
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

// Create the job_applications table if it does not exist
$tableCreationQuery = "CREATE TABLE IF NOT EXISTS job_applications (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    education VARCHAR(50) NOT NULL,
    location VARCHAR(50) NOT NULL,
    experience TEXT NOT NULL,
    portfolio TEXT NOT NULL,
    cv VARCHAR(255) NOT NULL,
    job_status VARCHAR(20) NOT NULL DEFAULT 'Pending'
)";

if ($conn->query($tableCreationQuery) !== TRUE) {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
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
    
    // Insert the data into the database with job_status set to 'Pending'
    $stmt = $conn->prepare("INSERT INTO job_applications (fullname, email, phone, education, location, experience, portfolio, cv, job_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("ssssssss", $fullname, $email, $phone, $education, $location, $experience, $portfolio, $cvPath);
    
    if ($stmt->execute()) {
        // Redirect to home.php after successful form submission
        header("Location: home_user.php");
        exit();
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
