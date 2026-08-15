import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createApplication } from '../../../data/application_factory.js';
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

    test('User sees the empty state', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(noApplicationsUser.email, noApplicationsUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
    });

});
