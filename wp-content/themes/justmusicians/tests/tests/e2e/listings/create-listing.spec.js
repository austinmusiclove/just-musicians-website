import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createListing } from '../../../data/listing_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliGetUserMeta, wpCliGetLatestPostId, wpCliGetPostField, wpCliGetPostMeta, wpCliGetPostThumbnailId, wpCliGetLocationByPostalCode, wpCliDeleteUser, wpCliDeletePost } from '../../../data/wp_cli.js';

test.describe('E2E - Create Listing', () => {

    let testUser;
    let userId;
    let listingId;

    test.beforeEach(async () => {
        testUser = createUser();
        wpCliCreateUser(testUser);
        userId = wpCliGetUserId(testUser.email);
    });

    test.afterEach(async () => {
        if (listingId) { wpCliDeletePost(listingId); }
        if (testUser)  { wpCliDeleteUser(testUser.email); }
    });

    test('Create listing using bottom publish button', async ({ listingFormPage }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.navigate('/');
        await listingFormPage.login(testUser.email, testUser.password);
        await listingFormPage.navigate('/listing-form/');

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/test-image.png');
        await listingFormPage.publishBottom();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        listingId = wpCliGetLatestPostId(userId);
        expect(listingId).toBe(urlListingId);

        await expect(listingFormPage.performerName).toHaveValue(listing.name);
        await expect(listingFormPage.description).toHaveValue(listing.description);

        const userListings = wpCliGetUserMeta(userId, 'listings');
        expect(userListings).toContain(Number(listingId));

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('publish');
        expect(wpCliGetPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCliGetPostMeta(listingId, 'description')).toBe(listing.description);
        expect(wpCliGetPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCliGetPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCliGetPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCliGetPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCliGetPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCliGetPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCliGetPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test('Create listing using top publish button', async ({ listingFormPage }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.navigate('/');
        await listingFormPage.login(testUser.email, testUser.password);
        await listingFormPage.navigate('/listing-form/');

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/test-image.png');
        await listingFormPage.publishTop();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        listingId = wpCliGetLatestPostId(userId);
        expect(listingId).toBe(urlListingId);

        await expect(listingFormPage.performerName).toHaveValue(listing.name);
        await expect(listingFormPage.description).toHaveValue(listing.description);

        const userListings = wpCliGetUserMeta(userId, 'listings');
        expect(userListings).toContain(Number(listingId));

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('publish');
        expect(wpCliGetPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCliGetPostMeta(listingId, 'description')).toBe(listing.description);
        expect(wpCliGetPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCliGetPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCliGetPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCliGetPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCliGetPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCliGetPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCliGetPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test.skip('Create listing as user with existing listings', async ({ listingFormPage }) => { }); // Make sure listing is added to user listings correctly and the old listing is still there
    test.skip('Create listing with every single field filled out', async ({ listingFormPage }) => { });
});
