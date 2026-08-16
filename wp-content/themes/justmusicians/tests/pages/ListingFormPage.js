import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class ListingFormPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.performerName     = page.locator('#performer-name-input');
        this.description       = page.locator('#description-input');
        this.postalCodeInput   = page.locator('#listing-form-zip');
        this.postalCodeTarget  = page.locator('#listing-form-zip-target');
        this.email             = page.locator('#listing_email');
        this.coverImageInput   = page.locator('input[name="cover_image_input"]');
        this.publishBtnBottom  = page.getByRole('button', { name: 'Publish listing' }).last();
        this.publishBtnTop     = page.getByRole('button', { name: 'Publish listing' }).first();
        this.draftBtnBottom    = page.getByRole('button', { name: 'Save draft' }).last();
        this.draftBtnTop       = page.getByRole('button', { name: 'Save draft' }).first();
        this.applyCropBtn      = page.getByRole('button', { name: 'Apply' });
    }

    async navigate(url = '/listing-form/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await super.login(username, password);
        await super.expectLoggedInPage();
    }

    async fillPostalCode(postalCodePrefix) {
        await this.postalCodeInput.click();
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('location-search-options-pc') && resp.status() === 200
        );
        await this.postalCodeInput.fill(postalCodePrefix);
        await responsePromise;
        await this.postalCodeTarget.locator('li').first().waitFor({ timeout: 10000 });
        await this.postalCodeTarget.locator('li').first().click();
    }

    async fillMinimumFields(name, description, postalCodePrefix, email) {
        await this.performerName.fill(name);
        await this.description.fill(description);
        await this.fillPostalCode(postalCodePrefix);
        await this.email.fill(email);
    }

    async uploadCoverImage(imagePath) {
        await this.coverImageInput.setInputFiles(imagePath);
        await expect(this.applyCropBtn).toBeVisible();
        await this.applyCropBtn.click();
    }

    async publishBottom() {
        await this.publishBtnBottom.click();
    }

    async publishTop() {
        await this.publishBtnTop.click();
    }

    async saveDraftBottom() {
        await this.draftBtnBottom.click();
    }

    async saveDraftTop() {
        await this.draftBtnTop.click();
    }

    async waitForPublishRedirect() {
        await this.page.waitForURL(/\/listing-form\/.*lid=/, { timeout: 30000 });
        const url = new URL(this.page.url());
        return url.searchParams.get('lid');
    }
}
