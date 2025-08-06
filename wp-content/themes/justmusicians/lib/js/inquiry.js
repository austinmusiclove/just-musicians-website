
function showDate(inquiry) {
    if (inquiry.date != '' && inquiry.date_type == 'Single Date') {
        return inquiry.date;
    } else if (inquiry.date_type != 'Single Date' && inquiry.date_type != '') {
        return inquiry.date_type;
    } else {
        return 'TBD';
    }
}

function showTime(inquiry) {
    if (inquiry.time != '' && inquiry.date_type == 'Single Date') {
        return inquiry.time;
    } else {
        return 'TBD';
    }
}

function showBudget(inquiry) {
    if (inquiry.budget_type == 'Request Quotes') {
        return 'I need a quote';
    } else if (inquiry.budget_type == 'Guarantee' && inquiry.budget != '') {
        return '$' + inquiry.budget;
    } else if (inquiry.budget_type == 'Guarantee' && inquiry.budget == '') {
        return 'Guarantee (unspecified)'
    } else if (inquiry.budget_type == 'Door Deal' && inquiry.percent_of_door != '') {
        return 'Door Deal (' + inquiry.percent_of_door + '%)';
    } else if (inquiry.budget_type == 'Door Deal' && inquiry.percent_of_door == '') {
        return 'Door Deal';
    } else if (inquiry.budget_type == 'Bar Deal' && inquiry.percent_of_bar != '') {
        return 'Bar Deal (' + inquiry.percent_of_bar + '%)';
    } else if (inquiry.budget_type == 'Bar Deal' && inquiry.percent_of_bar == '') {
        return 'Bar Deal';
    } else {
        return 'TBD';
    }
}

