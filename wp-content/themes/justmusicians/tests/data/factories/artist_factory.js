import { faker } from '@faker-js/faker';

export function createArtist(overrides = {}) {
    return {
        artist_uuid: faker.string.uuid(),
        name: faker.person.fullName(),
        description: faker.lorem.sentence({ min: 3, max: 7 }).slice(0, 40),
        genres: [faker.music.genre(), faker.music.genre()],
        city: faker.location.city(),
        state: faker.location.state({ abbreviated: true }),
        bio: faker.lorem.paragraphs(2),
        email: faker.internet.email(),
        ...overrides,
    };
}

export function createArtistPostData({ authorId, status = 'publish', overrides = {} } = {}) {
    const artist = createArtist(overrides);
    return {
        postType: 'artist',
        title: artist.name,
        status,
        authorId,
        meta: {
            artist_uuid: artist.artist_uuid,
            name: artist.name,
            description: artist.description,
            email: artist.email,
            city: artist.city,
            state: artist.state,
            bio: artist.bio,
        },
    };
}
