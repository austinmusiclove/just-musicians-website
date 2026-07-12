import { test, expect } from '@playwright/test';
import { execSync } from 'child_process';

const ts = Date.now();
let testEmail;

async function openSignupModal(page) {
    const btn = page.getByTestId('header-signup-btn');
    await expect(btn).toBeVisible();
    await btn.click();
    await expect(page.getByTestId('signup-modal-heading')).toBeVisible();
}

test.describe('User Registration', () => {

    test.afterEach(async () => {
        if (testEmail) {
            execSync(
                `wp user delete "${testEmail}" --yes --path=/Users/johnfilippone/Local\\ Sites/just-musicians/app/public`,
                { stdio: 'ignore' }
            );
        }
    });

    test('register user from header sign up button', async ({ page }) => {
        testEmail = `e2e-success-${ts}@test.justmusicians.com`;

        await page.goto('/');
        await openSignupModal(page);

        await page.fill('#first_name', 'E2ETest');
        await page.fill('#last_name', 'User');
        await page.fill('#email', testEmail);
        await page.fill('#password', 'TestPassword123!');
        await page.locator('#r_rememberme').check();

        await Promise.all([
            page.waitForURL('**/*'),
            page.getByTestId('signup-submit-btn').click(),
        ]);

        await page.waitForLoadState('load');
        await expect(page.getByTestId('header-signup-btn')).not.toBeVisible();
        await expect(page.getByTestId('header-login-btn')).not.toBeVisible();
    });

    test.skip('new user is redirected to the same url that they were at when they registered successfully with query params', async ({ page }) => {});
    test.skip('account activation email is sent to new user after registration', async ({ page }) => {}); // Maybe best for a PHP test
    test.skip('sign up with google', async ({ page }) => {}); // Mock the google part

});
