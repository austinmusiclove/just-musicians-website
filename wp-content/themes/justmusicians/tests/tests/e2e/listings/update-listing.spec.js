import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Update Listing', () => {

    let listingId;

    test.beforeEach(async ({ listingFormPage, wpCli }) => {
        const testUser = createUser();
        const userId = wpCli.createUser(testUser);

        const listingData = createListingPostData({ authorId: userId, overrides: { name: 'Original Name' } });
        listingId = wpCli.createListing(listingData);

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
