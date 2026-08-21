import { faker } from '@faker-js/faker';

export function createTmpCodePostData({ authorId, status = 'publish', overrides = {} } = {}) {
    const tmpCode = {
        code: faker.string.alphanumeric(32),
        expiration: Math.floor(faker.date.soon({ days: 1, refDate: new Date(Date.now() + 24 * 60 * 60 * 1000) }).getTime() / 1000),
        ...overrides,
    };
    const meta = { code: tmpCode.code };
    if (tmpCode.listings) { meta.listings = tmpCode.listings; }
    if (tmpCode.artists)  { meta.artists  = tmpCode.artists; }
    return {
        postType: 'tmp_code',
        title: tmpCode.code,
        status,
        authorId,
        meta,
    };
}
