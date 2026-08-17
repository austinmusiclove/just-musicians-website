import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';


test.describe('Navigation - Applications - One Application', () => {

    test.beforeEach(async ({ wpCli, applicationsPage }) => {
        const applicationAuthorUser = createUser();
        wpCli.createUser(applicationAuthorUser);
        const applicationAuthorUserId = wpCli.getUserId(applicationAuthorUser.email);
        const applicationId = createApplicationPost({ authorId: applicationAuthorUserId });
        wpCli.trackPost(applicationId);

        await applicationsPage.login(applicationAuthorUser.email, applicationAuthorUser.password);
        await applicationsPage.navigate('/applications/');
    });

    test('click add button navigates to application form', async ({ applicationsPage }) => {
        await applicationsPage.addBtn.click();
        await expect(applicationsPage.page).toHaveURL(/\/application-form\/$/);
    });

    test('application review applicants button navigates to single application page on the applicants tab', async ({ applicationsPage }) => {
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await applicationsPage.getReviewApplicantsBtn(applicationsPage.applicationCards.first()).click();
        await expect(applicationsPage.page).toHaveURL(/\/application\/[^/]+\/?\?tab=applicants/);
    });

    test('edit application button navigates to single application page on the details tab', async ({ applicationsPage }) => {
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await applicationsPage.getEditApplicationBtn(applicationsPage.applicationCards.first()).click();
        await expect(applicationsPage.page).toHaveURL(/\/application\/[^/]+\/?$/);
    });

});
