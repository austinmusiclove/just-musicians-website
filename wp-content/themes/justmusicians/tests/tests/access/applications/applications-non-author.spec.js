import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';

test.describe.serial('E2E - Share Application Access', () => {

    let applicationAuthor, accessUser;
    let applicationAuthorId, accessUserId;
    let applicationId, applicationSlug, applicationData;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthor = createUser();
        applicationAuthorId = wpCli.createUser(applicationAuthor);

        accessUser = createUser();
        accessUserId = wpCli.createUser(accessUser);

        applicationData = createApplicationPostData({ authorId: applicationAuthorId })
        applicationId = wpCli.createPost(applicationData);
        applicationSlug = wpCli.getPostField(applicationId, 'post_name');

        // Ensure author has owner access (transition_post_status may not fire via wp-cli)
        wpCli.grantAccess(applicationAuthorId, applicationId, 'owner', 'application');
    });

    test('Author has owner access, others have no access', async ({ wpCli, applicationsPage, singleApplicationPage }) => {
        // Author can see application card on applications page
        await applicationsPage.login(applicationAuthor.email, applicationAuthor.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationData.title);

        // Author can see single application page with all buttons
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.editBtn).toBeVisible();
        await expect(singleApplicationPage.shareBtn).toBeVisible();
        await expect(singleApplicationPage.deleteBtn).toBeVisible();
        await applicationsPage.logout();

        // non author user has no access - cannot see card on applications page
        await applicationsPage.login(accessUser.email, accessUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).not.toHaveURL(/\/application\//);
        await applicationsPage.logout();
    });

    test('User with view access can see application but cannot edit/share/delete', async ({ wpCli, applicationsPage, singleApplicationPage }) => {
        // Grant view access to accessUser
        wpCli.grantAccess(accessUserId, applicationId, 'view', 'application');

        // accessUser can see application card on applications page
        await applicationsPage.login(accessUser.email, accessUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationData.title);

        // accessUser can see single application page
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).toHaveURL(/\/application\//);

        // accessUser cannot see edit, share, or delete buttons
        await expect(singleApplicationPage.editBtn).not.toBeVisible();
        await expect(singleApplicationPage.shareBtn).not.toBeVisible();
        await expect(singleApplicationPage.deleteBtn).not.toBeVisible();
    });

    test('User with edit access can see application and edit but cannot share/delete', async ({ wpCli, applicationsPage, singleApplicationPage }) => {
        // Grant edit access to accessUser
        wpCli.grantAccess(accessUserId, applicationId, 'edit', 'application');

        // accessUser can see application card on applications page
        await applicationsPage.login(accessUser.email, accessUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationData.title);

        // accessUser can see single application page
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).toHaveURL(/\/application\//);

        // accessUser can see edit button but not share or delete
        await expect(singleApplicationPage.editBtn).toBeVisible();
        await expect(singleApplicationPage.shareBtn).not.toBeVisible();
        await expect(singleApplicationPage.deleteBtn).not.toBeVisible();
    });
});
