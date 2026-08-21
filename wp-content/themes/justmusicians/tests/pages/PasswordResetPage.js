import { ThemePage } from './ThemePage.js';

export class PasswordResetPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.newPasswordInput = page.locator('#reset_user_pass');
        this.submitBtn        = page.getByRole('button', { name: 'Reset Password' });
        this.errorMessage     = page.locator('.password-reset-error-message');
    }

    async navigate(url) {
        await super.navigate(url);
    }

    async resetPassword(password) {
        await this.newPasswordInput.fill(password);
        await Promise.all([
            this.page.waitForNavigation({ timeout: 10000 }),
            this.submitBtn.click(),
        ]);
    }
}
