@extends('Layout.guest.content')

@section('content')
    @widget('BgHeader', [
            'id' => $content->id,
            'src' => $content->thumbnail,
            'title' => $content->title
        ])
    <div class="container post">
        <div class="row justify-content-md-center">
            <article class="col-lg-8">
                <div class="meta">Posté le {{ \Carbon\Carbon::parse($content->created_at)->format('d-m-Y') }}</div>
                <p>{!! $content->content !!}</p>
            </article>
        </div>
    </div>
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