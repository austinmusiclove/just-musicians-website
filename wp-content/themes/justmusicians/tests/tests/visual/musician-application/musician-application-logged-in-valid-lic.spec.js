import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';
import { createTmpCodePostData } from '../../../data/factories/tmp_code_factory.js';


test.describe('Visual - Musician Application - Logged in - Valid lic', () => {

    test.beforeEach(async ({ wpCli, musicianApplicationPage }) => {
        const applicationAuthorUser = createUser();
        const applicationAuthorUserId = wpCli.createUser(applicationAuthorUser);
        const applicationId = createApplicationPost({ authorId: applicationAuthorUserId });
        wpCli.trackPost(applicationId);

        const testUser = createUser();
        const testUserId = wpCli.createUser(testUser);

        const listingData = createListingPostData({ authorId: testUserId, status: 'pending' });
        const listingId = wpCli.createListing(listingData);

        const tmpCodeData = createTmpCodePostData({
            authorId: applicationAuthorUserId,
            overrides: { listings: [Number(listingId)] },
        });
        const tmpCodeId = wpCli.createPost(tmpCodeData);
        const lic = tmpCodeData.meta.code;
        wpCli.trackPost(tmpCodeId);

        await musicianApplicationPage.login(testUser.email, testUser.password);
        await musicianApplicationPage.navigateToApplication(applicationId, lic);
    });

    test('Displays successful submission content instead of the form', async ({ musicianApplicationPage }) => {
        await expect(musicianApplicationPage.successfulSubmissionNewListing).toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).not.toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

});
