@include('layouts.header')

<body>
    <!-- Begin page -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        @include('layouts.navbar')


    </header><!-- End Header -->
    @include('layouts.sidebar')
    <main id="main" class="main">
        @yield('content')
    </main><!-- End #main -->

    {{-- @include('layouts.footer') --}}
    @include('layouts.script')
