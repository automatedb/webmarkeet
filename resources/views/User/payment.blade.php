@extends('Layout.guest.content')

@section('content')
    <header id="payment" class="masthead" style="background-image: url('/img/blog-thumbnail.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h3 class="price text-center">97<small>€ HT</small></h3>
                        <p class="period text-center">par mois</p>
                        <hr class="intro-divider">
                        <div class="row justify-content-md-center">
                            <div class="col-md-6">
                                <h4>Accès immédiat aux :</h4>
                                <ul>
                                    <li><i class="fa fa-check" aria-hidden="true"></i> codes sources</li>
                                    <li><i class="fa fa-check" aria-hidden="true"></i> vidéos</li>
                                    {{--<li>services développés</li>--}}
                                </ul>
                                <h4>Pas d'engagement de durée :</h4>
                                <ul>
                                    <li><i class="fa fa-check" aria-hidden="true"></i> Arrêtez votre abonnement à tout moment</li>
                                    <li><i class="fa fa-check" aria-hidden="true"></i> Désinscription simple</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row justify-content-md-center">
                @widget('Alert', $alert)

                {!! Form::open(['action' => 'UserCtrl@postPayment']) !!}

                <fieldset class="form-group">
                    <legend>Information vous concernant</legend>
                    <div class="form-group">
                        {!! Form::text('firstname', '', ['class' => 'form-control required', 'placeholder' => 'Saisissez votre prénom']) !!}
                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre prénom.</p>
                    </div>

                    <div class="form-group">
                        {!! Form::text('lastname', '', ['class' => 'form-control required', 'placeholder' => 'Saisissez votre nom']) !!}
                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre nom.</p>
                    </div>
                </fieldset>

                <fieldset class="form-group">
                    <legend>Information permettant votre connexion</legend>
                    <div class="form-group">
                        {!! Form::email('email', '', ['class' => 'form-control required', 'placeholder' => 'Saisissez votre email']) !!}
                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre email.</p>
                    </div>

                    <div class="form-group">
                        {!! Form::password('password', ['class' => 'form-control required', 'placeholder' => 'Saisissez votre mot de passe']) !!}
                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre mot de passe.</p>
                    </div>

                    <div class="form-group">
                        {!! Form::password('confirm', ['class' => 'form-control required', 'placeholder' => 'Confirmez votre mot de passe']) !!}
                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre confirmation de mot de passe.</p>
                    </div>
                </fieldset>

                <fieldset class="form-group">
                    <legend>Souscription</legend>
                    <div class="form-group">
                        {!! Form::text('card_number', '', ['class' => 'form-control required', 'placeholder' => 'Numéro de carte']) !!}
                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre nom.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::text('exp_month', '', ['class' => 'form-control required', 'placeholder' => 'Expiration mois']) !!}
                                <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre nom.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::text('exp_year', '', ['class' => 'form-control required', 'placeholder' => 'Expiration année']) !!}
                                <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre nom.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::text('cvc', '', ['class' => 'form-control required', 'placeholder' => 'Code carte']) !!}
                                <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre nom.</p>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="form-group">
                    {!! Form::submit('Souscrire', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-c -->
@endsection

@section('seo')
    <title>Devenir membre...</title>
@stop