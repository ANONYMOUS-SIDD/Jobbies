<?php
include 'db.php';
session_start(); // Start session to access user data

// Fetch jobs from the database
$sql = "SELECT id, title, company, location, description FROM jobs"; // Added job ID for deletion
$result = $conn->query($sql);

$jobs = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Jobbies</title>
    <link rel="stylesheet" href="home_admin.css">
    <link rel="stylesheet" href="upload_button.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand">Jobbies</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="index.html" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <h1>Welcome to Jobbies!</h1>
            <p>Find and manage jobs with Jobbies</p>
        </div>
    </header>

    <section class="main-content">
        <div class="container">
            <h2>Available Jobs</h2>
            <div class="job-listings">
                <?php foreach ($jobs as $job): ?>
                    <div class="job-listing">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <div class="job-meta">
                            <div>
                                <i class="fas fa-building"></i>
                                <?php echo htmlspecialchars($job['company']); ?>
                            </div>
                            <div>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($job['location']); ?>
                            </div>
                        </div>
                        <div class="job-meta">
                            <div>
                                <i class="fas fa-file-alt"></i>
                                <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                            </div>
                        </div>

                        <!-- Delete Button: Only show if logged-in user's company matches the job company -->
                        <?php if (isset($_SESSION['user_company']) && trim(strtolower($_SESSION['user_company'])) === trim(strtolower($job['company']))): ?>
                            <div class="job-actions">
                                <button class="delete-job-btn" onclick="deleteJob(<?php echo $job['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete Job
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($jobs)): ?>
                    <div class="job-listing">
                        <p>No jobs available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Upload Button Section -->
    <section class="upload-button">
        <div class="container">
            <button class="credentials-btn" onclick="window.location.href='upload_job_form.php'">
                <i class="fas fa-upload"></i> Upload a Job
            </button>
        </div>
    </section>

    <!-- Credentials Button Section -->
    <section class="credentials-button">
        <div class="container">
            <button class="credentials-btn" onclick="window.location.href='applications.php'">
                <i class="fas fa-key"></i> View Credentials
            </button>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Mero Job. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function deleteJob(jobId) {
            if (confirm("Are you sure you want to delete this job?")) {
                window.location.href = "delete_job.php?id=" + jobId;
            }
        }
    </script>
</body>
</html>
