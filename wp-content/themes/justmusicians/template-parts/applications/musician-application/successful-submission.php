<!-- Logged in -->
<?php if (is_user_logged_in()) { ?>

<div class="flex flex-col items-center gap-4 my-16 text-center" data-testid="successful-submission">
    <h2 class="font-bold text-25">Your application has been submitted!</h2>
    <p class="text-16 text-black/80">Your submission is complete and has been delivered to the reviewer.</p>
    <div class="flex gap-4 mt-2">
        <a href="<?php echo site_url('/listings/'); ?>" class="bg-yellow hover:bg-navy text-black hover:text-white px-4 py-2 rounded-sm font-sun-motter text-14">Edit Your Listing</a>
        <a href="<?php echo site_url('/submitted-applications/'); ?>" class="border border-black/20 hover:border-black px-4 py-2 rounded-sm font-sun-motter text-14">View Submitted Applications</a>
    </div>
</div>

<?php } else { ?>

<!-- Logged out (embed case) -->
<div class="flex flex-col items-center gap-4 my-16 text-center" data-testid="successful-submission-logged-out">
    <h2 class="font-bold text-25">Your application has been submitted!</h2>
    <p class="text-16 text-black/80">Your submission is complete and has been delivered to the reviewer.</p>
</div>

<?php } ?>
