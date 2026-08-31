import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('E2E - Buyer Pro Monthly Subscription', () => {

    test('buyer subscribes to Talent Buyer Pro (webhook simulation)', async ({ themePage, pricingPage, subscriptionsPage, wpCli, stripe, mailpit }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);

        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();

        const customerId = 'cus_test_buyer_pro_monthly';
        const event = stripe.buildEvent({
            email: user.email,
            name: `${user.firstName} ${user.lastName}`,
            customer: customerId,
            product: 'buyer-pro-monthly',
        });
        const response = await stripe.postWebhook(event);
        expect(response.status()).toBe(200);

        await expect.poll(() => wpCli.userHasCap(userId, 'hm_buyer_pro'), { timeout: 30000 }).toBe(true);
        await expect.poll(() => wpCli.getUserMeta(userId, 'stripe_customer_id'), { timeout: 30000 }).toBe(customerId);

        const confirmationEmail = await mailpit.findEmailBySubject(`(${mailpit.siteUrl} ${user.email}) Order Confirmation — Hire Musicians`);
        expect(confirmationEmail).toBeTruthy();

        await pricingPage.navigate('/pricing/');
        await expect(pricingPage.getCardLink(pricingPage.talentBuyerProCard)).toHaveText('Manage My Subscriptions');

        await subscriptionsPage.navigate();
        await expect(subscriptionsPage.cardHeading(subscriptionsPage.buyerProCard)).toHaveText('Talent Buyer Pro');
        await expect(subscriptionsPage.cardButton(subscriptionsPage.buyerProCard)).toHaveText('Cancel Subscription');
    });

});
