
<section class="flex flex-col gap-4">

<div class="flex items-center gap-2 mb-5">
    <img class="h-5" src="<?php echo get_template_directory_uri() . '/lib/images/icons/media.svg'; ?>" />
    <h2 class="text-25 font-bold">Media</h2>
</div>

<div>
    <div class="flex items-stretch gap-1 z-10 relative">
        <?php
            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Thumbnails',
                    'number' => 5,
                    'active' => true,
                    'required' => true
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'YouTube Links',
                    'number' => null,
                    'active' => false,
                    'required' => false
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Cover Image',
                    'number' => null,
                    'active' => false,
                    'required' => false
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Profile Images',
                    'number' => 3,
                    'active' => false,
                    'required' => false
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Stage Plot Images',
                    'number' => 1,
                    'active' => false,
                    'required' => false
                ]
            );

        ?>
    </div>
    <fieldgroup class="has-border px-4 py-8 relative h-64 relative z-0 rounded-tl-none overflow-scroll" style="margin-top: -1px">
    </fieldgroup>
</div>


</section>