$('.js-settings').each(function(i, el) {

    var $newOrg = $(el).find('.js-new-org');
    var $buttonNewOrg = $(el).find('.js-add-org');
    var $newClear = $(el).find('.js-new-clear');
    var $newInput = $(el).find('.js-new-input');

    $buttonNewOrg.off('newOrg').on('click.newOrg', function(e) {

        e.preventDefault();
        $newOrg.slideToggle(300);
        $(this).toggleClass('is-active');

    });

    $newClear.off('clear').on('click.clear', function(e) {

        e.preventDefault();
        $newInput.val('');

    });

});

$('.js-org-block').each(function(i, el) {

    var $orgDel = $(el).find('.js-org-del');
    var $orgEdit = $(el).find('.js-org-edit');
    var $orgInput = $(el).find('.js-org-input');

    $orgDel.off('delOrg').on('click.delOrg', function(e) {

        e.preventDefault();
        $(el).remove();

    });

    $orgEdit.off('editOrg').on('click.editOrg', function(e) {

        e.preventDefault();
        $orgInput.removeAttr('disabled').select();
        $(this).parent().addClass('is-active');

    });

});

$('.js-org-choise').each(function(i, el) {

    var $orgButton = $(el).find('.js-button-org');
    var $orgModal = $(el).find('.js-modal-org');

    $orgButton.off('choiseModal').on('click.choiseModal', function(e) {

        $orgModal.fadeToggle(300).toggleClass('is-active');

    });

});
