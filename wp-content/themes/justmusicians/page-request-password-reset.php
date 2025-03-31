<?php
/**
 * The template for the forgot password page
 *
 * @package JustMusicians
 */
$email = '';
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = sanitize_email($_POST['email']); // Sanitize the email input

    // Check if the email is valid
    if (is_email($email)) {
        // Attempt to get the user by email
        $user = get_user_by('email', $email);

        if ($user) {
            // User found, send the password reset email
            $reset_link_sent = retrieve_password($user->user_login);

            if ($reset_link_sent) {
                $message = 'Password reset email has been sent successfully. Please check your inbox.';
                $message_type = 'success';
            } else {
                $message = 'There was an error sending the reset email. Please try again.';
                $message_type = 'error';
            }
        } else {
            // If no user is found with the given email
            $message = 'No user found with that email address.';
            $message_type = 'error';
        }
    } else {
        // If the email is invalid
        $message = 'Please enter a valid email address.';
        $message_type = 'error';
    }
}

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
            <div>
                <label for="log" class="block text-sm font-medium leading-6 mt-4">Email Address</label>
                <div class="mt-2">
                    <input name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 bg-brown-light-3">
                </div>
            </div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-yellow px-3 py-1.5 text-sm font-semibold leading-6 shadow-sm hover:bg-navy hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow mt-4">Request Password Reset</button>
        </form>

        <?php if ($message): ?>
            <div class="mt-20 message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>


    </div>
</div>


<?php
get_footer();


