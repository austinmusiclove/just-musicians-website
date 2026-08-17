import { ThemePage } from './ThemePage.js';

export class ApplicationFormPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.titleInput       = page.locator('#title');
        this.descriptionInput = page.locator('#description');
        this.submitBtn        = page.getByRole('button', { name: 'Create Application' });
    }

    async navigate(url = '/application-form/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await super.login(username, password);
        await super.expectLoggedInPage();
    }

    async fillMinimumFields(title, description) {
        await this.titleInput.fill(title);
        await this.descriptionInput.fill(description);
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
        const pathParts = url.pathname.split('/');
        return pathParts.find(part => /^\d+$/.test(part));
    }
}
