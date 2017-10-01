@extends('Mails.Layout.content')


@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-center">Bonjour,</h1>

            <p>Votre souscription a bien été prise en compte. Merci de votre confiance.</p>

            <h4>Vous pouvez dès maintenant :</h4>

            <ul>
                <li>Télécharger toutes les sources disponibles avec les tutoriels et formations via les boutons de téléchargement en vert.</li>
                {{--<li>Accéder et télécharger les formations via l'onglet "Formations" présent dans le menu.</li>--}}
            </ul>
        </div>

        <div class="btn-share">
            <hr>
            <p class="text-center">Prenez le temps de partager {{ config('app.name') }} ! :)</p>
            <p class="text-center">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ config('app.url') }}"
                   class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i> Partager sur Facebook</a>

                <a href="https://twitter.com/share?url={{ config('app.url') }}
                        &text={{ urlencode(sprintf('%s - %s : %s', config('app.name'), config('app.slogan'), config('app.url'))) }}"
                   class="btn btn-social btn-twitter"><i class="fa fa-twitter"></i> Partager sur Twitter</a>

            </p>
            <hr>
        </div>

        <div class="row justify-content-md-center">
            <a href="{{ config('app.url') }}/authentication" class="btn btn-success btn-lg">Me connecter à mon compte</a>
        </div>
    </div>
@endsection