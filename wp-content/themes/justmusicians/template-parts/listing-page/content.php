<?php
    $is_preview        = $args['instance'] == 'listing-form';
    $ph_thumbnail      = get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp';
    $listing_image_ids = $is_preview ? [] : (is_array(get_field('listing_images')) ? get_field('listing_images') : []);
    $stage_plot_ids    = $is_preview ? [] : (is_array(get_field('stage_plots'))    ? get_field('stage_plots')    : []);

    $theme = $is_preview ?
        [ 'container_class' => 'flex flex-col-reverse gap-8 pb-8', ] :
        [ 'container_class' => 'container flex flex-col-reverse lg:grid grid-cols-8 gap-8 xl:gap-24 mb-20', ];
?>

<section class="<?php echo $theme['container_class']; ?>"
    x-data="{
        inquiriesMap: <?php echo clean_arr_for_doublequotes($args['inquiries_map']); ?>,
        get sortedInquiries()                                { return getSortedInquiries(this); },
        _addInquiry(postId, subject, listings, permalink)    { return addInquiry(this, postId, subject, listings, permalink); },
        _showAddListingToInquiryButton(inquiryId, listingId) { return showAddListingToInquiryButton(this, inquiryId, listingId); },
        _showListingInInquiry(inquiryId, listingId)          { return showListingInInquiry(this, inquiryId, listingId); },
    }"
>
    <div class="col-span-5 flex flex-col gap-8 items-start">


        <!-- Bio -->
        <?php if (!empty(get_field('bio')) or $is_preview) { ?>
        <div id="biography"
            <?php if ($is_preview) { ?> x-show="pBio.length > 0" x-cloak <?php } ?>
            <?php if ($is_preview) { ?> x-on:click="focusElm('bio')" <?php } ?>
        >
            <h2 class="text-25 font-bold mb-5">Biography</h2>
            <p class="mb-4"
                <?php if ($is_preview) { ?> x-html="pBio.replace(/\n/g, '<br>')" <?php } ?>>
                <?php if (!$is_preview) { echo nl2br(get_field('bio')); } ?>
            </p>
        </div>
        <?php } ?>


        <!-- Venues -->
        <?php echo get_template_part('template-parts/listing-page/parts/venues', '', [
            'is_preview'      => $is_preview,
            'venues_combined' => $args['venues_combined'],
        ]); ?>


        <!-- Media -->
        <div class="w-full" x-data="{
                showImageTab: true,
                showVideoTab: false,
                showStagePlotTab: false,
                hasStagePlot: <?php if ($is_preview) { echo "orderedImageData['stage_plots'].length > 0"; } else { echo (count($stage_plot_ids) > 0) ? 'true' : 'false'; } ?>,
                hasVideo: <?php if ($is_preview) { echo 'youtubeVideoData.length > 0'; } else { echo (count($args['youtube_video_data']) > 0) ? 'true' : 'false'; } ?>,
                hideTabs() {
                    this.showImageTab = false;
                    this.showVideoTab = false;
                    this.showStagePlotTab = false;
                },
            }"
            <?php if ($is_preview) { ?>
            x-init="$watch('orderedImageData', value => { hasStagePlot = orderedImageData['stage_plots'].length > 0; hideTabs(); showImageTab = true;});
                    $watch('youtubeVideoData', value => { hasVideo = youtubeVideoData.length > 0; hideTabs(); showImageTab = true;});"
            <?php } ?>
        >
            <h2 class="text-25 font-bold mb-5">Media</h2>
            <div class="flex items-start gap-4 media-tabs mb-2.5">
                <div class="text-14 sm:text-16 tab-heading pb-1 cursor-pointer" :class="{'active': showImageTab}" x-on:click="hideTabs(); showImageTab = true;">Images</div>
                <div class="text-14 sm:text-16 tab-heading pb-1 cursor-pointer" :class="{'active': showVideoTab}" x-show="hasVideo" x-cloak x-on:click="hideTabs(); showVideoTab = true;">Videos</div>
                <div class="text-14 sm:text-16 tab-heading pb-1 cursor-pointer" :class="{'active': showStagePlotTab}" x-show="hasStagePlot" x-cloak x-on:click="hideTabs(); showStagePlotTab = true;">Stage Plots</div>
            </div>


            <!-- Sliders -->
            <?php echo get_template_part('template-parts/listing-page/parts/image-slider', '', [
                'is_preview'        => $is_preview,
                'listing_image_ids' => $listing_image_ids,
                'ph_thumbnail'      => $ph_thumbnail,
            ]); ?>
            <?php echo get_template_part('template-parts/listing-page/parts/video-slider', '', [
                'is_preview'         => $is_preview,
                'youtube_video_data' => $args['youtube_video_data'],
            ]); ?>
            <?php echo get_template_part('template-parts/listing-page/parts/stage-plot-slider', '', [
                'is_preview'     => $is_preview,
                'stage_plot_ids' => $stage_plot_ids,
            ]); ?>


        </div>

        <!-- Calendar -->
        <?php //echo get_template_part('template-parts/listing-page/parts/calendar', '', []); ?>

        <!-- Reviews -->
        <?php if (!$is_preview) {
            echo get_template_part('template-parts/listing-page/parts/reviews', '', [ 'post_id' => get_the_ID() ]);
        } ?>

    </div>

    <div class="col-span-3">
        <div class="sticky top-20 flex flex-col gap-8">

            <!-- Inquire button -->
            <?php get_template_part('template-parts/listing-page/parts/inquire-button', '', [
                'post_id'  => !$is_preview ? get_the_ID() : '',
                'name'     => !$is_preview ? get_field('name') : '',
                'disabled' => $is_preview,
            ]); ?>

            <div class="flex flex-col gap-4">

                <div class="sidebar-module border border-black/40 rounded overflow-hidden bg-white">
                    <h3 class="bg-yellow-50 font-bold py-2 px-3">Contact Information</h3>
                    <div class="p-4 flex flex-col gap-4">


                        <!-- Contact info -->
                        <?php echo get_template_part('template-parts/listing-page/parts/contact-info', '', [
                            'is_preview'     => $is_preview,
                        ]); ?>


                        <!-- Socials and links -->
                        <?php echo get_template_part('template-parts/listing-page/parts/social-links', '', [
                            'is_preview'     => $is_preview,
                        ]); ?>


                    </div>
                </div>


                <!-- Classifications/Taxonomies -->
                <?php echo get_template_part('template-parts/listing-page/parts/classifications', '', [
                    'is_preview'       => $is_preview,
                    'categories'       => $args['categories'],
                    'genres'           => $args['genres'],
                    'subgenres'        => $args['subgenres'],
                    'instrumentations' => $args['instrumentations'],
                    'settings'         => $args['settings'],
                ]); ?>

                <!-- Spotify artist playlist -->
                <?php echo get_template_part('template-parts/listing-page/parts/spotify-playlist', '', [
                    'is_preview'       => $is_preview,
                ]); ?>


            </div>
        </div>
    </div>
</section>
