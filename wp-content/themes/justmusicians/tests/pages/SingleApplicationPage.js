import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class SingleApplicationPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.editBtn             = page.getByRole('button', { name: 'Edit Application' });
        this.titleInput          = page.locator('input[name="title"]');
        this.updateBtn           = page.getByRole('button', { name: 'Update Application' });
    }

    async navigateToApplication(slug) {
        await super.navigate(`/application/${slug}`);
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }

    async clickEdit() {
        await this.editBtn.click();
        await expect(this.titleInput).toBeVisible();
    }

    async fillTitle(title) {
        await this.titleInput.fill(title);
    }

    async fillDescription(description) {
        await this.page.waitForFunction(() => typeof tinymce !== 'undefined' && tinymce.get('application_description') !== null);
        await this.page.evaluate((text) => {
            tinymce.get('application_description').setContent(text);
            tinymce.get('application_description').save();
        }, description);
    }

    async updateApplication() {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/applications') && resp.status() === 200
        );
        await this.updateBtn.click();
        await responsePromise;
    }
}
