import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('Collections - Remove listing from collection', () => {

    test('removes a listing from an existing collection with the favorites button on the single listing page', async ({ singleListingPage, collectionsPage, wpCli }) => {
        const musicianId = wpCli.createUser(createUser());
        const listingId = wpCli.createListing(createListingPostData({ authorId: musicianId }));
        const listingPermalink = wpCli.getPostUrl(listingId);

        const user = createUser();
        const userId = wpCli.createUser(user);
        const collectionName = `Collection ${Date.now()}`;
        const collectionId = wpCli.createCollection({ title: collectionName, authorId: userId, listingIds: [listingId] });
        wpCli.trackPost(collectionId);

        await collectionsPage.login(user.email, user.password);
        await singleListingPage.navigate(listingPermalink);

        await singleListingPage.removeListingFromCollection(listingId, collectionId, collectionName);

        await expect(singleListingPage.getFavoriteCollectionRow(listingId, collectionName).locator('button:visible').first()).toBeVisible();

        expect(wpCli.getUserMeta(userId, 'collections').map(String)).toContain(String(collectionId));
        expect(wpCli.getPostMetaJson(collectionId, 'listings')).not.toContain(String(listingId));
        expect(wpCli.getPostField(collectionId, 'post_status')).toBe('publish');
    });
});