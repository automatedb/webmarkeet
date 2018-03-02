@extends('Layout.guest.content')

@section('content')
    <main id="stats">
        <header class="intro-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="intro-message">
                            <h1>Performances de trading</h1>
                            <h3>Accède en toute transparence aux performances de trading sur Robots Trading</h3>
                            <hr class="intro-divider">
                            <p class="lead">Si tu trouves que c'est cool, partage cette page :</p>

                            <div class="btn-share">
                                <p>
                                    <a href="{{ action('StatsCtrl@index') }}"
                                       data-image="{{ asset($thumbnail) }}"
                                       data-description="Accède en toute transparences aux statistiques des comptes de trading de Robots Trading"
                                       data-title="Performances de trading - Robots Trading"
                                       class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i> Partager sur Facebook</a>

                                    <a href="https://twitter.com/share?
                                url={{ action('StatsCtrl@index') }}
                                            &text=Performances de trading - Robots Trading"
                                       class="btn btn-social btn-twitter"><i class="fa fa-twitter"></i> Partager sur Twitter</a>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container -->
        </header>
        <!-- /.intro-header -->

        <section>
            <div class="container">
                <div class="clearfix"></div>
                <div class="row">
                    <div id="gains" class="col-md-12">
                        <p><strong>Les performances de trading</strong> ci-dessous sont issus soit de <strong>comptes de trading réels ou de démonstrations</strong>. Il faut donc prendre les performances en fonction du type de compte (mais le type de compte de trading est précisé).</p>
                        <p>Je ne trade pas manuellement. Les gains/pertes sont donc entièrement réalisés par la prise de positions automatiques.</p>
                        <p>J'utilise deux types d'algorithme de trading : </p>
                        <ul>
                            <li>Des robots trading commerciaux</li>
                            <li>Des algorithmes de trading personnels</li>
                        </ul>
                        <p>L'idée d'afficher mes performances de trading, n'est pas d'exposer mes performances de façon narcissique, mais de démontrer que <strong>le trading automatique peut être une activité professionnelle</strong> à part entière.</p>
                    </div>
                </div>
            </div>
            <!-- /.container -->
        </section>
        <!-- /.content-section-a -->

        <section>
            <div class="container">
                <div class="clearfix"></div>
                <div class="row">
                    <div id="gains" class="col-md-4">
                        <div id="account-name" class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ $stats[\App\Models\StatsTrading::NAME] }}</h5>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center {{ $stats[\App\Models\StatsTrading::GAINS] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($stats[\App\Models\StatsTrading::GAINS], 2) }}<small>%</small>
                                    <span>Gains</span></h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <strong>Solde du compte : {{ number_format($stats[\App\Models\StatsTrading::BALANCE], 2) }}€</strong>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Type de compte : {{ !$stats[\App\Models\StatsTrading::DEMO] ? 'Compte réel' : 'Compte de démonstration' }}</li>
                                <li class="list-group-item">Profit : {{ number_format($stats[\App\Models\StatsTrading::PROFITS], 2) }}€</li>
                                <li class="list-group-item">Drawdown : {{ number_format($stats[\App\Models\StatsTrading::DRAWDOWN], 2) }}%</li>
                                <li class="list-group-item">Pips : {{ number_format($stats[\App\Models\StatsTrading::PIPS], 2) }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container -->
        </section>
        <!-- /.content-section-a -->

        <div class="banner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <h4 class="text-center">Inscris-toi sur la chaîne</h4>

                        <div class="btn-share text-center">
                            <a href="https://www.youtube.com/channel/UCn8_Y5KCMtVqT7y7hcQku7g" target="_blank" class="btn btn-social btn-lg btn-google"><i class="fa fa-youtube"></i> Youtube de Robots Trading</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container -->
        </div>
        <!-- /.banner -->
    </main>
@endsection

@section('seo')
    <title>{!! config('app.name') !!} - {{ config('app.slogan') }}</title>
    <meta name="description" content="{{ config('app.description') }}">
    <meta property="og:title" content="{!! config('app.name') !!} - {{ config('app.slogan') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($thumbnail) }}">
@stop