@extends('Layout.content')

@section('content')
    {{ link_to_action('AdminCtrl@logout', 'Déconnection') }}
@endsection