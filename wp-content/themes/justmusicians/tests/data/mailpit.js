export async function findEmailBySubject(subject, mailpitApiUrl, { timeout = 10000, interval = 500 } = {}) {
    const deadline = Date.now() + timeout;
    while (Date.now() < deadline) {
        try {
            const res = await fetch(`${mailpitApiUrl}/search?query=${encodeURIComponent('subject:' + subject)}`);
            const data = await res.json();
            const match = data.messages?.find(msg => msg.Subject === subject);
            if (match) return match;
        } catch {}
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
