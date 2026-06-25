
// Get notification count
async function get_user_notifications() {

    // Return 0 if user not logged in
    if (!notificationsSiteData.userLoggedIn) { return 0; }

    const res = await fetch('/wp-json/v1/user-notifications', {
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': notificationsSiteData.nonce,
        },
    });

    const data = await res.json();
    if (typeof data === 'object') {
        return data;
    }
    return 0;
}

function get_notification_count(notifications, type) {
    return notifications?.[type]?.count ?? 0;
}

function get_total_notification_count(notifications) {
    if (!notifications || typeof notifications !== 'object') return 0;
    let total = 0;
    for (const key in notifications) {
        if (notifications[key]?.count) total += notifications[key].count;
    }
    return total;
}

function get_event_notification_count(notifications) {
    return get_notification_count(notifications, 'inquiry_response')
         + get_notification_count(notifications, 'inquiry_response_update');
}

function get_subject_ids(notifications, type) {
    return notifications?.[type]?.subject_ids ?? [];
}

function has_notification(notifications, type, subject_id) {
    return get_subject_ids(notifications, type).includes(subject_id);
}

function has_notifications(notifications, types, subject_id) {
    return types.some(type => has_notification(notifications, type, subject_id));
}

function get_event_count_for_proposals(notifications, proposal_ids) {
    return get_subject_ids(notifications, 'inquiry_response').filter(id => proposal_ids.includes(id)).length
         + get_subject_ids(notifications, 'inquiry_response_update').filter(id => proposal_ids.includes(id)).length;
}

function get_gig_notification_count(notifications) {
    return get_notification_count(notifications, 'new_inquiry')
         + get_notification_count(notifications, 'event_dt_change');
}
