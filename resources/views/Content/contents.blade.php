@extends('Layout.admin.content')

@section('content')
    <section class="container">
        @widget('Alert', $alert)
    </section>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {{ link_to_action('ContentCtrl@add', 'Ajouter un contenu', [], [ 'class' => 'btn btn-primary' ]) }}
            @foreach($contents as $index => $content)
                <tr>
                    <th scope="row">{{ $index + 1 }}</th>
                    <td>{{ link_to_action('ContentCtrl@modify', $content->title, [ 'id' => $content->id ], []) }}</td>
                    <td>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal">
                            Supprimer
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Delete modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Suppression de contenu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Etes vous sur de vouloir supprimer ce contenu.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    {{ link_to_action('ContentCtrl@delete', 'Oui', [ 'id' => $content->id ], [ 'class' => 'btn btn-danger' ]) }}
                </div>
            </div>
        </div>
    </div>
@endsection