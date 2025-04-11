<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

$listing_data = null;
if (!empty($_GET['lid'])) {
    $listing_data = get_listing(['post_id' => $_GET['lid']]);
}
$categories = array_map(function($term) { return $term->name; }, get_terms(array( 'taxonomy' => 'mcategory', 'hide_empty' => false,)));
$genres = array_map(function($term) { return $term->name; }, get_terms(array( 'taxonomy' => 'genre', 'hide_empty' => false,)));
$subgenres = array_map(function($term) { return $term->name; }, get_terms(array( 'taxonomy' => 'subgenre', 'hide_empty' => false,)));
$instrumentations = array_map(function($term) { return $term->name; }, get_terms(array( 'taxonomy' => 'instrumentation', 'hide_empty' => false,)));
$settings = array_map(function($term) { return $term->name; }, get_terms(array( 'taxonomy' => 'setting', 'hide_empty' => false,)));
$filename_prefix = get_current_user_id() . '_' . time() . '_' . (!empty($listing_data['post_meta']['name'][0]) ? sanitize_title($listing_data['post_meta']['name'][0]) : '');


get_header();


/*
Fields to cover

Basic
    Type (only new post)
    Ensemble Size
Taxonomy
    Unofficial tags
Media
    Gallery Images
    Youtube Video URLs
        start at
        taxonomy for each video
    Stage plots with labels
Venues
    Venues Verified
    Venues Played Unverified
    Venues Played Unverified Strings
Other
    Offers Sound Setup
    Pricing?
    Accepted payment methods
    Service area
    Show dates and availability
    Press Mentions
    Bands you have played or would play with
Calculated Unseen
    Status
    Content
    Rank

*/

?>
<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden <?php echo $header_padding; ?>">
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
    </div>
</header>

<div class="container md:grid md:grid-cols-12 py-8 min-h-[500px]"
    x-data="{
        pName:                '<?php if ($listing_data) { echo str_replace("'", "\'", $listing_data["post_meta"]["name"][0]); } ?>',
        pDescription:         '<?php if ($listing_data) { echo str_replace("'", "\'", $listing_data["post_meta"]["description"][0]); } ?>',
        pCity:                '<?php if ($listing_data) { echo str_replace("'", "\'", $listing_data["post_meta"]["city"][0]); } ?>',
        pState:               '<?php if ($listing_data) { echo str_replace("'", "\'", $listing_data["post_meta"]["state"][0]); } ?>',
        pInstagramHandle:     '<?php if ($listing_data) { echo $listing_data["post_meta"]["instagram_handle"][0]; } ?>',
        pInstagramUrl:        '<?php if ($listing_data) { echo $listing_data["post_meta"]["instagram_url"][0]; } ?>',
        pTiktokHandle:        '<?php if ($listing_data) { echo $listing_data["post_meta"]["tiktok_handle"][0]; } ?>',
        pTiktokUrl:           '<?php if ($listing_data) { echo $listing_data["post_meta"]["tiktok_url"][0]; } ?>',
        pXHandle:             '<?php if ($listing_data) { echo $listing_data["post_meta"]["x_handle"][0]; } ?>',
        pXUrl:                '<?php if ($listing_data) { echo $listing_data["post_meta"]["x_url"][0]; } ?>',
        pWebsite:             '<?php if ($listing_data) { echo $listing_data["post_meta"]["website"][0]; } ?>',
        pFacebookUrl:         '<?php if ($listing_data) { echo $listing_data["post_meta"]["facebook_url"][0]; } ?>',
        pYoutubeUrl:          '<?php if ($listing_data) { echo $listing_data["post_meta"]["youtube_url"][0]; } ?>',
        pBandcampUrl:         '<?php if ($listing_data) { echo $listing_data["post_meta"]["bandcamp_url"][0]; } ?>',
        pSpotifyArtistUrl:    '<?php if ($listing_data) { echo $listing_data["post_meta"]["spotify_artist_url"][0]; } ?>',
        pAppleMusicArtistUrl: '<?php if ($listing_data) { echo $listing_data["post_meta"]["apple_music_artist_url"][0]; } ?>',
        pSoundcloudUrl:       '<?php if ($listing_data) { echo $listing_data["post_meta"]["soundcloud_url"][0]; } ?>',
        pThumbnailSrc:        '<?php if (!empty($listing_data['thumbnail_url']))                 { echo $listing_data['thumbnail_url']; } else { echo get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp'; } ?>',
        categoriesCheckboxes:  <?php if (!empty($listing_data["taxonomies"]["mcategory"]))       { echo array_2_doublequote_str($listing_data["taxonomies"]["mcategory"]);       } else { echo '[]'; }?>,
        genresCheckboxes:      <?php if (!empty($listing_data["taxonomies"]["genre"]))           { echo array_2_doublequote_str($listing_data["taxonomies"]["genre"]);           } else { echo '[]'; } ?>,
        subgenresCheckboxes:   <?php if (!empty($listing_data["taxonomies"]["subgenre"]))        { echo array_2_doublequote_str($listing_data["taxonomies"]["subgenre"]);        } else { echo '[]'; } ?>,
        instCheckboxes:        <?php if (!empty($listing_data["taxonomies"]["instrumentation"])) { echo array_2_doublequote_str($listing_data["taxonomies"]["instrumentation"]); } else { echo '[]'; } ?>,
        settingsCheckboxes:    <?php if (!empty($listing_data["taxonomies"]["setting"]))         { echo array_2_doublequote_str($listing_data["taxonomies"]["setting"]);         } else { echo '[]'; } ?>,
        getListingLocation() { return this.pCity && this.pState ? `${this.pCity}, ${this.pState}` : this.pCity || this.pState || ''; },
        showGenre(term)      { return this.genresCheckboxes.includes(term); },
    }"
    x-on:updatethumbnail.window="pThumbnailSrc = $event.detail;"
