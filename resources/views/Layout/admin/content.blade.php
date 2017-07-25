@include('Layout.admin.header')

<div class="container-fluid">
    <div id="sidebar" class="col-md-4">
        @include('Layout.admin.sidebar')
    </div>
    <section id="content" class="col-md-8">
        @yield('content')
    </section>
</div>

@include('Layout.admin.footer')