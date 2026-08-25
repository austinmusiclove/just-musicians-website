import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class SingleApplicationPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.editBtn             = page.getByRole('button', { name: 'Edit Application' });
        this.shareBtn            = page.getByRole('button', { name: 'Share' });
        this.titleInput          = page.locator('input[name="title"]');
        this.updateBtn           = page.getByRole('button', { name: 'Update Application' });
        this.deleteBtn           = page.getByRole('button', { name: 'Delete Application' });
        this.exportButton        = page.locator('#applicants-export-button');
        this.exportCsvOption     = page.locator('li[hx-get*="applicants-export/csv"]');
        this.shareModal          = page.locator('.popup-wrapper').filter({ hasText: 'Share Application' });
        this.shareEmailInput     = this.shareModal.locator('input[type="email"]');
        this.shareAccessType     = this.shareModal.locator('select[name="access_type"]');
        this.shareSubmitBtn      = this.shareModal.getByRole('button', { name: 'Share' });
        this.shareAccessList     = this.shareModal.locator('[id^="share-access-list-"]');
    }

    async navigateToApplication(slug, tab = '') {
        await super.navigate(`/application/${slug}/${tab ? `?tab=${tab}` : ''}`);
    }

    async login(username, password) {
        await super.navigate('/');
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
        await this.page.waitForFunction(
            (text) => document.querySelector('textarea[name="description"]').value === text,
            description
        );
    }

    async updateApplication() {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/applications') && resp.status() === 200
        );
        await this.updateBtn.click();
        await responsePromise;
    }

    async exportApplicantsToCsv() {
        const downloadPromise = this.page.waitForEvent('download');
        await this.exportButton.click();
        await this.exportCsvOption.click();
        return downloadPromise;
    }

    async shareAccess(email, accessType = 'view') {
        await this.shareBtn.click();
        await expect(this.shareModal).toBeVisible();
        await this.shareEmailInput.fill(email);
        await this.shareAccessType.selectOption(accessType);
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/access') && resp.request().method() === 'POST'
        );
        await this.shareSubmitBtn.click();
        await responsePromise;
    }
}
