<?php
/**
 * The template for the listings landing page
 *
 * @package JustMusicians
 */

get_header();

?>

<div id="page" class="flex flex-col grow">

        <input type="hidden" name="search" value="" x-bind:value="searchInput" x-init="$watch('searchInput', value => { searchVal = value; $dispatch('filterupdate'); })" />
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-0">
                      <?php echo get_template_part('template-parts/account/sidebar', '', array()); ?>
                    </div>
                </div>
                <div class="col md:col-span-6 py-6 md:py-12">

                    <div class="mb-14 flex justify-between items-center flex-row">
                        <h1 class="font-bold text-25">My Listings</h1>
                        <button class="font-bold text-12 pt-1.5 pb-1 px-1.5 rounded bg-white border border-black/20 cursor-pointer">Add +</button>
                    </div>

                    <div class="flex items-center justify-between md:justify-start">
                        <?php echo get_template_part('template-parts/search/sort', '', array()); ?>
                    </div>

                    <script>
                        window.onYouTubeIframeAPIReady = function () { document.dispatchEvent(new Event('youtube-api-ready')); };
                    </script>

                    <span id="results"
                        x-data='{
                            players: {},
                            playersMuted: true,
                            playersPaused: false,
                            initPlayerFromIframe(playerId) {
                                if (playerId) {
                                    var player = new YT.Player(playerId, {
                                        playerVars: {
                                            controls: 0,
                                            origin: "<?php echo site_url(); ?>",
                                            enablejsapi: 1,
                                        },
                                        events: {
                                            "onReady": () => {
                                                this.players[playerId].isReady = true;
                                                if (!this.playersMuted) { this.players[playerId].unMute(); }
                                            },
                                            "onStateChange": (event) => {
                                                this.players[playerId].state = event.data;
                                                if (event.data == 1) { this.players[playerId].isPaused = false; } // 1 is playing
                                                if (event.data == 2) { this.players[playerId].isPaused = true; } // 2 is paused
                                            }
                                        }
                                    });
                                    this.players[playerId] = player;
                                }
                            },
                            pausePlayer(playerId) {
                                if (playerId && this.players[playerId] && this.players[playerId].isReady) {
                                    var playerState = this.players[playerId].state;
                                    if (playerState == -1 || playerState == 3) { // -1 is unstarted and 3 is buffering
                                        this.players[playerId].stopVideo();
                                    } else {
                                        this.players[playerId].pauseVideo();
                                    }
                                    this.players[playerId].isPaused = true; // redundant but this usually fires before onStateChange creating a snappier UX
                                }
                            },
                            playPlayer(playerId) {
                                if (playerId && this.players[playerId] && this.players[playerId].isReady) {
                                    this.players[playerId].playVideo();
                                    this.players[playerId].isPaused = false; // redundant but this usually fires before onStateChange creating a snappier UX
                                }
                            },
                            toggleMute() {
                                if (this.playersMuted) {
                                    Object.values(this.players).forEach((player) => {if (player.isReady) { player.unMute(); }});
                                } else {
                                    Object.values(this.players).forEach((player) => {if (player.isReady) { player.mute(); }});
                                }
                                this.playersMuted = !this.playersMuted;
                            },
                        }'
                        x-on:init-youtube-player="initPlayerFromIframe($event.detail.playerId);"
                        x-on:pause-youtube-player="pausePlayer($event.detail.playerId)"
                        x-on:play-youtube-player="playPlayer($event.detail.playerId)"
                        x-on:mute-youtube-players="toggleMute()"
                    >
                        <?php
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                            echo get_template_part('template-parts/search/standard-listing-skeleton');
                        ?>
                    </span>


                    <div class="xl:hidden">
                        <?php
                        // Mobile form - moves to right column at lg breakpoint
                        echo get_template_part('template-parts/global/form-quote', '', array(
                            'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                            'responsive' => 'xl:border-none xl:p-0'
                        ));
                        ?>
                    </div>
                </div>
                <?php if (!is_user_logged_in()) {
                    if (!empty($_GET['lic']) and !empty($_GET['mdl']) and $_GET['mdl'] == 'signup') { ?>
                        <span x-init="showLoginModal = false; showSignupModal = true; signupModalMessage = 'Sign up to activate this listing invitation link';"></span>
                    <?php } else if (isset($_GET['lic'])) { ?>
                        <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to activate this listing invitation link';"></span>
                    <?php } else { ?>
                        <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your listings';"></span>
                    <?php }
                } ?>
            </div>
        </div>
</div>

<?php
get_footer();

