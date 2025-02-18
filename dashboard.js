document.addEventListener('DOMContentLoaded', () => {
    fetch('get_jobs.php')
        .then(response => response.json())
        .then(data => {
            const jobListings = document.querySelector('.job-listings');
            data.forEach(job => {
                const jobListing = document.createElement('div');
                jobListing.classList.add('job-listing');
                jobListing.innerHTML = `
                    <h3>${job.title}</h3>
                    <p>${job.company}</p>
                    <p>${job.location}</p>
                `;
                jobListings.appendChild(jobListing);
            });
        })
        .catch(error => console.error('Error fetching job listings:', error));

    const map = L.map('map').setView([27.7172, 85.3240], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    fetch('get_job_locations.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(job => {
                L.marker([job.latitude, job.longitude]).addTo(map)
                    .bindPopup(`<b>${job.title}</b><br>${job.company}<br>${job.location}`);
            });
        })
        .catch(error => console.error('Error fetching job locations:', error));
});