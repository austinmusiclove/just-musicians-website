<?php
/**
 * The template for the forgot password page
 *
 * @package JustMusicians
 */

// Ensure the user is logged out before showing the password reset form.
if ( is_user_logged_in() ) {
    wp_redirect( home_url() ); // TODO change this to password change page once it is created
    exit;
}


// Check if the password reset form is submitted
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    // Get the user data from the query parameters
    $key = isset($_POST['key']) ? $_POST['key'] : '';
    $login = isset($_POST['login']) ? $_POST['login'] : '';

    // Ensure both key and login are present
    if (empty($key) || empty($login)) {
        $error_message = 'Invalid password reset link.';
    } else {

        // Check the key and login against the database
        $user = check_password_reset_key($key, $login);

        if (is_wp_error($user)) {
            $error_message = 'Invalid password reset link or expired key. Try requesting <a class="text-yellow hover:underline" href="' . site_url('request-password-reset') . '">another password reset.</a>';
        } else {
            $new_password = sanitize_text_field($_POST['new_password']);

            // Reset the password
            wp_set_password($new_password, $user->ID);

            // Password reset success, log the user in automatically
            wp_set_auth_cookie($user->ID);
            wp_redirect( home_url() ); // TODO change this to password change page once it is created
            exit;
        }
    }
}

$key = isset($_GET['key']) ? $_GET['key'] : '';
$login = isset($_GET['login']) ? $_GET['login'] : '';


get_header();
?>


<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
    </div>
</header>
<div class="container lg:grid lg:grid-cols-10 gap-24 py-8 min-h-[500px]">
    <div class="col lg:col-span-7 article-body mb-8 lg:mb-0">


        <form class="space-y-6" action="" method="POST">
            <input type="hidden" name="key" value="<?php echo esc_attr( $key ); ?>" />
            <input type="hidden" name="login" value="<?php echo esc_attr( $login ); ?>" />
            <div>
                <label for="new_password" class="block text-sm font-medium leading-6 mt-4">New Password</label>
                <div class="mt-2">
                    <input id="user_pass" name="new_password" x-bind:type="showPassword ? 'text' : 'password'" autocomplete="current-password" required class="block w-full rounded-md border border-yellow px-3 py-2 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6 bg-brown-light-3">
                    <span class="float-right right-[10px] mt-[-30px] relative">
                        <img class="h-6 w-6 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/eye-password-show.svg'; ?>" x-cloak x-show="showPassword" x-on:click="showPassword = false;"/>
                        <img class="h-6 w-6 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/eye-password-hide.svg'; ?>" x-cloak x-show="!showPassword" x-on:click="showPassword = true;"/>
                    </span>
                </div>
            </div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-yellow px-3 py-1.5 text-sm font-semibold leading-6 shadow-sm hover:bg-navy hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow mt-4">Reset Password</button>
        </form>
        <div class="mt-4"><?php echo $error_message; ?></div>


    </div>
</div>


<?php
get_footer();
