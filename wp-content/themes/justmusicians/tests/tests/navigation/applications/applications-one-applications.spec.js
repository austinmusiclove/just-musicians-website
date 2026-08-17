import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createApplication } from '../../../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Navigation - Applications - One Application', () => {

    let applicationAuthorUser;
    let applicationId;
    let applicationTitle;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        const applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);

        const application = createApplication();
        applicationTitle = application.title;
        applicationId = wpCliCreatePost({
            postType: 'application',
            title: application.title,
            authorId: applicationAuthorUserId,
            meta: {
                title: application.title,
                description: application.description,
            },
        });
    });

    test.afterAll(async () => {
        if (applicationId) {
            wpCliDeletePost(applicationId);
        }
        if (applicationAuthorUser) {
            wpCliDeleteUser(applicationAuthorUser.email);
        }
    });

    test.beforeEach(async ({ applicationsPage }) => {
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
