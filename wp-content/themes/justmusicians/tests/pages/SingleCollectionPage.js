import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class SingleCollectionPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.cards            = page.locator('#results > [data-listing-id]');
        this.reorderHandles   = page.locator('#results [data-reorder-handle]');
        this.reorderToggle    = page.locator('[data-reorder-toggle]');
        this.results          = page.locator('#results');
    }

    async open(slug) {
        const listingsLoaded = this.page.waitForResponse(resp => resp.request().method() === 'GET'
            && /wp-html\/v1\/collections\/\d+\/listings/.test(resp.url()));
        await this.page.goto(`/collection/${slug}`, { waitUntil: 'domcontentloaded' });
        await listingsLoaded;
    }

    card(listingId) {
        return this.page.locator(`#results > [data-listing-id="${listingId}"]`);
    }

    reorderHandle(listingId) {
        return this.card(listingId).locator('[data-reorder-handle]');
    }

    async expectCardOrdering(listingIds) {
        await expect(this.cards).toHaveCount(listingIds.length);
        for (let i = 0; i < listingIds.length; i++) {
            await expect(this.cards.nth(i)).toHaveAttribute('data-listing-id', String(listingIds[i]));
        }
    }

    async enableReorderMode() {
        await this.reorderToggle.click();
        await expect(this.reorderHandles.first()).toBeVisible();
    }

    async dragHandle(listingId, { afterListingId, waitForUrl }) {
        const handle = this.reorderHandle(listingId);
        const box1 = await handle.boundingBox();
        const target = await this.reorderHandle(afterListingId).boundingBox();

        const posted = waitForUrl
            ? this.page.waitForResponse(waitForUrl)
            : null;
        await this.page.mouse.move(box1.x + box1.width / 2, box1.y + box1.height / 2);
        await this.page.mouse.down();
        await this.page.mouse.move(target.x + target.width / 2, target.y + target.height / 2, { steps: 15 });
        await this.page.mouse.up();
        if (posted) await posted;
    }
}
