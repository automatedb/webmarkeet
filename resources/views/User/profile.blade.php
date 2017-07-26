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
                    @if($user->role !== 'admin')
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal">
                            Supprimer mon compte
                        </button>
                    @endif
                    {{ link_to_action('UserCtrl@logout', 'Me déconnecter', [], ['class' => 'btn btn-warning']) }}
                    {!! Form::submit('Mettre à jour', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Je suis conscience qu'en supprimant mon compte je perds aussi tous mes accès aux formations vidéos et aux codes sources.</p>
                    <p>Je comprends aussi que la suppression de mon compte sera effective et irreversible immédiatement après avoir cliqué sur "Je confirme". </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    {{ link_to_action('UserCtrl@delete', 'Je confirme', [], ['class' => 'btn btn-danger']) }}
                </div>
            </div>
        </div>
    </div>
@endsection