@extends('Layout.guest.content')

@section('content')
    <header class="masthead" style="background-image: url('img/blog-thumbnail.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h1>Tutoriels</h1>
                        <hr class="intro-divider">
                        <span class="subheading">Découvrez tous les tutoriels proposés sur {{ config('app.name') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <h2 class="section-heading">Tutoriel le plus récent</h2>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-6">
                    @widget('TutorialThumb', [
                        'id' => $firstContent['id'],
                        'src' => $firstContent[\App\Models\Content::$THUMBNAIL],
                        'slug' => $firstContent[\App\Models\Content::$SLUG]
                    ])
                </div>
                <div class="col-md-6 text-center">
                    <h2 class="text-center">Devenir membre {{ config('app.name') }}</h2>
                    <p class="lead text-center">Télécharger immédiatement de toutes les ressources</p>
                    {!! link_to_action('UserCtrl@authentication', 'Devenir membre', [], [ 'class' => 'btn btn-success' ]) !!}
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-a -->

    <section id="all-tutorials">
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <h2 class="section-heading">Les derniers tutoriels</h2>
            <div class="clearfix"></div>
                @forelse(array_chunk($contents, 3) as $list)
                    <div class="row">
                        @foreach($list as $content)
                            <article class="col-md-4">
                                <div class="card">
                                    <div class="card-block">
                                        <h3 class="card-title text-center">
                                            {!! link_to_action('ContentCtrl@tutorial', str_limit($content[\App\Models\Content::$TITLE], 20), [ 'slug' => $content[\App\Models\Content::$SLUG] ], [ 'itemprop' => 'url' ]) !!}
                                        </h3>
                                        <p class="card-text">{!! strip_tags(str_limit($content[\App\Models\Content::$CONTENT], 50)) !!}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @empty
                    <p>Pas de contenu pour le moment</p>
                @endforelse
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-b -->
@endsection

@section('seo')
    <title>{!! $title !!}</title>
    <meta name="description" content="{!! $description !!}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($thumbnail) }}">
@stop

@push('scripts')
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "WebSite",
        "headline": "Tutoriels",
        "description": "Découvrez tous les tutoriels proposés sur {{ config('app.name') }}",
    }
    </script>
@endpush