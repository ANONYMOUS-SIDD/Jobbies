<?php
include 'db.php';

$sql = "SELECT title, company, location FROM jobs";
$result = $conn->query($sql);

$jobs = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$conn->close();
echo json_encode($jobs);
?>