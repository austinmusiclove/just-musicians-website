import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';

test.describe.serial('E2E - Share Application Access', () => {

    let applicationAuthor, viewUser, editUser;
    let applicationAuthorId, viewUserId, editUserId;
    let applicationId, applicationSlug, applicationData;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthor = createUser();
        applicationAuthorId = wpCli.createUser(applicationAuthor);

        viewUser = createUser();
        viewUserId = wpCli.createUser(viewUser);

        editUser = createUser();
        editUserId = wpCli.createUser(editUser);

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
        await expect(applicationsPage.applicationCards.first().getCardTitle()).toBe(applicationData.title);

        // Author can see single application page with all buttons
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.editBtn).toBeVisible();
        await expect(singleApplicationPage.shareBtn).toBeVisible();
        await expect(singleApplicationPage.deleteBtn).toBeVisible();
        await applicationsPage.logout();

        // non author user has no access - cannot see card on applications page
        await applicationsPage.login(viewUser.email, viewUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).not.toHaveURL(/\/application\//);
        await applicationsPage.logout();
    });

    test('User with view access can see application but cannot edit/share/delete', async ({ wpCli, applicationsPage, singleApplicationPage }) => {
        // Grant view access to viewUser
        wpCli.grantAccess(viewUserId, applicationId, HM_ACCESS_TYPE_VIEW, 'application');

        // viewUser can see application card on applications page
        await applicationsPage.login(viewUser.email, viewUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await expect(applicationsPage.applicationCards.first().getCardTitle()).toBe(applicationData.title);

        // viewUser can see single application page
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).toHaveURL(/\/application\//);

        // viewUser cannot see edit, share, or delete buttons
        await expect(singleApplicationPage.editBtn).not.toBeVisible();
        await expect(singleApplicationPage.shareBtn).not.toBeVisible();
        await expect(singleApplicationPage.deleteBtn).not.toBeVisible();
    });

    test('User with edit access can see application and edit but cannot share/delete', async ({ wpCli, applicationsPage, singleApplicationPage }) => {
        // Grant edit access to editUser
        wpCli.grantAccess(editUserId, applicationId, HM_ACCESS_TYPE_VIEW, 'application');

        // editUser can see application card on applications page
        await applicationsPage.login(editUser.email, editUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await expect(applicationsPage.applicationCards.first().getCardTitle()).toBe(applicationData.title);

        // editUser can see single application page
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).toHaveURL(/\/application\//);

        // editUser can see edit button but not share or delete
        await expect(singleApplicationPage.editBtn).toBeVisible();
        await expect(singleApplicationPage.shareBtn).not.toBeVisible();
        await expect(singleApplicationPage.deleteBtn).not.toBeVisible();
    });
});
