import { ThemePage } from './ThemePage.js';

export class PricingPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.talentBuyerProCard = page.locator('div.border', { has: page.getByRole('heading', { name: 'Talent Buyer Pro', exact: true }) });
    }

    async navigate(url = '/pricing/') {
        await super.navigate(url);
    }

    getCardLink(card) {
        return card.getByRole('link');
    }
}
