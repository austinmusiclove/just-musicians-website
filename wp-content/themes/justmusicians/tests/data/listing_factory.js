import { faker } from '@faker-js/faker';

const DEFAULT_THUMBNAIL_URL = 'https://picsum.photos/seed/listing/200';

export function createListing(overrides = {}) {
    return {
        name: faker.person.fullName(),
        description: faker.lorem.sentence({ min: 3, max: 7 }).slice(0, 40),
        genres: [faker.music.genre(), faker.music.genre()],
        thumbnail: DEFAULT_THUMBNAIL_URL,
        city: faker.location.city(),
        state: faker.location.state({ abbreviated: true }),
        zip: faker.location.zipCode('#####'),
        bio: faker.lorem.paragraphs(2),
        verified: faker.datatype.boolean(),
        ...overrides,
    };
}
