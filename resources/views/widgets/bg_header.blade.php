<header style="background-image: url('{{ $config['content']['thumbnail'] }}')">
    <div class="container">
        <div class="row justify-content-md-center">
            @if($config['content']['type'] == \App\Models\Content::TUTORIAL)
                <div id="playing-video" class="col-lg-10 text-center">
                    <a href="#tutorial-video">
                        <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                    </a>

                    <div id="tutorial-video" class="hidden-xl-down embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
            <div class="col-lg-10">

                <h1 class="text-center">{{ $config['content']['title'] }}</h1>
            </div>
            @if($config['content']['sources']->count())
                <div id="download-btn" class="col-md-6 text-center">
                    @if(\Illuminate\Support\Facades\Auth::check())
{{--                        <a href="{{ action('ContentCtrl@downloadSources', ['slug' => $config['content']['slug'], 'type' => 'video']) }}" class="btn btn-success"><i class="fa fa-play-circle" aria-hidden="true"></i> Télécharger la vidéo</a>--}}
                        <a href="{{ action('ContentCtrl@downloadSources', ['slug' => $config['content']['slug']]) }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Télécharger les sources</a>
                    @else
{{--                        <a href="{{ action('UserCtrl@authentication') }}" class="btn btn-success"><i class="fa fa-play-circle" aria-hidden="true"></i> Télécharger la vidéo</a>--}}
                        <a href="{{ action('UserCtrl@authentication') }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Télécharger les sources</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</header>

@push('styles')
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endpush

@push('scripts')
    <script type="application/javascript">
        var url = '//www.youtube.com/embed/{{ $config['content']['video_id'] }}?rel=0&autoplay=1';

        $(document).ready(function() {
            $('a[href="#tutorial-video"]').on('click', function(e) {
                e.preventDefault();

                $('#playing-video .embed-responsive-item').attr('src', url);

                $(this).addClass('hidden-xl-down');
                $('#tutorial-video').removeClass('hidden-xl-down');
            });
        });
    </script>
@endpush