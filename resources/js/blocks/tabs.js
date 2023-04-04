$('.js-tabs').each(function(i, el) {

    var $tabTrigger = $(el).find('.js-tab-trigger');
    var $tabElement = $(el).find('.js-tab');

    $tabTrigger.off('tab').on('click.tab',function(e) {

        var data = $(this).data('tab');
        var $element = $tabElement.filter('[data-tab=' + data +']');

        $(this).addClass('is-active');
        $tabTrigger.not($(this)).removeClass('is-active');
        $element.fadeIn(300);
        $tabElement.not($element).fadeOut(0);
    });

});