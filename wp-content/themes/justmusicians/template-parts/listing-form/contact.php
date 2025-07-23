
<section class="flex flex-col gap-5">

    <div class="flex items-center gap-2">
        <img class="h-7 sm:h-8 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/contact-info.svg'; ?>" />
        <h2 class="text-20 sm:text-25 font-bold">Contact Information and Links</h2>
    </div>


    <fieldgroup class="block border-b border-black/20 pb-6">
        <div class="grid sm:grid-cols-3 gap-2">
            <!-- Email -->
            <div class="grow relative">
                <label for="listing_email" class="mb-1 inline-block">Email (not public)<span class="text-red">*</span></label>
                <img class="h-5 absolute bottom-2.5 left-3" src="<?php echo get_template_directory_uri() . '/lib/images/icons/email.svg'; ?>" />
                <input class="has-icon" type="text" id="listing_email" name="listing_email" placeholder="example@example.com" title="example@example.com" x-model="pEmail" >
            </div>
            <!-- Phone -->
            <div class="grow relative">
                <label for="phone" class="mb-1 inline-block">Phone (not public)<span class="text-red">*</span></label><br>
                <img class="h-5 absolute bottom-2.5 left-3" src="<?php echo get_template_directory_uri() . '/lib/images/icons/phone.svg'; ?>" />
                <input class="has-icon" type="tel" id="phone" name="phone"
                        placeholder="(555) 555-5555" maxlength="14"
                        title="Format: (555) 555-5555"
                        x-mask="(999) 999-9999"
                        x-model="pPhone"
                >
            </div>
            <!-- Website -->
            <div class="grow relative">
                <label for="zip_code" class="mb-1 inline-block">Website</label>
                <img class="h-5 absolute bottom-2.5 left-3" src="<?php echo get_template_directory_uri() . '/lib/images/icons/website.svg'; ?>" />
                <input class="has-icon" type="text" id="website" name="website" placeholder="https://" x-model="pWebsite" >

            </div>
        </div>
    </fieldgroup>

    <fieldgroup class="grid sm:grid-cols-3 gap-2">
        <!-- Instagram -->
        <div>
            <label class="mb-1 inline-block" for="instagram_handle">Instagram Handle</label><br>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">@</span>
                <input class="!pl-7 w-full px-3 py-2" type="text" id="instagram_handle" name="instagram_handle"
                    pattern="^[A-Za-zA-Z0-9_\.]{1,30}$"
                    title="Instagram handle must be 1-30 characters long and can only include letters, numbers, underscores, or periods. No @ sign."
                    x-model="pInstagramHandle">
            </div>
            <input type="hidden" id="instagram_url" name="instagram_url"
                x-init="$watch('pInstagramHandle', value => pInstagramUrl = value ? 'https://instagram.com/' + value : '')"
                x-model="pInstagramUrl">
        </div>
        <!-- Tiktok -->
        <div>
            <label class="mb-1 inline-block" for="tiktok_handle">Tiktok Handle</label><br>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">@</span>
                <input class="!pl-7 w-full px-3 py-2" type="text" id="tiktok_handle" name="tiktok_handle"
                    pattern="^[a-zA-Z0-9_.]+$"
                    title="Handle can only contain letters, numbers, underscores, and periods. No @ sign."
                    x-model="pTiktokHandle">
            </div>
            <input type="hidden" id="tiktok_url" name="tiktok_url"
                x-init="$watch('pTiktokHandle', value => pTiktokUrl = value ? 'https://tiktok.com/@' + value : '')"
                x-model="pTiktokUrl">
        </div>
         <!-- X -->
         <div>
            <label class="mb-1 inline-block" for="x_handle">X Handle</label><br>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">@</span>
                <input class="!pl-7 w-full px-3 py-2" type="text" id="x_handle" name="x_handle"
                    pattern="^[A-Za-zA-Z0-9_\.]{1,15}$"
                    title="X handle must be 1-15 characters long and can only include letters, numbers, underscores, or periods. No @ sign."
                    x-model="pXHandle">
            </div>
            <input type="hidden" id="x_url" name="x_url"
                x-init="$watch('pXHandle', value => pXUrl = value ? 'https://x.com/@' + value : '')"
                x-model="pXUrl">
        </div>

    </fieldgroup>

    <fieldgroup class="grid sm:grid-cols-3 gap-2">
        <!-- Facebook -->
        <div class="grow"><label class="mb-1 inline-block text-18 lg:text-16 xl:text-18" for="facebook_url">Facebook URL</label><br>
        <input type="text" id="facebook_url" name="facebook_url" placeholder="https://www.facebook.com/" x-model="pFacebookUrl"></div>
        <!-- Youtube -->
        <div class="grow"><label class="mb-1 inline-block text-18 lg:text-16 xl:text-18" for="youtube_url">Youtube Channel URL</label><br>
        <input type="text" id="youtube_url"  name="youtube_url"  placeholder="https://www.youtube.com/@" x-model="pYoutubeUrl" ></div>
        <!-- Bandcamp -->
        <div class="grow"><label class="mb-1 inline-block text-18 lg:text-16 xl:text-18" for="bandcamp_url">Bandcamp URL</label><br>
        <input type="text" id="bandcamp_url" name="bandcamp_url" placeholder="https://bandname.bandcamp.com/" x-model="pBandcampUrl"></div>
    </fieldgroup>

    <fieldgroup class="grid sm:grid-cols-3 gap-2">
        <!-- Spotify -->
        <div class="grow" x-data="{
                spotifyArtistId: '',
                setSpotifyArtistId() { this.spotifyArtistId = pSpotifyArtistUrl.match(/artist\/([a-zA-Z0-9]+)/) ? pSpotifyArtistUrl.match(/artist\/([a-zA-Z0-9]+)/)[1] : ''; },
            }">
                <label class="mb-1 inline-block text-18 lg:text-16 xl:text-18" for="spotify_artist_url">Spotify Artist URL</label><br>
                <input type="text" id="spotify_artist_url" name="spotify_artist_url" placeholder="https://open.spotify.com/artist/"
                    x-init="setSpotifyArtistId()"
                    x-on:input="setSpotifyArtistId()"
                    x-model="pSpotifyArtistUrl"
                >
                <input type="hidden" id="spotify_artist_id" name="spotify_artist_id" x-bind:value="spotifyArtistId">
            </div>
        <!-- Apple Music -->
        <div class="grow"><label class="mb-1 inline-block text-18 lg:text-16 xl:text-18" for="apple_music_artist_url">Apple Music Artist URL</label><br>
        <input type="text" id="apple_music_artist_url" name="apple_music_artist_url" placeholder="https://music.apple.com/us/artist/" x-model="pAppleMusicArtistUrl"></div>
        <!-- Soundcloud -->
        <div class="grow"><label class="mb-1 inline-block text-18 lg:text-16 xl:text-18" for="soundcloud_url">Soundcloud URL</label><br>
        <input type="text" id="soundcloud_url" name="soundcloud_url" placeholder="https://soundcloud.com/" x-model="pSoundcloudUrl"></div>
    </fieldgroup>


</section>
