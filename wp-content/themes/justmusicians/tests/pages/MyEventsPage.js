import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class MyEventsPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.results = page.locator('#results');
    }

    eventCard(eventName) {
        return this.results.locator(':scope > div', { hasText: eventName });
    }

    getEventTitle(card) {
        return card.getByRole('heading', { level: 2 });
    }

    getManageEventBtn(card) {
        return card.getByRole('link', { name: 'Manage Event' });
    }

    async waitForResults() {
        await this.results.locator(':scope > *').first().waitFor();
    }

    async countEvents() {
        return this.results.locator('a', { hasText: 'Manage Event' }).count();
    }

    async waitForEvent(eventName) {
        await expect(this.eventCard(eventName)).toBeVisible();
    }

    async navigate(url = '/my-events/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
