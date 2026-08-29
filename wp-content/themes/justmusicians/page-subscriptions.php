<?php

if (!is_user_logged_in()) { wp_redirect(site_url()); } // Don't allow non logged in users to see this page

$user = wp_get_current_user();
$is_pro_buyer = $user->has_cap('hm_buyer_pro');
$tier = get_user_meta($user->ID, 'membership_tier', true);

$tier_names = [
    'buyer-pro-monthly'  => 'Pro Talent Buyer (Monthly)',
    'buyer-pro-lifetime' => 'Pro Talent Buyer (Lifetime)',
];
$tier_name = $tier_names[$tier] ?? ($is_pro_buyer ? 'Pro Talent Buyer' : 'Free Tier');

get_header();

?>

<div class="flex flex-col grow">
    <div id="content" class="grow flex flex-col relative">
        <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
            <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                  <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                </div>
            </div>
            <div class="col sm:col-span-4 md:col-span-3 py-6 md:py-12">

                <!-- Heading -->
                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <a href="/subscriptions"><h1 class="font-bold text-22 sm:text-25">My Subscription</h1></a>
                </div>

                <div class="flex flex-col gap-6 md:col-span-6 pb-4">

                    <?php if ($is_pro_buyer) { ?>

                        <div class="border border-black/20 rounded p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="font-bold text-18"><?php echo esc_html($tier_name); ?></h2>
                                <span class="text-14 font-semibold text-green-600">Active</span>
                            </div>
                            <p class="mt-3 text-14 text-black/70">
                                Thank you for being a Pro Talent Buyer. You have access to unlimited applications and events.
                            </p>
                            <a class="mt-6 inline-block rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold"
                               href="<?php echo site_url('/buyer-pricing/'); ?>"
                            >Manage Subscription</a>
                        </div>

                    <?php } else { ?>

                        <div class="border border-black/20 rounded p-6">
                            <h2 class="font-bold text-18">Free Tier</h2>
                            <p class="mt-3 text-14 text-black/70">
                                You're currently on the free tier. Upgrade to Pro Talent Buyer for unlimited applications, events, and more.
                            </p>
                            <a class="mt-6 inline-block rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold"
                               href="<?php echo site_url('/buyer-pricing/'); ?>"
                            >Upgrade to Pro</a>
                        </div>

                    <?php } ?>

                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
