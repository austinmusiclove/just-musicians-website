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

    test('Owner can share view access via the share button', async ({ wpCli, mailpit, themePage, singleCollectionPage }) => {
        await themePage.navigate('/');
        await themePage.login(collectionAuthor.email, collectionAuthor.password);
        await themePage.expectLoggedInPage();

        await singleCollectionPage.open(collectionSlug);
        await singleCollectionPage.shareAccess(viewerUser.email, 'view');

        // The other user should appear in the access list with view access selected
        const accessEntry = singleCollectionPage.shareAccessList.locator('li', { hasText: viewerUser.email });
        await expect(accessEntry).toBeVisible();
        await expect(accessEntry.locator('select')).toHaveValue('view');

        // Verify in database
        const dbAccess = wpCli.queryAccess(viewerUserId, 'collection', collectionId);
        expect(dbAccess).toBeTruthy();
        expect(dbAccess.access_type).toBe('view');

        // A new grant emails the existing user with the granted access type
        const subject = grantedAccessSubject(mailpit.siteUrl, viewerUser.email, collectionTitle);
        const accessEmail = await mailpit.findEmailBySubject(subject);
        expect(accessEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(accessEmail.ID)).toContain('view access');
    });

    test('Owner can share edit access via the share button', async ({ wpCli, mailpit, themePage, singleCollectionPage }) => {
        await themePage.navigate('/');
        await themePage.login(collectionAuthor.email, collectionAuthor.password);
        await themePage.expectLoggedInPage();

        await singleCollectionPage.open(collectionSlug);
        await singleCollectionPage.shareAccess(viewerUser.email, 'edit');

        // The other user should appear in the access list with edit access selected
        const accessEntry = singleCollectionPage.shareAccessList.locator('li', { hasText: viewerUser.email });
        await expect(accessEntry).toBeVisible();
        await expect(accessEntry.locator('select')).toHaveValue('edit');

        // Verify in database
        const dbAccess = wpCli.queryAccess(viewerUserId, 'collection', collectionId);
        expect(dbAccess).toBeTruthy();
        expect(dbAccess.access_type).toBe('edit');

        // A new grant emails the existing user with the granted access type
        const subject = grantedAccessSubject(mailpit.siteUrl, viewerUser.email, collectionTitle);
        const accessEmail = await mailpit.findEmailBySubject(subject);
        expect(accessEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(accessEmail.ID)).toContain('edit access');
    });

});
