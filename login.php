<?php
session_start();
include 'db.php';

$response = array('status' => 'error', 'message' => 'Invalid email or password.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['userType'] = $user['userType'];
            $_SESSION['companyName'] = $user['companyName'];

            $_SESSION['login_success'] = 'Login successful. Welcome, ' . $_SESSION['fullname'] . '!';
            $response['status'] = 'success';
            $response['message'] = 'Login successful.';
        }
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>