@extends('Layout.guest.content')


@section('content')
    <!-- Header -->
    <header class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="intro-message">
                        <h2>Login</h2>
                        <hr class="intro-divider">

                        @widget('Alert', $alert)

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
                </div>
                <div class="col-lg-2">
                    <div class="intro-message">
                        <h3>ou</h3>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="intro-message">
                        <h2>Devenir membre</h2>
                        <hr class="intro-divider">
                        <h3 class="price text-center">{{ config('subscription.price') }}<small>€ HT</small></h3>
                        <p class="period text-center">par {{ config('subscription.period') }}</p>
                        <a href="{{ action('UserCtrl@payment') }}" class="btn btn-success">
                            Accédez aux codes sources
                        </a>
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
            <h2 class="section-heading">Devenir membre</h2>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Devenir membre sur {{ env('APP_NAME') }}, c'est soutenir la création de nouveaux contenus et accéder à du contenu exclusif pour apprendre et s'améliorer (comme le téléchargement des vidéos et des sources).</p>
                    <p>C'est aussi accéder à des contenus premium uniquement accéssible au membres {{ env('APP_NAME') }}.</p>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-a -->

    <section>
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <h2 class="section-heading">Pourquoi cette offre ?</h2>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Mon but à travers {{ env('APP_NAME') }} est de partager mes connaissances avec le plus grand nombre, c'est pourquoi j'essaie de rendre un maximum de contenu gratuit et public.</p>
                    <p>Malgré tout, la recherche, les tests, la préparation, l'enregistrement et le montage des formations prend un temps considérable. Du coup proposer des options payantes, comme le téléchargement des sources, me permet d'amortir une partie du temps passé et de continuer à faire vivre le site.</p>
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
            <h2 class="section-heading">Ai-je un engagement dans le durée ?</h2>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Non, il est possible de se désabonner à tout moment. Votre désinscription sera effective immédiatement sans procédure complexe. Et tous les prélèvements seront stoppés.</p>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-c -->

    <section>
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <h2 class="section-heading">Retour de produit</h2>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Aucun remboursement n’est effectué après l’accès au produit commandé (sauf dans le cas d’un achat multiple lié à une erreur de manipulation, dans le cas où le produit acheté n’a pas été téléchargé ou visionné, ou dans un autre cas légitime).</p>
                    <p>A titre informatif, la réglementation exclut du délai de rétractation légal la “fourniture d’enregistrements audio ou vidéo, ou de logiciels informatiques lorsqu’ils ont été descellés par le consommateur”. Le délai de rétractation légal est nul à partir du moment où le produit commandé est téléchargé ou visionné.</p>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-d -->

    <section>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-12 text-center">
                    <h2>Devenir membre {{ config('app.name') }}</h2>
                    <p class="lead">Accèdez immédiatement au téléchargement de toutes les resources</p>
                </div>
                <div class="col-lg-4">
                    {{ link_to_action('UserCtrl@payment', 'Créer mon compte', [], [ 'class' => 'btn btn-success btn-lg btn-block' ]) }}
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-e -->

    <section id="resume">
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <h2 class="section-heading">En résumé</h2>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-6">
                    <h4>Accès immédiat aux :</h4>
                    <ul>
                        <li>codes sources</li>
                        <li>vidéos</li>
                        {{--<li>services développés</li>--}}
                    </ul>
                    <h4>Pas d'engagement de durée</h4>
                    <ul>
                        <li>Arrêtez votre abonnement à tout moment</li>
                        <li>Désinscription simple</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h3 class="price text-center">{{ config('subscription.price') }}<small>€ HT</small></h3>
                    <p class="period text-center">par {{ config('subscription.period') }}</p>
                    {{ link_to_action('UserCtrl@payment', 'Créer mon compte', [], [ 'class' => 'btn btn-success btn-lg btn-block' ]) }}
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-fd -->
@endsection


@section('seo')
    <title>Devenir membre {{ config('app.name') }} - Authentification</title>
@stop