<?php
include 'db.php';

$sql = "SELECT title, company, location, latitude, longitude FROM jobs";
$result = $conn->query($sql);

$jobLocations = [];
while ($row = $result->fetch_assoc()) {
    $jobLocations[] = $row;
}

header('Content-Type: application/json');
echo json_encode($jobLocations);

$conn->close();
?>