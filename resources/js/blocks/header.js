$('.js-header').each(function(i, el) {

    var $burger = $(el).find('.js-nav-burger');
    var $nav = $(el).find('.js-nav');

    $burger.off('menu').on('click.menu', function(e) {

        $(this).toggleClass('is-active');
        $nav.toggleClass('is-active');

    });

});

$('.js-nav-item').each(function(i, el) {

    var $title = $(el).find('.js-nav-title');
    var $list = $(el).find('.js-nav-list');

    $title.off('list').on('click.list', function(e) {

        $list.slideToggle(300);

    });

});