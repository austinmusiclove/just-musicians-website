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

    test('Change view access to edit via share modal dropdown', async ({ wpCli, singleApplicationPage }) => {
        wpCli.grantAccess(otherUserId, applicationId, 'view', 'application');

        // Open share modal — the access list should load with other user's entry
        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await singleApplicationPage.shareBtn.click();
        await expect(singleApplicationPage.shareModal).toBeVisible();
        const accessEntry = singleApplicationPage.shareAccessList.locator('li', { hasText: otherUser.email });
        await expect(accessEntry).toBeVisible();
        await expect(accessEntry.locator('select')).toHaveValue('view');

        // Change to edit via the dropdown
        const responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'POST'
        );
        await accessEntry.locator('select').selectOption('edit');
        await responsePromise;

        // Verify edit access
        await expect(accessEntry.locator('select')).toHaveValue('edit');
        const dbAccess = wpCli.queryAccess(otherUserId, 'application', applicationId);
        expect(dbAccess).toBeTruthy();
        expect(dbAccess.access_type).toBe('edit');
    });

    test('Change edit access to view via share modal dropdown', async ({ wpCli, singleApplicationPage }) => {
        wpCli.grantAccess(otherUserId, applicationId, 'edit', 'application');

        // Open share modal — the access list should load with other user's entry
        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await singleApplicationPage.shareBtn.click();
        await expect(singleApplicationPage.shareModal).toBeVisible();
        const accessEntry = singleApplicationPage.shareAccessList.locator('li', { hasText: otherUser.email });
        await expect(accessEntry).toBeVisible();
        await expect(accessEntry.locator('select')).toHaveValue('edit');

        // Change to view via the dropdown
        const responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'POST'
        );
        await accessEntry.locator('select').selectOption('view');
        await responsePromise;

        // Verify view access
        await expect(accessEntry.locator('select')).toHaveValue('view');
        const dbAccess = wpCli.queryAccess(otherUserId, 'application', applicationId);
        expect(dbAccess).toBeTruthy();
        expect(dbAccess.access_type).toBe('view');
    });

    test('Revoke view access via share modal dropdown', async ({ wpCli, singleApplicationPage }) => {
        wpCli.grantAccess(otherUserId, applicationId, 'view', 'application');

        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);

        // Open share modal — the access list should load with other user's entry
        await singleApplicationPage.shareBtn.click();
        await expect(singleApplicationPage.shareModal).toBeVisible();
        const accessEntry = singleApplicationPage.shareAccessList.locator('li', { hasText: otherUser.email });
        await expect(accessEntry).toBeVisible();

        // Revoke via the dropdown
        const responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'DELETE'
        );
        await accessEntry.locator('select').selectOption('revoke');
        await responsePromise;

        // The access should be gone
        await expect(accessEntry).not.toBeVisible();
        const dbAccess = wpCli.queryAccess(otherUserId, 'application', applicationId);
        expect(dbAccess).toBeNull();
    });

    test('Revoke edit access via share modal dropdown', async ({ wpCli, singleApplicationPage }) => {
        wpCli.grantAccess(otherUserId, applicationId, 'edit', 'application');

        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);

        // Open share modal — the access list should load with other user's entry
        await singleApplicationPage.shareBtn.click();
        await expect(singleApplicationPage.shareModal).toBeVisible();
        const accessEntry = singleApplicationPage.shareAccessList.locator('li', { hasText: otherUser.email });
        await expect(accessEntry).toBeVisible();

        // Revoke via the dropdown
        const responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'DELETE'
        );
        await accessEntry.locator('select').selectOption('revoke');
        await responsePromise;

        // The access should be gone
        await expect(accessEntry).not.toBeVisible();
        const dbAccess = wpCli.queryAccess(otherUserId, 'application', applicationId);
        expect(dbAccess).toBeNull();
    });
});
