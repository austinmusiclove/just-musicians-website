(function($) {

  $(document).ready(function() {

    const $desktopSearchInput = $('[data-search="desktop"] input');

    $desktopSearchInput.on('focus', function () {
      if ($(window).width() < 768) {
        $('[data-element="mobile-search"]').removeClass('hidden');
        $('[data-element="mobile-search"] [data-input="search"]').focus();
      } else {
        $('[data-search-state="desktop-1"]').removeClass('hidden');
      }
    });
    

    $(document).on('click', function (event) {
        if (!$(event.target).closest('[data-search="desktop"]').length) {
            $('[data-search-state="desktop-1"]').addClass('hidden');
            $('[data-search-state="desktop-2"]').addClass('hidden');
        }
    });
      
  
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



  });
  

  $('[data-trigger="filter"]').on('click', function () {
    $('[data-popup="filter"]').toggleClass('hidden');
  });

  $('[data-element="option"]').on('click', function () {
    var selectedText = $(this).html();
    var selectedElement = $('[data-value="selected"]');
    selectedElement.html(selectedText);
        $('[data-element="what-do-you-need"]').addClass('hidden');
  });

  $('[data-trigger="what-do-you-need"]').on('click', function () {
    $('[data-element="what-do-you-need"]').toggleClass('hidden');
  });

  $('[data-trigger="mobile-search"]').on('click', function () {
    toggleSearch();
  });

  $('[data-trigger="mobile-filter"]').on('click', function () {
    $('[data-element="mobile-filter"]').toggleClass('hidden');
    $('body').toggleClass('frozen');
    closeMenu();
  });

  $('[data-trigger="mobile-menu"]').on('click', function () {
    $('[data-element="mobile-menu"]').toggleClass('hidden');
    $(this).toggleClass('active');
    $('[data-element="mobile-filter"]').addClass('hidden');
    $('body').removeClass('frozen');
  });

  function closeMenu() {
    $('[data-element="mobile-menu"]').addClass('hidden');
    $('[data-trigger="mobile-menu"]').removeClass('active');
  }

  function closeSearch() {
    $('[data-element="mobile-search"]').addClass('hidden');
    $('[data-search-state="mobile-1"]').removeClass('hidden');
    $('[data-search-state="mobile-2"]').addClass('hidden');
  }

  function toggleSearch() {
    $('[data-element="mobile-search"]').toggleClass('hidden');
    $('[data-search-state="mobile-1"]').removeClass('hidden');
    $('[data-search-state="mobile-2"]').addClass('hidden');
  }

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

  $('[data-trigger="mobile-menu-dropdown"]').on('click', function () {
    $(this).toggleClass('active');
    $('[data-element="mobile-menu-dropdown"]').toggleClass('hidden');
  });
  

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

  $(window).resize(function() {
    closeMenu();
    closeSearch();
    $('[data-element="mobile-filter"]').addClass('hidden');
    $('body').removeClass('frozen');

  });



})(jQuery);





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


