
// Refresh nonce after interval as long as tab is active
setInterval(async () => {
    const res = await fetch('/wp-json/v1/refresh-nonce', {
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': siteData.nonce,
        },
    });
    const data = await res.json();
    siteData.nonce = data.nonce; // update the global nonce
}, siteData.nonceRefreshInterval); // every 10 minutes (well before 12h expiry)
