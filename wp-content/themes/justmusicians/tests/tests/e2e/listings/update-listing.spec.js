import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliSetUserMeta, wpCliSetPostThumbnail, wpCliGetPostField, wpCliGetPostMeta, wpCliDeleteUser, wpCliDeletePost } from '../../../data/wp_cli.js';
import { createListingPost } from '../../../data/listing_factory.js';

test.describe('E2E - Update Listing', () => {

    let testUser;
    let userId;
    let listingId;

    test.beforeEach(async () => {
        testUser = createUser();
        wpCliCreateUser(testUser);
        userId = wpCliGetUserId(testUser.email);

        listingId = createListingPost({ authorId: userId, overrides: { name: 'Original Name' } });
        wpCliSetPostThumbnail(listingId, 'tests/data/test-image.png');
        wpCliSetUserMeta(testUser.email, 'listings', [Number(listingId)]);
    });

    test.afterEach(async () => {
        if (listingId) { wpCliDeletePost(listingId); }
        if (testUser)  { wpCliDeleteUser(testUser.email); }
    });

    test('Update listing name from published listing', async ({ listingFormPage }) => {
        await listingFormPage.navigate('/');
        await listingFormPage.login(testUser.email, testUser.password);
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
        await listingFormPage.navigate('/');
        await listingFormPage.login(testUser.email, testUser.password);
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
