@extends('Layout.guest.content')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h1 class="text-center">Merci pour votre souscription</h1>
            <p class="lead text-center">Votre compte est prêt, vous pouvez vous y connecter dès à présent.</p>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-md-5">
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
@endsection