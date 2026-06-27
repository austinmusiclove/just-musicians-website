<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package JustMusicians
 */


?>
<!doctype html>
<html <?php language_attributes(); ?> >
<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--<link rel="apple-touch-icon" sizes="180x180" href="<?php //echo get_template_directory_uri(); ?>/lib/images/favicon/apple-touch-icon.png">-->
    <!--<link rel="icon" type="image/png" sizes="32x32" href="<?php //echo get_template_directory_uri(); ?>/lib/images/favicon/favicon-32x32.png">-->
    <!--<link rel="icon" type="image/png" sizes="16x16" href="<?php //echo get_template_directory_uri(); ?>/lib/images/favicon/favicon-16x16.png">-->
    <!--<link rel="manifest" href="<?php //echo get_template_directory_uri(); ?>/lib/images/favicon/site.webmanifest">-->
    <!--<link rel="mask-icon" href="<?php //echo get_template_directory_uri(); ?>/lib/images/favicon/safari-pinned-tab.svg" color="#989572">-->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">


    <!-- Poppins weights 400 and 700 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

	<?php wp_head(); ?>

</head>

<?php
// Get user location
$ip_loc = function_exists('hm_get_ip_location') ? hm_get_ip_location() : null;
$ip_lat   = $ip_loc->lat ?? null;
$ip_lng   = $ip_loc->lon ?? null;
$ip_label = $ip_loc ? "{$ip_loc->city}, {$ip_loc->region}" : '';

// Priority: SEO page args > URL query args > IP geolocation > hardcoded defaults
$header_arg_location_label = $args['header_arg_location_label'] ?? '';
$header_arg_lat            = $args['header_arg_lat'] ?? null;
$header_arg_lng            = $args['header_arg_lng'] ?? null;

