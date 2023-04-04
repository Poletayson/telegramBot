$('.js-calendar-scroll').each(function(i, el) {

    $(el).mCustomScrollbar({
        axis: 'y',
        contentTouchScroll: true,
        scrollInertia: 120,
        setHeight: 200,
    });
});