// replaced with alpinejs and htmx
// TODO remove data triggers and data element attributes from html or leave it in to use this as a fall back in case i move away from alpine and htmx
// TODO finish flow for the inquiry modal


(function($) {

// show/hide mobile or desktop search bar based on screen size
// show/hide autocomplete elements based on click, focus, user input
// replace with htmx active search for autocomplete options and alpine for basic options display

/*
const $passwordResetForm = $('[data-slide="request-password-reset"]');
const $loginForm = $('[data-slide="login-form"]');

window.showResetForm = function () {
  $passwordResetForm.removeClass('hidden');
  $loginForm.addClass('hidden');
};

window.showLoginForm = function () {
  $passwordResetForm.addClass('hidden');
  $loginForm.removeClass('hidden');
};
*/

  $(document).ready(function() {



    // replace with alpine
    /*
    const $desktopSearchInput = $('[data-search="desktop"] input');
    $desktopSearchInput.on('focus', function () {
        console.log('focus'):
      if ($(window).width() < 768) {
        $('[data-element="mobile-search"]').removeClass('hidden');
        $('[data-element="mobile-search"] [data-input="search"]').focus(); // causes infinite loop because mobile search is inside desktop search; but still need to set the focus somehow
      } else {
        $('[data-search-state="desktop-1"]').removeClass('hidden');
      }
    });
    */


    // hide search options on click off
    // replace with alpine
    /*
    $(document).on('click', function (event) {
        if (!$(event.target).closest('[data-search="desktop"]').length) {
            $('[data-search-state="desktop-1"]').addClass('hidden');
            $('[data-search-state="desktop-2"]').addClass('hidden');
        }
    });
    */


    // replace with htmx to get state 2 and alpine to return to static state 1
    /*
    $desktopSearchInput.on('input', function () {
        const value = $(this).val().trim();
        // When user starts to type
        if (value) {
          $('[data-search-state="desktop-1"]').addClass('hidden');
          $('[data-search-state="desktop-2"]').removeClass('hidden');
        // Perform actions when there's input
        } else {
          $('[data-search-state="desktop-1"]').removeClass('hidden');
          $('[data-search-state="desktop-2"]').addClass('hidden');
        }
    });
    */

    // replace with htmx to get state 2 and alpine to return to static state 1
    /*
    const $mobileSearchInput = $('[data-search="mobile"] input');
    $mobileSearchInput.on('input', function () {
      const value = $(this).val().trim();
      // When user starts to type
      if (value) {
        $('[data-search-state="mobile-1"]').addClass('hidden');
        $('[data-search-state="mobile-2"]').removeClass('hidden');
      // Perform actions when there's input
      } else {
        $('[data-search-state="mobile-1"]').removeClass('hidden');
        $('[data-search-state="mobile-2"]').addClass('hidden');
      }
  });
  */



  });


// "see all" pop up button
// replace with alpine
/*
  $('[data-trigger="filter"]').on('click', function () {
    $('[data-popup="filter"]').toggleClass('hidden');
  });
*/

// manage state of request a quote form side bar dropdown
// replace with alpine
/*
  $('[data-element="option"]').on('click', function () {
    var selectedText = $(this).html();
    var selectedElement = $('[data-value="selected"]');
    selectedElement.html(selectedText);
    $('[data-element="what-do-you-need"]').addClass('hidden');
  });
  $('[data-trigger="what-do-you-need"]').on('click', function () {
    $('[data-element="what-do-you-need"]').toggleClass('hidden');
  });
*/

// close mobile search
// also gets opened at top by event handler on desktopsearchinput focus
/*
  $('[data-trigger="mobile-search"]').on('click', function () {
    toggleSearch();
  });
  function toggleSearch() {
    $('[data-element="mobile-search"]').toggleClass('hidden');
    $('[data-search-state="mobile-1"]').removeClass('hidden');
    $('[data-search-state="mobile-2"]').addClass('hidden');
  }
  function closeSearch() {
    $('[data-element="mobile-search"]').addClass('hidden');
    $('[data-search-state="mobile-1"]').removeClass('hidden');
    $('[data-search-state="mobile-2"]').addClass('hidden');
  }
*/


// open/close mobile filters
// replace with alpine
/*
  $('[data-trigger="mobile-filter"]').on('click', function () {
    $('[data-element="mobile-filter"]').toggleClass('hidden');
    $('body').toggleClass('frozen');
    closeMenu();
  });
*/

// open/close mobile menu (hamburger button)
// replace with alpine
    // not great locality of behavior because there is code in two files and two different places in one of the files; but that is partly because of the html structure
/*
  $('[data-trigger="mobile-menu"]').on('click', function () {
    $('[data-element="mobile-menu"]').toggleClass('hidden');
    $(this).toggleClass('active');
    $('[data-element="mobile-filter"]').addClass('hidden');
    $('body').removeClass('frozen');
  });
*/

// close menu
/*
  function closeMenu() {
    $('[data-element="mobile-menu"]').addClass('hidden');
    $('[data-trigger="mobile-menu"]').removeClass('active');
  }
*/

  // Move between modal slides
  // replace with alpine
  /*
  $('[data-trigger="quote"]').on('click', function () {
    if ($('[data-popup="quote"]').hasClass('hidden')) {
      $('[data-popup="quote"]').removeClass('hidden');
      $('.slide').addClass('hidden');
      $('[data-slide="1"]').removeClass('hidden');

    } else if ($('[data-slide="4"]').hasClass('hidden')) {
      $('.slide').addClass('hidden');
      $('[data-slide="5"]').removeClass('hidden');

    } else {
      $('[data-popup="quote"]').addClass('hidden');
    }
  });
  */

  // Mobile menue dropdown
  // replace with alpine
  /*
  $('[data-trigger="mobile-menu-dropdown"]').on('click', function () {
    $(this).toggleClass('active');
    $('[data-element="mobile-menu-dropdown"]').toggleClass('hidden');
  });
  */


  // Move between modal slides
  // replace with alpine
  /*
  $('[data-trigger="slide-1"]').on('click', function () {
    $('.slide').addClass('hidden');
    $('[data-slide="1"]').removeClass('hidden');
  });

  $('[data-trigger="slide-2"]').on('click', function () {
    $('.slide').addClass('hidden');
    $('[data-slide="2"]').removeClass('hidden');
  });

  $('[data-trigger="slide-3"]').on('click', function () {
    $('.slide').addClass('hidden');
    $('[data-slide="3"]').removeClass('hidden');
  });

  $('[data-trigger="slide-4"]').on('click', function () {
    $('.slide').addClass('hidden');
    $('[data-slide="4"]').removeClass('hidden');
  });
  */

// handle resize
// replace with alpine
/*
  $(window).resize(function() {
    closeMenu();
    closeSearch();
    $('[data-element="mobile-filter"]').addClass('hidden');
    $('body').removeClass('frozen');

  });
*/


})(jQuery);





// Show/hide sort tooltip
// Replace with alpine
/*
const tooltip = document.getElementById('tooltip-sort');
const info = document.getElementById('info-sort');

info.addEventListener('mouseenter', () => {
    tooltip.style.display = 'block';

    const rect = info.getBoundingClientRect();
    const tooltipHeight = tooltip.offsetHeight;

    tooltip.style.left = `${rect.left - 8}px`;
    tooltip.style.top = `${rect.top - tooltipHeight - 10}px`;

});

info.addEventListener('mouseleave', () => {
    tooltip.style.display = 'none';
});
*/
