import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class SingleEventPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.musiciansTab = page.locator('.preview-tab.tab-heading', { hasText: 'Musicians' });
        this.applicantResults = page.locator('#applicant-results');
        this.sendMessageForm = this.page.locator('#send-message-form');
        this.sendMessageMdlMessage = this.sendMessageForm.locator('textarea[name="message"]');
        this.sendMessageMdlSubmit = this.sendMessageForm.getByRole('button', { name: 'Send', exact: true });
        this.sendMessageMdlClose = this.page.getByTestId('send-message-mdl-close');
    }

    applicantCard(listingName) {
        return this.applicantResults.locator(':scope > div', { hasText: listingName });
    }

    async openMusiciansTab() {
        await this.musiciansTab.click();
    }

    async expectApplicant(listingName, { details, quote } = {}) {
        const card = this.applicantCard(listingName);
        await expect(card).toBeVisible();
        if (details) {
            await expect(card.getByText(details)).toBeVisible();
        }
        if (quote) {
            await expect(card.getByText(`Quote: $${quote}`, { exact: true })).toBeVisible();
        }
    }

    async sendMessageToApplicant(listingName, message) {
        await this.applicantCard(listingName).getByRole('button', { name: 'Send Message' }).click();

        await expect(this.sendMessageForm).toBeVisible();
        await this.sendMessageMdlMessage.fill(message);

        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/wp-html/v1/send-message-listing/') && resp.request().method() === 'POST'
        );
        await this.sendMessageMdlSubmit.click();
        const response = await responsePromise;
        expect(response.status()).toBe(200);

        await expect(this.page.getByText('Your message has been sent!')).toBeVisible();
    }

    async closeSendMessageModal() {
        await this.sendMessageMdlClose.click();
        await expect(this.sendMessageForm).toBeHidden();
    }
}
