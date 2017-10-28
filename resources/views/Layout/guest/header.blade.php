<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @yield('seo')

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css" integrity="sha256-rFMLRbqAytD9ic/37Rnzr2Ycy/RlpxE5QH52h7VoIZo=" crossorigin="anonymous" />

        @stack('styles')
        {!! Html::style('/css/app.css', [], env('APP_ENV') == 'production') !!}
    </head>
    <body class="guest-page">
    @if(!empty($referer))
        <div id="popin" class="hidden-xs-up">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <h3 class="text-center">Tous les tutoriels de {{ env('APP_NAME') }}</h3>
                    <p class="lead text-center">{{ env('APP_NAME') }} vulgarise le trading automatique et systématique. En vous inscrivant, vous vous assurez de ne rien manquer :</p>
                    <hr>
                    <div class="row justify-content-center">
                        <ul class="col-lg-6">
                            <li><i class="fa fa-check" aria-hidden="true"></i> Quand un nouveau tutoriel est en ligne</li>
                            <li><i class="fa fa-check" aria-hidden="true"></i> Quand un live Facebook est planifié</li>
                        </ul>
                        <div class="col-lg-5">
                            <div class="text-center">
                                <a href="#" class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i>  Suivre avec mon compte Facebook</a>
                            </div>
                            <div class="text-center">
                                <a href="#" class="close-popin">Non merci</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="popin-success-alert" class="alert alert-success alert-dismissible fade" role="alert">
            <strong>Cool !</strong> Vous recevrez prochainement mes mails d'actualité
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div id="popin-email-alert" class="alert alert-warning alert-dismissible fade" role="alert">
            <strong>Oups !</strong> Votre email est requis pour l'inscription
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div id="popin-failure-alert" class="alert alert-warning alert-dismissible fade" role="alert">
            <strong>Oups !</strong> Il semblerait qu'une erreur soit survenue
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <nav class="navbar navbar-toggleable-md navbar-light bg-faded topnav">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand topnav" href="/" rel="home">{{ env('APP_NAME') }}</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav navbar-nav ml-auto" itemscope itemtype="https://schema.org/SiteNavigationElement">
                    <li class="nav-item {{ \App\Helpers\Menu::activeMenu('blog') }}">
                        {{ link_to_action('ContentCtrl@index', 'Blog', [], ['class' => 'nav-link', 'itemprop' => 'url']) }}
                    </li>
                    <li class="nav-item {{ \App\Helpers\Menu::activeMenu('tutorials') }}">
                        {{ link_to_action('ContentCtrl@tutorials', 'Tutoriels', [], ['class' => 'nav-link', 'itemprop' => 'url']) }}
                    </li>
                    {{--<li class="nav-item {{ \App\Helpers\Menu::activeMenu('formations') }}">--}}
                        {{--{{ link_to_action('FormationCtrl@index', 'Formations', [], ['class' => 'nav-link', 'itemprop' => 'url']) }}--}}
                    {{--</li>--}}
                    @if(!\Illuminate\Support\Facades\Auth::check())
                        <li class="nav-item {{ \App\Helpers\Menu::activeMenu('authentication') }}">
                            {{ link_to_action('UserCtrl@authentication', 'Authentification', [], ['class' => 'nav-link', 'itemprop' => 'url']) }}
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Paramètres
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                {{ link_to_action('UserCtrl@profile', 'Mon profil', [], ['class' => 'dropdown-item']) }}
                                <div class="dropdown-divider"></div>
                                {{ link_to_action('UserCtrl@logout', 'Me déconnecter', [], ['class' => 'dropdown-item']) }}
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>