import { execSync } from 'child_process';
import { test } from '../fixtures/fixtures.js';

let testEmail;

test.describe('User Registration', () => {

    test.afterEach(async () => {
        if (testEmail) {
            execSync(
                `wp user delete "${testEmail}" --yes --path=/Users/johnfilippone/Local\\ Sites/just-musicians/app/public`,
                { stdio: 'ignore' }
            );
        }
    });

    test('register user from header sign up button on home page', async ({ themePage }) => {
        await themePage.navigate('/');
        const user = await themePage.registerUser();
        testEmail = user.email;
        await themePage.expectLoggedInPage();
    });

    test.skip('new user is redirected to the same url that they were at when they registered successfully with query params', async ({ page }) => {});
    test.skip('account activation email is sent to new user after registration', async ({ page }) => {}); // Maybe best for a PHP test
    test.skip('sign up with google', async ({ page }) => {}); // Mock the google part

});
