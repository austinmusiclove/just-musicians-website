import { ThemePage } from './ThemePage.js';

export class ApplicationsPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.addBtn = page.getByRole('link', { name: 'Add +' });
    }

    async navigate(url = '/applications/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
