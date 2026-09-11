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

    test('reorders the 11th listing to the 10th position in a collection of 12 listings', async ({ wpCli, themePage, singleCollectionPage, waitForScrollToSettle }) => {
        test.slow();
        test.skip(themePage.isMobile, 'This test is for Desktop only.');

        const listingIds = [];
        for (let i = 0; i < 12; i++) {
            listingIds.push(wpCli.createListing(createListingPostData({ authorId })));
        }

        const largeCollectionId = wpCli.createCollection({
            title: `Reorder Large ${author.email}`,
            authorId,
            listingIds,
        });
        const largeCollectionSlug = wpCli.getPostField(largeCollectionId, 'post_name');

        await themePage.navigate('/');
        await themePage.login(author.email, author.password);
        await themePage.expectLoggedInPage();

        await singleCollectionPage.open(largeCollectionSlug);

        // The collection page paginates listings 10 at a time
        await expect(singleCollectionPage.cards).toHaveCount(10);

        // Reveal the last card of page one to trigger the infinite scroll load of the rest
        await singleCollectionPage.cards.nth(9).scrollIntoViewIfNeeded();
        await expect(singleCollectionPage.cards).toHaveCount(12);

        await singleCollectionPage.expectCardOrdering(listingIds);

        await singleCollectionPage.enableReorderMode();

        // Bring the 11th card's handle on screen so its drag target coordinates are in view
        await singleCollectionPage.reorderHandle(listingIds[10]).scrollIntoViewIfNeeded();
        await waitForScrollToSettle();

        // Drag the 11th card's handle onto the 10th card's handle
        const eleventh = listingIds[10];
        const tenth = listingIds[9];
        await singleCollectionPage.dragHandle(eleventh, {
            afterListingId: tenth,
            waitForUrl: resp => resp.url().includes(`wp-html/v1/collections/${largeCollectionId}/reorder`) && resp.request().method() === 'POST',
        });

        const expectedOrder = [...listingIds.slice(0, 9), eleventh, tenth, listingIds[11]];

        // The list reflects the new order
        await singleCollectionPage.expectCardOrdering(expectedOrder);

        // And the collection's stored listings order is updated
        await expect.poll(() => wpCli.getPostMetaJson(largeCollectionId, 'listings')).toEqual(expectedOrder.map(String));

        // The drag handles survive the re-render
        await expect(singleCollectionPage.reorderHandles).toHaveCount(12);
    });
});
