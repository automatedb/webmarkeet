@extends('Layout.admin.content')

@section('content')
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
                        {{ link_to_action('ContentCtrl@delete', 'Supprimer', [ 'id' => $content->id ], [ 'class' => 'btn btn-danger' ]) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection