$('.js-select').each(function(i, el) {

    $(el).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

});