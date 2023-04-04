$('.js-switcher').each(function(i, el) {

    $(el).off('switch').on('click.switch', function(e) {

        $(this).toggleClass('is-active');

    });

});