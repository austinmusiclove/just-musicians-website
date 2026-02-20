<?php
/**
 * The template for displaying buyers
 *
 * @package JustMusicians
 */

$buyer_id = get_query_var('buyer-id');
$account_settings = get_user_account_settings($buyer_id);


get_header();

echo get_template_part('template-parts/buyers/hero', '', [
    'buyer_id'       => $buyer_id,
    'display_name'  => $account_settings['display_name'],
    'profile_image' => $account_settings['profile_image'],
    'organization'  => $account_settings['organization'],
    'position'      => $account_settings['position'],
]);
echo get_template_part('template-parts/buyers/content', '', [
    'buyer_id'       => $buyer_id,
    'display_name'  => $account_settings['display_name'],
]);

// Show review modal popup on page load when mdl=review in url
if (!empty($_GET['mdl']) and $_GET['mdl'] == 'review') {
    if (is_user_logged_in()) { ?>
        <span x-init="_openReviewModal('', '')"></span>
    <?php } else { ?>
        <span x-init="showSignupModal = true; signupModalMessage = 'Sign up to write a review'; loginModalMessage = 'Sign in to write a review';"></span>
    <?php }
}

get_footer();
