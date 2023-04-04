$('.js-file-input').each(function(i, el) {

    var $fileButton = $(el).find('.js-file-button');
    var $fileText = $(el).find('.js-file-text');
    var $input = $(el).find('.js-file');

    $fileButton.off('file').on('click.file', function(e) {

        e.preventDefault();
        $input.click();

    });

    $input.change(function(e) {

        $fileText.text($(this)[0].files[0].name);

    });

});