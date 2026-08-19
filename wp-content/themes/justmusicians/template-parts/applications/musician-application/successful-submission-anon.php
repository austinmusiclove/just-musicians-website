<div class="flex flex-col items-center gap-4 mt-16 text-center" data-testid="successful-submission-anon">
    <h2 class="font-bold text-25">Your application submission has been received!</h2>
    <p class="text-16 text-black/80">We have received your application submission for "<?php echo $args['title']; ?>".</p>
    <p class="text-16 text-black/80">We need to verify you are a real person for the reviewer. Please create an account to complete your submission.</p>
</div>
<div class="text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

    <div class="pb-32 relative z-10">
        <button type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2" data-testid="successful-submission-anon-sign-up-btn"
            x-on:click="showLoginModal = false; showSignupModal = true; signupModalMessage = 'Sign up to complete your submission';"
        >Sign Up</button>
    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>
<span x-init="showLoginModal = false; showSignupModal = false; signupModalMessage = 'Sign up to complete your submission';"></span>
