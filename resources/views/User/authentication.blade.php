@extends('Layout.guest.content')


@section('content')
    <section class="container">
        @widget('Alert', $alert)
    </section>

    <section class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-8">
                {!! Form::open(['action' => 'UserCtrl@postAuthentication']) !!}

                <div class="form-group">
                    {!! Form::text('email', '', ['class' => 'form-control required']) !!}
                    <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre email.</p>
                </div>
                <div class="form-group">
                    {!! Form::password('password', ['class' => 'form-control required']) !!}
                    <p class="form-control-feedback hidden-xs-up">Merci d'entrer votre mot de passe.</p>
                </div>
                <div class="form-group">
                    {!! Form::submit('Connexion', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection