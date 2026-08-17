import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPost } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Update Listing', () => {

    let listingId;

    test.beforeEach(async ({ listingFormPage, wpCli }) => {
        const testUser = createUser();
        wpCli.createUser(testUser);
        const userId = wpCli.getUserId(testUser.email);

        listingId = createListingPost({ authorId: userId, overrides: { name: 'Original Name' } });
        wpCli.trackPost(listingId);
        wpCli.setPostThumbnail(listingId, 'tests/data/files/test-image.png');
        wpCli.setUserMeta(testUser.email, 'listings', [Number(listingId)]);

        await listingFormPage.login(testUser.email, testUser.password);
    });

    test('Update listing name from published listing', async ({ listingFormPage, wpCli }) => {
        await listingFormPage.navigateToListing(listingId);

        await expect(listingFormPage.performerName).toHaveValue('Original Name');

        const newName = 'Updated Name';
        await listingFormPage.performerName.fill(newName);
        await listingFormPage.updateListingBottom();

        expect(wpCli.getPostField(listingId, 'post_title')).toBe(newName);
        expect(wpCli.getPostMeta(listingId, 'name')).toBe(newName);
        expect(wpCli.getPostField(listingId, 'post_status')).toBe('publish');
    });

    test('Update listing name using top update button', async ({ listingFormPage, wpCli }) => {
        await listingFormPage.navigateToListing(listingId);

        await expect(listingFormPage.performerName).toHaveValue('Original Name');

        const newName = 'Updated Name Top';
        await listingFormPage.performerName.fill(newName);
        await listingFormPage.updateListingTop();

        expect(wpCli.getPostField(listingId, 'post_title')).toBe(newName);
        expect(wpCli.getPostMeta(listingId, 'name')).toBe(newName);
        expect(wpCli.getPostField(listingId, 'post_status')).toBe('publish');
    });

    test.skip('Update all listing fields', async ({ listingFormPage }) => {} );
    test.skip('Update listing with large image upload', async ({ listingFormPage }) => {} );

});
