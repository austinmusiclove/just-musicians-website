// Expects dates in Y-m-d (2027-01-07) format
function formatDateLine(startDate, endDate) {
    if (!startDate && !endDate) return 'Date not specified';

    const fmt = (dateToFormat) => {
        if (!dateToFormat) return '';
        const parts = dateToFormat.split('-');
        const date = new Date(parts[0], parts[1] - 1, parts[2]);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
    };

    let line = '';
    if (startDate && endDate) {
        return fmt(startDate) + ' \u2013 ' + fmt(endDate);
    } else if (startDate) {
        return fmt(startDate);
    } else if (endDate) {
        return '? \u2013 ' + fmt(endDate);
    }
    return '';
}

// Expects times in H:i (15:00) format
function formatTimeLine(startTime, endTime) {
    if (!startTime && !endTime) return 'Time not specified';

    const fmt = (t) => {
        const parts = t.split(':');
        const hour = parseInt(parts[0], 10);
        const min = parts[1];
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const h12 = hour % 12 || 12;
        return h12 + ':' + min + ' ' + ampm;
    };

    let line = '';
    if (startTime && endTime) {
        line = fmt(startTime) + ' \u2013 ' + fmt(endTime);
    } else if (startTime) {
        line = fmt(startTime);
    } else if (endTime) {
        line = '? \u2013 ' + fmt(endTime);
    }
    return line;
}

function updateEvent(alco, event) {
    alco.eventName      = event.event_name || '';
    alco.startDate      = event.start_date || '';
    alco.endDate        = event.end_date || '';
    alco.startTime      = event.start_time || '';
    alco.endTime        = event.end_time || '';
    alco.addressLine1   = event.address_line_1 || '';
    alco.addressLine2   = event.address_line_2 || '';
    alco.city           = event.city || '';
    alco.state          = event.state || '';
    alco.zipCode        = event.zip_code || '';
    alco.details        = event.details || '';
    alco.budget         = event.budget || '';
    alco.compensation   = event.compensation || '';
    alco.requestQuote   = event.request_quote ? true : false;
    alco.requestDraw    = event.request_draw ? true : false;
    alco.genres         = event.genres || [];
    alco.ensembleSize   = event.ensemble_size || [];
}
