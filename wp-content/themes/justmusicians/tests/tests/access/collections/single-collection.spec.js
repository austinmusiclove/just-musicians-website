import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

const grantedAccessSubject = (siteUrl, recipient, title) => `(${siteUrl} ${recipient}) You have been granted access: ${title}`;

test.describe('Access - Share Collection via Share Button', () => {

    let collectionAuthor, viewerUser, stranger;
    let collectionAuthorId, viewerUserId, strangerUserId;
    let listingId, collectionId, collectionSlug, collectionTitle;

    test.beforeEach(async ({ wpCli }) => {
        collectionAuthor = createUser();
        collectionAuthorId = wpCli.createUser(collectionAuthor);

        viewerUser = createUser();
        viewerUserId = wpCli.createUser(viewerUser);

        stranger = createUser();
        strangerUserId = wpCli.createUser(stranger);

        const musicianId = wpCli.createUser(createUser());
        listingId = wpCli.createListing(createListingPostData({ authorId: musicianId }));

        collectionTitle = `Shared Collection ${Date.now()}`;
        collectionId = wpCli.createCollection({ title: collectionTitle, authorId: collectionAuthorId, listingIds: [listingId] });
        collectionSlug = wpCli.getPostField(collectionId, 'post_name');
    });

    test('Recipient with view access can view the shared collection but not edit, share, or delete', async ({ wpCli, themePage, collectionsPage, singleCollectionPage }) => {
        wpCli.grantAccess(viewerUserId, collectionId, 'view', 'collection');

        await themePage.navigate('/');
        await themePage.login(viewerUser.email, viewerUser.password);
        await themePage.expectLoggedInPage();

        // The shared collection appears on the collections index
        await collectionsPage.navigate('/collections/');
        await collectionsPage.waitForCollectionCard(collectionTitle);
        await expect(collectionsPage.getDeleteCollectionButton(collectionId)).not.toBeVisible();

        // And can be opened via its direct link with the listings visible
        await singleCollectionPage.open(collectionSlug);
        await expect(singleCollectionPage.page).toHaveURL(/\/collection\//);
        await expect(singleCollectionPage.page.getByRole('heading', { name: collectionTitle })).toBeVisible();
        await expect(singleCollectionPage.cards).toHaveCount(1);
        await expect(singleCollectionPage.card(listingId)).toBeVisible();

        // A viewer cannot reorder (edit) or share
        await expect(singleCollectionPage.reorderToggle).not.toBeVisible();
        await expect(singleCollectionPage.shareBtn).not.toBeVisible();
    });

    test('Recipient with edit access can view and reorder the shared collection but not share', async ({ wpCli, themePage, singleCollectionPage }) => {
        wpCli.grantAccess(viewerUserId, collectionId, 'edit', 'collection');

        await themePage.navigate('/');
        await themePage.login(viewerUser.email, viewerUser.password);
        await themePage.expectLoggedInPage();

        await singleCollectionPage.open(collectionSlug);
        await expect(singleCollectionPage.page).toHaveURL(/\/collection\//);
        if (!themePage.isMobile) {
            await expect(singleCollectionPage.reorderToggle).toBeVisible();
        }
        await expect(singleCollectionPage.shareBtn).not.toBeVisible();
    });

    test('User without access is redirected away from the collection page', async ({ themePage, singleCollectionPage }) => {
        await themePage.navigate('/');
        await themePage.login(stranger.email, stranger.password);
        await themePage.expectLoggedInPage();

        await singleCollectionPage.page.goto(`/collection/${collectionSlug}`, { waitUntil: 'domcontentloaded' });
        await expect(singleCollectionPage.page).not.toHaveURL(/\/collection\//);
    });
});
