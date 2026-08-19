import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';

test.describe('Access - Single Application Page', () => {

    let applicationAuthor, noApplicationUser;
    let applicationSlug;

    test.beforeEach(async ({ wpCli, singleApplicationPage }) => {
        applicationAuthor = createUser();
        wpCli.createUser(applicationAuthor);
        const applicationAuthorId = wpCli.getUserId(applicationAuthor.email);

        noApplicationUser = createUser();
        wpCli.createUser(noApplicationUser);

        const applicationId = createApplicationPost({ authorId: applicationAuthorId });
        applicationSlug = wpCli.getPostField(applicationId, 'post_name');
    });

    test('Application author can see single application page', async ({ wpCli, singleApplicationPage }) => {
        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.editBtn).toBeVisible();
    });
    test('User cannot see single application page of an application they did not author', async ({ wpCli, singleApplicationPage }) => {
        await singleApplicationPage.login(noApplicationUser.email, noApplicationUser.password);
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).not.toHaveURL(/\/application\//);
    });
    test('Logged out user cannot see single application page of an application', async ({ wpCli, singleApplicationPage }) => {
        await singleApplicationPage.navigateToApplication(applicationSlug);
        await expect(singleApplicationPage.page).not.toHaveURL(/\/application\//);
    });
});
