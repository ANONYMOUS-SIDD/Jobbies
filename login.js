document.addEventListener("DOMContentLoaded", function () {
    let loginForm = document.getElementById("loginForm");

    if (!loginForm) {
        console.error("Error: loginForm not found in the DOM");
        return;
    }

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent page reload

        let formData = new FormData(loginForm);

        fetch("login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Convert response to JSON
        .then(data => {
            let errorMessage = document.getElementById("errorMessage");
            if (!errorMessage) {
                console.error("Error: errorMessage element not found!");
                return;
            }

            if (data.status === "success") {
                alert(data.message);
                window.location.href = data.redirect; // Redirect to home.php
            } else {
                errorMessage.innerText = data.message; // Show error
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
