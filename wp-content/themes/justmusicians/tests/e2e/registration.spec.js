import { test, expect } from '@playwright/test';
import { execSync } from 'child_process';

const ts = Date.now();
let testEmail;

async function openSignupModal(page) {
    const btn = page.getByTestId('header-signup-btn');
    await expect(btn).toBeVisible({ timeout: 5000 });
    await btn.click();
    await expect(page.getByTestId('signup-modal-heading')).toBeVisible({ timeout: 5000 });
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

    test('registers a new user successfully', async ({ page }) => {
        testEmail = `e2e-success-${ts}@test.justmusicians.com`;

        await page.goto('/');
        await openSignupModal(page);

        await page.fill('#first_name', 'E2ETest');
        await page.fill('#last_name', 'User');
        await page.fill('#email', testEmail);
        await page.fill('#password', 'TestPassword123!');
        await page.locator('#r_rememberme').check();

        await Promise.all([
            page.waitForURL('**/*', { timeout: 15000 }),
            page.getByTestId('signup-submit-btn').click(),
        ]);

        await expect(page.getByTestId('header-signup-btn')).not.toBeVisible({ timeout: 5000 });
    });

});
