@extends('Layout.content')

@section('content')
    <h1>{{ $content->title }}</h1>
    <p>{{ $content->content }}</p>
@endsection