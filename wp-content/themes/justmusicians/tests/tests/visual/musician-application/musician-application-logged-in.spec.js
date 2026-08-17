import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';


test.describe('Visual - Musician Application - Logged in - No listings', () => {

    let applicationId;

    test.beforeEach(async ({ wpCli, musicianApplicationPage }) => {
        const applicationAuthorUser = createUser();
        wpCli.createUser(applicationAuthorUser);
        const applicationAuthorUserId = wpCli.getUserId(applicationAuthorUser.email);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });
        wpCli.trackPost(applicationId);

        const testUser = createUser();
        wpCli.createUser(testUser);

        await musicianApplicationPage.login(testUser.email, testUser.password);
    });

    test('Displays listing form and no listing dropdown', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.applicationTitle).toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).toBeVisible();
        await expect(musicianApplicationPage.listingForm).toBeVisible();
        await expect(musicianApplicationPage.listingDropdown).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
    });

    test('Displays invalid link message instead of the form when there is an invalid lic in the url', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateToApplication(applicationId, 'test');
        await expect(musicianApplicationPage.invalidLic).toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).not.toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

});
