import { execSync } from 'child_process';
import { test } from '../fixtures/fixtures.js';

test.describe('User Registration', () => {

    const userEmailsToDelete = [];

    test.afterAll(async () => {
        if (userEmailsToDelete.length) {
            execSync(
                `wp user delete ${userEmailsToDelete.join(' ')} --yes --path=/Users/johnfilippone/Local\\ Sites/just-musicians/app/public`,
                { stdio: 'ignore' }
            );
        }
    });

    test('register user from header sign up button on home page', async ({ themePage }) => {
        await themePage.navigate('/');
        const user = await themePage.registerUserSignupModal();
        userEmailsToDelete.push(user.email);
        await themePage.expectLoggedInPage();
    });

    test('register user from header sign up button on blog page with query args', async ({ themePage }) => {
        await themePage.navigate('/blog/?arg=abc');
        const user = await themePage.registerUserSignupModal();
        userEmailsToDelete.push(user.email);
        await themePage.expectLoggedInPage();
    });

    test.skip('new user is redirected to the same url that they were at when they registered successfully with query params', async ({ page }) => {});
    test.skip('account activation email is sent to new user after registration', async ({ page }) => {}); // Maybe best for a PHP test
    test.skip('sign up with google', async ({ page }) => {}); // Mock the google part

});
