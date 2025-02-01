(function($) {

  $(document).ready(function() {

    const $searchInput = $('#search input');

    $searchInput.on('focus', function () {
      $('#search-state-1').removeClass('hidden');
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('#search').length) {
            $('#search-state-1').addClass('hidden');
            $('#search-state-2').addClass('hidden');
        }
    });
      
  
    $searchInput.on('input', function () {
        const value = $(this).val().trim();
        // When user starts to ype
        if (value) {
          $('#search-state-1').addClass('hidden');
          $('#search-state-2').removeClass('hidden');
        // Perform actions when there's input
        } else {
          $('#search-state-1').removeClass('hidden');
          $('#search-state-2').addClass('hidden');
        }
    });
  
  });

  $('[data-trigger="filter"]').on('click', function () {
    $('[data-popup="filter"]').toggleClass('hidden');
  });

  $('[data-trigger="mobile-menu"]').on('click', function () {
    $('[data-element="mobile-menu"]').toggleClass('hidden');
    $(this).toggleClass('active');
  });

  function closeMenu() {
    $('[data-element="mobile-menu"]').addClass('hidden');
    $('[data-trigger="mobile-menu"]').removeClass('active');
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


