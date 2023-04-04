$('.js-table').each(function(i, el) {

    var $tableRow = $(el).find('.js-table-row');
    var $callIcon = $(el).find('.js-call');
    var $tableScroll = $(el).find('.js-table-scroll');

    $tableRow.off('openBottom').on('click.openBottom', function(e) {


        if (!$callIcon.is(e.target)) {
            $(this).toggleClass('is-active');
            $(this).find('.js-table-bottom').slideToggle(300);
        } else {
            alert('popup');
        }
    });

});
