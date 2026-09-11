import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('Collections - Reorder collection items', () => {

    let author, authorId;
    let listing1, listing2;
    let collectionId, collectionSlug;

    test.beforeEach(async ({ wpCli }) => {
        author = createUser();
        authorId = wpCli.createUser(author);

        listing1 = wpCli.createListing(createListingPostData({ authorId }));
        listing2 = wpCli.createListing(createListingPostData({ authorId }));

        collectionId = wpCli.createCollection({
            title: `Reorder ${author.email}`,
            authorId,
            listingIds: [listing1, listing2],
        });
        collectionSlug = wpCli.getPostField(collectionId, 'post_name');
    });

    test('drag a card handle to reorder items and the new order persists', async ({ wpCli, themePage, singleCollectionPage }) => {
        test.skip(themePage.isMobile, 'This test is for Desktop only.');

        await themePage.navigate('/');
        await themePage.login(author.email, author.password);
        await themePage.expectLoggedInPage();

        // Open the collection page and wait for the cards to load
        await singleCollectionPage.open(collectionSlug);

        await singleCollectionPage.expectCardOrdering([listing1, listing2]);

        // Turn on reorder mode to make the drag handles visible
        await singleCollectionPage.enableReorderMode();

        // Drag the first card's handle below the second card
        await singleCollectionPage.dragHandle(listing1, {
            afterListingId: listing2,
            waitForUrl: resp => resp.url().includes(`wp-html/v1/collections/${collectionId}/reorder`)
                && resp.request().method() === 'POST',
        });

        // The list re-renders in the new order
        await singleCollectionPage.expectCardOrdering([listing2, listing1]);

        // And the collection's stored listings order is updated
        await expect.poll(() => wpCli.getPostMetaJson(collectionId, 'listings')).toEqual([String(listing2), String(listing1)]);

        // The drag handles survive the re-render
        await expect(singleCollectionPage.reorderHandles).toHaveCount(2);
    });
});
