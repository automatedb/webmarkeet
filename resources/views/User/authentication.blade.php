@extends('Layout.guest.content')


@section('content')
    <section class="container">
        @widget('Alert', $alert)
    </section>

    <section class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-4">
                <h3>Authentification</h3>
                {!! Form::open(['action' => 'UserCtrl@postAuthentication']) !!}

                <div class="form-group">
                    {!! Form::text('email', '', ['class' => 'form-control required', 'placeholder' => 'Votre adresse email']) !!}
                    <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre email.</p>
                </div>
                <div class="form-group">
                    {!! Form::password('password', ['class' => 'form-control required', 'placeholder' => 'Votre mot de passe']) !!}
                    <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre mot de passe.</p>
                </div>
                <div class="form-group">
                    {!! Form::submit('Connexion', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="text-center">Devenir membre</h1>

                        <h2 class="text-center">97 <small>€ HT</small></h2>
                        <h4 class="text-center">par mois</h4>

                        <h4>Accès immédiat aux :</h4>
                        <ul>
                            <li>codes sources</li>
                            <li>vidéos</li>
                            {{--<li>services développés</li>--}}
                        </ul>

                        <h4>Pas d'angagement de durée</h4>
                        <ul>
                            <li>Arrêtez votre abonnement à tout moment</li>
                            <li>Désinscription simple</li>
                        </ul>

                        {{ link_to_action('UserCtrl@payment', 'Créer mon compte', [], [ 'class' => 'btn btn-success btn-lg btn-block' ]) }}
                    </div>

                    <div class="col-lg-12">
                        <h3>Devenir membre</h3>
                        <p>Devenir membre sur {{ env('APP_NAME') }}, c'est soutenir la création de nouveaux contenus et accéder à du contenu exclusif pour apprendre et s'améliorer (comme le téléchargement des vidéos et des sources).</p>
                        <p>C'est aussi accéder à des contenus premium uniquement accéssible au membres {{ env('APP_NAME') }}.</p>
                        <h3>Pourquoi cette offre ?</h3>
                        <p>Mon but à travers {{ env('APP_NAME') }} est de partager mes connaissances avec le plus grand nombre, c'est pourquoi j'essaie de rendre un maximum de contenu gratuit et public.</p>
                        <p>Malgré tout, la recherche, les tests, la préparation, l'enregistrement et le montage des formations prend un temps considérable. Du coup proposer des options payantes, comme le téléchargement des sources, me permet d'amortir une partie du temps passé et de continuer à faire vivre le site.</p>
                        <h3>Ai-je un engagement dans le durée ?</h3>
                        <p>Non, il est possible de se désabonner à tout moment. Votre désinscription sera effective immédiatement sans procédure complexe. Et tous les prélèvements seront stoppés.</p>
                        <h3>Retour de produit</h3>
                        <p>Aucun remboursement n’est effectué après l’accès au produit commandé (sauf dans le cas d’un achat multiple lié à une erreur de manipulation, dans le cas où le produit acheté n’a pas été téléchargé ou visionné, ou dans un autre cas légitime).</p>
                        <p>A titre informatif, la réglementation exclut du délai de rétractation légal la “fourniture d’enregistrements audio ou vidéo, ou de logiciels informatiques lorsqu’ils ont été descellés par le consommateur”. Le délai de rétractation légal est nul à partir du moment où le produit commandé est téléchargé ou visionné.</p>
                        {{ link_to_action('UserCtrl@payment', 'Créer mon compte', [], [ 'class' => 'btn btn-success btn-lg btn-block' ]) }}
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection