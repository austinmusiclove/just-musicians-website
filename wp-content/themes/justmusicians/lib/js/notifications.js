
// Get notification count
async function get_notification_count() {

    // Return 0 if user not logged in
    if (!notificationsSiteData.userLoggedIn) { return 0; }

    const res = await fetch('/wp-json/v1/notifications_count', {
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': notificationsSiteData.nonce,
        },
    });

    const data = await res.json();
    if (typeof data === 'number') {
        return data;
    }
    return 0;
}
