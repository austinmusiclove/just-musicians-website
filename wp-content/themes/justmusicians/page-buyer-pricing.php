<?php
/**
 * The Buyer Pricing template file
 *
 * @package JustMusicians
 */

$tiers = [
    [
        'name'        => 'Free Tier',
        'slug'        => 'free',
        'price'       => '$0',
        'price_desc'  => '/month',
        'description' => 'Everything you need to hire musicians.',
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
        'name'        => 'Pro Talent Buyer',
        'slug'        => 'buyer-pro-monthly',
        'price'       => '$39',
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
        'slug'        => 'buyer-pro-lifetime',
        'price'       => '$349',
        'price_desc'  => 'one time',
        'description' => 'Everything in Pro for a one time payment.',
        'features'    => [
            'Everything in Pro Talent Buyer',
            'Lifetime access — no recurring fees',
        ],
    ],
];

get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Pro Talent Buyer Pricing</h1>
        <p class="text-20 mt-4 max-w-2xl">Take talent buying on Hire Musicians to the next level with a Pro membership.</p>
    </div>
</header>

<div class="container py-8 md:py-12">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:gap-12">
        <?php foreach ($tiers as $tier) { ?>
            <div class="border border-black/20 rounded p-8 flex flex-col">
                <h2 class="font-sun-motter text-25"><?php echo $tier['name']; ?></h2>

                <p class="mt-6 flex items-baseline gap-x-1">
                    <span class="text-40 font-bold"><?php echo $tier['price']; ?></span>
                    <span class="text-14 font-semibold"><?php echo $tier['price_desc']; ?></span>
                </p>

                <p class="mt-3 text-14"><?php echo $tier['description']; ?></p>

                <?php if ($tier['slug'] === 'free') { ?>
                    <button type="button"
                        x-on:click="showSignupModal = true"
                        class="mt-8 block rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold"
                    >Get Started</button>
                <?php } else { ?>

                    <form class="mt-8"
                        hx-post="<?php echo site_url('/wp-html/v1/checkout'); ?>"
                        hx-target="#get-started-results"
                    >
                        <input type="hidden" name="tier" value="<?php echo esc_attr($tier['slug']); ?>">
                        <button type="submit" class="w-full flex justify-center rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold">
                            <span class="htmx-indicator-component-block-replace">Get Started</span>
                            <span class="htmx-indicator-component-block">
                                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '5', 'color' => 'white']); ?>
                            </span>
                        </button>
                    </form>
                    <div id="get-started-results"></div>

                <?php } ?>

                <ul class="mt-8 space-y-3 flex-1">
                    <?php foreach ($tier['features'] as $feature) {
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

<?php get_footer(); ?>
