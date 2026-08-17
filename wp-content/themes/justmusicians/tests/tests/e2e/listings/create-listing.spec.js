import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createListing } from '../../../data/listing_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliGetUserMeta, wpCliGetLatestPostId, wpCliGetPostField, wpCliGetPostMeta, wpCliGetPostThumbnailId, wpCliGetLocationByPostalCode, wpCliDeleteUser, wpCliDeletePost } from '../../../data/wp_cli.js';

test.describe('E2E - Create Listing', () => {

    let testUser;
    let userId;
    let listingId;

    test.beforeEach(async ({ listingFormPage }) => {
        testUser = createUser();
        wpCliCreateUser(testUser);
        userId = wpCliGetUserId(testUser.email);

        await listingFormPage.login(testUser.email, testUser.password);
        await listingFormPage.navigate('/listing-form/');
    });

    test.afterEach(async () => {
        if (listingId) { wpCliDeletePost(listingId); }
        if (testUser)  { wpCliDeleteUser(testUser.email); }
    });

    test('Create listing using bottom publish button', async ({ listingFormPage }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/test-image.png');
        await listingFormPage.publishBottom();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        listingId = wpCliGetLatestPostId(userId);
        expect(listingId).toBe(urlListingId);

        const userListings = wpCliGetUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('publish');
        expect(wpCliGetPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCliGetPostMeta(listingId, 'description')).toBe(listing.description.trim());
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

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/test-image.png');
        await listingFormPage.publishTop();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        listingId = wpCliGetLatestPostId(userId);
        expect(listingId).toBe(urlListingId);

        const userListings = wpCliGetUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('publish');
        expect(wpCliGetPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCliGetPostMeta(listingId, 'description')).toBe(listing.description.trim());
        expect(wpCliGetPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCliGetPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCliGetPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCliGetPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCliGetPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCliGetPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCliGetPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test('Create listing using bottom save draft button', async ({ listingFormPage }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/test-image.png');
        await listingFormPage.saveDraftBottom();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        listingId = wpCliGetLatestPostId(userId, 'listing', 'draft');
        expect(listingId).toBe(urlListingId);

        const userListings = wpCliGetUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('draft');
        expect(wpCliGetPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCliGetPostMeta(listingId, 'description')).toBe(listing.description.trim());
        expect(wpCliGetPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCliGetPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCliGetPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCliGetPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCliGetPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCliGetPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCliGetPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test('Create listing using top save draft button', async ({ listingFormPage }) => {
        const listing = createListing({ zip: '78701' });

        await listingFormPage.fillMinimumFields(listing.name, listing.description, listing.zip, testUser.email);
        await listingFormPage.uploadCoverImage('tests/data/test-image.png');
        await listingFormPage.saveDraftTop();

        const urlListingId = await listingFormPage.waitForPublishRedirect();
        listingId = wpCliGetLatestPostId(userId, 'listing', 'draft');
        expect(listingId).toBe(urlListingId);

        const userListings = wpCliGetUserMeta(userId, 'listings');
        expect(userListings).toEqual([Number(listingId)]);

        expect(wpCliGetPostField(listingId, 'post_title')).toBe(listing.name);
        expect(wpCliGetPostField(listingId, 'post_status')).toBe('draft');
        expect(wpCliGetPostField(listingId, 'post_author')).toBe(userId);
        expect(wpCliGetPostMeta(listingId, 'name')).toBe(listing.name);
        expect(wpCliGetPostMeta(listingId, 'description')).toBe(listing.description.trim());
        expect(wpCliGetPostMeta(listingId, 'email')).toBe(testUser.email);
        expect(wpCliGetPostMeta(listingId, 'zip_code')).toBe(listing.zip);
        expect(wpCliGetPostMeta(listingId, 'verified')).toBeNull();
        expect(wpCliGetPostMeta(listingId, 'city')).toBe('Austin');
        expect(wpCliGetPostMeta(listingId, 'state')).toBe('Texas');

        const thumbnailId = wpCliGetPostThumbnailId(listingId);
        expect(thumbnailId).toBeTruthy();
        expect(wpCliGetPostField(thumbnailId, 'post_type')).toBe('attachment');
    });

    test.skip('Create listing with every single field filled out', async ({ listingFormPage }) => { });
    test.skip('Create listing with large image upload', async ({ listingFormPage }) => { });
});
