<?php
/**
 * The template for the email verification page
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
        <p><?php the_content(); ?></p>
        <p><?php
            $account_identifier = $_GET['aci'];
            if (!empty($account_identifier)) {
                // get user
                $account_user = get_users(array(
                    'meta_key' => 'account_identifier',
                    'meta_value' => $account_identifier
                ));
                // update the user email_verified field
                $update_success = update_user_meta($account_user[0]->ID, "email_verified", true);
                if ($update_success == true) {
                    echo "Your email has been verified. You may now close this browser tab.";
                } else {
                    echo "Error updating account record. Please contact the admin for assistance on your subscription at john@justmusicians.com";
                }
            } else {
                echo "Invalid verification URL";
            }
        ?></p>
    </div>

</div>


<?php
get_footer();

