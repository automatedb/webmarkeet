@extends('Layout.admin.content')

@section('content')
    <section class="container">
        @widget('Alert', $alert)
    </section>

    <div class="row form-group">
        <div class="col-md-12">
            {{ link_to_action('ContentCtrl@add', 'Ajouter un contenu', [], [ 'class' => 'btn btn-primary' ]) }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Titre</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contents as $index => $content)
                        <tr>
                            <th class="text-center" scope="row">{{ $index + 1 }}</th>
                            <td>
                                {{ link_to_action('ContentCtrl@modify', $content->title, [ 'id' => $content->id ], []) }}<br>
                                <small>{{ $content->type }} - {{ $content->status }}</small>
                            </td>
                            <td class="align-middle text-center">
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

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