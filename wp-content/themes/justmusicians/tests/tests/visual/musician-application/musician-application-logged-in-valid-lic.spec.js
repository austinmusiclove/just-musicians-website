import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';
import { createListingPost } from '../../../data/factories/listing_factory.js';
import { createTmpCodePost } from '../../../data/factories/tmp_code_factory.js';


test.describe('Visual - Musician Application - Logged in - Valid lic', () => {

    test.beforeEach(async ({ wpCli, musicianApplicationPage }) => {
        const applicationAuthorUser = createUser();
        const applicationAuthorUserId = wpCli.createUser(applicationAuthorUser);
        const applicationId = createApplicationPost({ authorId: applicationAuthorUserId });
        wpCli.trackPost(applicationId);

        const testUser = createUser();
        const testUserId = wpCli.createUser(testUser);

        const listingId = createListingPost({ authorId: testUserId, status: 'pending' });
        wpCli.trackPost(listingId);

        const tmpCode = createTmpCodePost({
            authorId: applicationAuthorUserId,
            overrides: { listings: [Number(listingId)] },
        });
        const tmpCodeId = tmpCode.id;
        const lic = tmpCode.code;
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
