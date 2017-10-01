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

        @stack('styles')
        {!! Html::style('/css/app.css', [], env('APP_ENV') == 'production') !!}
    </head>
    <body class="guest-page">
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