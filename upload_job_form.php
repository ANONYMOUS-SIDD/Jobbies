<?php
include 'db.php';
session_start(); 
$company = $_SESSION['user_company'] ?? '';
$error_message = '';
$success_message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Server-side validation
    $restricted_fields = ['title', 'company', 'location'];
    
    foreach ($restricted_fields as $field) {
        if (isset($_POST[$field])) {
            $_POST[$field] = trim($_POST[$field]);
            if (preg_match('/\d/', $_POST[$field])) {
                $errors[$field] = "Numbers are not allowed in " . ucfirst($field);
            }
            if (empty($_POST[$field])) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }
    }

    if (empty($errors)) {
        // Proceed with database insertion
        $title = $_POST['title'];
        $company = $_POST['company'];
        $location = $_POST['location'];
        $description = $_POST['description'];

        $sql = "INSERT INTO jobs (title, company, location, description) 
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $company, $location, $description);

        if ($stmt->execute()) {
            $success_message = "Job uploaded successfully!";
            $_POST = array();
            header("Location: home.php");
       
        } else {
            $error_message = "Error uploading job! Please try again.";
        }

        $stmt->close();
    } else {
        $error_message = "Please correct the highlighted errors";
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Job - Jobbies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #6a0dad, #1e90ff);
            color: #fff;
            min-height: 100vh;
        }

      
        /* Navigation */
        nav {
            padding: 20px 8%;
            background: rgba(0, 0, 0, 0.9);
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 100;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #FFD700;
            text-transform: uppercase;
        }

        .nav-links a {
            margin-left: 30px;
            text-decoration: none;
            color: #FFD700;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: #FF5733;
        }

        .cta-button {
            background: #FF5733;
            color: black !important;
            padding: 12px 30px;
            border-radius: 30px;
            transition: transform 0.3s ease;
        }

        .upload-job {
            padding: 4rem 0;
            min-height: calc(100vh - 120px);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #ff8c00;
            margin-bottom: 2.5rem;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, #ff8c00, #3498db);
        }

        .form-container {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: formSlideIn 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        @keyframes formSlideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.8rem;
            color: #ff8c00;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: #6a0dad;
            box-shadow: 0 0 20px rgba(106, 13, 173, 0.3);
        }

        .input-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        textarea::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #6a0dad;
            border-radius: 4px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(45deg, #ff8c00, #6a0dad);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 140, 0, 0.4);
        }

        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transition: all 0.6s ease;
        }

        button[type="submit"]:hover::before {
            left: 100%;
        }

        .error-message {
            color: #ff4444;
            margin-bottom: 1rem;
            text-align: center;
            animation: shake 0.4s ease;
        }

        .success-message {
            color: #00C851;
            margin-bottom: 1rem;
            text-align: center;
            animation: fadeInUp 0.4s ease;
        }

        .field-error {
            display: block;
            color: #ff4444;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            animation: shake 0.4s ease;
        }

        .invalid-input {
            border-color: #ff4444 !important;
            background: rgba(255, 68, 68, 0.1) !important;
            animation: inputError 0.4s ease;
        }

        @keyframes inputError {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes shake {
            0% { transform: translateX(-10px); }
            25% { transform: translateX(10px); }
            50% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
            100% { transform: translateX(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<nav>
        <div class="logo">Jobbies</div>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="contactUs.html">Contact Us</a>
            <a href="aboutUs.html">About Us</a>
            <a href="auth.html" class="cta-button">Get Started</a>
        </div>
    </nav>

    <section class="upload-job">
        <div class="container">
            <h2>Upload a Job Listing</h2>
            <div class="form-container">
                <?php if ($error_message) { echo "<div class='error-message'>$error_message</div>"; } ?>
                <?php if ($success_message) { echo "<div class='success-message'>$success_message</div>"; } ?>

                <form method="POST" action="" id="uploadJobForm">
                    <div class="input-group">
                        <label for="title">Job Title</label>
                        <input type="text" name="title" id="title" 
                               value="<?php echo $_POST['title'] ?? ''; ?>" 
                               required
                               onsubmit="validateField(this)">
                        <span class="field-error" id="title-error"></span>
                    </div>

                    <div class="input-group">
    <label for="company">Company</label>
    <input type="text" name="company" id="company" 
           value="<?php echo htmlspecialchars($company); ?>" 
           readonly
           oninput="validateField(this)">
    <span class="field-error" id="company-error"></span>
</div>


                    <div class="input-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" 
                               value="<?php echo $_POST['location'] ?? ''; ?>" 
                               required
                               oninput="validateField(this)">
                        <span class="field-error" id="location-error"></span>
                    </div>

                    <div class="input-group">
                        <label for="description">Job Description</label>
                        <textarea name="description" id="description" rows="4" required><?php echo $_POST['description'] ?? ''; ?></textarea>
                    </div>

                    <button type="submit" id="submitBtn">
                        <i class="fa fa-upload"></i> Upload Job
                    </button>
                    

                </form>
            </div>
        </div>
    </section>

    <script>
        function validateField(field) {
            const errorElement = document.getElementById(`${field.id}-error`);
            const value = field.value.trim();
            
            // Clear previous error
            field.classList.remove('invalid-input');
            errorElement.textContent = '';

            if (value === '') {
                errorElement.textContent = 'This field is required';
                field.classList.add('invalid-input');
                return false;
            }

            if (/\d/.test(value)) {
                errorElement.textContent = 'Numbers are not allowed';
                field.classList.add('invalid-input');
                return false;
            }

            return true;
        }

        function validateForm() {
            let isValid = true;
            const fields = ['title', 'company', 'location'];
            
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!validateField(field)) isValid = false;
            });

            return isValid;
        }

        // Real-time validation and form submission handling
        document.getElementById('uploadJobForm').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                document.querySelector('.error-message').textContent = 'Please fix the errors before submitting';
            }
        });

        // Enable/disable submit button based on validity
        document.querySelectorAll('input, textarea').forEach(element => {
            element.addEventListener('input', () => {
                const isValid = validateForm();
                document.getElementById('submitBtn').disabled = !isValid;
            });
        });

        // Initial validation check
        document.getElementById('submitBtn').disabled = !validateForm();
    </script>
</body>
</html>