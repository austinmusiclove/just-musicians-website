import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class MessagesPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.messageInput = page.locator('textarea[name="content"]');
        this.sendBtn = page.getByRole('button', { name: 'Send', exact: true });
    }

    conversationElm(name) {
        return this.page.locator('h3.text-18', { hasText: name }).first();
    }
    messageBubble(conversationId, messageId) {
        return this.page.locator(`#message-${conversationId}-${messageId}`);
    }

    async openConversation(participantName) {
        const readReceiptPromise = this.page.waitForResponse(
            resp => resp.url().includes('/read_receipts/') && resp.request().method() === 'POST',
            { timeout: 15000 }
        ).catch(() => null);
        await this.conversationElm(participantName).click();
        await expect(this.messageInput).toBeVisible();
        const response = await readReceiptPromise;
        expect(response).toBeTruthy();
        expect(response.status()).toBe(200);
    }

    async expectMessage(conversationId, messageId, content) {
        const bubble = this.messageBubble(conversationId, messageId);
        await expect(bubble).toBeVisible();
        await expect(bubble.getByText(content)).toBeVisible();
    }

    async reply(content) {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/v1/messages/') && resp.request().method() === 'POST'
        );
        await this.messageInput.fill(content);
        await this.sendBtn.click();
        const response = await responsePromise;
        expect(response.status()).toBe(200);
    }
}
