$(document).ready(function() {
    $('form').on('submit', function(e) {
        $('.required').each(function(i, el) {
            var value = $(el).val();
            var hasError = false;

            $(el).parent().removeClass('has-danger');
            $(el).parent().find('.form-control-feedback').addClass('hidden-xs-up');

            if($.trim(value) === '') {
                hasError = true;

                $(el).parent().addClass('has-danger');
                $(el).parent().find('.form-control-feedback').removeClass('hidden-xs-up');
            }

            if(hasError) {
                e.preventDefault();
            }
        });
    });
});