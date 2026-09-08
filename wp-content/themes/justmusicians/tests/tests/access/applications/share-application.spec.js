import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';

const grantedAccessSubject = (siteUrl, recipient, title) => `(${siteUrl} ${recipient}) You have been granted access: ${title}`;

test.describe('Access - Share Application via Share Button', () => {

    let applicationAuthor, otherUser;
    let applicationAuthorId, otherUserId;
    let applicationId, applicationSlug, applicationTitle;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthor = createUser();
        applicationAuthorId = wpCli.createUser(applicationAuthor);

        otherUser = createUser();
        otherUserId = wpCli.createUser(otherUser);

        const applicationData = createApplicationPostData({ authorId: applicationAuthorId });
        applicationId = wpCli.createPost(applicationData);
        applicationSlug = wpCli.getPostField(applicationId, 'post_name');
        applicationTitle = applicationData.title;
    });

    test('Share view access via share button', async ({ wpCli, mailpit, singleApplicationPage }) => {
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

        // A new grant emails the existing user with the granted access type
        const subject = grantedAccessSubject(mailpit.siteUrl, otherUser.email, applicationTitle);
        const accessEmail = await mailpit.findEmailBySubject(subject);
        expect(accessEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(accessEmail.ID)).toContain('view access');
    });

    test('Share edit access via share button', async ({ wpCli, mailpit, singleApplicationPage }) => {
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

        // A new grant emails the existing user with the granted access type
        const subject = grantedAccessSubject(mailpit.siteUrl, otherUser.email, applicationTitle);
        const accessEmail = await mailpit.findEmailBySubject(subject);
        expect(accessEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(accessEmail.ID)).toContain('edit access');
    });

    test('Change view access to edit via share modal dropdown', async ({ wpCli, mailpit, singleApplicationPage }) => {
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

        // Changing an existing grant's type is not a new grant — no email should go out
        const subject = grantedAccessSubject(mailpit.siteUrl, otherUser.email, applicationTitle);
        expect(await mailpit.findEmailBySubject(subject)).toBeNull();
    });

    test('Change edit access to view via share modal dropdown', async ({ wpCli, mailpit, singleApplicationPage }) => {
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

        // Changing an existing grant's type is not a new grant — no email should go out
        const subject = grantedAccessSubject(mailpit.siteUrl, otherUser.email, applicationTitle);
        expect(await mailpit.findEmailBySubject(subject)).toBeNull();
    });

    test('Revoke view access via share modal dropdown', async ({ wpCli, mailpit, singleApplicationPage }) => {
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

        // Revoking is not a grant — no email should go out
        const subject = grantedAccessSubject(mailpit.siteUrl, otherUser.email, applicationTitle);
        expect(await mailpit.findEmailBySubject(subject)).toBeNull();
    });

    test('Revoke edit access via share modal dropdown', async ({ wpCli, mailpit, singleApplicationPage }) => {
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

        // Revoking is not a grant — no email should go out
        const subject = grantedAccessSubject(mailpit.siteUrl, otherUser.email, applicationTitle);
        expect(await mailpit.findEmailBySubject(subject)).toBeNull();
    });

    test('Full access lifecycle: none → view → edit → revoke → edit', async ({ wpCli, mailpit, singleApplicationPage }) => {
        const accessEntry = () => singleApplicationPage.shareAccessList.locator('li', { hasText: otherUser.email });
        const subject = grantedAccessSubject(mailpit.siteUrl, otherUser.email, applicationTitle);

        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);

        // 1. No access — other user not in access list
        await singleApplicationPage.shareBtn.click();
        await expect(singleApplicationPage.shareModal).toBeVisible();
        await expect(accessEntry()).not.toBeVisible();
        expect(await wpCli.queryAccess(otherUserId, 'application', applicationId)).toBeNull();

        // 2. Add view access (modal is already open)
        await singleApplicationPage.shareEmailInput.fill(otherUser.email);
        await singleApplicationPage.shareAccessType.selectOption('view');
        let responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'POST'
        );
        await singleApplicationPage.shareModal.getByRole('button', { name: 'Share' }).click();
        await responsePromise;
        await expect(accessEntry()).toBeVisible();
        await expect(accessEntry().locator('select')).toHaveValue('view');
        expect((await wpCli.queryAccess(otherUserId, 'application', applicationId)).access_type).toBe('view');

        // New grant → existing user receives a view-access email
        let accessEmail = await mailpit.findEmailBySubject(subject);
        expect(accessEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(accessEmail.ID)).toContain('view access');

        // 3. Change to edit via dropdown
        responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'POST'
        );
        await accessEntry().locator('select').selectOption('edit');
        await responsePromise;
        await expect(accessEntry().locator('select')).toHaveValue('edit');
        expect((await wpCli.queryAccess(otherUserId, 'application', applicationId)).access_type).toBe('edit');

        // 4. Revoke via dropdown
        responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'DELETE'
        );
        await accessEntry().locator('select').selectOption('revoke');
        await responsePromise;
        await expect(accessEntry()).not.toBeVisible();
        expect(await wpCli.queryAccess(otherUserId, 'application', applicationId)).toBeNull();

        // 5. Add back as edit access via share button (modal is still open)
        await singleApplicationPage.shareEmailInput.fill(otherUser.email);
        await singleApplicationPage.shareAccessType.selectOption('edit');
        responsePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'POST'
        );
        await singleApplicationPage.shareModal.getByRole('button', { name: 'Share' }).click();
        await responsePromise;
        await expect(accessEntry()).toBeVisible();
        await expect(accessEntry().locator('select')).toHaveValue('edit');
        expect((await wpCli.queryAccess(otherUserId, 'application', applicationId)).access_type).toBe('edit');

        // Re-granting after a revoke is a new grant → the newest email carries the edit access
        accessEmail = await mailpit.findEmailBySubject(subject);
        expect(accessEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(accessEmail.ID)).toContain('edit access');
    });
});
