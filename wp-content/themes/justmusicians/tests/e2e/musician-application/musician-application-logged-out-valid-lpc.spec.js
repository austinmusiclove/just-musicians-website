import { expect } from '@playwright/test';
import { test } from '../../fixtures/fixtures.js';
import { createUser } from '../../data/user_factory.js';
import { createApplicationPost } from '../../data/application_factory.js';
import { createTmpCodePost } from '../../data/tmp_code_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliDeletePost } from '../../data/wp_cli.js';


test.describe('Musician Application valid lpc logged out', () => {

    let applicationAuthorUser;
    let applicationAuthorUserId;
    let applicationId;
    let tmpCodeId;
    let lpc;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });

        const tmpCode = createTmpCodePost({ authorId: applicationAuthorUserId });
        tmpCodeId = tmpCode.id;
        lpc = tmpCode.code;
    });

    test.afterAll(async () => {
        if (tmpCodeId)             { wpCliDeletePost(tmpCodeId); }
        if (applicationId)         { wpCliDeletePost(applicationId); }
        if (applicationAuthorUser) { wpCliDeleteUser(applicationAuthorUser.email); }
    });

    test('sees the successful submission content and sign up modal', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateByApplicationId(applicationId, lpc);
        await expect(musicianApplicationPage.successfulSubmissionAnon).toBeVisible();
        await expect(musicianApplicationPage.signupModalHeading).toHaveText('Sign up to complete your submission');
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).not.toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

});
