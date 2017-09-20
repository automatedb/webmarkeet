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
                    @if($content->type == 'CONTENT')
                        {!! link_to_action('ContentCtrl@content', "Voir le contenu", [ 'slug' => $content->slug ], [ 'class' => 'btn btn-secondary btn-block', 'target' => '_blank' ]) !!}
                    @endif

                    @if($content->type == 'TUTORIAL')
                        {!! link_to_action('ContentCtrl@tutorial', "Voir le contenu", [ 'slug' => $content->slug ], [ 'class' => 'btn btn-secondary btn-block', 'target' => '_blank' ]) !!}
                    @endif

                    @if($content->type == 'FORMATION')
                        {!! link_to_action('FormationCtrl@formation', "Voir le contenu", [ 'slug' => $content->slug ], [ 'class' => 'btn btn-secondary btn-block', 'target' => '_blank' ]) !!}
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::textarea('content', $content->content, ['class' => 'form-control', 'id' => 'editor']) !!}
            </div>

            @foreach($content->chapters as $index => $chapter)
                <div class="chapter">
                    <div class="form-group">
                        {!! Form::text(sprintf('chapters[%s][title]', $index), $chapter[\App\Models\Chapter::TITLE], ['class' => 'form-control required', 'placeholder' => "Saisissez un nom de chapitre..."]) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::textarea(sprintf('chapters[%s][content]', $index), $chapter[\App\Models\Chapter::CONTENT], ['class' => 'form-control']) !!}
                    </div>

                    <div class="row files">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="custom-file video-file col-md-12">
                                    {!! Form::file(sprintf('chapters[%s][%s]', $index, config('sources.type.VIDEO')), [ 'class' => 'custom-file-input', 'id' => 'video' ]) !!}
                                    <span class="custom-file-control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="custom-file source-file col-md-12">
                                    {!! Form::file(sprintf('chapters[%s][%s]', $index, config('sources.type.FILE')), [ 'class' => 'form-control', 'id' => 'sources' ]) !!}
                                    <span class="custom-file-control"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {!! Form::hidden(sprintf('chapters[%s][id]', $index), $chapter['id']) !!}
                </div>
            @endforeach

            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <a href="#" id="add-chapter" class="btn btn-primary btn-block btn-lg">Ajouter un chapitre</a>
                </div>
            </div>

            {!! Form::hidden('id', $content->id) !!}
        </div>
        <div class="col col-md-3">
            <div class="form-group">
                {!! Form::label('thumbnail', 'Illustration du contenu') !!}
                @if(!empty($content->thumbnail))
                    {!! Html::image(asset(sprintf('/img/%s', $content->thumbnail)), 'Thumbnail', [ 'class' => 'img-thumbnail' ]) !!}
                @endif
                <div class="form-group">
                    <label class="custom-file col-md-12">
                        {!! Form::file('thumbnail', [ 'class' => 'custom-file-input', 'id' => 'thumbnail' ]) !!}
                        <span class="custom-file-control"></span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('video_id', 'ID video youtube', [ 'for' => 'video_id' ]) !!}
                {!! Form::text('video_id', $content->video_id, ['class' => 'form-control', 'placeholder' => "Saisissez un ID Youtube..."]) !!}
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
        });
    </script>
@endpush