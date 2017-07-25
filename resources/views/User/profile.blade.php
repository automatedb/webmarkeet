@extends('Layout.admin.content')

@section('content')
    <section class="container">
        @widget('Alert', $alert)
    </section>

    <section class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-10">
                {!! Form::open(['action' => 'UserCtrl@modify']) !!}

                <div class="form-group">
                    {!! Form::text('firstname', $user->firstname, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::text('lastname', $user->lastname, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::email('email', $user->email, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::password('oldpassword',  ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::password('confirm', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {{ link_to_action('UserCtrl@delete', 'Supprimer mon compte', [], ['class' => 'btn btn-danger']) }}
                    {!! Form::submit('Mettre à jour', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection