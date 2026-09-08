<?php
$listing_id    = $args['post_id'];
$success_toast = $args['success_toast'] ?? null;
$error_toast   = $args['error_toast']   ?? null;
?>

<button type="button" class="bg-yellow hover:bg-yellow-light font-sun-motter w-full shadow-black-offset border-2 border-black px-2 py-2" x-show="!loggedIn" x-cloak
    x-on:click="showLoginModal = false; showSignupModal = true; signupModalMessage = 'Sign up to claim this listing';"
>Claim Listing</button>

<button type="button" class="bg-yellow hover:bg-yellow-light font-sun-motter w-full shadow-black-offset border-2 border-black px-2 py-2" x-show="loggedIn" x-cloak
    hx-post="<?php echo esc_url(site_url('/wp-html/v1/listings/' . $listing_id . '/claim')); ?>"
    hx-target="this"
    hx-swap="outerHTML"
    hx-trigger="click"

    <?php if ($success_toast) { ?>
        x-init="$dispatch('success-toast', { 'message': '<?php echo $success_toast; ?>' })"
    <?php } else if ($error_toast) { ?>
        x-init="$dispatch('error-toast',   { 'message': '<?php echo $error_toast; ?>' })"
    <?php } ?>
>
    <span class="flex justify-center">
        <span class="htmx-indicator-component-block-replace">Claim Listing</span>
        <span class="htmx-indicator-component-block">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
        </span>
    </span>
</button>
