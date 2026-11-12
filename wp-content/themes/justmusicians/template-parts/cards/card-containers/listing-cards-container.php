<?php
$indicator_class = $args['indicator_class'] ?? 'spinner-start';
$collections_map = $args['collections_map'] ?? [];
$send_first_page = $args['send_first_page'] ?? false;
$listings        = $args['listings'] ?? [];
$reorderable     = $args['reorderable'] ?? false;
$collection_id   = $args['collection_id'] ?? 0;
?>

<span id="<?php echo $args['id']; ?>" class="<?php echo $indicator_class; ?> htmx-indicator-block-replace"
    x-data='Object.assign(
        favoriteButtonData(),
        youTubeIframeContainerData(),
        mediaModalData(),
    )'
    x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoData);"
    x-on:clear-all-youtube-players="_clearAllPlayers()"
    x-on:pause-all-youtube-players="_pauseAllPlayers()"
    x-on:pause-youtube-player="_pausePlayerById($event.detail.playerId)"
    x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
    x-on:mute-youtube-players="_toggleMute()"
    x-init="_setupVisibilityListener()"

    <?php if ($reorderable) { ?>
    x-sort="$dispatch('reorder-collection');"
    x-sort:config="{'handle': '[data-reorder-handle]'}"
    hx-post="/wp-html/v1/collections/<?php echo $collection_id; ?>/reorder/"
    hx-trigger="reorder-collection"
    hx-target="#results"
    hx-swap="afterend"
    <?php } ?>
>
<?php
    if ($send_first_page) {
        // Render listings
        if (count($listings) > 0) {
            foreach($listings as $index => $listing) {
                $genres = [];
                $listing['genre'] ??= [];
                if (!empty($listing['genre'])) {
                    $genres = array_map(fn($genre) => $genre->name, $listing['genre']);
                }
                get_template_part('template-parts/cards/standard-listing-card', '', [
                    'post_id'                => $listing['post_id'],
                    'name'                   => $listing['name'],
                    'rating'                 => $listing['rating'],
                    'review_count'           => $listing['review_count'],
                    'reviews'                => $listing['reviews'],
                    'city'                   => $listing['city'],
                    'state'                  => $listing['state'],
                    'zip_code'               => $listing['zip_code'],
                    'bio'                    => $listing['bio'],
                    'location'               => $listing['city'] . ', ' . $listing['state'],
                    'description'            => $listing['description'],
                    'genres'                 => $genres,
                    'ensemble_size'          => $listing['ensemble_size'],
                    'thumbnail_url'          => $listing['thumbnail_url'],
                    'phone'                  => isset($listing['phone']) ? $listing['phone'] : null,
                    'website'                => $listing['website'],
                    'facebook_url'           => $listing['facebook_url'],
                    'instagram_url'          => $listing['instagram_url'],
                    'x_url'                  => $listing['x_url'],
                    'youtube_url'            => $listing['youtube_url'],
                    'tiktok_url'             => $listing['tiktok_url'],
                    'bandcamp_url'           => $listing['bandcamp_url'],
                    'spotify_artist_url'     => $listing['spotify_artist_url'],
                    'apple_music_artist_url' => $listing['apple_music_artist_url'],
                    'soundcloud_url'         => $listing['soundcloud_url'],
                    'youtube_video_data'     => $listing['youtube_video_data'],
                    'listing_image_urls'     => $listing['listing_image_urls'],
                    'verified'               => $listing['verified'],
                    'rank'                   => $listing['rank'] ?? 0,
                    'permalink'              => $listing['permalink'],
                    'position'               => $listing['position'],
                    'lazyload_thumbnail'     => $index >= 3,
                    'last'                   => $index == array_key_last($listings),
                    'is_last_page'           => $args['is_last_page'],
                    'next_page'              => $args['next_page'],
                ]);
            }

            get_template_part('template-parts/global/schema/item-list-schema', '', [
                'listings' => $listings,
            ]);

        } else {
            get_template_part( 'template-parts/global/empty-states/no-search-results');
        }
        if ($args['is_last_page']) {
            get_template_part( 'template-parts/global/empty-states/no-more-results');
        }
    }
?>
<?php get_template_part('template-parts/media-sliders/modal-media-slider', '', []); ?>
</span>
