import { expect } from '@playwright/test';
import { createUser } from '../data/factories/user_factory.js';

export class ThemePage {
    constructor(page, isMobile = false) {
        this.page = page;
        this.isMobile = isMobile;
        this.signupBtn          = page.getByTestId('header-signup-btn');
        this.loginBtn           = page.getByTestId('header-login-btn');
        this.signupModalHeading = page.getByTestId('signup-modal-heading');
        this.signupSubmitBtn    = page.getByTestId('signup-submit-btn');
        this.signupFirstName    = page.getByTestId('signup-first-name');
        this.signupLastName     = page.getByTestId('signup-last-name');
        this.signupEmail        = page.getByTestId('signup-email');
        this.signupPassword     = page.getByTestId('signup-password');
        this.signupRememberMe   = page.getByTestId('signup-remember-me');
        this.loginModalHeading  = page.getByTestId('login-modal-heading');
        this.loginEmail         = page.getByTestId('login-email');
        this.loginPassword      = page.getByTestId('login-password');
        this.loginRememberMe    = page.getByTestId('login-remember-me');
        this.loginSubmitBtn     = page.getByTestId('login-submit-btn');
        this.logoutLinkDesktop  = page.getByTestId('desktop-logout-link');
        this.logoutLinkMobile   = page.getByTestId('mobile-logout-link');
        this.hamburgerBtn           = page.getByTestId('header-hamburger-btn');
        this.accountMenu            = page.getByTestId('header-account-menu');
        this.forgotPasswordBtn      = page.getByRole('button', { name: 'Forgot password?' });
        this.passwordResetEmail     = page.locator('input[name="email"]');
        this.passwordResetSubmitBtn = page.getByRole('button', { name: 'Request Password Reset' });
        this.passwordResetMessage   = page.locator('.request-password-reset-message');
    }

    async navigate(url = '/') {
        await this.page.goto(url, { waitUntil: 'domcontentloaded' });
        await this.page.waitForFunction(() => window.Alpine, { timeout: 10000 });
    }

    async openSignupModal() {
        await expect(this.signupBtn).toBeVisible();
        await this.signupBtn.click();
        await expect(this.signupModalHeading).toBeVisible();
    }

    async openLoginModal() {
        await expect(this.loginBtn).toBeVisible();
        await this.loginBtn.click();
        await expect(this.loginModalHeading).toBeVisible();
    }

    async fillLoginForm(email, password) {
        await this.loginEmail.fill(email);
        await this.loginPassword.fill(password);
        await this.loginRememberMe.check();
    }

    async fillSignupForm(user) {
        await this.signupFirstName.fill(user.firstName);
        await this.signupLastName.fill(user.lastName);
        await this.signupEmail.fill(user.email);
        await this.signupPassword.fill(user.password);
        await this.signupRememberMe.check();
    }

    async registerUserSignupModal(user) {
        const userData = user || createUser();
        const originalUrl = this.page.url();
        await this.openSignupModal();
        await this.fillSignupForm(userData);
        await this.signupSubmitBtn.click();
        await expect(this.page).toHaveURL(originalUrl);
        return userData;
    }

    async login(username, password) {
        await this.openLoginModal();
        await this.fillLoginForm(username, password);
        const responsePromise = this.page.waitForResponse(resp => resp.url().includes('wp-login.php'));
        await this.loginSubmitBtn.click();
        await responsePromise;
    }

    async logout() {
        if (this.isMobile) {
            await this.hamburgerBtn.click();
            await expect(this.logoutLinkMobile).toBeVisible();
            await this.logoutLinkMobile.click();
        } else {
            await this.accountMenu.hover();
            await this.logoutLinkDesktop.click();
        }
    }

    async expectLoggedInPage() {
        await expect(this.signupBtn).not.toBeVisible();
        await expect(this.loginBtn).not.toBeVisible();
    }
    async openPasswordResetModal() {
        await this.openLoginModal();
        await this.forgotPasswordBtn.click();
        await expect(this.passwordResetSubmitBtn).toBeVisible();
    }

    async requestPasswordReset(email) {
        await this.passwordResetEmail.fill(email);
        await Promise.all([
            this.page.waitForNavigation({ timeout: 10000 }),
            this.passwordResetSubmitBtn.click(),
        ]);
    }

    async expectLoggedOutPage() {
        await expect(this.signupBtn).toBeVisible();
        await expect(this.loginBtn).toBeVisible();
    }
}
