@extends('Layout.guest.content')

@section('content')
    <header id="payment" class="masthead" style="background-image: url('/img/blog-thumbnail.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h2 class="text-center">Gestion de mon profil</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    @widget('Alert', $alert)

                    {!! Form::open(['action' => 'UserCtrl@modify']) !!}

                    <fieldset class="form-group">
                        <legend>Informations vous concernant</legend>

                        <div class="form-group">
                            {!! Form::text('firstname', $user->firstname, ['class' => 'form-control required']) !!}
                            <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre prénom.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::text('lastname', $user->lastname, ['class' => 'form-control required']) !!}
                            <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre nom.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::email('email', $user->email, ['class' => 'form-control required']) !!}
                            <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre email.</p>
                        </div>
                    </fieldset>

                    <fieldset class="form-group">
                        <legend>Informations permettant votre changement de mot de passe</legend>

                        <div class="form-group">
                            {!! Form::password('oldpassword',  ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::password('confirm', ['class' => 'form-control']) !!}
                        </div>
                    </fieldset>

                    <div class="form-group">
                        {!! Form::submit('Mettre à jour', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>

                    {!! Form::close() !!}
            </div>
        </div>
    </section>

    @if(!$user->subscribed('monthly'))
        <section>
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-lg-8">
                        <h2 class="text-left">Gestion de mon abonnement</h2>
                        <hr>
                        <div class="card card-outline-success mb-3 text-center">
                            <div class="card-block">
                                <div class="list-group">
                                    <div class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Souscrire à l'abonnement {{ config('app.name') }}</h5>
                                            <button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#subscribe-modal">
                                                Souscrire
                                            </button>
                                        </div>
                                        <p class="mb-1">Cet espace vous permet de renouveler votre abonnement de {{ config('subscription.price') }}<small>{{ config('subscription.devise') }}</small>/{{ config('subscription.period') }}.</p>
                                        <small class="text-muted text-left">En vous abonnant vous pouvez accéder à tous les codes sources associés aux tutoriels. Vous pouvez aussi accéder et télécharger toutes les formations vidéos ainsi que télécharger les codes sources associés.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    <h2 class="text-left">Zone de danger</h2>
                    <hr>
                    <p>Attention cette partie vous permet d'effectuer des actions qui ont des impacts sur l'utilisation de votre compte. Certaines actions ont sont immédiates et irréversible</p>
                    <div class="card card-outline-danger mb-3 text-center">
                        <div class="card-block">
                            <div class="list-group">
                                @if($user->subscribed('monthly'))
                                    <div class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Annulation de mon abonnement</h5>
                                            <button type="button" class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#unsubscribe-modal">
                                                Stopper mon abonnement
                                            </button>
                                        </div>
                                        <p class="mb-1">Cet espace vous permet d'annuler votre abonnement de {{ config('subscription.price') }}<small>{{ config('subscription.devise') }}</small>/{{ config('subscription.period') }}.</p>
                                        <small class="text-muted text-left">Cette annulation est effective à la fin de la période courante. A la fin de la période, il ne vous sera plus possible d'accéder aux ressources de {{ config('app.name') }}. Vous perdez aussi les privilèges de code promotionnel si vous en avez eu lors de votre souscription.</small>
                                    </div>
                                @endif

                                @if($user->role !== 'admin')
                                    <div class="list-group-item list-group-item-action flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Suppression de mon compte</h5>
                                            <button type="button" class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#delete-modal">
                                                Supprimer mon compte
                                            </button>
                                        </div>
                                        <p class="mb-1">Cet espace vous permet de fermer votre compte.</p>
                                        <small class="text-muted text-left">Cette suppression est immédiate. Elle entraine aussi l'annulation de votre abonnement. Vous perdez aussi accès à toutes les ressources et toutes les formations achetées.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation de suppression de profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Je suis conscience qu'en supprimant mon compte je perds aussi tous mes accès aux formations vidéos et aux codes sources.</p>
                    <p>Je comprends aussi que la suppression de mon compte sera effective et irréversible à la fin de la période à laquelle vous avez souscrit après avoir cliqué sur "Je confirme".</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    {{ link_to_action('UserCtrl@delete', 'Je confirme', [], ['class' => 'btn btn-danger']) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="unsubscribe-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmation de d'annulation d'abonnement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Je suis conscience qu'en stoppant mon abonnement je perds aussi tous mes accès aux formations vidéos, aux codes sources et code promotionnel que j'aurai pu bénéficier.</p>
                    <p>Je comprends aussi que cette annulation sera effective et irréversible immédiatement après avoir cliqué sur "Je confirme". </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    {{ link_to_action('UserCtrl@unSubscribe', 'Je confirme', [], ['class' => 'btn btn-danger']) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="subscribe-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Renouveller mon abonnement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['action' => 'UserCtrl@postRenewalSubscription']) !!}
                    <div class="modal-body">
                            <div class="hidden-xs-up alert alert-info">Merci de patienter, nous traitons votre demande. Cela peut prendre quelques instants.</div>

                            <div class="form-group">
                                {!! Form::text('card_number', '', ['class' => 'form-control required', 'placeholder' => 'Numéro de carte']) !!}
                                <p class="form-control-feedback hidden-xs-up">Merci d'entrer le numéro de carte.</p>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::text('exp_month', '', ['class' => 'form-control required', 'placeholder' => 'Exp. mois']) !!}
                                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer le mois d'expiration.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::text('exp_year', '', ['class' => 'form-control required', 'placeholder' => 'Exp. année']) !!}
                                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer l'année d'expiration.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::text('cvc', '', ['class' => 'form-control required', 'placeholder' => 'Code carte']) !!}
                                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer le code au dos de votre carte.</p>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success"><i class="hidden-xs-up fa fa-spinner fa-spin fa-fw"></i> <span class="hidden-xs-up sr-only">Loading...</span> Je confirme</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('seo')
    <title>{{ config('app.name') }} - Gestion de mon profil</title>
@stop

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function() {
            $('#subscribe-modal button[type=submit]').on('click', function(e) {
                var that = $(this);
                that.attr('disabled', 'disabled');
                that.find('.hidden-xs-up').removeClass('hidden-xs-up');
                $('#subscribe-modal .alert').removeClass('hidden-xs-up');

                $('.required').each(function(i, el) {
                    var value = $(el).val();
                    var hasError = false;

                    $(el).parent().removeClass('has-danger');
                    $(el).parent().find('.form-control-feedback').addClass('hidden-xs-up');

                    if($.trim(value) === '') {
                        hasError = true;

                        $(el).parent().addClass('has-danger');
                        $(el).parent().find('.form-control-feedback').removeClass('hidden-xs-up');
                    }

                    if(!hasError) {
                        $('#subscribe-modal form').submit();
                    } else {
                        that.removeAttr('disabled');
                        that.find('.fa-spinner').addClass('hidden-xs-up');
                        $('#subscribe-modal .alert').addClass('hidden-xs-up');
                    }
                });
            });
        });
    </script>
@endpush