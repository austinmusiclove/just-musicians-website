import { ThemePage } from './ThemePage.js';

export class ApplicationFormPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.titleInput           = page.locator('#title');
        this.submitBtn            = page.getByRole('button', { name: 'Create Application' });
    }

    async navigate(url = '/application-form/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }

    async fillMinimumFields(title, description) {
        await this.titleInput.fill(title);
        await this.fillDescription(description);
    }

    async fillDescription(description) {
        await this.page.waitForFunction(() => typeof tinymce !== 'undefined' && tinymce.get('description') !== null);
        await this.page.evaluate((text) => {
            tinymce.get('description').setContent(text);
            tinymce.get('description').save();
        }, description);
    }

    async submitApplication() {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/applications') && resp.status() === 200
        );
        await this.submitBtn.click();
        await responsePromise;
    }

    async waitForSubmitRedirect() {
        await this.page.waitForURL(/\/application\/.*toast=create/, { timeout: 30000 });
        const url = new URL(this.page.url());
        const pathParts = url.pathname.replace(/\/$/, '').split('/');
        return pathParts[pathParts.length - 1];
    }
}
