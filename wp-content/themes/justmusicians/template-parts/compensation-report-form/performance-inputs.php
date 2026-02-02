<section class="flex flex-col gap-5">

    <!-- Heading -->
    <div class="flex items-center gap-2">
        <img class="h-6 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/music.svg'; ?>" />
        <h2 class="text-25 font-bold">Performance</h2>
    </div>
    <p class="text-18">
        Performance details will never be shared.
        This information is used to verify the authenticity of your submission.
    </p>


    <fieldgroup class="block pb-6">
        <div class="grid sm:grid-cols-3 gap-2">

            <!-- Performing Act Name -->
            <?php echo get_template_part('template-parts/global/form/input-icon', '', [
                'title'         => 'Performing Act Name',
                'input_name'    => 'performing_act_name',
                'input_type'    => 'text',
                'icon_filename' => 'icon-bands.svg',
                'tooltip'       => 'Your band name or artist name as it appears on the show bill.',
                'placeholder'   => 'Band Name',
                'required'      => true,
            ]); ?>

            <!-- Performance Date -->
            <?php echo get_template_part('template-parts/global/form/input-icon', '', [
                'title'         => 'Performance Date',
                'input_name'    => 'performance_date',
                'input_type'    => 'date',
                'icon_filename' => 'calendar.svg',
                'placeholder'   => '',
                'required'      => true,
            ]); ?>

            <!-- Documentation (link to show flyer on IG or venue website) -->
            <?php echo get_template_part('template-parts/global/form/input-icon', '', [
                'title'         => 'Show Flier Url',
                'input_name'    => 'show_flier_url',
                'input_type'    => 'url',
                'icon_filename' => 'link.svg',
                'tooltip'       => 'This can be a link to the venue’s website or a Facebook or Instagram post that shows the event. We use this link only to verify your entry.',
                'placeholder'   => 'https://',
                'required'      => true,
            ]); ?>

        </div>
    </fieldgroup>

</section>
