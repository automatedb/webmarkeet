@extends('Layout.guest.content')

@section('content')
    <header class="masthead" style="background-image: url('/img/blog-thumbnail.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h2>Merci pour votre souscription</h2>
                        <hr class="intro-divider">
                        <h3>Votre compte est prêt</h3>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="login-thanks-form">
        <div class="container">
            <h3 class="section-heading">Vous pouvez vous connecter</h3>
            <div class="clearfix"></div>
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <div class="row justify-content-md-center">
                <div class="col-md-5">
                    <div class="btn-share text-center">
                        <p>Avant de vous connecter, vous pouvez faire connaître {{ config('app.name') }} en cliquant sur un des boutons ci-dessous : </p>
                        <p>
                            <a href="{{ config('app.url') }}"
                               data-image="{{ asset(config('app.thumbnail')) }}"
                               data-description="{{ config('app.description') }}"
                               data-title="{{ config('app.name') }}"
                               class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i> Partager sur Facebook</a>

                            <a href="https://twitter.com/share?
                            url={{ config('app.url') }}
                                    &text={{ urlencode(sprintf('%s - %s : %s', config('app.name'), config('app.slogan'), config('app.url'))) }}"
                               class="btn btn-social btn-twitter"><i class="fa fa-twitter"></i> Partager sur Twitter</a>

                        </p>
                    </div>

                    @widget('alert', $alert)

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
                        {!! Form::submit('Connexion', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-c -->

@endsection

@section('seo')
    <title>Merci de votre inscription</title>
@stop

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css" integrity="sha256-rFMLRbqAytD9ic/37Rnzr2Ycy/RlpxE5QH52h7VoIZo=" crossorigin="anonymous" />
    {!! Html::style('/css/libs.css') !!}
@endpush