>
    <div class="col-span-12 lg:col-span-6">
        <form action="/wp-json/v1/listings" enctype="multipart/form-data" class="flex flex-col gap-4"
            hx-post="<?php echo site_url('wp-html/v1/listings'); ?>"
            hx-headers='{"X-WP-Nonce": "<?php echo wp_create_nonce('wp_rest'); ?>" }'
            hx-target="#result">
            <?php if ($listing_data) { ?><input type="hidden" id="post_id" name="post_id" value="<?php echo $_GET['lid']; ?>"><?php } ?>
            <!--
            <input type="hidden" id="verified-venues" name="verified-venues">
            -->

            <!------------ Basic Information ----------------->
            <h2 class="mt-8 font-bold text-24 md:text-36 lg:text-40">Basic Information</h2>

            <!-- Listing type -->
            <!--
            <label for="listing-type">Listing Type. Chose the option that best describes your act. If you do multiple things, you may submit multiple listings.</label><br>
            <input class="button-input" value="Musician" type="radio" name="listing-type" id="musician"/>
            <label class="button-label" for="musician">Musician</label>
            <input class="button-input" value="Band" type="radio" name="listing-type" id="band"/>
            <label class="button-label" for="band">Band</label>
            <input class="button-input" value="DJ" type="radio" name="listing-type" id="dj"/>
            <label class="button-label" for="dj">DJ</label>
            <input class="button-input" value="Artist" type="radio" name="listing-type" id="artist"/>
            <label class="button-label" for="artist">Artist</label>
            -->

            <!-- Performer Name -->
            <div><label for="listing_name">Performer or Band Name</label><br>
            <input type="text" id="listing_name" name="listing_name" autocomplete="off" required x-model="pName"></div>

            <!-- Description -->
            <div><label for="description">40 Character Description. This will appear just below your name in your listing.</label>
            <!--
            <span class="tooltip">
                i<span class="tooltip-text">Examples: Psych rock band, Cello player, 90s cover band</span>
            </span><br>
            -->
            <input type="text" id="description" name="description" maxlength="40" placeholder="5-piece Country Band" required x-model="pDescription"></div>

            <!-- City -->
            <div><label for="city">City (This would be where you consider yourself to be "based out of" not where you are from)</label>
            <input type="text" id="city" name="city" required x-model="pCity"></div>

            <!-- State -->
            <div><label for="state">State</label><br>
            <input type="text" id="state" name="state" required x-model="pState"></div>

            <!-- Zip Code -->
            <div><label for="zip_code">Zip Code</label>
            <!--
            <span class="tooltip">
                i<span class="tooltip-text">This will be used to help match buyers with musicians who are broadly geographically near by.</span>
            </span><br>
            -->
            <input type="text" id="zip_code" name="zip_code"
                pattern="^\d{5}(-\d{4})?$"
                title="Enter a valid ZIP code (e.g., 12345 or 12345-6789)."
                <?php if ($listing_data) { echo 'value="' . $listing_data['post_meta']['zip_code'][0] . '"'; } ?>>
            </div>

            <!-- Bio -->
            <div><label for="bio">Bio</label><br>
            <textarea id="bio" name="bio" class="w-full h-32"><?php if ($listing_data) { echo $listing_data['post_meta']['bio'][0]; } ?></textarea></div>

            <!-- Ensemble Size -->
            <!--
            <label for="ensemble-size">How many performers in your group? If you perform with different ensemble sizes, check all that apply.</label><br>
            <input class="button-input" value="1" type="checkbox" name="ensemble_size[]" id="ensemble-size-1"/>
            <label class="button-label" for="ensemble-size-1">1</label><br>
            <input class="button-input" value="2" type="checkbox" name="ensemble_size[]" id="ensemble-size-2"/>
            <label class="button-label" for="ensemble-size-2">2</label><br>
            <input class="button-input" value="3" type="checkbox" name="ensemble_size[]" id="ensemble-size-3"/>
            <label class="button-label" for="ensemble-size-3">3</label><br>
            <input class="button-input" value="4" type="checkbox" name="ensemble_size[]" id="ensemble-size-4"/>
            <label class="button-label" for="ensemble-size-4">4</label><br>
            <input class="button-input" value="5" type="checkbox" name="ensemble_size[]" id="ensemble-size-5"/>
            <label class="button-label" for="ensemble-size-5">5</label><br>
            <input class="button-input" value="6" type="checkbox" name="ensemble_size[]" id="ensemble-size-6"/>
            <label class="button-label" for="ensemble-size-6">6</label><br>
            <input class="button-input" value="7" type="checkbox" name="ensemble_size[]" id="ensemble-size-7"/>
            <label class="button-label" for="ensemble-size-7">7</label><br>
            <input class="button-input" value="8" type="checkbox" name="ensemble_size[]" id="ensemble-size-8"/>
            <label class="button-label" for="ensemble-size-8">8</label><br>
            <input class="button-input" value="9" type="checkbox" name="ensemble_size[]" id="ensemble-size-9"/>
            <label class="button-label" for="ensemble-size-9">9</label><br>
            <input class="button-input" value="10+" type="checkbox" name="ensemble_size[]" id="ensemble-size-10"/>
            <label class="button-label" for="ensemble-size-10">10+</label><br>
            -->

            <!------------ Contact and Links ----------------->
            <h2 class="mt-8 font-bold text-24 md:text-36 lg:text-40">Contact and Links</h2>
            <!-- Email -->
            <div><label for="listing_email">Email</label><br>
                <input type="email" id="listing_email" name="listing_email" placeholder="example@example.com"
                    pattern="[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$"
                    title="example@example.com"
                    <?php if ($listing_data) { echo 'value="' . $listing_data['post_meta']['email'][0] . '"'; } ?>>
            </div>
            <!-- Phone -->
            <div><label for="phone">Phone</label><br>
                <input type="tel" id="phone" name="phone"
                    placeholder="(555) 555-5555" maxlength="14"
                    pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}"
                    title="Format: (555) 555-5555"
                    x-mask="(999) 999-9999"
                    <?php if ($listing_data) { echo 'value="' . $listing_data['post_meta']['phone'][0] . '"'; } ?>>
            </div>
            <!-- Instagram -->
            <div>
                <label for="instagram_handle">Instagram Handle</label><br>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">@</span>
                    <input class="!pl-7 w-full px-3 py-2" type="text" id="instagram_handle" name="instagram_handle"
                        pattern="^[A-Za-zA-Z0-9_\.]{1,30}$"
                        title="Instagram handle must be 1-30 characters long and can only include letters, numbers, underscores, or periods. No @ sign."
                        <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['instagram_handle'][0] . '"' : ''; ?>
                        x-model="pInstagramHandle">
                </div>
                <input type="hidden" id="instagram_url" name="instagram_url"
                    x-init="$watch('pInstagramHandle', value => pInstagramUrl = value ? 'https://instagram.com/' + value : '')"
                    x-model="pInstagramUrl">
            </div>
            <!-- Tiktok -->
            <div>
                <label for="tiktok_handle">Tiktok Handle</label><br>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">@</span>
                    <input class="!pl-7 w-full px-3 py-2" type="text" id="tiktok_handle" name="tiktok_handle"
                        pattern="^[a-zA-Z0-9_.]+$"
                        title="Handle can only contain letters, numbers, underscores, and periods. No @ sign."
                        <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['tiktok_handle'][0] . '"' : ''; ?>
                        x-model="pTiktokHandle">
                </div>
                <input type="hidden" id="tiktok_url" name="tiktok_url"
                    x-init="$watch('pTiktokHandle', value => pTiktokUrl = value ? 'https://tiktok.com/@' + value : '')"
                    x-model="pTiktokUrl">
            </div>
            <!-- X -->
            <div>
                <label for="x_handle">X Handle</label><br>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">@</span>
                    <input class="!pl-7 w-full px-3 py-2" type="text" id="x_handle" name="x_handle"
                        pattern="^[A-Za-zA-Z0-9_\.]{1,15}$"
                        title="X handle must be 1-15 characters long and can only include letters, numbers, underscores, or periods. No @ sign."
                        <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['x_handle'][0] . '"' : ''; ?>
                        x-model="pXHandle">
                </div>
                <input type="hidden" id="x_url" name="x_url"
                    x-init="$watch('pXHandle', value => pXUrl = value ? 'https://x.com/@' + value : '')"
                    x-model="pXUrl">
            </div>
            <!-- Website -->
            <div><label for="website">Website</label><br>
            <input type="url" id="website" name="website" placeholder="https://" x-model="pWebsite" <?php if ($listing_data) { echo 'value="' . $listing_data['post_meta']['website'][0] . '"'; } ?> ></div>
            <!-- Facebook -->
            <div><label for="facebook_url">Facebook URL</label><br>
            <input type="url" id="facebook_url" name="facebook_url" placeholder="https://www.facebook.com/"      x-model="pFacebookUrl" <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['facebook_url'][0] . '"' : ''; ?> ></div>
            <!-- Youtube -->
            <div><label for="youtube_url">Youtube Channel URL</label><br>
            <input type="url" id="youtube_url"  name="youtube_url"  placeholder="https://www.youtube.com/@"      x-model="pYoutubeUrl"  <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['youtube_url'][0]  . '"' : ''; ?> ></div>
            <!-- Bandcamp -->
            <div><label for="bandcamp_url">Bandcamp URL</label><br>
            <input type="url" id="bandcamp_url" name="bandcamp_url" placeholder="https://bandname.bandcamp.com/" x-model="pBandcampUrl" <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['bandcamp_url'][0] . '"' : ''; ?> ></div>
            <!-- Spotify -->
            <div x-data="{
                spotifyArtistId: '',
                setSpotifyArtistId() { this.spotifyArtistId = pSpotifyArtistUrl.match(/artist\/([a-zA-Z0-9]+)/) ? pSpotifyArtistUrl.match(/artist\/([a-zA-Z0-9]+)/)[1] : ''; },
            }">
                <label for="spotify_artist_url">Spotify Artist URL</label><br>
                <input type="url" id="spotify_artist_url" name="spotify_artist_url" placeholder="https://open.spotify.com/artist/"
                    <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['spotify_artist_url'][0] . '"' : ''; ?>
                    x-init="setSpotifyArtistId()"
                    x-on:input="setSpotifyArtistId()"
                    x-model="pSpotifyArtistUrl"
                >
                <input type="hidden" id="spotify_artist_id" name="spotify_artist_id" x-bind:value="spotifyArtistId">
            </div>
            <!-- Apple Music -->
            <div><label for="apple_music_artist_url">Apple Music Artist URL</label><br>
            <input type="url" id="apple_music_artist_url" name="apple_music_artist_url" placeholder="https://music.apple.com/us/artist/" x-model="pAppleMusicArtistUrl"  <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['apple_music_artist_url'][0] . '"' : ''; ?> ></div>
            <!-- Soundcloud -->
            <div><label for="soundcloud_url">Soundcloud URL</label><br>
            <input type="url" id="soundcloud_url" name="soundcloud_url" placeholder="https://soundcloud.com/" x-model="pSoundcloudUrl" <?php echo $listing_data ? 'value="' . $listing_data['post_meta']['soundcloud_url'][0] . '"' : ''; ?> ></div>

            <!------------ Taxonomies ----------------->
            <h2 class="mt-8 font-bold text-24 md:text-36 lg:text-40">Search Optimization</h2>
            <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                'title' => 'Categories',
                'terms' => $categories,
                'input_name' => 'categories',
                'input_x_model' => 'categoriesCheckboxes',
            ]); ?>
            <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                'title' => 'Genres',
                'terms' => $genres,
                'input_name' => 'genres',
                'input_x_model' => 'genresCheckboxes',
                'max_options' => 6,
            ]); ?>
            <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                'title' => 'Sub-Genres',
                'terms' => $subgenres,
                'input_name' => 'subgenres',
                'input_x_model' => 'subgenresCheckboxes',
            ]); ?>
            <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                'title' => 'Instrumentation',
                'terms' => $instrumentations,
                'input_name' => 'instrumentations',
                'input_x_model' => 'instCheckboxes',
            ]); ?>
            <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                'title' => 'Settings',
                'terms' => $settings,
                'input_name' => 'settings',
                'input_x_model' => 'settingsCheckboxes',
            ]); ?>
            <!-- Other Keywords -->
            <!--
            <label for="tags">Keywords</label>
            <span class="tooltip">
                i<span class="tooltip-text">Type a keyword and press enter to add it</span>
            </span><br>
            <div>Enter any key words that you would want to appear in search results for. i.e. wedding band, cover band etc.</div>
            <div class="error-container" id="tag-input-error"></div><br>
            <div id="selected-tags"></div>
            <input type="text" id="tags-input"/>
            <div style="display:none;" class="dropdown" id="tag-options"></div>
            -->

            <!-- Venues Played -->
            <!--
            <label for="venues">Venues</label>
            <span class="tooltip">
                i<span class="tooltip-text">Type a venue name and press enter to add it</span>
            </span><br>
            <div>Enter any venues you have played that you would like to be listed on your profile</div>
            <div class="error-container" id="venue-input-error"></div><br>
            <div id="selected-venues"></div>
            <input type="text" id="venues-input"/>
            <div style="display:none;" class="dropdown" id="venue-options"></div>
            -->

            <!------------ Media ----------------->
            <h2 class="mt-8 font-bold text-24 md:text-36 lg:text-40">Media</h2>
            <!-- Thumbnail -->
            <label for="thumbnail">Thumbnail</label><br>
            <div x-data="{
                    cropper: null,
                    filenamePrefix: '<?php echo $filename_prefix; ?>',
                    showCropButton: <?php if (!empty($listing_data['thumbnail_url'])) { echo 'true'; } else { echo 'false'; } ?>,
                    _initCropper(displayElement, croppedImageInput) {
                        initCropper(this, displayElement, croppedImageInput);
                    },
                    _initCropperFromFile(event, displayElement, croppedImageInput) {
                        initCropperFromFile(this, event, displayElement, croppedImageInput);
                    },
                }">
                <input id="thumbnail" name="thumbnail" type="file" accept="image/png, image/jpeg, image/jpg, image/webp"
                    x-on:change="_initCropperFromFile($event, $refs.thumbnailDisplay, $refs.croppedImageInput); showCropButton = false;"
                >
                <br>
                <button class="text-16 mt-4 px-2 py-1 bg-yellow border border-black rounded text-sm cursor-pointer hover:bg-navy hover:text-white" type="button" x-on:click="_initCropper($refs.thumbnailDisplay, $refs.croppedImageInput)" x-show="showCropButton" x-cloak>Crop Current Thumbnail</button>
                <input id="cropped-thumbnail" name="cropped-thumbnail" type="file" style="display:none" accept="image/*" x-ref="croppedImageInput">
                <div class="my-4 max-h-[400px]" >
                    <!--<img id="thumbnail-display" <?php //if (!empty($listing_data['thumbnail_url'])) { echo 'src="' . $listing_data['thumbnail_url'] . '"'; } ?> x-ref="thumbnailDisplay" x-init="_initCropper($el, $refs.croppedImageInput)" />-->
                    <img id="thumbnail-display" <?php if (!empty($listing_data['thumbnail_url'])) { echo 'src="' . $listing_data['thumbnail_url'] . '"'; } ?> x-ref="thumbnailDisplay" />
                </div>
            </div>

            <!-- Youtube links -->
            <!--
            <label for="media">Youtube Video Links</label><br>
            <div>This is your chance to show your stuff to talent buyers. Paste a Youtube video link into the box. Add as many as you wish. Listings with video will rank higher in search than those with only images.</div>
            <div class="error-container" id="media-input-error"></div><br>
            <input type="text" id="media-input"/>
            <div class="media-container" id="selected-media"></div>
            -->

            <input class="my-4 border p-4 bg-yellow hover:bg-navy hover:text-white" type="submit" value="Submit">
            <div id="result"></div>
        </form>
    </div>
    <div class="hidden lg:block md:col-span-1"></div>
    <div class="hidden lg:block md:col-span-5 sticky h-64 top-24">
        <h2 class="mt-8 font-bold text-24 md:text-36 lg:text-40">Preview</h2>
        <?php echo get_template_part('template-parts/search/standard-listing', '', [
            'name' => ($listing_data != null) ? $listing_data['post_meta']['name'][0] : 'Name',
            'location' => ($listing_data != null) ? $listing_data['post_meta']['city'][0] . ', ' . $listing_data['post_meta']['state'][0] : 'City, State',
            'description' => ($listing_data != null) ? $listing_data['post_meta']['description'][0] : 'Description',
            'genres' => $genres, // pass all genres; alpine_show_genre func will show the selected options
            'thumbnail_url' => $listing_data['thumbnail_url'],
            'alpine_thumbnail_src' => 'pThumbnailSrc',
            'verified' => $listing_data['post_meta']['verified'][0],
            'website' => $listing_data['post_meta']['website'][0],
            'facebook_url' => $listing_data['post_meta']['facebook_url'][0],
            'instagram_url' => $listing_data['post_meta']['instagram_url'][0],
            'x_url' => $listing_data['post_meta']['x_url'][0],
            'youtube_url' => $listing_data['post_meta']['youtube_url'][0],
            'tiktok_url' => $listing_data['post_meta']['tiktok_url'][0],
            'bandcamp_url' => $listing_data['post_meta']['bandcamp_url'][0],
            'spotify_artist_url' => $listing_data['post_meta']['spotify_artist_url'][0],
            'apple_music_artist_url' => $listing_data['post_meta']['apple_music_artist_url'][0],
            'soundcloud_url' => $listing_data['post_meta']['soundcloud_url'][0],
            'youtube_video_urls' => $listing_data['post_meta']['youtube_video_urls'][0],
            'youtube_video_ids' => $listing_data['post_meta']['youtube_video_ids'][0],
            'youtube_player_ids' => [], //array_map(fn($video_id, $video_index) => $video_id . '-' . $result_id . '-' . $video_index, $listing_data['post_meta']['youtube_video_ids'], array_keys($listing_data['post_meta']['youtube_video_ids'])),
            'lazyload_thumbnail' => false,
            'last' => false,
            'alpine_name' => 'pName',
            'alpine_location' => 'getListingLocation()',
            'alpine_description' => 'pDescription',
            'alpine_instagram_url' => 'pInstagramUrl',
            'alpine_tiktok_url' => 'pTiktokUrl',
            'alpine_x_url' => 'pXUrl',
            'alpine_website' => 'pWebsite',
            'alpine_facebook_url' => 'pFacebookUrl',
            'alpine_youtube_url' => 'pYoutubeUrl',
            'alpine_bandcamp_url' => 'pBandcampUrl',
            'alpine_spotify_artist_url' => 'pSpotifyArtistUrl',
            'alpine_apple_music_artist_url' => 'pAppleMusicArtistUrl',
            'alpine_soundcloud_url' => 'pSoundcloudUrl',
            'alpine_show_genre' => 'showGenre',
        ]); ?>
    </div>
</div>

<?php
get_footer();



/*
                    function get_spotify_artist_id_from_url($spotify_artist_url) {
                        if (preg_match('/\/artist\/([A-Za-z0-9]{22})/', $spotify_artist_url, $matches)) {
                            return $matches[1];
                        } else {
                            return '';
                        }
                    }

                    $url_path = wp_make_link_relative(get_permalink());
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $valid_nonce = wp_verify_nonce($_POST['lfs-nonce'], 'listing-form-submission');

                        $performer_name = sanitize_text_field($_POST['performer_name']);
                        $ensemble_size = (isset($_POST['ensemble_size'])) ? array_filter(array_map('sanitize_text_field', $_POST['ensemble_size'])) : array();
                        $venues_strings = (isset($_POST['venues'])) ? array_filter(array_map('sanitize_text_field', $_POST['venues'])) : array();
                        $verified_venues = (isset($_POST['verified-venues'])) ? array_filter(array_map('sanitize_text_field', explode(',', $_POST['verified-venues']))) : array();
                        $instagram_handle = preg_replace('/^@/', '', sanitize_text_field($_POST['instagram-handle']));
                        $instagram_url = !empty($instagram_handle) ? $instagram_url_prefix . $instagram_handle : '';
                        $tiktok_handle = preg_replace('/^@/', '', sanitize_text_field($_POST['tiktok-handle']));
                        $tiktok_url = !empty($tiktok_handle) ? $tiktok_url_prefix . $tiktok_handle : '';
                        $x_handle = preg_replace('/^@/', '', sanitize_text_field($_POST['x-handle']));
                        $x_url = !empty($x_handle) ? $x_url_prefix . $x_handle : '';
                        $spotify_artist_url = sanitize_url($_POST['spotify-artist-url']);
                        $spotify_artist_id = get_spotify_artist_id_from_url($spotify_artist_url);
                        $tag_terms = array_map(function ($item) { return $item->name; }, get_terms(array( 'taxonomy' => 'tag', 'hide_empty' => false)));
                        function is_tag_term($term) { global $tag_terms; return in_array($term, $tag_terms, true); }
                        function is_not_tag_term($term) { global $tag_terms; return !in_array($term, $tag_terms, true); }
                        $all_tags = (isset($_POST['tags'])) ? array_filter(array_map('sanitize_text_field', $_POST['tags'])) : array();
                        $unofficial_tags = array_filter($all_tags, 'is_not_tag_term');
                        $tags = array_filter($all_tags, 'is_tag_term');
                        $macro_genres = (isset($_POST['genres'])) ? array_filter(array_map('sanitize_text_field', $_POST['genres'])) : array();
                        $youtube_video_urls = (isset($_POST['media'])) ? array_filter(array_map('sanitize_url', $_POST['media'])) : array();

 */
