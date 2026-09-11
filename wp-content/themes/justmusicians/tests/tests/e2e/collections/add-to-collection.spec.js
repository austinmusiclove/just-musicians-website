import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('Collections - Add listing to collection', () => {

    let user, userId;

    test.beforeEach(async ({ collectionsPage, wpCli }) => {
        user = createUser();
        userId = wpCli.createUser(user);
        await collectionsPage.login(user.email, user.password);
    });

    test('add a listing to an existing collection using the favorites button', async ({ singleListingPage, wpCli }) => {
        const musicianId = wpCli.createUser(createUser());
        const listingId = wpCli.createListing(createListingPostData({ authorId: musicianId }));
        const listingPermalink = wpCli.getPostUrl(listingId);

        const otherListingId = wpCli.createListing(createListingPostData({ authorId: musicianId }));
        const collectionName = `Saved Collection ${Date.now()}`;
        const collectionId = wpCli.createCollection({
            title: collectionName,
            authorId: userId,
            listingIds: [otherListingId],
        });

        await singleListingPage.navigate(listingPermalink);
        await singleListingPage.addListingToCollection(listingId, collectionId, collectionName);

        expect(wpCli.getPostMetaJson(collectionId, 'listings').map(String)).toContain(String(listingId));
        expect(wpCli.getPostMetaJson(collectionId, 'listings').length).toBe(2);
    });
});