import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';

test.describe('Access - Applications Page', () => {

    let applicationAuthor;
    let noApplicationUser;
    let applicationId;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthor = createUser();
        const applicationAuthorId = wpCli.createUser(applicationAuthor);

        noApplicationUser = createUser();
        wpCli.createUser(noApplicationUser);

        applicationId = createApplicationPost({ authorId: applicationAuthorId });
    });

    test('Application author sees their application on applications page', async ({ wpCli, applicationsPage }) => {
        await applicationsPage.login(applicationAuthor.email, applicationAuthor.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        const expectedTitle = wpCli.getPostMeta(applicationId, 'title');
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(expectedTitle);
    });
    test('User cannot see applications belonging to other users on applications page', async ({ wpCli, applicationsPage }) => {
        await applicationsPage.login(noApplicationUser.email, noApplicationUser.password);
        await applicationsPage.navigate();
        await applicationsPage.waitForResults();
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
    });
    test('Logged out user sees sign-up-to-access instead of applications', async ({ applicationsPage }) => {
        await applicationsPage.navigate();
        await expect(applicationsPage.applicationCards).toHaveCount(0);
    });
});
