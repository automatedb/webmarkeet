@extends('Layout.guest.content')

@section('content')
    <!-- Header -->
    <header class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>{{ config('app.name') }}</h1>
                        <h3>{{ config('app.slogan') }}</h3>
                        <hr class="intro-divider">
                        <ul class="list-inline intro-social-buttons">
                            @foreach(config('social.brand') as $brand)
                                @if(!empty($brand['url']))
                                    <li class="list-inline-item">
                                        <a href="{{ $brand['url'] }}" target="_blank" class="btn btn-secondary btn-lg"><i class="fa {{ $brand['css'] }} fa-fw"></i> <span class="network-name">{{ $brand['name'] }}</span></a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </header>
    <!-- /.intro-header -->

    <section>
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <h2 class="section-heading">Les derniers tutoriels</h2>
            <div class="clearfix"></div>
            <div class="row">
                @forelse($tutorials as $tutorial)
                    <div class="col-md-4">
                        <div class="card">
                            <a href="{{ action('ContentCtrl@tutorial', $tutorial->slug) }}">
                                @widget('Img', [
                                    'id' => $tutorial->id,
                                    'src' => $tutorial->thumbnail,
                                    'title' => $tutorial->title,
                                    'type' => 'home-thumbnail'
                                ])

                                <p class="card-text">{{ str_limit($tutorial->title, 40) }}</p>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="lead">Aucun tutoriel pour le moment.</p>
                @endforelse
            </div>
            <div class="row link-more">
                <div class="col-md-12">
                    <a class="btn btn-link pull-right" href="{{ action('ContentCtrl@tutorials') }}">Accéder à d'autres tutoriels <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-a -->

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Devenir membre {{ config('app.name') }}</h2>
                    <p class="lead">Accéder immédiatement au téléchargement de toutes les ressources</p>
                    {!! link_to_action('UserCtrl@authentication', 'Accéder', [], [ 'class' => 'btn btn-success' ]) !!}
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-b -->

    <section>
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <h2 class="section-heading">Les derniers articles</h2>
            <div class="clearfix"></div>
            <div class="row">
                @forelse($contents as $content)
                    <div class="col-md-4">
                        <div class="card">
                            <a href="{{ action('ContentCtrl@content', $content->slug) }}">
                                @widget('Img', [
                                    'id' => $tutorial->id,
                                    'src' => $tutorial->thumbnail,
                                    'title' => $tutorial->title,
                                    'type' => 'home-thumbnail'
                                ])

                                <p class="card-text">{{ str_limit($content->title, 40) }}</p>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="lead">Aucun article pour le moment.</p>
                @endforelse
            </div>
            <div class="row link-more">
                <div class="col-md-12">
                    <a class="btn btn-link pull-right" href="{{ action('ContentCtrl@index') }}">Accéder à d'autres articles <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                </div>
            </div>

        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-c -->

    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <h2>Discutons ailleurs :</h2>
                </div>
                <div class="col-lg-7">
                    <ul class="list-inline banner-social-buttons">
                        @foreach(config('social.brand') as $brand)
                            @if(!empty($brand['url']))
                                <li class="list-inline-item">
                                    <a href="{{ $brand['url'] }}" target="_blank" class="btn btn-secondary btn-lg"><i class="fa {{ $brand['css'] }} fa-fw"></i> <span class="network-name">{{ $brand['name'] }}</span></a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </div>
    <!-- /.banner -->
@endsection

@section('seo')
    <title>{!! config('app.name') !!} - {{ config('app.slogan') }}</title>
    <meta name="description" content="{{ config('app.description') }}">
    <meta property="og:title" content="{!! config('app.name') !!} - {{ config('app.slogan') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($thumbnail) }}">
@stop