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

        await pricingPage.navigate();
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerFreeCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerProCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerLifetimeCard)).toHaveText('Get Started');

        await subscriptionsPage.navigate();
        await expect(subscriptionsPage.cardHeading(subscriptionsPage.buyerProCard)).toHaveText('Talent Buyer Pro');
        await expect(subscriptionsPage.cardButton(subscriptionsPage.buyerProCard)).toHaveText('Cancel Subscription');
    });

    test('new user subscribes to Talent Buyer Pro as a guest and an account is created', async ({ themePage, pricingPage, subscriptionsPage, passwordResetPage, wpCli, stripe, mailpit }) => {
        const user = createUser();
        const customerId = 'cus_test_buyer_pro_monthly_guest';

        // No login: simulate checkout for a brand new email so the webhook creates the account
        const event = stripe.buildEvent({
            email: user.email,
            name: `${user.firstName} ${user.lastName}`,
            customer: customerId,
            product: 'buyer-pro-monthly',
        });
        const response = await stripe.postWebhook(event);
        expect(response.status()).toBe(200);

        // New user is registered with the capability and customer id saved in meta
        const userId = wpCli.getUserId(user.email);
        expect(userId).toBeTruthy();
        wpCli.trackUser(user);
        await expect.poll(() => wpCli.userHasCap(userId, 'hm_buyer_pro'), { timeout: 30000 }).toBe(true);
        await expect.poll(() => wpCli.getUserMeta(userId, 'stripe_customer_id'), { timeout: 30000 }).toBe(customerId);

        // Emails expected to go out for a new-user checkout
        expect(await mailpit.findEmailBySubject('[Hire Musicians] New User Registration')).toBeTruthy();
        expect(await mailpit.findEmailBySubject(`(${mailpit.siteUrl} ${user.email}) Verify your email to activate your Hire Musicians account`)).toBeTruthy();
        expect(await mailpit.findEmailBySubject(`(${mailpit.siteUrl} ${user.email}) Order Confirmation — Hire Musicians`)).toBeTruthy();

        // Use the password reset email link to set a known password
        const resetEmail = await mailpit.findEmailTo(user.email, '[Hire Musicians] Password Reset');
        expect(resetEmail).toBeTruthy();
        const resetBody = await mailpit.getEmailBody(resetEmail.ID);
        const resetLink = mailpit.extractLinkFromEmail(resetBody);
        expect(resetLink).toBeTruthy();

        const newPassword = '#1NewPassword' + Date.now();
        await passwordResetPage.navigate(resetLink);
        await passwordResetPage.resetPassword(newPassword);
        await themePage.expectLoggedInPage();

        // Log out, then log back in with the new password
        await themePage.logout();
        await themePage.expectLoggedOutPage();
        await themePage.login(user.email, newPassword);
        await themePage.expectLoggedInPage();

        // Pricing page reflects the active subscription
        await pricingPage.navigate();
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerFreeCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerProCard)).toHaveText('Manage My Subscriptions');
        await expect(pricingPage.getCardButton(pricingPage.talentBuyerLifetimeCard)).toHaveText('Get Started');

        // Subscriptions page shows the pro card
        await subscriptionsPage.navigate();
        await expect(subscriptionsPage.cardHeading(subscriptionsPage.buyerProCard)).toHaveText('Talent Buyer Pro');
        await expect(subscriptionsPage.cardButton(subscriptionsPage.buyerProCard)).toHaveText('Cancel Subscription');
    });

});
