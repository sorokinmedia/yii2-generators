(function ($) {
    $('#generator-modelclass').on('blur', function () {
        var modelClass = $(this).val();
        if (modelClass !== '') {
            var formClassInput = $('#generator-formclass');
            var formClass = formClassInput.val();
            if (formClass === '') {
                formClass = modelClass.split('\\').slice(-1)[0] + 'Form';
                formClassInput.val(formClass);
            }
        }
    });
})(jQuery);