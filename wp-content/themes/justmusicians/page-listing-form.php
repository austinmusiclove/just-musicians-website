<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

get_header();

?>
<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden <?php echo $header_padding; ?>">
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
    </div>
</header>

<div id="content-wrap" class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="single-page-wrapper">
				<?php
                    function get_listing_content($listing_post) {
                        return implode(" ", array(
                            $listing_post['meta_input']['type'],
                            $listing_post['meta_input']['name'],
                            $listing_post['meta_input']['description'],
                            $listing_post['meta_input']['city'],
                            $listing_post['meta_input']['state'],
                            $listing_post['meta_input']['zip_code'],
                            $listing_post['meta_input']['bio'],
                            implode(', ', $listing_post['meta_input']['venues_played_unverified_strings']),
                            $listing_post['meta_input']['email'],
                            $listing_post['meta_input']['phone'],
                            $listing_post['meta_input']['website'],
                            $listing_post['meta_input']['instagram_handle'],
                            $listing_post['meta_input']['tiktok_handle'],
                            $listing_post['meta_input']['x_handle'],
                            $listing_post['meta_input']['facebook_url'],
                            $listing_post['meta_input']['youtube_url'],
                            $listing_post['meta_input']['bandcamp_url'],
                            $listing_post['meta_input']['spotify_artist_url'],
                            $listing_post['meta_input']['apple_music_artist_url'],
                            $listing_post['meta_input']['soundcloud_url'],
                            implode(', ', $listing_post['meta_input']['youtube_video_urls']),
                            implode(', ', $listing_post['meta_input']['unofficial_tags']),
                            implode(', ', $listing_post['tax_input']['tag']),
                            implode(', ', $listing_post['tax_input']['genre']),
                        ));
                    }
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

                        $performer_name = sanitize_text_field($_POST['performer-name']);
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

                        $listing_post = array(
                            'post_title'   => $performer_name,
                            'post_status'  => 'pending',
                            'post_type'    => 'listing',
                            'meta_input'   => array(
                                'type' => sanitize_text_field($_POST['listing-type']),
                                'name' => $performer_name,
                                'artist_uuid' => sanitize_text_field($_POST['artist-uuid']),
                                'artist_post' => sanitize_text_field($_POST['artist-post-id']),
                                'description' => sanitize_text_field($_POST['description']),
                                'city' => sanitize_text_field($_POST['city']),
                                'state' => sanitize_text_field($_POST['state']),
                                'zip_code' => sanitize_text_field($_POST['zip-code']),
                                'bio' => sanitize_text_field($_POST['bio']),
                                'ensemble_size' => $ensemble_size,
                                'venues_played_verified' => $verified_venues,
                                'venues_played_unverified_strings' => $venues_strings,
                                //'draw' => sanitize_text_field($_POST['draw']),
                                'email' => sanitize_text_field($_POST['listing-email']),
                                'phone' => sanitize_text_field($_POST['phone']),
                                'website' => sanitize_url($_POST['website']),
                                'instagram_handle' => $instagram_handle,
                                'instagram_url' => $instagram_url,
                                // instagram is private
                                'tiktok_handle' => $tiktok_handle,
                                'tiktok_url' => $tiktok_url,
                                'x_handle' => $x_handle,
                                'x_url' => $x_url,
                                'facebook_url' => sanitize_url($_POST['facebook-url']),
                                'youtube_url' => sanitize_url($_POST['youtube-url']),
                                'bandcamp_url' => sanitize_url($_POST['bandcamp-url']),
                                'spotify_artist_url' => $spotify_artist_url,
                                'spotify_artist_id' => $spotify_artist_id,
                                'apple_music_artist_url' => sanitize_url($_POST['apple-music-url']),
                                'soundcloud_url' => sanitize_url($_POST['soundcloud-url']),
                                'youtube_video_urls' => $youtube_video_urls,
                                'unofficial_tags' => $unofficial_tags,
                            ),
                            'tax_input' => array(
                                'tag' => $tags,
                                'genre' => $macro_genres,
                            ),
                        );
                        $listing_post['post_content'] = get_listing_content($listing_post); // Set content to contain any information that the post should be searchable by

                        $post_id = wp_insert_post($listing_post);
                        if (is_wp_error($post_id)) {
                            echo '<h2>There was an error saving your submission. Please try again or contact the adminstrator at john@justmusicians.com.</h2>';
                        } else {
                            // Set taxonomy
                            wp_set_post_terms($post_id, $macro_genres, 'genre');
                            wp_set_post_terms($post_id, $tags, 'tag');

                            // Add featured image and don't show error if thumbnail fails
                            $thumbnail_upload = wp_handle_upload($_FILES['cropped-thumbnail'], ['test_form' => false]);
                            if (isset($thumbnail_upload['file'])) {
                                // Set attachment data
                                $attachment = array(
                                    'post_mime_type' => $thumbnail_upload['type'],
                                    'post_title'     => sanitize_file_name( $thumbnail_upload['file'] ),
                                    'post_content'   => '',
                                    'post_status'    => 'inherit'
                                );

                                // Create the attachment
                                $attachment_id = wp_insert_attachment( $attachment, $thumbnail_upload['file'], $post_id );
                                if( !is_wp_error( $attachment_id ) ) {
                                    set_post_thumbnail($post_id, $attachment_id);
                                }
                            }
                            echo '<h2>Thank you for your submission!</h2>';
                            echo '<p>Your listing has been submitted successfully.</p>';
                        }
                    } else {
                        // Display the form
                        ?>
                        <h1>Live Musician Listing Submission Form</h1>
                        <p>Just Musicians is accepting submissions to be listed in the Just Musicians directory, a free to use live music booking platform. Talent buyers will be able to find you and reach out to you directly through your available contact methods. No transaction fees, no pay per lead, no middle man, just musicians.</p>
                        <p>The mission of the platform is to make live music booking easier for both talent and talent buyers, break down barriers to entry for new artists, and to foster fairness in the live music industry. After submitting this form, your listing will be reviewed. Approved listings will be included in the launch of the directory.</p>
                        <form class="custom-form" method="post" action="" enctype="multipart/form-data">
                            <?php wp_nonce_field('listing-form-submission', 'lfs-nonce'); ?>
                            <!------------ Gig details ----------------->
                            <hr>
                            <h2>Listing Details</h2>
                            <input type="hidden" id="artist-uuid" name="artist-uuid" value="<?php echo $_GET['aid']; ?>">
                            <input type="hidden" id="artist-post-id" name="artist-post-id">
                            <input type="hidden" id="verified-venues" name="verified-venues">
                            <div class="form-separator"></div>

                            <!-- Listing type -->
                            <label for="listing-type">Listing Type. Chose the option that best describes your act. If you do multiple things, you may submit multiple listings.</label><br>
                            <input class="button-input" value="Musician" type="radio" name="listing-type" id="musician"/>
                            <label class="button-label" for="musician">Musician</label>
                            <input class="button-input" value="Band" type="radio" name="listing-type" id="band"/>
                            <label class="button-label" for="band">Band</label>
                            <input class="button-input" value="DJ" type="radio" name="listing-type" id="dj"/>
                            <label class="button-label" for="dj">DJ</label>
                            <input class="button-input" value="Artist" type="radio" name="listing-type" id="artist"/>
                            <label class="button-label" for="artist">Artist</label>
                            <div class="form-separator"></div>

                            <div class="form-group">
                                <!-- Performer Name -->
                                <div><label for="performer-name">Performer or Band Name</label><br>
                                <input required type="text" id="performer-name" name="performer-name" autocomplete="off" ></div>

                                <!-- Description -->
                                <div><label for="description">30 Character Description. This will appear just below your name in your listing. </label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">Examples: Psych rock band, Cello player, 90s cover band</span>
                                </span><br>
                                <input required type="text" id="description" name="description" maxlength="30" placeholder="5-piece Country Band"></div>
                            </div>
                            <div class="form-separator"></div>

                            <div class="form-group">
                                <!-- City -->
                                <div><label for="city">City</label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">This would be where you consider yourself to be "based out of".</span>
                                </span><br>
                                <input required type="text" id="city" name="city" ></div>

                                <!-- State -->
                                <div><label for="state">State</label><br>
                                <input required type="text" id="state" name="state"></div>

                                <!-- Zip Code -->
                                <div><label for="zip-code">Zip Code</label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">This will be used to help match buyers with musicians who are broadly geographically near by.</span>
                                </span><br>
                                <input required type="text" id="zip-code" name="zip-code" pattern="^\d{5}(-\d{4})?$" title="Enter a valid ZIP code (e.g., 12345 or 12345-6789)."></div>
                            </div>
                            <div class="form-separator"></div>

                            <!-- Bio -->
                            <label for="bio">Bio</label><br>
                            <textarea id="bio" name="bio"></textarea><br><br>
                            <div class="form-separator"></div>

                            <!-- Macro Genres (genre taxonomy) -->
                            <label for="genres">Genres. Check all that apply.</label><br>
                            <input class="button-input" value="avant-garde" type="checkbox" name="genres[]" id="avant-garde"/>
                            <label class="button-label" for="avant-garde">Avant-Garde</label>
                            <input class="button-input" value="blues" type="checkbox" name="genres[]" id="blues"/>
                            <label class="button-label" for="blues">Blues</label>
                            <input class="button-input" value="christian-gospel" type="checkbox" name="genres[]" id="christian-gospel"/>
                            <label class="button-label" for="christian-gospel">Christian/Gospel</label>
                            <input class="button-input" value="classical" type="checkbox" name="genres[]" id="classical"/>
                            <label class="button-label" for="classical">Classical</label>
                            <input class="button-input" value="country" type="checkbox" name="genres[]" id="country"/>
                            <label class="button-label" for="country">Country</label>
                            <input class="button-input" value="electronic" type="checkbox" name="genres[]" id="electronic"/>
                            <label class="button-label" for="electronic">Electronic</label>
                            <input class="button-input" value="folk" type="checkbox" name="genres[]" id="folk"/>
                            <label class="button-label" for="folk">Folk</label>
                            <input class="button-input" value="funk" type="checkbox" name="genres[]" id="funk"/>
                            <label class="button-label" for="funk">Funk</label>
                            <input class="button-input" value="hip-hop-rap" type="checkbox" name="genres[]" id="hip-hop-rap"/>
                            <label class="button-label" for="hip-hop-rap">Hip Hop/Rap</label>
                            <input class="button-input" value="jazz" type="checkbox" name="genres[]" id="jazz"/>
                            <label class="button-label" for="jazz">Jazz</label>
                            <input class="button-input" value="latin" type="checkbox" name="genres[]" id="latin"/>
                            <label class="button-label" for="latin">Latin</label>
                            <input class="button-input" value="metal" type="checkbox" name="genres[]" id="metal"/>
                            <label class="button-label" for="metal">Metal</label>
                            <input class="button-input" value="pop" type="checkbox" name="genres[]" id="pop"/>
                            <label class="button-label" for="pop">Pop</label>
                            <input class="button-input" value="reggae" type="checkbox" name="genres[]" id="reggae"/>
                            <label class="button-label" for="reggae">Reggae</label>
                            <input class="button-input" value="rock" type="checkbox" name="genres[]" id="rock"/>
                            <label class="button-label" for="rock">Rock</label>
                            <input class="button-input" value="soul-rnb" type="checkbox" name="genres[]" id="soul-rnb"/>
                            <label class="button-label" for="soul-rnb">Soul/RnB</label>
                            <div class="form-separator"></div>

                            <!-- Ensemble Size -->
                            <label for="ensemble-size">How many performers in your group? If you perform with different ensemble sizes, check all that apply.</label><br>
                            <input class="button-input" value="1" type="checkbox" name="ensemble_size[]" id="ensemble-size-1"/>
                            <label class="button-label" for="ensemble-size-1">1</label>
                            <input class="button-input" value="2" type="checkbox" name="ensemble_size[]" id="ensemble-size-2"/>
                            <label class="button-label" for="ensemble-size-2">2</label>
                            <input class="button-input" value="3" type="checkbox" name="ensemble_size[]" id="ensemble-size-3"/>
                            <label class="button-label" for="ensemble-size-3">3</label>
                            <input class="button-input" value="4" type="checkbox" name="ensemble_size[]" id="ensemble-size-4"/>
                            <label class="button-label" for="ensemble-size-4">4</label>
                            <input class="button-input" value="5" type="checkbox" name="ensemble_size[]" id="ensemble-size-5"/>
                            <label class="button-label" for="ensemble-size-5">5</label>
                            <input class="button-input" value="6" type="checkbox" name="ensemble_size[]" id="ensemble-size-6"/>
                            <label class="button-label" for="ensemble-size-6">6</label>
                            <input class="button-input" value="7" type="checkbox" name="ensemble_size[]" id="ensemble-size-7"/>
                            <label class="button-label" for="ensemble-size-7">7</label>
                            <input class="button-input" value="8" type="checkbox" name="ensemble_size[]" id="ensemble-size-8"/>
                            <label class="button-label" for="ensemble-size-8">8</label>
                            <input class="button-input" value="9" type="checkbox" name="ensemble_size[]" id="ensemble-size-9"/>
                            <label class="button-label" for="ensemble-size-9">9</label>
                            <input class="button-input" value="10+" type="checkbox" name="ensemble_size[]" id="ensemble-size-10"/>
                            <label class="button-label" for="ensemble-size-10">10+</label>
                            <div class="form-separator"></div>

                            <!-- Other genres and descriptors (tag taxonomy) -->
                            <label for="tags">Keywords</label>
                            <span class="tooltip">
                                i<span class="tooltip-text">Type a keyword and press enter to add it</span>
                            </span><br>
                            <div>Enter any key words that you would want to appear in search results for. i.e. wedding band, cover band etc.</div>
                            <div class="error-container" id="tag-input-error"></div><br>
                            <div id="selected-tags"></div>
                            <input type="text" id="tags-input"/>
                            <div style="display:none;" class="dropdown" id="tag-options"></div>
                            <div class="form-separator"></div>

                            <!-- Venues Played -->
                            <label for="venues">Venues</label>
                            <span class="tooltip">
                                i<span class="tooltip-text">Type a venue name and press enter to add it</span>
                            </span><br>
                            <div>Enter any venues you have played that you would like to be listed on your profile</div>
                            <div class="error-container" id="venue-input-error"></div><br>
                            <div id="selected-venues"></div>
                            <input type="text" id="venues-input"/>
                            <div style="display:none;" class="dropdown" id="venue-options"></div>
                            <div class="form-separator"></div>

                            <!-- Draw -->
                            <!--
                            <label for="draw"></label><br>
                            <textarea id="draw" name="draw"></textarea><br><br>
                            <div class="form-separator"></div>
                            -->

                            <!------------ Contact and Links ----------------->
                            <hr>
                            <h2>Contact and Links</h2>
                            <div class="form-group">
                                <!-- Email -->
                                <div><label for="listing-email">Email</label><br>
                                <input type="email" id="listing-email" name="listing-email" pattern="[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$" title="example@example.com" placeholder="example@example.com"></div>
                                <!-- Phone -->
                                <div><label for="phone">Phone</label><br>
                                <input type="tel" id="phone" name="phone" placeholder="(555) 555-5555" maxlength="14" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" title="Format: (555) 555-5555"></div>
                                <!-- Website -->
                                <div><label for="website">Website</label><br>
                                <input type="url" id="website" name="website" placeholder="https://" ></div>
                            </div>
                            <div class="form-group">
                                <!-- Instagram -->
                                <div><label for="instagram-handle">Instagram Handle</label><br>
                                <input type="text" id="instagram-handle" name="instagram-handle" pattern="^[A-Za-zA-Z0-9_\.]{1,30}$" title="Instagram handle must be 1-30 characters long and can only include letters, numbers, underscores, or periods. No @ sign.">
                                <input type="hidden" id="instagram-url" name="instagram-url"></div>
                                <!-- Tiktok -->
                                <div><label for="tiktok-handle">Tiktok Handle</label><br>
                                <input type="text" id="tiktok-handle" name="tiktok-handle" pattern="^[a-zA-Z0-9_.]+$" title="Handle can only contain letters, numbers, underscores, and periods. No @ sign.">
                                <input type="hidden" id="tiktok-url" name="tiktok-url"></div>
                                <!-- X -->
                                <div><label for="x-handle">X Handle</label><br>
                                <input type="text" id="x-handle" name="x-handle">
                                <input type="hidden" id="x-url" name="x-url"></div>
                            </div>
                            <div class="form-group">
                                <!-- Facebook -->
                                <div><label for="facebook-url">Facebook URL</label><br>
                                <input type="url" id="facebook-url" name="facebook-url"></div>
                                <!-- Youtube -->
                                <div><label for="youtube-url">Youtube Channel URL</label><br>
                                <input type="url" id="youtube-url" name="youtube-url"></div>
                                <!-- Bandcamp -->
                                <div><label for="bandcamp-url">Bandcamp URL</label><br>
                                <input type="url" id="bandcamp-url" name="bandcamp-url"></div>
                            </div>
                            <div class="form-group">
                                <!-- Spotify -->
                                <div><label for="spotify-artist-url">Spotify Artist URL</label><br>
                                <input type="url" id="spotify-artist-url" name="spotify-artist-url">
                                <input type="hidden" id="spotify-artist-id" name="spotify-artist-id"></div>
                                <!-- Apple Music -->
                                <div><label for="apple-music-url">Apple Music Artist URL</label><br>
                                <input type="url" id="apple-music-url" name="apple-music-url"></div>
                                <!-- Soundcloud -->
                                <div><label for="soundcloud-url">Soundcloud URL</label><br>
                                <input type="url" id="soundcloud-url" name="soundcloud-url"></div>
                            </div>
                            <div class="form-separator"></div>

                            <!------------ Media ----------------->
                            <hr>
                            <h2>Media</h2>
                            <p></p>
                            <!-- Thumbnail -->
                            <label for="thumbnail">Thumbnail</label><br>
                            <div>This is your main thumbnail image. It's the first thing people will see as they are scrolling listings.</div><br>
                            <input required id="thumbnail" name="thumbnail" type="file" accept="image/*">
                            <input id="cropped-thumbnail" name="cropped-thumbnail" type="file" style="display:none" accept="image/*">
                            <div style="max-width: 500px; margin: 20px 0;">
                                <img id="thumbnail-display" style="display: none; max-width: 100%;" />
                            </div>
                            <div class="form-separator"></div>

                            <!-- Youtube links -->
                            <label for="media">Youtube Video Links</label><br>
                            <div>This is your chance to show your stuff to talent buyers. Paste a Youtube video link into the box. Add as many as you wish. Listings with video will rank higher in search than those with only images.</div>
                            <div class="error-container" id="media-input-error"></div><br>
                            <input type="text" id="media-input"/>
                            <div class="media-container" id="selected-media"></div>
                            <div class="form-separator"></div>

                            <hr>
                            <input type="submit" value="Submit">
                        </form>
                        <?php
                    }
				?>
			</div><!-- .single-page-wrapper  -->
		</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- .container -->

<?php
get_footer();
