import { ThemePage } from './ThemePage.js';

export class PricingPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.talentBuyerFreeCard = page.locator('div.border', { has: page.getByRole('heading', { name: 'Free Tier', exact: true }) });
        this.talentBuyerProCard = page.locator('div.border', { has: page.getByRole('heading', { name: 'Talent Buyer Pro', exact: true }) });
        this.talentBuyerLifetimeCard = page.locator('div.border', { has: page.getByRole('heading', { name: 'Lifetime Pro Membership', exact: true }) });
    }

    async navigate(url = '/pricing/') {
        await super.navigate(url);
    }

    getCardButton(card) {
        return card.getByRole('button');
    }
}
