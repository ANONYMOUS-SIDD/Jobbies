<?php
// Enable error reporting to help debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure no output before JSON response
ob_start();

// Include database connection
include 'db.php';

// Initialize response array
$response = array('status' => 'error', 'message' => 'Something went wrong.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userType = $_POST['userType'];
    $companyName = isset($_POST['companyName']) ? trim($_POST['companyName']) : '';
// Check if required fields are filled
if (empty($fullname) || empty($email) || empty($password) || empty($confirmPassword)) {
    $response['message'] = 'All fields are required.';
} 
// Validate full name (only letters and spaces)
elseif (!preg_match("/^[a-zA-Z ]+$/", $fullname)) {
    $response['message'] = 'Full name should contain only letters and spaces.';
} 
// Validate email format
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please provide a valid email address.';
} 
// Check if email is from a disposable email provider (Basic Check)
elseif (preg_match('/@(tempmail|mailinator|disposablemail)\./i', $email)) {
    $response['message'] = 'Please use a non-disposable email address.';
} 
// Validate password length
elseif (strlen($password) < 6) {
    $response['message'] = 'Password must be at least 6 characters long.';
} 
// Check if password contains at least one uppercase letter
elseif (!preg_match('/[A-Z]/', $password)) {
    $response['message'] = 'Password must contain at least one uppercase letter.';
} 
// Check if password contains at least one special character
elseif (!preg_match('/[\W_]/', $password)) {
    $response['message'] = 'Password must contain at least one special character.';
} 
// Check if password contains at least one digit
elseif (!preg_match('/\d/', $password)) {
    $response['message'] = 'Password must contain at least one digit.';
} 
// Check if passwords match
elseif ($password !== $confirmPassword) {
    $response['message'] = 'Passwords do not match.';
} 

    
    
    
    else {
        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $response['message'] = 'This email is already registered.';
            } else {
                // If user type is 'employer' and company name is provided, check for company name duplication
                if ($userType == 'employer' && !empty($companyName)) {
                    // Check if the company name already exists
                    $companyCheckSql = "SELECT * FROM users WHERE companyName = ?";
                    if ($companyCheckStmt = $conn->prepare($companyCheckSql)) {
                        $companyCheckStmt->bind_param("s", $companyName);
                        $companyCheckStmt->execute();
                        $companyResult = $companyCheckStmt->get_result();
                        if ($companyResult->num_rows > 0) {
                            $response['message'] = 'Company name is already registered.';
                        } else {
                            // Proceed with user registration if company name is unique
                            registerUser($fullname, $email, $password, $userType, $companyName);
                        }
                        $companyCheckStmt->close();
                    } else {
                        $response['message'] = 'Error checking company name: ' . $conn->error;
                    }
                } else {
                    // Proceed with user registration for non-employer or if company name is not provided
                    registerUser($fullname, $email, $password, $userType, $companyName);
                }
            }
            $stmt->close();
        } else {
            $response['message'] = 'Error checking email: ' . $conn->error;
        }
    }
}

// Function to handle user registration
function registerUser($fullname, $email, $password, $userType, $companyName) {
    global $conn, $response;

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL query to insert the user
    $sql = "INSERT INTO users (fullname, email, password, userType, companyName) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $fullname, $email, $hashedPassword, $userType, $companyName);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Account created successfully!';
        } else {
            $response['message'] = 'Error registering user: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Error preparing registration query: ' . $conn->error;
    }
}

// Ensure proper response format and prevent any unexpected output
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
$conn->close();

// Flush the output buffer
ob_end_flush();
?>