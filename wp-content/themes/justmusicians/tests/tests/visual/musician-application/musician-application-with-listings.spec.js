import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createApplicationPost } from '../../../data/application_factory.js';
import { createListingPost } from '../../../data/listing_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliSetUserMeta, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Visual - Musician Application - Logged in - One listings', () => {

    let applicationAuthorUser;
    let applicationAuthorUserId;
    let applicationId;
    let testUser;
    let testUserId;
    let listingId;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);
        applicationId = createApplicationPost({ authorId: applicationAuthorUserId });

        testUser = createUser();
        wpCliCreateUser(testUser);
        testUserId = wpCliGetUserId(testUser.email);

        listingId = createListingPost({ authorId: testUserId });
        wpCliSetUserMeta(testUser.email, 'listings', [listingId]);
    });

    test.afterAll(async () => {
        if (listingId)             { wpCliDeletePost(listingId); }
        if (applicationId)         { wpCliDeletePost(applicationId); }
        if (applicationAuthorUser) { wpCliDeleteUser(applicationAuthorUser.email); }
        if (testUser)              { wpCliDeleteUser(testUser.email); }
    });

    test.beforeEach(async ({ musicianApplicationPage }) => {
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
