@extends('Layout.admin.content')

@section('content')
    {{ link_to_action('UserCtrl@logout', 'Déconnection') }}
@endsection