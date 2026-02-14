function updateInquiry(alco, post_id, inquiry) {
    alco.$data.inquiriesMap[post_id]['subject'] = inquiry.subject;
    alco.$data.inquiriesMap[post_id]['details'] = inquiry.details;
    alco.$data.inquiry['subject'] = inquiry.subject;
    alco.$data.inquiry['details'] = inquiry.details;
    alco.$data.editInquiryMode = false;
}
