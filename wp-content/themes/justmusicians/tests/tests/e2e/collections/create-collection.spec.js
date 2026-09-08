import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('Collections - Create collection', () => {

    let user, userId;

    test.beforeEach(async ({ collectionsPage, wpCli }) => {
        user = createUser();
        userId = wpCli.createUser(user);
        await collectionsPage.login(user.email, user.password);
    });

    test('create a collection from the collections page using the Add button', async ({ collectionsPage, wpCli }) => {
        const collectionName = `My Collection ${Date.now()}`;

        await collectionsPage.navigate('/collections/');
        await collectionsPage.createCollection(collectionName);
        await expect(collectionsPage.getCollectionCard(collectionName)).toBeVisible();

        const collectionId = wpCli.getLatestPostId(userId, 'collection');
        wpCli.trackPost(collectionId);
        expect(collectionId).toBeTruthy();

        expect(wpCli.getPostField(collectionId, 'post_type')).toBe('collection');
        expect(wpCli.getPostField(collectionId, 'post_title')).toBe(collectionName);
        expect(wpCli.getPostField(collectionId, 'post_status')).toBe('publish');
        expect(wpCli.getPostField(collectionId, 'post_author')).toBe(String(userId));
        expect(wpCli.getPostMeta(collectionId, 'name')).toBe(collectionName);
        expect(wpCli.queryAccess(userId, 'collection', String(collectionId))?.access_type).toBe('owner');
    });

    test('create a collection from a single listing page using the create new collection option', async ({ singleListingPage, wpCli }) => {
        const musicianId = wpCli.createUser(createUser());
        const listingId = wpCli.createListing(createListingPostData({ authorId: musicianId }));
        const listingPermalink = wpCli.getPostUrl(listingId);

        const collectionName = `From Listing ${Date.now()}`;

        await singleListingPage.navigate(listingPermalink);
        await singleListingPage.createCollectionFromListing(listingId, collectionName);

        const collectionId = wpCli.getLatestPostId(userId, 'collection');
        wpCli.trackPost(collectionId);
        expect(collectionId).toBeTruthy();

        expect(wpCli.getPostField(collectionId, 'post_type')).toBe('collection');
        expect(wpCli.getPostField(collectionId, 'post_title')).toBe(collectionName);
        expect(wpCli.getPostField(collectionId, 'post_status')).toBe('publish');
        expect(wpCli.getPostField(collectionId, 'post_author')).toBe(String(userId));
        expect(wpCli.getPostMeta(collectionId, 'name')).toBe(collectionName);
        expect(wpCli.getPostMetaJson(collectionId, 'listings')).toEqual([String(listingId)]);
        expect(wpCli.queryAccess(userId, 'collection', String(collectionId))?.access_type).toBe('owner');
    });
});
