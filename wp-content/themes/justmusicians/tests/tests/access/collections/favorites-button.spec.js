import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('Access - Favorites button collections list', () => {

    let trustedUser, otherAuthor;
    let trustedUserId;
    let listingId, listingPermalink;
    let ownedCollectionName, editCollectionName, viewCollectionName;

    test.beforeEach(async ({ wpCli, themePage, singleListingPage }) => {
        trustedUser = createUser();
        trustedUserId = wpCli.createUser(trustedUser);

        otherAuthor = createUser();
        const otherAuthorId = wpCli.createUser(otherAuthor);

        // The listing trustedUser will favorite on the single listing page
        const musicianId = wpCli.createUser(createUser());
        listingId = wpCli.createListing(createListingPostData({ authorId: musicianId }));
        listingPermalink = wpCli.getPostUrl(listingId);

        // Owner access: trustedUser owns this collection
        ownedCollectionName = `Owned ${Date.now()}`;
        const ownedCollectionId = wpCli.createCollection({ title: ownedCollectionName, authorId: trustedUserId });

        // Edit access: otherAuthor owns it, trustedUser can edit
        editCollectionName = `Edit ${Date.now()}`;
        const editCollectionId = wpCli.createCollection({ title: editCollectionName, authorId: otherAuthorId });
        wpCli.grantAccess(trustedUserId, editCollectionId, 'edit', 'collection');

        // View access: otherAuthor owns it, trustedUser can only view
        viewCollectionName = `View ${Date.now()}`;
        const viewCollectionId = wpCli.createCollection({ title: viewCollectionName, authorId: otherAuthorId });
        wpCli.grantAccess(trustedUserId, viewCollectionId, 'view', 'collection');

        await themePage.navigate('/');
        await themePage.login(trustedUser.email, trustedUser.password);
        await themePage.expectLoggedInPage();
        await singleListingPage.navigate(listingPermalink);
        await singleListingPage.addToFavorites(listingId);
    });

    test('collections the user owns appear in the favorites button list', async ({ singleListingPage }) => {
        await expect(singleListingPage.getFavoriteCollectionRow(listingId, ownedCollectionName)).toBeVisible();
    });

    test('collections shared with edit access appear in the favorites button list', async ({ singleListingPage }) => {
        await expect(singleListingPage.getFavoriteCollectionRow(listingId, editCollectionName)).toBeVisible();
    });

    test('collections shared with view access do not appear in the favorites button list', async ({ singleListingPage }) => {
        await expect(singleListingPage.getFavoriteCollectionRow(listingId, viewCollectionName)).toBeHidden();
        await expect(singleListingPage.favoritesPopup).toBeVisible();
    });
});
