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
    <link rel="stylesheet" href="home_user.css">
    <link rel="stylesheet" href="upload_button.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="home.js" defer></script>
    <script>
        function showForm() {
            var uploadJobSection = document.querySelector(".upload-job");
            if (uploadJobSection.style.display === 'none' || uploadJobSection.style.display === '') {
                uploadJobSection.style.display = 'block';  // Show the section
            } else {
                uploadJobSection.style.display = 'none';  // Hide the section
            }
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand">Mero Job</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="home_user.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="index.html" class="nav-link">Dashboard</a></li>
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
                            <div id="company">
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
                        <button class="apply-button" onclick="location.href='apply.html' ">Apply Now</button>
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
    <button type="button" id="show-form" onclick="window.location.href='view_appliction.php';">
    View Application Status
</button>


    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Mero Job. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>