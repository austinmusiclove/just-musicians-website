<?php

    $heading = 'Failed to Complete Application Submission!';
    $message = 'Something went wrong with your submission. Try resubmitting the application.';
    if ($args['error']->get_error_code() == 'no_listings') {
        $heading = 'Your application has been submitted! ';
        $message = 'No further action is required';
    }
?>

<div class="flex flex-col items-center gap-4 my-16 text-center">
    <h2 class="font-bold text-25"><?php echo $heading; ?></h2>
    <p class="text-16 text-black/80"><?php echo $message; ?></p>
    <div class="flex gap-4 mt-2">
        <a href="<?php echo site_url('/musician-application/' . $args['application_id'] . '/'); ?>" class="bg-yellow hover:bg-navy text-black hover:text-white px-4 py-2 rounded-sm font-sun-motter text-14">Go to Application</a>
    </div>
</div>
