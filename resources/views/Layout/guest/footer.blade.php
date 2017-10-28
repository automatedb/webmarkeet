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

    @widget('Cookies')

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script type="application/javascript">
        var config = {
            analytics: {
                codes: {
                    google: '{{ config('analytics.codes.google') }}',
                    facebook: '{{ config('analytics.codes.facebook') }}'
                },
                cookies: {
                    cnil: {
                        days: {{ config('analytics.cookies.cnil.days') }},
                        name: '{{ config('analytics.cookies.cnil.name') }}',
                        value: '{{ config('analytics.cookies.cnil.value') }}'
                    },
                    facebook_refuse_registration: {
                        days: {{ config('analytics.cookies.facebook_refuse_registration.days') }},
                        name: '{{ config('analytics.cookies.facebook_refuse_registration.name') }}',
                        value: '{{ config('analytics.cookies.facebook_refuse_registration.value') }}'
                    }
                }
            },
            social: {
                api: {
                    facebook: {
                        public_id: '{{ config('social.api.facebook.public_id') }}'
                    }
                }
            }
        };

        @if(!empty($referer))
            config.hasReferer = true;
            config.popin = {
                display_after: {{ config('popin.display_after') }}
            };
        @endif
    </script>

    {!! Html::script('/js/app.js', [], env('APP_ENV') == 'production') !!}

    @stack('scripts')

    @if(env('APP_ENV') == 'production')
        {!! Html::script('/js/analytics.js', [], env('APP_ENV') == 'production') !!}
    @endif
    </body>
</html>
