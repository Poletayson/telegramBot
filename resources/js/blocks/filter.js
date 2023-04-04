$('.js-filter').each(function(i, el) {

    var $scrollList = $(el).find('.js-filter-list');
    $scrollList.mCustomScrollbar({
        axis: 'y',
        contentTouchScroll: true,
        scrollInertia: 120,
        setHeight: 70
    });
});