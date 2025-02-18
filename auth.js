document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const showSignup = document.getElementById('showSignup');
    const showLogin = document.getElementById('showLogin');

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

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(loginForm);
        fetch('login.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('loginSuccess').textContent = 'Success';
                document.getElementById('loginError').textContent = '';
                setTimeout(() => {
                    window.location.href = 'home.php';
                }, 1000);
            } else {
                document.getElementById('loginError').textContent = 'Invalid credentials';
                document.getElementById('loginSuccess').textContent = '';
            }
        })
        .catch(error => {
            document.getElementById('loginError').textContent = 'An error occurred while logging in.';
            document.getElementById('loginSuccess').textContent = '';
            console.error('Error:', error);
        });
    });

    signupForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(signupForm);
        fetch('signup.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('signupSuccess').textContent = data.message;
                document.getElementById('signupError').textContent = '';
                signupForm.reset();
            } else {
                document.getElementById('signupError').textContent = data.message;
                document.getElementById('signupSuccess').textContent = '';
            }
        })
        .catch(error => {
            document.getElementById('signupError').textContent = 'An error occurred while signing up.';
            document.getElementById('signupSuccess').textContent = '';
            console.error('Error:', error);
        });
    });

    loginForm.classList.add('active');
});