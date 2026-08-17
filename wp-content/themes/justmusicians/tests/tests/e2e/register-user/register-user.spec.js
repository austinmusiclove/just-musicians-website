import { test } from '../../../fixtures/fixtures.js';

test.describe('E2E - Register User', () => {

    test('register user from header sign up button on home page', async ({ themePage, wpCli }) => {
        await themePage.navigate('/');
        const user = await themePage.registerUserSignupModal();
        wpCli.trackUser(user);
        await themePage.expectLoggedInPage();
    });

    test('register user from non home page url with query args and expect it to redirect to the same url after success', async ({ themePage, wpCli }) => {
        await themePage.navigate('/blog/?arg=abc');
        const user = await themePage.registerUserSignupModal();
        wpCli.trackUser(user);
        await themePage.expectLoggedInPage();
    });

    test.skip('new user is redirected to the same url that they were at when they registered successfully with query params', async ( {} ) => {});
    test.skip('account activation email is sent to new user after registration', async ( {} ) => {}); // Maybe best for a PHP test
    test.skip('sign up with google', async ( {} ) => {}); // Mock the google part

});
