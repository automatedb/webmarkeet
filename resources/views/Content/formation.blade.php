@extends('Layout.guest.content')

@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="clearfix"></div>
                <div class="row">
                    <h1>{{ $content[\App\Models\Content::$TITLE] }}</h1>
                    <div class="col-md-12">
                        {!! $content[\App\Models\Content::$CONTENT] !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @forelse($content['chapters'] as $chapter)
        <section class="chapter">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-8">
                        <hr class="section-heading-spacer">
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <h2>{{ $chapter[\App\Models\Chapter::TITLE] }}</h2>

                                @foreach($chapter['sources'] as $source)
                                    @if($source[\App\Models\Source::TYPE] == config('sources.type.VIDEO'))
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <video controls class="embed-responsive-item" controlsList="nodownload">
                                                <source src="{{ action('FormationCtrl@video', [ 'slug' => $source[\App\Models\Source::NAME]]) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="row justify-content-md-center btn-downloads">
                            @foreach($chapter['sources'] as $source)
                                @if($source[\App\Models\Source::TYPE] == config('sources.type.FILE'))
                                    <a href="{{ action('ContentCtrl@downloadChaptersSources', [ 'id' => $chapter['id'], 'type' => $source[\App\Models\Source::TYPE] ]) }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Télécharger les sources</a>
                                @endif
                            @endforeach

                            @foreach($chapter['sources'] as $source)
                                @if($source[\App\Models\Source::TYPE] == config('sources.type.VIDEO'))
                                    <a href="{{ action('ContentCtrl@downloadChaptersSources', [ 'id' => $chapter['id'], 'type' => $source[\App\Models\Source::TYPE] ]) }}" class="btn btn-success"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Télécharger la formation</a>
                                @endif
                            @endforeach

                            <div class="col-md-12 explains">
                                {!! Markdown::convertToHtml($chapter[\App\Models\Chapter::CONTENT]) !!}
                            </div>

                            <div class="text-center">
                                <a class="more" href="#">Tout afficher</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @empty
        <p>Aucun chapitre pour cette formation</p>
    @endforelse
@endsection

@section('seo')
    <title>{{ $content[\App\Models\Content::$TITLE] }}</title>
@stop

@push('styles')
    {!! Html::style('/css/libs.css') !!}
@endpush

@push('scripts')
    {!! Html::script('/js/prism.js') !!}

    <script type="application/javascript">
        $(document).ready(function() {
            $('.more').on('click', function(e) {
                e.preventDefault();

                $(this).addClass('hidden-md-up');
                $(this).parents('.chapter').find('.explains').addClass('displaying');
            });
        })
    </script>
@endpush