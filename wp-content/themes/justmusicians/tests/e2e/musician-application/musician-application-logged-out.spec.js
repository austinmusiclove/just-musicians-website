import { expect } from '@playwright/test';
import { test } from '../../fixtures/fixtures.js';
import { createUser } from '../../data/user_factory.js';
import { createApplicationPost } from '../../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliDeletePost } from '../../data/wp_cli.js';


test.describe('Musician Application', () => {

    let applicationAuthorUser;
    let applicationAuthorUserId;
    let applicationId;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });

    });

    test.afterAll(async () => {
        if (applicationId)         { wpCliDeletePost(applicationId); }
        if (applicationAuthorUser) { wpCliDeleteUser(applicationAuthorUser.email); }
    });

    test('not logged in user sees the listing form and no listing dropdown', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateByApplicationId(applicationId);
        await expect(musicianApplicationPage.applicationTitle).toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).toBeVisible();
        await expect(musicianApplicationPage.listingForm).toBeVisible();
        await expect(musicianApplicationPage.listingDropdown).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
    });

    test('not logged in user with an invalid lpc code sees the invalid link message', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateByApplicationId(applicationId, 'test');
        await expect(musicianApplicationPage.invalidLpc).toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).not.toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

});
