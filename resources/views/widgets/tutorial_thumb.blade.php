<a href="{{ action('ContentCtrl@tutorial', [ 'slug' => $config['slug'] ]) }}">
    <div id="tutorial-thumb" style="background-image: url('{{ $config['src'] }}')">
        <h2>{{ $config['title'] }}</h2>
    </div>
</a>
