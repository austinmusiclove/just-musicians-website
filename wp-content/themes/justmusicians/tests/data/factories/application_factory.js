import { faker } from '@faker-js/faker';

export function createApplicationPostData({ authorId, status = 'publish', overrides = {} } = {}) {
    const application = {
        title: faker.lorem.sentence(),
        description: faker.lorem.sentence({ min: 10, max: 20 }),
        request_quote: false,
        request_draw: false,
        ...overrides,
    };
    return {
        postType: 'application',
        title: application.title,
        status,
        authorId,
        meta: {
            title: application.title,
            description: application.description,
            request_quote: application.request_quote,
            request_draw: application.request_draw,
        },
    };
}
