import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class CollectionsPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.addBtn              = page.getByRole('button', { name: 'Add +' });
        this.collectionNameInput = page.locator('input[name="collection_name"]');
        this.createCollectionBtn = page.getByRole('button', { name: 'Create', exact: true });
        this.collectionCards     = page.locator('#results > div');
        this.results             = page.locator('#results');
    }

    async navigate(url = '/collections/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }

    async openAddCollectionPopup() {
        await expect(this.addBtn).toBeVisible();
        await this.addBtn.click();
        await expect(this.collectionNameInput).toBeVisible();
    }

    async createCollection(name) {
        await this.openAddCollectionPopup();
        await this.collectionNameInput.fill(name);

        const posted = this.page.waitForResponse(
            resp => resp.request().method() === 'POST'
                && resp.url().includes('/wp-html/v1/collections/')
        );
        await this.createCollectionBtn.click();
        await posted;

        await this.waitForCollectionCard(name);
    }

    getCollectionCard(name) {
        return this.results.getByRole('heading', { level: 2, name, exact: true });
    }

    getDeleteCollectionButton(collectionId) {
        return this.page.locator(`button[hx-delete$="/wp-html/v1/collections/${collectionId}"]`);
    }

    async deleteCollection(collectionId) {
        const deleted = this.page.waitForResponse(
            resp => resp.request().method() === 'DELETE'
                && resp.url().includes(`/wp-html/v1/collections/${collectionId}`)
        );
        await this.getDeleteCollectionButton(collectionId).click();
        await deleted;
    }

    async waitForCollectionCard(name) {
        await expect(this.getCollectionCard(name)).toBeVisible({ timeout: 15000 });
    }
}