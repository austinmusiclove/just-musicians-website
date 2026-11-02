<?php
$event_id              = $args['event_id'] ?? 0;
$search_lat            = $args['lat'] ?? '';
$search_lng            = $args['lng'] ?? '';
$search_location_label = $args['location_label'] ?? '';
$has_search_location   = $search_lat !== '' && $search_lng !== '' && $search_location_label !== '';

$search_url = add_query_arg([
    'lat'            => $search_lat,
    'lng'            => $search_lng,
    'location_label' => $search_location_label,
], site_url('/live-music/search/'));

$edit_event_url = $event_id ? add_query_arg('tab', 'edit', get_permalink($event_id)) : '';
?>

<div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

    <div class="pb-32 relative">
        <span class="text-18 sm:text-22 block text-center mb-4">Looks like we couldn't find any suggestions.</span>
        <p class="text-20 mb-4">Try selecting more genres or ensemble sizes in your event details to get more matches or start a broader search.</p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <?php if ( $edit_event_url ) { ?>
                <a href="<?php echo esc_url( $edit_event_url ); ?>"
                    class="bg-yellow shadow-black-offset border-2 border-black hover:bg-navy hover:text-white font-sun-motter text-16 px-5 py-3 inline-block">
                    Edit Event
                </a>
            <?php } ?>

            <?php if ( $has_search_location ) { ?>
                <a href="<?php echo esc_url( $search_url ); ?>"
                    class="bg-white shadow-black-offset border-2 border-black hover:bg-navy hover:text-white font-sun-motter text-16 px-5 py-3 inline-block">
                    Search Musicians
                </a>
            <?php } ?>
        </div>
    </div>

    <img class="w-40 absolute bottom-0 left-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" alt="" />
    <img class="w-40 absolute bottom-0 right-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" alt="" />

</div>
