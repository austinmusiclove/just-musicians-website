import { faker } from '@faker-js/faker';

const DEFAULT_THUMBNAIL_URL = 'https://picsum.photos/seed/listing/200';
const GENRES = [
    'Avant-Garde', 'Blues', 'Christian', 'Classical', 'Country', 'Electronic',
    'Folk', 'Funk', 'Hip Hop', 'Jazz', 'Latin', 'Metal', 'Pop', 'R&B',
    'Reggae', 'Rock', 'Soul',
];
const ENSEMBLE_SIZES = ['Solo', 'Duo', 'Trio', '4-6', '7+'];

export function createListingPostData({ authorId, status = 'publish', overrides = {} } = {}) {
    const listing = {
        name: faker.person.fullName(),
        description: faker.lorem.sentence({ min: 3, max: 7 }).slice(0, 40),
        genres: faker.helpers.arrayElements(GENRES, { min: 0, max: 3 }),
        ensembleSizes: faker.helpers.arrayElements(ENSEMBLE_SIZES, { min: 0, max: 3 }),
        thumbnail: DEFAULT_THUMBNAIL_URL,
        city: faker.location.city(),
        state: faker.location.state({ abbreviated: true }),
        zip: faker.location.zipCode('#####'),
        bio: faker.lorem.paragraphs(2),
        verified: faker.datatype.boolean(),
        email: faker.internet.email(),
        ...overrides,
    };
    return {
        postType: 'listing',
        title: listing.name,
        status,
        authorId,
        genres: listing.genres,
        ensembleSizes: listing.ensembleSizes,
        meta: {
            name: listing.name,
            description: listing.description,
            email: listing.email,
            city: listing.city,
            state: listing.state,
            zip_code: listing.zip,
            bio: listing.bio,
            verified: listing.verified,
        },
    };
}
