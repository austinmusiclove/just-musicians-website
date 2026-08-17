import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';


test.describe('Navigation - Applications - Empty State', () => {

    test.beforeEach(async ({ wpCli, applicationsPage }) => {
        const noApplicationsUser = createUser();
        wpCli.createUser(noApplicationsUser);
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
