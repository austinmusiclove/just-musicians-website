<form id="applicants-form"
    x-bind:hx-get="'<?php echo site_url('/wp-html/v1/applications/'); ?>' + applicationId + '/applicants/'"
    hx-target="#applicant-results"
    hx-indicator="#applicants-spinner-top"
    hx-trigger="intersect once, filterupdate"
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

    <div class="flex flex-wrap items-center gap-2 mb-4 pb-4">

        <div x-on:filter_status-changed="$dispatch('filterupdate');">
            <?php get_template_part('template-parts/global/form/dropdown', '', [
                'options'     => [
                    ['value' => 'all',       'label' => 'All'],
                    ['value' => 'active',    'label' => 'Active'],
                    ['value' => 'withdrawn', 'label' => 'Withdrawn'],
                ],
                'input_name'  => 'filter_status',
                'selected'    => 'active',
            ]); ?>
        </div>

        <div x-on:sort-changed="$dispatch('filterupdate');">
            <?php get_template_part('template-parts/global/form/dropdown', '', [
                'options'     => [
                    ['value' => 'recent', 'label' => 'Most Recent'],
                ],
                'input_name'  => 'sort',
                'selected'    => 'recent',
            ]); ?>
        </div>

        <div id="applicants-spinner-top" class="flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
        </div>

    </div>

    <div id="applicant-results"></div>

    <div id="applicants-spinner-bottom" class="my-8 flex items-center justify-center htmx-indicator">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
    </div>

</form>
