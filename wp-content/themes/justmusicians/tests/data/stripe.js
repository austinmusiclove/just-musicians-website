import { createHmac, randomBytes } from 'crypto';

export function generateStripeSignature(payload, secret) {
    const timestamp = Math.floor(Date.now() / 1000);
    const signedPayload = `${timestamp}.${payload}`;
    const v1 = createHmac('sha256', secret)
        .update(signedPayload, 'utf8')
        .digest('hex');
    return {
        timestamp,
        header: `t=${timestamp},v1=${v1}`,
    };
}

export function buildCheckoutCompletedEvent({ email, name, customer, product, type = 'checkout.session.completed' }) {
    return {
        id: `evt_test_${randomBytes(12).toString('hex')}`,
        object: 'event',
        api_version: '2022-11-15',
        created: Math.floor(Date.now() / 1000),
        type,
        data: {
            object: {
                id: `cs_test_${randomBytes(12).toString('hex')}`,
                object: 'checkout.session',
                mode: 'subscription',
                customer,
                customer_email: email,
                customer_details: {
                    email,
                    name: name || email,
                },
                custom_fields: [],
                metadata: { product },
            },
        },
    };
}

export function buildSubscriptionDeletedEvent({ customer, priceId, subId }) {
    return {
        id: `evt_test_${randomBytes(12).toString('hex')}`,
        object: 'event',
        api_version: '2022-11-15',
        created: Math.floor(Date.now() / 1000),
        type: 'customer.subscription.deleted',
        data: {
            object: {
                id: subId || `sub_test_${randomBytes(12).toString('hex')}`,
                object: 'subscription',
                customer,
                items: {
                    data: [
                        { price: { id: priceId, object: 'price' } },
                    ],
                },
            },
        },
    };
}

export async function postSignedWebhook({ request, baseURL, event, secret }) {
    const payload = JSON.stringify(event);
    const timestamp = generateStripeSignature(payload, secret);
    return request.post(`${baseURL}/wp-json/stripe-checkout/v1/webhook`, {
        headers: {
            'Content-Type': 'application/json',
            'Stripe-Signature': timestamp.header,
        },
        data: payload,
    });
}
