<div class="flex flex-col items-center gap-4 my-16 text-center">
    <h2 class="font-bold text-25">Invalid Link!</h2>
    <p class="text-16 text-black/80">This link has expired or is invalid. Try resubmitting the application.</p>
    <div class="flex gap-4 mt-2">
        <a href="<?php echo site_url('/musician-application/' . $args['application_id'] . '/'); ?>" class="bg-yellow hover:bg-navy text-black hover:text-white px-4 py-2 rounded-sm font-sun-motter text-14">Go to Application</a>
    </div>
</div>
