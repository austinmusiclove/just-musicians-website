// Deprecated
function updateInquiry(alco, post_id, inquiry) {
    //alco.$data.eventsMap[post_id]['subject'] = inquiry.subject;  // eventsMap Deprecated
    //alco.$data.eventsMap[post_id]['details'] = inquiry.details;  // eventsMap Deprecated
    alco.$data.inquiry['subject'] = inquiry.subject;
    alco.$data.inquiry['details'] = inquiry.details;
    alco.$data.editInquiryMode = false;
}
