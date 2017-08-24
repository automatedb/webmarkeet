@extends('Layout.guest.content')

@section('content')
    <header class="masthead" style="background-image: url('img/blog-thumbnail.jpg')" itemscope itemtype="https://schema.org/WebSite">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h1 itemprop="headline">Blog</h1>
                        <hr class="intro-divider">
                        <span  itemprop="description" class="subheading">Toutes les astuces, actualités et billets d'humeurs sont ici.</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                @forelse ($contents as $content)
                    <article itemscope itemtype="http://schema.org/Article">
                        <meta itemprop="author" content="Robots Trading" />
                        <span itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                            @widget('Img', [
                                'id' => $content->id,
                                'src' => $content->thumbnail,
                                'title' => $content->title,
                                'type' => 'post-list'
                            ])
                        </span>
                        <h2 itemprop="name headline">
                            <a itemprop="url" href="/blog/{{ $content->slug }}">{{ $content->title }}</a>
                        </h2>
                        <div class="row meta">
                            <time datetime="{{ $content->created_at }}" itemprop="datePublished" class="col-md-6">Date: {{ \Carbon\Carbon::parse($content->created_at)->format('d-m-Y') }}</time>
                        </div>
                        <p itemprop="description">{!! str_limit($content->content, 100) !!}</p>
                    </article>
                @empty
                    <p>Pas de contenu pour le moment</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('seo')
    <title>{!! $title !!}</title>
    <meta name="description" content="{!! $description !!}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($thumbnail) }}">
@stop
