import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';
import { createTmpCodePostData } from '../../../data/factories/tmp_code_factory.js';


test.describe('Visual - Musician Application - Logged out - Valid lic', () => {

    let applicationAuthorUser;
    let applicationId;
    let tmpCodeId;
    let lic;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthorUser = createUser();
        const applicationAuthorUserId = wpCli.createUser(applicationAuthorUser);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });
        wpCli.trackPost(applicationId);

        const tmpCodeData = createTmpCodePostData({ authorId: applicationAuthorUserId });
        tmpCodeId = wpCli.createPost(tmpCodeData);
        lic = tmpCodeData.meta.code;
        wpCli.trackPost(tmpCodeId);
    });

    test('Displays successful submission content instead of the form', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateToApplication(applicationId, lic);
        await expect(musicianApplicationPage.successfulSubmissionAnon).toBeVisible();
        await expect(musicianApplicationPage.signupModalHeading).toHaveText('Sign up to complete your submission');
        await expect(musicianApplicationPage.signupModalHeading).not.toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).not.toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

});
