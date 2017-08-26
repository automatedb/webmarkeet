@extends('Layout.guest.content')

@section('content')
    <main class="content">
        @widget('BgHeader', [
                'content' => $content
            ])
        <div class="container post">
            <div class="row justify-content-md-center">
                <article class="col-lg-8">
                    <div>
                        {!! $content->content !!}
                    </div>
                </article>
            </div>
        </div>
    </main>
@endsection

@section('seo')
    <title>{!! $title !!}</title>
    <meta name="description" content="{!! $description !!}">
    <meta property="og:title" content="{!! $title !!}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($image) }}">
@stop

@push('styles')
    {!! Html::style('/css/libs.css') !!}
@endpush

@push('scripts')
    {!! Html::script('/js/prism.js') !!}

    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Article",
        "headline": "{{ $title }}",
        "description": "{{ $description }}",
        "datePublished": "{{ $content[\App\Models\Content::$POSTED_AT] }}",
        "dateModified": "{{ $content['updated_at'] }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ asset($content[\App\Models\Content::$SLUG]) }}"
        },
        "image": {
            "@type": "ImageObject",
            "url": "{{ asset($content[\App\Models\Content::$THUMBNAIL]) }}",
            "height": "1080",
            "width": "700"
        },
        "author": {
            "@type": "Person",
            "name": "{{ config('app.name') }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "{{ config('app.name') }}",
            "logo": {
                  "@type": "ImageObject",
                  "url": "{{ asset(config('app.thumbnail')) }}",
                  "width": "2000",
                  "height": "1333"
            }
        },
        "video": {
            "@type": "VideoObject",
            "url": "https://www.youtube.com/watch?v={{ $content['video_id'] }}",
            "name": "{{ $title }}",
            "thumbnailUrl": "{{ asset($content['thumbnail']) }}",
            "description": "{{ $description }}",
            "uploadDate": "{{ $content[\App\Models\Content::$POSTED_AT] }}"
        }
    }
    </script>
@endpush