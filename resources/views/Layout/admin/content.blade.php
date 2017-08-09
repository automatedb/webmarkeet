@include('Layout.admin.header')

<div class="container-fluid">
    <div id="sidebar" class="col-lg-3">
        @include('Layout.admin.sidebar')
    </div>
    <section id="content" class="col-lg-9">
        @yield('content')
    </section>
</div>

@include('Layout.admin.footer')