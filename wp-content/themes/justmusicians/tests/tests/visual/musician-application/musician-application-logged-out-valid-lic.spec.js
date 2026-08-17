import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';
import { createTmpCodePost } from '../../../data/factories/tmp_code_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Visual - Musician Application - Logged out - Valid lic', () => {

    let applicationAuthorUser;
    let applicationAuthorUserId;
    let applicationId;
    let tmpCodeId;
    let lic;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });

        const tmpCode = createTmpCodePost({ authorId: applicationAuthorUserId });
        tmpCodeId = tmpCode.id;
        lic = tmpCode.code;
    });

    test.afterAll(async () => {
        if (tmpCodeId)             { wpCliDeletePost(tmpCodeId); }
        if (applicationId)         { wpCliDeletePost(applicationId); }
        if (applicationAuthorUser) { wpCliDeleteUser(applicationAuthorUser.email); }
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
