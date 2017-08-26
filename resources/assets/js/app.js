var cookiesFunctions = {
    'man cookies': function() {
        $('#cookies p.man-cookies').insertBefore('.clearfix');
        $('#cookies p').removeClass('hidden-xs-up');
    },
    'exit': function() {
        $('#cookies').addClass('hidden-xs-up');
    },
    'other': function(val) {
        $('<p class="cmd">~ root: <br>' + val + ' commande introuvable</p>').insertBefore('#cookies .clearfix')
    }
};

$(document).ready(function() {
    if(!Cookies.get(config.analytics.cookies.cnil.name)) {
        Cookies.set(config.analytics.cookies.cnil.name, config.analytics.cookies.cnil.value, {
            expires: config.analytics.cookies.cnil.days
        });

        $('#cookies').removeClass('hidden-xs-up');
        $('#cookies input').focus();
    }

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

    $('#cookies input').on('keyup', function(e) {
        if(e.which == 13) {
            try {
                cookiesFunctions[$(this).val()]();
            } catch (e) {
                cookiesFunctions['other']($(this).val());
            }

            $(this).val('');
        }
    });
});