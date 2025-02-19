<?php
header('Content-Type: application/json'); // Ensure JSON response

// Start the session
session_start();

// Include database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Email and password are required."]);
        exit();
    }

    // Prepare SQL query to get user by email
    $sql = "SELECT email, password, userType,companyName FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) { // Verify hashed password
                // Store user info in session after successful login
                $_SESSION['user_email'] = $email;
                $_SESSION['user_type'] = $row['userType'];
                $_SESSION['user_company'] = $row['companyName'];
         

                // Redirect based on user type
                if ($row['userType'] === 'employee') {
                    echo json_encode(["status" => "success", "message" => "Login successful!", "redirect" => "home_user.php"]);
                } elseif ($row['userType'] === 'employer') {
                    echo json_encode(["status" => "success", "message" => "Login successful!", "redirect" => "home.php"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Invalid user type."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid password."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "User not found."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
