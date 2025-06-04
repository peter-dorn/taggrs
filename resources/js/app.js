import axios from 'axios';
async function loadRedisStatusWithSpinner() {
    const spinner = document.getElementById('redisSpinner');

    try {
        spinner.classList.remove('hidden');

        const [loadingResult] = await Promise.all([
            loadRedisStatus(),
            new Promise(resolve => setTimeout(resolve, 1000))
        ]);

        return loadingResult;
    } finally {
        spinner.classList.add('hidden');
    }
}

async function loadDatabaseStatus() {
    try {
        const response = await axios.get('/api/status/database');
        const databaseStatusElement = document.getElementById('databaseStatus');

        if (response.data.status === 'online') {
            databaseStatusElement.textContent = `Online (${response.data.count} keys)`;
            databaseStatusElement.classList.remove('text-red-500');
            databaseStatusElement.classList.add('text-green-500');
        } else {
            databaseStatusElement.textContent = 'Offline';
            databaseStatusElement.classList.remove('text-green-500');
            databaseStatusElement.classList.add('text-red-500');
        }
    } catch (error) {
        const databaseStatusElement = document.getElementById('databaseStatus');
        databaseStatusElement.textContent = 'Error';
        databaseStatusElement.classList.remove('text-green-500');
        databaseStatusElement.classList.add('text-red-500');
        console.error('Error fetching database status:', error);
    }
}

async function loadDatabaseStatusWithSpinner() {
    try {
        const spinner = document.getElementById('databaseSpinner');
        spinner.classList.remove('hidden');

        const [loadingResult] = await Promise.all([
            loadDatabaseStatus(),
            new Promise(resolve => setTimeout(resolve, 1000))
        ]);

        return loadingResult;
    } finally {
        const spinner = document.getElementById('databaseSpinner');
        spinner.classList.add('hidden');
    }
}

// Load Redis status
const loadRedisStatus = async () => {
    const redisStatus = document.getElementById('redisStatus');

    try {
        const response = await axios.get('/api/status/redis');
        redisStatus.innerHTML = `
                <div class="text-green-600">
                    Online (${response.data.count} keys)
                </div>
            `;
    } catch (error) {
        redisStatus.innerHTML = `
                <div class="text-red-600">
                    Connection failed
                </div>
            `;
    }
};
document.addEventListener('DOMContentLoaded', function () {
    const trackButton = document.getElementById('trackButton');
    const trackingResult = document.getElementById('trackingResult');

    // Start interval voor automatisch verversen (elke 5 seconden)
    setInterval(loadRedisStatusWithSpinner, 5000);
    setInterval(loadDatabaseStatusWithSpinner, 5000);

    // Track button click handler
    trackButton?.addEventListener('click', async () => {
        try {
            const currentDate = new Date();
            const trackingData = {
                event: 'mouseClick',
                data: [
                    {
                        timestamp: currentDate.toISOString()
                    }
                ]
            };

            const response = await axios.post('/api/track', trackingData);
            trackingResult.classList.remove('hidden');

            loadRedisStatusWithSpinner();

            setTimeout(() => {
                trackingResult.classList.add('hidden');
            }, 3000);
        } catch (error) {
            console.error('Tracking failed:', error);
        }
    });

    // Initiële Redis status check
    loadRedisStatusWithSpinner();

    // Initiele Database status check
    loadDatabaseStatusWithSpinner();

});

document.getElementById('processQueue')?.addEventListener('click', async function() {
    const button = this;
    try {
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
        button.textContent = 'Processing...';

        const response = await axios.post('/api/process');

        if (response.data.success === true) {
            loadRedisStatusWithSpinner();
            loadDatabaseStatusWithSpinner();
        }
    } catch (error) {
        console.error('Error processing queue:', error);
    } finally {
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
        button.textContent = 'Process Queue';
    }
});
