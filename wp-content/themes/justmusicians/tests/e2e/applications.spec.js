import { expect } from '@playwright/test';
import { test } from '../fixtures/fixtures.js';
import { createUser } from '../data/user_factory.js';
import { createApplication } from '../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../data/wp_cli.js';


test.describe('Applications', () => {

    let noApplicationsUser;
    let applicationAuthorUser;
    let applicationId;

    test('logged out user sees the login modal', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/applications/');
        await expect(applicationsPage.loginModalHeading).toBeVisible();
    });

    test('user with no applications sees the empty state', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(noApplicationsUser.email, noApplicationsUser.password);
        await applicationsPage.navigate('/applications/');
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

    test.beforeAll(async () => {
        noApplicationsUser = createUser();
        wpCliCreateUser(noApplicationsUser);

        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        const applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);

        const application = createApplication();
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
