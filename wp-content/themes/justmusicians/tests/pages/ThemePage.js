import { expect } from '@playwright/test';
import { createUser } from '../data/user_factory.js';

export class ThemePage {
    constructor(page) {
        this.page = page;
        this.signupBtn          = page.getByTestId('header-signup-btn');
        this.loginBtn           = page.getByTestId('header-login-btn');
        this.signupModalHeading = page.getByTestId('signup-modal-heading');
        this.signupSubmitBtn    = page.getByTestId('signup-submit-btn');
    }

    async navigate(url = '/') {
        await this.page.goto(url);
    }

    async openSignupModal() {
        await expect(this.signupBtn).toBeVisible();
        await this.signupBtn.click();
        await expect(this.signupModalHeading).toBeVisible();
    }

    async fillSignupForm(user) {
        await this.page.fill('#first_name', user.firstName);
        await this.page.fill('#last_name', user.lastName);
        await this.page.fill('#email', user.email);
        await this.page.fill('#password', user.password);
        await this.page.locator('#r_rememberme').check();
    }

    async submitSignup() {
        await Promise.all([
            this.page.waitForURL('**/*'),
            this.signupSubmitBtn.click(),
        ]);
        await this.page.waitForLoadState('load');
    }

    async registerUser(user) {
        const userData = user || createUser();
        const originalUrl = this.page.url();
        await this.openSignupModal();
        await this.fillSignupForm(userData);
        await this.submitSignup();
        await expect(this.page).toHaveURL(originalUrl);
        return userData;
    }

    async expectLoggedInPage() {
        await expect(this.signupBtn).not.toBeVisible();
        await expect(this.loginBtn).not.toBeVisible();
    }
}
