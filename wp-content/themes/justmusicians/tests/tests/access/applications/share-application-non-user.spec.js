import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';

const invitedAccessSubject = (siteUrl, recipient, title) => `(${siteUrl} ${recipient}) You have been invited to access: ${title}`;

test.describe('Access - Share Application with Unregistered User', () => {

    let applicationAuthor, nonUser;
    let applicationAuthorId;
    let applicationId, applicationSlug, applicationTitle;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthor = createUser();
        applicationAuthorId = wpCli.createUser(applicationAuthor);

        // Intentionally not registered yet — the invite flow keys off the email alone
        nonUser = createUser();

        const applicationData = createApplicationPostData({ authorId: applicationAuthorId });
        applicationId = wpCli.createPost(applicationData);
        applicationSlug = wpCli.getPostField(applicationId, 'post_name');
        applicationTitle = applicationData.title;
    });

    test('invite unregistered user → register → can view → revoke → can no longer view', async ({ wpCli, mailpit, themePage, applicationsPage, singleApplicationPage }) => {
        const inviteSubject = invitedAccessSubject(mailpit.siteUrl, nonUser.email, applicationTitle);
        const accessEntry = () => singleApplicationPage.shareAccessList.locator('li', { hasText: nonUser.email });

        // 1. Author adds view access for an unregistered email
        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await singleApplicationPage.shareAccess(nonUser.email, 'view');

        // The invite shows in the access list as a pending entry with view access
        await expect(accessEntry()).toBeVisible();
        await expect(accessEntry().locator('select')).toHaveValue('view');

        // An invitation email goes to the unregistered email (the invite variant, not the grant variant)
        const inviteEmail = await mailpit.findEmailBySubject(inviteSubject);
        expect(inviteEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(inviteEmail.ID)).toContain('view access');
        expect(await mailpit.getEmailBody(inviteEmail.ID)).toContain('sign up');

        // 2. Log out the author (close the send modal first), then register the invited email
        await singleApplicationPage.shareModal.locator('.close-button').click();
        await singleApplicationPage.logout();
        await themePage.navigate('/');
        await themePage.registerUserSignupModal(nonUser);
        wpCli.trackUser(nonUser);
        await themePage.expectLoggedInPage();

        const nonUserId = wpCli.getUserId(nonUser.email);
        const dbAccess = wpCli.queryAccess(nonUserId, 'application', applicationId);
        expect(dbAccess).toBeTruthy();
        expect(dbAccess.access_type).toBe('view');

        // 3. The invited user can see the application on their applications page
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationTitle);

        // 4. The author logs back in and revokes the access
        await applicationsPage.logout();
        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await singleApplicationPage.shareBtn.click();
        await expect(singleApplicationPage.shareModal).toBeVisible();
        await expect(accessEntry()).toBeVisible();

        const revokePromise = singleApplicationPage.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'DELETE'
        );
        await accessEntry().locator('select').selectOption('revoke');
        await revokePromise;

        await expect(accessEntry()).not.toBeVisible();
        expect(await wpCli.queryAccess(nonUserId, 'application', applicationId)).toBeNull();

        // 5. The (now registered) user can no longer see the application
        await singleApplicationPage.shareModal.locator('.close-button').click();
        await singleApplicationPage.logout();
        await applicationsPage.login(nonUser.email, nonUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.page.locator('#results')).not.toContainText(applicationTitle);
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
    });
});