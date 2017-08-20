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

        <div class="row justify-content-md-center">
            <div class="col-md-6">
                <a href="#" id="add-chapter" class="btn btn-primary btn-block btn-lg">Ajouter un chapitre</a>
            </div>
        </div>
    </div>
    <div class="col col-md-3">

        <div class="form-group">
            {!! Form::label('thumbnail', 'Illustration du contenu') !!}
            <label class="custom-file col-md-12">
                {!! Form::file('thumbnail', [ 'class' => 'form-control', 'id' => 'thumbnail' ]) !!}
                <span class="custom-file-control"></span>
            </label>
        </div>

        <div class="form-group">
            {!! Form::label('video_id', 'ID video youtube', [ 'for' => 'video_id' ]) !!}
            {!! Form::text('video_id', '', ['class' => 'form-control', 'placeholder' => "Saisissez un ID Youtube..."]) !!}
        </div>

        <div id="add-sources-files" class="form-group">
            {!! Form::label('sources', 'Associer des sources') !!}
            <label class="custom-file col-md-12">
                {!! Form::file('sources', [ 'class' => 'form-control', 'id' => 'sources' ]) !!}
                <span class="custom-file-control"></span>
            </label>
        </div>

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
    {!! Html::script('/js/jquery-initialize.min.js', [], env('APP_ENV') == 'production') !!}

    <script type="application/javascript">
        new SimpleMDE({
            element: $('#editor')[0],
            showIcons: ["code"]
        });

        if (!String.format) {
            String.format = function(format) {
                var args = Array.prototype.slice.call(arguments, 1);
                return format.replace(/{(\d+)}/g, function(match, number) {
                    return typeof args[number] != 'undefined'
                        ? args[number]
                        : match
                        ;
                });
            };
        }

        $(document).ready(function() {
            if($('.chapter').length) {
                $('#add-sources-files').addClass('hidden-md-up')
            }

            $('#add-chapter').on('click', function(e) {
                e.preventDefault();

                if($('#add-sources-files').is(':visible')) {
                    $('#add-sources-files').addClass('hidden-md-up');
                }

                var content = '\
                 <div class="chapter">\
                    <div class="form-group"> \
                        {!! Form::text('chapters[{0}][title]', '', ['class' => 'form-control required', 'placeholder' => "Saisissez un nom de chapitre..."]) !!} \
                    </div> \
                    <div class="form-group"> \
                        {!! Form::textarea('chapters[{0}][content]', '', ['class' => 'form-control']) !!} \
                    </div> \
                    <div class="row files"> \
                        <div class="col-md-6"> \
                            <div class="form-group"> \
                                <label class="custom-file video-file col-md-12"> \
                                    {!! Form::file(sprintf('chapters[{0}][%s]', config('sources.type.VIDEO')), [ 'class' => 'custom-file-input', 'id' => 'video' ]) !!} \
                                <span class="custom-file-control"></span> \
                            </label> \
                            </div> \
                        </div> \
                        <div class="col-md-6"> \
                            <div class="form-group"> \
                                <label class="custom-file source-file col-md-12"> \
                                    {!! Form::file(sprintf('chapters[{0}][%s]', config('sources.type.FILE')), [ 'class' => 'form-control', 'id' => 'sources' ]) !!} \
                                <span class="custom-file-control"></span> \
                            </label> \
                            </div> \
                        </div> \
                    </div>\
                    {!! Form::hidden('chapters[{0}][id]', '') !!} \
                 </div>';

                var chapters = $('.chapter');

                console.log(chapters.length);

                content = String.format(content, chapters.length);

                if(chapters.length) {
                    $(content).insertAfter(chapters.last());
                } else {
                    $(content).insertAfter($('#editor').parent('div'));
                }

            });

            $('.chapter').initialize(function(i, el) {
                new SimpleMDE({
                    element: $(el).find('textarea')[0],
                    showIcons: ["code"]
                });
            });
        });
    </script>
@endpush