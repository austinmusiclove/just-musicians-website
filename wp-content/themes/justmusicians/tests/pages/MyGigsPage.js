import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class MyGigsPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.results = page.locator('#results');
    }

    gigCard(eventName) {
        return this.results.locator(':scope > div', { hasText: eventName });
    }
    gigCardDetailsInput(card) {
        return card.locator('textarea[name="details"]');
    }
    gigCardAvailabilityOption(card, availability) {
        return card.locator(`input[name="availability"][value="${availability}"]`);
    }
    gigCardQuoteInput(card) {
        return card.locator('input[name="quote"]');
    }

    async expectGigCard(eventName, statusText) {
        const card = this.gigCard(eventName);
        await expect(card).toBeVisible();
        if (statusText) {
            await expect(card.getByText(statusText, { exact: true })).toBeVisible();
        }
    }

    async respondToInquiry(eventName, { details, availability = 'available', quote } = {}) {
        const card = this.gigCard(eventName);
        await card.getByRole('button', { name: 'Respond to Inquiry' }).click();

        const detailsInput = this.gigCardDetailsInput(card);
        await expect(detailsInput).toBeVisible();
        await detailsInput.fill(details);

        // the styled label overlays the sr-only radio input, so force the check
        await this.gigCardAvailabilityOption(card, availability).check({ force: true });

        if (quote !== undefined) {
            const quoteInput = this.gigCardQuoteInput(card);
            if (await quoteInput.isVisible().catch(() => false)) {
                await quoteInput.fill(String(quote));
            }
        }

        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/respond-to-inquiry/') && resp.request().method() === 'POST'
        );
        await card.getByRole('button', { name: 'Submit', exact: true }).click();
        const response = await responsePromise;
        expect(response.status()).toBe(200);

        await expect(this.page.getByText('Response Updated Successfully')).toBeVisible();
    }
}
