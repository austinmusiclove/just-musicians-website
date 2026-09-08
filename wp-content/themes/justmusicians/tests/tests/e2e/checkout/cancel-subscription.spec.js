import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('E2E - Cancel Buyer Pro Subscription', () => {

    test('cancels a Talent Buyer Pro subscription (webhook simulation)', async ({ themePage, pricingPage, subscriptionsPage, wpCli, stripe, mailpit }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);
        const customerId = `cus_test_buyer_pro_cancel_${Date.now()}`;

        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();

        // Subscribe the existing user so the app grants hm_buyer_pro and links the Stripe customer
        const checkoutEvent = stripe.buildEvent({
            email: user.email,
            name: `${user.firstName} ${user.lastName}`,
            customer: customerId,
            product: 'buyer-pro-monthly',
        });
        const checkoutResponse = await stripe.postWebhook(checkoutEvent);
        expect(checkoutResponse.status()).toBe(200);
        await expect.poll(() => wpCli.userHasCap(userId, 'hm_buyer_pro'), { timeout: 30000 }).toBe(true);
        await expect.poll(() => wpCli.getUserMeta(userId, 'stripe_customer_id'), { timeout: 30000 }).toBe(customerId);

        // Pre-cancel: subscriptions page shows the active Pro card with a Cancel button
        await subscriptionsPage.navigate();
        await expect(subscriptionsPage.cardHeading(subscriptionsPage.buyerProCard)).toHaveText('Talent Buyer Pro');
        await expect(subscriptionsPage.cardButton(subscriptionsPage.buyerProCard)).toHaveText('Cancel Subscription');

        // Stripe reports the subscription deleted
        const priceId = wpCli.getWpConfig('STRIPE_PRO_PRICE_ID');
        const subId = `sub_test_buyer_pro_cancel_${Date.now()}`;
        const event = stripe.buildSubscriptionDeletedEvent({ customer: customerId, priceId, subId });
        const response = await stripe.postWebhook(event);
        expect(response.status()).toBe(200);

        // Capability is removed
        await expect.poll(() => wpCli.userHasCap(userId, 'hm_buyer_pro'), { timeout: 30000 }).toBe(false);

        // Post-cancel: subscriptions page no longer shows the Pro card, shows free tier
        await subscriptionsPage.navigate();
        await expect(subscriptionsPage.buyerProCard).toHaveCount(0);
        await expect(subscriptionsPage.buyerProLifetimeCard).toHaveCount(0);
        await expect(subscriptionsPage.buyerFreeCard).toBeVisible();

        // Post-cancel: pricing page no longer grants Pro => Get Started button
        await pricingPage.navigate();
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerFreeCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerProCard)).toHaveText('Get Started');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerLifetimeCard)).toHaveText('Get Started');
    });

});
