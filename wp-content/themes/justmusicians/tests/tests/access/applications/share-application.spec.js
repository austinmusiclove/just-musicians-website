import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';

test.describe('Access - Share Application via Share Button', () => {

    let applicationAuthor, otherUser;
    let applicationAuthorId, otherUserId;
    let applicationId, applicationSlug;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthor = createUser();
        applicationAuthorId = wpCli.createUser(applicationAuthor);

        otherUser = createUser();
        otherUserId = wpCli.createUser(otherUser);

        applicationId = wpCli.createPost(createApplicationPostData({ authorId: applicationAuthorId }));
        applicationSlug = wpCli.getPostField(applicationId, 'post_name');
    });

    test('Share view access via share button', async ({ wpCli, singleApplicationPage }) => {
        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await singleApplicationPage.shareAccess(otherUser.email, 'view');

        // The other user should appear in the access list with view access selected
        const accessEntry = singleApplicationPage.shareAccessList.locator('li', { hasText: otherUser.email });
        await expect(accessEntry).toBeVisible();
        await expect(accessEntry.locator('select')).toHaveValue('view');

        // Verify in database
        const dbAccess = wpCli.queryAccess(otherUserId, 'application', applicationId);
        expect(dbAccess).toBeTruthy();
        expect(dbAccess.access_type).toBe('view');
    });

    test('Share edit access via share button', async ({ wpCli, singleApplicationPage }) => {
        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await singleApplicationPage.shareAccess(otherUser.email, 'edit');

        // The other user should appear in the access list with edit access selected
        const accessEntry = singleApplicationPage.shareAccessList.locator('li', { hasText: otherUser.email });
        await expect(accessEntry).toBeVisible();
        await expect(accessEntry.locator('select')).toHaveValue('edit');

        // Verify in database
        const dbAccess = wpCli.queryAccess(otherUserId, 'application', applicationId);
        expect(dbAccess).toBeTruthy();
        expect(dbAccess.access_type).toBe('edit');
    });
});
