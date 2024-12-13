import axios from 'axios';

class ListingFormManager {
    constructor(helper, tagInputFactory, textInputOptionDropdownFactory, phoneInputFactory, croppableImageInputFactory) {
        this.helper = helper
        this.tagInputFactory = tagInputFactory;
        this.textInputOptionDropdownFactory = textInputOptionDropdownFactory;
        this.phoneInputFactory = phoneInputFactory;
        this.croppableImageInputFactory = croppableImageInputFactory;
        document.addEventListener('DOMContentLoaded', () => {
            this._setupElements();
            this._setupEventListeners();
            this._setupComponents();
            this.preFill();
        });
    }
    _setupElements() {
        this.artistIdInput = document.getElementById('artist-uuid');
        this.phoneInputElement = document.getElementById('phone');
        this.venueTextInput = document.getElementById('venues-input');
        this.venueOptions = document.getElementById('venue-options');
        this.venueErrorContainer = document.getElementById('venue-input-error');
        this.selectedVenuesContainer = document.getElementById('selected-venues');
        this.tagTextInput = document.getElementById('tags-input');
        this.tagOptions = document.getElementById('tag-options');
        this.selectedTagsContainer = document.getElementById('selected-tags');
        this.tagErrorContainer = document.getElementById('tag-input-error');
        this.mediaTextInput = document.getElementById('media-input');
        this.selectedMediaContainer = document.getElementById('selected-media');
        this.thumbnailInput = document.getElementById('thumbnail');
        this.croppedThumbnailInput = document.getElementById('cropped-thumbnail');
        this.thumbnailDisplay = document.getElementById('thumbnail-display');
        this.mediaErrorContainer = document.getElementById('media-input-error');
    }
    _setupEventListeners() {
        // interrupt submit to check urls are not 404s
    }
    _setupComponents() {
        this.venueTagsInput = this.tagInputFactory.create('venues', this.venueTextInput, this.selectedVenuesContainer, 'tag-item', 'tag-delete-button', this.venueErrorContainer, 'error-message');
        function getVenues() { return axios.get(`${siteData.venues_api_url}/?min_review_count=0`); }
        function addVenueTag(venueId, venueName) { this.venueTagsInput.addTag(venueName); }
        this.venueInputOptionDropdown = this.textInputOptionDropdownFactory.create(this.venueTextInput, this.venueOptions, getVenues, 'ID', 'name', addVenueTag.bind(this));
        this.tagsInput = this.tagInputFactory.create('tags', this.tagTextInput, this.selectedTagsContainer, 'tag-item', 'tag-delete-button', this.tagErrorContainer, 'error-message');
        function getTags() { return axios.get(`${siteData.get_taxonomy_terms_api_url}/?taxonomy=tag`); }
        function addTag(termId, termName) { this.tagsInput.addTag(termName); }
        this.tagInputOptionDropdown = this.textInputOptionDropdownFactory.create(this.tagTextInput, this.tagOptions, getTags, 'term_id', 'name', addTag.bind(this));
        this.phoneInput = this.phoneInputFactory.create(this.phoneInputElement);
        this.croppableImageInput = this.croppableImageInputFactory.create(this.thumbnailInput, this.croppedThumbnailInput, this.thumbnailDisplay);
        this.mediaTagsInput = this.tagInputFactory.create('media', this.mediaTextInput, this.selectedMediaContainer, 'media-tag-item', 'media-tag-delete-button', this.mediaErrorContainer, 'error-message', 'youtube');
    }

    preFill() {
        let artistUUID = this.artistIdInput.value;
        if (artistUUID) {
            this.getArtist(artistUUID).then( (response) => {
                return response.data;
            }).then((data) => {
                if (data) {
                    document.getElementById('artist-post-id').value = data.ID;
                    document.getElementById('performer-name').value = data.name;
                    document.getElementById('description').value = data.claimed_genre;
                    document.getElementById('city').value = data.city;
                    document.getElementById('state').value = data.state;
                    document.getElementById('bio').value = data.bio;
                    document.getElementById('listing-email').value = data.email;
                    document.getElementById('phone').value = data.phone;
                    document.getElementById('website').value = data.website;
                    document.getElementById('instagram-handle').value = data.instagram_handle;
                    document.getElementById('tiktok-handle').value = data.tiktok_handle;
                    document.getElementById('x-handle').value = data.x_handle;
                    document.getElementById('facebook-url').value = data.facebook_url;
                    document.getElementById('youtube-url').value = data.youtube_url;
                    document.getElementById('bandcamp-url').value = data.bandcamp_url;
                    document.getElementById('spotify-artist-url').value = `https://open.spotify.com/artist/${data.spotify_artist_id}`;
                    document.getElementById('spotify-artist-id').value = data.spotify_artist_id;
                    document.getElementById('soundcloud-url').value = data.soundcloud_url;
                    // listing type
                    let listingTypeOption = document.getElementById(data.type.toLowerCase());
                    if (listingTypeOption) { listingTypeOption.checked = true; }
                    // genres
                    for (let iterator = 0; iterator < data.macro_genres.length; iterator++) {
                        let genre = data.macro_genres[iterator];
                        let genreOption = document.getElementById(this.helper.convertStringToSlug(genre));
                        if (genreOption) { genreOption.checked = true; }
                    }
                    // ensemble size
                    for (let iterator = 0; iterator < data.ensemble_size.length; iterator++) {
                        let size = data.ensemble_size[iterator];
                        let sizeOption = document.getElementById(`ensemble-size-${this.helper.convertStringToSlug(size)}`);
                        if (sizeOption) { sizeOption.checked = true; }
                    }
                    // verified venues
                    document.getElementById('verified-venues').value = data.venues_played_verified; // set hidden venues played verified input
                    return this.getVenuesBatch(data.venues_played_verified);
                }
            }).then((response) => {
                // display verified venues in form
                let venues = response.data;
                for (let iterator = 0; iterator < venues.length; iterator++) {
                    let venue = venues[iterator];
                    this.venueTagsInput.addTag(venue.name);
                }
            }).catch( (err) => {
                console.warn(err);
            });
        }
    }
    getArtist(artistUUID) {
        return axios.get(`${siteData.artist_api_url}/?artist_uuid=${artistUUID}`);
    }
    getVenuesBatch(venueIds) {
        return axios.get(`${siteData.venues_by_post_id_batch_api_url}/?venue_ids=${venueIds.join(",")}`);
    }
}

export default ListingFormManager
