<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mero Job</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">Mero Job</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="#" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Jobs</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Companies</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Profile</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <h1>Welcome, <?php echo $_SESSION['fullname']; ?>!</h1>
            <p>Find your dream job with Mero Job</p>
            <form class="search-form">
                <input type="text" placeholder="Job title, keywords, or company" />
                <input type="text" placeholder="Location" />
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search Jobs</button>
            </form>
        </div>
    </header>

    <section class="jobs">
        <div class="container">
            <h2>Latest Jobs</h2>
            <div class="job-listings">
                <!-- Job listings will be dynamically loaded here -->
            </div>
        </div>
    </section>

    <section class="map">
        <div class="container">
            <h2>Job Locations</h2>
            <div id="map"></div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Mero Job. All rights reserved.</p>
        </div>
    </footer>

    <script src="dashboard.js"></script>
</body>
</html>