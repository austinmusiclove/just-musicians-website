import { test } from '../fixtures/fixtures.js';
import { wpCliDeleteUsers } from '../data/wp_cli.js';

test.describe('User Registration', () => {

    const userEmailsToDelete = [];

    test('register user from header sign up button on home page', async ({ themePage }) => {
        await themePage.navigate('/');
        const user = await themePage.registerUserSignupModal();
        userEmailsToDelete.push(user.email);
        await themePage.expectLoggedInPage();
    });

    test('register user from non home page url with query args and expect it to redirect to the same url after success', async ({ themePage }) => {
        await themePage.navigate('/blog/?arg=abc');
        const user = await themePage.registerUserSignupModal();
        userEmailsToDelete.push(user.email);
        await themePage.expectLoggedInPage();
    });

    test.skip('new user is redirected to the same url that they were at when they registered successfully with query params', async ({ page }) => {});
    test.skip('account activation email is sent to new user after registration', async ({ page }) => {}); // Maybe best for a PHP test
    test.skip('sign up with google', async ({ page }) => {}); // Mock the google part

    test.afterAll(async () => {
        wpCliDeleteUsers(userEmailsToDelete);
    });

});
