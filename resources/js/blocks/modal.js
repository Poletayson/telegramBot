$('.js-filter-button').each(function(i, el) {

    var $filterModal = $('.js-filter-modal');

    $(el).on('click', function(e) {

        if ($filterModal.hasClass('is-active')) {
            $filterModal.removeClass('is-active').fadeOut('300');
        } else {
            $filterModal.addClass('is-active').fadeIn('300');
        }

    });

});

$('[data-fancybox]').fancybox({
    autoFocus: false,
    touch: false
});

