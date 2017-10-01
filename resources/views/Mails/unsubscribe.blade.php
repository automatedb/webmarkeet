@extends('Mails.Layout.content')


@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-center">Bonjour,</h1>

            <p>Votre désabonnement a bien été pris en compte. Merci de la confiance que vous nous avez accordé jusqu'à maintenant.</p>

            <p>Sachez tout de même que votre abonnement reste valable jusqu'à la fin de la période en cours. Pendant cette période, vous pouvez toujours :</p>

            <ul>
                {{--<li>Accéder aux formations vidéos</li>--}}
                <li>Accéder aux téléchargements des codes sources</li>
            </ul>


            <p>Bien cordialement,</p>

            <p>{{ config('app.name') }}</p>
        </div>
    </div>
@endsection