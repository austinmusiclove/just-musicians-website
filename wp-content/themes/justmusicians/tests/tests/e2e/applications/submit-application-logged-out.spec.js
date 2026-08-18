import { expect } from '@playwright/test';
import { faker } from '@faker-js/faker';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';
import { createListing } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Submit Application - Logged Out', () => {

    let applicationAuthor;
    let applicationAuthorId;
    let applicationId;
    let listingData;
    let message;

    test.beforeEach(async ({ wpCli }) => {
        applicationAuthor = createUser();
        wpCli.createUser(applicationAuthor);
        applicationAuthorId = wpCli.getUserId(applicationAuthor.email);

        applicationId = createApplicationPost({ authorId: applicationAuthorId });
        wpCli.trackPost(applicationId);

        listingData = createListing();
        message = faker.lorem.sentence();
    });

    test('Submit application while logged out and sign up', async ({ musicianApplicationPage, wpCli, mailpit }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);

        await musicianApplicationPage.fillMinimumListingFields( listingData.name, listingData.description, listingData.zip, listingData.email);
        await musicianApplicationPage.uploadCoverImage('tests/data/files/test-image.png');
        await musicianApplicationPage.fillMessage(message);
        await musicianApplicationPage.submitApplication();

        await musicianApplicationPage.page.waitForURL(
            url => url.pathname.includes(`/musician-application/${applicationId}`) && url.searchParams.has('lic'),
            { timeout: 10000 }
        );

        // Need to make sure to get a spcific listing with specific title; otherwise parallelism problem
        const listingPostId = wpCli.getLatestPostIdByType('listing', 'pending');
        expect(listingPostId).toBeTruthy();
        expect(wpCli.getPostField(listingPostId, 'post_status')).toBe('pending');
        expect(wpCli.getPostMeta(listingPostId, 'name')).toBe(listingData.name);

        const submissionId = wpCli.getLatestPostIdByType('app_submission', 'publish');
        expect(submissionId).toBeTruthy();
        expect(wpCli.getPostMeta(submissionId, 'application')).toBe(String(applicationId));
        expect(wpCli.getPostMeta(submissionId, 'listing')).toBe(String(listingPostId));
        expect(wpCli.getPostMeta(submissionId, 'message')).toBe(message);

        const expectedSubmitterSubject = `(${mailpit.siteUrl} ${listingData.email}) Your application submission has been submitted.`;
        const submitterEmail = await mailpit.findEmailBySubject(expectedSubmitterSubject);
        expect(submitterEmail).toBeTruthy();
        // Check that the link is the same as the redirect url

        const expectedAuthorSubject = `(${mailpit.siteUrl} ${applicationAuthor.email}) You have a new applicant!`;
        const authorEmail = await mailpit.findEmailBySubject(expectedAuthorSubject);
        expect(authorEmail).toBeTruthy();

        const notificationExists = wpCli.notificationExists(applicationAuthorId, 'new_applicant', submissionId);
        expect(notificationExists).toBe(true);

        // Check for success anon
        // Click sign up
        // Sign up
        // Redirect
        // Check for listing published, authored by user, and in listings user meta
    });
    test.skip('Submit application while logged out and sign up from email link', async ({}) => {} );
    test.skip('Submit application while logged out and sign up with complete listing', async ({}) => {} );
    test.skip('Submit application while logged out and sign up with event availability', async ({}) => {} );
});
