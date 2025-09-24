
// Get notification count
async function get_notification_count() {
    const res = await fetch('/wp-json/v1/notifications_count', {
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': siteData.nonce,
        },
    });

    const data = await res.json();
    if (typeof data === 'number') {
        return data;
    }
    return 0;
}
