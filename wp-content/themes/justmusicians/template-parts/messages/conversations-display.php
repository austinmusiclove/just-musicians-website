
<div class="flex-1 overflow-y-auto w-full"
    x-show="!getCvInFlight || conversations.length > 0" x-cloak
    x-data="{
        openMenu: null,
        toggleOpenMenu(conversationId) {
            if (this.openMenu == conversationId) {
                this.openMenu = null;
            } else {
                this.openMenu = conversationId;
            }
        },
    }"
>

    <!-- No conversations -->
    <template x-if="!getCvInFlight && conversations.length == 0 && inquiry == null">
        <div class="my-4">No Conversations</div>
    </template>

    <!-- No inquiry responses-->
    <template x-if="!getCvInFlight && conversations.length == 0 && inquiry != null">
        <div class="my-4 py-16 pr-2 text-16">No conversations yet. This inquiry has not been sent to any musicians yet.</div>
    </template>

    <template x-for="(conversation, index) in conversations" :key="index">
        <span>

            <!-- Display Conversation  -->
            <template x-if="index < conversations.length-1">
                <?php echo get_template_part('template-parts/messages/conversation', '', ['is_last' => false]); ?>
            </template>

            <!-- Display last conversation: distinct from other conversations for pagination purposes -->
            <template x-if="index == conversations.length-1">
                <?php echo get_template_part('template-parts/messages/conversation', '', ['is_last' => true]); ?>
            </template>

        </span>
    </template>

    <!-- Suggestions -->
    <template x-if="inquiry">
        <div class="border-t-4 border-black/20 py-16 pr-4"
            x-bind:hx-get="`<?php echo site_url(); ?>/wp-html/v1/inquiries/${inquiry.inquiry_id}/suggestions/`"
            hx-trigger="load"
            hx-target="#inquiry-suggestion-results"
            hx-indicator="#suggestions-spinner"
            x-data="{
                players: {},
                playersMuted: true,
                playersPaused: false,
                _initPlayer(playerId, videoData) { initPlayer(this, playerId, videoData); },
                _pauseAllPlayers()               { pauseAllPlayers(this); },
                _pausePlayer(playerId)           { pausePlayer(this, playerId); },
                _playPlayer(playerId)            { playPlayer(this, playerId); },
                _toggleMute()                    { toggleMute(this); },
                _setupVisibilityListener()       { setupVisibilityListener(this); },
            }"
            x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoData);"
            x-on:pause-all-youtube-players="_pauseAllPlayers()"
            x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
            x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
            x-on:mute-youtube-players="_toggleMute()"
            x-init="_setupVisibilityListener()"
        >

            <h2 class="font-bold text-22">Need more responses?</h2>
            <p class="py-2 text-16">Send this inquiry to musicians in your area like these..</p>

            <!-- Suggestion Listings -->
            <div id="inquiry-suggestion-results">
                <?php
                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                ?>
            </div>

            <!-- Spinner -->
            <div id="suggestions-spinner" class="flex items-center justify-center" x-show="getCvInFlight" x-cloak>
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
            </div>

        </div>
    </template>


</div>
