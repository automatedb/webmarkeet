@if(!empty($config['message']))
    <div class="alert alert-{{ $config['type'] }}">
        {{ $config['message'] }}
    </div>
@endif