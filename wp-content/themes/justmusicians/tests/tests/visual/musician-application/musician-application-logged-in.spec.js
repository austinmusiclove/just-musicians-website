import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createApplicationPost } from '../../../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Visual - Musician Application - Logged in - No listings', () => {

    let applicationAuthorUser;
    let applicationAuthorUserId;
    let applicationId;
    let testUser;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });

        testUser = createUser();
        wpCliCreateUser(testUser);
    });

    test.afterAll(async () => {
        if (applicationId)         { wpCliDeletePost(applicationId); }
        if (applicationAuthorUser) { wpCliDeleteUser(applicationAuthorUser.email); }
        if (testUser)              { wpCliDeleteUser(testUser.email); }
    });

    test('Displays listing form and no listing dropdown', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigate('/');
        await musicianApplicationPage.login(testUser.email, testUser.password);
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.applicationTitle).toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).toBeVisible();
        await expect(musicianApplicationPage.listingForm).toBeVisible();
        await expect(musicianApplicationPage.listingDropdown).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
    });

    test('Displays invalid link message instead of the form when there is an invalid lic in the url', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigate('/');
        await musicianApplicationPage.login(testUser.email, testUser.password);
        await musicianApplicationPage.navigateToApplication(applicationId, 'test');
        await expect(musicianApplicationPage.invalidLic).toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).not.toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

});
