@extends('Layout.admin.content')

@section('content')
    <div class="row form-group">
        <div class="col-md-12">
            @widget('Alert', $alert)
        </div>
    </div>

    {!! Form::open(['class' => 'row', 'files' => true]) !!}
        <div class="col col-md-9">
            <div class="form-group">
                {!! Form::text('title', $content->title, ['class' => 'form-control required', 'placeholder' => "Saisissez un titre..."]) !!}
                <p class="form-control-feedback hidden-xs-up">Merci d'entrer un titre.</p>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        {!! Form::text('slug', $content->slug, ['class' => 'form-control required', 'placeholder' => "Saisissez l'url de votre contenu..."]) !!}
                        <p class="form-control-feedback hidden-xs-up">Merci d'entrer un slug.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    {!! link_to_action('ContentCtrl@content', "Voir le contenu", [ 'slug' => $content->slug ], [ 'class' => 'btn btn-secondary btn-block', 'target' => '_blank' ]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::textarea('content', $content->content, ['class' => 'form-control', 'id' => 'editor']) !!}
            </div>

            {!! Form::hidden('id', $content->id) !!}
        </div>
        <div class="col col-md-3">

            <div class="form-group">
                {!! Form::label('thumbnail', 'Illustration du contenu') !!}
                @if(!empty($content->thumbnail))
                    {!! Html::image(asset(sprintf('/img/%s', $content->thumbnail)), 'Thumbnail', [ 'class' => 'img-thumbnail' ]) !!}
                @endif
                {!! Form::file('thumbnail', [ 'class' => 'form-control', 'id' => 'thumbnail' ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('video_id', 'ID video youtube', [ 'for' => 'video_id' ]) !!}
                {!! Form::text('video_id', $content->video_id, ['class' => 'form-control', 'placeholder' => "Saisissez un ID Youtube..."]) !!}
            </div>

            {{--<div class="form-group">--}}
                {{--{!! Form::label('type', 'Type de contenu') !!}--}}
                {{--{!! Form::select('type', $types, $content->type, [ 'class' => 'form-control', 'id' => 'type' ]) !!}--}}
            {{--</div>--}}

            <div class="form-group">
                {!! Form::label('status', 'Status de publication', [ 'for' => 'status' ]) !!}
                {!! Form::select('status', $status, $content->status, [ 'class' => 'form-control', 'id' => 'status' ]) !!}
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

    <script type="application/javascript">
        new SimpleMDE({
            element: $("#editor")[0],
            showIcons: ["code"]
        });
    </script>
@endpush