<div class="font-sun-motter text-center px-4 pb-28 pt-12 sm:py-20 relative mb-4 xl:mb-0 h-[70vh] flex items-center justify-center flex-col">

    <div class="pb-16 relative z-10">
        <span class="text-22 block text-center mb-2">
            Only <span class="text-yellow">contributors</span> can access the earnings database.
        </span>
        <p class="text-20 mb-4">
            If you believe you have contributed, please <a href="<?php echo site_url('/contact'); ?>">contact the website admin</a> for assistance in gaining access.
        </p>
        <p class="text-20 mb-4">
            If you have not contributed and you are a live musician who has earned money from performing, consider contributing to gain access to data about how much musicians are earning from live music gigs.
            A contribution is an anonymous report of how much you earned from a live music gig.
        </p>

        <a href="<?php echo site_url('/compensation-report-form'); ?>">
            <button type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3">
                Contribute Earnings Data
            </button>
        </a>
    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>
