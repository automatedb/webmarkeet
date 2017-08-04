@extends('Layout.guest.content')

@section('content')
    <div class="container">
        <h1>Tous les tutoriels</h1>
        <p class="lead">Découvrez tous les tutoriels proposés sur {{ config('app.name') }}</p>

        <div class="row">
            <div class="col-md-6">
                <h4>Tutoriel le plus récent</h4>
                @widget('TutorialThumb', [
                    'id' => $firstContent['id'],
                    'src' => $firstContent[\App\Models\Content::$THUMBNAIL],
                    'title' => $firstContent[\App\Models\Content::$TITLE],
                    'slug' => $firstContent[\App\Models\Content::$SLUG]
                ])
            </div>
            <div class="col-md-6"></div>
        </div>

        <h4>Les derniers tutoriels</h4>
        @forelse(array_chunk($contents, 3) as $list)
        <div class="row">
            @foreach($list as $content)
                <article class="col-md-4">
                    <div class="card">
                        <div class="card-block">
                            <h3 class="card-title">
                                {!! link_to_action('ContentCtrl@tutorial', $content[\App\Models\Content::$TITLE], [ 'slug' => $content[\App\Models\Content::$SLUG] ]) !!}
                            </h3>
                            <p class="card-text">{!! str_limit($content[\App\Models\Content::$CONTENT], 50) !!}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        @empty
            <p>Pas de contenu pour le moment</p>
        @endforelse
    </div>
@endsection