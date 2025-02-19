<?php
$uploadsDir = 'uploads';

if (!file_exists($uploadsDir)) {
    if (mkdir($uploadsDir, 0777, true)) {
        echo "Uploads folder created successfully.<br>";
    } else {
        die("Failed to create uploads folder.<br>");
    }
} else {
    echo "Uploads folder already exists.<br>";
}
if (is_writable($uploadsDir)) {
    echo "The uploads folder has write permissions.<br>";
} else {
    // Set write permissions for the folder
    if (chmod($uploadsDir, 0777)) {
        echo "Write permissions set successfully for the uploads folder.<br>";
    } else {
        die("Failed to set write permissions for the uploads folder.<br>");
    }
}
?>
