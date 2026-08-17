export async function findEmailBySubject(subject, mailpitApiUrl, { timeout = 10000, interval = 500 } = {}) {
    console.log(subject);
    const deadline = Date.now() + timeout;
    while (Date.now() < deadline) {
        try {
            console.log('try');
            const res = await fetch(`${mailpitApiUrl}/search?query=${encodeURIComponent('subject:' + subject)}`);
            const data = await res.json();
            const match = data.messages?.find(msg => msg.Subject === subject);
            console.log(data.messages)
            if (match) return match;
        } catch {}
        await new Promise(r => setTimeout(r, interval));
    }
    return null;
}
