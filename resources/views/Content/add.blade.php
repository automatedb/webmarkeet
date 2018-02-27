@extends('Layout.admin.content')

@section('content')
    <section class="container">
        @widget('Alert', $alert)
    </section>

    {!! Form::open(['class' => 'row', 'files' => true, 'name' => 'global-form']) !!}
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
                <span class="custom-file-control" lang="{{ config('app.locale') }}"></span>
            </label>
        </div>

        {{--<div class="form-group">--}}
            {{--<a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#uploadModal">Uploader un vidéo</a>--}}
        {{--</div>--}}

        <div id="add-sources-files" class="form-group">
            {!! Form::label('sources', 'Associer des sources') !!}
            <label class="custom-file col-md-12">
                {!! Form::file('sources', [ 'class' => 'form-control', 'id' => 'sources', 'lang' => 'fr' ]) !!}
                <span class="custom-file-control" lang="{{ config('app.locale') }}"></span>
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

    {{--<!-- Modal -->--}}
    {{--<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">--}}
        {{--<div class="modal-dialog" role="document">--}}
            {{--{!! Form::open(['class' => 'row', 'files' => true, 'name' => 'upload-from']) !!}--}}
                {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                        {{--<h5 class="modal-title" id="exampleModalLabel">Choisir une vidéo...</h5>--}}
                        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                            {{--<span aria-hidden="true">&times;</span>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body">--}}
                        {{--<div class="form-group">--}}
                            {{--{!! Form::label('video', 'Séléctionner un vidéo') !!}--}}
                            {{--<label class="custom-file col-md-12">--}}
                                {{--{!! Form::file('video', [ 'class' => 'form-control required', 'id' => 'video' ]) !!}--}}
                                {{--<span class="custom-file-control" lang="{{ config('app.locale') }}"></span>--}}
                                {{--<p class="form-control-feedback hidden-xs-up">Merci séléctionner une vidéo.</p>--}}
                            {{--</label>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--{!! Form::text('title', '', ['class' => 'form-control required', 'placeholder' => "Saisissez un titre..."]) !!}--}}
                            {{--<p class="form-control-feedback hidden-xs-up">Merci d'entrer un titre.</p>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--{!! Form::textarea('content', '', ['class' => 'form-control']) !!}--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--{!! Form::text('tags', '', ['class' => 'form-control', 'placeholder' => "Saisissez une liste de mots clés (tag 1, tag 2...)"]) !!}--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--{!! Form::label('youtube-thumbnail', 'Séléctionner une illustration') !!}--}}
                            {{--<label class="custom-file col-md-12">--}}
                                {{--{!! Form::file('youtube-thumbnail', [ 'class' => 'form-control', 'id' => 'youtube-thumbnail' ]) !!}--}}
                                {{--<span class="custom-file-control" lang="{{ config('app.locale') }}"></span>--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
                        {{--<button type="submit" class="btn btn-primary">Uploader</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--{!! Form::close() !!}--}}
        {{--</div>--}}
    {{--</div>--}}
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

//            $('form[name="upload-from"]').on('submit', function(e) {
//                e.preventDefault();
//                e.stopPropagation();
//
//                if($(this).validForm(this, e)) {
//                    var data = new FormData();
//
//                    data.append('title', $(this).find('input[name="title"]').val());
//                    data.append('description', $(this).find('input[name="description"]').val());
//                    data.append('tags', $(this).find('input[name="tags"]').val());
//                    data.append('video', $(this).find('input[name="video"]').get(0).files[0]);
//                    data.append('thumbnail', $(this).find('input[name="thumbnail"]').get(0).files[0]);
//
//                    $.ajax({
//                        url: '/api/v1/upload/youtube',
//                        type: 'POST',
//                        data: data,
//                        cache: false,
//                        dataType: 'json',
//                        processData: false,
//                        contentType: false,
//                        success: function(result) {
//                          console.log(result);
//                        },
//                        error: function(result) {
//                          console.log(result);
//                        },
//                        complete: function() {
//                          console.log('complete');
//                        }
//                    })
//                }
//            });

            $('form[name="global-form"]').on('submit', function(e) {
                validForm(this, e);
            });
        });
    </script>
@endpush