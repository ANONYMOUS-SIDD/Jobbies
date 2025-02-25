<?php
session_start(); // Start the session

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

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

// Store the email from the session (logged-in user's email)
$user_email = $_SESSION['user_email'];

// Generate the dynamic table name
$userTableName = "job_applications_" . md5($user_email);

// Check if the table exists
$tableCheckSql = "SHOW TABLES LIKE '$userTableName'";
$tableCheckResult = $conn->query($tableCheckSql);

if ($tableCheckResult->num_rows > 0) {
    // Fetch the application of the logged-in user from the dynamically created table
    $sql = "SELECT * FROM `$userTableName` WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);  // Use the session email for the logged-in user
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If table doesn't exist, set result to null to show no applications message
    $result = null;
}

// Handle form submission for Reject or Accept
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $user_email !== "") {
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];

    if ($_POST['action'] == 'reject') {
        // Update job_status to 'Rejected'
        $sql = "UPDATE `$userTableName` SET job_status = 'Rejected' WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $message = "$fullname's application has been rejected.";
    } elseif ($_POST['action'] == 'accept') {
        // Update job_status to 'Accepted'
        $sql = "UPDATE `$userTableName` SET job_status = 'Accepted' WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $message = "$fullname's application has been accepted.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link href="applications.css" rel="stylesheet">
    <style>
        .status-box {
            font-size: 16px;
            display: inline-block;
            padding: 8px 10px;
            background: linear-gradient(45deg, rgb(7, 137, 231), rgb(232, 80, 20));
            border-radius: 30px;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            position: absolute;
            bottom: 15px;
            right: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .status-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        button {
            font-size: 16px;
            display: inline;
            padding: 8px 10px;
            background: linear-gradient(45deg, rgb(7, 137, 231), rgb(232, 80, 20));
            border-radius: 30px;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            bottom: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand">Jobbies</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="home_user.php" class="nav-link">Home</a></li>
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

        <?php if ($result && $result->num_rows > 0): ?>
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
