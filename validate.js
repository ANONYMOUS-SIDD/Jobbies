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
        const userType = signupForm.querySelector('[name="userType"]:checked').value;
        const companyName = signupForm.querySelector('[name="companyName"]').value.trim();
        let isValid = true;

        // Validate Full Name
        if (fullname === '') {
            document.getElementById('fullnameError').textContent = 'Full name is required.';
            isValid = false;
        } else {
            document.getElementById('fullnameError').textContent = '';
        }

        // Validate Email
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!email.match(emailPattern)) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            isValid = false;
        } else {
            document.getElementById('emailError').textContent = '';
        }

        // Validate Password
        if (password.length < 6) {
            document.getElementById('passwordError').textContent = 'Password must be at least 6 characters long.';
            isValid = false;
        } else {
            document.getElementById('passwordError').textContent = '';
        }

        // Validate Confirm Password
        if (password !== confirmPassword) {
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
            isValid = false;
        } else {
            document.getElementById('confirmPasswordError').textContent = '';
        }

        // Company Name Validation (if Employer selected)
        if (userType === 'employer' && companyName === '') {
            document.getElementById('companyNameError').textContent = 'Company name is required for Employers.';
            isValid = false;
        } else {
            document.getElementById('companyNameError').textContent = '';
        }

        return isValid;
    }

    // Handle Signup Form Submission
    signupForm.addEventListener('submit', (event) => {
        event.preventDefault();
        if (validateSignupForm()) {
            const userType = signupForm.querySelector('[name="userType"]:checked').value;
            window.location.href = userType === 'employer' ? 'home.php' : 'home_user.php';
        }
    });

    // Client-side Login Validation
    function validateLoginForm() {
        const email = loginForm.querySelector('[name="email"]').value.trim();
        const password = loginForm.querySelector('[name="password"]').value;
        const userType = loginForm.querySelector('[name="userType"]:checked').value;
        let isValid = true;

        // Validate Email
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!email.match(emailPattern)) {
            document.getElementById('loginEmailError').textContent = 'Please enter a valid email address.';
            isValid = false;
        } else {
            document.getElementById('loginEmailError').textContent = '';
        }

        // Validate Password
        if (password.length < 6) {
            document.getElementById('loginPasswordError').textContent = 'Password must be at least 6 characters long.';
            isValid = false;
        } else {
            document.getElementById('loginPasswordError').textContent = '';
        }

        return isValid;
    }

    // Handle Login Form Submission
    loginForm.addEventListener('submit', (event) => {
        event.preventDefault();
        if (validateLoginForm()) {
            const userType = loginForm.querySelector('[name="userType"]:checked').value;
            window.location.href = userType === 'employer' ? 'home.php' : 'home_user.php';
        }
    });
});
