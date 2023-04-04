$('.js-timetable').each(function(i, el) {

    $(el).mCustomScrollbar({
        axis: 'yx',
        contentTouchScroll: true,
        scrollInertia: 120,
    });

});