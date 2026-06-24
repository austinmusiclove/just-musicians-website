<form id="event-applicants-form"
    x-bind:hx-get="'<?php echo site_url('/wp-html/v1/events/'); ?>' + eventId + '/applicants/'"
    hx-target="#applicant-results"
    hx-indicator="#applicants-spinner-top"
    hx-trigger="load, filterupdate"
    x-data='{
        status: "all",
        sort: "recent",
        showSuggestions: false,

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
    <input type="hidden" name="filter_status" x-model="status" />
    <input type="hidden" name="sort" x-model="sort" />

    <div class="flex flex-wrap items-center gap-2 mb-4 pb-4">
        <div x-on:filter_status-changed="status = $event.detail.value; $nextTick(() => $dispatch('filterupdate'));">
            <?php get_template_part('template-parts/global/form/dropdown', '', [
                'options'     => [
                    ['value' => 'response',    'label' => 'All Responses'],
                    ['value' => 'available',   'label' => 'Available'],
                    ['value' => 'unavailable', 'label' => 'Unavailable'],
                    ['value' => 'inquiry',     'label' => 'Invited to Respond'],
                    //['value' => 'all',         'label' => 'All'],
                ],
                'input_name'  => 'filter_status',
                'selected'    => 'response',
            ]); ?>
        </div>

        <?php
        $sort_options = [
            ['value' => 'recent',      'label' => 'Most Recent'],
            ['value' => 'high-rating', 'label' => 'Highest Rating'],
        ];

        if (get_field('request_quote')) {
            $sort_options[] = ['value' => 'high-quote', 'label' => 'Highest Quote'];
            $sort_options[] = ['value' => 'low-quote',  'label' => 'Lowest Quote'];
        }

        if (get_field('request_draw')) {
            $sort_options[] = ['value' => 'high-draw',  'label' => 'Highest Draw'];
            $sort_options[] = ['value' => 'low-draw',   'label' => 'Lowest Draw'];
        }
        ?>

        <div x-on:sort-changed="sort = $event.detail.value; $nextTick(() => $dispatch('filterupdate'));">
            <?php get_template_part('template-parts/global/form/dropdown', '', [
                'options'     => $sort_options,
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
