<?php
/**
 * The Buyer Pricing template file
 *
 * @package JustMusicians
 */

$offers = [
    [
        'name'        => 'Free Tier',
        'schema_name' => 'Talent Buyer Pro Free Tier',
        'slug'        => 'free-tier',
        'price'       => '$0',
        'price_desc'  => '/month',
        'description' => 'Everything you need to hire musicians.',
        'schema_description' => 'Talent Buyer Pro Free Tier includes everything you need to hire musicians. Send unlimited inquiries to musicians, create unlimited collections of musicians, create up to 4 events per calendar month, create up to 1 musician application and export applicants to csv with email addresses.',
        'features'    => [
            [
                'text'    => 'Unlimited Inquiries',
                'tooltip' => 'Send unlimited inquiries to musicians for your events',
            ],
            [
                'text'    => 'Unlimited Collections',
                'tooltip' => 'Create unlimited collections. A collection is like a custom favorites list where you can save musician listings.',
            ],
            [
                'text'    => 'Up to 4 Events/month',
                'tooltip' => 'Create up to 4 events per calendar month. Creating an event allows you to send an inquiry to many musicians with the same details with one click.',
            ],
            [
                'text'    => 'Up to 1 Musician Application',
                'tooltip' => 'Musician Applications allow you to collect applicants for your event or venue. Share your application link where musicians can find it and manage your applicants from your dashboard.',
            ],
            'Export applicants with email addresses',
        ],
    ],
    [
        'name'        => 'Talent Buyer Pro',
        'schema_name' => 'Talent Buyer Pro',
        'slug'        => 'buyer-pro-monthly',
        'price'       => '$18',
        'price_desc'  => '/month',
        'description' => 'Everything in Free Tier plus unlimited usage and multi user.',
        'features'    => [
            'Everything in Free Tier',
            [
                'text'    => 'Unlimited Events',
                'tooltip' => 'Create and manage as many events as you need without any monthly limits.',
            ],
            [
                'text'    => 'Unlimited Musician Applications',
                'tooltip' => 'Musician Applications allow you to collect applicants for your event or venue. Share your application link where musicians can find it and manage your applicants from your dashboard.',
            ],
            [
                'text'    => 'Share Musician Applications with other users',
                'tooltip' => 'Collaborate with your team by sharing applications with view or edit access.',
            ],
        ],
    ],
    [
        'name'        => 'Lifetime Pro Membership',
        'schema_name' => 'Talent Buyer Pro Lifetime Membership',
        'slug'        => 'buyer-pro-lifetime',
        'price'       => '$239',
        'price_desc'  => 'one time',
        'description' => 'Everything in Pro for a one time payment.',
        'features'    => [
            'Everything in Talent Buyer Pro',
            'Lifetime access — no recurring fees',
        ],
    ],
];

get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Talent Buyer Pro Pricing</h1>
        <p class="text-20 mt-4 max-w-2xl">Take talent buying on Hire Musicians to the next level with a Pro membership.</p>
    </div>
</header>

<div class="container py-8 md:py-12">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:gap-12">
        <?php foreach ($offers as $product) { ?>
            <div class="border border-black/20 rounded p-8 flex flex-col">
                <h2 class="font-sun-motter text-25"><?php echo $product['name']; ?></h2>

                <!-- Product details -->
                <p class="mt-6 flex items-baseline gap-x-1">
                    <span class="text-40 font-bold"><?php echo $product['price']; ?></span>
                    <span class="text-14 font-semibold"><?php echo $product['price_desc']; ?></span>
                </p>
                <p class="mt-3 text-14"><?php echo $product['description']; ?></p>

                <!-- CTA -->
                <?php
                $owns_product = false;
                if (is_user_logged_in()) {
                    $user_caps = wp_get_current_user()->allcaps;
                    if ($product['slug'] === 'buyer-pro-monthly') {
                        $owns_product = !empty($user_caps['hm_buyer_pro']) || !empty($user_caps['hm_buyer_pro_lifetime']);
                    } elseif ($product['slug'] === 'buyer-pro-lifetime') {
                        $owns_product = !empty($user_caps['hm_buyer_pro_lifetime']);
                    } elseif ($product['slug'] === 'free-tier') {
                        $owns_product = is_user_logged_in();
                    }
                }
                if ($owns_product) { ?>
                    <a href="<?php echo site_url('/subscriptions/'); ?>"><button class="mt-8 block rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold">Manage My Subscriptions</button></a>
                <?php } elseif ($product['slug'] === 'free-tier') { ?>
                    <button type="button"
                        x-on:click="showSignupModal = true"
                        class="mt-8 block rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold"
                    >Get Started</button>
                <?php } else { ?>

                    <form class="mt-8"
                        hx-post="<?php echo site_url('/wp-html/v1/checkout'); ?>"
                        hx-target="#get-started-results"
                    >
                        <input type="hidden" name="product" value="<?php echo esc_attr($product['slug']); ?>">
                        <button type="submit" class="w-full flex justify-center rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold">
                            <span class="htmx-indicator-component-block-replace">Get Started</span>
                            <span class="htmx-indicator-component-block">
                                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '5', 'color' => 'white']); ?>
                            </span>
                        </button>
                    </form>
                    <div id="get-started-results"></div>

                <?php } ?>

                <!-- Features -->
                <ul class="mt-8 space-y-3 flex-1">
                    <?php foreach ($product['features'] as $feature) {
                        $text    = is_array($feature) ? $feature['text'] : $feature;
                        $tooltip = is_array($feature) ? ($feature['tooltip'] ?? '') : '';
                    ?>
                        <li class="flex items-start gap-x-3 text-14">
                            <img class="h-5 w-5 shrink-0 text-yellow mt-0.5" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/yellow-check.svg" />
                            <span><?php echo $text; ?></span>
                            <?php if ($tooltip) { ?>
                                <?php echo get_template_part('template-parts/global/tooltips/tooltip', '', ['tooltip' => $tooltip]); ?>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>

            </div>
        <?php } ?>
    </div>
</div>

<?php get_template_part('template-parts/global/schema/offer-schema', '', ['offers' => $offers]); ?>

<?php get_footer(); ?>
