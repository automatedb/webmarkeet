@extends('Layout.content')

@section('content')
    @foreach ($contents as $content)
        <h1><a href="/blog/{{ $content->slug }}">{{ $content->title }}</a></h1>
        <p>{{ str_limit($content->content, 200) }}</p>
    @endforeach
@endsection