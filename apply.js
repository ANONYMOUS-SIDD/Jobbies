// Form validation on submit
document
  .getElementById("applyForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form from submitting if there are errors
    let isValid = true;

    // Reset all error messages
    document.querySelectorAll(".error").forEach(function (errorElem) {
      errorElem.textContent = "";
    });

    // Validate Phone Number
    const phone = document.getElementById("phone").value;

    const nepalPhoneRegex = /^(98|97|96)[0-9]{8}$/; // Phone numbers must start with 98, 97, or 96 and be 10 digits in total

    if (!phone.trim()) {
      isValid = false;
      document.getElementById("phoneError").textContent =
        "Phone number is required.";
    } else if (!nepalPhoneRegex.test(phone)) {
      isValid = false;
      document.getElementById("phoneError").textContent =
        "Please enter a valid Nepal phone number.";
    }

    if (isValid) {
      document.getElementById("phoneError").textContent = ""; // Clear error message if valid
    }

    // Validate Education
    const education = document.getElementById("education").value;
    if (!education.trim()) {
      isValid = false;
      document.getElementById("educationError").textContent =
        "Please select your education.";
    }

    // Validate Location
    const location = document.getElementById("location").value;

    const locationRegex = /^[A-Za-z\s]+$/; // Allows alphabetic characters and spaces only

    if (!location.trim()) {
      isValid = false;
      document.getElementById("locationError").textContent =
        "Location is required.";
    } else if (!locationRegex.test(location)) {
      isValid = false;
      document.getElementById("locationError").textContent =
        "Location must only contain letters and spaces.";
    }

    if (isValid) {
      document.getElementById("locationError").textContent = ""; // Clear error message if valid
    }

    // Validate Portfolio Link (Optional)
    const portfolio = document.getElementById("portfolio").value;
    if (
      portfolio &&
      !/^(https?:\/\/)?([\w\-]+\.)+[a-z]{2,6}(\/[^\s]*)?$/i.test(portfolio)
    ) {
      isValid = false;
      document.getElementById("portfolioError").textContent =
        "Please enter a valid portfolio URL.";
    }

    // Validate CV file upload
    const cv = document.getElementById("cv").files[0];
    if (!cv) {
      isValid = false;
      document.getElementById("cvError").textContent = "CV is required.";
    } else {
      const allowedExtensions = ["pdf", "doc", "docx"];
      const fileExtension = cv.name.split(".").pop().toLowerCase();
      if (!allowedExtensions.includes(fileExtension)) {
        isValid = false;
        document.getElementById("cvError").textContent =
          "Only PDF, DOC, DOCX files are allowed.";
      }
    }

    // Validate Experience
    const experience = document.getElementById("experience").value;
    if (!experience.trim()) {
      isValid = false;
      document.getElementById("experienceError").textContent =
        "Experience is required.";
    }

    // If all validations pass, submit the form
    if (isValid) {
      document.getElementById("applyForm").submit();
      window.location.href = "home_user.php"; 
    }
  });
