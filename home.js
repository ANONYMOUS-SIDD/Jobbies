
document.addEventListener('DOMContentLoaded', () => {
    const uploadJobForm = document.getElementById('uploadJobForm');
    
    if (uploadJobForm) {
        
        uploadJobForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Disable the form to prevent multiple submissions
            const submitButton = uploadJobForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';

            const formData = new FormData(uploadJobForm);

            fetch('upload_job.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                // Show success message
                if (data.status === 'success') {
                    document.getElementById('uploadSuccess').textContent = data.message;
                    document.getElementById('uploadError').textContent = '';
                    uploadJobForm.reset();
                } else {
                    // Show error message
                    document.getElementById('uploadError').textContent = data.message;
                    document.getElementById('uploadSuccess').textContent = '';
                }
            })
            .catch(error => {
                // Show error message if fetch fails
                document.getElementById('uploadError').textContent = 'An error occurred while uploading the job: ' + error.message;
                document.getElementById('uploadSuccess').textContent = '';
                console.error('Error:', error);
            })
            .finally(() => {
                // Enable the submit button after the process completes
                submitButton.disabled = false;
                submitButton.textContent = 'Upload Job';
            });
        });
    }
});