$lat  = $header_arg_lat ?: (!empty($_GET['lat']) ? (float)$_GET['lat'] : ($ip_lat ?: 30.2672));
$lng  = $header_arg_lng ?: (!empty($_GET['lng']) ? (float)$_GET['lng'] : ($ip_lng ?: -97.7431));
$location_label = $header_arg_location_label ?: (!empty($_GET['location_label']) ? $_GET['location_label'] : ($ip_label ?: 'Austin, Texas'));
?>

    <body <?php body_class('flex flex-col min-h-screen relative'); ?>
        x-data="{
            width: 0,
            redirect(target) { if (target) { window.location.href=target; } else { window.location.href='<?php echo $_SERVER['REQUEST_URI']; ?>';} },
            loggedIn: <?php if (is_user_logged_in()) { echo 'true'; } else { echo 'false'; } ?>,
            accountSettings: <?php if (is_user_logged_in()) { echo clean_arr_for_doublequotes(get_account_settings()); } else { echo '[]'; } ?>,
            notifications: {},
            shakeElements: new Set(),
            _emphasizeElm(elm, elmId) { emphasizeElm(this, elm, elmId); },
            showPassword: false,
            showFavModal: false,
            showLoginModal: false,
            showSignupModal: false,
            loginModalMessage: 'Sign in to your account',
            signupModalMessage: 'Sign up for an account',
            showPasswordResetModal: false,
            searchLat: <?php echo $lat; ?>,
            searchLng: <?php echo $lng; ?>,
            searchLocation: '<?php echo clean_str_for_doublequotes($location_label); ?>',
            reviewProgress: 0,
            currentReviewSlide: '',
            showReviewModal: false,
            showReviewSlide: true,
            showReviewUserInfoSlide: false,
            showReviewThankYouSlide: false,
            showReviewErrorSlide: false,
            reviewPostType: '',
            revieweeId: '',
            revieweeName: '',
            reviewErrorMsg: '',
            inquiryProgress: 0,
            showInquiryModal: false,
            showSendMessageModal: false,
            showSendMessageSuccess: false,
            sendMessageListingName: '',
            sendMessageListingId: 0,
            sendMessageText: '',
            currentInquirySlide: '',
            showRequestSlide: false,
            showDateSlide: true,
            showLocationSlide: false,
            showBudgetSlide: false,
            showGenreSlide: false,
            showPerformersSlide: false,
            showDetailsSlide: false,
            showQuoteSlide: false,
            showDiscardSlide: false,
            showThankYouSlide: false,
            showErrorSlide: false,
            inquiryListing: '',
            inquiryListingName: '',
            inquiryStartDate: '',
            inquiryCity: '',
            inquiryState: '',
            inquiryZipCode: '',
            inquiryLat: '',
            inquiryLng: '',
            inquiryLocation: '',
            inquiryGenres: [],
            inquiryEventName: '',
            inquiryBudgetType: 'Request Quotes',
            inquiryBudget: '',
            inquiryCompensation: '',
            inquiryErrorMsg: '',
            newEventPermalink: '',
            _clearInquiryForm()                                  { clearInquiryForm(this); },
            _showInquirySlide(slide)                             { showInquirySlide(this, slide); },
            _openInquiryModal(listingId, listingName)            { openInquiryModal(this, listingId, listingName); },
            _openReviewModal(reviewType, revieweeId)             { openReviewModal(this, reviewType, revieweeId); },
            _tryExitInquiryModal()                               { tryExitInquiryModal(this); },
            _exitInquiryModal()                                  { exitInquiryModal(this); },
            _submitInquiry()                                     { submitInquiry(this); },
            _handleCreateInquirySuccess(inquiryId)               { handleCreateInquirySuccess(this, inquiryId); },
            _handleCreateInquiryError(message)                   { handleCreateInquiryError(this, message); },
            _handleCreateReviewSuccess()                         { handleCreateReviewSuccess(this); },
            _handleCreateReviewError(message)                    { handleCreateReviewError(this, message); },
            _handleUpdateAccountSettingsSuccess()                { handleUpdateAccountSettingsSuccess(this); },
            _handleUpdateAccountSettingsError(message)           { handleUpdateAccountSettingsError(this, message); },
            showSearchOptions: false,
            showLocationSearchOptions: false,
            showInquiryLocationSearchOptions: false,
            showLocationSearchOptionsHeader: false,
            getShowDefaultSearchOptionsDesktop() { return this.showSearchOptions && this.width >= 768 },
            getShowDefaultSearchOptionsMobile()  { return this.showSearchOptions && this.width <  768 },
            showMobileMenu: false,
            showMobileMenuDropdown1: false,
            showMobileMenuDropdown2: false,
            showMobileFilters: false,
            searchInput: '<?php if (!empty($_GET['qsearch'])) { echo $_GET['qsearch']; } ?>',
            locationInput: '<?php echo clean_str_for_doublequotes($location_label); ?>',
            locationInputHeader: '<?php echo clean_str_for_doublequotes($location_label); ?>',
            inquiryLocationInput: '',
            updateLocation(location) { this.locationInput = location.label; this.locationInputHeader = location.label; this.searchLocation = location.label; this.searchLat = location.lat; this.searchLng = location.lng; },
            _updateInquiryLocation(location) { updateInquiryLocation(this, location); },
            focusElm(id) {
                var elm = document.getElementById(id);
                if (elm) { elm.scrollIntoView({ behavior: 'smooth', block: 'center' }); elm.focus(); }
            }
        }"
        x-init="(async () => {
            width = window.innerWidth;
            document.body.addEventListener('htmx:responseError', (event) => { if (event.detail.xhr.status === 404) { $dispatch('error-toast', {'message': 'HTMX Error: 404'}); } });
            document.addEventListener('DOMContentLoaded', async function() { if (loggedIn) { notifications = await get_user_notifications(); } });
        })"
        x-resize.document="
            width = $width;
            showMobileMenu = false;
            showSearchOptions = false;
            showLocationSearchOptions = false;
            showInquiryLocationSearchOptions = false;
            showLocationSearchOptionsHeader = false;
        "
        x-on:focus-elm="focusElm($event.detail.id)"
        x-on:updateimageid="accountSettings.profile_image.attachment_id = $event.detail"
    >
    <!-- Setting a fixed height allows us to position the popups on mobile -->
    <!-- if height specifications here change from h-28 md:h-16 then the height calculations in page-messages.php have to be modified -->
    <header class="bg-brown-light-3 sticky top-0 z-50 h-28 md:h-16">
        <?php
        echo get_template_part('template-parts/global/header-bar', '', []);
        echo get_template_part('template-parts/global/toasts/success-toast', '', []);
        echo get_template_part('template-parts/global/toasts/error-toast',   '', []);
        ?>
    </header>


    <?php wp_body_open(); ?>
    <?php
        echo get_template_part('template-parts/menus/mobile-menu', '', []);
        echo get_template_part('template-parts/login/login-modal', '', []);
        echo get_template_part('template-parts/login/signup-modal', '', []);
        echo get_template_part('template-parts/login/password-reset-modal', '', []);
        echo get_template_part('template-parts/inquiries/inquiry-popup', '', []);
        echo get_template_part('template-parts/reviews/popup/review-popup', '', []);
        echo get_template_part('template-parts/messages/send-message-modal', '', []);
    ?>
