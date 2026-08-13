import { faker } from '@faker-js/faker';
import { wpCliCreatePost } from './wp_cli.js';

export function createTmpCode(overrides = {}) {
    return {
        code: faker.string.alphanumeric(32),
        ...overrides,
    };
}

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
