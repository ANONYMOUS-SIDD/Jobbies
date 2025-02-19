// Form validation on submit
document.getElementById("applyForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form from submitting if there are errors
    let isValid = true;

    // Reset all error messages
    document.querySelectorAll(".error").forEach(function (errorElem) {
        errorElem.textContent = "";
    });

    // Validate Full Name
    const fullname = document.getElementById("fullname").value;
    if (!fullname.trim()) {
        isValid = false;
        document.getElementById("fullnameError").textContent = "Full Name is required.";
    }

    // Validate Email
    const email = document.getElementById("email").value;
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!email.trim()) {
        isValid = false;
        document.getElementById("emailError").textContent = "Email is required.";
    } else if (!emailPattern.test(email)) {
        isValid = false;
        document.getElementById("emailError").textContent = "Please enter a valid email.";
    }

    // Validate Phone Number
    const phone = document.getElementById("phone").value;
    if (!phone.trim()) {
        isValid = false;
        document.getElementById("phoneError").textContent = "Phone number is required.";
    }

    // Validate Education
    const education = document.getElementById("education").value;
    if (!education.trim()) {
        isValid = false;
        document.getElementById("educationError").textContent = "Please select your education.";
    }

    // Validate Location
    const location = document.getElementById("location").value;
    if (!location.trim()) {
        isValid = false;
        document.getElementById("locationError").textContent = "Location is required.";
    }

    // Validate Portfolio Link (Optional)
    const portfolio = document.getElementById("portfolio").value;
    if (portfolio && !/^(https?:\/\/)?([\w\-]+\.)+[a-z]{2,6}(\/[^\s]*)?$/i.test(portfolio)) {
        isValid = false;
        document.getElementById("portfolioError").textContent = "Please enter a valid portfolio URL.";
    }

    // Validate CV file upload
    const cv = document.getElementById("cv").files[0];
    if (!cv) {
        isValid = false;
        document.getElementById("cvError").textContent = "CV is required.";
    } else {
        const allowedExtensions = ['pdf', 'doc', 'docx'];
        const fileExtension = cv.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            isValid = false;
            document.getElementById("cvError").textContent = "Only PDF, DOC, DOCX files are allowed.";
        }
    }

    // Validate Experience
    const experience = document.getElementById("experience").value;
    if (!experience.trim()) {
        isValid = false;
        document.getElementById("experienceError").textContent = "Experience is required.";
    }

    // If all validations pass, submit the form
    if (isValid) {
        document.getElementById("applyForm").submit();
    }
});
