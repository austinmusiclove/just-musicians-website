import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Update Application Submission', () => {

    let applicationAuthor;
    let applicationAuthorId;
    let applicationId;
    let submitter;
    let submitterId;
    let listingData;
    let listingId;
    let message;
    let submissionId;

    test.beforeEach(async ({ wpCli, musicianApplicationPage }) => {
        applicationAuthor = createUser();
        applicationAuthorId = wpCli.createUser(applicationAuthor);

        applicationId = wpCli.createPost(createApplicationPostData({ authorId: applicationAuthorId, overrides: { request_quote: true, request_draw: true } }));

        submitter = createUser();
        submitterId = wpCli.createUser(submitter);

        listingData = createListingPostData({ authorId: submitterId, overrides: { name: `Test Listing ${Date.now()}` } });
        listingId = wpCli.createListing(listingData);

        message = `I would love to play this event! ${Date.now()}`;

        await musicianApplicationPage.login(submitter.email, submitter.password);

        await musicianApplicationPage.navigateToApplication(applicationId);
        await musicianApplicationPage.selectListing(listingData.meta.name);
        await musicianApplicationPage.fillMessage(message);
        await musicianApplicationPage.fillMinGuarantee("1000");
        await musicianApplicationPage.fillDrawEstimate("100");
        await musicianApplicationPage.submitApplication();
        await musicianApplicationPage.expectSuccessScreen();

        submissionId = wpCli.getLatestPostId(submitterId, 'app_submission');
        expect(submissionId).toBeTruthy();
        wpCli.trackPost(submissionId);
    });

    test('Update application submission with changed min guarantee and draw', async ({ musicianApplicationPage, wpCli, mailpit }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);

        await musicianApplicationPage.selectListing(listingData.meta.name);
        await musicianApplicationPage.fillMessage(message);
        const newMinGuarantee = "2000";
        const newDrawEstimate = "200";
        await musicianApplicationPage.fillMinGuarantee(newMinGuarantee);
        await musicianApplicationPage.fillDrawEstimate(newDrawEstimate);
        await musicianApplicationPage.submitApplication();

        await musicianApplicationPage.expectSuccessScreen();

        const updatedSubmissionId = wpCli.getLatestPostId(submitterId, 'app_submission');
        expect(updatedSubmissionId).toBe(submissionId);
        wpCli.trackPost(updatedSubmissionId);
        expect(wpCli.getPostMeta(updatedSubmissionId, 'application')).toBe(String(applicationId));
        expect(wpCli.getPostMeta(updatedSubmissionId, 'listing')).toBe(String(listingId));
        expect(wpCli.getPostMeta(updatedSubmissionId, 'message')).toBe(message);
        expect(wpCli.getPostMeta(updatedSubmissionId, 'minimum_guarantee')).toBe(newMinGuarantee);
        expect(wpCli.getPostMeta(updatedSubmissionId, 'draw_estimate')).toBe(newDrawEstimate);
    });

    test('Update application submission to remove min guarantee and draw', async ({ musicianApplicationPage, wpCli }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);

        await musicianApplicationPage.selectListing(listingData.meta.name);
        await musicianApplicationPage.fillMessage(message);
        await musicianApplicationPage.fillMinGuarantee("");
        await musicianApplicationPage.fillDrawEstimate("");
        await musicianApplicationPage.submitApplication();

        await musicianApplicationPage.expectSuccessScreen();

        const updatedSubmissionId = wpCli.getLatestPostId(submitterId, 'app_submission');
        expect(updatedSubmissionId).toBe(submissionId);
        wpCli.trackPost(updatedSubmissionId);
        expect(wpCli.getPostMeta(updatedSubmissionId, 'minimum_guarantee')).toBeNull();
        expect(wpCli.getPostMeta(updatedSubmissionId, 'draw_estimate')).toBeNull();
    });

    test.skip('Update application submission with event availability', async ({}) => {} );
});
