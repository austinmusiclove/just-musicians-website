// Reads a Playwright download to a string and strips the CSV UTF-8 BOM
export async function downloadText(download) {
    const stream = await download.createReadStream();
    const chunks = [];
    for await (const chunk of stream) chunks.push(chunk);
    return Buffer.concat(chunks).toString('utf-8').replace(/^\uFEFF/, '');
}

// Parses a full CSV document into rows of fields per RFC 4180 (quoted fields may contain commas and newlines)
export function parseCsv(csv) {
    const rows = [];
    let row = [];
    let field = '';
    let inQuotes = false;
    for (let i = 0; i < csv.length; i++) {
        const char = csv[i];
        if (inQuotes) {
            if (char === '"' && csv[i + 1] === '"') { field += '"'; i++; }
            else if (char === '"') { inQuotes = false; }
            else { field += char; }
        } else if (char === '"') {
            inQuotes = true;
        } else if (char === ',') {
            row.push(field);
            field = '';
        } else if (char === '\n') {
            row.push(field);
            field = '';
            if (row.length > 1 || row[0] !== '') rows.push(row);
            row = [];
        } else if (char !== '\r') {
            field += char;
        }
    }
    if (field !== '' || row.length > 0) { row.push(field); rows.push(row); }
    return rows;
}
