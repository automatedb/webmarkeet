@extends('Layout.guest.content')

@section('content')
    <div class="container">
        {!! Html::image($content->thumbnail, $content->title, [ 'class' => 'img-fluid' ]) !!}
        <h1>{{ $content->title }}</h1>
        <p>{!! $content->content !!}</p>
    </div>
@endsection

@push('styles')
    {!! Html::style('/css/libs.css') !!}
@endpush

@push('scripts')
    {!! Html::script('/js/prism.js') !!}
@endpush