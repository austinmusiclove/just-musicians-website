import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';


test.describe('Visual - Musician Application - Logged in - One listings', () => {

    let applicationId;

    test.beforeEach(async ({ wpCli, musicianApplicationPage }) => {
        const applicationAuthorUser = createUser();
        const applicationAuthorUserId = wpCli.createUser(applicationAuthorUser);
        applicationId = wpCli.createPost(createApplicationPostData({ authorId: applicationAuthorUserId }));

        const testUser = createUser();
        const testUserId = wpCli.createUser(testUser);

        const listingData = createListingPostData({ authorId: testUserId });
        const listingId = wpCli.createListing(listingData);

        await musicianApplicationPage.login(testUser.email, testUser.password);
    });

    test('Displays the listing dropdown and no listing form', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.applicationTitle).toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).toBeVisible();
        await expect(musicianApplicationPage.listingDropdown).toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
    });

});
