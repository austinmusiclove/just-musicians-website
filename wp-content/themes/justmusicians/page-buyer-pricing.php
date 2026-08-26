<?php
/**
 * The Buyer Pricing template file
 *
 * @package JustMusicians
 */

$tiers = [
    [
        'name'        => 'Free Tier',
        'price'       => '$0',
        'price_desc'  => '/month',
        'description' => 'Everything you need to hire musicians.',
        'features'    => [
            'Unlimited inquiries to musicians',
            'Up to 4 events/month',
            'Up to 1 Musician Application',
            'Create Collections of musicians',
        ],
    ],
    [
        'name'        => 'Pro Talent Buyer',
        'price'       => '$39',
        'price_desc'  => '/month',
        'description' => 'Everything in Free Tier plus unlimited usage and multi user.',
        'features'    => [
            'Everything in Free Tier',
            'Unlimited Events',
            'Unlimited Musician Applications',
            'Share Musician Applications with other users',
        ],
    ],
    [
        'name'        => 'Lifetime Pro Membership',
        'price'       => '$349',
        'price_desc'  => 'one time',
        'description' => 'Everything in Pro for a one time payment.',
        'features'    => [
            'Everything in Pro Talent Buyer',
        ],
    ],
];

get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Pro Talent Buyer Pricing</h1>
        <p class="text-20 text-brown-dark-1 mt-4 max-w-2xl">Take talent buying on Hire Musicians to the next level with a Pro membership.</p>
    </div>
</header>

<div class="container py-8 md:py-12">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:gap-12">
        <?php foreach ($tiers as $tier) : ?>
            <div class="border border-black/20 rounded p-8 flex flex-col">
                <h2 class="font-sun-motter text-25 text-brown-dark-1"><?php echo $tier['name']; ?></h2>

                <p class="mt-6 flex items-baseline gap-x-1">
                    <span class="text-40 font-bold text-brown-dark-1"><?php echo $tier['price']; ?></span>
                    <span class="text-14 font-semibold text-brown-dark-2"><?php echo $tier['price_desc']; ?></span>
                </p>

                <p class="mt-3 text-14 text-brown-dark-3"><?php echo $tier['description']; ?></p>

                <a href="#" class="mt-8 block rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold text-brown-dark-1 transition-colors">
                    Get Started
                </a>

                <ul class="mt-8 space-y-3 flex-1">
                    <?php foreach ($tier['features'] as $feature) : ?>
                        <li class="flex items-start gap-x-3 text-14 text-brown-dark-1">
                            <svg class="h-5 w-5 shrink-0 text-yellow mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                            </svg>
                            <?php echo $feature; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php get_footer(); ?>
