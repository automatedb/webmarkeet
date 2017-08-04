@extends('Layout.admin.content')

@section('content')
    <section class="container">
        @widget('Alert', $alert)
    </section>

    {!! Form::open(['class' => 'row', 'files' => true]) !!}
    <div class="col col-md-9">
        <div class="form-group">
            {!! Form::text('title', '', ['class' => 'form-control required', 'placeholder' => "Saisissez un titre...", "data-slugger" => ""]) !!}
            <p class="form-control-feedback hidden-xs-up">Merci d'entrer un titre.</p>
        </div>

        <div class="form-group">
            {!! Form::text('slug', '', ['class' => 'form-control required', 'placeholder' => "Saisissez l'url de votre contenu..."]) !!}
            <p class="form-control-feedback hidden-xs-up">Merci d'entrer un slug.</p>
        </div>

        <div class="form-group">
            {!! Form::textarea('content', '', ['class' => 'form-control', 'id' => 'editor']) !!}
        </div>
    </div>
    <div class="col col-md-3">

        <div class="form-group">
            {!! Form::label('thumbnail', 'Illustration du contenu') !!}
            {!! Form::file('thumbnail', [ 'class' => 'form-control', 'id' => 'thumbnail' ]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('video_id', 'ID video youtube', [ 'for' => 'video_id' ]) !!}
            {!! Form::text('video_id', '', ['class' => 'form-control', 'placeholder' => "Saisissez un ID Youtube..."]) !!}
        </div>

        {{--<div class="form-group">--}}
            {{--{!! Form::label('type', 'Type de contenu') !!}--}}
            {{--{!! Form::select('type', $types, '', [ 'class' => 'form-control', 'id' => 'type' ]) !!}--}}
        {{--</div>--}}

        <div class="form-group">
            {!! Form::label('status', 'Status de publication', [ 'for' => 'status' ]) !!}
            {!! Form::select('status', $status, \App\Models\Content::DRAFT, [ 'class' => 'form-control', 'id' => 'status' ]) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary btn-block']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('styles')
{!! Html::style('https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css') !!}
@endpush

@push('scripts')
    {!! Html::script('https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js') !!}
    {!! Html::script('/js/jquery.slugger.min.js') !!}

    <script type="application/javascript">
        new SimpleMDE({
            element: $("#editor")[0],
            showIcons: ["code"]
        });
    </script>
@endpush