import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('Access - Talent Buyer Pro - Application Limits', () => {

    async function countApplications(appPage) {
        return appPage.page.locator('#results a', { hasText: 'Manage Application' }).count();
    }

    test('can only create one application without Pro; adding Pro unlocks unlimited', async ({ themePage, applicationsPage, applicationFormPage, wpCli }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);

        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();

        // First application succeeds for a user with no capabilities
        await applicationFormPage.navigate('/application-form/');
        await applicationFormPage.fillMinimumFields('First Application', 'My first application for testing.');
        await applicationFormPage.submitApplication();
        await applicationFormPage.waitForSubmitRedirect();

        // Second application attempt fails without Pro
        await applicationFormPage.navigate('/application-form/');
        await applicationFormPage.fillMinimumFields('Second Application', 'This one should be rejected.');
        await applicationFormPage.submitApplication();
        await expect(applicationFormPage.page).toHaveURL(/\/application-form\/$/);

        // Still only one application exists
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        await expect(applicationsPage.page.locator('#results')).toContainText('First Application');
        expect(await countApplications(applicationsPage)).toBe(1);

        // Add Talent Buyer Pro capability and the next application succeeds
        wpCli.addCap(userId, 'hm_buyer_pro');
        await applicationFormPage.navigate('/application-form/');
        await applicationFormPage.fillMinimumFields('Third Application', 'Now I am Pro, so this should work.');
        await applicationFormPage.submitApplication();
        await applicationFormPage.waitForSubmitRedirect();

        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        expect(await countApplications(applicationsPage)).toBe(2);
    });

    test('deleting the only application lets a free user create another one', async ({ themePage, applicationsPage, applicationFormPage, singleApplicationPage, wpCli }) => {
        const user = createUser();
        wpCli.createUser(user);

        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();

        // First application succeeds
        await applicationFormPage.navigate('/application-form/');
        await applicationFormPage.fillMinimumFields('First Application', 'My first application for testing.');
        await applicationFormPage.submitApplication();
        const appSlug = await applicationFormPage.waitForSubmitRedirect();

        // Second application attempt fails without Pro
        await applicationFormPage.navigate('/application-form/');
        await applicationFormPage.fillMinimumFields('Second Application', 'This one should be rejected.');
        await applicationFormPage.submitApplication();
        await expect(applicationFormPage.page).toHaveURL(/\/application-form\/$/);

        // Delete the only application through the front-end delete button
        await singleApplicationPage.navigateToApplication(appSlug);
        singleApplicationPage.page.once('dialog', dialog => dialog.accept());
        await singleApplicationPage.deleteBtn.click();
        await singleApplicationPage.page.waitForURL(/\/applications\/\?toast=delete/);

        // No applications remain
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        expect(await countApplications(applicationsPage)).toBe(0);
        await expect(applicationsPage.page.getByText('No applications yet!', { exact: true })).toBeVisible();

        // The free user can now create another application
        await applicationFormPage.navigate('/application-form/');
        await applicationFormPage.fillMinimumFields('Replacement Application', 'Created after deleting my first application.');
        await applicationFormPage.submitApplication();
        await applicationFormPage.waitForSubmitRedirect();

        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        expect(await countApplications(applicationsPage)).toBe(1);
    });

});
