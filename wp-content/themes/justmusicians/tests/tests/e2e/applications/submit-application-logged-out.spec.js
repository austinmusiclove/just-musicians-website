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

        listingData = createListing({ zip: '78701' });
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

        const listingPostId = wpCli.getLatestPostIdByType('listing', 'pending', { metaKey: 'name', metaValue: listingData.name });
        expect(listingPostId).toBeTruthy();
        wpCli.trackPost(listingPostId);
        expect(wpCli.getPostField(listingPostId, 'post_status')).toBe('pending');
        expect(wpCli.getPostMeta(listingPostId, 'name')).toBe(listingData.name);

        const submissionId = wpCli.getLatestPostIdByType('app_submission', 'publish', { metaKey: 'application', metaValue: String(applicationId) });
        expect(submissionId).toBeTruthy();
        expect(wpCli.getPostMeta(submissionId, 'application')).toBe(String(applicationId));
        expect(wpCli.getPostMeta(submissionId, 'listing')).toBe(String(listingPostId));
        expect(wpCli.getPostMeta(submissionId, 'message')).toBe(message);

        const expectedSubmitterSubject = `(${mailpit.siteUrl} ${listingData.email}) Your application submission has been submitted.`;
        const submitterEmail = await mailpit.findEmailBySubject(expectedSubmitterSubject);
        expect(submitterEmail).toBeTruthy();
        const emailBody = await mailpit.getEmailBody(submitterEmail.ID);
        const signUpLink = mailpit.extractLinkFromEmail(emailBody);
        expect(signUpLink).toBeTruthy();

        const code = new URL(signUpLink).searchParams.get('lic');
        const tmpCodePostId = wpCli.getLatestPostIdByType('tmp_code', 'publish', { metaKey: 'code', metaValue: code });
        expect(tmpCodePostId).toBeTruthy();
        wpCli.trackPost(tmpCodePostId);

        const expectedAuthorSubject = `(${mailpit.siteUrl} ${applicationAuthor.email}) You have a new applicant!`;
        const authorEmail = await mailpit.findEmailBySubject(expectedAuthorSubject);
        expect(authorEmail).toBeTruthy();

        const notificationExists = wpCli.notificationExists(applicationAuthorId, 'new_applicant', submissionId);
        expect(notificationExists).toBe(true);

        await musicianApplicationPage.page.waitForURL(
            url => url.href.startsWith(signUpLink),
            { timeout: 20000 }
        );
        await musicianApplicationPage.expectSuccessScreenAnon();

        const newUser = createUser();
        await musicianApplicationPage.successfulSubmissionAnonSignUpBtn.click();
        await expect(musicianApplicationPage.signupModalHeading).toBeVisible();
        await musicianApplicationPage.fillSignupForm(newUser);
        await musicianApplicationPage.signupSubmitBtn.click();
        await musicianApplicationPage.page.waitForURL(
            url => url.pathname.includes(`/musician-application/${applicationId}`) && url.searchParams.get('lic') === code,
            { timeout: 20000 }
        );
        wpCli.trackUser(newUser);

        await musicianApplicationPage.expectLoggedInPage();
        expect(wpCli.getPostField(listingPostId, 'post_status')).toBe('publish');

        const newUserId = wpCli.getUserId(newUser.email);
        const newUserListings = wpCli.getUserMeta(newUserId, 'listings');
        expect(newUserListings).toContain(Number(listingPostId));
        expect(wpCli.getPostField(listingPostId, 'post_author')).toBe(newUserId);
    });
    test.skip('Submit application while logged out and sign up from email link', async ({}) => {} );
    test.skip('Submit application while logged out and sign up with complete listing', async ({}) => {} );
    test.skip('Submit application while logged out and sign up with event availability', async ({}) => {} );
    test.skip('Submit application while logged out and sign up after getting an inquiry', async ({}) => {} );
});
