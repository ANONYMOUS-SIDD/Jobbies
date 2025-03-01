<?php
// Start output buffering to ensure header redirection works even if there is output
ob_start();

// Include database connection
include 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Default response in case of failure
$response = array('status' => 'error', 'message' => 'Job upload failed.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs and trim unnecessary spaces
    $title = trim($_POST['title']);
    $company = trim($_POST['company']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    // Validate that all fields are filled out
    if (empty($title) || empty($company) || empty($location) || empty($description)) {
        $response['message'] = 'All fields are required.';
    } else {
        // Prepare and execute the SQL statement to insert job details into the database
        $sql = "INSERT INTO jobs (title, company, location, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Check for errors in the prepared statement
        if ($stmt === false) {
            $response['message'] = 'Prepare failed: ' . htmlspecialchars($conn->error);
        } else {
            // Bind the input parameters to the prepared statement
            $stmt->bind_param("ssss", $title, $company, $location, $description);
            
            // Execute the statement and check if the job was successfully uploaded
            if ($stmt->execute()) {
                // Successful job upload, change status and message
                $response['status'] = 'success';
                $response['message'] = 'Job uploaded successfully.';
                
                // Redirect to home.php
                header("Location: home.php");
                exit();
            } else {
                // If execution failed, capture the error message
                $response['message'] = 'Execute failed: ' . htmlspecialchars($stmt->error);
            }
            
            // Close the prepared statement
            $stmt->close();
        }
    }
}

// Close the database connection
$conn->close();

// If there's an error, return the error message as JSON (can be used with AJAX)
echo json_encode($response);

// End output buffering
ob_end_flush();
?>
