@if(!empty($config['message']))
    <div class="alert alert-{{ $config['type'] }}">
        @if(is_array($config['message']))
            @foreach($config['message'] as $key => $messages)
                <ul>
                    @foreach($messages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endforeach
        @else
            {{ $config['message'] }}
        @endif
    </div>
@endif