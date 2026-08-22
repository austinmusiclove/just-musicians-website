import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplication } from '../../../data/factories/application_factory.js';

test.describe('E2E - Create Application', () => {

    let testUser;
    let userId;

    test.beforeEach(async ({ singleApplicationPage, wpCli }) => {
        testUser = createUser();
        userId = wpCli.createUser(testUser);

        applicationId = createApplicationPost({
            authorId: userId,
            overrides: createApplication(),
        });
        wpCli.trackPost(applicationId);

        // Add 3 applicants
        // One of them has no account
        // One has verified email account
        // one has not verified email account

        await singleApplicationPage.login(testUser.email, testUser.password);
        await singleApplicationPage.login(testUser.email, testUser.password);
        await singleApplicationPage.navigate('/application-form/');
    });

    test.skip('Export applicants to csv', async ({ singleApplicationPage, mailpit, wpCli }) => {
        // Update create application post pattern
    });
});
