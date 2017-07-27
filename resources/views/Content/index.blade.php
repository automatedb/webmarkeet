@extends('Layout.guest.content')

@section('content')
    <div class="container">
        @foreach ($contents as $content)
            {!! Html::image($content->thumbnail, $content->title, [ 'class' => 'img-fluid' ]) !!}
            <h1><a href="/blog/{{ $content->slug }}">{{ $content->title }}</a></h1>
            <p>{!! str_limit($content->content, 200) !!}</p>
        @endforeach
    </div>
@endsection