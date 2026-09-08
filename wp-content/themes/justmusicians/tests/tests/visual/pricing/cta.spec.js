import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('Visual - Pricing - CTA', () => {

    test('logged out shows Get Started on all cards', async ({ pricingPage }) => {
        await pricingPage.navigate('/pricing/');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerFreeCard)).toHaveText('Get Started');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerProCard)).toHaveText('Get Started');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerLifetimeCard)).toHaveText('Get Started');
    });

    test('logged in with no capabilities shows Manage on free tier only', async ({ themePage, pricingPage, wpCli }) => {
        const user = createUser();
        wpCli.createUser(user);
        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();
        await pricingPage.navigate('/pricing/');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerFreeCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerProCard)).toHaveText('Get Started');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerLifetimeCard)).toHaveText('Get Started');
    });

    test('logged in with hm_buyer_pro manages pro + free, Get Started on lifetime', async ({ themePage, pricingPage, wpCli }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);
        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();
        wpCli.addCap(userId, 'hm_buyer_pro');

        await pricingPage.navigate('/pricing/');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerFreeCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerProCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerLifetimeCard)).toHaveText('Get Started');
    });

    test('logged in with hm_buyer_pro_lifetime manages all cards', async ({ themePage, pricingPage, wpCli }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);
        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();
        wpCli.addCap(userId, 'hm_buyer_pro_lifetime');

        await pricingPage.navigate('/pricing/');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerFreeCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerProCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerLifetimeCard)).toHaveText('Manage My Subscriptions');
    });

});
