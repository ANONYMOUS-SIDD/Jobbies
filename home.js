document.addEventListener('DOMContentLoaded', () => {
    const uploadJobForm = document.getElementById('uploadJobForm');
    if (uploadJobForm) {
        uploadJobForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(uploadJobForm);
            fetch('upload_job.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('uploadSuccess').textContent = data.message;
                    document.getElementById('uploadError').textContent = '';
                    uploadJobForm.reset();
                } else {
                    document.getElementById('uploadError').textContent = data.message;
                    document.getElementById('uploadSuccess').textContent = '';
                }
            })
            .catch(error => {
                document.getElementById('uploadError').textContent = 'An error occurred while uploading the job: ' + error.message;
                document.getElementById('uploadSuccess').textContent = '';
                console.error('Error:', error);
            });
        });
    }
});