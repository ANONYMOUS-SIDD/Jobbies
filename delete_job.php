<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_company'])) {
    die(json_encode(["status" => "error", "message" => "Unauthorized access."]));
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $jobId = $_GET['id'];

    // Check if the job belongs to the logged-in company
    $sql = "DELETE FROM jobs WHERE id = ? AND company = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $jobId, $_SESSION['user_company']);
        if ($stmt->execute()) {
            echo "<script>alert('Job deleted successfully!'); window.location.href='home.php';</script>";
        } else {
            echo "<script>alert('Error deleting job.'); window.location.href='home.php';</script>";
        }
        $stmt->close();
    }
}

$conn->close();
?>
