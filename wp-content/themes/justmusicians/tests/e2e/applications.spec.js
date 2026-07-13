import { execSync } from 'child_process';
import { expect } from '@playwright/test';
import { test } from '../fixtures/fixtures.js';
import { createUser } from '../data/user_factory.js';


test.describe('Applications', () => {

    let noApplicationsUser;

    test.beforeAll(async () => {
        noApplicationsUser = createUser();
        execSync(
            `wp user create "${noApplicationsUser.email}" "${noApplicationsUser.email}" --role=subscriber --user_pass="${noApplicationsUser.password}" --first_name="${noApplicationsUser.firstName}" --last_name="${noApplicationsUser.lastName}" --path=/Users/johnfilippone/Local\\ Sites/just-musicians/app/public`,
            { stdio: 'ignore' }
        );
    });

    test.afterAll(async () => {
        if (noApplicationsUser) {
            execSync(
                `wp user delete ${noApplicationsUser.email} --yes --path=/Users/johnfilippone/Local\\ Sites/just-musicians/app/public`,
                { stdio: 'ignore' }
            );
        }
    });

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

});
