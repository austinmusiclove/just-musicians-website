import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplication, createApplicationPost } from '../../../data/factories/application_factory.js';


test.describe('Visual - Applications - One applicaiton', () => {

    let applicationTitle;

    test.beforeEach(async ({ wpCli, applicationsPage }) => {
        const applicationAuthorUser = createUser();
        wpCli.createUser(applicationAuthorUser);
        const applicationAuthorUserId = wpCli.getUserId(applicationAuthorUser.email);
        const application = createApplication();
        applicationTitle = application.title;
        const applicationId = createApplicationPost({ authorId: applicationAuthorUserId, overrides: application });
        wpCli.trackPost(applicationId);

        await applicationsPage.login(applicationAuthorUser.email, applicationAuthorUser.password);
        await applicationsPage.navigate('/applications/');
    });

    test('User\'s application is displayed', async ({ applicationsPage }) => {
        await applicationsPage.waitForResults();
        const cards = await applicationsPage.applicationCards.all();
        expect(cards).toHaveLength(1);
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationTitle);
    });

});
