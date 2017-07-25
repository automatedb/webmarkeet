@extends('Layout.content')

@section('content')
    <img src="{{ $content->thumbnail }}" alt="">
    <h1>{{ $content->title }}</h1>
    <p>{{ $content->content }}</p>
@endsection