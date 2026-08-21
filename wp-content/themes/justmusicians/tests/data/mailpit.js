export async function peekEmailBySubject(subject, mailpitApiUrl) {
    try {
        const res = await fetch(`${mailpitApiUrl}/search?query=${encodeURIComponent('subject:' + subject)}`);
        const data = await res.json();
        return data.messages?.find(msg => msg.Subject === subject) || null;
    } catch {
        return null;
    }
}

export async function findEmailBySubject(subject, mailpitApiUrl, { timeout = 10000, interval = 500 } = {}) {
    const deadline = Date.now() + timeout;
    while (Date.now() < deadline) {
        const match = await peekEmailBySubject(subject, mailpitApiUrl);
        if (match) return match;
        await new Promise(r => setTimeout(r, interval));
    }
    return null;
}

export async function getEmailBody(messageId, mailpitApiUrl) {
    const res = await fetch(`${mailpitApiUrl}/message/${messageId}`);
    const data = await res.json();
    return data.Text || '';
}

export function extractLinkFromEmail(text) {
    const match = text.match(/https?:\/\/[^\s]+/);
    return match ? match[0] : null;
}

async function triggerWpCron(request) {
    const timestamp = Date.now();
    const res = await request.post(`http://localhost/wp-cron.php?doing_wp_cron=${timestamp}`);
    if (!res.ok()) throw new Error(`WP-Cron trigger failed with status ${res.status()}`);
}

// Polls Mailpit for the email while re-triggering WP-Cron each cycle, so deferred
// notification emails can't get stranded behind another worker's cron lock in parallel runs
export async function waitForMessageEmail(subject, mailpitApiUrl, request, { timeout = 60000, interval = 4000 } = {}) {
    const deadline = Date.now() + timeout;
    while (Date.now() < deadline) {
        const match = await peekEmailBySubject(subject, mailpitApiUrl);
        if (match) return match;
        await triggerWpCron(request).catch(() => {});
        await new Promise(r => setTimeout(r, interval));
    }
    return null;
}
