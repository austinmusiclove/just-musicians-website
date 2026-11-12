<?php
$listing_data = $args['listing'] ?? [];
$genres       = $args['genres'] ?? [];
?>

<span x-show="showSearchResultTab" x-cloak
    x-data='Object.assign(
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
>
    <?php get_template_part('template-parts/cards/standard-listing-card-preview', '', [
        'genres'                        => $genres, // pass all genres; x-show expression will show the selected options
        'ensemble_size'                 => $listing_data['ensemble_size'],
        'verified'                      => $listing_data ? $listing_data['verified'] : false,
        'rating'                        => $listing_data['rating']       ?? 0,
        'review_count'                  => $listing_data['review_count'] ?? 0,
    ]); ?>

    <div class="opacity-50">
        <?php
            get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton', '', []);
            get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton', '', []);
        ?>
    </div>

    <?php get_template_part('template-parts/media-sliders/modal-media-slider', '', []); ?>
</span>
