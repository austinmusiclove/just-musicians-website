import { ThemePage } from './ThemePage.js';

export class ApplicationsPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.addBtn              = page.getByRole('link', { name: 'Add +' });
        this.emptyStateCreateBtn = page.getByRole('button', { name: 'Create an Application' });
        this.applicationCards    = page.locator('#results > div');
    }

    getCardTitle(card) {
        return card.getByRole('heading', { level: 2 });
    }

    getReviewApplicantsBtn(card) {
        return card.getByRole('link', { name: /Review Applicants/ });
    }

    getEditApplicationBtn(card) {
        return card.getByRole('link', { name: 'Edit Application' });
    }

    async waitForCards() {
        await this.page.waitForResponse(resp =>
            resp.url().includes('/wp-html/v1/applications/') && resp.status() === 200
        );
    }

    async navigate(url = '/applications/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
