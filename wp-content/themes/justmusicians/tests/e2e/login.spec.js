import { execSync } from 'child_process';
import { test } from '../fixtures/fixtures.js';
import { createUser } from '../data/user_factory.js';


test.describe('User Login', () => {

    let testUser;

    test.beforeAll(async () => {
        testUser = createUser();
        execSync(
            `wp user create "${testUser.email}" "${testUser.email}" --role=subscriber --user_pass="${testUser.password}" --first_name="${testUser.firstName}" --last_name="${testUser.lastName}" --path=/Users/johnfilippone/Local\\ Sites/just-musicians/app/public`,
            { stdio: 'ignore' }
        );
    });

    test.afterAll(async () => {
        if (testUser) {
            execSync(
                `wp user delete ${testUser.email} --yes --path=/Users/johnfilippone/Local\\ Sites/just-musicians/app/public`,
                { stdio: 'ignore' }
            );
        }
    });

    test('log in with valid credentials from header on home page', async ({ themePage }) => {
        await themePage.navigate('/');
        await themePage.openLoginModal();
        await themePage.fillLoginForm(testUser.email, testUser.password);
        await themePage.submitLogin();
        await themePage.expectLoggedInPage();
    });

    test('log in with valid credentials from header on home page and log out', async ({ themePage }) => {
        await themePage.navigate('/');
        await themePage.openLoginModal();
        await themePage.fillLoginForm(testUser.email, testUser.password);
        await themePage.submitLogin();
        await themePage.expectLoggedInPage();
    });

});
