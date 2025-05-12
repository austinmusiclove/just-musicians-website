<?php
$email = '';
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['is_pw_reset'])) {
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

?>

<div data-popup="quote" class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center p-4" x-show="showPasswordResetModal" x-cloak>
    <div data-trigger="quote" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer" x-on:click="showPasswordResetModal = false"></div>

    <div class="bg-white relative w-full md:w-auto flex items-center justify-center border-2 shadow-black-offset border-black" style="max-width: 780px;">

        <img data-trigger="quote" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
            x-on:click="showPasswordResetModal = false;" />

        <button data-slide="request-password-reset" class="p-2 absolute top-2 left-2" x-on:click="showLoginModal = true; showPasswordResetModal = false;">
            <img class="opacity-60 hover:opacity-100 w-3 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-left-sharp-solid.svg';?>"/>
        </button>

        <div class="slide w-[32rem] grow">

            <div  class="flex flex-col justify-center lg:px-8 min-h-full py-12 sm:px-6">


                <div data-slide="request-password-reset" class="relative mx-6 sm:mx-12">
                    <h2 class="mt-6 text-25 font-bold leading-9 tracking-tight mb-12 text-center leading-tight">Request password reset email</h2>
                    <form class="space-y-6" action="" method="POST">
                        <div>
                            <label for="log" class="block text-sm font-medium leading-6 mt-4">Email Address</label>
                            <div class="mt-2">
                                <input name="email" type="email" autocomplete="email" required class="block w-full rounded-md border border-yellow px-3 py-2 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6">
                                <input name="is_pw_reset" type="hidden">
                            </div>
                        </div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-yellow px-3 py-1.5 text-sm font-semibold leading-6 shadow-sm hover:bg-navy hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow mt-4">Request Password Reset</button>
                    </form>

                    <?php if ($message): ?>
                        <div class="mt-6 message text-center text-14 request-password-reset-message <?php echo $message_type; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>


                </div>
            </div>

        </div>


    </div>
</div>
