@extends('Layout.guest.content')

@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            @forelse ($contents as $content)
                <article class="col-lg-8">
                    @widget('Img', [
                        'id' => $content->id,
                        'src' => $content->thumbnail,
                        'title' => $content->title,
                        'type' => 'post-list'
                    ])
                    <h2><a href="/blog/{{ $content->slug }}">{{ $content->title }}</a></h2>
                    <div class="row meta">
                        <div class="col-md-6">Date: {{ \Carbon\Carbon::parse($content->created_at)->format('d-m-Y') }}</div>
                    </div>
                    <p>{!! str_limit($content->content, 100) !!}</p>
                </article>
            @empty
                <p>Pas de contenu pour le moment</p>
            @endforelse
        </div>
    </div>
@endsection

@section('seo')
    <title>{!! $title !!}</title>
    <meta name="description" content="{!! $description !!}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($image) }}">
@stop
