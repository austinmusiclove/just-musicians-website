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
        this.updateBtnBottom   = page.getByRole('button', { name: 'Update Listing' }).last();
        this.updateBtnTop      = page.getByRole('button', { name: 'Update Listing' }).first();
        this.applyCropBtn      = page.getByRole('button', { name: 'Apply' });
        this.alpineRoot        = page.getByTestId('listing-form-alpine');
    }

    async navigate(url = '/listing-form/') {
        await super.navigate(url);
    }

    async navigateToListing(lid) {
        await super.navigate(`/listing-form/?lid=${lid}`);
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
        // wait for image to be processed before closing modal so that auto submit is not triggered when it finishes processing
        const alpineEl = await this.alpineRoot.elementHandle();
        await this.page.waitForFunction(
            (el) => {
                const data = Alpine.$data(el);
                const coverImages = data.orderedImageData['cover_image'];
                return coverImages.length > 0 && !coverImages[0].loading;
            },
            alpineEl
        );
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

    // Waits for response and publish buttons do not because publish waits for redirect to new url; update does not
    async updateListingBottom() {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/listings') && resp.status() === 200
        );
        await this.updateBtnBottom.click();
        await responsePromise;
    }

    async updateListingTop() {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/listings') && resp.status() === 200
        );
        await this.updateBtnTop.click();
        await responsePromise;
    }

    async waitForPublishRedirect() {
        await this.page.waitForURL(/\/listing-form\/.*lid=/, { timeout: 30000 });
        const url = new URL(this.page.url());
        return url.searchParams.get('lid');
    }
}
