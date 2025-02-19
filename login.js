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
        .then(response => {
            return response.text(); // First get raw text to debug
        })
        .then(text => {
            console.log('Response from server:', text); // Log the raw response to see if it's valid JSON
            try {
                const data = JSON.parse(text); // Now parse it into JSON
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
            } catch (error) {
                console.error("Error parsing JSON:", error);
                console.log("Raw response:", text); // This will help you see the raw response
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
