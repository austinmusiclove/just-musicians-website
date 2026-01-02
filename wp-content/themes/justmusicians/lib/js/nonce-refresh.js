
// Refresh nonce after interval as long as tab is active
setInterval(async () => {
    const res = await fetch('/wp-json/v1/refresh-nonce', {
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nonceSiteData.nonce,
        },
    });
    const data = await res.json();
    nonceSiteData.nonce = data.nonce; // update the global nonce
}, nonceSiteData.nonceRefreshInterval); // every 10 minutes (well before 12h expiry)
