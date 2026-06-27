<?php if (!empty($args['disabled']) and $args['disabled']) { ?>

    <!-- For previews and any time the button should do nothing -->
    <button type="button" class="<?php echo $args['btn_classes']; ?>">Send Inquiry</button>

<?php } else { ?>

    <!-- For not logged in users; button directs users to create an account -->
    <button type="button" class="<?php echo $args['btn_classes']; ?>"
        x-show="!loggedIn" x-cloak
        x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to send inquiries to musicians'"
    >Send Inquiry</button>

    <!-- For logged in users; opens inquiry modal -->
    <button type="button" class="<?php echo $args['btn_classes']; ?>"
        x-show="loggedIn" x-cloak
        x-on:click="_clearInquiryForm(); _openInquiryModal('<?php echo $args['post_id']; ?>', '<?php echo clean_str_for_doublequotes($args['name']); ?>');"
    >Send Inquiry</button>

<?php } ?>
