import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';


test.describe('Visual - Applications - One applicaiton', () => {

    let applicationData;

    test.beforeEach(async ({ wpCli, applicationsPage }) => {
        const applicationAuthorUser = createUser();
        const applicationAuthorUserId = wpCli.createUser(applicationAuthorUser);
        applicationData = createApplicationPostData({ authorId: applicationAuthorUserId });
        wpCli.createPost(applicationData);

        await applicationsPage.login(applicationAuthorUser.email, applicationAuthorUser.password);
        await applicationsPage.navigate('/applications/');
    });

    test('User\'s application is displayed', async ({ applicationsPage }) => {
        await applicationsPage.waitForResults();
        const cards = await applicationsPage.applicationCards.all();
        expect(cards).toHaveLength(1);
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationData.title);
    });

});
