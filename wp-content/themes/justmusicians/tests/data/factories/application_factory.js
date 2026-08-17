import { faker } from '@faker-js/faker';
import { wpCliCreatePost } from './../wp_cli.js';

export function createApplication(overrides = {}) {
    return {
        title: faker.lorem.sentence(),
        description: faker.lorem.sentence({ min: 10, max: 20 }),
        ...overrides,
    };
}

export function createApplicationPost({ authorId, status = 'publish', overrides = {} } = {}) {
    const application = createApplication(overrides);
    return wpCliCreatePost({
        postType: 'application',
        title: application.title,
        status,
        authorId,
        meta: {
            title: application.title,
            description: application.description,
        },
    });
}
