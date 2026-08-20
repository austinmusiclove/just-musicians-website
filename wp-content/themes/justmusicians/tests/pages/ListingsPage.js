import { ThemePage } from './ThemePage.js';

export class ListingsPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.listingCards = page.locator('#account-listing-cards > div');
    }

    async navigate(url = '/listings/') {
        await super.navigate(url);
    }

    async navigateWithLic(lic, signup) {
        let url = `/listings/?lic=${lic}`;
        if (signup) { url += '&mdl=signup'; }
        await super.navigate(url);
    }

    async navigateWithAic(aic, signup) {
        let url = `/listings/?aic=${aic}`;
        if (signup) { url += '&mdl=signup'; }
        await super.navigate(url);
    }

    getCardTitle(card) {
        return card.getByRole('heading', { level: 2 });
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }

}
