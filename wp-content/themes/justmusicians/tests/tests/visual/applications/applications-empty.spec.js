import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplication } from '../../../data/factories/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Visual - Applications - Empty state', () => {

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

    test('User sees the empty state', async ({ applicationsPage }) => {
        await applicationsPage.waitForResults();
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
    });

});
