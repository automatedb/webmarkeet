@extends('Layout.guest.content')

@section('content')
    <header class="masthead" style="background-image: url('img/blog-thumbnail.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h1>Formations</h1>
                        <hr class="intro-divider">
                        <span class="subheading">Formez-vous en trading algorithmique.</span>
                        <span class="subheading">Les formations sont disponibles dans votre espace-client.</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @forelse ($formations as $formation)
        <section class="items-formation">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-8">
                        <hr class="section-heading-spacer">
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-4">
                                @widget('Img', [
                                    'id' => $formation->id,
                                    'src' => $formation->thumbnail,
                                    'title' => $formation->title,
                                    'type' => 'formation-thumbnail'
                                ])
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <h4>{{ $formation[\App\Models\Content::$TITLE] }}</h4>
                                    <p>{!! strip_tags($formation[\App\Models\Content::$CONTENT]) !!}</p>
                                    @if(\Illuminate\Support\Facades\Auth::check())
                                        <a href="{{ action('FormationCtrl@formation', ['slug' => $formation[\App\Models\Content::$SLUG]]) }}">Télécharger <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                    @else
                                        <a href="{{ action('UserCtrl@authentication') }}">Commander <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @empty
        <section>
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-6">
                        <p class="text-center">Pas de contenu pour le moment</p>
                    </div>
                </div>
            </div>
        </section>
    @endforelse
@endsection