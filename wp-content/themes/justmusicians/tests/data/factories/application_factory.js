import { faker } from '@faker-js/faker';

export function createApplicationPostData({ authorId, status = 'publish', overrides = {} } = {}) {
    const application = {
        title: faker.lorem.sentence(),
        description: faker.lorem.sentence({ min: 10, max: 20 }),
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
        },
    };
}
