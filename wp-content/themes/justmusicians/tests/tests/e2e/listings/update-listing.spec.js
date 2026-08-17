import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliSetUserMeta, wpCliSetPostThumbnail, wpCliGetPostField, wpCliGetPostMeta, wpCliDeleteUser, wpCliDeletePost } from '../../../data/wp_cli.js';
import { createListingPost } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Update Listing', () => {

    let testUser;
    let userId;
    let listingId;

    test.beforeEach(async ({ listingFormPage }) => {
        testUser = createUser();
        wpCliCreateUser(testUser);
        userId = wpCliGetUserId(testUser.email);

        listingId = createListingPost({ authorId: userId, overrides: { name: 'Original Name' } });
        wpCliSetPostThumbnail(listingId, 'tests/data/files/test-image.png');
        wpCliSetUserMeta(testUser.email, 'listings', [Number(listingId)]);

        await listingFormPage.login(testUser.email, testUser.password);
    });

    test.afterEach(async () => {
        if (listingId) { wpCliDeletePost(listingId); }
        if (testUser)  { wpCliDeleteUser(testUser.email); }
    });

    test('Update listing name from published listing', async ({ listingFormPage }) => {
        await listingFormPage.navigateToListing(listingId);

        await expect(listingFormPage.performerName).toHaveValue('Original Name');

        const newName = 'Updated Name';
        await listingFormPage.performerName.fill(newName);
        await listingFormPage.updateListingBottom();

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(newName);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(newName);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('publish');
    });

    test('Update listing name using top update button', async ({ listingFormPage }) => {
        await listingFormPage.navigateToListing(listingId);

        await expect(listingFormPage.performerName).toHaveValue('Original Name');

        const newName = 'Updated Name Top';
        await listingFormPage.performerName.fill(newName);
        await listingFormPage.updateListingTop();

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(newName);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(newName);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('publish');
    });

    test.skip('Update all listing fields', async ({ listingFormPage }) => {} );
    test.skip('Update listing with large image upload', async ({ listingFormPage }) => {} );

});
