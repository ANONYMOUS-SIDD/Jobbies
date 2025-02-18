<?php
include 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = array('status' => 'error', 'message' => 'Job upload failed.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $title = trim($_POST['title']);
    $company = trim($_POST['company']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    if (empty($title) || empty($company) || empty($location) || empty($description)) {
        $response['message'] = 'All fields are required.';
    } else {
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO jobs (title, company, location, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $response['message'] = 'Prepare failed: ' . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param("ssss", $title, $company, $location, $description);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Job uploaded successfully.';
            } else {
                $response['message'] = 'Execute failed: ' . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }
    }
}

$conn->close();
echo json_encode($response);
?>