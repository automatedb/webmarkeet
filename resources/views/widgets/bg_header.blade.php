<header style="background-image: url('{{ $config['src'] }}')">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-10">
                <h1 class="text-center">{{ $config['title'] }}</h1>
            </div>
            @if($config['sources']->count())
                <div class="col-md6">
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <a href="{{ action('ContentCtrl@downloadSources', $config['slug']) }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Télécharger les sources</a>
                    @else
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