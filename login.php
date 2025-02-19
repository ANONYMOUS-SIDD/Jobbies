<?php
header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check if both fields are filled
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Email and password are required."]);
        exit();
    }

    // Compare email and password directly
    if ($email === $password) { // Simple check: Email and password must be the same
        echo json_encode(["status" => "success", "message" => "Login successful!", "redirect" => "home.php"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid credentials."]);
        exit();
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}
?>
