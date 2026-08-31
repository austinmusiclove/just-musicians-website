import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('Visual - Subscriptions - Buyer', () => {

    test('logged out shows the sign-in prompt and no subscription cards', async ({ subscriptionsPage }) => {
        await subscriptionsPage.navigate('/subscriptions/');
        await expect(subscriptionsPage.page.getByText('Sign in to see your subscriptions', { exact: true })).toBeVisible();
        await expect(subscriptionsPage.buyerFreeCard).toHaveCount(0);
        await expect(subscriptionsPage.buyerProCard).toHaveCount(0);
        await expect(subscriptionsPage.buyerProLifetimeCard).toHaveCount(0);
    });

    test('logged in with no capabilities shows the free tier card', async ({ themePage, subscriptionsPage, wpCli }) => {
        const user = createUser();
        wpCli.createUser(user);
        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();
        await subscriptionsPage.navigate('/subscriptions/');
        await expect(subscriptionsPage.cardHeading(subscriptionsPage.buyerFreeCard)).toHaveText('Talent Buyer Free Tier');
        await expect(subscriptionsPage.buyerProCard).toHaveCount(0);
        await expect(subscriptionsPage.buyerProLifetimeCard).toHaveCount(0);
    });

    test('logged in with hm_buyer_pro shows the Talent Buyer Pro card', async ({ themePage, subscriptionsPage, wpCli }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);
        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();
        wpCli.addCap(userId, 'hm_buyer_pro');
        await subscriptionsPage.navigate('/subscriptions/');
        await expect(subscriptionsPage.cardHeading(subscriptionsPage.buyerProCard)).toHaveText('Talent Buyer Pro');
        await expect(subscriptionsPage.cardButton(subscriptionsPage.buyerProCard)).toHaveText('Cancel Subscription');
        await expect(subscriptionsPage.buyerFreeCard).toHaveCount(0);
        await expect(subscriptionsPage.buyerProLifetimeCard).toHaveCount(0);
    });

    test('logged in with hm_buyer_pro_lifetime shows the Lifetime Membership card', async ({ themePage, subscriptionsPage, wpCli }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);
        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();
        wpCli.addCap(userId, 'hm_buyer_pro_lifetime');
        await subscriptionsPage.navigate('/subscriptions/');
        await expect(subscriptionsPage.cardHeading(subscriptionsPage.buyerProLifetimeCard)).toHaveText('Talent Buyer Pro Lifetime Membership');
        await expect(subscriptionsPage.buyerProLifetimeCard.getByText('Active', { exact: true })).toBeVisible();
        await expect(subscriptionsPage.buyerProCard).toHaveCount(0);
        await expect(subscriptionsPage.buyerFreeCard).toHaveCount(0);
    });

});
