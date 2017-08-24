@extends('Layout.guest.content')

@section('content')
    <main class="content" itemscope itemtype="http://schema.org/BlogPosting">
        @widget('BgHeader', [
                'content' => $content
            ])
        <div class="container post">
            <div class="row justify-content-md-center">
                <article class="col-lg-8">
                    <div itemprop="articleBody">
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
@endpush