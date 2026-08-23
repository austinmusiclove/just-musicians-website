import { faker } from '@faker-js/faker';

export function createAppSubmissionPostData({ applicationId, listingId, authorId = 0, status = 'publish', overrides = {} } = {}) {
    const submission = {
        message: faker.lorem.sentence(),
        applicantStatus: 'active',
        ...overrides,
    };
    return {
        postType: 'app_submission',
        title: `${applicationId}-${listingId}`,
        status,
        authorId,
        meta: {
            application: String(applicationId),
            listing: String(listingId),
            message: submission.message,
            status: submission.applicantStatus,
        },
    };
}
