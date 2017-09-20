@extends('Mails.Layout.content')


@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-center">Bonjour,</h1>

            <p>Votre désinscription a bien été pris en compte. Merci de la confiance que vous nous avez accordé jusqu'à maintenant.</p>

            <p>Si vous aviez un abonnement, celui-ci a été désactivé automatiquement.</p>

            <p>Bien cordialement,</p>

            <p>{{ config('app.name') }}</p>
        </div>
    </div>
@endsection