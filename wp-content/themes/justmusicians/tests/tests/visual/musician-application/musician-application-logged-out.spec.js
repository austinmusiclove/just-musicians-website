import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';


test.describe('Visual - Musician Application - Logged out', () => {

    let applicationId;

    test.beforeEach(async ({ wpCli }) => {
        const applicationAuthorUser = createUser();
        const applicationAuthorUserId = wpCli.createUser(applicationAuthorUser);
        applicationId = wpCli.createPost(createApplicationPostData({ authorId: applicationAuthorUserId }));
    });

    test('Displays listing form and no listing dropdown', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.applicationTitle).toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).toBeVisible();
        await expect(musicianApplicationPage.listingForm).toBeVisible();
        await expect(musicianApplicationPage.listingDropdown).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
    });

    test('Displays invalid link message instead of the form when there is an invalid lic in the url', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateToApplication(applicationId, 'test');
        await expect(musicianApplicationPage.invalidLic).toBeVisible();
        await expect(musicianApplicationPage.applicationTitle).toBeVisible();
        await expect(musicianApplicationPage.applicationDescription).toBeVisible();
        await expect(musicianApplicationPage.listingForm).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).not.toBeVisible();
    });

    test('Displays request quote and request draw only when application has it checked', async ({ musicianApplicationPage, wpCli }) => {
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.minimumGuaranteeInput).not.toBeVisible();
        await expect(musicianApplicationPage.drawEstimateInput).not.toBeVisible();

        wpCli.setPostMeta(applicationId, 'request_quote', '1');
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.minimumGuaranteeInput).toBeVisible();
        await expect(musicianApplicationPage.drawEstimateInput).not.toBeVisible();

        wpCli.setPostMeta(applicationId, 'request_draw', '1');
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.minimumGuaranteeInput).toBeVisible();
        await expect(musicianApplicationPage.drawEstimateInput).toBeVisible();

        wpCli.setPostMeta(applicationId, 'request_quote', '');
        await musicianApplicationPage.navigateToApplication(applicationId);
        await expect(musicianApplicationPage.minimumGuaranteeInput).not.toBeVisible();
        await expect(musicianApplicationPage.drawEstimateInput).toBeVisible();
    });

});
