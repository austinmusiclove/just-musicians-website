import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('E2E - Forgot Password', () => {

    test('Password reset through forgot password workflow', async ({ themePage, passwordResetPage, wpCli, mailpit }) => {
        const user = createUser();
        wpCli.createUser(user);

        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();
        await themePage.logout();
        await themePage.expectLoggedOutPage();

        await themePage.openPasswordResetModal();
        await themePage.requestPasswordReset(user.email);

        const resetEmail = await mailpit.findEmailBySubject('[Hire Musicians] Password Reset');
        expect(resetEmail).toBeTruthy();
        const body = await mailpit.getEmailBody(resetEmail.ID);
        const resetLink = mailpit.extractLinkFromEmail(body);
        expect(resetLink).toBeTruthy();

        const newPassword = '#1NewPassword' + Date.now();
        await passwordResetPage.navigate(resetLink);
        await passwordResetPage.resetPassword(newPassword);
        await themePage.expectLoggedInPage();

        await themePage.logout();
        await themePage.expectLoggedOutPage();
        await themePage.login(user.email, newPassword);
        await themePage.expectLoggedInPage();

        await themePage.logout();
        await themePage.expectLoggedOutPage();
        await themePage.login(user.email, user.password);
        await expect(themePage.page.locator('#login-result')).toContainText('Invalid login credentials');
    });

});
