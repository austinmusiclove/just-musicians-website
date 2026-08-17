import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createApplication } from '../../../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Navigation - Applications - Empty State', () => {

    let noApplicationsUser;

    test.beforeAll(async () => {
        noApplicationsUser = createUser();
        wpCliCreateUser(noApplicationsUser);
    });

    test.afterAll(async () => {
        if (noApplicationsUser) {
            wpCliDeleteUser(noApplicationsUser.email);
        }
    });

    test.beforeEach(async ({ applicationsPage }) => {
        await applicationsPage.login(noApplicationsUser.email, noApplicationsUser.password);
        await applicationsPage.navigate('/applications/');
    });

    test('click empty state create application button navigates to application form', async ({ applicationsPage }) => {
        await applicationsPage.emptyStateCreateBtn.click();
        await expect(applicationsPage.page).toHaveURL(/\/application-form\/$/);
    });

    test('click add button navigates to application form', async ({ applicationsPage }) => {
        await applicationsPage.addBtn.click();
        await expect(applicationsPage.page).toHaveURL(/\/application-form\/$/);
    });

});
