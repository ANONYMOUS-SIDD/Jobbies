document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const showSignup = document.getElementById('showSignup');
    const showLogin = document.getElementById('showLogin');

    // Toggle between login and signup forms
    showSignup.addEventListener('click', (e) => {
        e.preventDefault();
        loginForm.classList.remove('active');
        setTimeout(() => {
            signupForm.classList.add('active');
        }, 300);
    });

    showLogin.addEventListener('click', (e) => {
        e.preventDefault();
        signupForm.classList.remove('active');
        setTimeout(() => {
            loginForm.classList.add('active');
        }, 300);
    });

    // Client-side Signup Validation
    function validateSignupForm() {
        const fullname = signupForm.querySelector('[name="fullname"]').value.trim();
        const email = signupForm.querySelector('[name="email"]').value.trim();
        const password = signupForm.querySelector('[name="password"]').value;
        const confirmPassword = signupForm.querySelector('[name="confirmPassword"]').value;
        const companyName = signupForm.querySelector('[name="companyName"]').value.trim();
        let isValid = true;

   // Validate Username
const username = document.getElementById('fullname');
const usernameError = document.getElementById('fullnameError');
const usernameRegex = /^[a-zA-Z_]{3,15}$/; // Allows only letters (A-Z, a-z) and underscores, length between 3 and 15 characters

if (username === '') {
    usernameError.textContent = 'Username is required.';
    isValid = false;
} else if (!usernameRegex.test(username)) {
    usernameError.textContent = 'Username must be between 3-15 characters and can only contain letters and underscores.';
    isValid = false;
} else {
    usernameError.textContent = ''; // Clear error message if validation passes
}


        // Validate Email
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!email.match(emailPattern)) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            isValid = false;
        } else {
            document.getElementById('emailError').textContent = '';
        }

      // Get the password input and error display elements
const pass = document.getElementById('password').value;
const passwordError = document.getElementById('passwordError');


// Check if the password is empty
if (pass === '') {
    passwordError.textContent = 'Password is required.';
    isValid = false;
// Check password length
} else if (pass.length < 6) {
    passwordError.textContent = 'Password must be at least 6 characters long.';
    isValid = false;
// Check for at least one digit
} else if (!/[0-9]/.test(pass)) {
    passwordError.textContent = 'Password must contain at least one digit.';
    isValid = false;
// Check for at least one uppercase letter
} else if (!/[A-Z]/.test(pass)) {
    passwordError.textContent = 'Password must contain at least one uppercase letter.';
    isValid = false;
// Check for at least one special character
} else if (!/[!@#$%^&*]/.test(pass)) {
    passwordError.textContent = 'Password must contain at least one special character.';
    isValid = false;
} else {
    // If all checks pass, clear the error message
    passwordError.textContent = '';
}

// Return the final validity status
return isValid;


        // Validate Confirm Password
        if (password !== confirmPassword) {
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
            isValid = false;
        } else {
            document.getElementById('confirmPasswordError').textContent = '';
        }

        // Company Name Validation (if Employer selected)
        if (signupForm.querySelector('[name="userType"]:checked').value === 'employer' && companyName === '') {
            document.getElementById('companyNameError').textContent = 'Company name is required for Employers.';
            isValid = false;
        } else {
            document.getElementById('companyNameError').textContent = '';
        }

        return isValid;
    }

    // Submit Signup Form
    signupForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (validateSignupForm()) {
            const formData = new FormData(signupForm);
            fetch('signup.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const successMessage = document.getElementById('signupSuccess');
                const errorMessage = document.getElementById('signupError');

                // Clear previous messages
                
                successMessage.style.display = 'none';
                errorMessage.style.display = 'none';

                if (data.status === 'success') {
                    successMessage.textContent = data.message;
                    successMessage.style.display = 'block';
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                const errorMessage = document.getElementById('signupError');
                errorMessage.textContent = 'An error occurred while signing up.';
                errorMessage.style.display = 'block';
                console.error('Error:', error);
            });
        }
    });

    // Initially show login form
    loginForm.classList.add('active');
});