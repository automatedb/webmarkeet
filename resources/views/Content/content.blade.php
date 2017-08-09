@extends('Layout.guest.content')

@section('content')
    @widget('BgHeader', [
            'content' => $content
        ])
    <div class="container post">
        <div class="row justify-content-md-center">
            <article class="col-lg-7">
                <div class="meta">Posté le {{ \Carbon\Carbon::parse($content->created_at)->format('d-m-Y') }}</div>
                <p>{!! $content->content !!}</p>
            </article>
            <aside class="col-md-3">
                <div class="card">
                    <div class="card-block text-center">
                        <h4>Devenir membre</h4>
                        <p class="lead">Téléchargez immédiatement de toutes les resources</p>
                        {!! link_to_action('UserCtrl@authentication', 'Devenir membre', [], [ 'class' => 'btn btn-success' ]) !!}
                    </div>
                </div>
            </aside>
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