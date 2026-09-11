import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class SingleListingPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.sendInquiryBtn            = page.getByRole('button', { name: 'Send Inquiry' });
        this.favoritesPopup            = page.getByText('Add to collection', { exact: true });
        this.collectionSavedSuccessfully = page.getByText('Collection Created Successfully', { exact: true });
        this.listingAddedSuccessfully  = page.getByText('Listing Added Successfully', { exact: true });
        this.listingRemovedSuccessfully = page.getByText('Listing Removed Successfully', { exact: true });
    }

    async navigate(url) {
        await super.navigate(url);
    }

    async sendInquiry() {
        await this.sendInquiryBtn.click();
    }

    getFavoritesButton(listingId) {
        return this.page.locator(`#favorite-button-${listingId}`);
    }

    async addToFavorites(listingId) {
        const favoritesButton = this.getFavoritesButton(listingId);
        const added = this.page.waitForResponse(
            resp => resp.request().method() === 'POST' && resp.url().includes(`/wp-html/v1/collections/0/listings/${listingId}/`)
        );
        await favoritesButton.locator('> button:visible').first().click();
        await added;
        await expect(this.favoritesPopup).toBeVisible();
    }

    async createCollectionFromListing(listingId, name) {
        await this.addToFavorites(listingId);

        const favoritesButton = this.getFavoritesButton(listingId);
        await favoritesButton.getByRole('button', { name: /Create new collection/ }).click();

        const input = favoritesButton.locator('input[name="collection_name"]');
        await expect(input).toBeVisible();
        await input.fill(name);

        const posted = this.page.waitForResponse(
            resp => resp.request().method() === 'POST' && resp.url().includes('/wp-html/v1/collections/')
        );
        await input.press('Enter');
        await posted;
        await expect(this.collectionSavedSuccessfully).toBeVisible();
    }

    getFavoriteCollectionRow(listingId, collectionName) {
        return this.getFavoritesButton(listingId)
            .locator('div.flex.items-center.justify-between.px-2.py-1.rounded.cursor-pointer')
            .filter({ hasText: collectionName });
    }

    async addListingToCollection(listingId, collectionId, collectionName) {
        await this.addToFavorites(listingId);

        const row = this.getFavoriteCollectionRow(listingId, collectionName);
        await expect(row).toBeVisible();

        const added = this.page.waitForResponse(
            resp => resp.request().method() === 'POST' && resp.url().includes(`/wp-html/v1/collections/${collectionId}/listings/${listingId}/`)
        );
        await row.locator('button:visible').first().click();
        await added;
        await expect(this.listingAddedSuccessfully).toBeVisible();
    }

    async removeListingFromCollection(listingId, collectionId, collectionName) {
        const favoritesButton = this.getFavoritesButton(listingId);
        await favoritesButton.locator('> button:visible').first().click();
        await expect(this.favoritesPopup).toBeVisible();

        const row = this.getFavoriteCollectionRow(listingId, collectionName);
        await expect(row).toBeVisible();

        const removed = this.page.waitForResponse(
            resp => resp.request().method() === 'DELETE' && resp.url().includes(`/wp-html/v1/collections/${collectionId}/listings/${listingId}/`)
        );
        await row.locator('button:visible').first().click();
        await removed;
        await expect(this.listingRemovedSuccessfully).toBeVisible();
    }
}
