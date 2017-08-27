(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/fr_FR/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function() {
    FB.init({
        appId            : config.social.api.facebook.public_id,
        autoLogAppEvents : true,
        xfbml            : true,
        version          : 'v2.10'
    });
    FB.AppEvents.logPageView();
};


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

$.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {

    // Prevent default anchor event
    e.preventDefault();

    // Set values for window
    intWidth = intWidth || '500';
    intHeight = intHeight || '400';
    strResize = (blnResize ? 'yes' : 'no');

    var left = (screen.width/2)-(intWidth/2);
    var top = (screen.height/2)-(intHeight/2);

    // Set title and open popup with focus on it
    var strTitle = ((typeof this.attr('title') !== 'undefined') ? this.attr('title') : 'Social Share'),
        strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize + ', top=' + top + ',left=' + left,
        objWindow = window.open(this.attr('href'), strTitle, strParam).focus();
};

$(document).ready(function() {
    if(!Cookies.get(config.analytics.cookies.cnil.name)) {
        Cookies.set(config.analytics.cookies.cnil.name, config.analytics.cookies.cnil.value, {
            expires: config.analytics.cookies.cnil.days
        });

        $('#cookies').removeClass('hidden-xs-up');
        $('#cookies input').focus();
    }

    /**
     * Permet la validation des formulaires
     */
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

    /**
     * Gestion des bouton de partage
     */
    $('.btn-facebook').on('click', function(e) {
        e.preventDefault();

        FB.ui({
                method: 'share_open_graph',
                action_type: 'og.shares',
                action_properties: JSON.stringify({
                    object: {
                        'og:url': $(this).attr('href'),
                        'og:title': $(this).attr('data-title'),
                        'og:description': $(this).attr('data-description'),
                        'og:image': $(this).attr('data-image')
                    }
                })
            }, function (response) { });
    });

    $('.btn-twitter').on('click', function(e) {
        $(this).customerPopup(e);
    });

    /**
     * Gestion des cookies
     */
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