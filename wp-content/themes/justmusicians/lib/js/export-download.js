// Enables file downloads through htmx: when a htmx request responds with an
// attachment (Content-Disposition), trigger a browser download instead of a swap
document.body.addEventListener('htmx:beforeSwap', function (event) {

    var xhr = event.detail.xhr;
    var disposition = xhr.getResponseHeader('Content-Disposition') || '';
    if (disposition.indexOf('attachment') === -1) { return; }

    event.detail.shouldSwap = false;
    event.detail.serverError = false;

    var contentType = xhr.getResponseHeader('Content-Type') || 'text/csv';
    var blob = new Blob([xhr.responseText], { type: contentType });
    var match = disposition.match(/filename="?([^";]+)"?/i);

    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = match ? match[1] : 'download.csv';
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(link.href);

    event.detail.elt.dispatchEvent(new CustomEvent('success-toast', {
        bubbles: true,
        detail:  { message: 'Applicants exported successfully' },
    }));

});
