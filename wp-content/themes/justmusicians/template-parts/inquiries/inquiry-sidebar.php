<div class="border border-black/20 p-8 <?php echo $args['responsive']; ?>">

    <h3 class="font-sun-motter text-20 mb-3">Find Musicians Fast</h3>
    <p class="text-16 text-brown-dark-1 leading-tight mb-6">Need to fill a spot in your line up?</p>
    <p class="text-16 text-brown-dark-1 leading-tight mb-6">Tell us about your event and get responses back from multiple musicians near you.</p>

    <button type="button" class="<?php echo $args['button_color']; ?> shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
        x-show="!loggedIn" x-cloak
        x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to request quotes from musicians'"
    >Get Started</button>
    <button type="button" class="<?php echo $args['button_color']; ?> shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
        x-show="loggedIn" x-cloak
        x-on:click="_openInquiryModal('', '')"
    >Get Started</button>

</div>
