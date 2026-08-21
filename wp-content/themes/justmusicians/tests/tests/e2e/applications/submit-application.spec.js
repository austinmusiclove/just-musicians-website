import { expect } from '@playwright/test';
import { faker } from '@faker-js/faker';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Submit Application - Logged in - No Listings', () => {

    let applicationAuthor;
    let applicationAuthorId;
    let applicationId;
    let submitter;
    let submitterId;
    let listingData;
    let message;

    test.beforeEach(async ({ wpCli, musicianApplicationPage }) => {
        applicationAuthor = createUser();
        applicationAuthorId = wpCli.createUser(applicationAuthor);

        applicationId = createApplicationPost({ authorId: applicationAuthorId });
        wpCli.trackPost(applicationId);

        submitter = createUser();
        submitterId = wpCli.createUser(submitter);

        listingData = createListingPostData({ overrides: { email: submitter.email, zip: '78701' } });
        message = faker.lorem.sentence();

        await musicianApplicationPage.login(submitter.email, submitter.password);
    });

    test('Submit application with new listing', async ({ musicianApplicationPage, wpCli, mailpit }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);

        await musicianApplicationPage.fillMinimumListingFields( listingData.meta.name, listingData.meta.description, listingData.meta.zip_code, listingData.meta.email);
        await musicianApplicationPage.uploadCoverImage('tests/data/files/test-image.png');
        await musicianApplicationPage.fillMessage(message);
        await musicianApplicationPage.submitApplication();

        await musicianApplicationPage.expectSuccessScreenNewListing();

        const listingPostId = wpCli.getLatestPostId(submitterId, 'listing');
        expect(listingPostId).toBeTruthy();
        wpCli.trackPost(listingPostId);
        expect(wpCli.getPostField(listingPostId, 'post_status')).toBe('publish');
        expect(wpCli.getPostMeta(listingPostId, 'name')).toBe(listingData.meta.name);

        const userListings = wpCli.getUserMeta(submitterId, 'listings');
        expect(userListings).toContain(Number(listingPostId));

        const submissionId = wpCli.getLatestPostId(submitterId, 'app_submission');
        expect(submissionId).toBeTruthy();
        wpCli.trackPost(submissionId);
        expect(wpCli.getPostField(submissionId, 'post_status')).toBe('publish');
        expect(wpCli.getPostMeta(submissionId, 'application')).toBe(String(applicationId));
        expect(wpCli.getPostMeta(submissionId, 'listing')).toBe(String(listingPostId));
        expect(wpCli.getPostMeta(submissionId, 'message')).toBe(message);

        const expectedSubmitterSubject = `(${mailpit.siteUrl} ${submitter.email}) Your application submission was successful!`;
        const submitterEmail = await mailpit.findEmailBySubject(expectedSubmitterSubject);
        expect(submitterEmail).toBeTruthy();

        const expectedAuthorSubject = `(${mailpit.siteUrl} ${applicationAuthor.email}) You have a new applicant!`;
        const authorEmail = await mailpit.findEmailBySubject(expectedAuthorSubject);
        expect(authorEmail).toBeTruthy();

        const notificationExists = wpCli.notificationExists(applicationAuthorId, 'new_applicant', submissionId);
        expect(notificationExists).toBe(true);
    });

    test.skip('Submit application with new listing including event availability', async ({}) => {} );
    test.skip('Submit application with new complete listing', async ({}) => {} );
});
