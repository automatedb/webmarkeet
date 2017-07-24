@extends('Layout.content')


@section('content')
    @widget('Alert', $alert)
    {!! Form::open(['action' => 'UserCtrl@postAuthentication']) !!}

    <div class="form-group">
        {!! Form::text('email', 'john.doe@domain.tld', ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::password('password', ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Connexion', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection