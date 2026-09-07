import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('Visual - Collections - Reorder button', () => {

    let author, authorId;
    let collectionSlug;

    test.beforeEach(async ({ wpCli }) => {
        author = createUser();
        authorId = wpCli.createUser(author);

        const listing1 = wpCli.createListing(createListingPostData({ authorId }));
        const listing2 = wpCli.createListing(createListingPostData({ authorId }));

        const collectionId = wpCli.createCollection({
            title: `Reorder Button ${author.email}`,
            authorId,
            listingIds: [listing1, listing2],
        });
        collectionSlug = wpCli.getPostField(collectionId, 'post_name');
    });

    test('reorder button is not visible on mobile', async ({ themePage, singleCollectionPage }) => {
        test.skip(!themePage.isMobile, 'This test is for Mobile only.');

        await themePage.navigate('/');
        await themePage.login(author.email, author.password);
        await themePage.expectLoggedInPage();

        await singleCollectionPage.open(collectionSlug);

        await expect(singleCollectionPage.reorderToggle).toBeHidden();
    });

    test('toggling reorder mode shows and hides the reorder handles', async ({ themePage, singleCollectionPage }) => {
        test.skip(themePage.isMobile, 'This test is for Desktop only.');

        await themePage.navigate('/');
        await themePage.login(author.email, author.password);
        await themePage.expectLoggedInPage();

        await singleCollectionPage.open(collectionSlug);

        // Handles start hidden
        await expect(singleCollectionPage.reorderHandles.first()).toBeHidden();

        // Toggle reorder mode on to show them
        await singleCollectionPage.enableReorderMode();
        await expect(singleCollectionPage.reorderHandles).toHaveCount(2);
        await expect(singleCollectionPage.reorderToggle).toHaveText('Done Reordering');

        // Toggle reorder mode off to hide them
        await singleCollectionPage.disableReorderMode();
        await expect(singleCollectionPage.reorderHandles.first()).toBeHidden();
        await expect(singleCollectionPage.reorderToggle).toHaveText('Reorder');
    });
});