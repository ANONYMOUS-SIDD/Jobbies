<?php
// Initialize connection
$servername = "localhost";
$username = "root"; // Default username for XAMPP/WAMP
$password = ""; // Default password for XAMPP/WAMP
$dbname = "merojob";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db($dbname);

// Handle form submission for Reject or Accept
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];

        if ($_POST['action'] == 'reject') {
            $sql = "UPDATE job_applications SET job_status = 'Rejected' WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $message = "$fullname's application has been rejected.";
        } elseif ($_POST['action'] == 'accept') {
            $sql = "UPDATE job_applications SET job_status = 'Accepted' WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $message = "$fullname's application has been accepted.";
        }
    }
}

// Fetch the applications from the database
$sql = "SELECT * FROM job_applications";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link href="applications.css" rel="stylesheet">
    <style>
 .status-box  {
    font-size: 16px;
    display: inline-block;
    padding: 8px 10px;
    background: linear-gradient(45deg,rgb(7, 137, 231),rgb(232, 80, 20)); /* Smooth gradient from pinkish to peach */
    border-radius: 30px; /* Rounded corners for a modern feel */
    color: white;
    font-weight: bold; /* Make the text stand out */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Subtle text shadow for depth */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1); /* Soft shadow for a floating effect */
    margin-top: 20px;
    position: absolute;
    bottom: 15px;
    right: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition effects */
}

.status-box:hover {
    transform: translateY(-5px); /* Slight lift effect on hover */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
}
button  {
    font-size: 16px;
    display: inline;
    padding: 8px 10px;
    background: linear-gradient(45deg,rgb(7, 137, 231),rgb(232, 80, 20)); /* Smooth gradient from pinkish to peach */
    border-radius: 30px; /* Rounded corners for a modern feel */
    color: white;
    font-weight: bold; /* Make the text stand out */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Subtle text shadow for depth */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1); /* Soft shadow for a floating effect */
    margin-top: 20px;

    bottom: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition effects */
}


    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand">Mero Job</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="index.html" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <header>
        <h1>Job Applications</h1>
    </header>

    <div class="container">
        <!-- Display the message if any -->
        <?php if (isset($message)): ?>
            <div class="alert">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="application-card">
                    <h2><?php echo htmlspecialchars($row['fullname']); ?></h2>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
                    <p><strong>Education:</strong> <?php echo htmlspecialchars($row['education']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($row['experience']); ?></p>
                    <p><strong>Portfolio:</strong> <a href="<?php echo htmlspecialchars($row['portfolio']); ?>" target="_blank" class="cv"><?php echo htmlspecialchars($row['portfolio']); ?></a></p>
                    <p><strong>CV:</strong> <a href="<?php echo htmlspecialchars($row['cv']); ?>" target="_blank" class="cv">Download CV</a></p>

                    <!-- Form with hidden email and fullname -->
                    <form method="POST" action="applications.php">
                        <input type="hidden" name="email" value="<?php echo $row['email']; ?>">
                        <input type="hidden" name="fullname" value="<?php echo $row['fullname']; ?>">

                        <!-- Reject and Accept buttons -->
                        <button type="submit" name="action" value="reject" class="button">Reject</button>
                        <button type="submit" name="action" value="accept" class="button">Accept</button>
                        <p class="status-box"><?php echo htmlspecialchars($row['job_status']); ?></p>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">No applications found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
