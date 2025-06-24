<section class="flex flex-col gap-5">

    <div class="flex items-center gap-2">
        <img class="h-4 sm:h-5 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/media.svg'; ?>" />
        <h2 class="text-20 sm:text-25 font-bold">Media</h2>
    </div>

    <div class="tabs-container relative z-0"
        x-data="{
            showTab1: true,
            showTab2: false,
            showTab3: false,
            showTab4: false,
            hideTabs() {
                this.showTab1 = false;
                this.showTab2 = false;
                this.showTab3 = false;
                this.showTab4 = false;
            },
        }"
    >
        <div class="flex max-sm:flex-wrap items-stretch gap-x-1 z-10 relative">

            <?php
                get_template_part( 'template-parts/components/tab', null, [
                    'title'  => 'Cover Image',
                    'required' => true,
                    'show_exp' => "showTab1",
                    'hide_exp' => "hideTabs()",
                    'count_exp' => "''",
                ]);
                get_template_part( 'template-parts/components/tab', null, [
                    'title'  => 'Listing Images',
                    'required' => false,
                    'show_exp' => "showTab2",
                    'hide_exp' => "hideTabs()",
                    'count_exp' => "youtubeVideoUrls.length > 0 ? youtubeVideoUrls.length : ''",
                ]);
                get_template_part( 'template-parts/components/tab', null, [
                    'title'  => 'Stage Plot Images',
                    'required' => false,
                    'show_exp' => "showTab3",
                    'hide_exp' => "hideTabs()",
                    'count_exp' => "''",
                ]);
                get_template_part( 'template-parts/components/tab', null, [
                    'title'  => 'YouTube Videos',
                    'required' => false,
                    'show_exp' => "showTab4",
                    'hide_exp' => "hideTabs()",
                    'count_exp' => "''",
                ]);
            ?>
        </div>
        <fieldgroup class="has-border block relative h-80 relative z-0 rounded-tl-none overflow-scroll" style="margin-top: -1px">
            <span x-show="showTab1" x-cloak><?php get_template_part('template-parts/listing-form/media-upload-form/cover-image', null,[] ); ?>      </span>
            <span x-show="showTab2" x-cloak><?php get_template_part('template-parts/listing-form/media-upload-form/listing-images', null,[] ); ?>   </span>
            <span x-show="showTab3" x-cloak><?php get_template_part('template-parts/listing-form/media-upload-form/stage-plot-images', null,[] ); ?></span>
            <span x-show="showTab4" x-cloak><?php get_template_part('template-parts/listing-form/media-upload-form/youtube-videos', null,[] ); ?>   </span>
        </fieldgroup>
    </div>
</section>
