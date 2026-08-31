<?php

$user = wp_get_current_user();
$is_logged_in = is_user_logged_in();
$has_pro = $is_logged_in && $user->has_cap('hm_buyer_pro');
$has_lifetime = $is_logged_in && $user->has_cap('hm_buyer_pro_lifetime');

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
            <div class="col md:col-span-6 py-6 md:py-12">

                <!-- Heading -->
                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <h1 class="font-bold text-22 sm:text-25">My Subscriptions</h1>
                </div>

                <!--Page Load Toasts -->
                <div>
                    <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'cancelled') { ?><span x-init="$dispatch('success-toast', {'message': 'Subscription Cancelled Successfully'});"></span><?php } ?>
                </div>

                <?php if (!$is_logged_in) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'see your subscriptions' ]); ?>

                <?php } else { ?>

                <div class="flex flex-col gap-6 md:col-span-6 pb-4">

                    <?php if ($has_lifetime) { ?>

                        <div class="border border-black/20 rounded p-6" data-testid="subscription-card-buyer-pro-lifetime">
                            <div class="flex items-center justify-between">
                                <h2 class="font-bold text-18">Talent Buyer Pro Lifetime Membership</h2>
                                <span class="text-14 font-semibold bg-navy text-white px-2 py-0.5 rounded-full capitalize">Active</span>
                            </div>
                            <p class="mt-3 text-14 text-black/70">
                                Thank you for being a Talent Buyer Pro Lifetime member. You have access to unlimited applications, events, multi-user and more, with no recurring fees.
                            </p>
                        </div>

                    <?php } elseif ($has_pro) { ?>

                        <div class="border border-black/20 rounded p-6" data-testid="subscription-card-buyer-pro">
                            <div class="flex items-center justify-between">
                                <h2 class="font-bold text-18">Talent Buyer Pro</h2>
                                <span class="text-14 font-semibold bg-navy text-white px-2 py-0.5 rounded-full capitalize">Active</span>
                            </div>
                            <p class="mt-3 text-14 text-black/70">
                                Thank you for being a Talent Buyer Pro. You have access to unlimited applications, events, multi-user and more.
                            </p>
                            <form class="mt-6"
                                hx-post="<?php echo site_url('/wp-html/v1/cancel-subscription'); ?>"
                                hx-target="#cancel-subscription-results"
                            >
                                <button type="submit" class="inline-flex justify-center gap-2 rounded bg-white border border-black/20 hover:bg-red hover:text-white px-3 py-2 text-center text-14 font-bold">
                                    <span class="htmx-indicator-component-block-replace">Cancel Subscription</span>
                                    <span class="htmx-indicator-component-block">
                                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '5', 'color' => 'yellow']); ?>
                                    </span>
                                </button>
                            </form>
                            <div id="cancel-subscription-results"></div>
                        </div>

                    <?php } else { ?>

                        <div class="border border-black/20 rounded p-6">
                            <h2 class="font-bold text-18">Talent Buyer Free Tier</h2>
                            <p class="mt-3 text-14 text-black/70">
                                You're currently on the free tier. Upgrade to Talent Buyer Pro for advanced features for talent buyers including unlimited applications, events, and multi-user.
                            </p>
                            <a class="mt-6 inline-block rounded bg-yellow hover:bg-navy hover:text-white px-3 py-2 text-center text-14 font-bold"
                               href="<?php echo site_url('/pricing/'); ?>"
                            >Pricing</a>
                        </div>

                    <?php } ?>

                </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
