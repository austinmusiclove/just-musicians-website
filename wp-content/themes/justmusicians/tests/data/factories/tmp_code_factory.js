import { faker } from '@faker-js/faker';
import { wpCliCreatePost } from './../wp_cli.js';

export function createTmpCode(overrides = {}) {
    return {
        code: faker.string.alphanumeric(32),
        expiration: Math.floor(faker.date.soon({ days: 1, refDate: new Date(Date.now() + 24 * 60 * 60 * 1000) }).getTime() / 1000),
        ...overrides,
    };
}

// Deprecated in favor of createTmpCodePostData
export function createTmpCodePost({ authorId, status = 'publish', overrides = {} } = {}) {
    const tmpCode = createTmpCode(overrides);
    const meta = { code: tmpCode.code };
    if (tmpCode.listings) {
        meta.listings = tmpCode.listings;
    }
    const id = wpCliCreatePost({
        postType: 'tmp_code',
        title: tmpCode.code,
        status,
        authorId,
        meta,
    });
    return { id, code: tmpCode.code };
}

export function createTmpCodePostData({ authorId, status = 'publish', overrides = {} } = {}) {
    const tmpCode = createTmpCode(overrides);
    const meta = { code: tmpCode.code };
    if (tmpCode.listings) {
        meta.listings = tmpCode.listings;
    }
     return {
        postType: 'tmp_code',
        title: tmpCode.code,
        status,
        authorId,
        meta,
    };
}
