@extends('Layout.guest.content')

@section('content')
    <header class="masthead" style="background-image: url('img/blog-thumbnail.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h1>Blog</h1>
                        <hr class="intro-divider">
                        <span class="subheading">Toutes les astuces, actualités et billets d'humeurs sont ici.</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                @forelse ($contents as $content)
                    <article>
                        @widget('Img', [
                            'id' => $content->id,
                            'src' => $content->thumbnail,
                            'title' => $content->title,
                            'type' => 'post-list'
                        ])
                        <h2>
                            <a href="/blog/{{ $content->slug }}">{{ $content->title }}</a>
                        </h2>
                        <div class="row meta">
                            <div class="col-md-6">
                                Date: <time datetime="{{ $content->created_at }}">{{ \Carbon\Carbon::parse($content->created_at)->format('d-m-Y') }}</time>
                            </div>
                        </div>
                        <p>{{ strip_tags(str_limit($content->content, 100)) }}</p>
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

@push('scripts')
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "WebSite",
        "headline": "Blog",
        "description": "Toutes les astuces, actualités et billets d'humeurs sont ici."
    }
    </script>

    <script type="application/ld+json">
    {
      "@context":"http://schema.org",
      "@type":"ItemList",
      "itemListElement":[
        @foreach($contents as $i => $content)
            @if($loop->last)
                {
                   "@type": "ListItem",
                   "position": {{ $i + 1 }},
                   "url": "{{ action('ContentCtrl@content', [ 'slug' => $content[\App\Models\Content::$SLUG] ]) }}"
                }
             @else
                {
                  "@type": "ListItem",
                  "position": {{ $i + 1 }},
                  "url": "{{ action('ContentCtrl@content', [ 'slug' => $content[\App\Models\Content::$SLUG] ]) }}"
                },
            @endif
        @endforeach
      ]
    }
    </script>
@endpush