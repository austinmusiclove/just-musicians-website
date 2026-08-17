import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('E2E - Login and Logout', () => {

    test('Log in with valid credentials from header on home page and log out', async ({ themePage, wpCli }) => {
        const testUser = createUser();
        wpCli.createUser(testUser);

        await themePage.navigate('/');
        await themePage.login(testUser.email, testUser.password);
        await themePage.expectLoggedInPage();
        await themePage.logout();
        await themePage.expectLoggedOutPage();
    });

});
