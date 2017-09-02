    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline text-center">
                        @foreach(config('social.brand') as $brand)
                            @if(!empty($brand['url']))
                                <li class="list-inline-item">
                                    <a href="{{ $brand['url'] }}" target="_blank">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa {{ $brand['css'] }} fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <p class="copyright text-muted small text-center">Copyright &copy; {{ config('app.name') }} 2017. All Rights Reserved</p>
                    <p class="copyright text-muted small text-center">{{ config('app.notice') }}</p>
                </div>
            </div>
        </div>
    </footer>
    </body>
</html>
