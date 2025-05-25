
<section class="flex flex-col gap-5">

<script>
document.addEventListener('click', function (e) {
    const tab = e.target.closest('[data-tab-heading]');
    if (!tab) return;

    const tabKey = tab.getAttribute('data-tab-heading');

    const container = tab.closest('.tabs-container');
    if (!container) return;

    container.querySelectorAll('[data-tab-heading]').forEach(el => {
        el.classList.remove('active');
    });

    tab.classList.add('active');

    container.querySelectorAll('[data-media-tab]').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('active');
    });

    const preview = container.querySelector(`[data-media-tab="${tabKey}"]`);
    if (preview) {
        preview.classList.remove('hidden');
        preview.classList.add('active');
    }
});


document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-show]');
    if (!trigger) return;

    e.preventDefault();

    const dataShow = trigger.getAttribute('data-show');
    const lastHyphenIndex = dataShow.lastIndexOf('-');
    const name = dataShow.substring(0, lastHyphenIndex);
    const number = dataShow.substring(lastHyphenIndex + 1);

    const target = document.querySelector(`[data-parent-tab="${name}"][data-screen="${number}"]`);
    if (target && target.getAttribute('data-type') !== 'popup') {
        document.querySelectorAll(`[data-parent-tab="${name}"]`).forEach(el => {
            el.classList.add('hidden');
        });
        target.classList.remove('hidden');
    } else {
        target.classList.remove('hidden');
    }

});
</script>



<div class="flex items-center gap-2">
    <img class="h-4 sm:h-5 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/media.svg'; ?>" />
    <h2 class="text-20 sm:text-25 font-bold">Media</h2>
</div>




<div class="tabs-container z-0 relative">
    <div class="flex max-sm:flex-wrap items-stretch gap-x-1 z-10 relative" style="row-gap: -2px">
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
    <fieldgroup class="has-border block relative h-80 relative z-0 rounded-tl-none overflow-scroll" style="margin-top: -1px">
        <?php
        get_template_part('template-parts/listing-form/media-upload-form/thumbnails', null,[] );
        ?>
        <?php
        get_template_part('template-parts/listing-form/media-upload-form/youtube-links', null,[] );
        ?>
        <?php
        get_template_part('template-parts/listing-form/media-upload-form/cover-image', null,[] );
        ?>
        <?php
        get_template_part('template-parts/listing-form/media-upload-form/profile-images', null,[] );
        ?>
        <?php
        get_template_part('template-parts/listing-form/media-upload-form/stage-plot-images', null,[] );
        ?>
    </fieldgroup>
</div>


</section>