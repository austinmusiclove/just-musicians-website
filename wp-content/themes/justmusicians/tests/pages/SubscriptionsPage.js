import { ThemePage } from './ThemePage.js';

export class SubscriptionsPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.buyerProCard = page.getByTestId('subscription-card-buyer-pro');
        this.buyerProLifetimeCard = page.getByTestId('subscription-card-buyer-pro-lifetime');
    }

    async navigate(url = '/subscriptions/') {
        await super.navigate(url);
    }

    cardHeading(card) {
        return card.getByRole('heading');
    }

    cardButton(card) {
        return card.getByRole('button');
    }
}
