import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';
import { createListingPost } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Musician Application - Submit with exisitng listing', () => {

    let applicationAuthor;
    let applicationAuthorId;
    let applicationId;
    let submitter;
    let submitterId;
    let listingName;
    let listingId;
    let message;

    test.beforeEach(async ({ wpCli, musicianApplicationPage }) => {
        applicationAuthor = createUser();
        wpCli.createUser(applicationAuthor);
        applicationAuthorId = wpCli.getUserId(applicationAuthor.email);

        applicationId = createApplicationPost({ authorId: applicationAuthorId });
        wpCli.trackPost(applicationId);

        submitter = createUser();
        wpCli.createUser(submitter);
        submitterId = wpCli.getUserId(submitter.email);

        listingName = `Test Listing ${Date.now()}`;
        listingId = createListingPost({ authorId: submitterId, overrides: { name: listingName } });
        wpCli.trackPost(listingId);
        wpCli.addListingToUser(submitterId, listingId);

        message = `I would love to play this event! ${Date.now()}`;

        await musicianApplicationPage.login(submitter.email, submitter.password);
    });

    test('Submit application with existing listing', async ({ musicianApplicationPage, wpCli, mailpit }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);

        await musicianApplicationPage.selectListing(listingName);
        await musicianApplicationPage.fillMessage(message);
        await musicianApplicationPage.submitApplication();

        await expect(musicianApplicationPage.successfulSubmission).toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).not.toBeVisible();
        await expect(musicianApplicationPage.listingDropdown).not.toBeVisible();
        await expect(musicianApplicationPage.messageTextarea).not.toBeVisible();
        await expect(musicianApplicationPage.submitButton).not.toBeVisible();

        const submissionId = wpCli.getLatestPostId(submitterId, 'app_submission');
        expect(submissionId).toBeTruthy();
        expect(wpCli.getPostMeta(submissionId, 'application')).toBe(String(applicationId));
        expect(wpCli.getPostMeta(submissionId, 'listing')).toBe(String(listingId));
        expect(wpCli.getPostMeta(submissionId, 'message')).toBe(message);

        const expectedSubmitterSubject = `(${mailpit.siteUrl} ${submitter.email}) Your application submission was successful!`;
        const submitterEmail = await mailpit.findEmailBySubject(expectedSubmitterSubject);
        expect(submitterEmail).toBeTruthy();

        const expectedAuthorSubject = `(${mailpit.siteUrl} ${applicationAuthor.email}) You have a new applicant!`;
        const authorEmail = await mailpit.findEmailBySubject(expectedAuthorSubject);
        expect(authorEmail).toBeTruthy();
    });

    test.skip('Submit application with existing listing including event availability', async ({}) => {} );
});
