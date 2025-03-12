<?php
session_start(); // Start the session

// Retrieve user details from session
$userEmail = $_SESSION['user_email'] ?? '';
$userFullName = $_SESSION['user_fullname'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="apply.css">
    <link rel="stylesheet" href="nav_bar.css">
    <script src="apply.js" defer></script> <!-- Linking the external JavaScript file -->
</head>

<script>
    window.onload = function () {
        var params = new URLSearchParams(window.location.search);
        var companyValue = params.get('company');
        if (companyValue) {
            document.getElementById("company").value = companyValue;
        }
    };
</script>

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

    <div class="apply-form-container">
        <form id="applyForm" action="store_application.php" method="POST" enctype="multipart/form-data" class="apply-form">
            <h2>Apply for Job</h2>

            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($userFullName); ?>" readonly>
            <div class="error" id="fullnameError"></div>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
            <div class="error" id="emailError"></div>

            <label for="text">Company Name:</label>
            <input type="text" name="company" id="company" readonly>
            <div class="error" id="companyError"></div>

            <label for="number">Phone Number:</label>
            <input type="number" name="phone" id="phone" >
            <div class="error" id="phoneError"></div>

            <label for="education">Education:</label>
            <select name="education" id="education" required>
                <option value="" disabled selected>Select your education</option>
                <option value="10+2">10+2</option>
                <option value="Bachelor in Computer Science">Bachelor in Computer Science</option>
                <option value="Bachelor in Engineering">Bachelor in Engineering</option>
                <option value="Bachelor in Electrical Engineering">Bachelor in Electrical Engineering</option>
                <option value="Bachelor in Mechanical Engineering">Bachelor in Mechanical Engineering</option>
                <option value="Others">Others</option>
            </select>
            <div class="error" id="educationError"></div>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" >
            <div class="error" id="locationError"></div>

            <label for="portfolio">Portfolio Link:</label>
            <input type="url" name="portfolio" id="portfolio" required >
            <div class="error" id="portfolioError"></div>

            <label for="cv">Upload CV (PDF/DOC):</label>
            <input type="file" name="cv" id="cv" accept=".pdf, .doc, .docx">
            <label for="cv" class="file-label">Choose File</label>
            <div class="error" id="cvError"></div>

            <label for="experience">Experience:</label>
            <textarea name="experience" id="experience" rows="4" required></textarea>
            <div class="error" id="experienceError"></div>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>
