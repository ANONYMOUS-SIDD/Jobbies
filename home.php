<?php
include 'db.php';

// Fetch jobs from the database
$sql = "SELECT title, company, location, description FROM jobs";
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
    <title>Home - Mero Job</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="home.js" defer></script>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="home.php" class="navbar-brand">Mero Job</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <h1>Welcome to Mero Job!</h1>
            <p>Find and manage jobs with Mero Job</p>
        </div>
    </header>

    <section class="jobs">
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

    <section class="upload-job">
        <div class="container">
            <h2>Upload a Job</h2>
            <form id="uploadJobForm" action="upload_job.php" method="POST">
                <div class="input-group">
                    <label for="title">Job Title</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="input-group">
                    <label for="company">Company</label>
                    <input type="text" name="company" id="company" required>
                </div>
                <div class="input-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" id="location" required>
                </div>
                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required></textarea>
                </div>
                <button type="submit">
                    <i class="fas fa-upload"></i>
                    Upload Job
                </button>
                <p class="error-message" id="uploadError"></p>
                <p class="success-message" id="uploadSuccess"></p>
            </form>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Mero Job. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>