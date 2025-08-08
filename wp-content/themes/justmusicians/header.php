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

    <body <?php body_class('flex flex-col min-h-screen relative'); ?>
        x-data="{
            width: 0,
            redirect(target) { if (target) { window.location.href=target; } else { window.location.href='<?php echo $_SERVER['REQUEST_URI']; ?>';} },
            loggedIn: <?php if (is_user_logged_in()) { echo 'true'; } else { echo 'false'; } ?>,
            shakeElements: new Set(),
            _emphasizeElm(elm, elmId) { emphasizeElm(this, elm, elmId); },
            showPassword: false,
            showFavModal: false,
            showLoginModal: false,
            showSignupModal: false,
            loginModalMessage: 'Sign in to your account',
            signupModalMessage: 'Sign up for an account',
            showPasswordResetModal: false,
            inquiryProgress: 0,
            showInquiryModal: false,
            currentInquirySlide: '',
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
            inquiryDateType: 'TBD',
            inquiryZipCode: '',
            inquiryGenres: [],
            inquirySubject: '',
            inquiryBudgetType: 'Request Quotes',
            quotesRequested: '<?php echo DEFAULT_QUOTES_REQUESTED; ?>',
            inquiryErrorMsg: '',
            newInquiryId: '',
            _clearInquiryForm()                                  { clearInquiryForm(this); },
            _showInquirySlide(slide)                             { showInquirySlide(this, slide); },
            _openInquiryModal(listingId, listingName)            { openInquiryModal(this, listingId, listingName); },
            _tryExitInquiryModal()                               { tryExitInquiryModal(this); },
            _exitInquiryModal()                                  { exitInquiryModal(this); },
            _submitInquiry()                                     { submitInquiry(this); },
            _handleCreateInquirySuccess(inquiryId)               { handleCreateInquirySuccess(this, inquiryId); },
            _handleCreateInquiryError(message)                   { handleCreateInquiryError(this, message); },
            showSearchOptions: false,
            getShowDefaultSearchOptionsDesktop() { return this.showSearchOptions && this.width >= 768 },
            getShowDefaultSearchOptionsMobile()  { return this.showSearchOptions && this.width <  768 },
            showMobileMenu: false,
            showMobileMenuDropdown1: false,
            showMobileMenuDropdown2: false,
            showMobileFilters: false,
            searchInput: '<?php if (!empty($_GET['qsearch'])) { echo $_GET['qsearch']; } ?>',
            focusElm(id) {
                var elm = document.getElementById(id);
                if (elm) { elm.scrollIntoView({ behavior: 'smooth', block: 'center' }); elm.focus(); }
            }
        }"
        x-init="width = window.innerWidth; document.body.addEventListener('htmx:responseError', (event) => { if (event.detail.xhr.status === 404) { $dispatch('error-toast', {'message': 'HTMX Error: 404'}); } })"
        x-resize.document="
            width = $width;
            showMobileMenu = false;
            showSearchOptions = false;
        "
        x-on:focus-elm="focusElm($event.detail.id)"
    >
      <!-- Setting a fixed height allows us to position the popups on mobile -->
    <header class="bg-brown-light-3 sticky top-0 z-50 h-28 md:h-16">
      <div class="container flex flex-row md:grid grid-cols-12 gap-2 md:gap-4 lg:gap-12 md:px-6 my-2">

        <div class="w-32 md:w-auto col-span-2 relative">
          <a class="w-full absolute top-0 left-0 z-10" href="<?php echo get_home_url(); ?>">
            <img src="<?php echo get_template_directory_uri() . '/lib/images/logos/hmm-logo.svg'; ?>" />
          </a>
        </div>

        <div class="col-span-10 flex flex-col-reverse max-md:grow md:flex-row md:items-center items-end gap-2 md:gap-6 lg:gap-12 justify-between">

          <div class="border bg-white text-14 pr-1 rounded-sm border-black/20 grow w-full flex items-stretch">
            <div data-search="desktop" class="grow relative px-1 py-1" x-on:click.outside="showSearchOptions = false" >
              <input class="w-full h-full py-2 px-3" type="text" name="s" autocomplete="off" placeholder="Search"
                x-on:focus="showSearchOptions = true; showMobileMenu = false; showMobileMenuDropdown1 = false; showMobileMenuDropdown2 = false; showMobileFilters = false; $dispatch('updatesearchoptions');"
                x-on:keyup.enter="location.href = '/?qsearch=' + encodeURIComponent($el.value)"
                x-ref="desktopSearchInput"
                x-bind:value="searchInput"
                hx-get="<?php echo site_url('/wp-html/v1/search-options'); ?>"
                hx-trigger="input changed delay:300ms, updatesearchoptions"
                hx-target="#active-search-results-desktop"
              />
              <div id="active-search-results-desktop" x-show="getShowDefaultSearchOptionsDesktop()" x-cloak>
                <?php echo get_template_part('template-parts/search/search-state-1', '', array()); ?>
              </div>
              <?php echo get_template_part('template-parts/search/mobile-search', '', array()); ?>
            </div>
            <div class="hidden md:block w-px bg-black/20 my-2"></div>
            <div class="hidden md:block grow relative px-1 py-1 flex items-center">
              <img class="h-4 absolute top-3 left-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
              <input class="w-full h-full py-2 pr-3 pl-5" type="text" placeholder="Austin, Texas" disabled />
            </div>
            <button type="button" class="flex cursor-pointer items-center px-2 py-2 hover:scale-105" x-on:click="location.href = '/?qsearch=' + $refs.desktopSearchInput.value">
              <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
            </button>
          </div>

          <div class="font-sun-motter text-18 items-center gap-6 hidden lg:flex shrink-0">
            <span class="flex items-center gap-2 relative group">
              <a href="#">Live Music</a>
              <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
              <!-- Dropdown menu -->
              <div class="absolute top-full w-48 left-0 px-4 py-4 bg-white hidden font-regular font-sans text-16 group-hover:flex flex-col shadow-md rounded-sm z-10">
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=Band'); ?>">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                  Bands
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=Solo Artist'); ?>">
                  <img class="h-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
                  Solo Artists
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qcategory=DJ'); ?>">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
                  DJs
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/?qsetting=Wedding'); ?>">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
                  Wedding Music
                </a>
              </div>
            </span>
            <a href="/blog/">Blog</a>
          </div>

          <div class="flex items-center gap-2 shrink-0">
            <div class="flex items-center">
              <div class="hamburger block lg:hidden h-8 w-8 cursor-pointer relative" x-on:click="showMobileMenu = ! showMobileMenu; showMobileFilters = false;" x-bind:class="{ 'active': showMobileMenu }" >
                <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 -translate-y-2.5 transform transition duration-500 ease-in-out"></div>
                <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 transform transition duration-500 ease-in-out"></div>
                <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 translate-y-2.5 transform transition duration-500 ease-in-out"></div>
              </div>
            </div>
            <button class="border-2 font-sun-motter text-16 px-3 md:px-5 py-2 md:py-3 ml-4" x-cloak x-show="!loggedIn" x-on:click="showLoginModal = !showLoginModal">Log In</button>
            <button class="bg-navy border-2 border-black text-white shadow-black-offset hover:bg-yellow hover:text-black font-sun-motter text-16 px-3 md:px-5 py-2 md:py-3" x-cloak x-show="!loggedIn" x-on:click="showSignupModal = !showSignupModal">Sign Up</button>

            <!-- Logged in Menu -->
            <span class="font-sun-motter text-18 items-center gap-2 relative group hidden lg:flex" x-cloak x-show="loggedIn">
              <a href="#">My Account</a>
              <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
              <!-- Dropdown menu -->
              <div class="absolute top-full w-40 left-0 px-4 py-4 bg-white hidden font-regular font-sans text-16 group-hover:flex flex-col shadow-md rounded-sm z-10">
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/account/'); ?>">
                  <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/contact-info.svg'; ?>" />
                  Account
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/listings/'); ?>">
                  <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                  My Listings
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/collections/'); ?>">
                  <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/album-collection-solid.svg'; ?>" />
                  Collections
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/messages/'); ?>">
                  <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/speech-bubble.svg'; ?>" />
                  Messages
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo site_url('/inquiries/'); ?>">
                  <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
                  Inquiries
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="<?php echo wp_logout_url('/'); ?>">
                  <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/log-out.svg'; ?>" />
                  Log Out
                </a>
              </div>
            </span>

          </div>

        </div>

      </div>
      <?php
          echo get_template_part('template-parts/global/toasts/success-toast', '', []);
          echo get_template_part('template-parts/global/toasts/error-toast',   '', []);
      ?>
    </header>


    <?php wp_body_open(); ?>
    <?php
        echo get_template_part('template-parts/global/mobile-menu', '', []);
        echo get_template_part('template-parts/login/login-modal', '', []);
        echo get_template_part('template-parts/login/signup-modal', '', []);
        echo get_template_part('template-parts/login/password-reset-modal', '', []);
        echo get_template_part('template-parts/inquiries/inquiry-popup', '', []);
    ?>
