import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('E2E - Register User', () => {

    test('register user from header sign up button on home page', async ({ themePage, wpCli, mailpit }) => {
        await themePage.navigate('/');
        const user = await themePage.registerUserSignupModal();
        wpCli.trackUser(user);
        await themePage.expectLoggedInPage();
        await expect(themePage.page).toHaveURL('/');

        const adminEmail = await mailpit.findEmailBySubject('[Hire Musicians] New User Registration');
        expect(adminEmail).toBeTruthy();

        const verifyEmail = await mailpit.findEmailBySubject(
            `(${mailpit.siteUrl} ${user.email}) Verify your email to activate your Hire Musicians account`
        );
        expect(verifyEmail).toBeTruthy();
    });

    test('register user from non home page url with query args and expect it to redirect to the same url after success', async ({ themePage, wpCli, mailpit }) => {
        await themePage.navigate('/blog/?arg=abc');
        const user = await themePage.registerUserSignupModal();
        wpCli.trackUser(user);
        await themePage.expectLoggedInPage();
        await expect(themePage.page).toHaveURL(/\/blog\/\?arg=abc/);
    });

    test('sign up user that already exists', async ({ themePage, wpCli }) => {
        const existingUser = createUser();
        wpCli.createUser(existingUser);

        await themePage.navigate('/');
        await themePage.openSignupModal();
        await themePage.fillSignupForm(existingUser);
        await themePage.signupSubmitBtn.click();

        await expect(themePage.page.locator('#sign-up-result')).toContainText('Email already registered');
    });

    test.skip('account activation email is sent to new user after registration', async ( {} ) => {}); // Maybe best for a PHP test
    test.skip('sign up with google', async ( {} ) => {}); // Mock the google part

});
