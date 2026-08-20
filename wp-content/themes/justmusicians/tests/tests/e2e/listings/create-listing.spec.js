import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListing } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Create Listing', () => {

    let testUser;
    let userId;

    test.beforeEach(async ({ listingFormPage, wpCli }) => {
        testUser = createUser();
        userId = wpCli.createUser(testUser);

        await listingFormPage.login(testUser.email, testUser.password);
        await listingFormPage.navigate('/listing-form/');
    });

    test('Create listing using bottom publish button', async ({ listingFormPage, wpCli }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/files/test-image.png');
        await listingFormPage.publishBottom();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        const listingId = wpCli.getLatestPostId(userId);
        wpCli.trackPost(listingId);
        expect(listingId).toBe(urlListingId);

        const userListings = wpCli.getUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCli.getPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCli.getPostField(listingId, 'post_status')).toBe('publish');
        expect(wpCli.getPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCli.getPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCli.getPostMeta(listingId, 'description')).toBe(listing.description.trim());
        expect(wpCli.getPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCli.getPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCli.getPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCli.getPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCli.getPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCli.getPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCli.getPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test('Create listing using top publish button', async ({ listingFormPage, wpCli }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/files/test-image.png');
        await listingFormPage.publishTop();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        const listingId = wpCli.getLatestPostId(userId);
        wpCli.trackPost(listingId);
        expect(listingId).toBe(urlListingId);

        const userListings = wpCli.getUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCli.getPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCli.getPostField(listingId, 'post_status')).toBe('publish');
        expect(wpCli.getPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCli.getPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCli.getPostMeta(listingId, 'description')).toBe(listing.description.trim());
        expect(wpCli.getPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCli.getPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCli.getPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCli.getPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCli.getPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCli.getPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCli.getPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test('Create listing using bottom save draft button', async ({ listingFormPage, wpCli }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/files/test-image.png');
        await listingFormPage.saveDraftBottom();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        const listingId = wpCli.getLatestPostId(userId, 'listing', 'draft');
        wpCli.trackPost(listingId);
        expect(listingId).toBe(urlListingId);

        const userListings = wpCli.getUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCli.getPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCli.getPostField(listingId, 'post_status')).toBe('draft');
        expect(wpCli.getPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCli.getPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCli.getPostMeta(listingId, 'description')).toBe(listing.description.trim());
        expect(wpCli.getPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCli.getPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCli.getPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCli.getPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCli.getPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCli.getPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCli.getPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test('Create listing using top save draft button', async ({ listingFormPage, wpCli }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/files/test-image.png');
        await listingFormPage.saveDraftTop();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        const listingId = wpCli.getLatestPostId(userId, 'listing', 'draft');
        wpCli.trackPost(listingId);
        expect(listingId).toBe(urlListingId);

        const userListings = wpCli.getUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCli.getPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCli.getPostField(listingId, 'post_status')).toBe('draft');
        expect(wpCli.getPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCli.getPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCli.getPostMeta(listingId, 'description')).toBe(listing.description.trim());
        expect(wpCli.getPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCli.getPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCli.getPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCli.getPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCli.getPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCli.getPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCli.getPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test.skip('Create listing with every single field filled out', async ({ listingFormPage }) => { });
    test.skip('Create listing with large image upload', async ({ listingFormPage }) => { });
});
