import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';


test.describe('Visual - Applications - Empty state', () => {

    test.beforeEach(async ({ wpCli, applicationsPage }) => {
        const noApplicationsUser = createUser();
        wpCli.createUser(noApplicationsUser);
        await applicationsPage.login(noApplicationsUser.email, noApplicationsUser.password);
        await applicationsPage.navigate('/applications/');
    });

    test('User sees the empty state', async ({ applicationsPage }) => {
        await applicationsPage.waitForResults();
        await expect(applicationsPage.emptyStateCreateBtn).toBeVisible();
    });

});
