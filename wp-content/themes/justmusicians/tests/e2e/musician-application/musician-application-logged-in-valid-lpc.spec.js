import { expect } from '@playwright/test';
import { test } from '../../fixtures/fixtures.js';
import { createUser } from '../../data/user_factory.js';
import { createApplicationPost } from '../../data/application_factory.js';
import { createListingPost } from '../../data/listing_factory.js';
import { createTmpCodePost } from '../../data/tmp_code_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliDeletePost } from '../../data/wp_cli.js';


test.describe('Musician Application logged in valid lpc', () => {

    let applicationAuthorUser;
    let applicationAuthorUserId;
    let applicationId;
    let testUser;
    let testUserId;
    let listingId;
    let tmpCodeId;
    let lpc;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });

        testUser = createUser();
        wpCliCreateUser(testUser);
        testUserId = wpCliGetUserId(testUser.email);

        listingId = createListingPost({ authorId: testUserId, status: 'pending' });

        const tmpCode = createTmpCodePost({
            authorId: applicationAuthorUserId,
            overrides: { listings: [listingId] },
        });
        tmpCodeId = tmpCode.id;
        lpc = tmpCode.code;
    });

    test.afterAll(async () => {
        if (tmpCodeId)             { wpCliDeletePost(tmpCodeId); }
        if (listingId)             { wpCliDeletePost(listingId); }
        if (applicationId)         { wpCliDeletePost(applicationId); }
        if (applicationAuthorUser) { wpCliDeleteUser(applicationAuthorUser.email); }
        if (testUser)              { wpCliDeleteUser(testUser.email); }
    });

    test('sees the successful submission content instead of the form', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigate('/');
        await musicianApplicationPage.login(testUser.email, testUser.password);
        await musicianApplicationPage.navigateByApplicationId(applicationId, lpc);
        await expect(musicianApplicationPage.successfulSubmissionNewListing).toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).not.toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

});
