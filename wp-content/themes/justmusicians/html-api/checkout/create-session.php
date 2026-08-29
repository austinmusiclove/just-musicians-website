<?php
/**
 * Creates a Stripe Checkout session and redirects the user to Stripe's hosted checkout page.
 *
 * POST /wp-html/v1/checkout/create-session
 */

require_once get_template_directory() . '/lib/php/stripe-php/init.php';

$product = sanitize_text_field(wp_unslash($_POST['product'] ?? ''));

$price_map = [
    'buyer-pro-monthly'  => defined('STRIPE_PRO_PRICE_ID') ? STRIPE_PRO_PRICE_ID : '',
    'buyer-pro-lifetime' => defined('STRIPE_LIFETIME_PRICE_ID') ? STRIPE_LIFETIME_PRICE_ID : '',
];

if (empty($product) || empty($price_map[$product])) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Invalid product selected' })"></span>
    <?php exit;
}

$price_id = $price_map[$product];
$is_lifetime = ($product === 'buyer-pro-lifetime');

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

try {
    $params = [
        'mode'        => $is_lifetime ? 'payment' : 'subscription',
        'line_items'  => [
            [
                'price'    => $price_id,
                'quantity' => 1,
            ],
        ],
        'success_url' => site_url('/checkout-success?session_id={CHECKOUT_SESSION_ID}'),
        'cancel_url'  => site_url('/subscriptions/'),
        'metadata'    => [
            'product' => $product,
        ],
    ];

    // Collect customer email if user is logged in
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $params['customer_email'] = $user->user_email;
    } else {
        // New user - collect first and last name via custom fields
        $params['custom_fields'] = [
            [
                'key'   => 'first_name',
                'label' => ['type' => 'custom', 'custom' => 'First Name'],
                'type'  => 'text',
                'text'  => ['minimum_length' => 1],
            ],
            [
                'key'   => 'last_name',
                'label' => ['type' => 'custom', 'custom' => 'Last Name'],
                'type'  => 'text',
                'text'  => ['minimum_length' => 1],
            ],
        ];
    }

    $session = \Stripe\Checkout\Session::create($params);

    // Redirect to Stripe Checkout
    echo '<script>window.location.replace(' . json_encode($session->url) . ');</script>';
    exit;

} catch (\Exception $e) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Error creating checkout session. Please try again.' })"></span>
    <?php exit;
}
