@extends('Layout.guest.content')

@section('content')
    <header class="masthead" style="background-image: url('/img/blog-thumbnail.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h1>404</h1>
                        <hr class="intro-divider">
                        <span class="subheading">Ooups, un problème est arrivé, <br> nous n'avons pas trouvé la ressource que vous recherchez.</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="section-404">
        <div class="container">
            <hr class="section-heading-spacer">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <p class="lead text-center">Vous pouvez toujours faire un tour sur <a href="{{ action('ContentCtrl@index') }}" title="le blog">le blog</a> ou sur
                        <a href="{{ action('ContentCtrl@tutorials') }}">les tutoriels</a>.</p>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.content-section-a -->
@endsection