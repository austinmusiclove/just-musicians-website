import { faker } from '@faker-js/faker';

export function createApplication(overrides = {}) {
    return {
        title: faker.lorem.sentence(),
        description: faker.lorem.paragraphs(2),
        ...overrides,
    };
}
