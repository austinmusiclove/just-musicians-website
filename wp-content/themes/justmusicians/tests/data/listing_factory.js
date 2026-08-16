import { faker } from '@faker-js/faker';
import { wpCliCreatePost } from './wp_cli.js';

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

export function createListingPost({ authorId, status = 'publish', overrides = {} } = {}) {
    const listing = createListing(overrides);
    return wpCliCreatePost({
        postType: 'listing',
        title: listing.name,
        status,
        authorId,
        meta: {
            name: listing.name,
            description: listing.description,
            email: faker.internet.email(),
            city: listing.city,
            state: listing.state,
            zip_code: listing.zip,
            bio: listing.bio,
            verified: listing.verified,
        },
    });
}
