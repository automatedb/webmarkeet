@extends('Mails.Layout.content')


@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-center">Bienvenue sur {{ config('app.name') }}</h1>

            <p>Votre souscription a bien été prise en compte. Vous pouvez dès à présent vous connecter à votre compte avec votre adresse email et le mot de passe que vous avez choisi lors de votre inscription.</p>

            <h4>Quelques conseils...</h4>

            <ul>
                <li>Vous pouvez accéder à votre espace profil via le menu déroulant en haut à droite du site.</li>
                <li>Vous pouvez modifier vos informations de connexion dans votre espace profil.</li>
                <li>Toutes les sources téléchargeables sont accéssibles via les boutons de téléchargement en vert.</li>
                {{--<li>Toutes les formations sont accéssibles dans l'onglet "Formations" via le menu.</li>--}}
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