<?php
/**
 * The template for the listings landing page
 *
 * @package JustMusicians
 */

get_header();

?>
<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8 min-h-[500px]">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">

    <?php if (is_user_logged_in()) {
        if (isset($_GET['lic'])) {
            $success = add_listing_by_invitation_code($_GET['lic']);
            // if success, redirect back to the same page but remove query params to avoid an infinite loop
            if ($success and !is_wp_error($success)) { ?>
                <div x-init="redirect('<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>')"></div>
            <?php } else { ?>
                <p>Failed to add listing from invitation link with error: <span class="text-yellow"><?php echo $success->get_error_message(); ?></span></p>
            <?php }
        } ?>

        <button type="button" href="" class="mb-8 border bg-yellow hover:bg-navy hover:text-white">Create Listing</button>


        <?php
        // Get listings
        $current_user = wp_get_current_user();
        $listing_ids = get_user_meta($current_user->ID, 'listings', true);


        if ($listing_ids && is_array($listing_ids)) {
            $args = array(
                'post_type' => 'listing',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'post__in' => $listing_ids,
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) { ?>
                <?php while ($query->have_posts()) {


                // Display listings
                $query->the_post(); ?>
                <br><a href="listing-form/?lid=<?php the_ID(); ?>"><?php the_title(); ?></a>


                <?php }
            } else {
                echo '<p>You don\'t have any listings associated with your account.</p>';
            }
            wp_reset_postdata(); // Reset the query
        } else {
            echo '<p>You don\'t have any listings associated with your account.</p>';
        }
    } else {
        if (!empty($_GET['lic']) and !empty($_GET['mdl']) and $_GET['mdl'] == 'signup') { ?>
            <span x-init="showLoginModal = false; showSignupModal = true; signupModalMessage = 'Sign up to activate this listing invitation link';"></span>
        <?php } else if (isset($_GET['lic'])) { ?>
            <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to activate this listing invitation link';"></span>
        <?php } else { ?>
            <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your listings';"></span>
        <?php }
    } ?>
    </div>

</div>


<?php
get_footer();

