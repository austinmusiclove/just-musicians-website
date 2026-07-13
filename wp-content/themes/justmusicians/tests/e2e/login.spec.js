import { test } from '../fixtures/fixtures.js';
import { createUser } from '../data/user_factory.js';
import { wpCliCreateUser, wpCliDeleteUser } from '../data/wp_cli.js';


test.describe('User Login', () => {

    let testUser;

    test('log in with valid credentials from header on home page and log out', async ({ themePage }) => {
        await themePage.navigate('/');
        await themePage.login(testUser.email, testUser.password);
        await themePage.expectLoggedInPage();
        await themePage.logout();
        await themePage.expectLoggedOutPage();
    });

    test.beforeAll(async () => {
        testUser = createUser();
        wpCliCreateUser(testUser);
    });

    test.afterAll(async () => {
        if (testUser) {
            wpCliDeleteUser(testUser.email);
        }
    });

});
