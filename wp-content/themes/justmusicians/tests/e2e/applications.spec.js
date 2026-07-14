import { expect } from '@playwright/test';
import { test } from '../fixtures/fixtures.js';
import { createUser } from '../data/user_factory.js';
import { createApplication } from '../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../data/wp_cli.js';


test.describe('Applications', () => {

    let noApplicationsUser;
    let applicationAuthorUser;
    let applicationId;
    let applicationTitle;

    test('logged out user sees the login modal', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/applications/');
        await expect(applicationsPage.loginModalHeading).toBeVisible();
    });

    test('user with no applications sees the empty state', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(noApplicationsUser.email, noApplicationsUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
    });

    test('click empty state create application button navigates to application form', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(noApplicationsUser.email, noApplicationsUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.emptyStateCreateBtn.click();
        await expect(applicationsPage.page).toHaveURL(/\/application-form\/$/);
    });

    test('click add button navigates to application form', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(noApplicationsUser.email, noApplicationsUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.addBtn.click();
        await expect(applicationsPage.page).toHaveURL(/\/application-form\/$/);
    });

    test('logged in user\'s application is displayed', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(applicationAuthorUser.email, applicationAuthorUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        const cards = await applicationsPage.applicationCards.all();
        expect(cards).toHaveLength(1);
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationTitle);
    });

    test('application review applicants button navigates to single application page on the applicants tab', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(applicationAuthorUser.email, applicationAuthorUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await applicationsPage.getReviewApplicantsBtn(applicationsPage.applicationCards.first()).click();
        await expect(applicationsPage.page).toHaveURL(/\/application\/[^/]+\/?\?tab=applicants/);
    });

    test('edit application button navigates to single application page on the details tab', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(applicationAuthorUser.email, applicationAuthorUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        await expect(applicationsPage.applicationCards.first()).toBeVisible();
        await applicationsPage.getEditApplicationBtn(applicationsPage.applicationCards.first()).click();
        await expect(applicationsPage.page).toHaveURL(/\/application\/[^/]+\/?$/);
    });

    test.beforeAll(async () => {
        noApplicationsUser = createUser();
        wpCliCreateUser(noApplicationsUser);

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
        if (noApplicationsUser) {
            wpCliDeleteUser(noApplicationsUser.email);
        }
    });

});
