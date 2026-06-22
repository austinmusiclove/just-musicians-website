<div
    x-bind:hx-get="'<?php echo site_url('/wp-html/v1/events/'); ?>' + eventId + '/applicants'"
    hx-trigger="load"
    hx-target="#applicant-results"
    hx-indicator="#applicants-spinner"
    x-data='{
        get sortedCollections()                              { return getSortedCollections(this, 0); },
        _showEmptyFavoriteButton(listingId)                  { return showEmptyFavoriteButton(this, listingId); },
        _showFilledFavoriteButton(listingId)                 { return showFilledFavoriteButton(this, listingId); },
        _showEmptyCollectionButton(collectionId, listingId)  { return showEmptyCollectionButton(this, collectionId, listingId); },
        _showFilledCollectionButton(collectionId, listingId) { return showFilledCollectionButton(this, collectionId, listingId); },

        players: {},
        playersMuted: true,
        playersPaused: false,
        _initPlayer(playerId, videoData) { initPlayer(this, playerId, videoData); },
        _pauseAllPlayers()               { pauseAllPlayers(this); },
        _pausePlayer(playerId)           { pausePlayer(this, playerId); },
        _playPlayer(playerId)            { playPlayer(this, playerId); },
        _toggleMute()                    { toggleMute(this); },
        _setupVisibilityListener()       { setupVisibilityListener(this); },
    }'
    x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoData);"
    x-on:pause-all-youtube-players="_pauseAllPlayers()"
    x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
    x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
    x-on:mute-youtube-players="_toggleMute()"
    x-init="_setupVisibilityListener()"
>
    <div id="applicant-results"></div>
    <div id="applicants-spinner" class="my-8 flex items-center justify-center htmx-indicator">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
    </div>
</div>
