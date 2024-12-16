(function($) {

  $(document).ready(function() {

    $('#search input').on('focus', function () {
      $('#search-state-1').removeClass('hidden');
  });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('#search').length) {
            $('#search-state-1').addClass('hidden');
        }
    });
  });



})(jQuery);




    const tooltip = document.getElementById('tooltip-sort');
    const info = document.getElementById('info-sort');

    info.addEventListener('mouseenter', () => {
        tooltip.style.display = 'block';

        const rect = info.getBoundingClientRect();
        const tooltipHeight = tooltip.offsetHeight;

        tooltip.style.left = `${rect.left - 8}px`;
        tooltip.style.top = `${rect.top - tooltipHeight - 40}px`;

    });

    info.addEventListener('mouseleave', () => {
        tooltip.style.display = 'none';
    });


